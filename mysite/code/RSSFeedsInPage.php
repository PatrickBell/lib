<?php

class RSSFeedsInPage extends Page {

	static $db = array(
		"TopContent" => "HTMLText",
		"Feed1URL" => "Text",
		"Feed2URL" => "Text",
		"Feed3URL" => "Text",
		"FeedItems" => "Int",
		"FeedContainer" => "Text",
		"FeedElement" => "Text",
		"NodeForFeedTitle" => "Varchar",
		"NodeForFeedLink" => "Varchar",
		"NodeForFeedDescription" => "Varchar",
		"NodeForFeedDate" => "Varchar",
		"NodeForFeedAuthor" => "Varchar",
		"FeedIdentifier" => "Varchar",
	);

	static $has_one = array(
	);

	
// add a another content box under the Content tab		
	function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->addFieldToTab("Root.Content.Main", new HtmlEditorField("TopContent","Top Content"),"Content");
		$fields->renameField("Content", "Bottom Content");

		$fields->addFieldToTab("Root.Content.RSSFeeds", new NumericField("FeedItems","Number of Feed Items to Show"));

		$fields->addFieldToTab("Root.Content.RSSFeeds", new HeaderField("PleaseNote", "Please note: the RSS feeds got from the following 3 urls must using the same xml schema"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("Feed1URL","URL of Feed #1, this is required"));
		if($this->Feed1URL !=''){
			$fields->addFieldToTab("Root.Content.RSSFeeds", new LiteralField("DownloadSample1", "<a tagget=\"_blank\" href=\"".$this->Link('downloadsample1')."\">Download XML sample file</a>"));
		}
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("Feed2URL","URL of Feed #2, this is optional"));
		if($this->Feed2URL !=''){
			$fields->addFieldToTab("Root.Content.RSSFeeds", new LiteralField("DownloadSample2", "<a tagget=\"_blank\" href=\"".$this->Link('downloadsample2')."\">Download XML sample file</a>"));
		}
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("Feed3URL","URL of Feed #3, this is optional"));
		if($this->Feed3URL !=''){
			$fields->addFieldToTab("Root.Content.RSSFeeds", new LiteralField("DownloadSample3", "<a tagget=\"_blank\" href=\"".$this->Link('downloadsample3')."\">Download XML sample file</a>"));
		}
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("FeedIdentifier","define the tag name for identifying each feed, eg '<b>id</b>', '<b>guid</b>' or '<b>link</b>'"));
		
		$fields->addFieldToTab("Root.Content.RSSFeeds", new HeaderField("ContainerElement", "Define the tag name in the sample xml file for parsing the feed item", 3));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("FeedContainer","Container Node for All Feed Items (eg. '<b>channel</b>' or '<b>entry</b>', etc)"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("FeedElement","Node for Feed Items (eg. '<b>item</b>', '<b>entry</b>', etc. Or just leave it blank if you put tag name of the feed items in the Container above)"));
		
		$fields->addFieldToTab("Root.Content.RSSFeeds", new HeaderField("NodeNames", "Define the tag name in the sample xml file for rendering into SilverStripe template", 3));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new LiteralField("MoreExplain1", "To get an element which has namespace in the tag name, use it as normal. eg '<b>itunes:subtitle</b>' or '<b>media:content</b><br />'"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new LiteralField("MoreExplain2", "To get an element's attribute, using '.' (dot) to separate the tag name and attribute name, eg '<b>enclosure.url</b>' or '<b>media:content.url</b>'"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("NodeForFeedTitle", "tag name that is used for Feed Title, eg '<b>title</b>'"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("NodeForFeedLink", "tag name that is used for Feed Link, eg '<b>media:content.url</b>' or '<b>link</b>'"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("NodeForFeedDescription", "tag name that is used for Feed Description, eg '<b>description</b>' or '<b>summary</b>'"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("NodeForFeedDate", "tag name that is used for Feed Date, eg '<b>pubDate</b>' or '<b>updated</b>'"));
		$fields->addFieldToTab("Root.Content.RSSFeeds", new TextField("NodeForFeedAuthor", "tag name that is used for Feed Author, eg '<b>itunes:subtitle</b>'"));
		return $fields;
	}
}

class RSSFeedsInPage_Controller extends Page_Controller {

	function GetRSSFeeds() {
		$RSSFeeds = new DataObjectSet();
		$urlCounter = 0;
		if($this->Feed1URL !=''){
			$urlCounter++;
		}
		if($this->Feed2URL !=''){
			$urlCounter++;
		}
		if($this->Feed3URL !=''){
			$urlCounter++;
		}
		
		if($this->FeedItems){
			$num1 = $num2 = ceil($this->FeedItems/$urlCounter);
			$num3 = $this->FeedItems - ($num1+$num2);
		}else{
			$num1 = $num2 = $num3 = 10;
		}
		
		if($this->Feed1URL !=''){
			$RSSFeeds = RestfulLinks($this->Feed1URL, $this->FeedContainer, $this->FeedElement, 3600, $num1);
		}
		if($this->Feed2URL !=''){
			$RSSFeeds ->merge(RestfulLinks($this->Feed2URL, $this->FeedContainer, $this->FeedElement, 3600, $num2));
		}
		if($this->Feed3URL !=''){
			$RSSFeeds ->merge(RestfulLinks($this->Feed3URL, $this->FeedContainer, $this->FeedElement, 3600, $num3));
		}
					
		//Remove duplicates
		if($this->FeedIdentifier) $RSSFeeds -> removeDuplicates($this->FeedIdentifier);
		$RSSList = new DataObjectSet();
		
		if($RSSFeeds && $RSSFeeds->count()){
			$fields = array(
				"NodeForFeedTitle",
				"NodeForFeedLink",
				"NodeForFeedDescription",
				"NodeForFeedDate",
				"NodeForFeedAuthor",
			);
			foreach($RSSFeeds as $RSSItem){
				$extrainfo = array('Title'=>$this->Title);
				foreach($fields as $field){
					if($this->$field) {
						$tag = $this->parseTagName($this->$field);
						if(is_string($tag)){
							$extrainfo[$field] = strip_tags($RSSItem->$tag, '<a>');
						}else if(is_array($tag)){
							$tagname = $tag[0];
							$tagattrname = $tag[1];
							$attrs = $RSSItem->$tagname->getArray();
							$extrainfo[$field] = strip_tags($attrs[$tagattrname], '<a>');
						}
					}
				}
				if(isset($extrainfo['NodeForFeedDate'])){
					$extrainfo['NodeForFeedDate'] = date('j F Y, \a\t g:i A', strtotime($extrainfo['NodeForFeedDate']));
				}
				$RSSList -> push(new ArrayData($extrainfo));
				
			}
			
		}			
		// Limit the number of events returned;
		return $RSSList;
	}
	
	function parseTagName($tag){
		if(strpos($tag, ".")){
			return explode(".", $tag);
		}else{
			return $tag;
		}
	}
	
	function downloadsample1(){
		$this->downloadsample($this->Feed1URL, 1);
	}
	
	function downloadsample2(){
		$this->downloadsample($this->Feed2URL, 2);
	}
	
	function downloadsample3(){
		$this->downloadsample($this->Feed3URL, 3);
	}
	
	function downloadsample($url, $i){
		$rssfeed = new RestfulService($url,3600);
		$conn = $rssfeed->request('')->getBody();
		$xml = new SimpleXMLElement($conn);
		$xml = $xml->AsXML();
		SS_HTTPRequest::send_file($xml, "sample".$i.".xml", 'text/xml')->output();
		exit;
	}

}

?>