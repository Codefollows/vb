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
 * Widget Exception
 * Exception thrown by widget classes.
 *
 * @package vBulletin
 * @author vBulletin Development Team
 * @version $Revision: 92140 $
 * @since Nulled by tuoitreit.vn
 * @copyright vBulletin Solutions Inc.
 */
class vBCms_Exception_Widget extends vB_Exception
{
	/**
	 * Creates a 404 exception with the given message
	 *
	 * @param string $message					- A user friendly error
	 * @param int $code							- The PHP code of the error
	 * @param string $file						- The file the exception was thrown from
	 * @param int $line							- The line the exception was thrown from
	 */
	public function __construct($message = false, $code = false, $file = false, $line = false)
	{
		if (!$message)
		{
			$message = new vB_Phrase('error', 'invalid_widget_request');
		}
		// Standard exception initialisation
		parent::__construct($message, $code, $file, $line);
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/