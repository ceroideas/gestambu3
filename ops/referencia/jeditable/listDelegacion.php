<?php
//Datos para listado de estados para Continuados
include '../../../functions/function.php';

$listProv = mysqli_query($gestambu, "SELECT id, provincia
  FROM provincias
  ORDER BY provincia ASC
");
$numTipoProv = mysqli_num_rows($listProv);

for($i=1; $i<=$numTipoProv; $i++) {
  $rwTipoProv = mysqli_fetch_array($listProv);
    $arrayTipoProv[$rwTipoProv['id']] = $rwTipoProv['provincia'];
}
header('Content-Type: application/json'); print json_encode($arrayTipoProv);

mysqli_close($gestambu);
?>
