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

/**
 * Base class for index controllers
 *
 * Defines the index functions that all searchable types need to support.
 * Should allow a common admin interface to base indexing.
 *
 * @package vBulletin
 * @subpackage Search
 */
abstract class vB_Search_IndexController
{
	public function get_contenttypeid()
	{
		return $this->contenttypeid;
	}

	public function get_groupcontenttypeid()
	{
		return $this->groupcontenttypeid;
	}

	/**
	*	Return the maximum id for the item type
	*
	*	Should be overridden by the specific type function.  If it is not
	* then loop through to the max id logic won't work correctly.
	*
	* @return int
	*/
	public function get_max_id()
	{
		return 0;
	}

	/**
	 * Index an item.
	 *
	 * @param int id of item to index.
	 */
	abstract public function index($id);

	/**
	 * Index a range of items based on id (inclusive)
	 *
	 * @param start first document to index
	 * @param end last document to index.
	 */
	abstract public function index_id_range($start, $end);

	/**
	 *	Remove items from the index.
	 *
	 *	This should be done when an item is deleted
	 *	from the database (soft deletes will generally not be a delete since such
	 *	items will generally be wanted in the index for mod searches).
	 *
	 * Can also be used when bulk reindexing occurs to ensure that items that
	 * may have been deleted but not removed from the index get removed on
	 * reindex.
	 *
	 *	Deletion of non existant item must be handled (primarily to support
	 * range deletion intened to remove orphaned items)
	 *
	 * @param int id of item to delete.
	 */

	public function delete($id)
	{
		vB_Search_Core::get_instance()->get_core_indexer()->delete($this->get_contenttypeid(), $id);
	}

	/**
	 * Delete a range of items
	 *
	 * @param int $start
	 * @param int $end
	 */
	public function delete_id_range($start, $end)
	{
		$indexer = vB_Search_Core::get_instance()->get_core_indexer();
		for ($i = $start; $i <= $end; $i++)
		{
			$indexer->delete($id);
		}
	}


	protected $contenttypeid = 0;
	protected $groupcontenttypeid = 0;
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/