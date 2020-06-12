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

$VB_API_WHITELIST = array(
	'response' => array(
		'content' => array(
			'blogbits' => array(
				'*' => array(
					'blog' => array(
						'userid', 'username', 'title', 'ratingnum', 'ratingavg',
						'entries', 'comments', 'entrytitle', 'lastblogtextid',
						'lastentrytime', 'notification'
					),
					'show' => array(
						'rating', 'private'
					)
				)
			),
			'sub_count',
			'pagenav' => $VB_API_WHITELIST_COMMON['pagenav']
		)
	)
);

function api_result_prerender_2($t, &$r)
{
	switch ($t)
	{
		case 'blog_blog_row':
			$r['blog']['lastentrytime'] = $r['blog']['lastblog'];
			break;
	}
}

vB_APICallback::instance()->add('result_prerender', 'api_result_prerender_2', 2);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/