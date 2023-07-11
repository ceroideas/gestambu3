<?php
//Datos para estado de servicio con vuelta
include '../../../functions/function.php';

$sqlListVh = mysqli_query($gestambu, "SELECT idVh, matricula, estado
  FROM vehiculo
  WHERE estado = '1'
  ORDER BY matricula ASC
");
$numVhList = mysqli_num_rows($sqlListVh);

for($i=1; $i<=$numVhList; $i++) {
  $rwVhList = mysqli_fetch_array($sqlListVh);
    $arrayVhList[$rwVhList['idVh']] = $rwVhList['matricula'];
}
$arrayVhList['0'] = 'SIN VEHICULO';
header('Content-Type: application/json'); print json_encode($arrayVhList);

mysqli_free_result($sqlListVh);
?>
