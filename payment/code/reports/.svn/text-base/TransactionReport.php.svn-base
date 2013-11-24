<?php

/**
 * Show all publishing activity across the site
 *
 * @package cmsworkflow
 * @subpackage reports
 */
class TransactionReport extends SS_Report {
	function title() {
		return 'Transaction Report';
	}
	
	function columns() {
		$fields = array(
			'TxnData1'				=> array(
					'title'		=> 'ID#'					
					),

			'LastEdited'		=> array(
					'title'		=> 'Date/Time',
					'casting'	=> 'SS_Datetime->Nice24'
					),
			'MerchantReference' => 'Reference',
			'SettlementAmount'	=> array(
					'title'		=> "Amount",
					'casting'	=> 'Currency->Nice'
					),
			'FeeAmount'			=> array(
					'title'		=> "Fee",
					'casting'	=> 'Currency->Nice'
					),
			'CardName'			=> 'Card',
			'PersonalDetails'	=> 'PersonalDetails',
			'EmailAddress'		=> array(
					'title'		=> 'Email',
					'formatting'=> '<a target=\"_blank\" href=\"mailto:$value\">$value</a>'
					),
			'DpsTxnRef'			=> 'DpsRef',
			'ReceivedAsFPRN'	=>	array(
					'title'		=> 'Received as FPRN?',
					'casting'	=> 'Boolean->Nice',
					),
			);
		return $fields;
	}
	
	function sourceRecords($params, $sort, $limit) {
		$q = singleton('RecordedTransactions')->extendedSQL();
//		$q->select[] = '';
		$q->where[] = "\"RecordedTransactions\".\"Success\" = 1";

		// name search
		if (!empty($params['SearchName'])) {
			$q->where[] = "\"RecordedTransactions\".\"PersonalDetails\" LIKE '%".$params['SearchName']."%'";
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
			$q->where[] = "\"RecordedTransactions\".\"LastEdited\" >= '".Convert::raw2sql($startDate)."' AND \"RecordedTransactions\".\"LastEdited\" <= '".Convert::raw2sql($endDate)."'";
		} else if ($startDate && !$endDate) {
			$q->where[] = "\"RecordedTransactions\".\"LastEdited\" >= '".Convert::raw2sql($startDate)."'";
		} else if (!$startDate && $endDate) {
			$q->where[] = "\"RecordedTransactions\".\"LastEdited\" <= '".Convert::raw2sql($endDate)."'";
		} else {
			$q->where[] = "\"RecordedTransactions\".\"LastEdited\" >= '".SS_Datetime::now()->URLDate()."'";
		}
		
		// Default sort to Date published descending
		$sort = (!$sort) ? 'LastEdited DESC' : $sort;
		
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
		//$q->groupby = array("\"TxnData1\"");
		$records = singleton('SiteTree')->buildDataObjectSet($q->execute(), 'DataObjectSet', $q);

		// Apply limit after that filtering.
		if($limit && $records) return $records->getRange($limit['start'], $limit['limit']);
		else return $records;
	}
	
	function parameterFields() {
		$params = new FieldSet();
		
		$params->push(new TextField("SearchName", "Name"));
		
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