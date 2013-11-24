<?php

class XmlDataTest extends SapphireTest {
	static $fixture_file = 'environmental/tests/XmlDataTest.yml';
		
	function testGetSiteTypes() {
		$types = FlatXmlData::getSiteTypes('Site');
		$this->assertEquals(count($types), 3);
		$this->assertTrue(in_array('Type1', $types));
		$this->assertTrue(in_array('Type2', $types));
		$this->assertTrue(in_array('Broken', $types));
	}
	
	function testGetXml() {
		$xd = $this->objFromFixture('FlatXmlData', 'x1');
		$xml = $xd->getXml();
		$this->assertTrue($xml instanceof SimpleXMLElement);
	}
	
	function testHasField() {
		$xd = $this->objFromFixture('FlatXmlData', 'x1');
		$this->assertFalse($xd->hasField('NonexistentField'));
		$this->assertTrue($xd->hasField('StringField'));
	}
	
	function testGetField() {
		$xd = $this->objFromFixture('FlatXmlData', 'x1');
		$this->assertFalse(isset($xd->NonexistentField));
		$this->assertEquals($xd->StringField, 'a string');
	}
	
	function testRound() {
		$xd = $this->objFromFixture('FlatXmlData', 'x1');
		$this->assertEquals($xd->Round('IntField'), '1.23');
	}
	
	function testBroken() {
		$xd = $this->objFromFixture('FlatXmlData', 'broken');
		$xml = $xd->getXml();
		$this->assertTrue($xml instanceof SimpleXMLElement);
		$this->assertTrue(empty($xml));
	}
}