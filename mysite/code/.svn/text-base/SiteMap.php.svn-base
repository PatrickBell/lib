<?php
 
class SiteMap extends Page {
	static $db = array(
		);
	static $has_one = array(
		);
}

class SiteMap_Controller extends Page_Controller {
	
	
	function SiteMapDepthControl(){
		$link = $this->Link();
		
		$output = "";
		$depth  = isset($_GET['depth']) ? (int) $_GET['depth'] : 3;
		$level_display = ($depth>1) ? "levels" : "level";
		$output .= '<p>Displaying website tree to '.$depth.' '.$level_display.' - ';
		if($depth>1){
			$output .= '&laquo; <a href="'. $link .'?depth='.($depth-1).'">Show Fewer Levels</a> - ';
		}
		if($depth<10){
			$output .= '<a href="'. $link .'?depth='.($depth+1).'">Show More Levels</a> &raquo;';
		}
		$output .= '</p>';
		return $output;
	}
	
	/**
	 * This function will return a unordered list of all pages on the site.
	 * Watch for the switch between $page and $child in the second line of the foreach().
	 * 
	 * Note that this will only skip ErrorPage's at the top/root level of the site. 
	 * If you have an ErrorPage class somewhere else in the hierarchy, it will be displayed.
	 */
	function SiteMap() {
		$rootLevel = DataObject::get("Page", "ParentID = 0"); // Pages at the root level only
		$max_depth  = isset($_GET['depth']) ? (int) $_GET['depth'] : 3;
		$output = "";
		$output = $this->makeList($rootLevel, 0, $max_depth);
		return $output;
	}
	
	private function makeList($pages, $depth, $max_depth=3) {
		$output = "";
		if(count($pages)) {
			$output = '
    <ol class="sitemap-list">';
			$depth++;
			foreach($pages as $page) {
				if(!($page instanceof ErrorPage) && $page->ShowInMenus && $page->Title != $this->Title){
					$output .= '
      <li><a href="'.$page->RelativeLink().'" title="Go to the '.Convert::raw2xml($page->Title).' page">'.Convert::raw2xml($page->MenuTitle).'</a>';
					if($depth<$max_depth) {
						$whereStatement = "ParentID = ".$page->ID;
						//$childPages = new DataObjectSet();
						$childPages = DataObject::get("Page", $whereStatement);
						$output .= $this->makeList($childPages, $depth, $max_depth);
					}
					$output .= '
      </li>';
				}
			}
			$output .= '
    </ol>';
		}
		return $output;
	}
}

?>