<?php

class XmlImportTaskTest extends SapphireTest {
	static $fixture_file = 'environmental/tests/XmlImportTaskTest.yml';

	function testAnalysis() {
		$import = new XmlImportTask;
		$import->analysis(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/Analysis.xml'));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 2);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Analysis' AND SiteType='StageReport' AND SiteID='52903'");
		$this->assertEquals($sites->Count(), 1);
		$site = $sites->First();
		$this->assertEquals($site->SiteName, 'HY Anatoki at Happy Sams');
		$this->assertEquals($site->Stage, 330);
		$this->assertEquals($site->DataTo, ' 5-May-2010 07:00');
	}
	
	function testSites() {
		$import = new XmlImportTask;
		$import->sites(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml'));
		$import->siteReadings(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/SiteReadings.xml'));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 4);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Site' AND SiteType='Swim Fresh' AND SiteID='2'");
		$this->assertEquals($sites->Count(), 1);
		$site = $sites->First();
		$this->assertEquals($site->SiteName, 'BW Motueka @ Alexanders Br');
		$this->assertEquals($site->Latitude, '-41.160402');
		$this->assertEquals($site->Longitude, '172.922363');
		// Check additional injected fields
		$this->assertEquals($site->Measurement, 'E.coli');
		$this->assertEquals($site->Unit, 'MPN/100mL');
		
		$readouts = DataObject::get('TimedXmlData');
		$this->assertEquals($readouts->Count(), 4);
		
		$readouts = DataObject::get('TimedXmlData', "SiteType='Swim Fresh' AND SiteID='2'", 'Time DESC');
		$this->assertEquals($readouts->Count(), 3);
		$readout = $readouts->First();
		$this->assertEquals($readout->Time, '2010-02-23 16:50:00');
		$this->assertEquals($readout->Value, '12');
	}
	
	function testAddingSites() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml')));
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/MoreSites.xml')));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 5);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Site' AND SiteType='Swim Fresh' AND SiteID='5'");
		$this->assertEquals($sites->Count(), 1);
		$site = $sites->First();
		$this->assertEquals($site->SiteName, 'BW Motueka @ SH bridge');
		$this->assertEquals($site->Latitude, '-41.090914');
		$this->assertEquals($site->Longitude, '173.010406');
	}
	
	/**
	 * The system is supposed to detect missing sites in a set and remove them.
	 * We make an assumption that the set has to be present for the sites to be removed.
	 */
	function testRemovingSites() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml')));
		//$import->import(array(array('func'=>'siteReadings', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/SiteReadings.xml')));
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/LessSites.xml')));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 3);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Site' AND SiteType='Swim Fresh' AND SiteID='2'");
		$this->assertNull($sites);
		
		// Too big dataset, not worth removing
		//$sites = DataObject::get('TimedXmlData', "SiteType='Swim Fresh' AND SiteID='2'");
		//$this->assertNull($sites);
	}
	
	function testUpdatingSites() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml')));
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/ChangedSites.xml')));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 4);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Site' AND SiteType='Swim Fresh' AND SiteID='2'");
		$this->assertEquals($sites->Count(), 1);
		$site = $sites->First();
		$this->assertEquals($site->Latitude, '-42.160402');
	}

	function testRemovingAnalyses() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'analysis', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Analysis.xml')));
		$import->import(array(array('func'=>'analysis', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/LessAnalysis.xml')));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 1);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Analysis' AND SiteType='StageReport' AND SiteID='52003'");
		$this->assertNull($sites);
		
	}
	
	function testUpdatingAnalyses() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'analysis', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Analysis.xml')));
		$import->import(array(array('func'=>'analysis', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/ChangedAnalysis.xml')));
		
		$sites = DataObject::get('FlatXmlData');
		$this->assertEquals($sites->Count(), 2);
		
		$sites = DataObject::get('FlatXmlData', "InformationType='Analysis' AND SiteType='StageReport' AND SiteID='52003'");
		$this->assertEquals($sites->Count(), 1);
		$site = $sites->First();
		$this->assertEquals($site->Stage, '1200');
	}

	function testUnpublishInvalidXmlPages() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml')));
		
		$sitePage = $this->objFromFixture('SiteXmlPage', 'sitePage');
		$sitePage->doPublish();
		
		$sites = Versioned::get_by_stage('FlatXmlData', 'Live', "InformationType='Site' AND SiteType='Swim Fresh' AND SiteID='2'");
		$this->assertEquals($sites->Count(), 1);
		
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/LessSites.xml')));
		
		// Should be automatically unpublished
		$sites = Versioned::get_by_stage('FlatXmlData', 'Live', "InformationType='Site' AND SiteType='Swim Fresh' AND SiteID='2'");
		$this->assertNull($sites);
	}
	
	function testTdcScenario() {
		$import = new XmlImportTask;
		// Import sites once a day
		$import->sites(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml'));
		
		// Import incremental data
		$import->siteReadings(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/SiteReadings.xml'));
		$readouts = DataObject::get('TimedXmlData', null, 'Time DESC');
		$this->assertEquals($readouts->Count(), 4);

		$import->siteReadings(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/SiteReadingsMore.xml'));
		$readouts = DataObject::get('TimedXmlData', null, 'Time DESC');
		$this->assertEquals($readouts->Count(), 5);
		$this->assertEquals($readouts->First()->Value, 13);
	}
	
	function testBrokenFiles() {
		$import = new XmlImportTask;
		ob_start();
		$import->import(array(
			array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Broken.xml'),
			array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/NonexistentFile.xml')
		));
		$output = ob_get_clean();
		
		$this->assertContains("File does not exist", $output);
		$this->assertContains("Skipping malformed XML document", $output);
	}
	
	function testVeryOldRemovedAutomatically() {
		$import = new XmlImportTask;
		$import->import(array(
			array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml'),
			array('func'=>'siteReadings', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/VeryOldSiteReadings.xml')
		));
		
		$readouts = DataObject::get('TimedXmlData');
		$this->assertNull($readouts);
	}
	
	function testWipeTimedType() {
		$import = new XmlImportTask;
		
		// Check "days ago"
		$import->sites(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml'));
		$import->siteReadings(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/VeryOldSiteReadings.xml'));
		
		$import->wipeTimedType('Swim Fresh', 30);
		
		$readouts = DataObject::get('TimedXmlData');
		$this->assertNull($readouts);
		
		// Check fixed threshold
		$import->sites(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml'));
		$import->siteReadings(simplexml_load_file(BASE_PATH.'/environmental/tests/XmlImportTaskTest/VeryOldSiteReadings.xml'));
		
		$import->wipeTimedType('Swim Fresh', '2002-01-01');
		
		$readouts = DataObject::get('TimedXmlData');
		$this->assertEquals($readouts->Count(), 1);
	}
	
	function testUpdateTitles() {
		$import = new XmlImportTask;
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/Sites.xml')));
		$import->import(array(array('func'=>'sites', 'file'=>BASE_PATH.'/environmental/tests/XmlImportTaskTest/ChangedSites.xml')));
		$import->processTitles(null);
		
		$id = $this->idFromFixture('SiteXmlPage', 'riverSitePage');
		$riverPage = DataObject::get_by_id('SiteXmlPage', $id);
		$this->assertEquals($riverPage->Title, 'Motueka - river flow changed');
	}
}