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

error_reporting(E_ALL & ~E_NOTICE);

// function to get a ban-end timestamp
function convert_date_to_timestamp($period)
{
	if ($period[0] == 'P')
	{
		return 0;
	}

	$p = explode('_', $period);
	$d = explode('-', date('H-i-n-j-Y'));
	$date = array(
		'h' => &$d[0],
		'm' => &$d[1],
		'M' => &$d[2],
		'D' => &$d[3],
		'Y' => &$d[4]
	);

	$date["$p[0]"] += $p[1];
	return mktime($date['h'], 0, 0, $date['M'], $date['D'], $date['Y']);
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
?>