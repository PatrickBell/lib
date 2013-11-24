<?php

/**
 * Show all publishing activity across the site
 *
 * @package cmsworkflow
 * @subpackage reports
 */
 
 class UnpublishedPages extends SS_Report {
	function title() {
		return 'Unpublished Pages';
	}
	
	function columns() {
		$fields = array(
			'Title' => array(
				"title" => "Page name",
				'formatting' => '<a href=\"admin/show/$ID\" title=\"Edit page\">$value</a>'
			),
			'ClassName' => 'Page type',
			'Status' => 'Recorded Status',
			'LastEdited' => array(
				'title' => 'Last edited',
				'casting' => 'SS_Datetime->Full'
			),
		);
		return $fields;
	}
	
	function sourceRecords($params, $sort, $limit) {
		$q = singleton('SiteTree')->extendedSQL();
		$q->leftJoin('SiteTree_Live', '"SiteTree"."ID" = "SiteTree_Live"."ID"');
		$q->where[] = "\"SiteTree_Live\".\"ID\" IS NULL";
		
		// Default sort to Date published descending
		$sort = (!$sort) ? '"SiteTree"."LastEdited" DESC' : $sort;
		$q->orderby = $sort;
		
		$records = singleton('SiteTree')->buildDataObjectSet($q->execute(), 'DataObjectSet', $q);

		// Apply limit after that filtering.
		if($limit && $records) return $records->getRange($limit['start'], $limit['limit']);
		else return $records;
		
	}
}
 
class UserActivity extends SS_Report {
	function title() {
		return 'User Activity';
	}
	
	function columns() {
		$fields = array(
			'PageTitle' => array(
				"title" => "Page name",
				'formatting' => '<a href=\"admin/show/$ID\" title=\"Edit page\">$value</a>'
			),
			'Published' => array(
				'title' => 'Published',
				'casting' => 'SS_Datetime->Full'
			),
			'AuthorTitle' => 'Author',
			'PublisherTitle' => 'Publisher',
			'Status' => 'Status',
			'AbsoluteLink' => array(
				'title' => 'Edit',
				'formatting' => '" . ($AbsoluteLiveLink ? "<a target=\"_blank\" href=\"$AbsoluteLiveLink\">(live)</a>&nbsp;" : "") . "<a target=\"_blank\" href=\"$value?stage=Stage\">(draft)</a>'
			),
			'ExpiryDate' => array(
				'title' => 'Expiry on?',
				'formatting' => '" . ($value && $value != "0000-00-00 00:00:00" ? date("j M Y g:ia", strtotime($value)) : "no") . "',
				'csvFormatting' => '" . ($value && $value != "0000-00-00 00:00:00" ? date("j M Y g:ia", strtotime($value)) : "no") . "'
			)
		);
		return $fields;
	}
	
	function sourceRecords($params, $sort, $limit) {
		$q = singleton('SiteTree')->extendedSQL();
		$q->select[] = '"SiteTree"."Title" AS "PageTitle"';
	
		$q->leftJoin('WorkflowRequest', '"WorkflowRequest"."PageID" = "SiteTree"."ID"');
		$q->select[] = "\"WorkflowRequest\".\"LastEdited\" AS \"Published\"";
		$q->where[] = "\"WorkflowRequest\".\"ClassName\" = 'WorkflowPublicationRequest'";
		$q->where[] = "\"WorkflowRequest\".\"Status\" = 'Completed'";
		
		
		$q->leftJoin('Member', '"WorkflowRequest"."PublisherID" = "Member"."ID"');
		$q->select[] = Member::get_title_sql().' AS "PublisherTitle"';
//		$q->renameTable('Member', 'Publisher');

//		$q->leftJoin('Member', '"WorkflowRequest"."AuthorID" = "Member"."ID"', 'Author');
//		$q->select[] = Member::get_title_sql().' AS "AuthorTitle"';
//		$q->renameTable('Member', 'Author');
		
		// restrict to member id
		if (!empty($params['memberId']) && DataObject::get_by_id('Member', $params['memberId'])) {
//			$q->where[] = "\"Publisher\".\"ID\" = ".Convert::raw2sql($params['memberId'])." OR \"Author\".\"ID\" = ".Convert::raw2sql($params['memberId']);
			$q->where[] = "\"Member\".\"ID\" = ".Convert::raw2sql($params['memberId']);
		}
		
		$startDate = isset($params['StartDate']) ? $params['StartDate'] : null;
		$endDate = isset($params['EndDate']) ? $params['EndDate'] : null;
				
		if($startDate) {
			if(count(explode('/', $startDate['Date'])) == 3) {
				list($d, $m, $y) = explode('/', $startDate['Date']);
				$startDate['Time'] = $startDate['Time'] ? $startDate['Time'] : '00:00:00';
				$startDate = @date('Y-m-d H:i:s', strtotime("$y-$m-$d {$startDate['Time']}"));
			} else {
				$startDate = null;
			}
		}
		
		if($endDate) {
			if(count(explode('/', $endDate['Date'])) == 3) {
				list($d,$m,$y) = explode('/', $endDate['Date']);
				$endDate['Time'] = $endDate['Time'] ? $endDate['Time'] : '23:59:59';
				$endDate = @date('Y-m-d H:i:s', strtotime("$y-$m-$d {$endDate['Time']}"));
			} else {
				$endDate = null;
			}
		}
		
		if ($startDate && $endDate) {
			$q->where[] = "\"WorkflowRequest\".\"LastEdited\" >= '".Convert::raw2sql($startDate)."' AND \"WorkflowRequest\".\"LastEdited\" <= '".Convert::raw2sql($endDate)."'";
		} else if ($startDate && !$endDate) {
			$q->where[] = "\"WorkflowRequest\".\"LastEdited\" >= '".Convert::raw2sql($startDate)."'";
		} else if (!$startDate && $endDate) {
			$q->where[] = "\"WorkflowRequest\".\"LastEdited\" <= '".Convert::raw2sql($endDate)."'";
		} else {
			$q->where[] = "\"WorkflowRequest\".\"LastEdited\" >= '".SS_Datetime::now()->URLDate()."'";
		}
		
		// Default sort to Date published descending
		$sort = (!$sort) ? 'Published DESC' : $sort;
		
		// Turn a query into records
		if($sort) {
			$parts = explode(' ', $sort);
			$field = $parts[0];
			$direction = $parts[1];
			
			if($field == 'AbsoluteLink') {
				$sort = '"URLSegment" ' . $direction;
			} elseif($field == '"Subsite"."Title"') {
				$q->from[] = 'LEFT JOIN "Subsite" ON "Subsite"."ID" = "SiteTree"."SubsiteID"';
			}
		
			$q->orderby = $sort;
		}
//		debug::show(print_r($q)); 
		$records = singleton('SiteTree')->buildDataObjectSet($q->execute(), 'DataObjectSet', $q);

		// Apply limit after that filtering.
		if($limit && $records) return $records->getRange($limit['start'], $limit['limit']);
		else return $records;
	}
	
	function parameterFields() {
		$params = new FieldSet();

		$options = DataObject::get('Member')->toDropdownMap('ID', 'Name', 'Any');
		$params->push(new DropdownField(
			"memberId", 
			"Member", 
			$options
		));
		
		$params->push($startDate = new PopupDateTimeField('StartDate', 'Start date', date('Y-m-d H:i',time()-2419200)));
		$params->push($endDate = new PopupDateTimeField('EndDate', 'End date', date('Y-m-d H:i',time())));
		$endDate->defaultToEndOfDay();
		$startDate->allowOnlyTime(false);
		$endDate->allowOnlyTime(false);
		$endDate->mustBeAfter($startDate->Name());
		$startDate->mustBeBefore($endDate->Name());
		
		return $params;
	}
}


?>