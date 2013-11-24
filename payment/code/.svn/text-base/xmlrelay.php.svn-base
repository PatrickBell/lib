<?php
$ncs_server = 'http://tasdist.tdc.tdc.govt.nz:8002';

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 1 Sat 2000 00:00:00 GMT');
header('Content-type: text/xml; charset=utf-8');

$type	= isset($_GET['t']) ? $_GET['t'] : '';
$id		= isset($_GET['id']) ? $_GET['id'] : 0;

switch($type){
	case "rates":
		$url = $ncs_server.'/properties/valid?valuation_id='.$id;
		$empty = '<Property><Result>0</Result></Property>';
	break;

	case "water":
		$url = $ncs_server.'/water/valid?water_id='.$id;
		$empty = '<WaterCustomer><Result>0</Result></WaterCustomer>';
	break;
}

	$file_headers = get_headers($url);
	
	if(!$url || strpos($file_headers[0], '200') === false){
		$file_contents = '<?xml version="1.0" encoding="UTF-8"?>'.$empty;
	} else {
		$file_contents = file_get_contents($url);
	}
echo $file_contents;

die();
?>