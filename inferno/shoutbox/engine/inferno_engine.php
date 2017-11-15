<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */

error_reporting(E_ALL & ~E_NOTICE);

class infernoshout_engine
{
	var $jversion = '3.2.7';
	var $vbulletin;
	var $db;
	var $deploy = false;
	var $box = '';
	var $script = '';
	var $parsebreaker = '<<~~PARSE_^_BREAKER~~>>';
	var $engines = array();
	var $editor_selects = array();

	function infernoshout_engine()
	{
		global $vbulletin;

		$this->vbulletin = $vbulletin;
		$this->db = $this->vbulletin->db;
		$this->vbulletin->options['ishout_jversion'] = $this->jversion;

		$this->script = THIS_SCRIPT;
	}

	function trigger_error($error = '', $method = 'unknown')
	{
		echo "<b>Fatal Error</b>: ./inferno/shoutbox/engine/inferno_engine.php has encountered a problem and is required to shut down.
		<pre>-----------------------------------------------
Problem encountered in: $method method
System Response: $error
-----------------------------------------------</pre>";

		exit;
	}

	function load_engine($engine = '')
	{
		($hook = vBulletinHook::fetch_hook('inferno_load_engine_start')) ? eval($hook) : false;
		
		if (in_array($engine, $this->engines))
		{
			return false;
		}

		if (!file_exists(DIR . '/inferno/shoutbox/engine/inferno_' . $engine . '.php'))
		{
			$this->trigger_error("The engine file '{$engine}' is missing from ./inferno/shoutbox/engine/", 'load_engine');
		}

		global $output;

		$this->engines[] = $engine;

		require_once(DIR . '/inferno/shoutbox/engine/inferno_' . $engine . '.php');
		
		($hook = vBulletinHook::fetch_hook('inferno_load_engine_end')) ? eval($hook) : false;
	}

	function setup_deployment()
	{
		global $cache;

		$this->deploy = true;

		// cache templates
		$cache[] ='inferno_shoutbox_box';
		$cache[] ='inferno_shoutbox_box_alt';
		$cache[] ='inferno_shoutbox_editor';
		$cache[] ='inferno_shoutbox_shout';
		$cache[] ='inferno_shoutbox_user';
		
	}

	function load_editor_settings()
	{
		$this->editor_settings = $this->vbulletin->db->query_first("select * from " . TABLE_PREFIX . "infernoshoutusers where s_user='{$this->vbulletin->userinfo['userid']}'");
	}

	function build_editor_select($selection, $css)
	{
		if (!$this->editor_selects[$selection])
		{
			$this->editor_selects[$selection] = '';

			$selections = explode("\n", $this->vbulletin->options['ishout_' . $selection]);

			$optiontitle = "Default";
			$templater = vB_Template::create('option');
			$templater->register('optiontitle',$optiontitle);
			$this->editor_selects["$selection"] .= $templater->render();

			if (is_array($selections))
			{
				foreach ($selections as $option)
				{
					if (($option = trim($option)) != '')
					{
						$optionvalue = $option;
						$optiontitle = $option;
						$optionselected = 'style="' . $css . ': ' . $option . ';"' . (($this->editor_settings[($selection == 'colours') ? 's_color' : 's_font'] == $option) ? ' selected="selected"' : '');
						$templater = vB_Template::create('option');
						$templater->register('optionvalue',$optionvalue);						
						$templater->register('optiontitle',$optiontitle);						
						$templater->register('optionselected',$optionselected);						
						$this->editor_selects["$selection"] .= $templater->render();
					}
				}
			}
		}
	}

	function wrap_tag(&$shout, $option, $tag, $param = false)
	{
		if ($option && trim($option) != '')
		{
			$shout = '[' . $tag . ($param ? '="' . $option . '"' : '') . ']' . $shout . '[/' . $tag . ']';
		}
	}

	function fetch_banned()
	{
		($hook = vBulletinHook::fetch_hook('inferno_fetch_banned_start')) ? eval($hook) : false;
		
		$banned = explode(',', str_replace(' ', '', trim($this->vbulletin->options['ishout_banned'])));

		foreach ($banned as $key => $data)
		{
			if (trim($data) == '')
			{
				unset($banned[$key]);
			}
		}

		if (is_array($banned) && !empty($banned))
		{
			return $banned;
		}
		
		($hook = vBulletinHook::fetch_hook('inferno_fetch_banned_end')) ? eval($hook) : false;
		
		return array();
	}
	
	function fetch_silenced()
	{
		$silenced = explode(',', str_replace(' ', '', trim($this->vbulletin->options['ishout_silenced'])));

		foreach ($silenced as $key => $data)
		{
			if (trim($data) == '')
			{
				unset($silenced[$key]);
			}
		}

		if (is_array($silenced) && !empty($silenced))
		{
			return $silenced;
		}

		return array();
	}

	function is_banned()
	{	
		if ((in_array($this->vbulletin->userinfo['userid'], $this->fetch_banned()) && $this->vbulletin->userinfo['userid'] > 0))
		{
			return true;
		}

		if ($this->is_in_ug_list($this->vbulletin->options['ishout_bannedgroups']))
		{
			return true;
		}

		return false;
	}
	
	function is_silenced()
	{
		if (in_array($this->vbulletin->userinfo['userid'], $this->fetch_silenced()) && $this->vbulletin->userinfo['userid'] > 0)
		{
			return true;
		}
		
		return false;
	}

	function clean_array(&$array)
	{
		if (is_array($array))
		{
			foreach ($array as $key => $value)
			{
				if (trim($value) == '')
				{
					unset($array[$key]);
				}
			}
		}
	}

	function fetch_filters(&$settings)
	{
		$settings['s_filters'] = unserialize($settings['s_filters']);
	}

	function fetch_shouts(&$shoutobj, $limit = 20, $archive = false, $search_terms = false, $single_only = false)
	{
		$settings = $this->fetch_user_settings($this->vbulletin->userinfo['userid']);

		$ignored = explode(',', $settings['s_ignored']);
		$ignored[] = '-1';

		$this->clean_array($ignored);

		$ignored = implode(',', $ignored);

		$template = !$archive ? 'inferno_shoutbox_shout' : 'inferno_shoutbox_archive_shout';

		switch ($_REQUEST['fetchtype'])
		{
			case 'pmonly':
			{
				$sqlcond = "and
					(
						(s.s_private = '" . intval($_REQUEST['pmid']) . "' and s.s_user = '{$this->vbulletin->userinfo['userid']}')
						or
						(s.s_private = '{$this->vbulletin->userinfo['userid']}' && s.s_user = '" . intval($_REQUEST['pmid']) . "')
					)";

				$ispmwindow = true;
			}
			break;

			default:
			{
				$sqlcond = '';
			}
		}

		$this->fetch_filters($settings);

		if ($settings['s_f_me'])
		{
			$sqlcond .= ' and s.s_me <> \'1\'';
		}

		if ($settings['s_f_pm'] && intval($_REQUEST['pmid']) < 1) // don't hide PMs in PM windows
		{
			$sqlcond .= ' and s.s_private = \'-1\'';
		}

		if ($single_only)
		{
			$sqlcond .= ' and s.sid = \'' . $single_only . '\'';
		}

		if ($search_terms)
		{
			$search_time = TIMENOW - ((60 * 60) * $search_terms['time']);
			$sqlcond .= "
				and s.s_shout like '%{$search_terms['phrase']}%'
				and u.username like '%" . htmlspecialchars_uni($search_terms['username']) . "%'
				and s.s_time > $search_time
			";
		}

		$build = '';
		$shouts = $this->vbulletin->db->query("
			select s.*, u.username, u.displaygroupid, u.usergroupid, u.userid, o.*
			from " . TABLE_PREFIX . "infernoshout s
			left join " . TABLE_PREFIX . "user u on (u.userid = s.s_user)
			left join " . TABLE_PREFIX . "infernoshoutusers o on (o.s_user = s.s_user)
			where
			(
				(s.s_private = -1)
				OR
				(s.s_private = '{$this->vbulletin->userinfo['userid']}')
				OR
				(s.s_private <> -1 AND s.s_user = '{$this->vbulletin->userinfo['userid']}')
			)
			and u.userid not in ($ignored)
			and
			(
				o.s_silenced = '0'
				OR
				(o.s_silenced <> '0' AND u.userid = '{$this->vbulletin->userinfo['userid']}')
			)
			$sqlcond
			order by s.s_time " . (($search_terms) ? (($search_terms['sort'] == 'new') ? 'desc' : 'asc') : 'desc') . "
			" . ((trim($limit) != '--nolim--') ? "limit $limit" : '') . "
		");

		if ($this->is_banned())
		{
			$shout = array(
				's_notice'	=> 1,
				's_shout'	=> 'You are currently banned from the shoutbox.',
				'musername'	=> '<span style="font-weight: bold; color: '.$this->vbulletin->options['ishout_time_pm_color'].';">Notice</span>',
			);

			$shoutobj->parse($shout['s_shout']);
			$templater = vB_Template::create($template);
			$templater->register('shout',$shout);
			$build = $templater->render();

			return $build;
		}

		if ($this->vbulletin->options['ishout_notice'] != '' && !$archive)
		{
			$shout = array(
				's_notice'	=> 1,
				's_shout'	=> $this->vbulletin->options['ishout_notice'],
				'musername'	=> '<b><span style="font-weight: bold; color: '.$this->vbulletin->options['ishout_time_pm_color'].';">Notice</span></b>',
			);

			$shoutobj->parse($shout['s_shout']);

			$templater = vB_Template::create($template);
			$templater->register('shout',$shout);
			$build .= $templater->render();
		}

		$canadmin = $this->is_in_ug_list($this->vbulletin->options['ishout_admincommands']);

		if (!function_exists('convert_url_to_bbcode'))
		{
			require_once(DIR . '/includes/functions_newpost.php');
		}

		while ($shout = $this->vbulletin->db->fetch_array($shouts))
		{
			if ($this->vbulletin->options['ishout_bbcodes'] & 64)
			{
				$shout['s_shout'] = convert_url_to_bbcode($shout['s_shout']);
			}

			$shout[canmod] = $canadmin || $shout['userid'] == $this->vbulletin->userinfo['userid'];

			$this->wrap_tag($shout['s_shout'], $shout['s_bold'], 'b');
			$this->wrap_tag($shout['s_shout'], $shout['s_italic'], 'i');
			$this->wrap_tag($shout['s_shout'], $shout['s_underline'], 'u');
			$this->wrap_tag($shout['s_shout'], $shout['s_font'], 'font', true);
			$this->wrap_tag($shout['s_shout'], $shout['s_color'], 'color', true);

			if ($settings['s_f_bbcode'])
			{
				$shout['s_shout'] = strip_bbcode($shout['s_shout']);
			}

			$shoutobj->parse($shout['s_shout']);

			$shout[date] = vbdate($this->vbulletin->options['dateformat'], $shout['s_time'], $this->vbulletin->options['yestoday']);
			$shout[time] = vbdate($this->vbulletin->options['timeformat'], $shout['s_time'], $this->vbulletin->options['yestoday']);

			fetch_musername($shout);

			$shout['javascript_name'] = addslashes($shout['username']);

			if (!$this->vbulletin->options['ishout_shoutorder'] || $archive)
			{
				$templater = vB_Template::create($template);
				$templater->register('shout',$shout);
				$build .= $templater->render();
			}
			else
			{
				$templater = vB_Template::create($template);
				$templater->register('shout',$shout);
				$build = $templater->render() . $builder;
			}
		}

		if ($this->vbulletin->options['ishout_largertext'])
		{
			$build = str_replace('smallfont', '', $build);
		}

		if ($archive)
		{
			return $build;
		}

		return $build . $this->parsebreaker . $this->fetch_activity();
	}

	function fetch_active_users()
	{
		$cutoff = TIMENOW - (60 * $this->vbulletin->options['ishout_activeuserscutoff']);

		$users = $this->vbulletin->db->query("
			select s.sid, u.username, u.userid, u.displaygroupid, u.usergroupid
			from " . TABLE_PREFIX . "infernoshoutsessions s
			left join " . TABLE_PREFIX . "user u on (u.userid = s.s_user)
			where s.s_activity > $cutoff
			group by s_user
		");

		return $users;
	}

	function fetch_users_list()
	{
		$activeusers = array();

		$users = $this->fetch_active_users();
		while ($user = $this->vbulletin->db->fetch_array($users))
		{
			fetch_musername($user);
			$templater = vB_Template::create('inferno_shoutbox_user');
			$templater->register('user',$user);
			$activeusers[] = $templater->render();
		}

		$total = count($activeusers);
		$activeusers = implode(', ', $activeusers);

		if (trim($activeusers) == '')
		{
			$activeusers = 'There are currently no active users in the shoutbox.';
		}
		$templater = vB_Template::create('inferno_shoutbox_activeusers');
		$templater->register('total',$total);
		$templater->register('activeusers',$activeusers);
		return $templater->render();
	}

	function fetch_activity()
	{
		$total = 0;
		$users = $this->fetch_active_users();
		while ($user = $this->vbulletin->db->fetch_array($users))
		{
			$total++;
		}

		return $total;
	}

	function set_style_properties()
	{
		$colour = $_REQUEST['colour'];
		$fontfamily = $_REQUEST['fontfamily'];
		$bold = intval($_REQUEST['bold']);
		$italic = intval($_REQUEST['italic']);
		$underline = intval($_REQUEST['underline']);
		
		$userid = $this->vbulletin->userinfo['userid'];
		
		$initial = $this->vbulletin->db->query_first("
			SELECT s_color,s_font FROM " . TABLE_PREFIX . "infernoshoutusers
				WHERE s_user = '" . $userid . "'
		");
		
		//$colours_allowed = explode(PHP_EOL,$this->vbulletin->options['ishout_colours']);
		
		//$fonts_allowed = explode(PHP_EOL,$this->vbulletin->options['ishout_fonts']);
		
		if (!preg_match("#^[\#a-z0-9\s]+$#i", $colour))
		{
			$colour = '';
			$inv['c'] = true;
		}
		
		if (!preg_match("#^[\#a-z0-9\s]+$#i", $colour))
		{
			$colour = '';
			$inv['c'] = true;
		}
		
		/*if (!preg_match("#^[\#a-z0-9\s]+$#i", $colour) || !in_array($colour,$colours_allowed))
		{
			$colour = '';
			$inv['c'] = true;
		}
		
		if (!preg_match("#^[\#a-z0-9\s]+$#i", $fontfamily) || !in_array($fontfamily,$fonts_allowed))
		{
			$fontfamily = '';
			$inv['f'] = true;
		}*/
		
		if(($initial['s_color'] != $colour or $initial['s_font'] != $fontfamily))
		{
			$this->vbulletin->db->query("
				UPDATE " . TABLE_PREFIX . "infernoshoutusers SET
					s_color='{$colour}',
					s_font='{$fontfamily}'
				WHERE s_user = '{$this->vbulletin->userinfo['userid']}';
			");

			if ($this->vbulletin->db->affected_rows() < 1)
			{
				if(!isset($inv) and ($inv['c'] != true || $inv['f'] != true))
				{
					$this->vbulletin->db->query("
						INSERT INTO " . TABLE_PREFIX . "infernoshoutusers
							(s_user, s_bold, s_italic, s_underline, s_color, s_font)
						VALUES
						('{$this->vbulletin->userinfo['userid']}', '{$bold}', '{$italic}', '{$underline}', '{$color}', '{$fontfamily}')
					");
				}
			}
		}
		if($bold != $initial['s_bold'] || $italic != $initial['s_italic'] || $underline != $initial['s_underline'])
		{
			$this->vbulletin->db->query("
				UPDATE " . TABLE_PREFIX . "infernoshoutusers SET
					s_bold='{$bold}',
					s_italic='{$italic}',
					s_underline='{$underline}'
				WHERE s_user = '{$this->vbulletin->userinfo['userid']}';
			");
			
			if ($this->vbulletin->db->affected_rows() < 1)
			{
				if(!isset($inv) and ($inv['c'] != true || $inv['f'] != true))
				{
					$this->vbulletin->db->query("
						INSERT INTO " . TABLE_PREFIX . "infernoshoutusers
							(s_user, s_bold, s_italic, s_underline, s_color, s_font)
						VALUES
						('{$this->vbulletin->userinfo['userid']}', '{$bold}', '{$italic}', '{$underline}', '{$color}', '{$fontfamily}')
					");
				}
			}
		}
	}

	function fetch_user_settings($userid = -1)
	{
		$usersettings = $this->vbulletin->db->query_first("select * from " . TABLE_PREFIX . "infernoshoutusers where s_user='{$userid}'");

		if ($usersettings['s_user'] == '')
		{
			$this->vbulletin->db->query("
				insert into " . TABLE_PREFIX . "infernoshoutusers
				(s_user)
				values
				('{$userid}')
			");

			$usersettings = array('s_user' => $userid);
		}

		return $usersettings;
	}

	function is_in_ug_list($list, $userinfo = false)
	{
		if (!$userinfo)
		{
			$userinfo = $this->vbulletin->userinfo;
		}

		$groups = explode(',', $list);

		if (is_array($groups))
		{
			foreach ($groups as $ug)
			{
				if (is_member_of($userinfo, $ug))
				{
					return true;
				}
			}
		}

		return false;
	}

	function fetch_edit_shout($shoutid)
	{
		$shout = $this->vbulletin->db->query_first("select * from " . TABLE_PREFIX . "infernoshout where sid='{$shoutid}'");

		if (!$this->is_in_ug_list($this->vbulletin->options['ishout_admincommands']) && $shout['s_user'] != $this->vbulletin->userinfo['userid'])
		{
			$this->xml_document('deny');
		}

		echo $this->xml_document($shout['s_shout'] . $this->parsebreaker . $shout['sid']);
	}

	function do_edit_shout($shoutid)
	{
		$shout = trim($this->vbulletin->GPC['shout']);
		
		// filter certain messages from being edited into shouts
		$shout = preg_replace('/\&#(.+?);/i','',$shout);
		
		$shout = convert_urlencoded_unicode($shout);
		$shout = addslashes($shout);
		$shout = substr($shout, 0, $this->vbulletin->options['ishout_maxshoutlength']);
		$extra = '';

		if (!$this->is_in_ug_list($this->vbulletin->options['ishout_admincommands']))
		{
			$extra = "and s_user='{$this->vbulletin->userinfo['userid']}'";
		}
		
		// we want to filter certain bbcodes if the filter is enabled in admincp, right?
		if($this->vbulletin->options['ishout_enable_bbcode_filters'])
		{
			foreach(explode(",",$this->vbulletin->options['ishout_filtered_bbcodes']) as $filtered_bbcode)
			{
				if($this->vbulletin->options['ishout_bbcode_filter_strict'])
				{
					$shout = preg_replace('/(\[' . $filtered_bbcode . '\])/i','',$shout);
					$shout = preg_replace('/(\[(\/+)' . $filtered_bbcode . '\])/i','',$shout);
				}
				else
				{
					$shout = str_ireplace("[".$filtered_bbcode,"[",$shout);
				}
			}
		}

		if ($this->vbulletin->options['ishout_logging'])
		{
			$old = $this->vbulletin->db->query_first("select s.*, u.username from " . TABLE_PREFIX . "infernoshout s left join " . TABLE_PREFIX . "user u on(u.userid = s.s_user) where s.sid='{$shoutid}'");
			$this->load_engine('log');

			$log = new log;

			if ($_POST['delete'])
			{
				$log->log_action(
					"Shout Deleted (Shouter: " . $old['username'] . ") <box>Shout: {$old['s_shout']}</box>",
					'delete'
				);
			}
			else
			{
				$log->log_action(
					"Shout Edited (Shouter: " . $old['username'] . ") <box>Previous: {$old['s_shout']}</box><box>New: $shout</box>",
					'edit'
				);
			}
		}

		if ($shout != '' && $_POST['delete'] != 1)
		{
			$this->vbulletin->db->query_first("update " . TABLE_PREFIX . "infernoshout set s_shout='{$shout}' where sid='{$shoutid}' $extra");
		}
		else if ($_POST['delete'] == 1)
		{
			$this->vbulletin->db->query_first("delete from " . TABLE_PREFIX . "infernoshout where sid='{$shoutid}' $extra");
		}

		$this->load_engine('shout');
		$shout = new shout;

		$shout->update_activity();

		echo 'completed';
	}

	function fetch_smilies($show = 1)
	{
		$show = intval($show);

		if ($show < 1)
		{
			$show = 1;
		}

		$smilies = array();
		$frame = '<img src="%s" alt="%s" onclick="InfernoShoutbox.append_smilie(\'%s\');" onmouseover="this.style.cursor = \'pointer\';" />';

		$fetchsmilies = $this->vbulletin->db->query("select * from " . TABLE_PREFIX . "smilie order by RAND() limit 0,$show");

		while ($smilie = $this->vbulletin->db->fetch_array($fetchsmilies))
		{
			$smilies[] = sprintf($frame, $smilie['smiliepath'], $smilie['title'], $smilie['smilietext']);
		}

		if (is_array($smilies))
		{
			return implode(' ', $smilies);
		}
		else
		{
			return 'No smilies found in database.';
		}
	}

	function xml_document($data)
	{
		$charset = $this->vbulletin->userinfo['lang_charset'];
		$charset = strtolower($charset) == 'iso-8859-1' ? 'windows-1252' : $charset;
		@header( "Content-type: text/xml; charset=" . $charset);

		$xmldoc = '<?xml version="1.0" encoding="' . $charset . '"?>
		<inferno>
			<data><![CDATA[' . $data . ']]></data>
		</inferno>';

		return trim($xmldoc);
	}
}

global $infernoshout;

if (!isset($infernoshout))
{
	$infernoshout = new infernoshout_engine;
}
?>