<?php

class CemeteryPageFTest extends FunctionalTest {
	public static $fixture_file = 'ncsconnect/tests/functional/CemeteryPageFTest.yml';
	public static $use_draft_site = true;

	function setUp() {
		parent::setUp();
		CemeteryPage_Controller::$ncs_server = Director::absoluteBaseURL().'NcsMockServer';
	}

	function testSearch() {
		$this->get('cemeteryPage');
		$response = $this->submitForm(
			'Form_CemeterySearchForm',
			null,
			array(
				'surname'=>'Easton',
				'cemetery'=>'Motueka'
			)
		);
		$table = $this->cssParser()->getBySelector('#content table');
		$table = $table[0];
		$this->assertContains('Easton, Elizabeth', $table->asXML());
		$this->assertContains('Motueka', $table->asXML());
		$this->assertContains('26/01/1945', $table->asXML());
		$this->assertContains('89 years',$table->asXML());

		$response = $this->get('cemeteryPage?uid=31643');
		$table = $this->cssParser()->getBySelector('#content table');
		$table = $table[0];
		$this->assertContains('Betty Violet', $table->asXML());

		$content = $this->cssParser()->getBySelector('#content');
		$content = $content[0];
		$this->assertContains('Smith, Raymond', $content->asXML());
		$this->assertContains(Director::baseURL().'cemeteryPage/?uid=30187', $content->asXML());
	}

	function testSearchNotFound() {
		$this->get('cemeteryPage');
		$response = $this->submitForm(
			'Form_CemeterySearchForm',
			null,
			array(
				'surname'=>'Nosuchsurname'
			)
		);
		$table = $this->cssParser()->getBySelector('#content');
		$table = $table[0];
		$this->assertContains('No Records Found', $table->asXML());

		$response = $this->get('cemeteryPage?uid=1');
		$table = $this->cssParser()->getBySelector('#content');
		$table = $table[0];
		$this->assertContains('Cannot Access Cemetery Database', $table->asXML());
	}
}
