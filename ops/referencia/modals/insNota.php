<?php
session_start();
include '../../../functions/function.php';
nonUser();

$descNota   = trim(mysqli_real_escape_string($gestambu, $_POST['descNota']));
$userNota   = trim(mysqli_real_escape_string($gestambu, $_POST['userNota']));
$idPac      = trim(mysqli_real_escape_string($gestambu, $_POST['idPac']));

$sqlNota = "INSERT INTO pacnota (idPac, userId, descNota)
  VALUES ('$idPac', '$userNota', '$descNota')";

  if(mysqli_query($gestambu, $sqlNota)) {
    echo ">>>> Nota registrada >>>> Puedes cerrar la ventana";
    $regOk   = 1;
  } else {
    echo "Error: " . $sqlNota . "<br>" . mysqli_error($gestambu);
  }
 ?>
