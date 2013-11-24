<?php

class AjaxControllerFunctionalTest extends FunctionalTest {
	static $fixture_file = 'environmental/tests/functional/AjaxControllerFunctionalTest.yml';
		
	function testGetSiteNames() {
		$response = $this->get('Ajax_Controller/getsitenames/Type');

		$this->assertPartialMatchBySelector('option', 'First Site');
		$this->assertPartialMatchBySelector('option', 'Second Site');
	}
}