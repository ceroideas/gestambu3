<?php
//notificar_estado('2102017','5',,,'Todo bien');

//Funcion para enviar el estado de la peticion de asistencia
function notificar_estado($cod_demanda, $estado){
global $diagnostico1, $fecha_estado, $hora_estado, $diagnostico, $fecha_realiz;

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

function escribefichero($incidencia, $xml){
    //echo "llega $incidencia, $xml";

    $file = __DIR__ ."/".date("Y-m-d")."_".$incidencia.".log";

    if (($arch = fopen($file, "a+")) !== false) {
        fwrite($arch, "$xml\n");
        fclose($arch);
    }
}
?>
<?php
$diagnostico1 = 1;
$fecha_estado = "10102017";
$hora_estado = "1000";
$diagnostico = 3;
$fecha_realiz = "10102017";
notificar_estado('1002', '3');
 ?>
