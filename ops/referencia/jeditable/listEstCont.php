<?php
//Datos para listado de estados para Continuados
include '../../../functions/function.php';

$listEst = mysqli_query($gestambu, "SELECT idEst, vaEst
  FROM estados
  WHERE idEst IN('1','2','3','4','5','10','15','16')
  ORDER BY vaEst ASC
");
$numEstList = mysqli_num_rows($listEst);

for($i=1; $i<=$numEstList; $i++) {
  $rwEstList = mysqli_fetch_array($listEst);
    $arrayEstList[$rwEstList['idEst']] = $rwEstList['vaEst'];
}
header('Content-Type: application/json'); print json_encode($arrayEstList);

mysqli_close($gestambu);
?>
