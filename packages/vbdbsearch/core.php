<?php if (!defined('VB_ENTRY')) die('Access denied.');

/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.6 by vBS
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/

/**
 * @package vbdbsearch
 * @author Kevin Sours, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */

require_once (DIR . '/packages/vbdbsearch/indexer.php');
require_once (DIR . '/packages/vbdbsearch/coresearchcontroller.php');
require_once (DIR . '/packages/vbdbsearch/postindexcontroller.php');

/**
*/
class vBDBSearch_Core extends vB_Search_Core
{
	/**
	 * Enter description here...
	 *
	 */
	static function init()
	{
		//register implementation objects with the search system.
		$search = vB_Search_Core::get_instance();
		$search->register_core_indexer(new vBDBSearch_Indexer());
		$search->register_index_controller('vBForum', 'Post', new vBDBSearch_PostIndexController());
		$__vBDBSearch_CoreSearchController = new vBDBSearch_CoreSearchController();
		$search->register_default_controller($__vBDBSearch_CoreSearchController);
//		$search->register_search_controller('vBForum', 'Post',$__vBDBSearch_CoreSearchController);
	}

}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/

