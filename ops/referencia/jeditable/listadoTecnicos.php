<?php
//Datos para listado de técnicos
include '../../../functions/function.php';

$lisTec = mysqli_query($gestambu, "SELECT userId, usNom, usApe, usCate, usEst
  FROM user
  WHERE usEst = '1' AND usCate = '5'
  ORDER BY usNom ASC
");
$numTecList = mysqli_num_rows($lisTec);

for($i=1; $i<=$numTecList; $i++) {
  $rwTecList = mysqli_fetch_array($lisTec);
    $arrayTecList[$rwTecList['userId']] = $rwTecList['usNom']." ".$rwTecList['usApe'];
}

header('Content-Type: application/json'); print json_encode($arrayTecList);

mysqli_close($gestambu);
?>
