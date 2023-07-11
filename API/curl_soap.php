<?php
defined("TAB1") or define("TAB1", "\t");
defined("TAB2") or define("TAB2", "\t\t");
defined("TAB3") or define("TAB3", "\t\t\t");

class curl_soap {

	/*
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	*/

	private static function curlSoap($soap_postfields){
		include $_SERVER["DOCUMENT_ROOT"].'/API/parametros.php';
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $soap_notificaciones,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => $soap_timeout,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $soap_postfields,
		  CURLOPT_HTTPHEADER => array(
			"Ocp-Apim-Subscription-Key: ".$soap_ocp_key,
			"api-version: ".$soap_api_version,
			"SOAPAction: ".$soap_action,
			"Content-Type: application/xml"
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$xml = simplexml_load_string($response);
		$xml->registerXPathNamespace("a", "http://schemas.datacontract.org/2004/07/ASISA.Viena.DemandService.API.Viewmodels.ProviderUpdate");
		$code = $xml->xpath('//a:codigo')[0];
		$msg = $xml->xpath('//a:mensaje')[0];
		return array('code' => $code, 'msg' => $msg);
	}

	private static function xmlLog($incidencia, $xml){
	    
	    $file = __DIR__ ."/".date("Y-m-d")."_".$incidencia.".log";
	    
	    if (($arch = fopen($file, "a+")) !== false) {
	        fwrite($arch, "$xml\n");
	        fclose($arch);
	    }
	}

	public static function callViena($cod_demanda, $estado, $vuelta, $fecha_estado, $hora_estado, $fecha_realizacion, $hora_realizacion, $diagnostico1, $diagnostico2, $observaciones){
		$xmlViena = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:seg="http://www.example.org/SeguimientoPeticionesWeb/">'.PHP_EOL;
		$xmlViena.=TAB1.'<soapenv:Body>'.PHP_EOL;
		$xmlViena.=TAB2.'<seg:notificarEstado>'.PHP_EOL;
		$xmlViena.=TAB3.'<seg:colaborador>AANDALUC</seg:colaborador>'.PHP_EOL;
		$xmlViena.=TAB3.'<seg:codigoDemanda>'.$cod_demanda.'</seg:codigoDemanda>'.PHP_EOL;
		if (isset($vuelta) && !empty($vuelta)){
		$xmlViena.=TAB3.'<seg:vuelta>'.$vuelta.'</seg:vuelta>'.PHP_EOL;
		}
		
		if (isset($estado) && !empty($estado)){
		$xmlViena.=TAB3.'<seg:estado>'.$estado.'</seg:estado>'.PHP_EOL;
		}
	
		if (isset($fecha_estado) && !empty($fecha_estado)){
		$xmlViena.=TAB3.'<seg:fecha_estado>'.$fecha_estado.'</seg:fecha_estado>'.PHP_EOL;
		}
		
		if (isset($hora_estado) && !empty($hora_estado)){
		$xmlViena.=TAB3.'<seg:hora_estado>'.$hora_estado.'</seg:hora_estado>'.PHP_EOL;
		}
		
		if (isset($fecha_realizacion) && !empty($fecha_realizacion)){
			$xmlViena.=TAB3.'<seg:fecha_realizacion>'.$fecha_realizacion.'</seg:fecha_realizacion>'.PHP_EOL;
		}
		
		if (isset($hora_realizacion) && !empty($hora_realizacion)){
			$xmlViena.=TAB3.'<seg:hora_realizacion>'.$hora_realizacion.'</seg:hora_realizacion>'.PHP_EOL;
		}
		
		if (isset($diagnostico1) && !empty($diagnostico1)){
			$xmlViena.=TAB3.'<seg:diagnostico1>'.$diagnostico1.'</seg:diagnostico1>'.PHP_EOL; //Modificación para pruebas, anterior: $xmlViena.=TAB3.'<seg:diagnostico>'.$diagnostico1.'</seg:diagnostico>'.PHP_EOL;
		}
		
		if (isset($diagnostico2) && !empty($diagnostico2)){
		   $xmlViena.=TAB3.'<seg:diagnostico2>'.$diagnostico2.'</seg:diagnostico2>'.PHP_EOL;//Modificación para pruebas, anterior: $xmlViena.=TAB3.'<seg:diagnostico1>'.$diagnostico2.'</seg:diagnostico1>'.PHP_EOL;
		}
		
		if (isset($observaciones) && !empty($observaciones)){
			$xmlViena.=TAB3.'<seg:observaciones>'.$observaciones.'</seg:observaciones>'.PHP_EOL;
		}

		$xmlViena.=TAB2.'</seg:notificarEstado>'.PHP_EOL;
		$xmlViena.=TAB1.'</soapenv:Body>'.PHP_EOL;
		$xmlViena.='</soapenv:Envelope>'.PHP_EOL;

		$response = self::curlSoap($xmlViena);
		$code = $response['code'];
		$msg = $response['msg'];

		if ($code <= '0') {
		    echo "Envio correcto: $code -- descripción $msg";
		} else {
		    echo "Error con código $code -- descripción $msg";
		}

		echo "<br>";
		self::xmlLog($cod_demanda."_notif", $xmlViena);

		return $response;
	}
}

?>