<?php

class PageTest extends SapphireTest {
	static $fixture_file = 'mysite/tests/unit/PageTest.yml';
	
	function testRootParent() {
		$root = $this->objFromFixture('Page', 'root');
		$this->assertEquals($root->getRootParent()->Title, $root->Title, 'Root returns itself as root');

		$child1 = $this->objFromFixture('Page', 'child1');
		$this->assertEquals($child1->getRootParent()->Title, $root->Title, 'Child returns their parent as root');

		$child2 = $this->objFromFixture('Page', 'child2');
		$this->assertEquals($child2->getRootParent()->Title, $root->Title, 'Child of a child returns parent of a parent as root');
	}
}
