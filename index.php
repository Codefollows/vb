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

/* Tell forum.php to redirect 
to the default url as defined 
in the navigation manager */
define('VB_REDIRECT', true);

/**
 * If you want to move this file to the root of your website, change the 
 * line below to your vBulletin directory and uncomment it (delete the //).
 *
 * For example, if vBulletin is installed in '/forum' the line should
 * state: define('VB_RELATIVE_PATH', 'forum');
 *
 * Note: You may need to change the cookie path of your vBulletin
 * installation to enable your users to log in at the root of your website.
 * If you move this file to the root of your website then you should ensure
 * the cookie path is set to '/'.
 *
 * See 'Admin Control Panel
 *	->Cookies and HTTP Header Options
 *	  ->Path to Save Cookies
 */

//define('VB_RELATIVE_PATH', 'forum');

// Do not edit anything below //
if (defined('VB_RELATIVE_PATH'))
{
	chdir('./' . VB_RELATIVE_PATH);
}

require('forum.php');


/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
