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

define('VB_API_LOADLANG', true);

loadCommonWhiteList();

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'attachlimit', 'attachsize', 'attachsum', 'pagenav',
			'pagenumber', 'perpage', 'showthumbs', 'template',
			'totalattachments', 'totalsize', 'userid', 'username',
			'attachmentlistbits' => array(
				'*' => array(
					'show' => array(
						'moderated', 'inlinemod', 'thumbnail', 'inprogress',
						'candelete', 'canmoderate'
					),
					'info' => array(
						'attachmentid', 'dateline', 'thumbnail_dateline',
						'attachmentextension', 'dateline', 'filename',
						'size', 'counter', 'postdate', 'posttime',
						'userid'
					),
					'uniquebit' => array(
						'threadinfo' => $VB_API_WHITELIST_COMMON['threadinfo'],
						'post' => array(
							't_title', 'postid', 'p_title'
						),
						'template',
						'pageinfo',
						'article' => array(
							'title'
						),
						'url',
						'album' => array(
							'albumid', 'title'
						),
						'group' => array(
							'albumid', 'name'
						)
					)
				)
			)
		)
	),
	'show' => array(
		'attachment_list', 'attachquota',
	)
);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/