<?php if (!defined('VB_ENTRY')) die('Access denied.');

class vBCms_Search_SearchController_NewArticle extends vBCms_Search_SearchController_NewContentNode
{

	/** standard constructor **/
	public function __construct()
	{
		$this->contenttypeid = vB_Types::instance()->getContentTypeID('vBCms_NewArticle');
	}

}
/*======================================================================*\
|| ####################################################################
|| # Downloaded: 19:19, Wed May 10th 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/