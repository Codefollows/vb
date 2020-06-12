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

$VB_API_WHITELIST = array(
	'response' => array(
		'attachkeybits' => array(
			'*' => array(
				'extension' => array(
					'extension', 'size', 'width', 'height'
				)
			)
		),
		'attachlimit',
		'attachments' => array(
			'*' => array(
				'attach' => array(
					'extension', 'attachmentid', 'dateline', 'filename',
					'filesize'
				)
			)
		),
		'attachsize',
		'attachsum', 'attach_username', 'contenttypeid',
		'editpost', 'errorlist', 'inimaxattach',
		'totallimit', 'totalsize', 'values'
	),
	'show' => array(
		'errors', 'attachmentlimits', 'currentsize', 'totalsize', 'attachoption',
		'attachfile', 'attachurl', 'attachmentlist'
	)
);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/