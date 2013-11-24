<?php
/**
 * PaymentLog is used to store payment information in the database for debug purposes.
 */

class PaymentLog extends DataObject {
	static $db = array(
		'Type'=>'Varchar',
		'Message'=>'Text',
		'Member'=>'Text'
	);
	
	static function log($type, $message) {
		$member = Member::currentUser();
		
		$logItem = new PaymentLog();
		$logItem->Type = $type;
		$logItem->Message = $message;
		if ($member) {
			$logItem->Member = $member->getTitle();
		}
		$logItem->write();
	}
}
