<?php 

	$retorno = "<methodReturn>
    				<methodName>METODO</methodName>
    	      	  	<value>VALOR</value>
	          	</methodReturn>";

	function customError($errno, $errstr, $errfile, $errline, $errcontext) { // para mandar o código de erro interno
		
		if (strpos($errno, 'DOMDocument::loadXML()') !== false && strpos($errno, 'DOMDocument::schemaValidate()') !== false) { // se ocorreu erro em algum lugar do código q nao sejam os erros dos xmls, lança erro interno
	  		echo str_replace('VALOR', '3', $errcontext['retorno']); // o último parâmetro serve para pegar as variáveis declaradas no escopo de onde aconteceu o erro (simplesmente para nao copiar a variavel de retorno para dentro da funcao, ela nao eh visivel aqui )
	  	}
	}

	set_error_handler("customError"); // altera o tratador de erro padrao do php para lancarmos erro interno caso seja necessario

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Content-Type: text/xml; charset=utf-8');

	$xml = new DOMDocument();

	$consultarStatus = 'consultarStatus';
	$submeter = 'submeter';

	$req = null;

	$retorno = str_replace('METODO', $_SERVER['REQUEST_METHOD'] == 'GET' ? $consultarStatus : $submeter, $retorno);
	$valorErro = $_SERVER['REQUEST_METHOD'] == 'GET' ? ['-1', '-2'] : ['1', '2'];
	
	if (key($_GET))
		$req = key($_GET);
	else if (key($_POST))
		$req = key($_POST);
	else
		$req = file_get_contents('php://input');

	$req = utf8_encode($req);
	
  	if($xml->loadXML($req)) {
		if ($xml->schemaValidate("request.xml")) {
				
			if ($xml->getElementsByTagName("methodName")[0]->nodeValue == $consultarStatus) {
				
				$cpf = substr($xml->getElementsByTagName("cpf")[0]->nodeValue, -1);
				echo str_replace('VALOR', $cpf >= '0' && $cpf <= '4' ? $cpf : '4', $retorno); // os cpfs sempre terao o ultimo caractere igual ao codigo q temos q retornar, entao eh so pegar o ultimo caractere da string
			
			} else if ($xml->getElementsByTagName("methodName")[0]->nodeValue == $submeter) { // envia 0 em caso de sucesso
      			
      			echo str_replace('VALOR', '0', $retorno);
      		
      		}

		} else { // se o esquema estiver errado envia o codigo de erro
			
			echo str_replace('VALOR', $valorErro[0], $retorno);
		
		}  
  	} else { // se o xml estiver mal formatado envia o codigo de erro
    	
    	echo str_replace('VALOR', $valorErro[1], $retorno);
  	
  	}  

?>