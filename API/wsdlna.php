<?php
$estado = $cod_demanda = "0";
$result="";
$estadoAsistencia = $estadoDemanda = 0;

include ('check_demanda.php');
include ('SOAPclient.php');

class demanda {
    public function AltaSolititud($solicitud) {
        global $estado, $estadoAsistencia, $estadoDemanda, $result;

        $demanda = $solicitud -> demanda;
        $cod_demanda = $demanda -> COD_DEMANDA;

        ob_start();
        var_dump($solicitud);
        $archivo = ob_get_clean();

        $sqlcampo = $sqlvalor = "(";

        foreach ($demanda as $clave => $valor) {
            $fallo = false;
            $campo = strtolower($clave);

            if ($campo=="telefono1") $campo="telefono_contacto1";
            if ($campo=="telefono2") $campo="telefono_contacto2";

	    $valor = addslashes($valor);

//	    $result = $result ."#". $campo .":". $valor ."\n";

            $objdemanda[$campo] = $valor;

            if ( empty($valor)){
                switch ($campo) {
                   case "cod_demanda" :
                       $estado = "-1";
                       break;
                    case "fecha_peticion" :
                        $estado = "-2";
                        break;
                    case "hora_peticion" :
                        $estado = "-3";
                        break;
                    case "apellido1" :
                        $estado = "-4";
                        break;
                    case "nombre" :
                        $estado = "-5";
                        break;
                    case "telefono_contacto1" :
                        $estado = "-6";
                        break;
                    case "motivo" :
                        $estado = "-7";
                        break;
                    case "tipo_servicio" :
                        $estado = "-8";
                        break;
                    case "prioridad" :
                        $estado = "-11";
                        break;
                    case "direccion_origen" :
                        $estado = "-12";
                        break;
                    case "poblacion_origen" :
                        $estado = "-13";
                        break;
                }// */
                break;
            }
            $sqlcampo.="$campo, ";
            $sqlvalor.="'$valor', ";
        }

        if (compruebaDemanda($objdemanda)) {
            $estadoDemanda = "true";
            $sqlcampo = substr($sqlcampo, 0,-2);
            $sqlcampo.=",nuevo)";
            $sqlvalor = substr($sqlvalor, 0,-2);
            $sqlvalor.=",'1')";
        }

        foreach ($solicitud->Asistencia as $asistencia) {

            if (is_object($asistencia)) { //solo tenemos 1 asistencia
                $sqlcampoasis = "insert into gestambu3.asisaasistencia("; 
                $sqlvalorasis = "values(";

                foreach ($asistencia as $clave => $valor) {
                    $fallo = false;
                    $campo = strtolower($clave);
                    $objasistencia[$campo] = $valor;
                    $sqlcampoasis.="$campo, ";
                    $sqlvalorasis.="'$valor', ";
                }
                /*$Asistencia = $asistencia -> NUM_ASISTENCIA;
                $Fec_Asist = $asistencia -> FECHA_ASISTENCIA;
                $Hora_Asist = $asistencia -> HORA_ASISTENCIA;
                $Vuelt_Asist = $asistencia -> VUELTA_ASISTENCIA;
                $Status_Asist = $asistencia -> ESTADO_ASISTENCIA;*/

                if (compruebaAsistencia($objasistencia)){
                    $estadoAsistencia = "true";
                    $sqlcampoasis = substr($sqlcampoasis, 0,-2);
                    $sqlcampoasis.=", timestamp)";
                    $sqlvalorasis = substr($sqlvalorasis, 0,-2);
                    $sqlvalorasis.=", now());";
                }else{
                    $estado = "No se ha podido validar la demanda";
                }
		/*
                if ($cod_demanda != $objasistencia[cod_demanda]){
                    //$result = $cod_demanda ."#".$objasistencia[cod_demanda];
                    $estado = "-50";
                    $estadoAsistencia = "false";
                }*/
                $sqlAsisCompleta = $sqlcampoasis . $sqlvalorasis;

            }else{ //tenemos un array de asistencias

                $sqlAsisCompleta="";

                foreach ($asistencia as $key) {
                    $sqlcampoasis = "insert into gestambu3.asisaasistencia ("; 
                    $sqlvalorasis = "values (";

                    foreach ($key as $clave => $valor) {
                        $fallo = false;
                        $campo = strtolower($clave);
                        $objasistencia[$campo] = $valor;
                        $sqlcampoasis.="$campo, ";
                        $sqlvalorasis.="'$valor', ";
                    }

                    if (compruebaAsistencia($objasistencia)){
                        $estadoAsistencia = "true";
                    }
                    $sqlcampoasis.="timestamp) ";
                    $sqlvalorasis = substr($sqlvalorasis, 0,-2);
                    $sqlvalorasis.=", now());";

                    $sqlAsisCompleta .= $sqlcampoasis . $sqlvalorasis." \n ";
                }
            }
        }

        if ($estadoAsistencia=="true" && $estadoDemanda=="true"){
            if (insertarDemanda($sqlcampo, $sqlvalor))
	           if ($sqlAsisCompleta!="")
                    insertarAsistencia($sqlAsisCompleta);
        }

        notificar_estado($cod_demanda, $estado);
        escribefichero($cod_demanda, $archivo);

        // *
        //tratamiento de datos
        $objResponse = new stdClass();

        $objResponse -> demanda = $cod_demanda;//.":\n ".$result;
        $objResponse -> estado = $estado;

        //$objResponse->out = $response;
        return $objResponse;// */
    }

}

ini_set("soap.wsdl_cache_enabled", "0");
$options = array(   'uri'           => 'https://gestambu.ambuandalucia.es/API/',
                    'encoding'      => 'utf-8',
                    'soap_version'  => SOAP_1_1,
                    'cache_wsdl'    => WSDL_CACHE_NONE);
$server = new SoapServer("https://gestambu.ambuandalucia.es/API/wsdl/wsdl.xml", $options);
$server -> setClass("demanda");
$server -> handle();
?>
