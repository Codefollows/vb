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
 * @package vBulletin
 * @subpackage Search
 * @author Kevin Sours, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */

require_once (DIR . '/vb/search/type.php');
require_once (DIR . '/packages/vbforum/search/result/event.php');

/**
 * @package vBulletin
 * @subpackage Search
 */
class vBForum_Search_Type_Event extends vB_Search_Type
{
	public function fetch_validated_list($user, $ids, $gids)
	{
		$list = array_fill_keys($ids, false);
		$items = vBForum_Search_Result_Event::create_array($ids);
		foreach ($items as $id => $item)
		{
			if ($item->can_search($user))
			{
				$list[$id] = $item;
			}
		}
		
		$retval = array('list' => $list, 'groups_rejected' => array());

		($hook = vBulletinHook::fetch_hook('search_validated_list')) ? eval($hook) : false;

		return $retval;
	}

	public function prepare_render($user, $results)
	{
		($hook = vBulletinHook::fetch_hook('search_prepare_render')) ? eval($hook) : false;
	}

	public function get_display_name()
	{
		return new vB_Phrase('search', 'searchtype_events');
	}

	public function create_item($id)
	{
		return vBForum_Search_Result_Event::create($id);
	}

	/**
	 * You can create from an array also
	 *
	 * @param integer $id
	 * @return object
	 */
	public function create_array($ids)
	{
		return vBForum_Search_Result_Event::create_array($ids);
	}
	protected $package = "vBForum";
	protected $class = "Event";
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
