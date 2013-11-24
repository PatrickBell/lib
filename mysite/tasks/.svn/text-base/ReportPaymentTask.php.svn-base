<?php

class ReportPaymentTask extends WeeklyTask{
	static $send_to = "bia@tasman.govt.nz";
	function process(){
		$this->reportBy(false);
	//	$this->reportBy(false, 'Monthly');
	}
	
	function reportBy($successOnly = true, $period="Weekly"){
/*		switch($period){
			case 'Monthly':
				$start = date('Y-m-d H:i:s', strtotime('first day last month'));
				$startNice = date('F Y', strtotime("first day last month"));
				$end = date('Y-m-d H:i:s', strtotime("last day last month"));
				$subject = "Monthly payment report";
				break;
			default:
				$start = date('Y-m-d H:i:s', strtotime("last Monday", strtotime('Sunday')));
				$startNice = "the week starting on ". date('l jS \of F Y', strtotime("last Monday", strtotime('Sunday')));
				$end = date('Y-m-d H:i:s');
				$subject = "Weekly payment report";
		}
*/
		$start = date('Y-m-d H:i:s', strtotime("last Monday", strtotime('Sunday')));
		$startNice = "the week starting on ". date('l jS \of F Y', strtotime("last Monday", strtotime('Sunday')));
		$end = date('Y-m-d H:i:s');
		$subject = "Weekly payment report";
		
		$monday = date('Y-m-d H:i:s', strtotime("last Monday", strtotime('Sunday')));
		$mondayNice = date('l jS \of F Y', strtotime("last Monday", strtotime('Sunday')));
		$now = date('Y-m-d H:i:s');
		//echo $start ." - ". $end;die;
		if($successOnly) {
			$trans = DataObject::get('RecordedTransactions', "\"ReceivedAsFPRN\" <> 1 AND \"LastEdited\" BETWEEN '$start' AND '$end' AND Success = 1");
		}else{
			$trans = DataObject::get('RecordedTransactions', "\"ReceivedAsFPRN\" <> 1 AND \"LastEdited\" BETWEEN '$start' AND '$end'");		
		}

		$email = new Email(Email::getAdminEmail(), self::$send_to, $subject);
		if($trans && $trans->count()){
			$output = "\"ID\",\"Date/Time\",\"Success?\",\"Merchant Reference\",\"Card Type\",\"Authorization Code\",\"Personal Details\",\"Email Address\",\"DPS Transaction Reference\",\"Settlement\",\"Fee\"\n";
			foreach($trans as $tran) {
				$output .= $tran->renderwith('PaymentReport')."\n";
			}
			$filename = "weekly_payment_export_".Date("d-m-Y-H-i").".csv";
			
			$parentFolder = Folder::findOrMake("Payments_export");
			$email->attachFileFromString($output, $filename);
			if($successOnly) {
				$successful = " successful";
			}else{
				$successful = "";
			}
			$email->setBody($trans->count().$successful." payment(s) found for $startNice, see attached csv file for details");
		}else{
			$email->setBody("There is no".$successful." payment found for $startNice.");
		}

		//return SS_HTTPRequest::send_file($output, $filename, 'csv');
		$email->send();
	}
}
?>