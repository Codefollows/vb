<?php

/* ======================================================================*\
  || #################################################################### ||
  || # vBulletin 4.2.6 by vBS
  || # ---------------------------------------------------------------- # ||
  || # Copyright ©2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
  || # This file may not be redistributed in whole or significant part. # ||
  || # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
  || #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
  || #################################################################### ||
  \*====================================================================== */

/**
 * Class to populate the activity stream from existing content
 *
 * @package	vBulletin
 * @version	$Revision: 92140 $
 * @date		NulleD By - vBSupport.org
 */
class vB_ActivityStream_Populate_Forum_Visitormessage extends vB_ActivityStream_Populate_Base
{
	/**
	 * Constructor - set Options
	 *
	 */
	public function __construct()
	{
		return parent::__construct();
	}

	/*
	 * Don't get: Deleted threads, redirect threads, CMS comment threads
	 *
	 */
	public function populate()
	{
		$typeid = vB::$vbulletin->activitystream['forum_visitormessage']['typeid'];
		$this->delete($typeid);

		if (!vB::$vbulletin->activitystream['forum_visitormessage']['enabled'])
		{
			return;
		}

		$timespan = TIMENOW - vB::$vbulletin->options['as_expire'] * 60 * 60 * 24;
		vB::$db->query_write("
			INSERT INTO " . TABLE_PREFIX . "activitystream
				(userid, dateline, contentid, typeid, action)
				(SELECT
					postuserid, dateline, vmid, '{$typeid}', 'create'
				FROM " . TABLE_PREFIX . "visitormessage
				WHERE
					dateline >= {$timespan}
				)
		");
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/