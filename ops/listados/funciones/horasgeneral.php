<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del registro
$jValor      = $_POST['value']; // valor por el cual reemplazar

//Comprueba si existe registro en tabla serhorario
$compHorarios = mysqli_query($gestambu, "SELECT * FROM serhorario WHERE idRefSv = '$jId' ");
$rwCompHora   = mysqli_fetch_assoc($compHorarios);
$numHorarios  = mysqli_num_rows($compHorarios);

if($numHorarios == '0') {
	//Crea nuevo resgistro
	//El campo de hora es un VARCHAR(5) si se utiliza para cálculos de horas se tendrá que formatear a hora H:i:s
	$valorHora   = "";
	$insHorarios = "INSERT INTO serhorario (idRefSv, ".$jCampo.") VALUES ('$jId', '$jValor') ";
	if(mysqli_query($gestambu,$insHorarios)) {
		echo $jValor;
		/* Mensajes de log */
		$obsText = $jCampo." : ".$jValor;
		$usuario = $_SESSION['userId'];
		guardarLog('11', $usuario, $obsText, $jId);
	} else {
		echo "Error: " . $insHorarios . "<br>" . mysqli_error($gestambu);
	}
} else {
	//Actualiza el registro
	$valorHora = $rwCompHora[$jCampo];
	$jActHora  = "UPDATE serhorario SET  ".$jCampo." = '".$jValor."'
								WHERE idRefSv = ".$jId." ";
	if(mysqli_query($gestambu,$jActHora)) {

		/* Mensajes de log */
		$obsText = $jCampo." : ".$jValor;
		$usuario = $_SESSION['userId'];
		guardarLog('11', $usuario, $obsText, $jId);
		
	} else {
		echo "Error: " . $jActHora . "<br>" . mysqli_error($gestambu);
	}
	echo $jValor;	
}