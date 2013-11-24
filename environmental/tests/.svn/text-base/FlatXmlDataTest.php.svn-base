<?php

class FlatXmlDataTest extends SapphireTest {
	static $fixture_file = 'environmental/tests/FlatXmlDataTest.yml';
		
	function testGetSiteNames() {
		$names = FlatXmlData::getSiteNames('Type');
		$this->assertEquals(count($names), 2);
		$this->assertTrue(in_array('First Site', $names));
		$this->assertTrue(in_array('Second Site', $names));
	}
}