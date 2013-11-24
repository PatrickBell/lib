<?php
/**
 * AnalysisSite displays the crossection information on multiple monitoring sites.
 */

class AnalysisXmlPage extends XmlPage {
	static $hide_ancestor = 'XmlPage';
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab('Root.Content.Main', 'SiteID');
		return $fields;
	}
}

class AnalysisXmlPage_Controller extends XmlPage_Controller {

}