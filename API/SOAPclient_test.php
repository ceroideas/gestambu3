<?php
//Escribe la en el log de respuesta la notificación 
function notificar_estado($cod_demanda, $estado){
global $diagnostico1, $fecha_estado, $hora_estado, $diagnostico, $fecha_realiz;

    $response = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:seg="http://www.example.org/SeguimientoPeticionesWeb/">
   <soapenv:Header/>
   <soapenv:Body>
      <seg:notificarEstado>
         <colaborador>AANDALUC</colaborador>
         <codigoDemanda>'.$cod_demanda.'</codigoDemanda>';

   if (isset($diagnostico1) && !empty($diagnostico1)){
	$response.='<diagnostico1>'.$diagnostico.'</diagnostico1>';
   }


	$response.='
         <estado>'.$estado.'</estado>
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

?>
