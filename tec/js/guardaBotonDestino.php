<?php

include '../../functions/function.php';

$valorEntrante = $_POST['valorRecibido'];
$idSv          = $_POST['iden'];
$estado        = $_POST['estadoTab'];
$idVTab        = $_POST['idvTab'];
$demanda       = $_POST['demanda'];
$cia           = $_POST['cia'];
$recurso       = $_POST['recurso'];

$valorNestado = 12; // valor nuevo estado
$idTabla      = $valorEntrante;  // id de tabla serestados

/* Horario */
$compHora = mysqli_query($gestambu, "SELECT idRefSv, horaId, idReco FROM serhorario WHERE idRefSv='$idSv'");
$numCompHora = mysqli_num_rows($compHora);

$ahora = date('H:i:s');

if($estado > 1 && $estado < 3) { // Para servicios id/vta -> modifica estado de ida
	if($numCompHora == '0') {
		$sqlInser = "INSERT INTO serhorario (idRefSv, idReco) VALUES ('$idSv', '$ahora')";
	} elseif($numCompHora == '1') {
		$sqlInser = "UPDATE serhorario SET idReco = '$ahora' WHERE idRefSv = '$idSv'";
	} else {
		//No hace nada
	}
	$vuelta = "N";
	$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
} elseif($estado > 3 && $estado < 6) { // modifica vuelta
	if($numCompHora == '0') {
		$sqlInser = "INSERT INTO serhorario (idRefSv, vtaReco) VALUES ('$idSv', '$ahora')";
	} elseif($numCompHora == '1') {
		$sqlInser = "UPDATE serhorario SET vtaReco = '$ahora' WHERE idRefSv = '$idSv'";
	} else {
		//No hace nada
	}
	$vuelta = "S";
	$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'");
} elseif($estado == 11) { // Para servicios de solo ida o solo vuelta, guarda los horarios en su columna correspondiente
	if($idVTab == 2) { // solo ida o que no sea un servicio de ida y vuelta, por ejemplo una urgencia
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, idReco) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET idReco = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "N";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
	} elseif($idVTab == 3) { // solo vuelta
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, vtaReco) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET vtaReco = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "S";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'");
	} elseif(empty($idVtab) || $idVtab == 0) {
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, idReco) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET idReco = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "N";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
	} else {
		//Con estado 11 no se admite un estado idvta 1, solo admite 2 o 3
	}
} else {
	if($idVTab == 2) { // solo ida
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, idReco) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET idReco = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "N";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
	} elseif($idVTab == 3) { // solo vuelta
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, vtaReco) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET vtaReco = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "S";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'");
	} else {
		//Con estado 11 no se admite un estado idvta 1, solo admite 2 o 3
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
	}
}

if($recurso == 2 || $recurso == 4) {
  $vuelta = "";
} else {
  $vuelta = $vuelta;
}

if(mysqli_query($gestambu, $sqlInser)) {

	/* Notificación para asisa */

  if(($cia == 1 || $cia == 103) && $demanda != 0) {
		 //echo "marcada asisa notificacion";
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
		 $estado             = "4";
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
		 $diagnostico1       = "";
		 $diagnostico2       = "";

		 include '../../API/noti_est_ambu.php'; // Notificaciones
  }
} else {
  echo "Error: " . $sqlInser . "<br>" . mysqli_error($gestambu);
}
 ?>
