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

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'inimaxattach', 'maxnote', 'preview', 'sigperms',
			'sigpicurl'
		)
	),
	'show' => array(
		'canbbcode', 'canbbcodebasic', 'canbbcodecolor', 'canbbcodesize',
		'canbbcodefont', 'canbbcodealign', 'canbbcodelist', 'canbbcodelink',
		'canbbcodecode', 'canbbcodephp', 'canbbcodehtml', 'canbbcodequote',
		'allowimg', 'allowvideo', 'allowsmilies', 'allowhtml', 'cansigpic', 'cananimatesigpic'
	)
);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/