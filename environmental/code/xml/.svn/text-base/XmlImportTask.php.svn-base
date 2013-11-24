<?php
/**
 * Scheduled imports of XML data. Retains the uniqueness of each data type. Upon encountering a site type
 * for the first time, entire set is wiped off, so the updates to the payload are picked up. This is true
 * for "analysis" and "sites". Old timed records ("siteReadings") will not be updated.
 * 
 * Adding entries
 * --------------
 * Call format:
 *   XmlImportTask/files/{FILE1},{FILE2},{FILE3}
 * Each file entity has following designation (the / have to be replaced with |):
 *   {PATH}{FILE}={HANDLER}
 * For example:
 *   folder1|folder2|data_file.xml=sites
 * Valid handlers are: sites, siteReadings, analysis
 * 
 * Wiping entries
 * --------------
 * Call format:
 *   XmlImportTask/wipe/{TYPE1},{TYPE2},{TYPE3}
 * Each type entity has following designation:
 *   {SITETYPE}={DAYS|DATE}
 * For example:
 *   RiverFlow=30
 * Flat number (30) specifies number of days ago. Date (2010-05-01) will remove all earlier entries.
 *
 * PITFALL
 * -------
 * As SiteReadings contain fields that have to be moved to Sites, execute siteReadings last.
 * Also, you have to reimport siteReadings each time you import sites. SiteReadings contain some information
 * that belongs to sites, and it's wiped off when sites are imported (as they replace the old sites
 * completely). 
 * The data needs to be rebuilt from scratch, otherwise we would need to inspect every XML chunk in the 
 * database for updates, and this will eat too much time.
 */

class XmlImportTask extends HourlyTask {
	private $touchedTypes;
	//private $sites;
	
	static $url_handlers = array(
		'files/$Files' => 'processFiles',
		'wipe/$Types' => 'processWipe',
		'updateTitles' => 'processTitles'
	);
	
	/**
	 * Adds new entries to the database
	 */
	function processFiles($request) {
		increase_time_limit_to(3600);
		
		$list = array();
		
		$files = str_replace('|', '/', $request->param('Files'));
		
		$files = explode(',', $files);
		foreach ($files as $file) {
			$spec = explode('=', $file);
			if (in_array($spec[1], array('analysis', 'sites', 'siteReadings'))) {
				array_push($list, array('func'=>$spec[1], 'file'=>ASSETS_PATH."/xml/$spec[0]"));
			}
		}

		$this->import($list);
	}

	/**
	 * Removes old readouts from the database.
	 */
	function processWipe($request) {
		increase_time_limit_to(3600);
		
		$types = explode(',', $request->param('Types'));
		foreach ($types as $type) {
			$spec = explode('=', $type);

			$this->wipeTimedType($spec[0], $spec[1]);
		}
		
		echo "Database cleaned.\n";
	}
	
	/**
	 * Goes through all sites and copies the new titles from xml to the db fields.
	 * Take both stages into the account.
	 */
	function processTitles($request) {
		increase_time_limit_to(3600);
		
		$sitePages = array();
		$sitePages['Stage'] = Versioned::get_by_stage('SiteXmlPage', 'Stage');
		$sitePages['Live'] = Versioned::get_by_stage('SiteXmlPage', 'Live');
		
		if (!$sitePages) return;
		
		foreach ($sitePages as $stage=>$stagePages) {
			if (!$stagePages) continue;
			foreach ($stagePages as $sitePage) {
				if ($xml = $sitePage->XML()) {
					// trigger Title update if it differs from the one stored in xml
					if ($sitePage->Title!=$xml->PageName && $sitePage->Title!=$xml->AltSiteName) {
						$oldMode = Versioned::get_reading_mode();
						Versioned::reading_stage($stage);
						
						$sitePage->writeWithoutVersion();
						
						Versioned::set_reading_mode($oldMode);
					}
				}
			}
		}
		
		echo "Updated titles.\n";
	}
	
	function import($steps) {
		// We will delete the whole type upon first encounter, to make updates and removals possible
		$this->touchedTypes = array();
		$startTime = time();
		
		// Do the actual import (processes $this->touchedTypes and $this->sites)
		foreach ($steps as $step) {
			if (is_file($step['file'])) {
				$oldXmlErrors = libxml_use_internal_errors(true);
				$xml = simplexml_load_file($step['file']);
				$errors = libxml_get_errors();
				libxml_clear_errors();
				libxml_use_internal_errors($oldXmlErrors);
				
				if (!empty($errors)) {
					echo "Skipping malformed XML document: {$step['file']}, see below for list of errors.\n";
					foreach ($errors as $error) {
						echo "    ".trim($error->message)." (line ".trim($error->line).")\n";
					}
					continue;
				}
				
				if (!$xml) {
					echo "Could not load: {$step['file']}.\n";
					continue;
				}
				
				echo "Processing {$step['file']} with '{$step['func']}' handler.\n";
				$this->{$step['func']}($xml);
			}
			else {
				echo "File does not exist: {$step['file']}.\n";
			}
		}
	
		// TODO: Remove unmatched TimedXmlData. Couldn't find a way to do it reasonably quickly yet.
		
		// Remove very old TimedXmlData
		$this->wipeTimedType('all', 365);
		
		// Unpublish all SiteXmlPages that point to invalid records, in case something got deleted
		$invalidPages = DB::query("SELECT DISTINCT XmlPage_Live.ID, XmlData.SiteType, XmlData.SiteID 
									FROM XmlPage_Live LEFT JOIN XmlData ON (XmlPage_Live.SiteType=XmlData.SiteType AND XmlPage_Live.SiteID=XmlData.SiteID)
									LEFT JOIN SiteTree_Live ON SiteTree_Live.ID=XmlPage_Live.ID 
									WHERE SiteTree_Live.ClassName='SiteXmlPage' AND (XmlData.SiteType IS NULL OR XmlData.SiteID IS NULL)");
		echo "Unpublishing ".$invalidPages->numRecords()." invalid pages.\n";
		foreach ($invalidPages as $invalid) {
			$page = Versioned::get_by_stage('XmlPage', 'Live', "XmlPage_Live.ID='{$invalid['ID']}'");
			if ($page) $page->First()->doUnpublish();
		}
		
		echo "Import took ".(time()-$startTime)."s.\n";
	}
	
	/**
	 * Wipe the FlatXmlData type completely from the database. 
	 */
	function wipeType($siteType) {
		// Delete sites
		DB::query("DELETE FROM FlatXmlData, XmlData
			USING XmlData INNER JOIN FlatXmlData ON XmlData.ID=FlatXmlData.ID
			WHERE SiteType='$siteType'");
	}
	
	/**
	 * Similar to the wipeType, but it works on TimedXmlData
	 */
	function wipeTimedType($type, $threshold) {
		if (is_numeric($threshold)) {
			$threshold = date('Y-m-d H:i:m', strtotime(((int)$threshold).' days ago'));
		}
		else {
			$threshold = date('Y-m-d H:i:m', strtotime($threshold));
		}
		
		$siteType = Convert::raw2sql($type);
		
		if ($siteType=='all') {
			DB::query("DELETE FROM TimedXmlData, XmlData 
					USING XmlData INNER JOIN TimedXmlData ON XmlData.ID=TimedXmlData.ID
					WHERE Time<'$threshold'");				
		}
		else {
			DB::query("DELETE FROM TimedXmlData, XmlData 
					USING XmlData INNER JOIN TimedXmlData ON XmlData.ID=TimedXmlData.ID
					WHERE SiteType='$siteType' AND Time<'$threshold'");
		}	
	}
	
	/**
	 * Collect information for environmental monitoring sites
	 */
	function sites($xml) {
		$count = 0;
		foreach ($xml as $sourceNode) {
			// Required fields: SiteType and ID
			if (!isset($sourceNode->DataType) || !isset($sourceNode->SiteName) || !$sourceNode['UniqueID']) continue;
			
			$infoType = 'Site';
			$siteType = (string)$sourceNode->DataType;
			$siteID = (string)$sourceNode['UniqueID'];
			
			if (!isset($this->touchedTypes[$siteType])) {
				// This is the first time we are encountering this site type. Delete all records to make space for new data.
				$this->wipeType($siteType);
			}
			
			// Touch type as used and mark the site off the list
			$this->touchedTypes[$siteType] = true;
			
			// Uniqueness
			$result = DB::query("SELECT COUNT(*) AS Count FROM XmlData INNER JOIN FlatXmlData ON XmlData.ID=FlatXmlData.ID 
									WHERE \"SiteType\"='$siteType' AND \"SiteID\"='$siteID'");
			if ((int)$result->value()) continue;
			
			// Store
			$xd = new FlatXmlData;
			$xd->InformationType = $infoType;
			$xd->SiteType = $siteType;
			$xd->SiteName = (string)$sourceNode->SiteName;
			$xd->xml = $sourceNode;
			$xd->SiteID = $siteID;
			$xd->Payload = $xd->getXml()->asXML();
			$xd->write();
			$count++;
		}
		
		echo "    $count processed.\n";
	}
	
	/**
	 * Collect timed data for the environmental monitoring sites
	 * NOTE: execute this after importing 'sites', as there is some site data sitting in this file too.
	 * You can do it without big performance hit, the old entries will be skipped.
	 */
	function siteReadings($xml) {
		// Get the site type
		if (!isset($xml->DataType)) return
		$siteType = null;
		$siteType = (string)$xml->DataType;

		$count = 0;
		foreach ($xml as $sourceNode) {
			// Required fields: ID
			if (!$sourceNode['UniqueID']) continue;
			$siteID = (string)$sourceNode['UniqueID'];
			
			// The readings file contains also master information for the site (Unit, Measurement).
			// We need to inject that information into the site's XmlData, as it does not belong here.
			$flatxd = DataObject::get_one('FlatXmlData', "\"SiteType\"='$siteType' AND \"SiteID\"='$siteID'", 'Created DESC');
			if ($flatxd) {
				$flatxdXml = $flatxd->getXml();
				if (isset($flatxdXml)) {
					foreach ($sourceNode->children() as $child) {
						if ($child->getName()!='Values') {
							// Move the additional field into the payload
							$flatxdXml->{$child->getName()} = (string)$child;
						}
					}
					$flatxd->Payload = $flatxdXml->asXML();
					$flatxd->write();
				}
			}
			
			// Get the timestamped values
			if (!isset($sourceNode->Values)) continue;
			
			// Get the latest entry in the database
			$result = DB::query("SELECT Time FROM XmlData INNER JOIN TimedXmlData ON XmlData.ID=TimedXmlData.ID 
									WHERE \"SiteType\"='$siteType' AND \"SiteID\"='$siteID' ORDER BY \"Time\" DESC LIMIT 1");
			$latest = $result->value();
			
			foreach ($sourceNode->Values->children() as $value) {
				if (!isset($value->DateTime)) continue;

				// We need the date out of the xml. But the following fails, thinking it's american date
				// $parsedDate = date('Y-m-d H:i:s', strtotime($value->DateTime));
				
				// And this doesnt work on some servers
				// $parts = date_parse_from_format('j/m/Y g:i:s a', $value->DateTime);
				// $parsedDate = date('Y-m-d H:i:s', strtotime("{$parts['year']}-{$parts['month']}-{$parts['day']} {$parts['hour']}:{$parts['minute']}:{$parts['second']}"));				
				
				// So we cheat and exchange month with day, so the strtotime works as expected
				$pieces = explode('/', $value->DateTime);
				$tmp = $pieces[0];
				$pieces[0] = $pieces[1];
				$pieces[1] = $tmp;
				$reassembled = implode('/', $pieces);
				$parsedDate = date('Y-m-d H:i:s', strtotime($reassembled));
				
				// Import only if the readout is newer than the latest entry in the database. Ignore the entries already imported.
				if ($latest && strtotime($parsedDate)<=strtotime($latest)) continue;
				
				// Uniqueness
				$result = DB::query("SELECT COUNT(*) AS Count FROM XmlData INNER JOIN TimedXmlData ON XmlData.ID=TimedXmlData.ID WHERE \"SiteType\"='$siteType' AND \"SiteID\"='$siteID' AND \"Time\"='$parsedDate'");
				if ((int)$result->value()) continue;
				
				// Store
				$xd = new TimedXmlData;
				$xd->SiteType = $siteType;
				$xd->xml = $value;
				$xd->SiteID = $siteID;
				$xd->Time = (string)$parsedDate;
				$xd->Payload = $xd->getXml()->asXML();
				$xd->write();
				$count++;
			}
		}
		
		echo "    $count added.\n";
	}
	
	/**
	 * Collect the analysis information data.
	 */
	function analysis($xml) {
		$count = 0;
		foreach ($xml as $sourceNode) {
			// Required fields: SiteType and ID
			if (!$sourceNode->getName() || !isset($sourceNode->Syn2) || !isset($sourceNode->SiteName)) continue;
			
			$infoType = 'Analysis';
			$siteType = $sourceNode->getName();
			$siteID = (string)$sourceNode->Syn2;
			
			if (!isset($this->touchedTypes[$siteType])) {
				// This is the first time we are encountering this site type. Delete all records to make space for new data.
				$this->wipeType($siteType);
			}
			
			// Touch type as used and mark the site off the list
			$this->touchedTypes[$siteType] = true;
			
			// Uniqueness
			$result = DB::query("SELECT COUNT(*) AS Count FROM XmlData INNER JOIN FlatXmlData ON XmlData.ID=FlatXmlData.ID 
									WHERE \"SiteType\"='$siteType' AND \"SiteID\"='$siteID'");
			if ((int)$result->value()) continue;
			
			// Store
			$xd = new FlatXmlData;
			$xd->InformationType = $infoType;
			$xd->SiteType = $siteType;
			$xd->SiteName = (string)$sourceNode->SiteName;
			$xd->xml = $sourceNode;
			$xd->SiteID = $siteID;
			$xd->Payload = $xd->getXml()->asXML();
			$xd->write();
			$count++;
			
			$this->touchedTypes[$xd->SiteType] = true;
		}
		
		echo "    $count processed.\n";
	}
}