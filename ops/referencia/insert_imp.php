<?php
include '../../functions/function.php';

$servicio_id = $_GET['servicio_id'];
$user_id = $_GET['user'];

$comp = mysqli_query($gestambu, "SELECT idLog, idSv FROM loguser WHERE idSv='$servicio_id' AND idLog='4'");
$numfilas = mysqli_num_rows($comp);

if($numfilas == 0) {
  $obsText = "Impreso";
	$impSQL = mysqli_query($gestambu, "INSERT INTO loguser (idLog,userId,obsText,idSv) VALUES ('4','$user_id','$obsText', '$servicio_id')");
} else {
  $obsText = "Se volvió a imprimir";
	$impSQL = mysqli_query($gestambu, "INSERT INTO loguser (idLog,userId,obsText,idSv) VALUES ('4','$user_id','$obsText', '$servicio_id')");
}

?>
