<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.6 by vBS
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);
ignore_user_abort(true);
chdir('./../');

// ##################### DEFINE IMPORTANT CONSTANTS #######################
define('NO_IMPORT_DOTS', true);
define('NOZIP', 1);
if (!defined('VB_AREA')) { define('VB_AREA', 'Upgrade'); }
define('TIMENOW', time());
if (!defined('VB_ENTRY')) { define('VB_ENTRY', 'upgrade.php'); }

require_once('./install/includes/language.php');

// Wont ever run on PHP 4 //
if (version_compare(PHP_VERSION, '4.9.9', '<'))
{
	echo 'vBulletin is not compatible with PHP 4';
	exit;
}

$cli = array();
if (VB_AREA == 'Upgrade')
{
	// Save for later CLI Processing //
	$cli['cliver'] = isset($argv[1]) ? trim($argv[1]) : 'xxx';
	$cli['clionly'] = (isset($argv[2]) AND ($argv[2] == 'y')) ? 1 : 0;
}

// ########################## REQUIRE BACK-END ############################
require_once('./install/includes/class_upgrade.php');
require_once('./install/init.php');
require_once(DIR . '/includes/functions_misc.php');

if (function_exists('set_time_limit') AND !SAFEMODE)
{
	@set_time_limit(0);
}

$vbulletin->cli =& $cli;
$verify =& vB_Upgrade::fetch_library($vbulletin, $phrases, '', !defined('VBINSTALL'));

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
