<?php
//Datos para estado de servicio sin vuelta
include '../../../functions/function.php';
$jDataVuelo = explode("-", $_POST['id']);

$jCampoVuelo      = $jDataVuelo['0']; //Nombre del campo
$jIdVuelo         = $jDataVuelo['1']; //Id del registro
$jValorVuelo      = $_POST['value']; // valor por el cual reemplazar

//Obtiene el valor de la tabla estados
$vTablaEst = mysqli_query($gestambu, "SELECT idEst, vaEst FROM estados WHERE idEst = '$jValorVuelo'");
$rwTablaEst = mysqli_fetch_assoc($vTablaEst);

// Actualiza la columna estServ de la tabla servicio
$jActserVuelo = "UPDATE vueloref SET  ".$jCampoVuelo." = '$jValorVuelo'
							WHERE idVuelo = '$jIdVuelo' ";

if(mysqli_query($gestambu, $jActserVuelo)) {
	echo $rwTablaEst['vaEst'];
} else {
  echo "Error: " . $vuelorefIns . "<br>" . mysqli_error($gestambu);
}

?>
