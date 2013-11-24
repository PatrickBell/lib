<?php
/**
 * Adds library-specific functionality to the homepage
 */

class HomePageDecorator extends DataObjectDecorator {
	function extraStatics() {
		return array(
			'has_one' => array(
				'Featured1' => 'LibraryListingPage',
				'Featured2' => 'LibraryListingPage',
				'Featured3' => 'LibraryListingPage',
				'Featured4' => 'LibraryListingPage'
			),
		);
	}

	function updateCMSFields(&$fields) {
		$listings = DataObject::get('LibraryListingPage', '', 'Title DESC');
		$listingMap = $listings ? $listings->toDropdownMap() : array();
		if($listings){
			$fields->addFieldToTab('Root.Content.Library', new DropdownField('Featured1ID', 'Featured block #1', $listings->toDropdownMap(), $this->owner->Featured1ID, null, '-- empty --'));
			$fields->addFieldToTab('Root.Content.Library', new DropdownField('Featured2ID', 'Featured block #2', $listings->toDropdownMap(), $this->owner->Featured2ID, null, '-- empty --'));
			$fields->addFieldToTab('Root.Content.Library', new DropdownField('Featured3ID', 'Featured block #3', $listings->toDropdownMap(), $this->owner->Featured3ID, null, '-- empty --'));
			$fields->addFieldToTab('Root.Content.Library', new DropdownField('Featured4ID', 'Featured block #4', $listings->toDropdownMap(), $this->owner->Featured4ID, null, '-- empty --'));
		}
	}

	/**
	 * Get a random item from underneath the LibraryListingPage associated with requested slot.
	 * Gets items only from directly underneath.
	 */
	function getFeatured() {
		$featured = new DataObjectSet();
		foreach (array(1,2,3,4) as $slot) {
			$item = DataObject::get_one('LibraryItemPage', "\"ParentID\"='".$this->owner->{"Featured".$slot."ID"}."'", '', 'RAND()'); 
			if ($item) $featured->push($item);
		}
		return $featured;
	}
}
