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

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);
if (!is_object($vbulletin->db))
{
	exit;
}

// ########################## REQUIRE BACK-END ############################
require_once(DIR . '/includes/functions_infractions.php');

// ########################################################################
// ######################### START MAIN SCRIPT ############################
// ########################################################################

$infractions = $vbulletin->db->query_read("
	SELECT infractionid, infraction.userid, points, username
	FROM " . TABLE_PREFIX . "infraction AS infraction
	LEFT JOIN " . TABLE_PREFIX . "user AS user USING (userid)
	WHERE expires <= " . TIMENOW . "
		AND expires <> 0
		AND action = 0
");

$infractionid = array();

$warningarray = array();
$infractionarray = array();
$ipointsarray = array();

$userids = array();
$usernames = array();

while ($infraction = $vbulletin->db->fetch_array($infractions))
{
	$vbulletin->db->query_write("
		UPDATE " . TABLE_PREFIX . "infraction
		SET action = 1, actiondateline = " . TIMENOW . "
		WHERE infractionid = $infraction[infractionid]
			AND action = 0
	");

	// enforce atomic update so that related records are only updated at most one time, in the event this task is executed more than one time
	if ($vbulletin->db->affected_rows())
	{
		$userids["$infraction[userid]"] = $infraction['username'];
		if ($infraction['points'])
		{
			$infractionarray["$infraction[userid]"]++;
			$ipointsarray["$infraction[userid]"] += $infraction['points'];
		}
		else
		{
			$warningarray["$infraction[userid]"]++;
		}
	}
}

// ############################ MAGIC ###################################
if (!empty($userids) AND build_user_infractions($ipointsarray, $infractionarray, $warningarray))
{
	build_infractiongroupids(array_keys($userids));
}

if (!empty($userids))
{
	log_cron_action(implode(', ', $userids), $nextitem, 1);
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
?>