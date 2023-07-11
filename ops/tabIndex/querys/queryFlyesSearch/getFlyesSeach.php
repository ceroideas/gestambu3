<?php



function getFlyesSearch()
{
  global $gestambu;

  $sqlVuelo = mysqli_query($gestambu, "SELECT vuelosanitario.idVuelo as '#',  vuelosanitario.tipo as Tipo, vuelosanitario.hc as HC, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%y') AS Dia, vuelosanitario.nombre as Nombre,
  vuelosanitario.locRec as Reco, vuelosanitario.locTras as Tras, vuelosanitario.hora as Hora, vuelosanitario.incub as INC, vuelosanitario.idvta As IV, vuelosanitario.comp as CIA,
   cia.ciaNom as Aseg,
  vueloref.medico as Med, vueloref.due as Due, vueloref.pediatra as Ped,
  estados.vaEst as Est
FROM vuelosanitario
  LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
  LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
  LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
WHERE vuelosanitario.fecha");
  return $sqlVuelo;
}

?>