<?php
//Datos para listado de estados para Continuados
include '../../../functions/function.php';

$lisTipo = mysqli_query($gestambu, "SELECT idServi, nomSer
  FROM servi
  WHERE idServi NOT IN('1', '2','9','10', '11', '12', '13', '14', '16', '17', '19', '22')
  ORDER BY nomSer ASC
");
$numTipotList = mysqli_num_rows($lisTipo);

for($i=1; $i<=$numTipotList; $i++) {
  $rwTipotList = mysqli_fetch_array($lisTipo);
    $arrayTipoList[$rwTipotList['idServi']] = $rwTipotList['nomSer'];
}
header('Content-Type: application/json'); print json_encode($arrayTipoList);

mysqli_close($gestambu);
?>
