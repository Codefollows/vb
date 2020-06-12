<?php
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
if (!VB_API) die;

define('VB_API_LOADLANG', true);

loadCommonWhiteList();

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'birthdaybit',
			'customfields' => array(
				'required' => array(
					'*' => $VB_API_WHITELIST_COMMON['customfield']
				),
				'regular' => array(
					'*' => $VB_API_WHITELIST_COMMON['customfield']
				),
			),
		)
	),
	'bbuserinfo' => array(
		'username', 'parentemail', 'usertitle', 'homepage', 'icq', 'aim',
		'msn', 'yahoo', 'skype', 'coppauser'
	),
	'show' => array(
		'customtitleoption', 'birthday_readonly', 'birthday_required'
	)
);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/