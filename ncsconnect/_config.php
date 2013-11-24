<?php

if (!Director::isLive()) {
	Director::addRules(100,array('NcsMockServer/$Action/$ID/$OtherID' => 'NcsMockServer'));
}

CemeteryPage_Controller::$ncs_server = 'http://tasdist.tdc.tdc.govt.nz:8002';
RatesPage_Controller::$ncs_server = 'http://tasdist.tdc.tdc.govt.nz:8002';

