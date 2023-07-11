<?php
include ("../functions/function.php");
global $estado;

// define('CHARSET', 'ISO-8859-1');
define('CHARSET', 'UTF-8');

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
        if ($demanda['cod_servicio'] >= 70 && $demanda['cod_servicio'] < 119) {
            if ((!isset($demanda['tipo_vehiculo'])) || 
                (isset($demanda['tipo_vehiculo']) && $demanda['tipo_vehiculo'] == "") || 
                (isset($demanda['tipo_vehiculo']) && !in_array($demanda['tipo_vehiculo'], array('1', '2'))))
                $estado = "-29";
            if ( (isset($demanda['amb_psiquiatrica']) && $demanda['amb_psiquiatrica'] == "") || 
                (isset($demanda['amb_psiquiatrica']) && (strlen($demanda['amb_psiquiatrica']) > 1 || 
                !in_array($demanda['amb_psiquiatrica'], array('S', 'N')))))
                $estado = "-30";
            else if ((isset($demanda['amb_oxigeno']) && $demanda['amb_oxigeno'] == "") ||
                (isset($demanda['amb_oxigeno']) && (strlen($demanda['amb_oxigeno']) > 1 ||
                !in_array($demanda['amb_oxigeno'], array('S', 'N')))))
                $estado = "-30";
            else if ((isset($demanda['amb_rampa']) && $demanda['amb_rampa'] == "") ||
                (isset($demanda['amb_rampa']) && (strlen($demanda['amb_rampa']) > 1 ||
                    !in_array($demanda['amb_rampa'], array('S', 'N')))))
                $estado = "-30";
            else if ((isset($demanda['amb_enfermeria']) && $demanda['amb_enfermeria'] == "") ||
                (isset($demanda['amb_enfermeria']) && (strlen($demanda['amb_enfermeria']) > 1 ||
                    !in_array($demanda['amb_enfermeria'], array('S', 'N')))))
                $estado = "-30";
            else if ((isset($demanda['amb_dostecnicos']) && $demanda['amb_dostecnicos'] == "") ||
                (isset($demanda['amb_dostecnicos']) && (strlen($demanda['amb_dostecnicos']) > 1 ||
                    !in_array($demanda['amb_dostecnicos'], array('S', 'N')))))
                $estado = "-30";
            else if ((isset($demanda['amb_medico']) && $demanda['amb_medico'] == "") ||
                (isset($demanda['amb_medico']) && (strlen($demanda['amb_medico']) > 1 ||
                    !in_array($demanda['amb_medico'], array('S', 'N')))))
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

//Funcion que compruebas si hay errores en la parte de asistencia
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
    
       if( (isset($asistencia['hora_asistencia']) && !compruebaHora($asistencia['hora_asistencia']) &&  $objdemanda['cod_servicio'] >= 70 && $asistencia['vuelta'] == 'N'))
       $estado = "-41"; 
    
    if($estado!="0")return false;
    else return true;
}

/* Introducir nueva demanda */
function insertarDemanda($sqlcampo,$sqlvalor) {
    global $gestambu, $result, $estado;

    $sql= "insert into gestambu3.asisademanda $sqlcampo \n values $sqlvalor;";
//    $gestambu->query("SET NAMES 'utf8'");
    if(!mysqli_query($gestambu, $sql)) $estado="50";
    else return true;
}

/* Comprueba si una existe una demanda con un determinado cod_demanda*/
function checkCodDem($cd) {
    global $gestambu, $estado;
//     $gestambu->query("SET NAMES 'utf8'");
    $sql= "SELECT * FROM gestambu3.asisademanda WHERE cod_demanda='".$cd."';";
    if ($datos = $gestambu->query($sql)){
        if ( $datos->num_rows>0 ) {
            while ( $registro = $datos->fetch_object() ) {
                $id=$registro->idemanda;
            }
            return $id;
        }else return "new";
    }else $estado="50";
}

/*Actualizamos la demanda o la asistencia*/
function actualizar($sqlActualizar){
    global $gestambu;
//     $gestambu->query("SET NAMES 'utf8'");
    if(!mysqli_query($gestambu, $sqlActualizar)){
        if($estado == "0") $estado="50";
    }
    else return true;
}

/* comprobar si la asistencia ya existe a partir de un cod_demanda*/
function checkNumAsis($cd) {
    global $gestambu, $estado;
//     $gestambu->query("SET NAMES 'utf8'");
    $sql= "SELECT * FROM gestambu3.asisaasistencia WHERE cod_demanda='".$cd."';";
    if ($datos = $gestambu->query($sql)){        
        if ( $datos->num_rows>0 ) {
            return $datos;
        }else return "0";
    }else{
        if($estado == "0") $estado="50";
    }
  
}

/* Introducir nueva asistencia */
function insertarAsistencia($sqlcompleta) {
    global $gestambu, $result, $estado;
//     $gestambu->query("SET NAMES 'utf8'");
    if(!mysqli_multi_query ($gestambu, $sqlcompleta)){
        if($estado == "0") $estado="-50";
    }
    else return true;
}


?>
