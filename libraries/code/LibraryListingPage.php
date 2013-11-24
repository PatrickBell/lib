<?php

class LibraryListingPage extends Page {
	static $db = array(
		'Content2' => 'HTMLText'
	);

	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Content.Main', new HtmlEditorField('Content2', 'Bottom content'));

		return $fields;
	}
}

class LibraryListingPage_Controller extends Page_Controller {

}
