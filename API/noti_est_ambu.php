<?php

defined("TAB3") or define("TAB3", "\t\t\t");
include_once 'curl_soap.php';

// Datos test
$test = false;
if ($test)  {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$colaborador  = 'AANDALUC';
	$cod_demanda  = "59302020";
	$vuelta       = "N";
	$estado             = "2";
	$fecha_estado       = "22072020";
	$hora_estado        = "2000";
	$fecha_realizacion  = "22072020";
	$hora_realizacion   = "1810";
	$pendienteEvolucion = "";
	$terminacion        = "";
	$observaciones      = "";
	$diagnostico1       = "";
	$diagnostico2       = "";
}

if (!empty($cod_demanda)){
	curl_soap::callViena($cod_demanda, $estado, $vuelta, $fecha_estado, $hora_estado, $fecha_realizacion, $hora_realizacion, $diagnostico1, $diagnostico2, $observaciones);
} else {
	echo "ERROR - No hay código de demanda";
}

?>