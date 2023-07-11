<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del registro
$idSvTab	 = $jData['2']; //Id tabla servicios
$jValor      = $_POST['value']; // valor por el cual reemplazar

//Comprueba si existe registro en tabla serhorario
$compHorarios = mysqli_query($gestambu, "SELECT * FROM factura WHERE idFac = '$jId' ");
$rwCompHora   = mysqli_fetch_assoc($compHorarios);
$numHorarios  = mysqli_num_rows($compHorarios);

if($numHorarios == '0') {
	//Crea nuevo resgistro
	//El campo de hora es un VARCHAR(5) si se utiliza para cálculos de horas se tendrá que formatear a hora H:i:s
	$valorHora   = "";
	$insHorarios = "INSERT INTO factura (idSvTab, ".$jCampo.") VALUES ('$idSvTab', '$jValor') ";
	if(mysqli_query($gestambu,$insHorarios)) {
		/* Mensajes de log */
		$obsText = "Creado ".$jCampo." : ".$jValor;
		$usuario = $_SESSION['userId'];
		guardarLog('25', $usuario, $obsText, $idSvTab);
	} else {
		echo "Error: " . $insHorarios . "<br>" . mysqli_error($gestambu);
	}
	echo $jValor;
} else {
	//Actualiza el registro
	$valorHora = $rwCompHora[$jCampo];
	$jActHora  = "UPDATE factura SET  ".$jCampo." = '".$jValor."'
								WHERE idFac = ".$jId." ";
	if(mysqli_query($gestambu,$jActHora)) {
		/* Mensajes de log */
		$obsText = "Modificado ".$jCampo." : ".$jValor;
		$usuario = $_SESSION['userId'];
		guardarLog('25', $usuario, $obsText, $idSvTab);
		
	} else {
		echo "Error: " . $jActHora . "<br>" . mysqli_error($gestambu);
	}
	echo $jValor;	
}