<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jDataText = explode("-", $_POST['id']);

$jCampoText  = $jDataText['0']; //Nombre del campo
$jRhText     = $jDataText['1']; //Id del registro (para este caso se necesita la columna de continuado : rh2017/....)
$jValorText  = $_POST['value']; // valor por el cual reemplazar

//Actualiza el registro

$jAcText = "UPDATE refcontinuado SET  ".$jCampoText." = '".$jValorText."'
							WHERE numCont = '$jRhText' ";
if(mysqli_query($gestambu,$jAcText)) {
	/* Mensajes de log */
	$obsText = $jCampoText." modificado a: ".$jValorText;
	$usuario = $_SESSION['userId'];
	guardarLogCont('3', $usuario, $obsText, $jRhText);

	echo $jValorText;
} else {
	echo "Error: " . $jAcText . "<br>" . mysqli_error($gestambu);
}

?>
