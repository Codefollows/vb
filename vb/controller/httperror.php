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
 * Alternate error controller for simple http errors.
 *
 * @author vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */
class vB_Controller_HttpError extends vB_Controller
{
	/*Properties====================================================================*/

	/**
	 * The package that the controller belongs to.
	 *
	 * @var string
	 */
	protected $package = 'vB';

	/**
	 * The class string id that identifies the controller.
	 *
	 * @var string
	 */
	protected $class = 'HttpError';



	/*Initialization================================================================*/

	/**
	 * Main entry point for the controller.
	 *
	 * @return string							- The final page output
	 */
	public function getResponse()
	{
		$error = vB_Router::getSegment('error');

		// Resolve rerouted error
		$error = in_array($error, array('403', '404', '500')) ? $error : '404';

		// Setup the templater for xhtml
		vB_View::registerTemplater(vB_View::OT_XHTML, new vB_Templater_vB());

		$view = new vB_View_AJAXHTML('http_error');

		if ('403' == $error)
		{
			$view->addError(new vB_Phrase('error', 'nopermission_loggedin_ajax'));
		}
		else if ('500' == $error)
		{
			$view->addError(new vB_Phrase('error', 'error_500'));
		}
		else
		{
			$view->addError(new vB_Phrase('error', 'error_400'));
		}

		return $view->render(true);
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/