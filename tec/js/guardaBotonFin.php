<?php
//Datos para estado de servicio sin vuelta
include '../../functions/function.php';

$valorEntrante = $_POST['valorRecibido'];
$idSv          = $_POST['iden'];
$estado        = $_POST['estadoTab'];
$idVTab        = $_POST['idvTab'];
$demanda       = $_POST['demanda'];
$cia           = $_POST['cia'];
$recurso       = $_POST['recurso'];
if($recurso == '4') {
	$diag = $_POST['diagnostico'];
}

$valorNestado = 13; // valor nuevo estado
$idTabla      = $valorEntrante; // valor nuevo estado

/* Horario */
$compHora = mysqli_query($gestambu, "SELECT idRefSv, horaId, idFin FROM serhorario WHERE idRefSv='$idSv'");
$numCompHora = mysqli_num_rows($compHora);

$ahora = date('H:i:s');

if($estado > 1 && $estado < 3) { // Estado para ida
	if($numCompHora == '0') {
		$sqlInser = "INSERT INTO serhorario (idRefSv, idFin) VALUES ('$idSv', '$ahora')";
	} elseif($numCompHora == '1') {
		$sqlInser = "UPDATE serhorario SET idFin = '$ahora' WHERE idRefSv = '$idSv'";
	} else {
		//No hace nada
	}
	$vuelta = "N";
	$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
} elseif($estado > 3 && $estado < 6) { // estado para la vuelta
	if($numCompHora == '0') {
		$sqlInser = "INSERT INTO serhorario (idRefSv, vtaFin) VALUES ('$idSv', '$ahora')";
	} elseif($numCompHora == '1') {
		$sqlInser = "UPDATE serhorario SET vtaFin = '$ahora' WHERE idRefSv = '$idSv'";
	} else {
		//No hace nada
	}
	$vuelta = "S";
	$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'");
} elseif($estado == 11) {
	if($idVTab == 2 || empty($idVtab)) { // Sólo ida - o servicios de sólo ida, como urgencias
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, idFin) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET idFin = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "N";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
	} elseif($idVTab == 3) { // Sólo vuelta
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, vtaFin) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET vtaFin = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "S";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'");
	} elseif(empty($idVtab)) {
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
	if($idVTab == 2) { // Sólo ida
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, idFin) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET idFin = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "N";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'");
	} elseif($idVTab == 3) { // Sólo vuelta
		if($numCompHora == '0') {
			$sqlInser = "INSERT INTO serhorario (idRefSv, vtaFin) VALUES ('$idSv', '$ahora')";
		} elseif($numCompHora == '1') {
			$sqlInser = "UPDATE serhorario SET vtaFin = '$ahora' WHERE idRefSv = '$idSv'";
		} else {
			//No hace nada
		}
		$vuelta = "S";
		$jActserv = mysqli_query($gestambu, "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'");
	} else {
		//Con estado 11 no se admite un estado idvta 1, solo admite 2 o 3
	}
}

if($recurso == 2 || $recurso == 4) {
  $vuelta = "";
} else {
  $vuelta = $vuelta;
}

if(mysqli_query($gestambu, $sqlInser)) {
  $jActservActu = "Acutalizado";
	/* Notificación para asisa */

  if(($cia == 1 || $cia == 103) && (!empty($demanda))) {
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
		$cod_demanda  = $demanda;


		//Parametros obligatorios según el caso
		$estado             = "5"; // Fin de servicio
		$fecha_estado       = date("dmY");
		$hora_estado        = date("Hi");
		$fecha_realizacion  = date("dmY"); //$rwAsisa['fecha_asistencia'];
		 if(empty($rwAsisa['hora_asistencia'])) {
		    $hora_realizacion	= date("Hi");
		 } else {
			$hora_realizacion   = $rwAsisa['hora_asistencia'];	
		 }
		
		echo $fecha_realizacion;
		//Servicios médicos
		if($recurso == '4') {
			$pendienteEvolucion = ""; //Opcional -> sólo visitas médicas -> S / N
			$terminacion        = '1'; //Opcional -> incidencias
			$observaciones      = ""; //Opcional
			$diagnostico1       = $diag;
			$diagnostico2       = ""; //Opcional
			$vuelta             = "";
	
		} else {
			$pendienteEvolucion = "";
			$terminacion        = '';
			$observaciones      = "";
			$diagnostico1       = "";
			$diagnostico2       = "";
			$vuelta       		= $vuelta;			
		}

		include '../../API/noti_est_ambu.php'; // Notificaciones
  }
} else {
  echo "Error: " . $sqlInser . "<br>" . mysqli_error($gestambu);
}
 ?>
