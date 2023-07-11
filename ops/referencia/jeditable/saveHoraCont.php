<?php
//Datos para guardar horas
include '../../../functions/function.php';
$jDataHora = explode("-", $_POST['id']);

$jCampoHora  = $jDataHora['0']; //Nombre del campo
$jRhHora     = $jDataHora['1']; //Id del registro (para este caso se necesita la columna de continuado : rh2017/....)
$selecHora   = $jDataHora['2']; //selector de query para la actualización
$jValorHora  = $_POST['value'].":00"; // valor por el cual reemplazar

// Para las horas, se restringe la búsqueda a sólo los servicios que no estan finalizados

if($selecHora == 'sInfo') {
	//Actualiza el registro
	$jAcHora = "UPDATE servicio
			INNER JOIN serinfo ON servicio.idSv = serinfo.idSv
		SET serinfo.$jCampoHora = '$jValorHora'
		WHERE servicio.continuado = '$jRhHora' AND servicio.estServ NOT IN('10', '14')
	";
} elseif($selecHora == 'unico') {
	//Actualiza el registro
	$jAcHora = "UPDATE servicio SET  ".$jCampoHora." = '".$jValorHora."'
								WHERE idSv = '$jRhHora'";	
} else {
	//Actualiza el registro
	$jAcHora = "UPDATE servicio SET  ".$jCampoHora." = '".$jValorHora."'
								WHERE continuado = '$jRhHora'";
}

if(mysqli_query($gestambu,$jAcHora)) {
	echo substr($jValorHora, 0, 5);
} else {
	echo "Error: " . $jAcHora . "<br>" . mysqli_error($gestambu);
}

?>
