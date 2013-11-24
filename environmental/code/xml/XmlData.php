<?php
/**
 * This is an object that represents a single chunk of imported data. It is not intended for direct display.
 * Instead, it is handled by XmlPage. Some of the XmlPages will access multiple XmlData records at the same time.
 * 
 * We extract the "meaningful" fields from the incoming XML. "Meaningful" fields are those needed for
 * later processing. Everything else is stored as XML in the payload, so TDC can add and remove fields as they please.
 *
 * Only fields from one level deep are available.
 *
 * XmlDataTypes
 * ------------
 * - FlatXmlData: used to store named, geolocated geographical site. Primary method of storing sites. Can have multiple TimedXmlData associated.
 * - TimedXmlData: used to store timestamped readout information. 
 */

class XmlData extends DataObject {
	static $db = array(
		'SiteType'=>'Varchar',
		'SiteID'=>'Varchar',
		'Payload'=>'Text'
	);
	
	static $indexes = array(
		'SiteType'=>true,
		'SiteID'=>true
	);
	
	public $xml = null;
	
	/**
	 * Lazy loaded xml
	 */
	public function getXml() {
		if (!$this->xml && isset($this->Payload)) {
			$oldXmlErrors = libxml_use_internal_errors(true);
			$this->xml = simplexml_load_string($this->Payload);
			$errors = libxml_get_errors();
			libxml_clear_errors();
			libxml_use_internal_errors($oldXmlErrors);
			
			if (!empty($errors)) {
				$this->xml = simplexml_load_string('<broken></broken>');
			}
		}
		
		return $this->xml;
	}
	
	/**
	 * Make XML fields available
	 */
	public function hasField($field) {
		if (parent::getField($field)) return true;
		
		if (isset($this->getXML()->$field)) return true;
		
		return false;
	}

	/**
	 * Return the xml "field", if no function/field available on the DataObject.
	 */
	public function getField($field) {
		if ($parent = parent::getField($field)) return $parent;
		
		if (isset($this->getXML()->$field)) $xmlVal = $this->getXML()->$field;
			
		if ($xmlVal) return (string)$xmlVal;
	}
	
	/**
	 * Utility function returning rounded XML field
	 */
	public function Round($field, $precision = 2) {
		if ($parent = parent::getField($field)) return round($parent, $precision);

		if (isset($this->getXML()->$field)) $xmlVal = $this->getXML()->$field;
			
		if ($xmlVal) return round((string)$xmlVal, $precision);
	}		
}