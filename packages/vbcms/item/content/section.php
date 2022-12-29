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
 * CMS Section Content Item
 * The model item for CMS sections.
 *
 * @author vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */
class vBCms_Item_Content_Section extends vBCms_Item_Content
{
	/*Properties====================================================================*/

	/**
	 * A class identifier.
	 *
	 * @var string
	 */
	protected $class = 'Section';

	/**
	 * A package identifier.
	 *
	 * @var string
	 */
	protected $package = 'vBCms';

	protected $dm_class = 'vBCms_DM_Section';

	/**
	 * 
	 *
	 * @return int
	 */
	/**
	 * Fetches the contentid, which for a section is the nodeid.
	 * How this is interpreted is up to the content handler for the contenttype.
	 * 
	 * @param  boolean $contentonly added for php 5.4 srtrict standards compliance
	 * @return int
	 */
	public function getContentId($contentonly = false)
	{
		$this->Load();
		//for sections, and probably for some other types in the futurne
		return ($this->nodeid);
	}


}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/