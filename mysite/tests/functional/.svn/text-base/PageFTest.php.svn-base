<?php 

class PageFTest extends FunctionalTest {
	static $fixture_file = 'mysite/tests/functional/PageFTest.yml';
	static $use_draft_site = true;

	function testCivilDefence() {
		$this->get('home');
		$selected = $this->cssParser()->getBySelector('#alert');
		$this->assertTrue(count($selected)==0);

		$config = SiteConfig::current_site_config();
		$config->CivilDefenceAlertMode = true;
		$config->write();

		$this->get('home');
		$selected = $this->cssParser()->getBySelector('.alert');
		$this->assertTrue(count($selected)>0);
	}
}
