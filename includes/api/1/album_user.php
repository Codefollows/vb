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
if (!VB_API) die;

loadCommonWhiteList();

$VB_API_WHITELIST = array(
	'response' => array(
		'albumbits' => $VB_API_WHITELIST_COMMON['albumbits'],
		'latestbits' => $VB_API_WHITELIST_COMMON['albumbits'],
		'latest_pagenav' => $VB_API_WHITELIST_COMMON['pagenav'],
		'pagenav' => $VB_API_WHITELIST_COMMON['pagenav'],
		'userinfo' => array(
			'userid', 'username'
		)
	),
	'show' => array(
		'personalalbum', 'moderated', 'add_album_option'
	)
);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/