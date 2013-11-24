<?php

class GoogleMapPageFunctionalTest extends FunctionalTest {
	static $fixture_file = 'geotagging/tests/functional/GoogleMapPageFunctionalTest.yml';
	static $use_draft_site = true;
	
	function testShowsChildren() {
		$this->get('mapPage');

		$this->assertContains('Site Title', $this->content());
		$this->assertContains('Sub Site Title', $this->content());
	}
}