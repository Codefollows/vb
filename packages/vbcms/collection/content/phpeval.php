<?php if (!defined('VB_ENTRY')) die('Access denied.');
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.5
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/

/**
 * CMS Content Collection
 * Fetches CMS specific content items, including node related info.
 *
 * @package vBulletin
 * @author vBulletin Development Team
 * @version $Revision: 92140 $
 * @since Nulled by tuoitreit.vn
 * @copyright vBulletin Solutions Inc.
 */

class vBCms_Collection_Content_PhpEval extends vBCms_Collection_Content_StaticPage
{
	/*Item==========================================================================*/

	/**
	 * The package identifier of the child items.
	 *
	 * @var string
	 */
	protected $item_package = 'vBCms';

	/**
	 * The class identifier of the child items.
	 *
	 * @var string
	 */
	protected $item_class = 'PhpEval';

}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/