<?php
include ("../functions/function.php");
global $estado;

define('CHARSET', 'ISO-8859-1');
define('REPLACE_FLAGS', ENT_COMPAT | ENT_XHTML);

function html($string) {
    return htmlspecialchars($string, REPLACE_FLAGS, CHARSET);
}

function compruebaFecha($fecha){

    if(strlen($fecha) == 8){
        $dia = substr($fecha, 0, 2);
        $mes = substr($fecha, 2, 2);
        $anio = substr($fecha, 4,4);
        if (checkdate($mes, $dia, $anio))
            return true;
        else {
            return false;
        }
    }else return false;
}

function compruebaHora($tiempo){
    if(strlen($tiempo) == 4){
        $hora = substr($tiempo, 0, 2);
        if(strcmp($hora, '23') > 0 ) return false;
        $minuto = substr($tiempo, 2, 2);
        if(strcmp($minuto, '59') > 0 ) return false;
        return true;
    }else return false;
}

function compruebaDemanda($demanda) {
    global $result, $estado;
        
    //Comprobamos otros problemas
    if ((isset($demanda['cod_demanda']) && (strlen($demanda['cod_demanda']) > 12 ||
         !is_numeric($demanda['cod_demanda']))))
        $estado = "-16";
    else if ((isset($demanda['telefono1']) && (strlen($demanda['telefono1']) > 9 || 
        !is_numeric($demanda['telefono1']))))
        $estado = "-24";
    else if ((isset($demanda['telefono2']) && !is_numeric($demanda['telefono2'])))
        $estado = "-25";
    else if ((isset($demanda['tipo_servicio']) && (strlen($demanda['tipo_servicio']) > 1 || 
        !in_array($demanda['tipo_servicio'], array('U', 'P')))))
        $estado = "-28";
    else if ((isset($demanda['prioridad']) && (strlen($demanda['prioridad']) > 1 || 
        !in_array($demanda['prioridad'], array('1', '2', '3', '4', '5')))))
        $estado = "-33";
    else if ((isset($demanda['poblacion_origen']) && strlen($demanda['poblacion_origen']) > 5))
        $estado = "-37";
    else if ((isset($demanda['fecha_peticion']) && !compruebaFecha($demanda['fecha_peticion'])))
        $estado = "-17";
    else if ((isset($demanda['hora_peticion']) && !compruebaHora($demanda['hora_peticion'])))
        $estado = "-18";

    //Si es un traslado
    if ($demanda['cod_servicio'] >= 70) {
        if ((!isset($demanda['tipo_vehiculo'])) || 
            (isset($demanda['tipo_vehiculo']) && $demanda['tipo_vehiculo'] == "") || 
            (isset($demanda['tipo_vehiculo']) && (strlen($demanda['tipo_vehiculo']) > 1 ||
             !in_array($demanda['tipo_vehiculo'], array('1', '2')))))
            $estado = "-29";
        else if ((!isset($demanda['amb_psiquiatrica'])) || 
            (isset($demanda['amb_psiquiatrica']) && $demanda['amb_psiquiatrica'] == "") || 
            (isset($demanda['amb_psiquiatrica']) && (strlen($demanda['amb_psiquiatrica']) > 1 || 
            !in_array($demanda['amb_psiquiatrica'], array('S', 'N')))))
            $estado = "-30";
        else if ((!isset($demanda['poblacion_destino'])) || 
            (isset($demanda['poblacion_destino']) && $demanda['poblacion_destino'] == "") || 
            (isset($demanda['poblacion_destino']) && strlen($demanda['poblacion_destino']) > 5))
            $estado = "-38";
        else if ((!isset($demanda['poblacion_destino_nombre'])) || 
            (isset($demanda['poblacion_destino_nombre']) && $demanda['poblacion_destino_nombre'] == ""))
            $estado = "-14";
    }

    if($estado!="0")return false;
    else return true;        
}

//Funcion que selecciona el codigo de error según el campo que está vacio y envia el estado
function compruebaAsistencia($asistencia) {
    global $objdemanda, $result, $estado;
    
    //Si es un traslado
    if($objdemanda['cod_servicio'] >= 70){
        if( (isset($asistencia['vuelta']) && (strlen($asistencia['vuelta']) > 1 ||
         !in_array($asistencia['vuelta'],array('S','N')))))
            $estado = "-32"; 
    }
    //Comprobamos otros problemas
    if( (isset($asistencia['num_asistencia']) && (strlen($asistencia['num_asistencia']) > 5 || 
        !is_numeric($asistencia['num_asistencia']))))
       $estado = "-43";
       
    if( (isset($asistencia['fecha_asistencia']) && !compruebaFecha($asistencia['fecha_asistencia']) ))
       $estado = "-17";
    
    if( (isset($asistencia['hora_asistencia']) && !compruebaHora($asistencia['hora_asistencia']) ))
       $estado = "-18"; 
    
    if($estado!="0")return false;
    else return true;
}

/* Introducir nueva demanda */
function insertarDemanda($sqlcampo,$sqlvalor) {
    global $gestambu, $result, $estado;

    $sql= "insert into gestambu3.asisademanda $sqlcampo \n values $sqlvalor;";
//    $result.= "\n $sql \n";
//    $gestambu->query("SET NAMES 'utf8'");
    if(!mysqli_query($gestambu, $sql)) $estado="50";
    else return true;
}

/* Introducir nueva asistencia */
function insertarAsistencia($sqlcompleta) {
    global $gestambu, $result, $estado;

    //result.= "\nrecibo:\n $sqlcompleta \n";
//    $gestambu->query("SET NAMES 'utf8'");
    if(!mysqli_multi_query ($gestambu, $sqlcompleta)){
         $estado="-50";
    }
    else return true;
}


?>
