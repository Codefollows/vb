<?php if (!defined('VB_ENTRY')) die('Access denied.');

require_once DIR . '/vb/search/searchcontroller.php' ;

class vBCms_Search_SearchController_NewStaticPage extends vBCms_Search_SearchController_NewContentNode
{

	/** standard constructor **/
	public function __construct()
	{
		$this->contenttypeid = vB_Types::instance()->getContentTypeID('vBCms_StaticPage');
	}

}
/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
