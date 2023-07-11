<?php
/* Seleccion de zona local */
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');

/* ACTIVAR ERRORES */
error_reporting(E_ALL);

ini_set('display_errors', '1');
error_reporting(-1);
error_reporting(0);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
/*
function fechaGDB() {
	$fechaTSTP = date_create(date());
	$fechaFTSP = date_format($fechaTSTP, 'Y-m-d H:i:s');
	return $fechaFTSP;
}
*/
$fechaRecep = "15032018";
$horaRecep  = "1223";

function arregloTimeStamp($fechaRecep, $horaRecep) {
	$dia   = substr($fechaRecep, 0,2);
    $mes   = substr($fechaRecep, 2,2);
	$anio  = substr($fechaRecep, 4,7);
	$hora  = substr($horaRecep, 0,2);
    $min   = substr($horaRecep, 2,2);

	$arregloFecha = $anio."-".$mes."-".$dia;
	$arregloHora  = $hora.":".$min.":00";
	
	$arreglo = $arregloFecha." ".$arregloHora;
	return $arreglo;
}

?>