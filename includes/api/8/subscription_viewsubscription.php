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

$threadbit = $VB_API_WHITELIST_COMMON['threadbit'];

$threadbit['thread'][] = 'forumtitle';

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'folder', 'folderjump', 'pagenav', 'totalallthreads',
			'threadbits' => array(
				'*' => $threadbit
			)
		)
	),
	'show' => array(
		'allfolders', 'threadicons', 'dotthreads', 'havethreads',
	)
);

function api_result_prerender($t, &$r)
{
	switch ($t)
	{
		case 'SUBSCRIBE':
			if (isset($r['threadbits']))
			{
				if (!isset($r['threadbits'][0]))
				{
					$r['threadbits'] = array($r['threadbits']);
				}
			}
		break;
	}
}

vB_APICallback::instance()->add('result_prerender', 'api_result_prerender', 1);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
