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
		'content' => array(
			'blogbits' => array(
				'*' => array(
					'blog' => array(
						'ratingnum', 'ratingavg', 'title', 'username', 'entries',
						'comments', 'entrytitle', 'lastblogtextid',
						'lastentrytime', 'userid', 'notification'
					),
					'show' => array(
						'private', 'rating', 'lastentry', 'lastcomment'
					)
				)
			),
			'pagenav'
		)
	)
);

function api_result_prerender_2($t, &$r)
{
	switch ($t)
	{
		case 'blog_blog_row':
		case 'blog_list_blogs_blog':
		case 'blog_list_blogs_blog_ignore':
			$r['blog']['lastentrytime'] = $r['blog']['lastblog'];
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