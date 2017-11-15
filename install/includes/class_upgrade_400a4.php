<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.5 - Nulled by vBWarez.org
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
/*
if (!isset($GLOBALS['vbulletin']->db))
{
	exit;
}
*/

class vB_Upgrade_400a4 extends vB_Upgrade_Version
{
	/*Constants=====================================================================*/

	/*Properties====================================================================*/

	/**
	* The short version of the script
	*
	* @var	string
	*/
	public $SHORT_VERSION = '400a4';

	/**
	* The long version of the script
	*
	* @var	string
	*/
	public $LONG_VERSION  = '4.0.0 Alpha 4';

	/**
	* Versions that can upgrade to this script
	*
	* @var	string
	*/
	public $PREV_VERSION = '4.0.0 Alpha 3';

	/**
	* Beginning version compatibility
	*
	* @var	string
	*/
	public $VERSION_COMPAT_STARTS = '';

	/**
	* Ending version compatibility
	*
	* @var	string
	*/
	public $VERSION_COMPAT_ENDS   = '';

	/**
	* Step #1
	*
	*/
	function step_1()
	{
		$this->run_query(
			sprintf($this->phrase['vbphrase']['create_table'], TABLE_PREFIX . 'bbcode_video'),
			"CREATE TABLE " . TABLE_PREFIX . "bbcode_video (
			  providerid INT UNSIGNED NOT NULL AUTO_INCREMENT,
			  tagoption VARCHAR(50) NOT NULL DEFAULT '',
			  provider VARCHAR(50) NOT NULL DEFAULT '',
			  url VARCHAR(100) NOT NULL DEFAULT '',
			  regex_url VARCHAR(254) NOT NULL DEFAULT '',
			  regex_scrape VARCHAR(254) NOT NULL DEFAULT '',
			  embed MEDIUMTEXT,
			  priority INT UNSIGNED NOT NULL DEFAULT '0',
			  PRIMARY KEY  (providerid),
			  UNIQUE KEY tagoption (tagoption),
			  KEY priority (priority),
			  KEY provider (provider)
			)",
			self::MYSQL_ERROR_TABLE_EXISTS
		);
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 19:19, Wed May 10th 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/
