<?php
/**
 * Google Map aggregating page. 
 * Scans all the children for their Geotags and displays them as icons on a map.
 */

class GoogleMapPage extends Page {
	static $db = array(
		'Legend'=>'HTMLText',
		'Description'=>'HTMLText',
		'TopContent' => 'HTMLText',
		'MapZoom' => 'Int',
		'DefaultIcon' => 'Varchar'
	);
	
	/**
	 * Access to google maps api, has to be generated via http://code.google.com/apis/maps/signup.html per domain
	 */
	static public $gmaps_api_key;
	
	/**
	 * Cache the GeotaggedChildren call
	 */
	private $__cache;
	
	/**
	 * Recursive walk, potential bottleneck.
	 *
	 * @param $parent next node to scan
	 * @param $list resulting list of lat+long+link
	 * @param $level the depth we are at
	 */
	function walkTheChildren($parent, &$set, $level) {
		foreach ($parent->Children() as $child) {
			if ($child->Lat && $child->Long) {
				// Get the page-specific icon, or fall back to default
				$child->ComputedIcon = null;
				if ($child->hasMethod('Icon')) {
					$child->ComputedIcon = $child->Icon();
				}
				if (!$child->ComputedIcon) {
					if ($this->DefaultIcon && file_exists(BASE_PATH.'/'.$this->DefaultIcon)) {
						$child->ComputedIcon = Director::baseURL().$this->DefaultIcon;
					}
				}

				$set->push($child);
			}
			
			// Put a cap on depth in case it gets out of control
			if ($level<25)
				$this->walkTheChildren($child, $set, $level+1);
		}
	}
	
	/**
	 * Cached accessor to self::walkTheChildren
	 */
	function GeotaggedChildren() {
		if (!isset($this->__cache)) {
			$this->__cache = new DataObjectSet;
			$this->walkTheChildren($this, $this->__cache, 0);
			$this->__cache->sort('Title', 'ASC');
		}
		return $this->__cache;
	}
	
	function getCMSFields() {
		$fields = parent::getCMSFields();		
		
		$fields->addFieldToTab('Root.Content.Main', new HtmlEditorField('TopContent','Top Content', '10'), 'Content');
		$fields->renameField('Content', 'Bottom Content');
		
		$fields->addFieldToTab('Root.Content.Legend', new HtmlEditorField('Legend','Map Key (right field)', '15'));
		$fields->addFieldToTab('Root.Content.Legend', new HtmlEditorField('Description','Description (left field)', '15'), 'Legend');
		$fields->addFieldToTab("Root.Content.Legend", new NumericField('MapZoom','Google Map Zoom - 0 = automatic. Higher is closer, 8 = show entire Tasman District'),'Description');

		$icons = array(''=>'Google default (red)', "geotagging/images/icons/Gray.png"=>'Gray');
		$fields->addFieldToTab('Root.Content.Main', new DropdownField('DefaultIcon','Default marker', $icons));
		return $fields;
	}
}

class GoogleMapPage_Controller extends Page_Controller {	
	function init() {
		parent::init();
		Requirements::javascript('http://www.google.com/jsapi?key='.GoogleMapPage::$gmaps_api_key);
		
		$list = array();
		$children = $this->GeotaggedChildren();
		if ($children) {
			foreach ($children as $child) {
				array_push($list, array(
					'lat'=>$child->Lat, 
					'long'=>$child->Long,
					'info'=>$child->Info(),
					'icon'=>$child->ComputedIcon
				));
			}
		}
		
		// Push the data out to JS via the template
		Requirements::javascriptTemplate('geotagging/javascript/google_maps_page.js', array(
			'GeotaggedChildren'=>json_encode($list),
			'MapZoom'=>$this->MapZoom
		));
	}
}
