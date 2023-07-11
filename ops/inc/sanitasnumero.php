<?php
include '../../functions/function.php';
/* Asisa servicios para guardar */
$guardSanitas = mysqli_query($gestambu, "SELECT count(idAviso) FROM sanitasdemanda WHERE estado_proceso = 0");
$row = mysqli_fetch_array($guardSanitas, MYSQLI_NUM);
$numGuardaSanita = $row[0];

echo $numGuardaSanita;

if($numGuardaSanita > 0 ) {
  $link = "/alertas/SD_ALERT_43.mp3";
  $audio = "<audio src='".$link."' autoplay></audio>";
  echo $audio;
}
mysqli_close($gestambu);
?>
