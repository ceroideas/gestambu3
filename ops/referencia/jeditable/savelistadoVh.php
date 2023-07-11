<?php
session_start();
//Datos para estado de servicio sin vuelta
include '../../../functions/function.php';
$jDataVh = explode("-", $_POST['id']);

$jCampoVh      = $jDataVh['0']; //Nombre del campo
$jIdVh         = $jDataVh['1']; //Id del registro
$jValorVh      = $_POST['value']; // valor por el cual reemplazar

//Vehículo anterior
$vhAnterior = mysqli_query($gestambu, "SELECT idSv, ".$jCampoVh." FROM serestados WHERE idSv = '$jIdVh'");
$rwAnterior = mysqli_fetch_assoc($vhAnterior);
$verEstado  = mysqli_query($gestambu, "SELECT idSv, estServ FROM servicio WHERE idSv= '$jIdVh' ");
$rwVerEst   = mysqli_fetch_assoc($verEstado);
$vEstComp   = $rwVerEst['estServ'];

if($jCampoVh == 'vhIda') {
	$ambAnt = $rwAnterior['vhIda'];
	$modIV  = "estTec"; //ida
	$verIV  = "2";
} elseif($jCampoVh == 'vhVta') {
	$ambAnt = $rwAnterior['vhVta'];
	$modIV  = "estTecVta"; //vta
	$verIV  = "3";
}

//Obtiene el valor de la tabla estados
$vTablaVh = mysqli_query($gestambu, "SELECT idVh, matricula FROM vehiculo WHERE idVh = '$jValorVh'");
$rwTablaVh = mysqli_fetch_assoc($vTablaVh);

// Actualiza la columna estServ de la tabla servicio
$jActserVh = mysqli_query($gestambu, "UPDATE serestados SET  ".$jCampoVh." = '".$jValorVh."'
							WHERE idSv = ".$jIdVh." ");
/* Mensajes de log */
$obsText = ": ".$rwTablaVh['matricula'];
$usuario = $_SESSION['userId'];
guardarLog('10', $usuario, $obsText, $jIdVh);

echo $rwTablaVh['matricula'];

###############################
/* Modifica estado del técnico */
# Cuando cambia el vehículo
# Modifica el estado del vehículo asignado
# Sólo cuando el servicio está adjudicado
if($vEstComp == '2' || $vEstComp =='5' || $vEstComp =='11') {
	if($jValorVh == $ambAnt) {
	 //iguales
	} else {
		if($ambAnt !='0') { //No guarda nota cuando no hay vehículo asignado
			//distintas
			//Modifica el estado del técnico según sea ida o vuelta a estado 9
			$modAdj = mysqli_query($gestambu, "UPDATE serestados SET ".$modIV." = '9' WHERE idSv = ".$jIdVh." ");
			//Modifica horarios de serhorario
			/*
			if($verIV == "2") {
				$modHorario = mysqli_query($gestambu, "UPDATE serhorario SET idReco IS NULL WHERE, idFin IS NULL WHERE idSv = '$jIdVh' ");
			} elseif($verIV == "3") {
				$modHorario = mysqli_query($gestambu, "UPDATE serhorario SET vtaReco IS NULL, vtaFin IS NULL WHERE idSv = '$jIdVh' ");
			}
			*/
			/* Crea nota para tecnico */
			$sqlNota = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.tipo, servicio.nombre, servicio.recoger, servicio.locRec,
				servi.idServi, servi.nomSer
				FROM servicio
					LEFT JOIN servi ON servicio.tipo = servi.idServi
				WHERE idSv = '$jIdVh'");
			$rwNota  = mysqli_fetch_assoc($sqlNota);
			
			$creadoTime = fechaGDB();
			
			if($ambAnt != '0') {
				$sistNota = "El servicio: ".$rwNota['nomSer']." - ".$rwNota['nombre']." recoger en ".$rwNota['recoger']." ".$rwNota['locRec']." .Ha sido asignado a otro vehículo.";
				$inNotaSist = mysqli_query($gestambu, "INSERT INTO notas (descNota, vhId, userId, creado) VALUES ('$sistNota', '$ambAnt', '1', '$creadoTime') ");
			}
		}
	}
}


 ?>
