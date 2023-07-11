<?php

function getFlyes()
{
  global $gestambu;

  $sqlVuelo = mysqli_query($gestambu,
  "SELECT vuelosanitario.idVuelo as '#', vuelosanitario.idCia as 'Fecha', vuelosanitario.tipo as Hora, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%Y') AS Aseguradora, vuelosanitario.hora as Nombre, vuelosanitario.medico as Tipo, vuelosanitario.due as 'Id/Vta', vuelosanitario.idvta as Medico, vuelosanitario.incub as Due, vuelosanitario.pediatra as Pediatra, vuelosanitario.nombre as Incubadora,
  vuelosanitario.recoger as Recoger, vuelosanitario.locRec as Loc, vuelosanitario.trasladar as Trasladar, vuelosanitario.locTras as Loc2, cia.ciaNom as Medico2, vueloref.idVuelo as Due2, vueloref.estVuelo as Pediatra, vueloref.medico AS Pediatra2,
  estados.idEst as Estado
  FROM vuelosanitario
  LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
  LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
  LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
  ");
  return $sqlVuelo;
}

?>