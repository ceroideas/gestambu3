<?php
//Datos para listado de técnicos
include '../../../functions/function.php';

$lisDue = mysqli_query($gestambu, "SELECT userId, usNom, usApe, usCate, usEst
  FROM user
  WHERE usEst = '1' AND usCate = '6'
  ORDER BY usNom ASC
");
$numDueList = mysqli_num_rows($lisDue);

for($i=1; $i<=$numDueList; $i++) {
  $rwDueList = mysqli_fetch_array($lisDue);
    $arrayDueList[$rwDueList['userId']] = $rwDueList['usNom']." ".$rwDueList['usApe'];
}
header('Content-Type: application/json'); print json_encode($arrayDueList);

mysqli_close($gestambu);
?>
