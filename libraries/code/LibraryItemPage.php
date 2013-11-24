<?php
/**
 * Atomic data for the library items that will be featured on the website. Doubles as a detail page.
 */

class LibraryItemPage extends Page {
	static $db = array(
		'ItemTitle' => 'Varchar(1024)',
		'Author' => 'Varchar(256)',
		'AccessionNo' => 'Varchar(256)',
		'URLOverride' => 'Varchar(1024)',
		'URLOverrideComplex' => 'Varchar(1024)'
	);

	static $defaults = array(
		'ShowInMenus' => false
	);

	static $has_one = array(
		'CoverImage' => 'Image'
	);

	function getCMSFields() {
		$fields = parent::getCMSFields();

		$tab = $fields->fieldByName('Root.Content.Main');
		$title = $tab->fieldByName('Title')->performDisabledTransformation();
		$menuTitle = $tab->fieldByName('MenuTitle')->performDisabledTransformation();
		$tab->replaceField('Title', $title);
		$tab->replaceField('MenuTitle', $menuTitle);

		$fields->addFieldToTab('Root.Content.Main', new TextField('ItemTitle', 'Title', 1024), 'Content');
		$fields->addFieldToTab('Root.Content.Main', new TextField('Author', 'Author', 256), 'Content');

		//$fields->addFieldToTab('Root.Content.Main', new TextField('AccessionNo', 'Accession number (leave blank to link to the item page on this site)', 256), 'Content');
		$fields->addFieldToTab('Root.Content.Main', new TextField('URLOverride', 'URL override (leave blank to use ItemTitle)', 1024), 'Content');
		$fields->addFieldToTab('Root.Content.Main', new TextField('URLOverrideComplex', 'URL override complex (use if search is complex)', 1024), 'Content');

		$fields->addFieldToTab('Root.Content.Main', new ImageField('CoverImage', 'Cover image'), 'Content');

		return $fields;
	}

	/**
	 * Keep the page title in sync with the automatically assembled one.
	 */
	function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->Title = $this->getTitle();
	}

	function getTitle() {
		if ($this->ItemTitle && $this->Author) return $this->ItemTitle." by ".$this->Author;
		else if ($this->ItemTitle) return $this->getField('ItemTitle');
		else return $this->getField('Title');
	}

	function getMenuTitle() {
		if ($this->ItemTitle) return $this->getField('ItemTitle');
		else return $this->getField('Title');
	}

	function getURLSegment() {
		if ($this->Tnumber) return $this->getField('ItemTitle');
		else return $this->getField('URLSegment');
	}

	/**
	 * Find related books by author by trying to match the author name (case-insensitive).
	 */
	function getItemsByThisAuthor() {
		if ($this->Author) {
			return DataObject::get('LibraryItemPage', "\"LibraryItemPage\".\"ID\"<>$this->ID AND \"Author\" LIKE '$this->%27Author%27'");
		}
	}

	/**
	 * Provide link to the external system for requesting the item.
	 */
/* 	function getRequestLink() {
		if ($this->Tnumber) {
			return "http://daklms01.kotui.org.nz/uhtbin/cgisirsi/x/x/0/57/108?user_id=TSWEB&library=TS_SEARCH&item_id=$this->Tnumber";
		}
	} */

	/**
	 * Provide a link to the item in the catalogue.
	 */
	function getCatalogueLink() {
		if ($this->URLOverrideComplex) {
			return ($this->URLOverrideComplex);
			} elseif ($this->URLOverride) {
			return "http://ent.kotui.org.nz/client/tasman/search/results?qu=%22$this->URLOverride%22";
		} elseif ($this->ItemTitle) {
			return "http://ent.kotui.org.nz/client/tasman/search/results?qu=%22$this->ItemTitle%22";
		} else {
			return $this->Link();
		}
	}
	
	
}

class LibraryItemPage_Controller extends Page_Controller {

}
