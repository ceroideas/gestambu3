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
	$volverEstado = 0;
	$notiEncamino = 1;
	$estadoSO = 3;
	$hora_estadoSO = $hora_estado;
	$hora_realizacionSO = $hora_realizacion;
	$estadoCA = 3;
	$hora_estadoCA = $hora_estado;
	$hora_realizacionCA = $hora_realizacion;
}

/* Modificación de archivo notificar estado ambulancia */
# Sólamente notifica cuando se ha dado la hora de llegada
# El estado "en camino" se ha de notificar antes que la hora de llegada
if (!empty($cod_demanda)){
	if($volverEstado == 1) {
		curl_soap::callViena($cod_demanda, $estadoSO, $vuelta, $fecha_estado, $hora_estadoSO, $fecha_realizacion, $hora_realizacionSO, $diagnostico1, $diagnostico2, $observaciones);
	} else {
		if($notiEncamino == "1") {
			curl_soap::callViena($cod_demanda, $estadoCA, $vuelta, $fecha_estado, $hora_estadoCA, $fecha_realizacion, $hora_realizacionCA, $diagnostico1, $diagnostico2, $observaciones);
		}
		
		curl_soap::callViena($cod_demanda, $estado, $vuelta, $fecha_estado, $hora_estado, $fecha_realizacion, $hora_realizacion, $diagnostico1, $diagnostico2, $observaciones);
	}
} else {
	echo "ERROR - No hay código de demanda";
}

?>