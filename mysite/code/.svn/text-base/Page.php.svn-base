<?php
/**
 * Generic page. Every page in the CMS derives from this one.
 */

class Page extends SiteTree {

	static $db = array(
		"ShowChildren" => "Int",
		);
	
	static $defaults = array(
		"ShowChildren" => 8,		
		);

	function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->renameField("Content", "Main Content");
		$fields->renameField("MetaDescription", "Short Description to Display on Parent Landing Page");
		$fields->addFieldToTab("Root.Content.Metadata", new NumericField("ShowChildren","Number of Children to Display on Parent Landing Page"),"MetaDescription");
		return $fields;
	}

	/**
	 * Get the topmost parent (current site section)
	 */
	function getRootParent() {
		$stack = $this->parentStack();
		if ($stack) {
			return array_pop($stack);
		}
	}

	function URLTimeTag(){
		$urltimetag = '?m='.time();
		return $urltimetag;
	}

	function results($data, $form){
		$data = array(
			'Results' => $form->getResults(),
			'Query' => $form->getSearchQuery(),
			'Title' => 'Search Results'
			);
		$this->Query = $form->getSearchQuery();

		return $this->customise($data)->renderWith(array('Page_results', 'Page'));
	}

	function LimitedChildren() {
		// Returns children as limited by ShowChildren value in Page.
		$ID = $this->ID;
		$Limit = DB::query("SELECT ShowChildren FROM Page WHERE ID = '$ID'")->value();
		return $this->Children()->getRange(0, $Limit);
	}
	
	public function Breadcrumbs($maxDepth = 20, $unlinked = false, $stopAtPageType = false, $showHidden = false) {
		$page = $this;
		$parts = array();
		$i = 0;
		while(
			$page  
 			&& (!$maxDepth || sizeof($parts) < $maxDepth) 
 			&& (!$stopAtPageType || $page->ClassName != $stopAtPageType)
 		) {
			if($showHidden || $page->ShowInMenus || ($page->ID == $this->ID)) { 
				if($page->URLSegment == 'home') $hasHome = true;
				if(($page->ID == $this->ID) || $unlinked) {
				 	$parts[] = Convert::raw2xml($page->Title);
				} else {
					$parts[] = ("<a onclick=\"_gaq.push(['_trackEvent', 'Links', 'Bread Crumbs', '".$page->Title."']);\"  href=\"" . $page->Link() . "\">" . Convert::raw2xml($page->Title) . "</a>"); 
				}
			}
			$page = $page->Parent;
		}

		return implode(self::$breadcrumbs_delimiter, array_reverse($parts));
	}
	
	function getContent() {
		$content = $this->getField('Content');
		$thisURLRelativeToBase = Director::makeRelative(Director::absoluteURL($_SERVER['REQUEST_URI']));
		if (!Director::is_ajax() && substr($thisURLRelativeToBase,0,5) != "admin") {
			$content = str_replace('href="#','href="'.$thisURLRelativeToBase.'#',$content);
			$content = str_replace('href=\'#','href=\''.$thisURLRelativeToBase.'#',$content);
		}
		return $content;
	}

}

class Page_Controller extends ContentController {
	
	public function init() {
		parent::init();

		// special case for a thirdparty JS file which shouldn't be combined!
		Requirements::javascript('http://supportforms.epnet.com/eit/scripts/ebscohostsearch.js');

		// Get the theme base path, e.g. themes/tdc (note: no trailing slash!)
		$themeFolder = $this->ThemeDir();
		
		// Set up a custom combined files folder (note: inside the theme so relative paths are intact in the original css)
		Requirements::set_combined_files_folder($themeFolder . '/combinedfiles');

		// Block sapphire's requirements, they get combined below
		// Note: there is an issue (javascript error: "Behaviour is undefined") with blocking and combining the behaviour.js, so it's left-out for now.
		Requirements::block('sapphire/thirdparty/jquery/jquery.js');
		Requirements::block('sapphire/thirdparty/jquery-metadata/jquery.metadata.js');
		Requirements::block('sapphire/thirdparty/prototype/prototype.js');
		Requirements::block('sapphire/javascript/prototype_improvements.js');
		Requirements::block('sapphire/javascript/i18n.js');
		Requirements::block('sapphire/javascript/lang/en_US.js');
		Requirements::block('sapphire/javascript/Validator.js');

		// Block autosuggest's requirements
		Requirements::block('sapphire/thirdparty/jquery/jquery.js');
		Requirements::block('autosuggest/javascript/autosuggest.min.js','');

		Requirements::combine_files('combined.css', array(
			$themeFolder . '/css/reset.css',
			$themeFolder . '/css/grid.css',
			$themeFolder . '/css/layout.css',
			$themeFolder . '/css/typography.css',
			$themeFolder . '/css/form.css',
			$themeFolder . '/css/print.css'
		));

		Requirements::combine_files('combined.js', array(
			$themeFolder . '/js/jquery.js',
			$themeFolder . '/js/jquery-ui.js',
			$themeFolder . '/js/jquery.timer.js',
			$themeFolder . '/js/carousel.js',
			$themeFolder . '/js/placeholder.min.js',
			'emailthispage/javascript/fancybox/jquery.fancybox-1.3.4.js',
			'emailthispage/javascript/emailthispage.js',
			'autosuggest/javascript/autosuggest.js',
			'silentone/javascript/silentone_link.js',
			$themeFolder . '/js/default.js'
		));

		Requirements::process_combined_files();

		// Compress the combined css file on flush
		if (isset($this->request) && ($this->request->getVar('flush') == '1' || $this->request->getVar('flush') == 'all')) {
			$file = Director::baseFolder() . '/' . Requirements::backend()->getCombinedFilesFolder() . '/combined.css';
			if (file_exists($file) && is_writable($file)) {
				$bf = file_get_contents($file);
				$bf = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $bf);
				$bf = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $bf);
				$bf = str_replace(array('( ', ' )', '{ ', ' }', ' {', '} ', ', ', ': ', '; ', ' ;'), array('(', ')', '{', '}', '{', '}', ',', ':', ';', ';'), $bf);
				$fh = fopen($file, 'w');
				fwrite($fh, $bf);
				fclose($fh);
			}
		}

		//Display RSS link on every page with children. If no children display parent RSS link
		
		if($this->Title == "Home"){
			RSSFeed::linkToFeed($this->Link() . "rss");
		} elseif (DB::query("SELECT COUNT(*) FROM SiteTree_Live WHERE ParentID = '$this->ID'")->value()>0){
			RSSFeed::linkToFeed($this->Link() . "rss");
		} else {
			RSSFeed::linkToFeed($this->Parent()->Link() . "rss");
		}
	}
	
	function ShareLink(){
		$BaseURL = Director::absoluteBaseURL();
		$ShareLink = $BaseURL.substr($this->Link(),1);
		return urlencode($ShareLink);
	}
	
	function ShareTitle(){
		$SiteTitle = "Tasman District Council";
		$ShareTitle = $this->Title.' - '.$SiteTitle;
		return urlencode($ShareTitle);
	}
	
	public function numChildren()
	{
		$children = $this->AllChildren();
		return $children->Count();
	}
	
	function CMSLink() {
		if(Director::isDev() || Permission::check('CMS_ACCESS_CMSMain') || Permission::check('VIEW_DRAFT_CONTENT')) {
			return Director::absoluteBaseURL().'admin/show/'.$this->ID;
		}
	}

	/**
	 * If the CivilDefenceAlertMode is on in the SiteConfig, provide the newest entry
	 * from the Nelson-Tasman CD site. The entry is not checked, so it will how up even
	 * if it's not a CD warning.
	 *
	 * @returns DataObject first entry of the RSS feed
	 */
	function CivilDefenceAlert() {
		$config = SiteConfig::current_site_config();
		if (!$config->CivilDefenceAlertMode) return null;

		$RSSFeed = RestfulLinks('http://www.nelsontasmancivildefence.co.nz/news/rss', 'channel', 'item', 360);
		$RSSList = new DataObjectSet();
		
		if($RSSFeed){
			foreach($RSSFeed as $RSSItem){
				$Content = htmlspecialchars_decode($RSSItem->description);
				$ShortSummary =	wordwrap($Content, 200, ".", false);
				$DateTime = date("l j F Y g:ia",strtotime($RSSItem->pubDate));
				$AlertLevel = 1;

				$extrainfo = array(
					'RSSTitle' => $RSSItem->title,
					'RSSLink' => $RSSItem->link,
					'RSSDateTime' => $DateTime,
					'RSSDescription' => $Content,
					'RSSShortSummary' => $ShortSummary
				);
				$RSSList -> push(new ArrayData($extrainfo));
			}
		}
		return $RSSList->First();
	}
	
	function PageVersions(){
		$PageVersions = $this->AllVersions();
		$PageVersions -> sort('LastEdited', 'ASC');
		
//		debug::show(print_r($PageVersions)); 
//		die();		
		$VersionList = new DataObjectSet();
		
		$last_version		= 0;
		$latest_version		= 0;
		$last_displayed_version = 0;
		$content_changed	= 0;
		$last_day_edited	= '';
		$last_author_name	= '';
		$last_content_size	= '';
		$last_page_title	= '';
		$last_class_name	= '';
	
	if($PageVersions){
		foreach($PageVersions as $VersionItem){
			$latest_version = ($VersionItem->Version > $latest_version) ? $VersionItem->Version : $latest_version;
		}
		
		foreach($PageVersions as $VersionItem){
	
			$display_item = 0;
			
			$day_edited		= substr($VersionItem->LastEdited,0,10);
			$all_content	= $VersionItem->TopContent.$VersionItem->Content.$VersionItem->MetaDescription.$VersionItem->MetaKeywords;
			$content_size	= strlen($all_content);
			$word_count		= str_word_count(strip_tags($VersionItem->TopContent.$VersionItem->Content));
			$link_count		= substr_count($VersionItem->TopContent.$VersionItem->Content, 'href=');
			$page_title		= $VersionItem->Title;
			$class_name		= $VersionItem->ClassName;
			$this_version	= $VersionItem->Version;
			
			
			$member			= DataObject::get_one('Member', "Member.ID = {$VersionItem->AuthorID}");			
			$author_name	= ($member) ? $member->getName() : '';
			
			$content_changed = $content_changed + ($content_size - $last_content_size);
			$show_page_title = ($last_page_title != $page_title) ? $page_title : '&nbsp;';
			$show_class_name = ($last_class_name != $class_name) ? $class_name : '&nbsp;';
			
			$extrainfo = array(
				'AuthorName' => $author_name,
				'DayEdited' => $day_edited,
				'LinkCount' => $link_count,
				'WordCount' => $word_count,
				'ContentChanged' => $content_changed,
				'Title' => $show_page_title,
				'Version' => $this_version,
				'LastVersion' => $last_displayed_version,
				'ClassName' => $show_class_name, 
				'CompareNewLink' => '/admin/compareversions/'.$this->ID.'/?From='.$this_version.'&amp;To='.$latest_version,
				'CompareLink' => '/admin/compareversions/'.$this->ID.'/?From='.$last_version.'&amp;To='.$this_version
				);
		
			
			if ($author_name != $last_author_name || $day_edited != $last_day_edited || ($last_version != $this_version && ($content_changed != 0 || $page_title != $last_page_title || $class_name != $last_class_name))){
				$display_item = 1;
				$content_changed = 0;
				$last_displayed_version = $this_version;
			} 
			
			$last_class_name	= $class_name;
			$last_content_size	= $content_size;
			$last_author_name	= $author_name;
			$last_day_edited	= $day_edited;
			$last_page_title	= $page_title;
			$last_version		= $this_version;
								
			if($display_item){
				$VersionList -> push(new ArrayData($extrainfo));
			}	
			
		}
	
	}			
		$VersionList -> sort('Version', 'DESC');
	
//		debug::show(print_r($VersionList)); 		
		// Limit the number of events returned;
		return $VersionList -> getRange(0, 20);
	
	}
	
	function getIsStage() {
		return (Versioned::current_stage() == 'Stage');
	}

	function TopParent(){
		$NestedTitle = $this->NestedTitle(99);
		$words = explode(' ',$NestedTitle);
		return $words[0];
	}

	function SearchForm() {
		$searchText = '';

		if($this->owner->request) {
			$searchText = $this->owner->request->getVar('Search');
		}
	
		$fields = new FieldSet(
			new LiteralField('Search', '<div class="field text  nolabel" id="Search"><div class="middleColumn"><input type="text" value="" name="Search" id="SearchForm_SearchForm_Search" class="text nolabel" accesskey="3"></div></div>')
		);
		$actions = new FieldSet(
			new FormAction('results', 'Search')
		);
		
		return new SilentOneSearchForm($this->owner, 'SearchForm', $fields, $actions);
	}

	function results($data, $form, $request) {
		$mode = Convert::raw2xml($this->request->getVar('mode'));
		$query = (isset($data['Search'])) ? Convert::raw2att($data['Search']) : "";
		
		$data = array(
			'Query' => $form->getSearchQuery(),
			'Title' => 'Search Results',
			'Link' => $this->Link()."SearchForm?Search={$query}&action_results=Search",
			'IsSearchResultsPage' => true
		);

		if (!$mode) {
			// No paging, limited to 5 results with link to "more"
			$data['Results'] = $this->shortcodeParseResults($form->getResults()->getRange(0, 5));
			$data['Results']->setPageLimits(0, 5, 5);
			$data['SilentOneResults'] = $form->getSilentOneResults()->getRange(0, 5);
			$data['SilentOneResults']->setPageLimits(0, 5, 5);
		}
		else if ($mode=='pages') {
			$data['Results'] = $this->shortcodeParseResults($form->getResults());
		}
		else if ($mode=='documents') {
			$data['SilentOneResults'] = $form->getSilentOneResults();
		}

		return $this->owner->customise($data)->renderWith(array('Page_results'.$mode, 'Page'));
	}

	/**
	 * Runs all the content fields of the result data through the shortcode parser, so [sitetree] shortcode links
	 * get transformed to actual href links
	 * @param $results
	 * @return DataObjectSet
	 */
	function shortcodeParseResults($results) {
		$parser = ShortcodeParser::get_active();
		foreach($results as $result) {
			if (!empty($result->Content)) {
				$limitedContent = $this->LimitWordCountNoEscaping($result->Content, 35);
				$parsedContent = $parser->parse($limitedContent);
				$result->Content = $parsedContent;
			}
		}

		return $results;
	}

	/**
	 * Limits the number of words in a string and does not XML escape links in the string
	 * @param $value
	 * @param int $numWords
	 * @param string $add
	 * @return array|string
	 */
	function LimitWordCountNoEscaping($value, $numWords = 40, $add = '...') {
		$value = trim($value);
		$ret = explode(' ', $value, $numWords + 1);

		if(count($ret) <= $numWords - 1) {
			$ret = $value;
		} else {
			array_pop($ret);
			$ret = implode(' ', $ret) . $add;
		}

		//remove any HTML tags except links
		$ret = preg_replace('/<[^a\/].*?>/','',$ret);
		$ret = preg_replace('/<\/[^a].*?>/','',$ret);

		//add a space between links that don't have a space
		$ret = preg_replace('/(<\/a.*?>)\s*(<a.*?>)/','\1&nbsp;\2',$ret);

		//close the final link, if necessary
		$lastOpenA = strrpos($ret,'<a');
		$lastCloseA = strrpos($ret,'</a');
		if ($lastOpenA !== false) { //we have an "a" tag in the string
			if ($lastCloseA === false || $lastOpenA > $lastCloseA) {    //no closing tag, or closing tag before opening tag
				$ret = substr($ret,0,$lastOpenA) . "...";   //cut off last bit and re-add ...
			}
		}

		return $ret;
	}
	
	function searchText(){
		$searchText  = isset($_GET['Search']) ? $_GET['Search'] : '';
		
		return strtolower(Convert::raw2att($searchText));
	}

	function rss() {
		$pagetype = $this->ClassName;
		$num = 25;

		switch($pagetype){
			case "NewsListPage":
				$GetNews = DataObject::get("NewsPage", "ParentID = $this->ID", "DisplayDate DESC, Sort ASC", "", $num);
				$rssTitle = $this->Title." RSS Feed -  Tasman District Council";
				break;

			case "NoticeListPage":
				$GetNews = DataObject::get("NoticePage", "ParentID = $this->ID AND PublishNotice = 1", "DisplayDate DESC, Sort ASC", "", $num);
				$rssTitle = $this->Title." RSS Feed -  Tasman District Council";
				break;

			case "HomePage":
				$GetNews = DataObject::get("NewsPage", "ParentID = 116", "DisplayDate DESC, Sort ASC", "", $num);
				$GetNotices = DataObject::get("NoticePage", "ParentID = 18 AND PublishNotice = 1", "DisplayDate DESC, Sort ASC", "", $num);
				$GetNews->merge($GetNotices);

				$GetNews->sort("DisplayDate","DESC");
				$rssTitle = "News + Notices - Tasman District Council - RSS Feed";
				break;
			
			default:
				$GetNews = $this->Children();
				$rssTitle = $this->Title." RSS Feed -  Tasman District Council";
		}

		//display the LastEdited date of the NewsPage instead of the Created date in the RSS feed
		$outputNews = new DataObjectSet();
		foreach($GetNews as $n) {
			$n->Created = $n->LastEdited;
			$outputNews->push($n);
		}

		$rss = new RSSFeed($outputNews, $this->Link(),$rssTitle);
		$rss->outputToBrowser();
	}


}

function RestfulLinks($url, $collection, $element, $interval, $num=NULL){
	// Accepts an RSS feed URL and outputs a list of links from it
	$rssfeed = new RestfulService($url,$interval);
	$conn = $rssfeed->request('')->getBody();
	$result = $rssfeed->getValues($conn, $collection, $element, $num);
	return $result;
}

function GetEndDateFromBracketedString($input_string){
			preg_match("/\((.*)\)/i", $input_string, $date_string);
			
			// Check that extraction worked - if not use last day of the century
			$date_match = (count($date_string)>0) ? $date_string[0] : "(Thursday 31st December 2100)";
			// Strip brackets from date
			$brackets = array("(",")");
			$replacements = array("","");
			$date_match = str_replace($brackets, $replacements, $date_match);
			
			// Split date into parts using space as the delimiter
			$date_parts = explode(" ", $date_match);
			
			// Reverses date parts array as end date is what we're after
			$date_parts = array_reverse($date_parts);
			
			// Convert end date to UNIX timestamp			
			$end_date = strtotime($date_parts[2]." ".$date_parts[1]." ".$date_parts[0]);
			
			return $end_date;
}

class SearchCustomSummary extends TextParser
{
	function parse(){
//		debug::show($this); 
		$content = $this->content;
		$paragraphs = array();
		preg_match("/<p>(.+?)<\/p>/is", $content, $paragraphs);
		
		$returnparse = ($paragraphs) ? $paragraphs[0] : '';
		
		return $returnparse;
	}
}
