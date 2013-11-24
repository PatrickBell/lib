<?php

class RatesPageFTest extends FunctionalTest {
	public static $fixture_file = 'ncsconnect/tests/functional/RatesPageFTest.yml';
	public static $use_draft_site = true;

	function setUp() {
		parent::setUp();
		RatesPage_Controller::$ncs_server = Director::absoluteBaseURL().'NcsMockServer';
	}

	function testSearch() {
		$this->get('ratesPage');
		$response = $this->submitForm(
			'Form_RatesSearchForm',
			null,
			array(
				'street_name'=>'Takaka'
			)
		);
		$table = $this->cssParser()->getBySelector('#content table');
		$table = $table[0];
		$this->assertContains('Central Takaka Road, Takaka', $table->asXML());

		$response = $this->get('ratesPage?uid=1010');
		$table = $this->cssParser()->getBySelector('#content table');
		$table = $table[0];
		$this->assertContains('17 Rangihaeata Road, Takaka', $table->asXML());

	}

	function testSearchNotFound() {
		$this->get('ratesPage');
		$response = $this->submitForm(
			'Form_RatesSearchForm',
			null,
			array(
				'street_name'=>'Nonexistent Street'
			)
		);
		$table = $this->cssParser()->getBySelector('#content');
		$table = $table[0];
		$this->assertContains('No Properties Found', $table->asXML());
		
		$response = $this->get('ratesPage?uid=1');
		$table = $this->cssParser()->getBySelector('#content');
		$table = $table[0];
		$this->assertContains('Cannot Access Rates Database', $table->asXML());
	}
}

