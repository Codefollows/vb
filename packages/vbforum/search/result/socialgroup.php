<?php if (!defined('VB_ENTRY')) die('Access denied.');

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

/**
 * @package vBulletin
 * @subpackage Search
 * @author Kevin Sours, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */

require_once (DIR . '/vb/search/result.php');
require_once (DIR . '/vb/legacy/socialgroup.php');
require_once (DIR . '/vb/search/indexcontroller/null.php');
/**
 * Result Implementation for Social Groups
 *
 * @see vB_Search_Result
 * @package vBulletin
 * @subpackage Search
 */
class vBForum_Search_Result_SocialGroup extends vB_Search_Result
{

	public static function create($id)
	{
		$result = new vBForum_Search_Result_SocialGroup();
		$result->group = vB_Legacy_SocialGroup::create_from_id($id);
		if ($result->group == null)
		{
			return new vB_Search_Result_Null();
		}
		return $result;
	}

	public static function create_array($ids)
	{
		$results = array();
		foreach (vB_Legacy_SocialGroup::create_array($ids) as $group)
		{
			$result = new vBForum_Search_Result_SocialGroup();
			$result->group = $group;
			$results[$group->get_field('groupid')] = $result;
		}

		$ordered_items = array();
		foreach($ids AS $item_key)
		{
			if(isset($results[$item_key]))
			{
				$ordered_items[$item_key] = $results[$item_key];
				unset($results[$item_key]);
			}
		}

		return $ordered_items;
	}


	protected function __construct() {}

	public function get_contenttype()
	{
		return vB_Search_Core::get_instance()->get_contenttypeid('vBForum', 'SocialGroup');
	}

	public function can_search($user)
	{
		if ($this->group != null)
		{
			return $this->group->can_view($user);
		}
		return false;
	}

	public function render($current_user, $criteria, $template_name = '')
	{
		global $vbulletin;

		if (!strlen($template_name)) {
			$template_name = 'search_results_socialgroup';
		}
		$group = $this->group->get_record();

		//some aliasing to make the template happy
		$group['categoryid'] = $group['socialgroupcategoryid'];

		global $show;
		$show['gminfo'] = $vbulletin->options['socnet_groups_msg_enabled'];
		$show['pictureinfo'] = ($vbulletin->options['socnet_groups_pictures_enabled']);
		$show['pending_link'] =  ($this->group->has_modperm('caninvitemoderatemembers', $current_user) AND
			$group['moderatedmembers'] > 0);
		$show['lastpostinfo'] = ($group['lastposterid']);
		$show['category_names'] = true;

		$template = vB_Template::create($template_name);
		$template->register('lastpostalt', $show['pictureinfo'] ? 'alt2' : 'alt1');
		$template->register('group', $group);
		$template->register('show', $show);
		$template->register('dateformat', $vbulletin->options['dateformat']);
		$template->register('timeformat', $vbulletin->options['timeformat']);
		return $template->render();
	}


	/*** Returns the primary id. Allows us to cache a result item.
	 *
	 * @result	integer
	 ***/
	public function get_id()
	{
		if (isset($this->group) and ($groupid = $this->group->get_field('groupid')))
		{
			return $groupid;
		}
		return false;
	}

	private $group;
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
