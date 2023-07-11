<?php
session_start();
//Guarda el nombre del técnico
include '../../../functions/function.php';
$jDataPer = explode("-", $_POST['id']);

$jCampoPer      = $jDataPer['0']; //Nombre del campo
$jIdPer         = $jDataPer['1']; //Id del registro
$jValorPer      = $_POST['value']; // valor por el cual reemplazar

//Obtiene el valor de la tabla estados
$vTablaPer = mysqli_query($gestambu, "SELECT userId, usNom, usApe FROM user WHERE userId = '$jValorPer'");
$rwTablaPer = mysqli_fetch_assoc($vTablaPer);
$nomPersonal = $rwTablaPer['usNom']." ".$rwTablaPer['usApe'];

// Actualiza la columna estServ de la tabla servicio
$jActserPer = mysqli_query($gestambu, "UPDATE serpersonal SET  ".$jCampoPer." = '".$nomPersonal."'
							WHERE idSv = ".$jIdPer." ");

/* Mensajes de log */
$obsText = $jCampoPer." :".$nomPersonal;
$usuario = $_SESSION['userId'];
guardarLog('13', $usuario, $obsText, $jIdPer);

echo $nomPersonal;

 ?>
