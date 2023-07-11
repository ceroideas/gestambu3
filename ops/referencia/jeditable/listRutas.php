<?php
//Datos para listado de técnicos
include '../../../functions/function.php';

$lisRuta = mysqli_query($gestambu, "SELECT idRuta, codRuta, nomRuta, activa
  FROM ruta
  WHERE activa ='1'
  ORDER BY nomRuta ASC
");
$numRutaList = mysqli_num_rows($lisRuta);

for($i=1; $i<=$numRutaList; $i++) {
  $rwRutaList = mysqli_fetch_array($lisRuta);
    $arrayRutaList[$rwRutaList['codRuta']] = $rwRutaList['nomRuta'];
}
$arrayRutaList['0'] = 'SIN RUTA';
header('Content-Type: application/json'); print json_encode($arrayRutaList);

mysqli_close($gestambu);
?>
