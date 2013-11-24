<?php

class PageAutoSuggestExtension extends DataObjectDecorator {
	function contentcontrollerInit() {
		Requirements::javascript('sapphire/thirdparty/jquery/jquery.js');
		Requirements::javascript('autosuggest/javascript/autosuggest.min.js','');
		Requirements::css('autosuggest/css/autosuggest.css');
		Requirements::includeInHTML('AutoSuggestSearchForm', 'AutoSuggestSearchForm', 'autosuggest/templates/Includes');
	}
}

?>