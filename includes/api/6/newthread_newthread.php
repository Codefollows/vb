<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.5 - Nulled by vBWarez.org
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
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
		'attachmentoption' => $VB_API_WHITELIST_COMMON['attachmentoption'],
		'disablesmiliesoption', 'emailchecked', 'explicitchecked',
		'folderbits',
		'foruminfo' => $VB_API_WHITELIST_COMMON['foruminfo'],
		'forumrules', 'human_verify', 'newpost',
		'podcastauthor', 'podcastkeywords', 'podcastsize', 'podcastsubtitle',
		'podcasturl', 'polloptions', 'posthash', 'posticons', 'postpreview',
		'poststarttime', 'prefix_options', 'selectedicon', 'subject',
		'tags_remain', 'tag_delimiters', 'htmloption', 'errorlist'
	),
	'vboptions' => array(
		'postminchars', 'titlemaxchars', 'maxpolloptions'
	),
	'show' => array(
		'tag_option', 'posticons', 'smiliebox', 'attach', 'threadrating',
		'openclose', 'stickunstick', 'closethread', 'unstickthread',
		'subscribefolders', 'reviewmore', 'parseurl', 'misc_options',
		'additional_options', 'poll', 'podcasturl', 'tags_remain'
	)
);

function api_result_prewhitelist(&$value)
{
    if (is_array($value['response']['postpreview']['errorlist']['errors']))
	{
		foreach($value['response']['postpreview']['errorlist']['errors'] AS $key => $error)
		{
			$value['response']['errorlist'][] = $error[0];
		}
	}
}

vB_APICallback::instance()->add('result_prewhitelist', 'api_result_prewhitelist', 1);

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 19:19, Wed May 10th 2017 : $Revision: 92140 $
|| # $Date: 2016-12-30 20:26:15 -0800 (Fri, 30 Dec 2016) $
|| ####################################################################
\*======================================================================*/