<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin Blog 4.2.6 by vBS
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
if (!VB_API) die;

class vB_APIMethod extends vBI_APIMethod
{
	public function output()
	{
		global $vbulletin, $VB_API_REQUESTS;

		if (!$VB_API_REQUESTS['api_s'])
		{
			return $this->error('sessionhash_required', "Sessionhash Required");
		}
		return $vbulletin->userinfo['securitytoken_raw'];
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/