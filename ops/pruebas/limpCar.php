<?php

/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
 error_reporting(E_ALL);

ini_set('display_errors', '1');
error_reporting(-1);
error_reporting(0);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

function sanear_string($string) {
 
    $string = trim($string);
 
    //Cambia los valores # por Ñ -> reconocido por Asisa
	# Problema con la DB de Asisa y la DB de Asisa24h (RAD)
    $string = str_replace('#', 'Ñ', $string);
    return $string;
}

 
?>


