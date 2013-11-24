<?php

class Tier3LandingPage extends Page {

	static $db = array(
		"TopContent" => "HTMLText",
	);

	static $has_one = array(
   );

	
// add a another content box under the Content tab		
	function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.Content.Main", new HtmlEditorField("TopContent","Top Content"),"Content");
		$fields->renameField("Content", "Bottom Content");
		return $fields;
	}
}

class Tier3LandingPage_Controller extends Page_Controller {

}

?>