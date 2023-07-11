<?php

include '../../functions/function.php';

$sqlVuelo = mysqli_query($gestambu,
  "SELECT vuelosanitario.idVuelo, vuelosanitario.idCia, vuelosanitario.tipo, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%Y') AS fecha, vuelosanitario.hora, vuelosanitario.medico, vuelosanitario.due, vuelosanitario.idvta, vuelosanitario.incub, vuelosanitario.pediatra, vuelosanitario.nombre,
    vuelosanitario.recoger, vuelosanitario.locRec, vuelosanitario.trasladar, vuelosanitario.locTras, cia.idCia, cia.ciaNom, vueloref.idVuelo, vueloref.estVuelo, vueloref.medico AS medNom, vueloref.due AS dueNom, vueloref.pediatra AS pedNom,
    estados.idEst, estados.vaEst
  FROM vuelosanitario
    LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
    LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
    LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
  WHERE vueloref.estVuelo NOT IN ('14', '15')
  ORDER BY vuelosanitario.fecha, vuelosanitario.hora ASC
");
$numSqlVuelo = mysqli_num_rows($sqlVuelo);

?>
