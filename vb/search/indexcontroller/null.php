<?php if (!defined('VB_ENTRY')) die('Access denied.');

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
/**
 * @package indexer_null
 * @author Ed Brown, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
 * @copyright vBulletin Solutions Inc.
 */

// ###################### class  vb_Search_Indexer_Null #######################
// This class simply returns false, regardless of what function call it receives. It
// should normally be handled as static, but no problems are caused
// if it is instantiated.

class vb_Search_Indexcontroller_Null{
	public function __call($name, $arguments)
	{
		return false;
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 19:19, Wed May 10th 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/
