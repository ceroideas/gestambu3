<?php
//Datos para estado de servicio sin vuelta
include '../../functions/function.php';

$valorEntrante = $_POST['valorRecibido'];
$estado        = $_POST['estadoTab'];
$demanda       = $_POST['demanda'];
$cia           = $_POST['cia'];
$recurso       = $_POST['recurso'];
$idvta         = $_POST['idvta'];

$valorNestado = 7; // valor nuevo estado - en camino
$idTabla      = $valorEntrante; // id de tabla serestados

if($estado > 1 && $estado < 3) { // Para servicios id/vta -> modifica estado de ida
  $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
  $vuelta   = "N";
} elseif($estado > 3 && $estado < 6) { // modifica vuelta
  $jActserv = "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'";
  $vuelta   = "S";
} elseif($estado == 11) {
  if($idvta == 1) {
    //No es puede dar esta convinación -> estado: adjudicado; es sólo para servicios de 1 trayecto
  } elseif($idavta == 2) { // servicio sólo ida
    $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
    $vuelta   = "N";
  } elseif($idavta == 3) { // servicio sólo vuelta
    $jActserv = "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'";
    $vuelta   = "S";
  } else { // servicios con idvta vacío
    $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
    $vuelta   = "N";
  }
} else {
  $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
  $vuelta   = "N";
}

if($recurso == 2 || $recurso == 4) {
  $vuelta = "";
} else {
  $vuelta = $vuelta;
}

if(mysqli_query($gestambu, $jActserv)) {
  $jActservActu = "Acutalizado";
  /* Notificación para asisa */
  #Agregada HLA -> 103
  
  if(($cia == 1 || $cia == 103) && $demanda != 0) { 
	if(empty($vuelta)) {
	    $sqlAsisa = mysqli_query($gestambu, "SELECT asisaasistencia.cod_demanda, asisaasistencia.vuelta, asisaasistencia.fecha_asistencia, asisaasistencia.hora_asistencia, asisademanda.cod_demanda
		FROM asisaasistencia
        LEFT JOIN asisademanda ON asisaasistencia.cod_demanda = asisademanda.cod_demanda
		WHERE asisaasistencia.cod_demanda = '$demanda'");
	} else {
	    $sqlAsisa = mysqli_query($gestambu, "SELECT asisaasistencia.cod_demanda, asisaasistencia.vuelta, asisaasistencia.fecha_asistencia, asisaasistencia.hora_asistencia, asisademanda.cod_demanda
		FROM asisaasistencia
        LEFT JOIN asisademanda ON asisaasistencia.cod_demanda = asisademanda.cod_demanda
		WHERE asisaasistencia.cod_demanda = '$demanda' AND asisaasistencia.vuelta='$vuelta'");
	}		

    $rwAsisa = mysqli_fetch_assoc($sqlAsisa);

    //Parametros obligatorios
    $colaborador  = 'AANDALUC';
    $cod_demanda  = $rwAsisa['cod_demanda'];
    $vuelta       = $vuelta;

    //Parametros obligatorios según el caso
    $estado             = "3";
    $fecha_estado       = date("dmY");
    $hora_estado        = date("Hi");
    $fecha_realizacion  = date("dmY"); //$rwAsisa['fecha_asistencia'];
	if(empty($rwAsisa['hora_asistencia'])) {
		$hora_realizacion	= date("Hi");
	} else {
		$hora_realizacion   = $rwAsisa['hora_asistencia'];	
	}
    $pendienteEvolucion = "";
    $terminacion        = '';
    $observaciones      = "";
    //$diagnostico1     = "";
    //$diagnostico2     = "";

    include '../../API/noti_est_ambu.php'; // Notificaciones
  }
} else {
  echo "Error: " . $jActserv . "<br>" . mysqli_error($gestambu);
}

 ?>
