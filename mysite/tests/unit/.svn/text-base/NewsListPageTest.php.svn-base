<?php

class NewsListPageTest extends SapphireTest {
	public static $fixture_file = 'mysite/tests/unit/NewsListPageTest.yml';

	public function testLatestNews() {
		$list = $this->objFromFixture('NewsListPage', 'newsListPage');

		SS_Datetime::set_mock_now('2010-01-30 12:00:00');

		$news = $list->LatestNews();
		$this->assertEquals($news->Count(), 3);
		$this->assertDOSContains(
			array(
				array('Title'=>'title2'),
				array('Title'=>'title3'),
				array('Title'=>'title4')
			),
			$news
		);

		$news = $list->LatestNews(2);
		$this->assertEquals($news->Count(), 2);
		$this->assertDOSContains(
			array(
				array('Title'=>'title2'),
				array('Title'=>'title3')
			),
			$news
		);

	}
}
