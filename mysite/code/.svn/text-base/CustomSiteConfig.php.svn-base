<?php
 
class CustomSiteConfig extends DataObjectDecorator {
 
	function extraStatics() {
		return array(
			'db' => array(
				'CivilDefenceAlertMode' => 'Boolean'
			)
		);
	}
 
	public function updateCMSFields(FieldSet $fields) {
		$fields->addFieldToTab("Root.Main", new CheckboxField("CivilDefenceAlertMode", "Civil Defence alert mode"));
	}
}

