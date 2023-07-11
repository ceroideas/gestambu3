<?php 
//Parametros obligatorios
$colaborador='AANDALUC';
$cod_demanda  = "7102017";
$vuelta       = "N";

//Parametros obligatorios segun el caso
$estado       = "5";
$fecha_estado = "22112017";
$hora_estado  = "1615";
$fecha_realizacion = "22112017";
$hora_realizacion  = "1200";
$pendienteEvolucion ="";
$terminacion='';
$observaciones="";
$diagnostico1 = "";
$diagnostico2  = "";

$soap_notificaciones = "https://ursae.asisa.es/ASISA/Viena/demand/SeguimientoPeticionesWebSOAP";
//$soap_notificaciones = "https://ursaepre.asisa.es/ASISA/Viena/api-demand-proyecto-viena/SeguimientoPeticionesWebSOAP";

$soap_action = "http://www.example.org/SeguimientoPeticionesWeb/notificarEstado";
$soap_ocp_key = "49557f7662f842a394e1dbbcb168afd2";
$soap_api_version = "1.0";
$soap_timeout = 10;
?>