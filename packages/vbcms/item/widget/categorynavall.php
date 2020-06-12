<?php if (!defined('VB_ENTRY')) die('Access denied.');
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.5
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/

/**
 * Test Widget Item
 *
 * @package vBulletin
 * @author Edwin Brown, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since Nulled by tuoitreit.vn
 * @copyright vBulletin Solutions Inc.
 */
class vBCms_Item_Widget_CategoryNavAll extends vBCms_Item_Widget
{
	/**
	 * A package identifier.
	 *
	 * @var string
	 */
	protected $package = 'vBCms';

	/**
	 * A class identifier.
	 *
	 * @var string
	 */
	protected $class = 'CategoryNavAll';

	/** The default configuration **/
	protected $config = array(
		'showparent' => 1,
		'showsection' => 1,
		'template_name' => 'vbcms_widget_categorynavall_page',
	);

}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/