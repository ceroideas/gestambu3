<?php
session_start();
include '../functions/function.php';
// comprobamos que se haya iniciado la sesión
if(isset($_SESSION['userId'])) {

  session_destroy();
  mysqli_close($gestambu);
  header("Location: /index.php");
}else {
    echo "Operación incorrecta.";
}
?>
