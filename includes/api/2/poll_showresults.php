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
		'pollresults' => array(
			'pollbits' => array(
				'*' => array(
					'names' => array(
						'*' => array(
							'loggedin' => array(
								'userid', 'username', 'invisiblemark', 'buddymark'
							)
						)
					),
					'option' => array('question', 'votes', 'percentraw'),
					'show' => array('pollvoters')
				)
			),
			'pollinfo' => array(
				'question', 'timeout', 'posttime', 'public', 'closed'
			),
			'pollendtime', 'pollstatus'
		),
		'threadinfo' => $VB_API_WHITELIST_COMMON['threadinfo'],
	),
	'show' => array(
		'pollvoters', 'multiple', 'editpoll', 'pollenddate'
	)
);

function api_result_prerender_2($t, &$r)
{
	switch ($t)
	{
		case 'pollresults_table':
			$r['pollinfo']['posttime'] = $r['pollinfo']['dateline'];
			$r['pollendtime'] = $r['pollinfo']['dateline'] + ($r['pollinfo']['timeout'] * 86400);
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