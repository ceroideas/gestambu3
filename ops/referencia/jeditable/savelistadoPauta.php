<?php
//Datos para estado de servicio sin vuelta
include '../../../functions/function.php';
$jDataPauta = explode("-", $_POST['id']);

$jCampoPauta = $jDataPauta['0']; //Nombre del campo
$jIdPauta    = $jDataPauta['1']; //Id del registro
$jValorPauta = $_POST['value']; // valor por el cual reemplazar

// Actualiza la columna estServ de la tabla servicio
$jActserPauta = mysqli_query($gestambu, "UPDATE refcontinuado SET  ".$jCampoPauta." = '".$jValorPauta."'
							WHERE numCont = '$jIdPauta' ") or die (mysqli_error($gestambu));

pautaSesion($jValorPauta);

 ?>
