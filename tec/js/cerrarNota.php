<?php
//Cierra notas de ususario
include '../../functions/function.php';
$idNota  = $_POST['idNota'];
$tecnico = $_POST['tecnico'];
$cerrada = date("Y-m-d H:i:s");

$notaUp = mysqli_query($gestambu, "UPDATE notas SET cerrada='$cerrada', notaEst='0', tecnico='$tecnico' WHERE idNota='$idNota'");

?>
