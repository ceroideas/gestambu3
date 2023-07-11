<?php
$estado = $cod_demanda = "0";
$result="";
$estadoAsistencia = $estadoDemanda = 0;
$asis_field=array('cod_demanda','num_asistencia','fecha_asistencia','hora_asistencia','vuelta','estado');
include ('check_demanda.php');
include ('SOAPclient.php');
class demanda {
    public function AltaSolititud($solicitud) {
        global $estado, $estadoAsistencia, $estadoDemanda, $result,$asis_field;

        $demanda = $solicitud -> demanda;
        $cod_demanda = $demanda -> COD_DEMANDA;

        $idemanda = checkCodDem($cod_demanda); //new nuevo, si no un numero que es el id       
   
        ob_start();
        var_dump($solicitud);
        $archivo = ob_get_clean();

        if($idemanda != "new"){
            $tipo=true;
            $sqlActualizar="";
            $sqldatos="";
        }else{
            $tipo=false;
            $sqlcampo = $sqlvalor = "(";
        }
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
            if($tipo) $sqlActualizar .= "$campo='".$valor."',";
            else{
                $sqlcampo.="$campo, ";
                $sqlvalor.="'$valor', ";
            }
        }
        
        if (compruebaDemanda($objdemanda)) {
            if($tipo){
                $estadoDemanda = "actualizar";
                $sqlActualizar .= "nuevo='2'";
                $sqldatos = "UPDATE gestambu3.asisademanda SET $sqlActualizar where idemanda =$idemanda;";
      
            }else{
                $estadoDemanda = "true";
                $sqlcampo = substr($sqlcampo, 0,-2);
                $sqlcampo.=",nuevo)";
                $sqlvalor = substr($sqlvalor, 0,-2);
                $sqlvalor.=",'1')";

            }
        }

        /*Parte Asistencia */
        
        $registro=checkNumAsis($cod_demanda);//devuelve las asistencias
        if($registro != '0'){
            $nAsis = $registro->num_rows;
        }else $nAsis = '0';

        foreach ($solicitud->Asistencia as $asistencia) {

            if (is_object($asistencia)) { //solo tenemos 1 asistencia
                if ( $nAsis>0 ){
                    $sqlActAsis = "";
                    $nuevaAsistencia=false;
                }else{
                    $nuevaAsistencia=true;
                    $sqlcampoasis = "insert into gestambu3.asisaasistencia("; 
                    $sqlvalorasis = "values(";
                }
                $array_asis_field= array(); //valores en asistencia actual
                foreach ($asistencia as $clave => $valor) {
                    $fallo = false;
                    $campo = strtolower($clave);
                    $objasistencia[$campo] = $valor;

                    if ( $nAsis>0 ){
                        $sqlActAsis .= "$campo='".$valor."',"; 
                        $array_asis_field[] = $campo;
                    }else{                        
                        $sqlcampoasis.="$campo, ";
                        $sqlvalorasis.="'$valor', ";
                    }
                }

                if (compruebaAsistencia($objasistencia)){
                    if ( $nAsis>0 ){
                        $estadoAsistencia = "actualizar";
                        $asis_diff=array_diff($asis_field, $array_asis_field); //valores no presentes en asistencia actual
                        foreach($asis_diff as $diff){
                            $sqlActAsis .= "$diff=NULL,";
                        }
                        $now=date("Y-m-d H:i:s");
                        $sqlActAsis .= "timestamp='".$now."'";
                        $registro_tmp=$registro;
                        while ( $asisActualizar = $registro_tmp->fetch_object() ) {
                            $idasistencia=$asisActualizar->idasistencia;                                                       
                        }

                        $sqlAsistencia = "UPDATE gestambu3.asisaasistencia SET $sqlActAsis where idasistencia = $idasistencia";
                        actualizar($sqlAsistencia);

                    }else{
                        $estadoAsistencia = "true";
                        $sqlcampoasis = substr($sqlcampoasis, 0,-2);
                        $sqlcampoasis.=", timestamp)";
                        $sqlvalorasis = substr($sqlvalorasis, 0,-2);
                        $sqlvalorasis.=", now());";
                    }
                }
                
                if ( $nAsis == 0 ) $sqlAsisCompleta = $sqlcampoasis . $sqlvalorasis;

            }else{ //tenemos un array de asistencias

                $sqlAsisCompleta="";
                $nActual=1;
                
                foreach ($asistencia as $key) {
                    $array_asis_field = array();//valores en asistencia actual
                    if ( $nAsis>0 ){
                        if ( $nActual <= $nAsis ){
                            $sqlActAsis = "";
                            $nuevaAsistencia=false;
                        }else{
                            $nuevaAsistencia=true;
                            $sqlcampoasis = "insert into gestambu3.asisaasistencia(";
                            $sqlvalorasis = "values(";
                        }                       
                    }else{
                        $nuevaAsistencia=true;
                        $sqlcampoasis = "insert into gestambu3.asisaasistencia(";
                        $sqlvalorasis = "values(";
                    }
                    //Preparamos la SQL
                    foreach ($key as $clave => $valor) {
                        $fallo = false;
                        $campo = strtolower($clave);
                        $objasistencia[$campo] = $valor;
                        
                        if (!$nuevaAsistencia){         //actualizar
                            $sqlActAsis .= "$campo='".$valor."',";
                            $array_asis_field[] = $campo;
                        }else{                          //nueva asistencia
                            $sqlcampoasis.="$campo, ";
                            $sqlvalorasis.="'$valor', ";
                        }
                    }

                    if (compruebaAsistencia($objasistencia)){                        
                        $estadoAsistencia = "true";                       
                    }
                   
                    if (!$nuevaAsistencia){
                        $asis_diff=array_diff($array_asis_field,$asis_field); //valores no presentes en asistencia actual
                        foreach($asis_diff as $diff){
                            $sqlActAsis .= "$diff=NULL,";
                        }
                        $now=date("Y-m-d H:i:s");
                        $sqlActAsis .= "timestamp='".$now."'";
                        $i=1;
                        $registro_tmp=$registro;
                        while ( $asisActualizar = $registro_tmp->fetch_object() ) {
                            $idasistencia=$asisActualizar->idasistencia;
                            $idasistencia_tmp.=$idasistencia;
                            break;
//                             if($nActual == $i) break;
//                             else $i++;
                        }
                        
                        $sqlAsistencia = "UPDATE gestambu3.asisaasistencia SET $sqlActAsis where idasistencia = $idasistencia";
                        $sql_tmp .= $sqlAsistencia;
                        actualizar($sqlAsistencia);
                    }else{
                        $sqlcampoasis.="timestamp) ";
                        $sqlvalorasis = substr($sqlvalorasis, 0,-2);
                        $sqlvalorasis.=", now());";                       
                        $sqlAsisCompleta .= $sqlcampoasis . $sqlvalorasis." \n ";
//                         $nuevasAsistencia=true;
                    }
                    $nActual++;
                }
            }
        }

        if ($estadoDemanda=="actualizar"){
            actualizar($sqldatos);    
            if ($nuevaAsistencia)
                insertarAsistencia($sqlAsisCompleta);
        }else if ($estadoAsistencia=="true" && $estadoDemanda=="true"){
            if (insertarDemanda($sqlcampo, $sqlvalor))
	           if ($sqlAsisCompleta!="")
                    insertarAsistencia($sqlAsisCompleta);
        }

//      escribimos en el log de respuesta la notificación
        notificar_estado($cod_demanda, $estado); //PRO
        
//      escribimos en el log el archivo recibido        
        escribefichero($cod_demanda, $archivo);

        //Es el objeto respuesta.
        $objResponse = new stdClass();

        $objResponse -> demanda = $cod_demanda;
        $objResponse -> estado = $estado;

        //$objResponse->out = $response;
        return $objResponse;
    }

}

//Comunicación SOAP
ini_set("soap.wsdl_cache_enabled", "0");
$options = array(   'uri'           => 'http://217.126.31.136/API/',
                    'encoding'      => 'utf-8',
                    'soap_version'  => SOAP_1_1,
                    'cache_wsdl'    => WSDL_CACHE_NONE);
$server = new SoapServer("http://217.126.31.136/API/wsdl/wsdl.xml", $options);
$server -> setClass("demanda");
$server -> handle();
?>
