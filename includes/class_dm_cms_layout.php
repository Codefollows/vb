<?php
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

if (!class_exists('vB_DataManager'))
{
	exit;
}

/**
* Class to do data save/delete operations for layouts
*
* @package	vBulletin
* @version	$Revision: 92140 $
* @date		NulleD By - vBSupport.org
*/
class vB_DataManager_Cms_Layout extends vB_DataManager
{
	/**
	* Array of recognised and required fields for a CMS layout
	*
	* @var	array
	*/
	var $validfields = array(
		'layoutid'      => array(TYPE_UINT,       REQ_INCR, 'return ($data > 0);'),
		'title'         => array(TYPE_NOHTMLCOND, REQ_YES),
		'contentcolumn' => array(TYPE_UINT,       REQ_YES),
		'contentindex'  => array(TYPE_UINT,       REQ_YES),
		'gridid'        => array(TYPE_UINT,       REQ_YES),
	);

	/**
	* The main table this class deals with
	*
	* @var	string
	*/
	var $table = 'cms_layout';

	/**
	* Condition for update query
	*
	* @var	array
	*/
	var $condition_construct = array('layoutid = %1$d', 'layoutid');

	/**
	* Constructor - checks that the registry object has been passed correctly.
	*
	* @param	vB_Registry	Instance of the vBulletin data registry object - expected to have the database object as one of its $this->db member.
	* @param	integer		One of the ERRTYPE_x constants
	*/
	function __construct(&$registry, $errtype = ERRTYPE_STANDARD)
	{
		parent::__construct($registry, $errtype);

		($hook = vBulletinHook::fetch_hook('cms_layout_start')) ? eval($hook) : false;
	}

	/**
	* Any checks to run immediately before saving. If returning false, the save will not take place.
	*
	* @param	boolean	Do the query?
	*
	* @return	boolean	True on success; false if an error occurred
	*/
	function pre_save($doquery = true)
	{
		global $vbphrase;

		if ($this->presave_called !== null)
		{
			return $this->presave_called;
		}

		$return_value = true;
		($hook = vBulletinHook::fetch_hook('cms_layout_presave')) ? eval($hook) : false;

		$this->presave_called = $return_value;
		return $return_value;
	}

	/**
	* Additional data to update after a save call (such as denormalized values in other tables).
	* In batch updates, is executed for each record updated.
	*
	* @param	boolean	Do the query?
	*/
	function post_save_each($doquery = true)
	{
		$layoutid = intval($this->fetch_field('layoutid'));
		// Save widget data
		if ($this->info['widgetdata'] AND $layoutid)
		{
			if ($this->condition)
			{
				$this->registry->db->query_write("
					DELETE FROM " . TABLE_PREFIX . "cms_layoutwidget
					WHERE layoutid = $layoutid
				");
			}

			$values = array();
			foreach ($this->info['widgetdata'] AS $layoutcolumn => $widgets)
			{
				$layoutindex = 1;
				foreach ($widgets AS $widgetinfo)
				{
					if ($widgetid = intval($widgetinfo['xyz_widgetid']))
					{
						$values[] = "($layoutid, $widgetid, $layoutcolumn, $layoutindex)";
					}
					$layoutindex++;
				}
			}

			if (!empty($values))
			{
					$this->registry->db->query_write("
						INSERT INTO " . TABLE_PREFIX . "cms_layoutwidget
							(layoutid, widgetid, layoutcolumn, layoutindex)
						VALUES
							" . implode(", ", $values) . "
					");
			}
		}

		($hook = vBulletinHook::fetch_hook('cms_layout_postsave')) ? eval($hook) : false;

		return true;
	}

	/**
	* Additional data to update after a delete call (such as denormalized values in other tables).
	*
	* @param	boolean	Do the query?
	*/
	function post_delete($doquery = true)
	{
		if ($layoutid = intval($this->fetch_field('layoutid')))
		{
			$this->registry->db->query_write("
				DELETE FROM " .  TABLE_PREFIX . "cms_layoutwidget
				WHERE layoutid = $layoutid
			");
		}

		($hook = vBulletinHook::fetch_hook('cms_layout_delete')) ? eval($hook) : false;
		return true;
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
?>
