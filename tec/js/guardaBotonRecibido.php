<?php
//Datos para estado de servicio sin vuelta
include '../../functions/function.php';
$valorEntrante = $_POST['valorRecibido'];
$estado        = $_POST['estadoTab'];

$valorNestado = 6; // valor nuevo estado
$idTabla      = $valorEntrante; // id de tabla serestados

echo $valorNestado."<br />";
echo $idTabla."<br />";

if($estado > 1 && $estado < 3) { // Para servicios id/vta -> modifica estado de ida
  $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
  if(mysqli_query($gestambu, $jActserv)) {
    $jActservActu = "Acutalizado";
  } else {
    echo "Error: " . $jActserv . "<br>" . mysqli_error($gestambu);
  }
} elseif($estado > 3 && $estado < 6) { // modifica vuelta
  $jActserv = "UPDATE serestados SET estTecVta = '$valorNestado' WHERE idEst = '$idTabla'";
  if(mysqli_query($gestambu, $jActserv)) {
    $jActservActu = "Acutalizado";
  } else {
    echo "Error: " . $jActserv . "<br>" . mysqli_error($gestambu);
  }
} elseif($estado == 11) {
  $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
  if(mysqli_query($gestambu, $jActserv)) {
    $jActservActu = "Acutalizado";
  } else {
    echo "Error: " . $jActserv . "<br>" . mysqli_error($gestambu);
  }
} else {
  $jActserv = "UPDATE serestados SET estTec = '$valorNestado' WHERE idEst = '$idTabla'";
  if(mysqli_query($gestambu, $jActserv)) {
    $jActservActu = "Acutalizado";
  } else {
    echo "Error: " . $jActserv . "<br>" . mysqli_error($gestambu);
  }
}

/* Volver a estado solicitado Asisa */



 ?>
