<?php
/**
 * MonitoringSitePage is a specific environmental monitoring site.
 * It blocks Title, Lat and Long, and allows them to be taken from XML data.
 */

class SiteXmlPage extends XmlPage {
	static $hide_ancestor = 'XmlPage';
	
	/**
	 * "Get" functions below shadow the object's fields with the data taken from XML.
	 */
	function getLat() {
		$xml = $this->XML();
		if ($xml && isset($xml->Latitude)) {
			return $xml->Latitude;
		}
		
		return $this->getField("Lat");
	}
	
	function getLong() {
		$xml = $this->XML();
		if ($xml && isset($xml->Longitude)) {
			return $xml->Longitude;
		}
		
		return $this->getField("Long");
	}

	/**
	 * Set the automatic fields
	 */
	function onBeforeWrite() {
		$title = null;
		$xml = $this->XML();
		if ($xml) {
			if (isset($xml->PageName)) $title = $xml->PageName;
			else if (isset($xml->AltSiteName)) $title = $xml->AltSiteName;
			
			if ($title) {
				$this->Title = $title;
			}
			
			$this->URLSegment = strtolower($xml->SiteType.'-'.$xml->SiteID);
		}
		
		parent::onBeforeWrite();
	}
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$tab = $fields->fieldByName('Root.Content.Main');
		$title = $tab->fieldByName('Title')->performDisabledTransformation();
		$menuTitle = $tab->fieldByName('MenuTitle')->performDisabledTransformation();
		$tab->replaceField('Title', $title);
		$tab->replaceField('MenuTitle', $menuTitle);
				
		// $fields->renameField('MenuTitle', 'Navigation label (be careful, changing this will override the automatic Title from external data)');
				
		$tab = $fields->fieldByName('Root.Content.Geotag');
		$lat = $tab->fieldByName('Lat')->performDisabledTransformation();
		$long = $tab->fieldByName('Long')->performDisabledTransformation();
		$tab->replaceField('Lat', $lat);
		$tab->replaceField('Long', $long);
		
		return $fields;
	}
	
	/**
	 * Get the icon according to the latest TimedXmlData status entry.
	 */
	function Icon() {
		if ($latest = $this->LatestXML()) {
			if ($latest->Status) {
				if (file_exists(BASE_PATH."/assets/icons/{$latest->Status}.png")) {
					return Director::baseURL()."assets/icons/{$latest->Status}.png";
				}
			}
		}
	}
	
	/**
	 * Get the text to fit into the google map info window
	 */
	function Info() {
		if (($latest = $this->LatestXML()) && 
			($flat = $this->XML()) ) {
				
			return str_replace(array("\n","\r"), '', $this->renderWith('SiteXmlPopup'));
		}
	}
}

class SiteXmlPage_Controller extends XmlPage_Controller {

}