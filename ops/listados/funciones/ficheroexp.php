<?php
date_default_timezone_set('Europe/Madrid');

header("Content-type: application/vnd.ms-excel; name='excel'; charset = iso-8859-1");
header("Content-Disposition: filename=exportDatos".date('d-m-y').".xls");
header("Pragma: no-cache");
header("Expires: 0");

//Decodificar para acentos y caracteres especiales

$tabla=$_POST['datos_a_enviar'];
$tabla=utf8_decode($tabla);
echo $tabla;

?>