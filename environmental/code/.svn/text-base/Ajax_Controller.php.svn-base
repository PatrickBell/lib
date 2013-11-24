<?php

class Ajax_Controller extends Controller {
	static $url_handlers = array(
		'getsitenames/$SiteType' => 'getsitenames'
	);

	function getsitenames($request) {
		$siteType = Convert::raw2sql($request->param('SiteType'));

		$names = FlatXmlData::getSiteNames($siteType);
		$names['0'] = '-- None --';
		asort($names);
		
		$field = new DropdownField('Form_EditForm_SiteID','Site name', $names);
		
		return $field->forTemplate();
	}
}