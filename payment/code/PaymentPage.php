<?php
class PaymentPage extends Page {

	function GetTransactionID() {
		if(Director::urlParam('Action') == 'confirm') {
			return Convert::raw2xml(Director::urlParam('ID'));
		}
	}

}
class PaymentPage_Controller extends Page_Controller{

	public static $ncs_server;

	static $allowed_actions = array (
		'confirm',
		'rates',
		'water',
		'PaymentForm'
	);	

	function PageMode(){
		return $this->Action;
	}
	
	function PaymentForm(){
	
		$PaymentType = (Director::urlParam('PaymentType') == '') ? $this->Action : Director::urlParam('PaymentType');
		
		if($PaymentType == 'rates'){
			$ReferenceLabel = 'Valuation Number of Property, no spaces or hyphens';
			$ReferenceDefault = '';
			Requirements::javascript('payment/javascript/RatesPayment.js','');
		} elseif($PaymentType == 'water') {
			$ReferenceLabel = 'Water Account Number, no spaces or hyphens';
			$ReferenceDefault = 'W';
			Requirements::javascript('payment/javascript/WaterPayment.js','');
		} else {
			$ReferenceLabel	= '';
			$ReferenceDefault = '';
		}
		
		$ReferenceNumberField = new TextField('ReferenceNumber', $ReferenceLabel, $ReferenceDefault );
		
		// Create fields
		$fields = new FieldSet(
			$ReferenceNumberField, 
			new HiddenField('ReferenceValid', '', 0),
			new TextField('FirstName', 'Your First Name*', '' ),
			new TextField('LastName', 'Your Last Name*', '' ),
			new TasmanEmailField('Email', 'Your Email Address*', '' ),
			new TextField('PhoneNumber', 'Your Phone Number*', '' ),
			new NumericField('Amount', 'Amount to Pay, NZD*', ''),
			new TextField('ConvenienceFee', 'Convenience Fee, NZD', '2% of your payment, minimum $3.00' ),
			new TextField('TotalAmount', 'Total To Pay, NZD', '' ),
			new CheckboxField('AgreeToConditions', 'I Agree to the <a href="/disclaimer/" target="_blank">Terms and Conditions</a>*', ''),
			new HiddenField('PaymentType', '', $PaymentType)
		);
		
		// Set required fields in form object and assign classes
		$requiredFieldList = array('ReferenceNumber', 'FirstName','LastName','Email', 'PhoneNumber','Amount', 'AgreeToConditions' );
		
		$validator = new PaymentPageValidator($requiredFieldList);
		
		foreach($requiredFieldList as $fieldName) {
 			$fields->fieldByName($fieldName)->addExtraClass("requiredField"); 
		} 		

		// Create actions
		$actions = new FieldSet(
			new FormAction('RedirectToPaymentPage', 'Proceed to Pay')
		);
		
		$PaymentForm = new Form($this, 'PaymentForm', $fields, $actions, $validator);	
	
		// If previous form data exists, reload.	
		if (($oldData = Session::get('PaymentForm_'.$PaymentType)) || ($oldData = unserialize(Cookie::get('PaymentForm_'.$PaymentType)))) {
			$PaymentForm->loadDataFrom($oldData);
		} 	
	 
		return $PaymentForm;
	}
	
	function PaymentSuccess(){
		$GetPaymentDetails = DataObject::get_one("RecordedTransactions", "\"TxnData1\"='{$this->request->param('ID')}'", "", "", 1);
		if ($GetPaymentDetails->Success) {
			Cookie::set('PaymentForm_water', null); 
			Session::set('PaymentForm_water', null);
			Cookie::set('PaymentForm_rates', null); 
			Session::set('PaymentForm_rates', null);
			Cookie::set('PaymentRepeat', null);
			Session::set('PaymentRepeat', null);
		}
		return ($GetPaymentDetails) ? $GetPaymentDetails->Success : 0;
	}
	
	function ErrorCheck(){
		if(Director::urlParam('ID') == 'error' && $this->Action == 'rates'){
			$ErrorMessage = '<h2>Valuation Number Not Valid</h2>
			<p>The Valuation Number you entered did not match any Rateable Property Records. Please check and try again.</p>';
		} elseif(Director::urlParam('ID') == 'error' && $this->Action == 'water'){
			$ErrorMessage = '<h2>Water Account Number Not Valid</h2>
			<p>The Water Account Number you entered did not match any Property Records. Please check and try again.</p>';
		} elseif(Director::urlParam('ID') == 'error'){
			$ErrorMessage = '<h2>An Error Has Occurred</h2>
			<p>Please check your details and try again.</p>';
		} else {
			$ErrorMessage = '';	
		}
		
		return $ErrorMessage;
	}
			
	function PaymentReceipt(){
		$confirm_id = $this->ID;
		
		$GetPaymentDetails = DataObject::get_one("RecordedTransactions", "\"TxnData1\"='{$this->request->param('ID')}'", "", "", 1);
		$PaymentDetails = new DataObjectSet();
//		debug::show(print_r($GetPaymentDetails)); 
		if($GetPaymentDetails){
			foreach($GetPaymentDetails as $PaymentItem){
				
				$extrainfo = array(
					'Expire' => (strtotime($PaymentItem->LastEdited) < time()-600) ? 1 : 0,
					'ID' => $PaymentItem->TxnData1,
					'LastEdited' => date("j F Y - g:ia", strtotime($PaymentItem->LastEdited)),
					'Reference' => $PaymentItem->MerchantReference,
					'SettlementAmount' => sprintf("%9.2f", $PaymentItem->SettlementAmount),
					'FeeAmount' => sprintf("%9.2f", $PaymentItem->FeeAmount),
					'TotalAmount' => sprintf("%9.2f", ($PaymentItem->FeeAmount + $PaymentItem->SettlementAmount)),
					'TotalGST' => sprintf("%9.2f", ($PaymentItem->FeeAmount + $PaymentItem->SettlementAmount)*0.15),
					'PersonalDetails' => $PaymentItem->PersonalDetails,
					'EmailAddress' => $PaymentItem->EmailAddress,
					'DpsTxnRef' => $PaymentItem->DpsTxnRef
					);
				$PaymentDetails -> push(new ArrayData($extrainfo));
			}
		}
		
		//Expire if more than an hour old
		return $PaymentDetails;	
	}
	
	/**
	 * Provide a link to retry transaction
	 */
	function PaymentRepeatLink() {
		if (($link = Session::get('PaymentRepeat')) || ($link = Cookie::get('PaymentRepeat'))) {
			return $link;
		}

		return $this->Link();
	}

	function RedirectToPaymentPage($data, $form){
		// Validate the valuation ID server side.
		
		// $inputs can have optional pxpay input values added to it if necessary. 
		// Be careful some of these may be overwritten in the DPSPayment.php files prepareDPSHostedRequest function
		
		// Payment codes are governed by Payment type
		// RA	Rates
		// WT	Water
		// DR	Debtor
		// DG	Dogs
		// PI	Parking Infringement
		// SI	Sundry Infringement
		// DI	Dog Infringement.
		
		// Payment type
		$PaymentType = $data['PaymentType'];

		// Store for later, to be cleaned on successful submit, cookie valid for 10 minutes.
		Cookie::set('PaymentForm_'.$PaymentType, serialize($data), 1/144); 
		Session::set('PaymentForm_'.$PaymentType, $data);		
		Cookie::set('PaymentRepeat', $this->Link().$PaymentType.'/', 1/144);
		Session::set('PaymentRepeat', $this->Link().$PaymentType.'/');
		
		if($PaymentType == 'rates'){
			$ValidateXML = CheckXML(self::$ncs_server.'/properties/valid?valuation_id='.$data['ReferenceNumber'], 'Rateable', 1);
			$PaymentSuffix = 'RA';
		} elseif($PaymentType == 'water'){
			$ValidateXML = CheckXML(self::$ncs_server.'/water/valid?water_id='.$data['ReferenceNumber'], 'Result', 1);
			$PaymentSuffix = 'WT';
		} else {
			$PaymentSuffix = '';
		} 
		
		if(!$ValidateXML){
			if($PaymentType == 'rates'){
				$form->addErrorMessage('ReferenceNumber','<div class="validate-incorrect"><p><strong>No Matching Record Found</strong><br/>Please enter the Valuation Number of the property you wish to pay rates for without any spaces or hyphens. The number can be found on the top right of your rates letter or through an online search.<br/><a href="/property/rates/rate-record-search/" target="_blank">Online property search</a><br/><a href="/contact-us/#Rates" target="_blank">Contact Us</a></p></div>','required');
			} elseif($PaymentType == 'water'){
				$form->addErrorMessage("ReferenceNumber", "<div class='validate-incorrect'><p><strong>No Matching Record Found</strong><br/>Please enter the Water Account Number you wish to pay residential water rates for without any spaces or hyphens. The number can be found on the top right of your water account letter, starting with ‘W’, or through an online search.<br/><a href='/property/rates/rate-record-search/' target='_blank'>Online property search</a><br/><a href='/contact-us/#Rates' target='_blank'>Contact Us</a></p></div>", 'required');
			} else {
				$form->addErrorMessage("ReferenceNumber", "<div class='validate-incorrect'><p><strong>This does not match any of our records</strong><br/> Please try again.</p></div>", 'required');
			} 

			
			return Director::redirectBack();
//			Director::redirect('/pay-online/'.$PaymentType.'/error');
			return false;
		}

		
		// Add personal details to custom transaction fields. Keep under 255 characters
		$PersonalDetails = substr($data['FirstName'].' '.$data['LastName'].' - '.$data['PhoneNumber'],0,254);
		
		$amount = preg_replace ('/[^\d.]/', '', $data['Amount']); //
		
		if ($amount>=1000000) {
			$form->addErrorMessage("Amount", "The amount exceeds the allowed maximum.", 'validation');
			Director::redirectBack();
			return;
		}

		// Calculate and compare the Javascript (client side) generated fee versus the PHP (server side) generated fee. If they are within 5 cents client side is used, otherwise server side is used (for when Javascript is disabled or tampered with).
		$jsfee	= preg_replace ('/[^\d.]/', '', $data['ConvenienceFee']); //
		$jsfee	= sprintf("%9.2f",$jsfee);
		
		// Fee is 2% of the amount with a minimum amount of 3.00
		$asPercentage = $amount * 0.02;
		if($asPercentage > 3.00){        
			$phpfee = sprintf("%9.2f",$asPercentage); 
		}else{
			$phpfee = "3.00";
		}
		$feedifference = abs($jsfee-$phpfee);
		$ConvenienceFee = ($feedifference <= 0.5) ? $jsfee : $phpfee;
		
//		$PersonalDetails .= ' -> JSF:'.$jsfee.' PHPF'.$phpfee.' FD:'.$feedifference.' FCF:'.$ConvenienceFee;

		// TxnData2 is used for displaying the Convenience fee
		$inputs = array(
			"EmailAddress"=>$data['Email'],
			"MerchantReference"=>$PaymentSuffix.$data['ReferenceNumber'],
			"TxnData2"=>$ConvenienceFee,
			"AmountSurcharge"=>$ConvenienceFee,
			"TxnData3"=>$PersonalDetails
			);

		//Try format amount correctly		
		$amount = sprintf("%9.2f",$amount);
		
		$payment = new DPSPayment();
		$payment->Amount->Amount = $amount;	
		$payment->Amount->Currency = "NZD";		
		//$payment->PaidByID = $member->ID;
		//$payment->PaidForClass = $this->owner->ClassName;
		$payment->MerchantReference = "TestPayment";
		$payment->write();
		$payment->DPSHostedRedirectURL = $this->ConfirmLink($payment);
		$payment->write();

		PaymentLog::log('PaymentPage', serialize($data));

		$payment->dpshostedPurchase($inputs);
	}
	
	function ConfirmLink($payment) {
		$payablesPage = DataObject::get_one('PaymentPage');
//		return $payablesPage->Link()."confirm/".$payment->ClassName."/".$payment->ID;
		return $payablesPage->Link()."confirm/".$payment->ID;
	}
}

function CheckXML($url, $element, $value){
	
	$file_headers = get_headers($url);
	
	if(!$url || strpos($file_headers[0], '200') === false){
		$xmlStr = '<?xml version="1.0" encoding="UTF-8"?>';
	} else {
		$xmlStr = file_get_contents($url);
	}	
	
	$xmlObj = simplexml_load_string($xmlStr);
	$arrXml = objectsIntoArray($xmlObj);
	if(array_key_exists($element, $arrXml)){
		if($arrXml[$element] == $value){
			return true;
		}
	}
	//	debug::show(print_r($arrXml)); 
	return false;
}

//This function from PHP.net: http://www.php.net/manual/en/function.xml-parse.php#97556
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
	$arrData = array();
	
	// if input is object, convert into array
	if (is_object($arrObjData)) {
		$arrObjData = get_object_vars($arrObjData);
	}
	
	if (is_array($arrObjData)) {
		foreach ($arrObjData as $index => $value) {
			if (is_object($value) || is_array($value)) {
				$value = objectsIntoArray($value, $arrSkipIndices); // recursive call
			}
			if (in_array($index, $arrSkipIndices)) {
				continue;
			}
			$arrData[$index] = $value;
		}
	}
	return $arrData;
}

?>
