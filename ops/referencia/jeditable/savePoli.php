<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del paciente
$jValor      = $_POST['value']; // valor por el cual reemplazar

//Actualiza el registro
$jActPoli = "UPDATE servicio SET ".$jCampo." = '".$jValor."' WHERE idPac = ".$jId." ";

if(mysqli_query($gestambu,$jActPoli)) {
	echo $jValor;
	// Actualiza paciente
	$jActPoliPac = mysqli_query($gestambu, "UPDATE paciente SET ".$jCampo." = '".$jValor."' WHERE idPac = ".$jId." ");

	/* Mensajes de log */
	/*
	$obsText = $jCampo." : ".$jValor;
	$usuario = $_SESSION['userId'];
	guardarLog('22', $usuario, $obsText, $jId);
	*/
} else {
	echo "Error: " . $jActPoli . "<br>" . mysqli_error($gestambu);
}
