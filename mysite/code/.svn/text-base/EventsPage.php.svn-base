<?php

class EventsPage extends Page {

}

class EventsPage_Controller extends Page_Controller {
	public static $rss_feed = 'http://itson.co.nz/feeds/user/tdclibraries/';

	function EventList($num=25) {
		try{
			$EventList = RestfulLinks(self::$rss_feed, 'entry', '', 3600);

			$count=0;
			if($EventList){
				foreach($EventList as $EventItem){

					//Add linebreak to first open bracket on title
					$event_title = str_replace("(", "<br/>(", $EventItem->title);

					//Decode event summary
					$summary = htmlspecialchars_decode($EventItem->summary);

					//Extract first paragraph from event summary
					preg_match("/<p>(.*)<\/p>/", $summary, $paragraphs);
					$first_paragraph = (count($paragraphs)>1) ? $paragraphs[1] : $paragraphs; 

					//Add target='_blank' to links
					$first_paragraph = str_replace('<a href', '<a target="_blank" href', $first_paragraph);

					//Extract and convert end date from $event_title
					$end_date = GetEndDateFromBracketedString($event_title);

					$EventItem->EventTitle = $event_title;
					$EventItem->NoBreakEventTitle = $EventItem->title;
					$EventItem->EventLink = $EventItem->id;
					$EventItem->ShortSummary = $first_paragraph;
					$EventItem->OrderID = $count;
					$EventItem->EndDate = $end_date;

					++$count;
				}

				// Reverse order so near events show first
				$EventList->sort("EndDate","ASC");
			}			
			// Limit the number of events returned;
			return $EventList->getRange(0, $num );
		}catch(Exception $exc){
			return;
		}
	}
}
