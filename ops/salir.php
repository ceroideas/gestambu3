<?php
session_start();
include '../functions/function.php';
// comprobamos que se haya iniciado la sesión
if(isset($_SESSION['userId'])) {
  /* Guardar en log cerrado de sesion */
  $usuario = $_SESSION['userId'];
  $obsText = "Cerrar sesión";
  $servicioID = '0';
  guardarLog('2', $usuario, $obsText, $servicioID);

  /* Actualizar estado a Desconectado */
  $estUp = mysqli_query($gestambu, "UPDATE user SET estUser='0' WHERE userId='$usuario'");

  /* Cierra sesión */
  session_destroy();
  mysqli_close($gestambu);
  header("Location: /index.php");
}else {
    echo "Operación incorrecta.";
}
?>
