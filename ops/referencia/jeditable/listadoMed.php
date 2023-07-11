<?php
//Datos para listado de técnicos
include '../../../functions/function.php';

$lisMed = mysqli_query($gestambu, "SELECT userId, usNom, usApe, usCate, usEst
  FROM user
  WHERE usEst = '1' AND usCate = '7'
  ORDER BY usNom ASC
");
$numMedList = mysqli_num_rows($lisMed);

for($i=1; $i<=$numMedList; $i++) {
  $rwMedList = mysqli_fetch_array($lisMed);
    $arrayMedList[$rwMedList['userId']] = $rwMedList['usNom']." ".$rwMedList['usApe'];
}
header('Content-Type: application/json'); print json_encode($arrayMedList);

mysqli_close($gestambu);
?>
