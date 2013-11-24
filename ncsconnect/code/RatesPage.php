<?php

/**
 * Defines the Rates page type
 */
 
class RatesPage extends Page {
//   static $icon = 'nsconnect/icons/house';

   static $db = array(
	);

   static $has_one = array(
   );
}
 
class RatesPage_Controller extends Page_Controller {
	public static $ncs_server;

	function RatesDisplay() {
		$request = Convert::raw2att($_GET);
		
		$uid = isset($request['uid']) ? (int) $request['uid'] : 0;
		$valuation_id = isset($request['valuation_id']) ? $request['valuation_id'] : '';
		$street_from = isset($request['street_from']) ? $request['street_from'] : '';
		$street_to = isset($request['street_to']) ? $request['street_to'] : '';
		$street_name = str_ireplace(' ', '+', isset($request['street_name']) ? $request['street_name'] : '');
		$legal_description = str_ireplace(' ', '+', isset($request['legal_description']) ? $request['legal_description'] : '');
		$page = isset($request['page']) ? $request['page'] : '';
		
		//Strip non alphanumberic characters from Valuation ID
		$valuation_id = preg_replace('/[^a-zA-Z0-9]/', '', $valuation_id);

		if($uid>0){
			if (isset($_GET['current_new']) && $_GET['current_new'] == "new") {
				$rates_display = GetHTML(self::$ncs_server.'/properties/'.$uid."?current_new=new");
			} else {
				$rates_display = GetHTML(self::$ncs_server.'/properties/'.$uid."?current_new=current");
			}

			$rates_display = preg_replace('/\/properties\/'.$uid.'(\?current_new=(\w+))?/i', $this->Link().'?uid='.$uid."&current_new=$2", $rates_display);
		} elseif($valuation_id != '' || $street_from != '' || $street_to != '' || $street_name != '' || $legal_description != ''){
			$page_query = ($page > 0) ? 'page='.$page.'&' : '';
			$getURL = self::$ncs_server.'/properties?'.$page_query.'property[valuation_id]='.$valuation_id.'&street_from='.$street_from.'&street_to='.$street_to.'&street[address_1]='.$street_name.'&legal_description='.$legal_description;
			$rates_display = GetHTML($getURL);
			
			$rates_display = str_ireplace('/properties/', $this->Link().'?uid=', $rates_display);
			$rates_display = preg_replace("/\/properties\?legal_description\=([A-Za-z0-9[:punct:]]*)\&amp\;page\=([0-9]*)\&amp\;property\%5Bvaluation\_id\%5D\=([A-Za-z0-9]*)\&amp\;street\%5Baddress\_1\%5D\=([A-Za-z0-9[:punct:]]*)\&amp\;street\_from\=([A-Za-z0-9\%]*)\&amp\;street\_to\=([A-Za-z0-9\%]*)/i", $this->Link().'?legal_description='.$legal_description.'&page=$2&valuation_id='.$valuation_id.'&street_from='.$street_from.'&street_to='.$street_to.'&street_name='.$street_name, $rates_display);
			
		} else {
			$rates_display = '';
		}
		

		return $rates_display;
	}
	
	function RatesSearchForm() {
		$request = Convert::raw2att($_GET);
		
		//Get values from query string
		$valuation_id = isset($request['valuation_id']) ? $request['valuation_id'] : '';
		$street_from = isset($request['street_from']) ? $request['street_from'] : '';
		$street_to = isset($request['street_to']) ? $request['street_to'] : '';
		$street_name = isset($request['street_name']) ? $request['street_name'] : '';
		$legal_description = isset($request['legal_description']) ? $request['legal_description'] : '';

		//Strip non alphanumberic characters from Valuation ID
		$valuation_id = preg_replace('/[^a-zA-Z0-9]/', '', $valuation_id);

		//Create fields
		$fields = new FieldSet(
			new TextField('valuation_id', 'Valuation Number', $valuation_id,12),
			new TextField('street_from', 'Street Number from', $street_from,8),
			new TextField('street_to', 'to', $street_to,8),
			new TextField('street_name', 'Street Name', $street_name,52),
			new TextField('legal_description', 'Legal Description', $legal_description,50)
		);
		
		//Create actions
		$actions = new FieldSet(
			new FormAction('GetRateResults', 'Submit')
		);
		
		return new Form($this, 'RatesSearchForm', $fields, $actions);

	}

	function GetRateResults($data, $form) {
//		print_r($data);
//		echo '<hr/>';
//		print_r($form);
		$data = Convert::raw2att($data);
		
		if($data['street_from'] > 0 && $data['street_to'] < $data['street_from']){
				$data['street_to'] = $data['street_from'];
		}

		if($data['street_to'] > 0 && $data['street_from'] < 1){
			$data['street_from'] = $data['street_to'];
		}


		Director::redirect($this->Link().'?valuation_id='.$data['valuation_id'].'&street_from='.$data['street_from'].'&street_to='.$data['street_to'].'&street_name='.$data['street_name'].'&legal_description='.$data['legal_description']);
	}	
}

function GetHTML($file_url){

	$time1 = microtime(true)*1000;
	$file = @fopen ($file_url, 'r');
	if (!$file) {
		$html_body = '<h2>Cannot Access Rates Database</h2><p>The Rates Database is currently inaccessible please <a href="/contact-us/">Contact Us</a> for the information you require.</p>
		<p></p>';
	} else {
	
		$file_contents = '';
		$html_body = '';
	
		$time2 = microtime(true)*1000;
		while (!feof ($file)) {
			$file_contents .= fgets($file, 1024);
		}
	
		$time3 = microtime(true)*1000;
//		if (preg_match ('@\<body(.*)\</body\>@i', $file_contents, $out)) {
//			$html_body = $out[1];
//			break;
//		}

		$html_body = ($html_body == '') ? $file_contents : $html_body;
		$time4 = microtime(true)*1000;
	
		fclose($file);
		$time5 = microtime(true)*1000;
	
		//		$html_body .= '<hr/><h6>Time debugging information in milliseconds</h6><p>Open File: '.($time2-$time1).'</p>'.'<p>Get File: '.($time3-$time2).'</p>'.'<p>Get HTML: '.($time4-$time3).'</p>'.'<p>Close File: '.($time5-$time4).'</p><hr/>';
}
		
	return $html_body;
}

?>
