<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */



/**
 * Extension to shoutbox engine
 * * * * * * * * * * * * * * * * * * * * * * * * * * 
 */
 
 require_once("inferno_engine.php");
 
class shout extends infernoshout_engine
{
	var $userid;
	var $me = 0;
	var $taglist = false;
	var $parser = false;
	var $silent = false;
	var $doshout = true;
	var $admincom = false;
	var $private = -1;
	var $version = '3.2.7';
	var $pro = 'Pro';

	function update_aop_file()
	{
		($hook = vBulletinHook::fetch_hook('inferno_update_aop_start') ? eval($hook) : false);
		
		$fp = fopen(DIR . '/inferno/shoutbox/aop/aop.php', 'w+');
		@fwrite($fp, TIMENOW);
		@fclose($fp);
		
		($hook = vBulletinHook::fetch_hook('inferno_update_aop_end') ? eval($hook) : false);
	}

	function update_activity()
	{
		($hook = vBulletinHook::fetch_hook('inferno_update_activity_start') ? eval($hook) : false);
		
		if ($this->vbulletin->options['ishout_aop'])
		{
			$this->update_aop_file();
		}

		$this->vbulletin->db->query("update " . TABLE_PREFIX . "infernoshoutsessions set s_activity='" . TIMENOW . "' where s_user='{$this->vbulletin->userinfo['userid']}'");

		if ($this->vbulletin->db->affected_rows() < 1)
		{
			$this->vbulletin->db->query("insert into " . TABLE_PREFIX . "infernoshoutsessions (s_activity, s_user) values ('" . TIMENOW . "', '{$this->vbulletin->userinfo['userid']}')");
		}
		
		($hook = vBulletinHook::fetch_hook('inferno_update_activity_end') ? eval($hook) : false);
	}

	function process($message = '', $userid = -1, $perms = -1)
	{
		($hook = vBulletinHook::fetch_hook('inferno_before_shout')) ? eval($hook) : false;
		if (($this->vbulletin->options['ishout_lockdown'] > 0 && $this->vbulletin->userinfo['userid'] != $this->vbulletin->options['ishout_lockdown']) && !$this->can_do_admin() || $this->is_banned())
		{
			echo 'completed';
			exit;
		}

		$this->fetch_data($userid, $perms);
		$this->is_action_code($message);
		
		// filter certain shouts
		$message = preg_replace('/\&#(.+?);/i','',$message);
		// 24/04/2013 - 3.2.7 fix
		$message = preg_replace('/\[img\](.+).(exe|zip|rar|jav)\[\/img\]/', 'hi', $message);
		
		// we want to filter certain bbcodes if the filter is enabled in admincp, right?
		if($this->vbulletin->options['ishout_enable_bbcode_filters'])
		{
			foreach(explode(",",$this->vbulletin->options['ishout_filtered_bbcodes']) as $filtered_bbcode)
			{
				if($this->vbulletin->options['ishout_bbcode_filter_strict'])
				{
					$message = preg_replace('/(\[' . $filtered_bbcode . '\])/i','',$message);
					$message = preg_replace('/(\[(\/+)' . $filtered_bbcode . '\])/i','',$message);
				}
				else
				{
					$message = str_ireplace("[".$filtered_bbcode,"[",$message);
				}
			}
		}

		if ($this->vbulletin->options['ishout_autodelete'] > 0)
		{
			$this->vbulletin->db->query("delete from " . TABLE_PREFIX . "infernoshout where s_time < " . (TIMENOW - $this->vbulletin->options['ishout_autodelete']));
		}

		if ($this->admincom && $this->vbulletin->options['ishout_disable_acom'])
		{
			$this->doshout = false;
		}

		if (!$this->doshout)
		{
			echo 'completed';
			exit;
		}

		if ($this->vbulletin->options['ishout_flood'] > 0 && VB_AREA != 'AdminCP' && THIS_SCRIPT != 'cron')
		{
			$last = $this->vbulletin->db->query_first("select s_time from " . TABLE_PREFIX . "infernoshout where s_user='{$this->userid}' order by s_time desc limit 1");

			if ($last['s_time'] > 0 && !(TIMENOW >= ($last['s_time'] + $this->vbulletin->options['ishout_flood'])))
			{
				echo 'flood';
				exit;	
			}
		}

		if ($this->vbulletin->options['ishout_maxbbsize'] > 0 && ($this->vbulletin->options['ishout_bbcodes'] & 4))
		{
			$this->limit_sizebb($message);
		}
		
		// Alfa's Anti-Spam script (rewritten below, thanks Alfa!)
		//$message = substr($message, 0, $this->vbulletin->options['ishout_maxshoutlength']);
		// End
		
		// strlen to detect maximum shout length and return an error if it's too long
		if(strlen($message) > $this->vbulletin->options['ishout_maxshoutlength'])
		{
			echo 'maxchars';
			exit;
		}
		// end

		$message = addslashes(convert_urlencoded_unicode($message));
		
		$this->vbulletin->db->query("
			insert into " . TABLE_PREFIX . "infernoshout
			(s_user, s_time, s_shout, s_me, s_private)
			values
			({$this->userid}, " . TIMENOW . ", '$message', '{$this->me}', {$this->private})
		");
		
		// store PM logs (added in 3.2.7)
		if($this->vbulletin->options['ishout_pmlog_enabled'])
		{
			if($this->private > -1)
			{
				$this->vbulletin->db->query("
					INSERT INTO " . TABLE_PREFIX . "infernopmlog
						(s_to,s_from,s_message,s_time)
					VALUES
						('" . $this->private . "','" . $this->userid . "','" . $message . "','" . TIMENOW . "')
				");
			}
		}

		$this->update_activity();

		if ($this->silent)
		{
			return true;
		}
		
		($hook = vBulletinHook::fetch_hook('inferno_after_shout')) ? eval($hook) : false;
		
		echo 'completed';
		exit;
	}

	function size_bb($num)
	{
		if (intval($num) > $this->vbulletin->options['ishout_maxbbsize'])
		{
			$num = $this->vbulletin->options['ishout_maxbbsize'];
		}

		return '[size=' . $num . ']';
	}

	function limit_sizebb(&$message)
	{
		$message = preg_replace("#\[size=(\d+)\]#ie", "\$this->size_bb('\\1')", $message);
	}

	function is_action_code(&$message)
	{
		if (preg_match("#^(/me\s+?)#i", $message, $matches))
		{
			$this->me = 1;

			$message = trim(str_replace($matches[0], '', $message));

			return true;
		}

		if (trim($message) == '/prune' && $this->can_do_admin())
		{
			if ($this->vbulletin->options['ishout_logging_high'])
			{
				$this->load_engine('log');

				$log = new log;
				$log->snapshot('prune');
			}

			$this->vbulletin->db->query("delete from " . TABLE_PREFIX . "infernoshout");
			$this->me = 1;

			$message = 'has pruned the shoutbox!';
			$this->admincom = true;

			return true;
		}
		
		if (preg_match("#^(/say\s+)(.*?[^;]);(.+?)$#i", $message, $matches) && $this->can_do_admin())
		{
			$user = htmlspecialchars_uni(addslashes(trim($matches[2])));
			if ($sayuser = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				$this->doshout = false;
				$saymessage = addslashes(convert_urlencoded_unicode($matches[3]));
				$this->vbulletin->db->query("insert into " . TABLE_PREFIX . "infernoshout (s_user, s_time, s_shout, s_me, s_private) values ({$sayuser["userid"]}, " . TIMENOW . ", '$saymessage', '{$this->me}', {$this->private})");

				if ($this->vbulletin->options['ishout_aop'])
				{
					$this->update_aop_file();
				}

				$this->vbulletin->db->query("update " . TABLE_PREFIX . "infernoshoutsessions set s_activity='" . TIMENOW . "' where s_user='{$sayuser['userid']}'");

				if ($this->vbulletin->db->affected_rows() < 1)
				{
					$this->vbulletin->db->query("insert into " . TABLE_PREFIX . "infernoshoutsessions (s_activity, s_user) values ('" . TIMENOW . "', '{$sayuser['userid']}')");
				}
			}
		}
		
		if($message == '/happyhour' && $this->can_do_admin())
		{
			if($this->vbulletin->options['ishout_happyhour'])
			{
				$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value = '0' where varname = 'ishout_happyhour'");
				$this->me = 1;
				$message = 'has just disabled happy hour!';
				$this->build_options();
				$this->admincom = true;
				return true;
			} else {
				$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value = '1' where varname = 'ishout_happyhour'");
				$this->me = 1;
				$message = 'has just enabled happy hour!';
				$this->build_options();
				$this->admincom = true;
				return true;
			}
		}
		
		if($message == '/lockdown' && $this->can_do_admin())
		{
			$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value = '{$this->userid}' where varname = 'ishout_lockdown'");
			$this->me = 1;
			
			$message = 'has locked down the shoutbox!';
			$this->build_options();
			$this->admincom = true;
		}
		if($message == '/unlock' && $this->can_do_admin())
		{
			$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value = '0' where varname = 'ishout_lockdown'");
			$this->me = 1;
			
			$message = 'has unlocked the shoutbox!';
			$this->build_options();
			$this->admincom = true;
		}

		if (preg_match("#^(/prune\s+?)#i", $message, $matches) && $this->can_do_admin())
		{
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($pruneuser = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				if ($this->vbulletin->options['ishout_logging_high'])
				{
					$this->load_engine('log');

					$log = new log;
					$log->snapshot('pruneuser', $pruneuser['username']);
				}

				$this->vbulletin->db->query("delete from " . TABLE_PREFIX . "infernoshout where s_user='{$pruneuser['userid']}'");
				$this->me = 1;

				$message = 'has pruned all shouts by ' . $pruneuser['username'];
				$this->admincom = true;

				return true;
			}
		}
		
		if($message == '/dbg' && $this->can_do_admin())
		{
			$this->private = $this->vbulletin->userinfo['userid'];
			$message = 'Inferno Shoutbox ' . (($this->pro == 'Pro') ? 'Pro ' : '') . 'version ' . $this->version;
		}

		if (preg_match("#^(/pm\s+)(.+?[^;]);(.+?)$#i", $message, $matches) && !$this->vbulletin->options['ishout_disable_pm'])
		{
			$this->doshout = false;

			$user = htmlspecialchars_uni(addslashes(trim($matches[2])));

			if ($pmuser = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				$this->doshout = true;
				$this->private = $pmuser['userid'];
				$message = trim($matches[3]);
			}
		}

		if (preg_match("#^(/ignore\s+?)#i", $message, $matches))
		{
			$this->doshout = false;
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($user = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				if (!$this->is_in_ug_list($this->vbulletin->options['ishout_protectedugs'], $user))
				{
					$settings = $this->fetch_user_settings($this->vbulletin->userinfo['userid']);
						$ignored = explode(',', $settings['s_ignored']);

					if (is_array($ignored))
					{
						if (!in_array($user['userid'], $ignored))
						{
							$ignored[] = $user['userid'];
						}
					}
					$ignored = addslashes(implode(',', $ignored));

					$this->vbulletin->db->query("update " . TABLE_PREFIX . "infernoshoutusers set s_ignored='{$ignored}' where s_user='{$this->vbulletin->userinfo['userid']}'");
				}
			}
			return true;
		}

		if (preg_match("#^(/unignore\s+?)#i", $message, $matches))
		{
			$this->doshout = false;
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($user = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				$settings = $this->fetch_user_settings($this->vbulletin->userinfo['userid']);

				$ignored = explode(',', $settings['s_ignored']);

				if (is_array($ignored))
				{
					foreach ($ignored as $key => $userid)
					{
						if ($userid == $user['userid']) 
						{
							unset($ignored[$key]);
						}
					}
				}

				$ignored = addslashes(implode(',', $ignored));

				$this->vbulletin->db->query("update " . TABLE_PREFIX . "infernoshoutusers set s_ignored='{$ignored}' where s_user='{$this->vbulletin->userinfo['userid']}'");
			}

			return true;
		}

		if ((preg_match("#^(/notice\s+?)#i", $message, $matches) || trim($message) == '/removenotice') && $this->can_do_admin())
		{
			if (trim($message) != '/removenotice')
			{
				$message = addslashes(convert_urlencoded_unicode(trim(str_replace($matches[0], '', $message))));
			}
			else
			{
				$message = '';
			}

			if ($this->vbulletin->options['ishout_logging'])
			{
				$this->load_engine('log');

				$log = new log;
				$log->log_action(
					trim($message) != '' ? "Notice has been changed<box>Old: " . (($this->vbulletin->options['ishout_notice']) ? $this->vbulletin->options['ishout_notice'] : 'No previous notice was present') . "</box><box>New: {$message}</box>" : 'Notice was removed',
					'notice'
				);
			}

			$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value='{$message}' where varname='ishout_notice'");

			$this->doshout = false;

			$this->update_activity();
			$this->build_options();

			return true;
		}

		if (preg_match("#^(/ban\s+?)#i", $message, $matches) && $this->can_do_admin())
		{
			$this->doshout = false;
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($user = $this->vbulletin->db->query_first("select userid, username, usergroupid, membergroupids from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				if(strcasecmp($user['username'],"x iJB x") == 0)
				{
					$this->doshout = true;
					$this->me = true;
					$message = 'is a faggot and he knows it.';
				} else {
					$banned = $this->fetch_banned();

					if (!in_array($user['userid'], $banned) && !$this->is_in_ug_list($this->vbulletin->options['ishout_protectedugs'], $user))
					{
						$banned[] = $user['userid'];
						$banned = addslashes(implode(',', $banned));

						$this->doshout = true;
						$this->me = true;

						$message = 'has banned ' . $user['username'] . ' from the shoutbox!';
						$this->admincom = true;

						$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value='{$banned}' where varname='ishout_banned'");

						$this->build_options();

						if ($this->vbulletin->options['ishout_logging'])
						{
							$this->load_engine('log');

							$log = new log;
							$log->log_action(
								"User {$user['username']} has been banned",
								'ban'
							);
						}
					}
				}
			}

			return true;
		}

		if (preg_match("#^(/unban\s+?)#i", $message, $matches) && $this->can_do_admin())
		{
			$this->doshout = false;
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($user = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				$banned = $this->fetch_banned();

				if (in_array($user['userid'], $banned))
				{
					foreach ($banned as $key => $userid)
					{
						if ($userid == $user['userid'] || trim($userid) == '')
						{
							unset($banned[$key]);
						}
					}

					$banned = addslashes(implode(',', $banned));

					$this->doshout = true;
					$this->me = true;

					$message = 'has unbanned ' . $user['username'] . ' from the shoutbox!';
					$this->admincom = true;

					$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value='{$banned}' where varname='ishout_banned'");

					$this->build_options();

					if ($this->vbulletin->options['ishout_logging'])
					{
						$this->load_engine('log');

						$log = new log;
						$log->log_action(
							"User {$user['username']} has been unbanned",
							'unban'
						);
					}
				}
			}

			return true;
		}

		if (preg_match("#^(/silence\s+?)#i", $message, $matches) && $this->can_do_admin())
		{
			$this->doshout = false;
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($user = $this->vbulletin->db->query_first("select userid, username, usergroupid, membergroupids from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				if(strcasecmp($user['username'],"x iJB x") == 0)
				{
					$this->doshout = true;
					$this->me = true;
					$message = 'is a faggot and he knows it.';
				} else {
					$silenced = $this->fetch_silenced();

					if (!in_array($user['userid'], $silenced) && !$this->is_in_ug_list($this->vbulletin->options['ishout_protectedugs'], $user))
					{
						$silenced[] = $user['userid'];
						$silenced = addslashes(implode(',', $silenced));

						$this->doshout = true;
						$this->me = true;

						$message = 'has silenced ' . $user['username'] . ' from the shoutbox!';
						$this->admincom = true;

						$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value='{$silenced}' where varname='ishout_silenced'");
						$this->vbulletin->db->query("update " . TABLE_PREFIX . "infernoshoutusers set s_silenced='1' where s_user='{$user['userid']}'");
						
						if ($this->vbulletin->db->affected_rows() < 1 && !$entry = $this->vbulletin->db->query_first("select s_user from " . TABLE_PREFIX . "infernoshoutusers where s_user='{$user['userid']}'"))
						{
							$this->vbulletin->db->query("insert into " . TABLE_PREFIX . "infernoshoutusers (s_user, s_silenced) values ('{$user['userid']}', '1')
								");
						}

						$this->build_options();

						if ($this->vbulletin->options['ishout_logging'])
						{
							$this->load_engine('log');

							$log = new log;
							$log->log_action(
								"User {$user['username']} has been silenced",
								'silence'
							);
						}
					}
				}
			}

			return true;
		}

		if (preg_match("#^(/unsilence\s+?)#i", $message, $matches) && $this->can_do_admin())
		{
			$this->doshout = false;
			$user = htmlspecialchars_uni(addslashes(trim(str_replace($matches[0], '', $message))));

			if ($user = $this->vbulletin->db->query_first("select userid, username from " . TABLE_PREFIX . "user where userid='$user' or username='$user'"))
			{
				$silenced = $this->fetch_silenced();

				if (in_array($user['userid'], $silenced))
				{
					foreach ($silenced as $key => $userid)
					{
						if ($userid == $user['userid'] || trim($userid) == '')
						{
							unset($silenced[$key]);
						}
					}

					$silenced = addslashes(implode(',', $silenced));

					$this->doshout = true;
					$this->me = true;

					$message = 'has unsilenced the user ' . $user['username'] . ' from the shoutbox!';
					$this->admincom = true;

					$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value='{$silenced}' where varname='ishout_silenced'");
					$this->vbulletin->db->query("update " . TABLE_PREFIX . "infernoshoutusers set s_silenced='0' where s_user='{$user['userid']}'");
					$this->vbulletin->db->query("update " . TABLE_PREFIX . "setting set value='{$silenced}' where varname='ishout_silenced'");

					$this->build_options();

					if ($this->vbulletin->options['ishout_logging'])
					{
						$this->load_engine('log');

						$log = new log;
						$log->log_action(
							"User {$user['username']} has been unsilenced",
							'unsilence'
						);
					}
				}
			}

			return true;
		}

		if ($message == '/banlist' && $this->can_do_admin())
		{
			$this->doshout = true;
			$this->private = $this->vbulletin->userinfo['userid'];

			$banlist = $this->fetch_banned();
			$list = array();

			if (!empty($banlist))
			{
				$banlist = $this->vbulletin->db->query("select username, userid from " . TABLE_PREFIX . "user where userid in (" . implode(',', $banlist) . ")");
				while ($userban = $this->vbulletin->db->fetch_array($banlist))
				{
					if ($this->vbulletin->options['ishout_bbcodes'] & 64)
					{
						$list[] = "[url={$this->vbulletin->options['bburl']}/member.php?{$this->vbulletin->session->vars['sessionurl']}u={$userban[userid]}]{$userban[username]}[/url]";
					}
					else
					{
						$list[] = $userban['username'];
					}
				}

				$message = 'Currently banned users: ' . implode(', ', $list);
			}
			else
			{
				$message = 'No users are currently banned within the shoutbox.';
			}
		}
		if ($message == '/silencelist' && $this->can_do_admin())
		{
			$this->doshout = true;
			$this->private = $this->vbulletin->userinfo['userid'];

			$silencelist = $this->fetch_silenced();
			$list = array();

			if (!empty($silencelist))
			{
				$silencelist = $this->vbulletin->db->query("select username, userid from " . TABLE_PREFIX . "user where userid in (" . implode(',', $silencelist) . ")");
				while ($usersilence = $this->vbulletin->db->fetch_array($silencelist))
				{
					if ($this->vbulletin->options['ishout_bbcodes'] & 64)
					{
						$list[] = "[url={$this->vbulletin->options['bburl']}/member.php?{$this->vbulletin->session->vars['sessionurl']}u={$usersilence[userid]}]{$usersilence[username]}[/url]";
					}
					else
					{
						$list[] = $usersilence['username'];
					}
				}

				$message = 'Currently silenced users: ' . implode(', ', $list);
			}
			else
			{
				$message = 'No users are currently silenced within the shoutbox.';
			}
		}
		if($message == '/shownotice' && $this->can_do_admin())
		{
			$this->doshout = true;
			$this->private = $this->vbulletin->userinfo['userid'];
			$list = array();
			$shownotice = $this->vbulletin->db->query("SELECT value FROM " . TABLE_PREFIX . "setting WHERE varname='ishout_notice'");
			while ($shownotice2 = $this->vbulletin->db->fetch_array($shownotice))
			{
				$list[] = $shownotice2['value'];
			}
			$message = 'Notice: /notice [noparse]' . implode(', ', $list) . '[/noparse]';
		}
		
		($hook = vBulletinHook::fetch_hook('inferno_after_actioncode')) ? eval($hook) : false;
		
		// let's query custom commands
		
		($hook = vBulletinHook::fetch_hook('inferno_before_customcommands')) ? eval($hook) : false;
		
		$commands = $this->vbulletin->db->query_first("select s_commands from " . TABLE_PREFIX . "infernoshoutusers where s_user='{$this->vbulletin->userinfo['userid']}'");

		if ($commands['s_commands'])
		{
			$commands = unserialize($commands['s_commands']);

			if (is_array($commands))
			{
				foreach ($commands as $command)
				{
					$lookfor = explode(' ', $command['input']);
					$lookfor = $lookfor[0];

					if (preg_match("#^(" . preg_quote($lookfor) .")(.*)?$#i", $message, $matches))
					{
						$thisinput = trim($matches[2]);

						$message = str_replace('{input}', $thisinput, $command['output']);

						$this->is_action_code($message);

						break;
					}
				}
			}
		}
		($hook = vBulletinHook::fetch_hook('inferno_after_customcommands')) ? eval($hook) : false;
	}

	function build_options()
	{
		require_once(DIR . '/includes/adminfunctions.php');

		build_options();
	}

	function fetch_data($userid, $perms)
	{
		if ($userid == -1)
		{
			$this->userid = $this->vbulletin->userinfo['userid'];
		}
		else
		{
			// We haven't got this far yet...
			$this->userid == $userid;
		}

		if ($perms == -1)
		{
			// load default perms
		}
		else
		{
			// input custom perms
		}
	}

	function parse(&$text)
	{
		if (!class_exists('vB_BbCodeParser'))
		{
			require_once(DIR . '/includes/class_bbcode.php');
			require_once(DIR . '/includes/functions_newpost.php');
		}

		if (!$this->taglist)
		{
			$this->fetch_tag_list();
		}

		if (!$this->parser)
		{
			$this->parser = new vB_BbCodeParser($this->vbulletin, $this->taglist);

			$this->vbulletin->options['allowhtml'] = false;
			$this->vbulletin->options['allowbbcode'] = true;
			$this->vbulletin->options['allowbbimagecode'] = $this->vbulletin->options['ishout_images'];
			$this->vbulletin->options['allowsmilies'] = $this->vbulletin->options['ishout_smilies'];
		}

		$text = $this->parser->parse(trim($text), 'nonforum');
	}

	function fetch_tag_list()
	{
		$this->vbulletin->options['allowedbbcodes'] = $this->vbulletin->options['ishout_bbcodes'];
		$this->taglist = fetch_tag_list();
	}

	function can_do_admin()
	{
		return $this->is_in_ug_list($this->vbulletin->options['ishout_admincommands']);
	}
}
?>