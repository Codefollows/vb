<?php

/* ======================================================================*\
  || #################################################################### ||
  || # vBulletin 4.2.5 - Nulled by vBWarez.org
  || # ---------------------------------------------------------------- # ||
  || # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
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
 * @date		$Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
 */
class vB_ActivityStream_Populate_Forum_Post extends vB_ActivityStream_Populate_Base
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
		$typeid = vB::$vbulletin->activitystream['forum_post']['typeid'];
		$this->delete($typeid);

		if (!vB::$vbulletin->activitystream['forum_post']['enabled'])
		{
			return;
		}

		$timespan = TIMENOW - vB::$vbulletin->options['as_expire'] * 60 * 60 * 24;
		vB::$db->query_write("
			INSERT INTO " . TABLE_PREFIX . "activitystream
				(userid, dateline, contentid, typeid, action)
				(SELECT
					post.userid, post.dateline, post.postid, '{$typeid}', 'create'
				FROM " . TABLE_PREFIX . "post AS post
				INNER JOIN " . TABLE_PREFIX . "thread AS thread ON (post.threadid = thread.threadid)
				WHERE
					post.dateline >= {$timespan}
						AND
					post.postid <> thread.firstpostid
						AND
					thread.open <> 10
					" . (vB::$vbulletin->options['vbcmsforumid'] ? "AND thread.forumid <> " . vB::$vbulletin->options['vbcmsforumid'] : "") . "
				)
		");
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 19:19, Wed May 10th 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/