<?php

class AtoZPage extends Page {

	static $db = array(
		
		);

	static $has_one = array(
	
		);

}

class AtoZPage_Controller extends Page_Controller {
	
	function GenerateAtoZ(){
		$AtoZ = new DataObjectSet();
		$LastAtoZLetter = "";
		
//Variables for caching for autosuggest in search box
		$page_array = '$site_pages = array(';
		$keywords_array = '$site_keywords = array(';
		$url_array = '$site_urls = array(';
		$cache_filename = dirname(__FILE__).'/../../autosuggest/cache/autosuggest_list.php';
		$cache_filetime = (!file_exists($cache_filename)) ? 0 : filemtime($cache_filename);

//Cache if autocomplete_list is older than 6 hours and version is Live		
		$cache = ($cache_filetime < (time()-3600) && Versioned::current_stage() == 'Live') ? 1 : 0;

		$GetAtoZ = DataObject::get("Page", "Title!='' AND ShowInSearch=1 AND ClassName!='LibraryItemPage'", "Title ASC");
		if (!$GetAtoZ) return;

		// We need to sort in PHP case insensitive. LibraryItemPages build titles dynamically, so no SQL is possible.
		// This could be removed after all the library pages have been resaved, which will set the Titles in the DB now.
		// You can check if you can do it yet with:
		// 		select count(ID) from SiteTree where ClassName='LibraryItemPage' and Title like 'New Library%';
		foreach ($GetAtoZ as $page) {
			$page->SortTitle = strtoupper($page->Title);
		}
		$GetAtoZ->sort('SortTitle', 'ASC');
			
		if($GetAtoZ){
			foreach($GetAtoZ as $AtoZItem){

				$AtoZLetter = strtoupper(substr($AtoZItem->Title,0,1));
				$AtoZLetter = (is_numeric($AtoZLetter)) ? "0 - 9" : $AtoZLetter;
				
				$extrainfo = array(
					'AtoZTitle' => $AtoZItem->Title,
					'AtoZLink' => $AtoZItem->Link(),
// Control letter grouping. If first letter is different from last item then activate NewLetter flag
					'AtoZLetter' => $AtoZLetter,
					'NewLetter' => ($LastAtoZLetter != $AtoZLetter) ? 1 : 0
					);
				$LastAtoZLetter = $AtoZLetter;
				$AtoZ -> push(new ArrayData($extrainfo));
		
//If set to cache format values into plain text array		
				if($cache == 1){
					if($AtoZItem->Title != ''){
						$page_array			.= $AtoZItem->ID.' => "'.$AtoZItem->Title.'", ';
						$maori_keywords		= (!preg_match('/\m(.{1,4})ori/i',$AtoZItem->Title)) ? '' : 'maori, ';
					}
					if($AtoZItem->MetaKeywords != '' || $maori_keywords != ''){
						$keywords_array		.= $AtoZItem->ID.' => "'.$maori_keywords.$AtoZItem->MetaKeywords.'", ';
					}

					if($AtoZItem->Link() != ''){
						$url_array			.= $AtoZItem->ID.' => "'.$AtoZItem->Link().'", ';
					}

				}
			}
		}
		
//If set to cache write arrays to file;
		if($cache ==1){
			$file_contents	= "<?php\r\n".$page_array.");\r\n".$keywords_array.");\r\n".$url_array.");\r\n?>";		
			
			$cache_file = fopen($cache_filename, 'w');
			fwrite($cache_file, $file_contents);
			fclose($cache_file);
		}
		
		return $AtoZ;
	}


//Create top A to Z index linking to anchors on full page.
	function AtoZIndex(){
		$output = "";
		$letters = str_split("ABCDEFGHIJKLMNOPRSTUVWXYZ",1);
		$link = $this->Link();
		
		foreach($letters as $letter){
			$output .= (!$output) ? "" : " | ";
			$output .= '<a href="'.$link.'#'.$letter.'" accesskey="'.$letter.'">'.$letter.'</a>';
		}
		
		return $output;
	}
	
	
}
?>
