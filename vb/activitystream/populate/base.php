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
 * Class to populate
 *
 * @package	vBulletin
 * @version	$Revision: 92140 $
 * @date		NulleD By - vBSupport.org
 */
class vB_ActivityStream_Populate_Base
{
	/**
	 * Constructor - set Options
	 *
	 */
	public function __construct()
	{

	}

	/*
	 * Delete specific type from the stream
	 *
	 * @param	int	activitystreamtype typeid
	 */
	protected function delete($typeid)
	{
		vB::$db->query_write("
			DELETE FROM " . TABLE_PREFIX . "activitystream
			WHERE
				typeid = " . intval($typeid)
		);
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/