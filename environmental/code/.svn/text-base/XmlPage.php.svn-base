<?php
/**
 * XmlPage acccesses the data through a combination of site type and the indentifier of the actual location.
 * The actual information is stored as XmlData DataObjects in the database.
 *
 * Information Types
 * -----------------
 * XmlPage can be used to display one of three types of Information. See appropriate child page types.
 * - Monitoring Information: has one FlatXmlData and multiple TimedXmlData (currently maps to SiteXmlPage)
 * - Analysis Information: has multiple FlatXmlData (currently maps to AnalysisXmlPage)
 * - Asset Information: has one FlatXmlData (currently maps to SiteXmlPage)
 * 
 * Templates
 * ---------
 * Every XmlPage has a SiteType which in turn has it's own dedicated template. One Information Type can have
 * multiple SiteTypes available, and thus multiple templates.
 * If the template can not be found, the "Default" will be used.
 *
 */

class XmlPage extends Page {
	static $db = array(
		'SiteType'=>'Varchar',
		'SiteID'=>'Varchar',
		"TopContent" => "HTMLText"
	);
	
	function getCMSFields() {
		// Add dynamic loading of site names
		Requirements::javascript('environmental/javascript/cms_xml_page.js');

		$fields = parent::getCMSFields();
		
		$types = FlatXmlData::getSiteTypes(str_replace('XmlPage', '', $this->ClassName));
		if ($types) $types = array_combine($types, $types);
		else $types = array();
		$types['-- None --'] = '-- None --';
		asort($types);
		
		$names = FlatXmlData::getSiteNames($this->SiteType);
		if (!$names) $names = array();
		$names['0'] = '-- None --';
		asort($names);
		
		$fields->addFieldToTab('Root.Content.Main', new DropdownField('SiteType','Site type', $types), 'Content');
		$fields->addFieldToTab('Root.Content.Main', new DropdownField('SiteID','Site name', $names), 'Content');
		
		$fields->addFieldToTab("Root.Content.Main", new HtmlEditorField("TopContent","Top Content"),"Content");

		// Add information for JS on how to get refresh the SiteID dropdown
		$fields->addFieldToTab('Root.Content.Main', new HiddenField('getsitenames','getsitenames', Director::baseURL().'Ajax_Controller/getsitenames/'), 'Content');
		
		return $fields;
	}

	/**
	 * Get the historical information from the database.
	 * 
	 * @param $daysAgo the threshold for the query
	 */
	function HistoricalXML($daysAgo = 30) {
		if (!is_numeric($daysAgo)) {
			$threshold = date('Y-m-d 00:00:00', strtotime($daysAgo));
		}
		else {
			$threshold = date('Y-m-d 00:00:00', strtotime("$daysAgo days ago"));
		}
		return DataObject::get('TimedXmlData', "\"Time\">'$threshold' AND \"SiteType\"='$this->SiteType' AND \"SiteID\"='$this->SiteID'", 'Time DESC');
	}
	
	/**
	 * Get most recent historical information from the database.
	 */
	function LatestXML() {
		return DataObject::get_one('TimedXmlData', "\"SiteType\"='$this->SiteType' AND \"SiteID\"='$this->SiteID'", true, 'Time DESC');
	}
	
	/**
	 * Get the horizontal cut of the xml data (for analysis information sites)
	 */
	function AllXML() {
		$sortedDOS = new DataObjectSet();
		$data = DataObject::get('FlatXmlData', "\"SiteType\"='$this->SiteType'");

		$sortedData = array();
		if ($data && $data->Count() > 0) {
			foreach($data as $d) {
				if (preg_match('/<syn2>(.+)<\/syn2>/i',$d->Payload, $matches)) {
					//create an associate array with syn2 as the key
					$sortedData[$matches[1]] = $d;
				}
			}

			ksort($sortedData); //sort by array key

			//create a sorted dataobjectset for output to the template
			foreach($sortedData as $syn => $obj) {
				$sortedDOS->push($obj);
			}
		}
		
		return $sortedDOS;
	}

	/**
	 * Get the single XML data point
	 */
	function XML() {
		$xd = null;
		$xd = DataObject::get_one('FlatXmlData', "\"SiteType\"='$this->SiteType' AND \"SiteID\"='$this->SiteID'", 'Created DESC');

		// Try the latest entry of the timed data set
		if (!$xd) {
			$xd = DataObject::get_one('TimedXmlData', "\"SiteType\"='$this->SiteType' AND \"SiteID\"='$this->SiteID'", 'Time DESC');
		}
		
		return $xd;
	}
	
	/**
	 * Strips out all non-alphanum characters from the SiteType.
	 */
	function getTemplateName() {
		return preg_replace('/[^a-zA-Z0-9]/', '', $this->SiteType);
	}
	
	/**
	 * Content is rendered via separate specialized template tied to SiteType to allow for easy extension later.
	 */
	function SiteTemplate() {
		global $_TEMPLATE_MANIFEST;
		$tname = $this->getTemplateName();

		if (isset($_TEMPLATE_MANIFEST[$tname])) {	
			// Specific template exists
			return $this->renderWith('SiteTemplates/'.$tname);
		}
		else {
			return $this->renderWith('SiteTemplates/Default');
		}
	}
	
	/**
	 * Get the url of the images upload folder
	 */
	function ImagesURL() {
		return Director::baseURL().'assets/xml';
	}
	
	/**
	 * Check if the image file exists, so it's possible to prevent empty titles and broken images.
	 */
	function ImageExists($node) {
		if ($this->XML()) {
			if (isset($this->XML()->$node)) {
				$filename = $this->XML()->$node;
				if (is_file(ASSETS_PATH."/images/$filename")) {
					return true;
				}
			}
		}
		
		return false;
	}
}

class XmlPage_Controller extends Page_Controller {

}