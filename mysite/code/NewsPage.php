<?php

class NewsPage extends Page {

	static $defaults = array (
		'ShowInMenus' => false,
	);

	static $db = array(
		'DisplayDate' => 'Date',
	);

	static $has_one = array(
	);
	
	// add date field to News


	function getCMSFields(){

		$datefield = new DateField("DisplayDate","Display Date");
		$datefield->setConfig('showcalendar', true);
		$datefield->setConfig('showdropdown', true);
		$datefield->setConfig('dateformat', 'dd/MM/YYYY');
		
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.Content.Main", $datefield ,"Content");
		return $fields;
	}

}

class NewsPage_Controller extends Page_Controller {

	function LongDate(){
		$LongDate = date("l j F Y",strtotime($this->DisplayDate));
		return $LongDate;
	}

}

?>