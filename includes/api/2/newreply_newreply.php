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
		'attachmentoption' => $VB_API_WHITELIST_COMMON['attachmentoption'],
		'disablesmiliesoption', 'emailchecked',
		'folderbits', 'checked', 'multiquote_empty', 'rate',
		'forumrules', 'human_verify', 'posthash', 'posticons', 'postpreview',
		'poststarttime', 'prefix_options', 'selectedicon', 'title',
		'htmloption', 'specifiedpost',
		'threadreviewbits' => array(
			'*' => array(
				'posttime', 'reviewmessage', 'reviewtitle',
				'post' => array(
					'postid', 'threadid', 'username', 'userid'
				)
			)
		),
		'unquoted_post_count', 'return_node',
		'threadinfo' => $VB_API_WHITELIST_COMMON['threadinfo']
	),
	'vboptions' => array(
		'postminchars', 'titlemaxchars', 'maxposts'
	),
	'show' => array(
		'posticons', 'smiliebox', 'attach', 'threadrating', 'openclose', 'stickunstick',
		'closethread', 'unstickthread', 'subscribefolders', 'reviewmore',
		'parseurl', 'misc_options', 'additional_options'
	)
);

function api_result_prerender_2($t, &$r)
{
	switch ($t)
	{
		case 'newreply_reviewbit':
			$r['posttime'] = $r['post']['dateline'];
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