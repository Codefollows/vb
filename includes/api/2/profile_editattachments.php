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
						'attachmentid', 'thumbnail_dateline',
						'attachmentextension', 'filename',
						'size', 'counter', 'posttime',
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

function api_result_prerender_2($t, &$r)
{
	switch ($t)
	{
		case 'modifyattachmentsbit':
			$r['info']['posttime'] = $r['info']['dateline'];
			break;
	}
}

vB_APICallback::instance()->add('result_prerender', 'api_result_prerender_2', 2);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/