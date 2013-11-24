<?php

class GoogleMapPageTest extends SapphireTest {
	static $fixture_file = 'geotagging/tests/GoogleMapPageTest.yml';
	
	function testGeotaggedChildren() {
		$mapPage = $this->objFromFixture('GoogleMapPage', 'mapPage');
		$children = $mapPage->GeotaggedChildren();
		
		$this->assertEquals($children->Count(), 2);
		$this->assertDOSContains(array(
			array('Lat'=>'10', 'Long'=>'10'),
			array('Lat'=>'20', 'Long'=>'20'),
		), $children);
	}

	function testDefaultIcon() {
		$mapPage = $this->objFromFixture('GoogleMapPage', 'grayMapPage');
		$children = $mapPage->GeotaggedChildren();
		
		$this->assertEquals($children->Count(), 1);
		$this->assertDOSContains(array(
			array('Lat'=>'30', 'Long'=>'30', 'ComputedIcon'=>Director::baseURL().'geotagging/images/icons/Gray.png'),
		), $children);
	}
}
