<?php
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

$VB_API_WHITELIST = array(
	'session' => array('dbsessionhash', 'userid'),
	'show' => array()
);
class vB_APIMethod_login_facebook extends vBI_APIMethod
{
    public function output()
    {
        global $vbulletin, $db, $show, $VB_API_REQUESTS; 
        
        // check if facebook and session is enabled
		if (!is_facebookenabled())
		{
			return $this->error('feature_not_enabled');
		} 
        
        require_once(DIR . '/includes/functions_login.php');
        if (verify_facebook_app_authentication())
        {
            // create new session
            process_new_login('fbauto', false, '');

            // do redirect
            do_login_redirect();
        }

        else 
        {
            return $this->error('badlogin_facebook');
        }
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
?>
