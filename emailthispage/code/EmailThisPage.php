<?php
class EmailThisPage extends DataObjectDecorator {

	function EmailThisPageForm() {
		$fields = new FieldSet(
			array(
				new TextField('name', 'Your name:'),
				new TextField('recipient', 'Your friend\'s email address:'),
				new TextField('message', 'Your message:')
			)
		);

		$actions = new FieldSet(
			new FormAction('submitEmailThisPage', 'Send')
		);

		if (preg_match('/home[\/]$/',$this->owner->RelativeLink(true))) {
			$action = "home/submitEmailThisPage";
		} else {
			$action = "submitEmailThisPage";
		}

		$form = new Form($this->owner, $action, $fields, $actions);
		return $form->forAjaxTemplate();
	}


}

class EmailThisPage_Controller extends Extension {
	static $allowed_actions = array(
		'EmailThisPageForm',
		'submitEmailThisPage'
	);

	function submitEmailThisPage($request) {
		//can only be called via ajax to prevent abuse by spammers
		if (Director::is_ajax() && $request && get_class($request) == 'SS_HTTPRequest' && $request->getBody()) {
			//parse the GET string
			$parsed = array();
			parse_str($request->getBody(), $parsed);
			$name = $parsed['name'];
			$recipient = $parsed['recipient'];
			$message = $parsed['message'];
			if (!$name) $name = '';
			if (!$message) $message = '';

			$url = Director::absoluteURL($this->owner->Link()); //get the complete url for this page

			//write the message
			$message = "$name thought you might like this page. \n\nThey say:\n$message\n\nClick here to visit the page:\n$url";

			if($recipient && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {   //check for valid email address
				$headers = 'From: donotreply@tasman.govt.nz';
				mail($recipient,"$name thought you might be interested in this webpage",$message, $headers);
				return "<div>We have emailed this page to $recipient. <br/><br/>Thank you for visiting our website.</div>";
			}
		}

		return "<div>An error occurred. Please reload this page and try again.</div>";
	}
}

?>