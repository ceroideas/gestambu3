<?php
//Escribe la en el log de respuesta la notificación 
function notificar_estado($cod_demanda, $estado){
global $diagnostico1, $fecha_estado, $hora_estado, $diagnostico, $fecha_realiz;

	$response = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:seg="http://www.example.org/SeguimientoPeticionesWeb/">
   <soapenv:Header/>
   <soapenv:Body>
      <seg:notificarEstado>
         <seg:colaborador>AANDALUC</seg:colaborador>
         <seg:codigoDemanda>'.$cod_demanda.'</seg:codigoDemanda>';
		 
        if (isset($vuelta) && !empty($vuelta)){
			$response.='<seg:vuelta>'.$vuelta.'</seg:vuelta>';
		}
			
		if (isset($estado) && !empty($estado)){
			$response.='<seg:estado>'.$estado.'</seg:estado>';
		}
			
		if (isset($fecha_estado) && !empty($fecha_estado)){
			$response.='<seg:fecha_estado>'.$fecha_estado.'</seg:fecha_estado>';
		}
		
		if (isset($hora_estado) && !empty($hora_estado)){
			$response.='<seg:hora_estado>'.$hora_estado.'</seg:hora_estado>';
		}
		
		if (isset($fecha_realizacion) && !empty($fecha_realizacion)){
			$response.='<seg:fecha_realizacion>'.$fecha_realizacion.'</seg:fecha_realizacion>';
		}
		
		if (isset($hora_realizacion) && !empty($hora_realizacion)){
			$response.='<seg:hora_realizacion>'.$hora_realizacion.'</seg:hora_realizacion>';
		}
		
		if (isset($diagnostico) && !empty($diagnostico)){
			$response.='<seg:diagnostico>'.$diagnostico.'</seg:diagnostico>';
		}
		
		if (isset($diagnostico1) && !empty($diagnostico1)){
		   $response.='<seg:diagnostico1>'.$diagnostico1.'</seg:diagnostico1>';
		}
		
		if (isset($observaciones) && !empty($observaciones)){
			$response.='<seg:observaciones>'.$observaciones.'</seg:observaciones>';
		}

	$response.='
      </seg:notificarEstado>
   </soapenv:Body>
</soapenv:Envelope>';

    escribefichero($cod_demanda."_resp", $response);
}
//Escribe en el log
function escribefichero($incidencia, $xml){
    
    $file = __DIR__ ."/".date("Y-m-d")."_".$incidencia.".log";
    
    if (($arch = fopen($file, "a+")) !== false) {
        fwrite($arch, "$xml\n");
        fclose($arch);
    }
}

//Notificar estado en modo cliente
function notificar_estado_prueba($cod_demanda, $estado, $vuelta){
global $diagnostico1, $fecha_estado, $hora_estado, $diagnostico, $fecha_realiz,$cod_demanda;

//Necesitamos la url http://URL.asmx?WSDL

// $soapClient = new SoapClient("http://URL.asmx?WSDL",
//     array('cache_wsdl' => WSDL_CACHE_NONE,'trace' => TRUE));

//pasamos datos de busqueda
/*Parametros: campos notificar estado como cliente. Colaborador, codigo demanda y estado obligatorios */
// $sc_param = array('colaborador'=>'AANDALUC',
//                     'estado'=>$estado, 
//                     '$codigoDemanda'=>$cod_demanda,
//                     'vuelta'=>$vuelta,
//                     'fecha_estado'=>$fecha_estado,
//                     'hora_estado'=>$hora_estado,
//                     'diagnostico'=>$diagnostico,
//                     'diagnostico1'=>$diagnostico1,
//                     'fecha_realiz'=>$fecha_realiz                    
// );
//y obtenemos respuesta FUNCION es la funcion de ASISA para notificar estados. 
#$s_response = $soapClient->FUNCION($sc_param);
#$respuesta = $s_response->SearchResult->any;

// Escribir en el log 

    //$response = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:seg="https://movilradpru.asisa.es/SeguimientoPeticionesWeb/services/SeguimientoPeticionesWebSOAP">
	$response = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:seg="https://peticionesrad.asisa.es/SeguimientoPeticionesWeb/services/SeguimientoPeticionesWebSOAP?WSDL">
   <soapenv:Header/>
   <soapenv:Body>
      <seg:notificarEstado>
         <colaborador>AANDALUC</colaborador>
         <codigoDemanda>'.$cod_demanda.'</codigoDemanda>';

   if (isset($diagnostico1) && !empty($diagnostico1)){
	$response.='<diagnostico1>'.$diagnostico1.'</diagnostico1>';
   }
   if (isset($vuelta) && !empty($vuelta)){
	$response.='<vuelta>'.$vuelta.'</vuelta>';
   }   
   if (isset($fecha_estado) && !empty($fecha_estado)){
	$response.='<fecha_estado>'.$fecha_estado.'</fecha_estado>';
   }
   if (isset($hora_estado) && !empty($hora_estado)){
	$response.='<hora_estado>'.$hora_estado.'</hora_estado>';
   }
  if (isset($diagnostico) && !empty($diagnostico)){
	$response.='<diagnostico>'.$diagnostico.'</diagnostico>';
   }
  if (isset($fecha_realiz) && !empty($fecha_realiz)){
	$response.='<fecha_realizacion>'.$fecha_realiz.'</fecha_realizacion>';
   }
  if (isset($hora_realiz) && !empty($hora_realiz)){
	$response.='<hora_realizacion>'.$hora_realiz.'</hora_realizacion>';
   }      
	$response.='
         <estado>'.$estado.'</estado>
      </seg:notificarEstado>
   </soapenv:Body>
</soapenv:Envelope>';

    escribefichero($cod_demanda."_resp", $response);
}
?>
