<?php
session_start();
include '../../../functions/function.php';
nonUser();

$incHora    = trim(mysqli_real_escape_string($gestambu, $_POST['incHora']));
$descInci   = trim(mysqli_real_escape_string($gestambu, $_POST['descInci']));
$motivoInci = trim(mysqli_real_escape_string($gestambu, $_POST['motivoInci']));
$userInci   = trim(mysqli_real_escape_string($gestambu, $_POST['userInci']));
$idSv       = trim(mysqli_real_escape_string($gestambu, $_POST['idSv']));

if(empty($incHora)) {
  $restIncHora = "00:00:00";
} else {
  $restIncHora = $incHora.":00";
}

$sqlInci = "INSERT INTO incidencia (idSv, incHora, descInci, userInci, motivoInci)
  VALUES ('$idSv', '$restIncHora', '$descInci', '$userInci', '$motivoInci')
  ";
  if(mysqli_query($gestambu, $sqlInci)) {
    echo ">>>> Incidencia registrada";
    $regOk   = 1;

    /* Mensajes de log */
    $obsText = "Creación de incidencia";
    $usuario = $_SESSION['userId'];

    guardarLog('7', $usuario, $obsText, $idSv);

  } else {
    echo "Error: " . $sqlInci . "<br>" . mysqli_error($gestambu);
  }
 ?>
