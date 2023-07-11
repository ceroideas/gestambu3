<?php
include '../../functions/function.php';
/* Asisa servicios para guardar */
$guardAsisa = mysqli_query($gestambu, "SELECT nuevo FROM asisademanda WHERE nuevo IN('1','2') ");
$numGuarda = mysqli_num_rows($guardAsisa);

echo $numGuarda;

if($numGuarda > 0 ) {
  $link = "/alertas/SD_ALERT_43.mp3";
  $audio = "<audio src='".$link."' autoplay></audio>";
  echo $audio;
}
mysqli_close($gestambu);
?>
