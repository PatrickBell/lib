<?php

class GeotaggingPageDecorator extends DataObjectDecorator {
    function extraStatics() {
        return array(
            'db' => array(
				'Lat' => 'Varchar',	// -90 to 90
				'Long' => 'Varchar' // -180 to 180
			)
        );
    }

    function updateCMSFields(FieldSet &$fields) {
		$fields->addFieldToTab('Root.Content.Geotag', new TextField('Lat','Latitude (North - South)'));
		$fields->addFieldToTab('Root.Content.Geotag', new TextField('Long','Longtitude (West - East)'));
		$fields->addFieldToTab('Root.Content.Geotag', new LabelField('Geotagmap', '<a href="http://itouchmap.com/latlong.html" target="_geotagfrommap">Get the Latitude/Longtitude of an address or map point</a>', 'geotagmap',true));
    }

	/**
	 * Get the text to fit into the google map info window
	 */
	function Info() {
		return "<b>".$this->owner->getTitle()."</b><br/><br/><a href='".$this->owner->Link()."'>Click here to view the details</a>";
	}
}