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

$VB_API_ROUTE_SEGMENT_WHITELIST = array(
	'action' => array (
		'list'
	)
);

loadCommonWhiteList();

global $methodsegments;

// $methodsegments[0] 'type'
if ($methodsegments[0] == 'category')
{
	$VB_API_WHITELIST = array(
		'response' => array(
			'layout' => array(
				'content' => array(
					'rawtitle',
					'contents' => array(
						'*' => array(
							'id', 'node', 'title', 'authorid', 'authorname', 'page_url', 'showtitle', 'can_edit',
							'showuser', 'showpublishdate', 'viewcount', 'showviewcount',
							'showrating', 'publishdate', 'setpublish', 'publishdatelocal',
							'publishtimelocal', 'showupdated', 'lastupdated', 'dateformat',
							'rating', 'category', 'section_url', 'previewvideo', 'showpreviewonly',
							'previewimage', 'previewtext', 'preview_chopped', 'newcomment_url',
							'comment_count', 'ratingnum', 'ratingavg', 'avatar'
						)
					),
					'pagenav'
				),
			)
		)
	);

	function api_result_prewhitelist_1(&$value)
	{
		if ($value['response'])
		{
			$value['response']['layout']['content']['contents'] = $value['response']['layout']['content']['content_rendered']['contents'];
			foreach ($value['response']['layout']['content']['contents'] AS $k => &$v)
			{
				$v['title'] = unhtmlspecialchars($v['title']);
			}
		}
	}

	vB_APICallback::instance()->add('result_prewhitelist', 'api_result_prewhitelist_1', 1);
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/