<?php

/**
 * Defines the Cemetery page type
 */
 
class CemeteryPage extends Page {
//   static $icon = 'nsconnect/icons/house';

   static $db = array(
	);

   static $has_one = array(
   );
}
 
class CemeteryPage_Controller extends Page_Controller {
	public static $ncs_server;

	function CemeteryDisplay() {
		$request = Convert::raw2att($_GET);
		
		$uid = isset($request['uid']) ?  $request['uid'] : 0;
		$surname = isset($request['surname']) ? $request['surname'] : '';
		$firstnames = isset($request['firstnames']) ? $request['firstnames'] : '';
		$gender = isset($request['gender']) ? $request['gender'] : '';
		$cemetery = str_ireplace(' ', '+', isset($request['cemetery']) ? $request['cemetery'] : '');
		$date_from = isset($request['date_from']) ? $request['date_from'] : '';
		$date_to = isset($request['date_to']) ? $request['date_to'] : '';
		$page = isset($request['page']) ? $request['page'] : '';
		
// http://tasdist.tdc.tdc.govt.nz:8002/cemeteries?surname=easton&first_names=&gender=&cemetery_code=&date_of_interment_from=&date_of_interment_to=&section=&block=&plot_number_1=
// http://tasdist.tdc.tdc.govt.nz:8002/cemeteries/31643
		
		if($uid>0){
			$cemetery_display = self::getChtml(self::$ncs_server.'/cemeteries/'.$uid);
			$cemetery_display = str_ireplace('/cemeteries/', $this->Link().'?uid=', $cemetery_display);
		} elseif($surname != '' || $firstnames != '' || $gender != '' || $cemetery != ''){
			$page_query = ($page > 0) ? 'page='.$page.'&' : '';
			$cemetery_display = self::getChtml(self::$ncs_server.'/cemeteries?'.$page_query.'surname='.$surname.'&first_names='.$firstnames.'&gender='.$gender.'&cemetery_code='.$cemetery.'&date_of_interment_from='.$date_from.'&date_of_interment_to='.$date_to);
			
			$cemetery_display = preg_replace('/\/cemeteries\/([0-9]*)/i', $this->Link().'?uid=$1', $cemetery_display);
			
			$cemetery_display = preg_replace("/\/cemeteries\?cemetery_code\=([A-Z]*)\&amp\;date_of_interment_from\=([A-Za-z0-9\%]*)\&amp\;date_of_interment_to\=([A-Za-z0-9\%]*)\&amp\;first\_names\=([A-Za-z0-9]*)\&amp\;gender\=([A-Z]*)\&amp\;page\=([0-9]*)\&amp\;surname\=([A-Za-z]*)/i", $this->Link().'?page=$6&surname='.$surname.'&firstnames='.$firstnames.'&gender='.$gender.'&cemetery='.$cemetery.'&date_from='.$date_from.'&date_to='.$date_to, $cemetery_display);
			
			
		} else {
			$cemetery_display = '';
		}
		

		return $cemetery_display;
	}
	
	function CemeterySearchForm() {
		// get values from query string. Convert them
		// to safe values
		$request = Convert::raw2att($_GET);
		
		$surname = isset($request['surname']) ? $request['surname'] : '';
		$firstnames = isset($request['firstnames']) ? $request['firstnames'] : '';
		$gender = isset($request['gender']) ? $request['gender'] : '';
		$cemetery = isset($request['cemetery']) ? $request['cemetery'] : '';
		$date_from = isset($request['date_from']) ? $request['date_from'] : '';
		$date_to = isset($request['date_to']) ? $request['date_to'] : '';	

		$gender_options = array(
			'' => 'Unknown',
			'F' => 'Female',
			'M' => 'Male',
		);
		
		$cemetery_options = array(
			'' => 'Unknown',
			'BAI' => 'Bainham',
			'CLI' => 'Clifton',
			'COL' => 'Collingwood',
			'DOV' => 'Dovedale',
			'EAS' => 'East Takaka',
			'FLE' => 'Flett Road',
			'FOX' => 'Foxhill',
			'HAM' => 'Hamama West Takaka',
			'KOT' => 'Kotinga',
			'MAR' => 'Mararewa',
			'MOT' => 'Motueka',
			'MUR' => 'Murchison',
			'OLD' => 'Old Collingwood (Closed)',
			'PIO' => 'Pioneer Motueka (Closed)',
			'RIC' => 'Richmond',
			'RIW' => 'Riwaka',
			'ROT' => 'Rototai',
			'SAN' => 'Sandy Bay',
			'SPR' => 'Spring Grove',
			'STA' => 'Stanley Brook',
			'WAI' => 'Waimea West',
			'WHE' => 'Waiwhero',	
		);

		// create fields
		$fields = new FieldSet(
			new TextField('surname', 'Surname', $surname),
			new TextField('firstnames', 'First names', $firstnames),
//			new DropdownField('gender', 'Gender', $gender_options, $gender),
			new HiddenField('gender', 'Gender', $gender),
			new DropdownField('cemetery', 'Cemetery', $cemetery_options, $cemetery),
			new HiddenField('date_from', 'Date of interment', $date_from),
			new HiddenField('date_to', 'to', $date_to)
	
		);
		
		// create actions
		$actions = new FieldSet(
			new FormAction('GetRateResults', 'Submit')
		);
		
		return new Form($this, 'CemeterySearchForm', $fields, $actions);

	}

	function GetRateResults($data, $form) {
		Director::redirect($this->Link().'?surname='.$data['surname'].'&firstnames='.$data['firstnames'].'&gender='.$data['gender'].'&cemetery='.$data['cemetery'].'&date_from='.$data['date_from'].'&date_to='.$data['date_to']);
	}	
	
	
	static function getChtml($file_url){

		$time1 = microtime(true)*1000;
		$file = @fopen ($file_url, 'r');
		if (!$file) {
			$html_body = '<h2>Cannot Access Cemetery Database</h2><p>The Cemetery Database is currently inaccessible please <a href="/contact-us/">Contact Us</a> for the information you require.</p>
			<p></p>';
		} else {

			$file_contents = '';
			$html_body = '';

			$time2 = microtime(true)*1000;
			while (!feof ($file)) {
				$file_contents .= fgets($file, 1024);
			}

			$time3 = microtime(true)*1000;

			$html_body = ($html_body == '') ? $file_contents : $html_body;
			$time4 = microtime(true)*1000;

			fclose($file);
			$time5 = microtime(true)*1000;
		}

		return $html_body;
	}
}
