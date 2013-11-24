<?php

class XmlPageFunctionalTest extends FunctionalTest {
	static $fixture_file = 'environmental/tests/functional/XmlPageFunctionalTest.yml';
	static $use_draft_site = true;
	
	function testDefaultTemplate() {
		$this->get('without-template');
		$this->assertPartialMatchBySelector('h3', 'Welcome to the default template');
	}
	
	function testExistingTemplate() {
		$this->get('river-flow-template');
		try {
			$this->assertPartialMatchBySelector('h3', 'Welcome to the default template');
		}
		catch (Exception $e) {
			return true;
		}
		
		$this->fail("Default template used incorrectly. Specific template should be used in this case.");
	}
}