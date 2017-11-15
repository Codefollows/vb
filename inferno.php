<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */

error_reporting(E_ALL & ~E_NOTICE);

define('NO_REGISTER_GLOBALS', 1);
define('LOCATION_BYPASS', 1);
define('THIS_SCRIPT', 'infernomain');

$phrasegroups = $specialtemplates = array();

$actiontemplates = array(
	'archive'	=> array(
		'inferno_shoutbox_archive',
		'inferno_shoutbox_archive_shout',
		'inferno_shoutbox_archive_topshouter',
	),
	'options'	=> array(
		'inferno_shoutbox_cp',
		'inferno_shoutbox_filters',
		'inferno_shoutbox_ignore',
		'inferno_shoutbox_ignore_user',
		'inferno_shoutbox_commands',
		'inferno_shoutbox_commands_row',
	),
	'detach'	=> array(
		'inferno_shoutbox_box',
		'inferno_shoutbox_box_alt',
		'inferno_shoutbox_editor',
		'inferno_shoutbox_shout',
		'inferno_shoutbox_user',
		'inferno_shoutbox_detach',
	),
);

$globaltemplates = array(
	'GENERIC_SHELL',
	'inferno_shoutbox_shout',
	'inferno_home',
);

require_once('./global.php');
require_once(DIR . '/inferno/shoutbox/engine/inferno_engine.php');

if (empty($_REQUEST['do']))
{
    // set default branch for this script
    $_REQUEST['do'] = 'home';
}

# ------------------------------------------------------- #
# Display Inferno Home
# ------------------------------------------------------- #

if($_REQUEST['do'] == 'home')
{
	$navbits = construct_navbits(array('inferno.php' => $vbulletin->options['ishout_sbtitle'], '' => 'Home'));
	$navbar = render_navbar_template($navbits);

	// ###### YOUR CUSTOM CODE GOES HERE #####
	$pagetitle = 'Inferno Home';

	// ###### NOW YOUR TEMPLATE IS BEING RENDERED ######

	$templater = vB_Template::create('inferno_home');
	$templater->register_page_templates();
	$templater->register('navbar', $navbar);
	$templater->register('pagetitle', $pagetitle);
	print_output($templater->render());
}

# ------------------------------------------------------- #
# Display PM Log
# ------------------------------------------------------- #

if($_REQUEST['do'] == 'pmlog')
{
	if($vbulletin->options['ishout_pmlog_viewing_enabled'])
	{
		$navbits = construct_navbits(array('inferno.php' => $vbulletin->options['ishout_sbtitle'], '' => 'Private Message Log'));
		$navbar = render_navbar_template($navbits);

		// ###### YOUR CUSTOM CODE GOES HERE #####
		
		$userid = $vbulletin->userinfo['userid'];
		
		if(isset($_REQUEST['with']))
		{
			$with = $vbulletin->db->escape_string(intval($_REQUEST['with']));
			$query = "
			SELECT * FROM " . TABLE_PREFIX . "infernopmlog
				WHERE s_from = '" . $userid . "'
					AND s_to = '" . $with . "'
				OR s_to = '" . $userid . "'
					AND s_from = '" . $with . "'
				ORDER BY pmlogid DESC
		";
		}
		else
		{
			$query = "
			SELECT * FROM " . TABLE_PREFIX . "infernopmlog
				WHERE s_from = '" . $userid . "'
					AND s_to > -1
				OR s_to = '" . $userid . "'
				ORDER BY pmlogid DESC
		";
		}
		
		$shouts = $vbulletin->db->query($query);
		
		if(!$vbulletin->db->num_rows($shouts))
		{
			if(isset($_REQUEST['with']))
			{
				standard_error("You don't seem to have any private messages with this user.");
			}
			else
			{
				standard_error("You don't seem to have any stored private messages.");
			}
		}
		else
		{
			$pmlog = '';
			while($shout = $vbulletin->db->fetch_array($shouts))
			{
				if($shout['s_to'] === $shout['s_from'])
				{
					continue;
				}
				
				$shout['s_to_name'] = fetch_userinfo($shout['s_to']);
				$shout['s_from_name'] = fetch_userinfo($shout['s_from']);
				$shout['s_to_name'] = fetch_musername($shout['s_to_name']);
				$shout['s_from_name'] = fetch_musername($shout['s_from_name']);
				
				$shout['date'] = vbdate($vbulletin->options['dateformat'], $shout['s_time'], $vbulletin->options['yestoday']);
				$shout['time'] = vbdate($vbulletin->options['timeformat'], $shout['s_time'], $vbulletin->options['yestoday']);
				
				$shout['s_message'] = htmlspecialchars_uni($shout['s_message']);
				
				$templater = vB_Template::create('inferno_pmlog_shout');
				$templater->register('shout', $shout);
				$pmlog .= $templater->render();
			}
		}
		
		$pagetitle = 'Inferno Private Message Log';

		// ###### NOW YOUR TEMPLATE IS BEING RENDERED ######

		$templater = vB_Template::create('inferno_pmlog_main');
		$templater->register_page_templates();
		$templater->register('navbar', $navbar);
		$templater->register('pmlog', $pmlog);
		$templater->register('pagetitle', $pagetitle);
		print_output($templater->render());
	}
	else
	{
		standard_error("This feature has been disabled by the administrator.");
	}
}

elseif($_REQUEST['do'] == 'pmlogsearch')
{
	if($vbulletin->options['ishout_pmlog_viewing_enabled'])
	{
		if(isset($_REQUEST['type']) and $_REQUEST['type'] == 'withuser')
		{
			$vbulletin->input->clean_array_gpc('p',array(
				'with_username' => TYPE_STR
			));
			
			$cleaned_username = $vbulletin->db->escape_string($vbulletin->GPC['with_username']);
			
			$user = $vbulletin->db->query("SELECT userid,username FROM " . TABLE_PREFIX . "user WHERE username = '" . $cleaned_username . "'");
			
			if($vbulletin->db->num_rows($user) < 1)
			{
				standard_error("The user you specified was not found.");
			}
			else
			{
				while($u = $vbulletin->db->fetch_array($user))
				{
					exec_header_redirect('inferno.php?do=pmlog&with=' . $u['userid']);
				}
			}
		}
		elseif(isset($_REQUEST['type']) and $_REQUEST['type'] == 'pmcontains')
		{
			$vbulletin->input->clean_array_gpc('p',array(
				'pm_contains' => TYPE_STR
			));
			
			$userid = $vbulletin->userinfo['userid'];
			$cleaned_input = $vbulletin->db->escape_string($vbulletin->GPC['pm_contains']);
			
			$search = $vbulletin->db->query("SELECT * FROM " . TABLE_PREFIX . "infernopmlog
				WHERE s_message LIKE '%" . $cleaned_input . "%'
					AND s_to = '" . $userid . "'
				OR s_message LIKE '%" . $cleaned_input . "%'
					AND s_to > -1
					AND s_from = '" . $userid . "'
				ORDER BY pmlogid DESC
			");
			
			if($vbulletin->db->num_rows($search) < 1)
			{
				standard_error("No messages found.");
			}
			else
			{
				$pmlog = '';
				while($shout = $vbulletin->db->fetch_array($search))
				{
					if($shout['s_to'] === $shout['s_from'])
					{
						continue;
					}
					
					$shout['s_to_name'] = fetch_userinfo($shout['s_to']);
					$shout['s_from_name'] = fetch_userinfo($shout['s_from']);
					$shout['s_to_name'] = fetch_musername($shout['s_to_name']);
					$shout['s_from_name'] = fetch_musername($shout['s_from_name']);
					
					$shout['date'] = vbdate($vbulletin->options['dateformat'], $shout['s_time'], $vbulletin->options['yestoday']);
					$shout['time'] = vbdate($vbulletin->options['timeformat'], $shout['s_time'], $vbulletin->options['yestoday']);
					
					$shout['s_message'] = htmlspecialchars_uni($shout['s_message']);
					
					$templater = vB_Template::create('inferno_pmlog_shout');
					$templater->register('shout', $shout);
					$pmlog .= $templater->render();
				}
				
				$navbits = construct_navbits(array('inferno.php' => $vbulletin->options['ishout_sbtitle'], 'inferno.php?do=pmlog' => 'Private Message Log', '' => 'Search'));
				$navbar = render_navbar_template($navbits);
				
				$pagetitle = 'Inferno Private Message Log';
				
				// ###### NOW YOUR TEMPLATE IS BEING RENDERED ######

				$templater = vB_Template::create('inferno_pmlog_main');
				$templater->register_page_templates();
				$templater->register('navbar', $navbar);
				$templater->register('pmlog', $pmlog);
				$templater->register('pagetitle', $pagetitle);
				print_output($templater->render());
			}
		}
		else
		{
			standard_error("You did not specify a username or string.");
		}
	}
	else
	{
		standard_error("This feature has been disabled by the administrator.");
	}
}

# ------------------------------------------------------- #
# Display the archive
# ------------------------------------------------------- #

if ($_REQUEST['do'] == 'archive')
{
	$navbits = construct_navbits(array('inferno.php' => $vbulletin->options['ishout_sbtitle'], '' => $vbphrase['ishout_archive']));
	$navbar = render_navbar_template($navbits);
	$perpage	= $vbulletin->input->clean_gpc('r', 'perpage', TYPE_UINT);
	$page		= $vbulletin->input->clean_gpc('r', 'page', TYPE_UINT);
	
	if($vbulletin->userinfo['posts'] < $vbulletin->options['ishout_min_posts'])
	{
		print_no_permission();
	}

	if (!$infernoshout->is_in_ug_list($vbulletin->options['ishout_archiveperm']))
	{
		standard_error($vbphrase['ishout_no_archive_permission']);
	}
	if (!$vbulletin->options['ishout_archiveonline'] && !$infernoshout->is_in_ug_list($vbulletin->options['ishout_admincommands']))
	{
		standard_error($vbphrase['ishout_archive_offline']);
	}

	$cansearch = $infernoshout->is_in_ug_list($infernoshout->vbulletin->options['ishout_archive_search']);

	$TopTen = '';

	$TS = $infernoshout->vbulletin->db->query_first("select count(*) as `ts` from " . TABLE_PREFIX . "infernoshout");
	$TSN = $TS['ts'];
	$TS = vb_number_format($TS['ts']);

	$T4 = $infernoshout->vbulletin->db->query_first("select count(*) as `T4` from " . TABLE_PREFIX . "infernoshout where s_time > " . (TIMENOW - (60 * 60 * 24)));
	$T4 = vb_number_format($T4['T4']);

	$TY = $infernoshout->vbulletin->db->query_first("select count(*) as `TY` from " . TABLE_PREFIX . "infernoshout where s_user = '{$vbulletin->userinfo['userid']}'");
	$TY = vb_number_format($TY['TY']);

	if (!$cansearch)
	{
		unset($_REQUEST['search']);
	}

	// Are we searching?
	if ($_REQUEST['search'])
	{
		// Sanitise search parameters
		$vbulletin->input->clean_array_gpc('r', array(
			'search'	=> TYPE_ARRAY_NOHTML,
		));

		if ($vbulletin->GPC['search']['time'] < 1)
		{
			$vbulletin->GPC['search']['time'] = 1;
		}

		$search = array(
			'phrase'	=> addslashes($vbulletin->GPC['search']['phrase']),
			'username'	=> addslashes($vbulletin->GPC['search']['username']),
			'time'		=> intval($vbulletin->GPC['search']['time']),
			'sort'		=> $vbulletin->GPC['search']['sort'] == 'new' ? 'new' : 'old',
		);

		$search_time = TIMENOW - ((60 * 60) * $search['time']);

		// Re-calculate total shout results
		$TSN = $infernoshout->vbulletin->db->query("
			select u.userid, s.sid
			from " . TABLE_PREFIX . "infernoshout s
			left join " . TABLE_PREFIX . "user u on(u.userid = s.s_user)
			where s.s_shout like '%{$search['phrase']}%'
			and u.username like '%" . htmlspecialchars_uni($search['username']) . "%'
			and s.s_time > $search_time
		");
		$TSN = $infernoshout->vbulletin->db->num_rows($TSN);
	}

	sanitize_pageresults($TSN, $page, $perpage, 40, 10);

	$limitlower = ($page - 1) * $perpage + 1;
	if ($limitlower <= 0)
	{
		$limitlower = 1;
	}

	$TT = $infernoshout->vbulletin->db->query('
			select s.*, count(s.sid) as `TS`, u.username, u.usergroupid from '.TABLE_PREFIX.'infernoshout s
			left join '.TABLE_PREFIX.'user u on (u.userid = s.s_user)
			where u.userid > 0
			group by s.s_user having TS > 0
			order by `TS` desc limit ' . intval($vbulletin->options['ishout_topshouters']));

	while ($TTS = $infernoshout->vbulletin->db->fetch_array($TT))
	{
		$TTS['username'] = fetch_musername($TTS, 'usergroupid');
	}

	$top_shouter_num = $vbulletin->db->num_rows($TT);

	$infernoshout->load_engine('shout');
	$shout = new shout;

	$shouthtml = $infernoshout->fetch_shouts($shout, '' . ($limitlower - 1) . ',' . $perpage, true, $_REQUEST['search'] ? $search : false);

	$pagenav = construct_page_nav($page, $perpage, $TSN, 'inferno.php?' . $vbulletin->session->vars['sessionurl'] . 'do=archive', ''
		. (!empty($vbulletin->GPC['perpage'])			? "&amp;pp=$perpage" : '')
		. (!empty($vbulletin->GPC['search']['phrase'])		? "&amp;search[phrase]={$vbulletin->GPC['search']['phrase']}" : '')
		. (!empty($vbulletin->GPC['search']['username'])	? "&amp;search[username]={$vbulletin->GPC['search']['username']}" : '')
		. (!empty($vbulletin->GPC['search']['time'])		? "&amp;search[time]={$vbulletin->GPC['search']['time']}" : '')
		. (!empty($vbulletin->GPC['search']['sort'])		? "&amp;search[sort]={$vbulletin->GPC['search']['sort']}" : '')
	);

	// Are we searching?
	if ($_REQUEST['search'])
	{
		// Sanitise search parameters
		$search['phrase']	= stripslashes($search['phrase']);
		$search['username']	= stripslashes($search['username']);
	}

	$templater = vB_Template::create('inferno_shoutbox_archive');
	$templater->register_page_templates();
	$templater->register('shouthtml', $shouthtml);
	$templater->register('TS', $TS);
	$templater->register('T4', $T4);
	$templater->register('TY', $TY);
	$templater->register('TT', $TT);
	$templater->register('TTS', $TTS);
	$templater->register('top_shouter_num', $top_shouter_num);
	$templater->register('TopTen', $TopTen);
	$templater->register('pagenav', $pagenav);
	$templater->register('navbar', $navbar);
	$templater->register('pagetitle', $pagetitle);
	$templater->register('cansearch', $cansearch);
	$templater->register('search', $search);
	$templater->register('TSN', $TSN);
	print_output($templater->render());
}

# ------------------------------------------------------- #
# Display the control panel/options
# ------------------------------------------------------- #

if ($_REQUEST['do'] == 'options')
{
	if($vbulletin->userinfo['userid'] > 0)
	{
		exec_header_redirect('profile.php?do=inferno_filters');
	}
}

# ------------------------------------------------------- #
# Display the detached shoutbox
# ------------------------------------------------------- #

if ($_REQUEST['do'] == 'detach')
{
	$navbits = construct_navbits(array('inferno.php' => $vbulletin->options['ishout_sbtitle'], '' => $vbphrase['ishout_detached']));
	$navbar = render_navbar_template($navbits);
	
	$infernoshout->vbulletin->options['ishout_height'] = $vbulletin->options['ishout_height_detach'];
	$infernoshout->load_editor_settings();
	$infernoshout->build_editor_select('colours', 'color');
	$infernoshout->build_editor_select('fonts', 'font-family');

	if($vbulletin->userinfo['posts'] < $vbulletin->options['ishout_min_posts'])
	{
		print_no_permission();
	}
	
	if (!$vbulletin->options['ishout_detach_online'] && !$infernoshout->is_in_ug_list($vbulletin->options['ishout_admincommands']))
	{
		standard_error($vbphrase['ishout_detach_offline']);
	}
	
	if ($infernoshout->vbulletin->options['ishout_largertext'])
	{
		$infernoshout->box = str_replace('smallfont', '', $infernoshout->box);
	}

	unset($infernoshout->box);
	
	$iShout = array();
	$iShout = $infernoshout->editor_settings;
	$iShout['script'] = $infernoshout->script;
	
	$templater = vB_Template::create('inferno_shoutbox_editor');
	$templater->render();

	$templater = vB_Template::create('inferno_shoutbox_detach_box');
	$templater->register('editor', $editor);
	$templater->register('iShout', $iShout);
	$HTML .= $templater->render();

	$templater = vB_Template::create('inferno_shoutbox_detach');
	$templater->register_page_templates();
	$templater->register('HTML', $HTML);
	$templater->register('navbar', $navbar);
	$templater->register('pagetitle', $pagetitle);
	print_output($templater->render());
}

$navbits = construct_navbits($navbits);
$templater = vB_Template::create('navbar');
$templater = vB_Template::create('GENERIC_SHELL');
print_output($templater->render());

?>