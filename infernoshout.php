<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */

error_reporting(E_ALL & ~E_NOTICE);

define('NO_REGISTER_GLOBALS', 1);
define('LOCATION_BYPASS', 1);
define('THIS_SCRIPT', 'infernoshout');

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
);

require_once('./global.php');
require_once(DIR . '/inferno/shoutbox/engine/inferno_engine.php');

if($vbulletin->options['ishout_csrfprotect'])
{
	// CSRF Fix by Alfa
	if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== "www.".$_SERVER['SERVER_NAME'])
	{
		if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) !== $_SERVER['SERVER_NAME'])
		{
			die();
		}
		
	}
}

# ------------------------------------------------------- #
# Display the messages
# ------------------------------------------------------- #

if ((empty($_REQUEST['do']) || $_REQUEST['do'] == 'messages'))
{
	$charset = $vbulletin->userinfo['lang_charset'];
	$charset = strtolower($charset) == 'iso-8859-1' ? 'windows-1252' : $charset;
	@header('Content-Type: text/html; charset=' . $charset);

	$infernoshout->load_engine('shout');

	$shout = new shout;
	//if(isset($_GET['detach']))
	

	echo $infernoshout->xml_document($infernoshout->fetch_shouts($shout, (isset($_REQUEST['detach']) ? $vbulletin->options['ishout_shouts_detach'] : $vbulletin->options['ishout_shouts'])));
	exit;
}

# ------------------------------------------------------- #
# Post a message
# ------------------------------------------------------- #

if ($_POST['do'] == 'shout' && ($message = trim($_REQUEST['message'])) != '')
{
	$vbulletin->input->clean_array_gpc('r', array(
		'message'	=> TYPE_STR,
	));

	$message = trim($vbulletin->GPC['message']);
	
	if ($vbulletin->userinfo['userid'] > 0)
	{
		$infernoshout->load_engine('shout');

		$shout = new shout;
		$shout->process($message);
	}
}

# ------------------------------------------------------- #
# Edit a shout
# ------------------------------------------------------- #

if ($_POST['do'] == 'editshout')
{
	$infernoshout->fetch_edit_shout(intval($_POST['shoutid']));
	exit;
}

if ($_REQUEST['do'] == 'getarchiveshout')
{
	$infernoshout->load_engine('shout');
	$shout = new shout;

	echo $infernoshout->xml_document($infernoshout->fetch_shouts($shout, '--nolim--', true, false, intval($_REQUEST['shoutid'])));
	exit;
}

if ($_POST['do'] == 'doeditshout')
{
	$vbulletin->input->clean_array_gpc('p', array(
		'shout'	=> TYPE_STR,
	));

	$infernoshout->do_edit_shout(intval($_POST['shoutid']));
	exit;
}

# ------------------------------------------------------- #
# Update Style Properties
# ------------------------------------------------------- #

if ($_REQUEST['do'] == 'styleprops')
{
	$vbulletin->input->clean_array_gpc('r', array(
		'colour'	=> TYPE_NOHTML,
		'fontfamily'	=> TYPE_NOHTML,
	));

	$_REQUEST['colour']	= $vbulletin->GPC['colour'];
	$_REQUEST['fontfamily']	= $vbulletin->GPC['fontfamily'];

	if ($vbulletin->userinfo['userid'] > 0)
	{
		$infernoshout->set_style_properties();
	}
}

# ------------------------------------------------------- #
# Show active users
# ------------------------------------------------------- #

if ($_POST['do'] == 'userlist')
{
	echo $infernoshout->fetch_users_list($shout);
	exit;
}

# ------------------------------------------------------- #
# Fetch smilies
# ------------------------------------------------------- #

if ($_POST['do'] == 'fetchsmilies')
{
	echo $infernoshout->fetch_smilies($infernoshout->vbulletin->options['ishout_smiliesshow']);
	exit;
}

	$navbits = construct_navbits($navbits);
	$templater = vB_Template::create('navbar');
	$templater = vB_Template::create('GENERIC_SHELL');
	print_output($templater->render());

?>