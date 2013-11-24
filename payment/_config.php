<?php

Director::addRules(50, array(
	WorldpayPayment_Handler::$URLSegment . '/$Action/$ID' => 'WorldpayPayment_Handler',
	PayPalPayment_Handler::$URLSegment . '/$Action/$ID' => 'PayPalPayment_Handler',
	'harness/$Action/$Class/$ID' => 'DPSHarness', )); 

//Register Payment Reports
SS_Report::register('ReportAdmin', 'TransactionReport',20);

// Globals if needed
// PaymentPage::$gmaps_api_key = '';

PaymentPage_Controller::$ncs_server = 'http://tasdist.tdc.tdc.govt.nz:8002';

?>
