<?php
  include $_SERVER['DOCUMENT_ROOT'] . '/functions/function.php';
  $diaIni = date("Y-m-d");
  $diaFin = date("Y-m-d");
  $limit = 10;
  $offset = 10;

  if(isset($_POST['start'])) {
    $offset = $_POST['start'];
  }

  if(isset($_POST['length'])) {
    $limit = $_POST['length'];
  }

  if(isset($_POST['fFin'])) {
    $diaFin = $_POST['fFin'];
  }

  if(isset($_POST['fIni'])) {
    $diaIni = $_POST['fIni'];
  }


  function getServiceSearch()
  {
    global $gestambu;
    global $diaIni;
    global $diaFin;
    // global $console;
    global $offset;
    global $limit;
  $query = "SELECT servicio.idSv as '#', servicio.idCia, servicio.autorizacion as 'Control', servicio.provincia, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS Dia, servicio.hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre,
  servicio.orden, servicio.apellidos, servicio.recoger as Recoger, servicio.locRec as 'Rec-Loc', servicio.trasladar as Trasladar, servicio.locTras as 'Tras-Loc', servicio.estServ, servicio.obs, cia.idCia, cia.ciaNom as Cia, servi.idServi, servi.nomSer as Servicio, servi.icono, recurso.idRecu, recurso.recuCorto,
  serestados.idSv, serestados.vhIda, vehiculo.idVh, vehiculo.matricula, estados.idEst, estados.vaEst as Estado, serhorario.idRefSv, serhorario.idReco, serhorario.idFin, serhorario.vtaFin
FROM servicio
  LEFT JOIN cia ON servicio.idCia = cia.idCia
  LEFT JOIN servi ON servicio.tipo = servi.idServi
  LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
  LEFT JOIN serestados ON servicio.idSv = serestados.idSv
  LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
  LEFT JOIN estados ON servicio.estServ = estados.idEst
  LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' limit $limit offset $offset
";
  $query = mysqli_query($gestambu, $query);
  return $query;
}

$data = getServiceSearch();


// $console->log(
//   $data->fetch_assoc()
// );

$response = [];
while($rwList = $data->fetch_assoc()) {

  $href = "/ops/mostrar/editServ.php?iden=".$rwList['idSv'];


  $rwList['#'] = '<a style="color: black" class="linkBlank" href="'.$href.'" title= "Editar">Ver</a>';
$rwList['Hora'] = date('H:i', strtotime($rwList['hora']));
$rwList['Recurso'] = ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomSer']);

$rwList['Paciente'] = $rwList['nombre']." ".$rwList['apellidos'];

$rwList['Reco'] = substr($rwList['idReco'], 0, 5);

  if ($rwList['idvta'] == 1) {
    $rwList['Final'] = substr($rwList['vtaFin'], 0, 5);
  } else {
    $rwList['Final'] =  substr($rwList['idFin'], 0, 5);
  }
  

  $response[] = $rwList;
}

echo json_encode(["data" => $response]);
?>