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
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/