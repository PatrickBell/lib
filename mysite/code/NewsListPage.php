<?php

class NewsListPage extends Page {

	static $db = array(
		);

	static $has_one = array(
		);

	static $allowed_children = array(
			'NewsPage'
		);

	function LatestNews($num=5) {
		$now = SS_Datetime::now();
		$time = $now->Format('U');

		//Retrieve all child News Pages which are 28 days old or less
		$GetNews = DataObject::get("NewsPage", "ParentID = '$this->ID' AND DisplayDate>='".date("Y-m-d",($time-(28*86400)))."'", "DisplayDate DESC, Sort ASC", "", $num);

		if($GetNews){	
			foreach($GetNews as $NewsItem){
				$ShortSummary =	wordwrap($NewsItem->Content, 200, ".", false);
				
				$NewsItem->ArticleTitle = $NewsItem->Title;
				$NewsItem->ArticleLink = $NewsItem->Link();
				$NewsItem->ShortSummary = strip_tags(substr($ShortSummary,0,strpos($ShortSummary,".",0)));
			}
		}
		
		return $GetNews;
	}
}

class NewsListPage_Controller extends Page_Controller {

}
