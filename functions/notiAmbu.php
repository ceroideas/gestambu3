<?php
function notificar_estado_Modi($cod_demanda, $estado, $vuelta){
global $diagnostico1, $fecha_estado, $hora_estado, $diagnostico, $fecha_realiz, $hora_realiz;

//conexion a dentyred por soap
    //$soapClient = new SoapClient("https://movilradpru.asisa.es/SeguimientoPeticionesWeb/services/SeguimientoPeticionesWebSOAP",
    //       array('cache_wsdl' => WSDL_CACHE_NONE,'trace' => TRUE));

//pasamos datos de busqueda*/
    $response = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:seg="http://www.example.org/SeguimientoPeticionesWeb/">
   <soapenv:Header/>
   <soapenv:Body>
      <seg:notificarEstado>
         <colaborador>AMBANDAL</colaborador>
         <codigoDemanda>'.$cod_demanda.'</codigoDemanda>';

   if (isset($diagnostico1) && !empty($diagnostico1)){
	$response.='<diagnostico1>'.$diagnostico.'</diagnostico1>';
   }
	$response.='<vuelta>'.$vuelta.'</vuelta>'; //para servicios de ambulancia
  $response.='<fecha_estado>'.$fecha_estado.'</fecha_estado>';
  $response.='<hora_estado>'.$hora_estado.'</hora_estado>';
  $response.='<fecha_realizacion>'.$fecha_realiz.'</fecha_realizacion>';
  $response.='<hora_realizacion>'.$hora_realiz.'</hora_realizacion>';
	$response.='
         <estado>'.$estado.'</estado>
      </seg:notificarEstado>
   </soapenv:Body>
</soapenv:Envelope>';

    escribefichero($cod_demanda."_resp", $response);

//y obtenemos respuesta
#############################################################
# Descomentar esta linea en cuanto asisa tenga el sistema para probar
#############################################################
   //$s_response = $soapClient->notificarEstado($response);

    //if($estado != 0)
     //   exit("ERROR: código del fallo $estado");
}
 ?>
