<?php if (!defined('VB_ENTRY')) die('Access denied.');
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

class vB_Legacy_Phrase
{
	public function get_languageid()
	{
		global $vbulletin;
		return intval(!empty($vbulletin->userinfo['languageid']) ? 
			$vbulletin->userinfo['languageid'] : $vbulletin->options['languageid']);
	}

	/**
	*	Add phrase groups to global phrase array
	*
	*	@todo Add caching for languages for memcache/apccache.  Move main language load to 
	* This file.
	*/
	public function add_phrase_groups($groupnames)
	{
		global $vbulletin, $vbphrase, $phrasegroups;
		
		//only load groups that haven't been loaded.
		$selectlist = array();
		foreach ($groupnames AS $groupname)
		{
			if (!in_array($groupname, $phrasegroups))
			{
				$selectlist[] = "phrasegroup_$groupname AS $groupname";
				$phrasegroups[] = $groupname;
			}
		}

		//nothing to do so bail
		if (!count($selectlist))
		{
			return;
		}

		$groups = $vbulletin->db->query_first_slave($q = "
			SELECT " . implode(',', $selectlist) . "
			FROM " . TABLE_PREFIX . "language
			WHERE languageid = " . $this->get_languageid() 
		);
		
		foreach ($groups as $group)
		{
			$vbphrase = array_merge($vbphrase, vb_unserialize($group));
		}
	}
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
