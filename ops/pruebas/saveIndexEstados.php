<?php
session_start();
//Datos para estado de servicio sin vuelta
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del registro
$jValor      = $_POST['value']; // valor por el cual reemplazar

//Obtiene el estado anterior del servicio
$estAnterior = mysqli_query($gestambu, "SELECT idSv, estServ FROM servicio WHERE idSv='$jId'");
$rwEstAnt    = mysqli_fetch_assoc($estAnterior);
$estadoAnt   = $rwEstAnt['estServ'];

//Obtiene el valor de la tabla estados
$vTablaEst = mysqli_query($gestambu, "SELECT idEst, vaEst FROM estados WHERE idEst = '$jValor'");
$rwTablaEst = mysqli_fetch_assoc($vTablaEst);

// Actualiza la columna estServ de la tabla servicio
$jActserv = mysqli_query($gestambu, "UPDATE servicio SET  ".$jCampo." = '".$jValor."'
							WHERE idSv = ".$jId." ");

/* Mensajes de log */
$obsText = ": ".$rwTablaEst['vaEst'];
$usuario = $_SESSION['userId'];
guardarLog('12', $usuario, $obsText, $jId);

echo $rwTablaEst['vaEst'];
/* Notificación cuando es anulado */
# Al anular un servicio se envia notificación al vehículo
if($jValor == '15') {
	$sqlNota = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.tipo, servicio.idvta, servicio.nombre, servicio.recoger, servicio.locRec,
		servi.idServi, servi.nomSer, serestados.idSv, serestados.vhIda, serestados.vhVta
		FROM servicio
			LEFT JOIN servi ON servicio.tipo = servi.idServi
			LEFT JOIN serestados ON servicio.idSv = serestados.idSv
		WHERE servicio.idSv = '$jId'");
	$rwNota  = mysqli_fetch_assoc($sqlNota);

	if($rwNota['idvta'] =='1') { //idvta
		if($estadoAnt == '2') {
			$ambuNota = $rwNota['vhIda'];
		} elseif ($estadoAnt == '5') {
			$ambuNota = $rwNota['vhVta'];
		}
	} elseif ($rwNota['idvta'] =='2') { // solo ida
		$ambuNota = $rwNota['vhIda'];
	} elseif ($rwNota['idvta'] =='3') { // solo vuelta
		$ambuNota = $rwNota['vhVta'];
	} else { // servicio único
		$ambuNota = $rwNota['vhIda'];
	}
	
	$creadoTime = fechaGDB();
	
	if($ambuNota != '0') {
		$sistNota = "El servicio: ".$rwNota['nomSer']." - ".$rwNota['nombre']." recoger en ".$rwNota['recoger']." ".$rwNota['locRec']." .Ha sido ANULADO.";
		$inNotaSist = mysqli_query($gestambu, "INSERT INTO notas (descNota, vhId, userId, creado) VALUES ('$sistNota', '$ambuNota', '1', '$creadoTime') ");	
	}

}

 ?>
