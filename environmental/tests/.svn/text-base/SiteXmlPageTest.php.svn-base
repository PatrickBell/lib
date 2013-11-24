<?php

class SiteXmlPageTest extends SapphireTest {
	static $fixture_file = 'environmental/tests/SiteXmlPageTest.yml';
	
	function testSpecialProperties() {
		$sitePage = $this->objFromFixture('SiteXmlPage', 'sitePage');
		$sitePage->write(); // populate automated fields
		
		$this->assertEquals($sitePage->getLat(), '-41.409325');
		$this->assertEquals($sitePage->getLong(), '173.145348');
		$this->assertEquals($sitePage->getTitle(), 'The Page Name');
		$this->assertEquals($sitePage->getMenuTitle(), 'The Page Name');
	}
	
	function testInfo() {
		$sitePage = $this->objFromFixture('SiteXmlPage', 'sitePage');
		$sitePage->write(); // populate automated fields
		
		$this->assertContains('The Page Name', $sitePage->Info());
	}
	
	function testIcon() {
		$sitePage = $this->objFromFixture('SiteXmlPage', 'sitePage');
		
		$this->assertNull($sitePage->Icon());
	}
	
	function testChangeURLSegment() {
		$xml = $this->objFromFixture('FlatXmlData', 'siteXml');
		$xml->Payload = "<?xml version='1.0'?><Site UniqueID='1'><PageName>New Page Name</PageName><AltSiteName>New Alt Name</AltSiteName><Latitude>-41.409325</Latitude><Longitude>173.145348</Longitude></Site>";
		$xml->write();
		
		$sitePage = $this->objFromFixture('SiteXmlPage', 'sitePage');
		$sitePage->Content = 'Modified';
		$sitePage->write(); // this should automatically modify URLSegment from PageName
		
		$sitePage = DataObject::get_by_id('SiteXmlPage', $this->idFromFixture('SiteXmlPage', 'sitePage'));
		$this->assertEquals($sitePage->URLSegment, 'type-1');
	}
}