<?php

class LibraryItemPageTest extends SapphireTest {
	static $fixture_file = 'libraries/tests/LibraryItemPageTest.yml';
	
	function testItemsByThisAuthor() {
		$item1 = $this->objFromFixture('LibraryItemPage', 'item1');
		$other = $item1->getItemsByThisAuthor();
		$this->assertEquals(count($other), 1);
		$this->assertEquals($other->First()->Tnumber, '102');
	}

	function testAutoFields() {
		$item = new LibraryItemPage();
		$item->ItemTitle = "Earth";
		$item->Author = "Jake Worm";
		$item->Tnumber = 100;
		$item->AssesionNo = 'AAA';

		$this->assertEquals($item->Title, 'Earth by Jake Worm');
		$this->assertEquals($item->MenuTitle, 'Earth');
		$this->assertEquals($item->URLSegment, '100');
	}

	function testCatalogueLink() {
		$item = new LibraryItemPage();
		$item->URLSegment = 'library-item';

		$this->assertEquals($item->getCatalogueLink(), $item->Link(), 'Library item links to itself by default');

		$item->AccessionNo = '123';

		$this->assertEquals($item->getCatalogueLink(), "http://search.ebscohost.com/login.aspx?direct=true&scope=site&site=eds-live&profile=eds&authtype=guest&custid=s1191038&groupid=main&db=cat00733a&AN=tasman.$item->AccessionNo", 'Library item uses AccessionNo if available');

		$item->URLOverride = 'http://silverstripe.com';

		$this->assertEquals($item->getCatalogueLink(), "http://silverstripe.com", 'Library item link can be overriden.');
	}
}
