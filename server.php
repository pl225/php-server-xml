<?php 

	function customError($errno, $errstr) {
	  	echo "<b>Error:</b> [$errno] $errstr";
	}

	set_error_handler("customError");
	
	pg_connect("host=localhost port=5432 dbname=sd user=pl225 password=Pl2252122*") // conectar ao banco
    	or die('Could not connect: ' . pg_last_error());

	header('Access-Control-Allow-Origin: *'); // {$_SERVER['HTTP_ORIGIN']}
	header('Access-Control-Allow-Headers: Content-Type');
	header('Content-Type: text/xml; charset=utf-8');

	//$xml = simplexml_load_string($_GET['xml']);

	$query = 'SELECT * FROM trab1.historico';
	$result = pg_query($query);
	echo pg_fetch_array($result, 0, PGSQL_ASSOC)['nota'];

	$xml = new DOMDocument();
	$xml->loadXML($_GET['xml']);

	if ($xml->schemaValidate("Downloads/schema_teste.xsd")) {
		echo "<teste><resultado>{$xml->getElementsByTagName("numero")[0]->nodeValue}</resultado></teste>"; // tentar responder com xml
	} else {
		echo "<teste><resultado>erro</resultado></teste>"; // tentar responder com xml
	}

	pg_free_result($result);
	pg_close($dbconn);