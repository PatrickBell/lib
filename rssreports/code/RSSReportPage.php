<?php

class RSSReportPage extends Page {

	static $db = array(
	);

	static $has_one = array(
	);

}

class RSSReportPage_Controller extends Page_Controller {

	function action() {

	}
	
	function RSSFeed() {

	$this->response->addHeader("Content-Type", "text/xml");
		
	$user_ip = ip2long(getenv('REMOTE_ADDR'));
		
	if(($user_ip >= ip2long('172.17.0.1') && $user_ip <= ip2long('172.17.255.255')) || $user_ip <= ip2long('192.168.101.3')){ 
		
		$GetReport = DB::query("SELECT SiteTree.ID, SiteTree.Title, SiteTree.ClassName, SiteTree.Status, SiteTree.LastEdited AS pubDate FROM SiteTree LEFT JOIN SiteTree_Live ON SiteTree.ID = SiteTree_Live.ID WHERE SiteTree_Live.ID IS NULL ORDER BY pubDate DESC LIMIT 1000");
		
//		debug::show(print_r($GetReport)); 
		
		$ReportOutput = new DataObjectSet();
		
		$numrecords = $GetReport->numRecords();
		
		for($i=0; $i<=$numrecords; $i=$i+1){
				
			$ReportItem = $GetReport->record();		
			
			if($ReportItem['ID']){
				
				$pubDate = date(DATE_RSS, strtotime($ReportItem['pubDate']));	
								
				$extrainfo = array(
					'Title' => htmlspecialchars($ReportItem['Title']),
					'Description' => 'SiteTree Status: '.$ReportItem['Status'].' - Not on Live Website.',
					'Link' => Director::absoluteBaseURL().'admin/show/'.$ReportItem['ID'],
					'pubDate' => $pubDate,
					'guid' => Director::absoluteBaseURL().'admin/show/'.$ReportItem['ID']
					);
				$ReportOutput -> push(new ArrayData($extrainfo));
			}
		}
	} else {
		$ReportOutput = '';	
	}
		
//		debug::show(print_r($ReportOutput)); 
	return $ReportOutput;
	}
	
	function RSSFeedAddress(){
		$address = Director::absoluteBaseURL().substr($this->Link(),1,255);
		return $address;
	}
	
}

?>