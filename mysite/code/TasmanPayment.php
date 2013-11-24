<?php

class TasmanPayment extends DataObjectDecorator {
	function extraStatics() {
		return array(
			'db'=> array(
				"InvoiceSent" => "Boolean",
			),
		);
	}
}