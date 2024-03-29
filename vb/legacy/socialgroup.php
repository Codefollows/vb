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
 * @subpackage Legacy
 * @author Kevin Sours, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */

require_once (DIR . "/vb/legacy/dataobject.php");
require_once (DIR . "/includes/functions_socialgroup.php");

/**
 * Legacy Social Group wrapper
 *
 */
class vB_Legacy_SocialGroup extends vB_Legacy_Dataobject
{
	/**
	 * Load object from an id
	 *
	 * @param int $id
	 * @return vB_Legacy_Forum
	 */
	public static function create_from_id($id)
	{
		//this is cached for the page load and we rely upon that.  It also loads way more 
		//data than just the base record, some of which depends on the current user/options
		//we should try to provide some getters for stuff that depends on those fields
		// (either derived data or related objects) so that we can rework stuff here 
		// without massive case statements in get_field
		$record = fetch_socialgroupinfo($id);
		if ($record)
		{
			$group = new vB_Legacy_SocialGroup();
			$group->set_record($record);
			return $group;
		}
		else
		{
			return null;
		}
	}

	public static function create_array($ids)
	{
		$groups = array();
		$records = fetch_socialgroupinfo_array($ids);	
		foreach ($records as $record)
		{
			$group = new vB_Legacy_SocialGroup();
			$group->set_record($record);
			$groups[] = $group;
		}
		return $groups;
	}

	

	/**
	 * constructor -- protectd to force use of factory methods.
	 */
	protected function __construct() {}

	//*********************************************************************************
	// Derived Getters

//	public function get_last_read_by_current_user($user)
//	{
//		
//	}

	public function is_owner($user)
	{
		return ($this->get_field('creatoruserid') == vB::$vbulletin->userinfo['userid']);
	}

	//*********************************************************************************
	//	High level permissions
	public function can_view($user)
	{
		if (
			!$user->hasPermission('forumpermissions', 'canview') OR 
			!$user->hasPermission('socialgrouppermissions', 'canviewgroups')
		)
		{
			return false;
		}
		return $this->record['canviewcontent'];
	}

	/**
	*	
	*/
	public function has_modperm($perm, $user)
	{
		//$user is not currently used, fetch_socialgroup_modperm implicitly picks up 
		//the current user information.  We should fix that.
		return fetch_socialgroup_modperm($perm, $this->record);
	}

}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
