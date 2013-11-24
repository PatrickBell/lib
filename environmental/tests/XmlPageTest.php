<?php

class XmlPageTest extends SapphireTest {
	static $fixture_file = 'environmental/tests/XmlPageTest.yml';
	
	function testGetTemplateName() {
		$sitePage = $this->objFromFixture('XmlPage', 'flatSitePage');
		$this->assertEquals($sitePage->getTemplateName(), 'FlatType');
	}

	function testGetHistoricalXML() {
		$sitePage = $this->objFromFixture('XmlPage', 'flatSitePage');
		$readouts = $sitePage->HistoricalXML(365000);
		$this->assertEquals($readouts->Count(), 2);
		$this->assertEquals($readouts->First()->Value, 20);
		$this->assertEquals($readouts->Last()->Value, 10);
		
		$readouts = $sitePage->HistoricalXML('2010-01-02');
		$this->assertEquals($readouts->Count(), 1);
		$this->assertEquals($readouts->First()->Value, 20);
	}
	
	function testGetLatestXML() {
		$sitePage = $this->objFromFixture('XmlPage', 'flatSitePage');
		$readout = $sitePage->LatestXML();
		$this->assertEquals($readout->Value, 20);
	}
	
	function testGetXML() {
		$sitePage = $this->objFromFixture('XmlPage', 'flatSitePage');
		$site = $sitePage->XML();
		$this->assertEquals($site->SiteName, 'The Site');
	}

	function testGetAllXML() {
		$sitePage = $this->objFromFixture('XmlPage', 'analysisSitePage');
		$sites = $sitePage->AllXML();
		$this->assertEquals($sites->Count(), 2);
		$this->assertEquals($sites->First()->SiteName, 'The Analysis Site One');
		$this->assertEquals($sites->Last()->SiteName, 'The Analysis Site Two');
	}	
}