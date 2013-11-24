<?php

class HomePageDecoratorTest extends SapphireTest {
	static $fixture_file = 'libraries/tests/HomePageDecoratorTest.yml';
	
	function testGetFeatured() {
		$home = $this->objFromFixture('HomePage', 'home');
		$this->assertDOSEquals(
			array(
				array('Tnumber'=>101),
				array('Tnumber'=>104)
			),
			$home->getFeatured()
		);
	}
}
