<?php
session_start();
//Vincular servicio continuado a una ruta
include '../../../functions/function.php';
$jDataRuta = explode("-", $_POST['id']);

$jCampoRuta = $jDataRuta['0']; //Nombre del campo
$jIdRuta    = $jDataRuta['1']; //Id del registro
$jValorRuta = $_POST['value']; // valor por el cual reemplazar

$jActserRuta = mysqli_query($gestambu, "UPDATE servicio SET  ".$jCampoRuta." = '$jValorRuta'
							WHERE continuado = '$jIdRuta'") or die (mysqli_error($gestambu));
echo $jValorRuta;
?>
