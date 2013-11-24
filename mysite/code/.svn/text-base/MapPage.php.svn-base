<?php

class MapPage extends Page {

	static $db = array(
		"TopContent" => 'HTMLText',
		"MapZoom" => 'Int',
		);

	static $has_one = array(
		);

	
// Access to google maps api, has to be generated via http://code.google.com/apis/maps/signup.html per domain

	static public $gmaps_api_key;
	
// Add a another content box under the Content tab		
	function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.Content.Main", new HtmlEditorField("TopContent","Top Content"),"Content");
		$fields->addFieldToTab("Root.Content.Main", new NumericField("MapZoom","Google Map Zoom - Default =14. Higher is closer, 12 = show entire town/ settlement"),"TopContent");
		$fields->renameField("Content", "Bottom Content");
		return $fields;
	}
}

class MapPage_Controller extends Page_Controller {
	function init() {
		parent::init();
		Requirements::javascript('http://www.google.com/jsapi?key='.MapPage::$gmaps_api_key);
		Requirements::javascript('mysite/javascript/latlongdirections.js?lat='.$this->Lat.'&long='.$this->Long.'&mapzoom='.$this->MapZoom);
	}
}
