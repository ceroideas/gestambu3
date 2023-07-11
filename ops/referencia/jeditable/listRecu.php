<?php
//Datos para listado de técnicos
include '../../../functions/function.php';

$lisRecu = mysqli_query($gestambu, "SELECT idRecu, nomRecu
  FROM recurso
  ORDER BY nomRecu ASC
");
$numRecuList = mysqli_num_rows($lisRecu);

for($i=1; $i<=$numRecuList; $i++) {
  $rwRecuList = mysqli_fetch_array($lisRecu);
    $arrayRecuList[$rwRecuList['idRecu']] = $rwRecuList['nomRecu'];
}
header('Content-Type: application/json'); print json_encode($arrayRecuList);

mysqli_close($gestambu);
?>
