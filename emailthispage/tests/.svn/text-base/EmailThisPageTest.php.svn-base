<?php
class EmailThisPageTest extends FunctionalTest {

	function testEmailThisPage() {
		$page = new Page();
		$page->Title = "Test page";
		$page->URLSegment = "Test-page";
		$id = $page->write();

		$page = DataObject::get_by_id("Page",$id);
		$form = $page->EmailThisPageForm();
		$this->assertNotNull($form,"Form returned");
		$this->assertEquals(get_class($form),"Form","Form returned");
	}
}
?>