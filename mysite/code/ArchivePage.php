<?php

class ArchivePage extends Page {

	static $db = array(
			
	);

	static $has_one = array(
		'ArchiveParent' => 'SiteTree',
	);

	function getCMSFields(){
		$tree = new TreeDropdownField('ArchiveParentID', 'Choose a Parent page to display an archive of the Child pages', 'SiteTree');
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Content.Main', $tree, 'Content');
		
		return $fields;
	}

}

class ArchivePage_Controller extends Page_Controller {
	
	function GenerateArchive(){
		$Archive = new DataObjectSet();
		$LastArchiveMonth = "";

		//Get PageType from Archive Parent so dates/ page types can be handled correctly.
		//the query below losts the staging status, so not safe.
		//$pagetype = DB::query("SELECT ClassName FROM SiteTree WHERE ID = '$this->ArchiveParentID'")->value();
		
		$archive = DataObject::get_by_id("Page", $this->ArchiveParentID);
		if($archive->exists()) {
			$pagetype = $archive->ClassName;
			$num = 1000;
			switch($pagetype){
				case "NewsListPage":
					$GetArchive=DataObject::get("NewsPage", "ParentID = $this->ArchiveParentID", "DisplayDate DESC", "", $num);
					break;
				case "NoticeListPage":
					$GetArchive=DataObject::get("NoticePage", "ParentID = $this->ArchiveParentID", "DisplayDate DESC", "", $num);
					break;
				case "ArchivePage"://a child page, self contained.
					$parent = $this->Parent();
					if($parent->exists()){
						$parentArchive = DataObject::get_by_id("Page", $parent->ArchiveParentID);
						if($parentArchive->exists()){
							$parentArchiveType = $parentArchive->ClassName;
							switch($parentArchiveType) {
								case "NewsListPage":
									$GetArchive=DataObject::get("NewsPage", "ParentID = $this->ArchiveParentID", "DisplayDate DESC", "", $num);
									break;
								case "NoticeListPage":
									$GetArchive=DataObject::get("NoticePage", "ParentID = $this->ArchiveParentID", "DisplayDate DESC", "", $num);
									break;
							}
						}
					}
					break;

				default:
					$GetArchive=DataObject::get("Page", "ParentID = $this->ArchiveParentID", "LastEdited DESC", "", $num);
					break;
			}
			if($GetArchive){
				foreach($GetArchive as $ArchiveItem){

					// If an archive of News Pages use the DisplayDate
					switch($pagetype){
						case "NewsListPage":
						case "NoticeListPage":
						case "ArchivePage":
							$ArchiveMonth = date("F Y",strtotime($ArchiveItem->DisplayDate));
							break;
						default:
							$ArchiveMonth = date("F Y",strtotime($ArchiveItem->LastEdited));
							break;
					}

					$extrainfo = array(
						'ArchiveTitle' => $ArchiveItem->Title,
						'ArchiveLink' => $ArchiveItem->Link(),
		// Control display of Month, NewMonth flag will display current Month in front of item.				
						'ArchiveMonth' => $ArchiveMonth,
						'NewMonth' => ($LastArchiveMonth != $ArchiveMonth) ? 1 : 0
					);
					$LastArchiveMonth = $ArchiveMonth;
					$Archive -> push(new ArrayData($extrainfo));
				}
			}
			return $Archive;
		}
	}
}
?>