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

require_once (DIR . '/vb/search/type.php');
require_once (DIR . '/packages/vbforum/search/result/forum.php');

// ###################### Start vBForum_Search_Type_Forum ######################
// There is a type file for each search type. This is the one for forums

/**
 * vBForum_Search_Type_Forum
 *
 * @package
 * @author ebrown
 * @copyright Copyright (c) 2009
 * @version $Id: forum.php 92140 2016-12-31 04:26:15Z pmarsden $
 * @access public
 */
class vBForum_Search_Type_Forum extends vB_Search_Type
{

	// ###################### Start create_item ######################
	/**
	* vBForum_Search_Type_Forum::create_item()
	* This creates the type object
	*
	* @param integer $id
	* @return object vBForum_Search_Result_Forum
	*/
	public function create_item($id)
	{
		return vBForum_Search_Result_Forum::create($id);
	}

	// ###################### Start get_display_name ######################
	/**
	* vBForum_Search_Type_Forum::get_display_name()
	* This returns the display name
	*
	* @return string
	*/
	public function get_display_name()
	{
		return new vB_Phrase('search', 'searchtype_forums');
	}

	// ###################### Start cansearch ######################
	/**
	* vBForum_Search_Type_Forum::cansearch()
	* Each search type has some responsibilities, one of which is to tell
	* whether it is searchable
	*
	* @return true
	*/
	public function cansearch()
	{
		return true;
	}

	// ###################### Start listUi ######################
	/**
	 * vBForum_Search_Type_Forum::listUi()
	 * This prepares the HTML for the user to search for forums
	 * 
	 * @param  [type] $prefs         the array of user preferences
	 * @param  [type] $contenttypeid added for PHP 5.4 strict standards compliance
	 * @param  [type] $registers     added for PHP 5.4 strict standards compliance
	 * @param  [type] $template_name added for PHP 5.4 strict standards compliance
	 * @return $html: complete html for the search elements
	 */
	public function listUi($prefs = null, $contenttypeid = null, $registers = null, $template_name = null)
	{
		global $vbulletin, $show;
		$template = vB_Template::create('search_input_forum');
		$template->register('securitytoken', $vbulletin->userinfo['securitytoken']);
		$template->register('contenttypeid', vB_Search_Core::get_instance()->get_contenttypeid('vBForum', 'Forum'));
		$template->register('show', $show);
		$this->setPrefs($template, $prefs,  array(
			'select'=> array('titleonly', 'threadless', 'forumdateline', 'beforeafter', 'postless', 'sortby'),
			'cb' => array('nocache'),
		 	'value' => array('query', 'threadlimit', 'postlimit') ) );
		vB_Search_Searchtools::searchIntroRegisterHumanVerify($template);

		($hook = vBulletinHook::fetch_hook('search_listui_complete')) ? eval($hook) : false;

		return $template->render();
	}

	public function add_advanced_search_filters($criteria, $registry)
	{
		if ($registry->GPC['threadlimit'])
		{
			$criteria->add_display_strings('forumthreadlimit',
				vB_Search_Searchtools::getCompareString($registry->GPC['threadless'])
				. $registry->GPC['threadlimit'] . ' ' . $vbphrase['threads']);
			$op = $registry->GPC['threadless'] ? vB_Search_Core::OP_LT : vB_Search_Core::OP_GT;
			$criteria->add_filter('forumthreadlimit', $op, $registry->GPC['threadlimit'], true);
		}

		if ($registry->GPC['postlimit'])
		{
			$criteria->add_display_strings('forumthreadlimit',
				vB_Search_Searchtools::getCompareString($registry->GPC['postless'])
				. $registry->GPC['postlimit'] . ' ' . $vbphrase['posts']);

			$op = $registry->GPC['postless'] ? vB_Search_Core::OP_LT : vB_Search_Core::OP_GT;
			$criteria->add_filter('forumpostlimit', $op, $registry->GPC['postlimit'], true);
		}

		if ($registry->GPC['forumdateline'])
		{
			if (is_numeric($registry->GPC['forumdateline']))
			{
				$dateline = TIMENOW - ($this->forumdateline * 86400);
			}
			else
			{
				$current_user = new vB_Legacy_CurrentUser();;
				$dateline = $current_user->get_field('lastvisit');
			}

			$op = $registry->GPC['beforeafter'] == 'before' ? vB_Search_Core::OP_LT : vB_Search_Core::OP_GT;
			$criteria->add_filter('forumpostdateline', $op, $dateline, true);
			$this->set_display_date($criteria, $registry->GPC['forumdateline'], $registry->GPC['beforeafter']);

		}

		($hook = vBulletinHook::fetch_hook('search_advanced_filters')) ? eval($hook) : false;
	}

	public function get_db_query_info($fieldname)
	{
		$result['corejoin']['forum'] = "JOIN " . TABLE_PREFIX . "forum AS forum ON (
			searchcore.contenttypeid = " . $this->get_contenttypeid() . " AND searchcore.primaryid = forum.forumid)
		";

		$result['groupjoin']['forum'] = "JOIN " . TABLE_PREFIX . "forum AS forum ON (
			searchgroup.contenttypeid = " . $this->get_contenttypeid() . " AND searchgroup.groupid = forum.forumid)
		";

		$result['table'] = 'forum';

		if ($fieldname == 'forumthreadlimit')
		{
			$result['field'] = 'threadcount';
		}
		else if ($fieldname == 'forumpostlimit')
		{
			$result['field'] = 'replycount';
		}
		else if ($fieldname == 'forumdateline')
		{
			$result['field'] = 'lastpost';
		}
		else
		{
			$result = false;
		}

		($hook = vBulletinHook::fetch_hook('search_dbquery_info')) ? eval($hook) : false;

		return $result;
	}

	/**
	 * vBForum_Search_Type_Forum::set_display_date()
	 * This function sets the display date for the forum search.
	 * takes no parameters and returns none
	 *
	 * @return nothing
	 */
	private function set_display_date($criteria, $forumdateline, $beforeafter)
	{
		global $vbphrase, $vbulletin;
		if (isset($beforeafter) AND isset($forumdateline))
		{
			if (is_numeric($forumdateline))
			{
				$dateline = TIMENOW - ($forumdateline * 86400);
				$criteria->add_display_strings('forumpostdateline',
				$vbphrase['last_post'] . ' ' . $vbphrase[$beforeafter] . ' '
					. date($vbulletin->options['dateformat'], $dateline));
			}
			else
			{
				$criteria->add_display_strings('forumpostdateline',
				$vbphrase['last_post'] . ' ' . $vbphrase[$beforeafter] . ' '
					. $vbphrase['last_visit'] );
			}
		}

	}


// ###################### Start additional_pref_defaults ######################
/**
* vBForum_Search_Type_Forum::additional_pref_defaults()
* Each search type has some responsibilities, one of which is to tell
* what are its defaults
*
* @return array
*/
	public function additional_pref_defaults()
	{
		$retval = array(
			'query'         => '',
			'titleonly'     => 0,
			'nocache'       => '',
			'threadless'    => 0,
			'threadlimit'   => '',
			'forumdateline' => 0,
			'beforeafter'   => 'after',
			'postless'      => 0,
			'postlimit'     => '',
			'sortby'		=> 'dateline'
		);

		($hook = vBulletinHook::fetch_hook('search_pref_defaults')) ? eval($hook) : false;

		return $retval;
	}

	protected $package = "vBForum";
	protected $class = "Forum";

	protected $type_globals = array (
		'threadless'     => TYPE_UINT,
		'threadlimit'    => TYPE_UINT,
		'forumdateline'  => TYPE_NOHTML,
		'postless'       => TYPE_UINT,
		'postlimit'      => TYPE_UINT,
		'beforeafter'    => TYPE_NOHTML);
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
