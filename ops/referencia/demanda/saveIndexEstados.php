<?php
session_start();
//Datos para estado de servicio sin vuelta
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del registro
$jValor      = $_POST['value']; // valor por el cual reemplazar

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

 ?>
