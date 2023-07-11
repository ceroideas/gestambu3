<?php
//Estado sin vuelta
include '../../../functions/function.php';

$sqlEstado = mysqli_query($gestambu, "SELECT idEst, vaEst
  FROM estados
  WHERE idEst IN('1','11','14','15')
  ORDER BY vaEst ASC
");
$numSqlEstado = mysqli_num_rows($sqlEstado);

for($i=1; $i<=$numSqlEstado; $i++) {
  $rwSqlEstado = mysqli_fetch_array($sqlEstado);
    $arrayEstado[$rwSqlEstado['idEst']] = $rwSqlEstado['vaEst'];
}
header('Content-Type: application/json'); print json_encode($arrayEstado);

mysqli_free_result($sqlEstado);
?>
