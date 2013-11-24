<?php
/**
 * FlatXmlData stores a single-point Xml data. 
 * Holds the SiteNames extracted from the XML.
 */


class FlatXmlData extends XmlData {
	static $db = array(
		'SiteName'=>'Text',
		'InformationType'=>'Varchar',	// Only used to display choose sites for the SiteXmlPage/AnalysisXmlPage dropdown
	);
	
	/**
	 * Fetch all SiteNames for given SiteType
	 */
	static function getSiteNames($siteType) {
		$names = DB::query("SELECT DISTINCT SiteID,SiteName FROM XmlData INNER JOIN FlatXmlData ON XmlData.ID=FlatXmlData.ID WHERE SiteType='$siteType'");
		if ($names) return $names->map();
	}
	
	/**
	 * Fetch all site types from the database
	 *
	 * @param $informationType Class of the sites to display (currently only 'Site' or 'Analysis')
	 */
	static function getSiteTypes($informationType) {
		if ($informationType) {
			$types = DB::query("SELECT DISTINCT SiteType FROM XmlData INNER JOIN FlatXmlData ON XmlData.ID=FlatXmlData.ID 
								WHERE InformationType='$informationType'");
		}
		else {
			$types = DB::query('SELECT DISTINCT SiteType FROM XmlData');
		}
			
		if ($types) return $types->column();
	}
}