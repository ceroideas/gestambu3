<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del registro
$jValor      = $_POST['value']; // valor por el cual reemplazar

//Actualiza el registro
$jActHora = "UPDATE servicio SET ".$jCampo." = '".$jValor."' WHERE idSv = ".$jId." ";

if(mysqli_query($gestambu,$jActHora)) {
	echo $jValor;
	/* Mensajes de log */
	$obsText = $jCampo." : ".$jValor;
	$usuario = $_SESSION['userId'];
	guardarLogCont('22', $usuario, $obsText, $jId);

} else {
	echo "Error: " . $jActHora . "<br>" . mysqli_error($gestambu);
}