<?php

class NoticeListPage extends Page {

	static $db = array(
		);

	static $has_one = array(
		);

	static $allowed_children = array(
			'NoticePage'		
		);	


	function LatestNotices($num = 5) {
		$GetNotices = DataObject::get("NoticePage", "ParentID = $this->ID AND PublishNotice = 1", "DisplayDate DESC, Sort ASC", "", $num);

		if($GetNotices){
			foreach($GetNotices as $NoticeItem){
				$ShortSummary =	wordwrap($NoticeItem->Content, 200, ".", false);

				$NoticeItem->ArticleTitle = $NoticeItem->Title;
				$NoticeItem->ArticleLink = $NoticeItem->Link();
				$NoticeItem->ShortSummary = strip_tags(substr($ShortSummary,0,strpos($ShortSummary,".",0)));
			}
		}
		return $GetNotices;
	}

}

class NoticeListPage_Controller extends Page_Controller {

}
