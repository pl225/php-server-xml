<?php 

	function customError($errno, $errstr) {
	  	echo "<b>Error: [$errno] $errstr </b>";
	}

	set_error_handler("customError");

	header('Access-Control-Allow-Origin: *'); // {$_SERVER['HTTP_ORIGIN']}
	header('Access-Control-Allow-Headers: Content-Type');
	header('Content-Type: text/xml; charset=utf-8');

	//$xml = simplexml_load_string($_GET['xml']);

	$xml = new DOMDocument();
	$xml->loadXML($_GET['xml']);

	if ($xml->schemaValidate("request.xml")) {
		echo "<teste><resultado>{$xml->getElementsByTagName("cpf")[0]->nodeValue}</resultado></teste>"; // tentar responder com xml
	} else {
		echo "<teste><resultado>erro</resultado></teste>"; // tentar responder com xml
	}