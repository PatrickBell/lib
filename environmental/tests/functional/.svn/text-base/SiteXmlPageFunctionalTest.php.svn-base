<?php

class SiteXmlPageFunctionalTest extends FunctionalTest {
	static $fixture_file = 'environmental/tests/functional/SiteXmlPageFunctionalTest.yml';
	static $use_draft_site = true;

	// Skip this, no search functionality on the stub site. Can be used on integration site.
	function testSiteComesUpInSearch() {
		$sitePage = $this->objFromFixture('SiteXmlPage', 'sitePage');
		$sitePage->write(); // populate automated fields

		// Note: we are building agains modules only, so there is no way to execute site search
		//$result = $this->get('type-1/SearchForm?Search=The%20Page%20Name&action_results=Search&mode=pages');
		//$this->assertPartialMatchBySelector('.searchResultHeader', 'The Page Name');
	}
}
