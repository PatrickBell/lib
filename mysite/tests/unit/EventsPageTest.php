<?php

class EventsPageTest extends SapphireTest {
	function setUp() {
		parent::setUp();
		EventsPage_Controller::$rss_feed = Director::absoluteBaseURL().'ItsonMockFeed/all';

		// TODO: destroy cache
	}

	function testEventList() {
		$controller = new EventsPage_Controller();	
		$events = $controller->EventList(3);
		$this->assertEquals($events->Count(), 3);

		$arr = $events->toArray();
		$entry = $arr[0];
		$this->assertEquals($entry->NoBreakEventTitle, 'Event 4 (Tuesday 4th January 2011)');
		$this->assertEquals($entry->EventLink, 'http://itson.co.nz/event4');
		// TODO: fix the body parsing 
		// $this->assertEquals($entry->ShortSummary,'Body 4');

		$entry = $arr[1];
		$this->assertEquals($entry->NoBreakEventTitle, 'Event 3 (Wednesday 5th January 2011)');
		$this->assertEquals($entry->EventLink, 'http://itson.co.nz/event3');
		// TODO: fix the body parsing 
		// $this->assertEquals($entry->ShortSummary,'Body 3');

		$entry = $arr[2];
		$this->assertEquals($entry->NoBreakEventTitle, 'Event 2 (Thursday 6th January 2011)');
		$this->assertEquals($entry->EventLink, 'http://itson.co.nz/event2');
	}
}
