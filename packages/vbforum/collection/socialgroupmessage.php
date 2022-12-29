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
 * ContentType Collection
 * Fetches a collection of contenttypes.
 *
 * ItemId
 * The itemid is an integer referring to contenttype.contenttypeid
 *
 * @package vBulletin
 * @author vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */
class vBForum_Collection_SocialGroupMessage extends vB_Collection
{
	/*Config Info======================================================================*/

	protected $item_package = 'vBForum';
	protected $item_class = 'SocialGroupMessage';

	/*LoadInfo======================================================================*/

	/**
	 * Fetches the SQL for loading.
	 * $required_query is used to identify which query to build for classes that
	 * have multiple queries for fetching info.
	 *
	 * This can safely be based on $this->required_info as long as a consitent
	 * flag is used for identifying the query.
	 *
	 * @param int $required_query				- The required query
	 * @param bool $force_rebuild				- Whether to rebuild the string
	 *
	 * @return string
	 */
	protected function getLoadQuery($required_query = self::QUERY_BASIC, $force_rebuild = false)
	{
		// Hooks should check the required query before populating the hook vars
		$hook_query_fields = $hook_query_join = $hook_query_where = '';
		($hook = vBulletinHook::fetch_hook($this->query_hook)) ? eval($hook) : false;

		if (self::QUERY_BASIC == $required_query)
		{
			return $query = "
				SELECT 
					groupmessage.gmid as itemid,
					groupmessage.discussionid,
					groupmessage.postuserid,
					groupmessage.postusername,
					groupmessage.dateline,
					groupmessage.state,
					groupmessage.title,
					groupmessage.pagetext,
					groupmessage.ipaddress,
					groupmessage.allowsmilie,
					groupmessage.reportthreadid " .
					$hook_query_fields . "
				FROM " . TABLE_PREFIX . "groupmessage AS groupmessage " .
					$hook_query_join . "
				WHERE groupmessage.gmid IN (" . implode(',', $this->itemid) . ") 
					$hook_query_where";
		}

		throw (new vB_Exception_Model('Invalid query id \'' . htmlspecialchars_uni($required_query) . 
			'\'specified for social group message collection: ' . htmlspecialchars_uni($query)));
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
