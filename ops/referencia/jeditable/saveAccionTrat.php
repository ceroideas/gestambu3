<?php
session_start();

//Datos para estado de servicio sin vuelta
include '../../../functions/function.php';
$jDataAcc = explode("-", $_POST['id']);

$jCampoAcc = $jDataAcc['0']; //Nombre del campo
$jIdAcc    = $jDataAcc['1']; //Id del registro
$jValorAcc = $_POST['value']; // valor por el cual reemplazar

if($jValorAcc == 0 ) {
	//No hace nada
} elseif($jValorAcc == 1 ) {
	//Suspende el tratamiento - solamente para los servicios = pendiente
	$textAccion = "Servicio en suspenso";
	// Actualiza la columna estServ de la tabla servicio
	$jActserAcc = mysqli_query($gestambu, "UPDATE servicio SET  ".$jCampoAcc." = '16'
								WHERE continuado = '$jIdAcc' AND estServ = '1' ") or die (mysqli_error($gestambu));
	$cambEstCont = mysqli_query($gestambu, "UPDATE refcontinuado SET estCont = '16' WHERE numCont = '$jIdAcc'");

} elseif($jValorAcc == 2) {
	//Reanuda el tratamiento - solamente para los servicios = en suspenso
	$textAccion = "Reanuadar tratameinto";
	// Actualiza la columna estServ de la tabla servicio
	$jActserAcc = mysqli_query($gestambu, "UPDATE servicio SET  ".$jCampoAcc." = '1'
								WHERE continuado = '$jIdAcc' AND estServ = '16' ") or die (mysqli_error($gestambu));
	$cambEstCont = mysqli_query($gestambu, "UPDATE refcontinuado SET estCont = '1' WHERE numCont = '$jIdAcc'");

} elseif($jValorAcc == 3) {
	//Finaliza tratamiento.
	$textAccion = "Finaliza tratamiento";
	// Actualiza la columna estServ de la tabla servicio
	$jActserAcc = mysqli_query($gestambu, "UPDATE servicio SET  ".$jCampoAcc." = '15'
								WHERE continuado = '$jIdAcc' AND estServ = '1' ") or die (mysqli_error($gestambu));
	$cambEstCont = mysqli_query($gestambu, "UPDATE refcontinuado SET estCont = '14' WHERE numCont = '$jIdAcc'");
} elseif($jValorAcc == 4) {
	//Anula un tratamiento
	//La unica diferencia entre 3 y 4 es la forma en que se guarda en la tabla de referencia al continuado
	$textAccion = "Servicio anulado";
	// Actualiza la columna estServ de la tabla servicio
	$jActserAcc = mysqli_query($gestambu, "UPDATE servicio SET  ".$jCampoAcc." = '15'
								WHERE continuado = '$jIdAcc' AND estServ = '1' ") or die (mysqli_error($gestambu));
	$cambEstCont = mysqli_query($gestambu, "UPDATE refcontinuado SET estCont = '15' WHERE numCont = '$jIdAcc'");
}
/* Mensajes de log */
$obsText = $jCampoAcc." modificado a: ".$textAccion;
$usuario = $_SESSION['userId'];
guardarLogCont('16', $usuario, $obsText, $jIdAcc);
	
if($jValorAcc == 1 ) {
	echo "Servicio en Suspenso";
} elseif ($jValorAcc == 2) {
	echo "Servicio Activo";
} elseif($jValorAcc == 3) {
	echo "Servicio Finalizado";
}

?>
