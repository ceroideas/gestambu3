<!DOCTYPE">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Envio Notificaciones Ambulancias Andalucía</title>
</head>
<body>

<?php

defined("TAB3") or define("TAB3", "\t\t\t");
include_once 'curl_soap.php';

echo "<h2>Notificación manual</h2>\n";
echo "Comenzando notificación manual...<br>";

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

</body>
</html>