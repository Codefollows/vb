<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.5 - Nulled by vBWarez.org
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
if (!VB_API) die;

$VB_API_WHITELIST = array(
	'response' => array(
		'customfieldsheader', 'first', 'last', 'leadergroup',
		'memberlistbits' => array(
			'*' => array(
				'userinfo' => array(
					'username', 'userid', 'usertitle',
					'icq', 'aim', 'yahoo', 'skype', 'msn', 'homepage',
					'datejoined', 'posts', 'lastvisittime',
					'reputationdisplay' => array(
						'posneg',
						'post' => array(
							'username', 'level'
						)
					),
					'profilepic', 'birthday', 'age'
				),
				'customfields', 'avatarurl',
				'show' => array(
					'searchlink', 'emaillink', 'homepagelink', 'pmlink', 'avatar',
					'hideleader'
				)
			)
		),
		'pagenav', 'perpage', 'searchtime', 'totalcols', 'totalusers',
		'usergroupid', 'oppositesort'
	),
	'show' => array(
		'homepagecol', 'searchcol', 'datejoinedcol', 'postscol', 'usertitlecol',
		'lastvisitcol', 'reputationcol', 'avatarcol', 'birthdaycol', 'agecol',
		'emailcol', 'customfields', 'imicons', 'profilepiccol', 'advancedlink',
		'usergroup', 'selectedletter', 'pagenav', 'prev', 'next', 'first',
		'last', 'pagelinks', 'curpage', 'hideleader'
	)
);

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 19:19, Wed May 10th 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/