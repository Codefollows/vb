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

loadCommonWhiteList();

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'confirmedreceipts' => array(
				'startreceipt',
				'endreceipt',
				'numreceipts',
				'receiptbits' => array(
					'*' => array(
						'receipt' => array(
							'receiptid', 'send_date', 'send_time', 'read_date',
							'read_time', 'title', 'tousername'
						)
					)
				),
				'counter'
			),
			'unconfirmedreceipts' => array(
				'startreceipt',
				'endreceipt',
				'numreceipts',
				'receiptbits' => array(
					'*' => array(
						'receipt' => array(
							'receiptid', 'send_date', 'send_time', 'read_date',
							'read_time', 'title', 'tousername'
						)
					)
				),
				'counter'
			)
		)
	),
	'show' => array(
		'readpm', 'receipts', 'pagenav'
	)
);

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # Nulled by tuoitreit.vn
|| ####################################################################
\*======================================================================*/