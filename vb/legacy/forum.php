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
 * @version $Revision: 92548 $
 * @since $Date: 2017-01-23 07:05:29 -0800 (Mon, 23 Jan 2017) $
 * @copyright vBulletin Solutions Inc.
 */

require_once (DIR . "/vb/legacy/dataobject.php");

/**
 * Legacy forum wrapper
 *
 */
class vB_Legacy_Forum extends vB_Legacy_Dataobject
{
	// VBIV-9878
	private $noncachedfields = array(
		'podcast' => 1, // This is normally cached (unless Install or Upgrade).
		'replycount' => 1,
		'lastpost' => 1,
		'lastposter' => 1,
		'lastposterid' => 1,
		'lastpostid' => 1, // This is actually cached but should be treated as uncached.
		'lastthread' => 1,
		'lastthreadid' => 1,
		'lasticonid' => 1,
		'lastprefixid' => 1,
		'threadcount' => 1
	);
 
	// VBIV-9878
	public function has_field($field)
	{
		return ((isset($this->record[$field])) OR isset($this->noncachedfields[$field]));
	}
	
	public function get_field($field)
	{
		// Update cache if field not currently cached.
		if (isset($this->noncachedfields[$field]))
		{
			$this->record = fetch_foruminfo($this->record['forumid'], false);
		}
		
		return parent::get_field($field);
	}

	public function get_fieldnames()
	{
		return array_merge(array_keys($this->record), array_keys($this->noncachedfields));
	}

	/**
	 * Create object from and existing record
	 *
	 * @param int $foruminfo
	 * @return vB_Legacy_Forum
	 */
	public static function create_from_record($foruminfo)
	{
		$forum = new vB_Legacy_Forum();
		$forum->set_record($foruminfo);
		return $forum;
	}

	/**
	 * Load object from an id
	 *
	 * @param int $id
	 * @return vB_Legacy_Forum
	 */
	public static function create_from_id($id)
	{
		$foruminfo = fetch_foruminfo($id);

		//try to work with bad data integrity.  There are dbs out there
		//with threads that belong to a nonexistant forum.
		if ($foruminfo)
		{
			return self::create_from_record($foruminfo);
		}
		else
		{
			return null;
		}
	}

	/**
	 * constructor -- protectd to force use of factory methods.
	 */
	protected function __construct() {}

	//*********************************************************************************
	// Derived Getters

	/**
	 * Get the url for the forum page
	 *
	 * @return string
	 */
	public function get_url()
	{
		return fetch_seo_url('forum', $this->record);
	}

	/**
	 * Does this forum allow icons
	 *
	 * @return boolean
	 */
	public function allow_icons()
	{
		global $vbulletin;
		return $vbulletin->forumcache [$this->get_field('forumid')] ['options'] &
			$vbulletin->bf_misc_forumoptions ['allowicons'];
	}


	/**
	 * Does this forum allow icons
	 *
	 * @return boolean
	 */
	public function get_last_read_by_current_user($user)
	{
		global $vbulletin;
		if (!$user->isGuest())
		{
			//deal with the fact that the forum cache (from which we likely loaded this object)
			//doesn't have the forumread field.
			//we should consider a query to just look the value up for this forum -- it will likely
			//be faster in actual use and will almost certainly be simpler
			if (!$this->has_field('forumread'))
			{
				if (array_key_exists('forumread', $vbulletin->forumcache[$this->get_field('forumid')]))
				{
					$this->set_field('forumread', $vbulletin->forumcache[$this->get_field('forumid')]['forumread']);
				}
				else
				{
					//if we don't have forum read in the cache, then reload the cache.
					//this implicitly references the current
					cache_ordered_forums(1);
					$this->set_field('forumread', $vbulletin->forumcache[$this->get_field('forumid')]['forumread']);
				}
			}
			return max($this->get_field('forumread'), (TIMENOW - ($vbulletin->options['markinglimit'] * 86400)));
		}
		else
		{
			$forumview = intval(fetch_bbarray_cookie('forum_view', $this->get_field('forumid')));

			//use which one produces the highest value, most likely cookie
			return ($forumview > $vbulletin->userinfo['lastvisit'] ? $forumview : $vbulletin->userinfo['lastvisit']);
		}
	}

	//*********************************************************************************
	//	High level permissions
	public function can_view($user)
	{
		return !in_array($this->get_field('forumid'), $user->getHiddenForums());
	}

	public function can_search($user)
	{
		return !in_array($this->get_field('forumid'), $user->getUnsearchableForums());
	}

	//*********************************************************************************
	//	Data operation functions

	/**
	 * Decrement the threadcount for the forum.
	 *
	 * Also decrements the reply count
	 */
	public function decrement_threadcount()
	{
		global $vbulletin;
		// deleting a thread also deletes a post so decrement the
		// reply counter as well
		// just decrement the reply and thread counter for the forum
		$forumdm = datamanager_init('Forum', $vbulletin, ERRTYPE_SILENT);
		$forumdm->set_existing($this->record);
		$forumdm->set('threadcount', 'threadcount - 1', false);
		$forumdm->set('replycount', 'replycount - 1', false);
		$forumdm->save();
		unset($forumdm);
	}

	/**
	 * Decrement the replycount for the forum.
	 */
	public function decrement_replycount()
	{
		global $vbulletin;
		// just decrement the reply counter
		$forumdm = datamanager_init('Forum', $vbulletin, ERRTYPE_SILENT);
		$forumdm->set_existing($this->record);
		$forumdm->set('replycount', 'replycount - 1', false);
		$forumdm->save();
		unset($forumdm);
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92548 $
|| # $Date: 2017-01-23 07:05:29 -0800 (Mon, 23 Jan 2017) $
|| ####################################################################
\*======================================================================*/
