<?php

class NoticeListPageTest extends SapphireTest {
	public static $fixture_file = 'mysite/tests/unit/NoticeListPageTest.yml';

	public function testLatestNotice() {
		$list = $this->objFromFixture('NoticeListPage', 'noticeListPage');

		$notice = $list->LatestNotices();
		$this->assertEquals($notice->Count(), 3);
		$this->assertDOSContains(
			array(
				array('Title'=>'title2'),
				array('Title'=>'title3'),
				array('Title'=>'title4')
			),
			$notice
		);

		$notice = $list->LatestNotices(2);
		$this->assertEquals($notice->Count(), 2);
		$this->assertDOSContains(
			array(
				array('Title'=>'title2'),
				array('Title'=>'title3')
			),
			$notice
		);

	}
}

