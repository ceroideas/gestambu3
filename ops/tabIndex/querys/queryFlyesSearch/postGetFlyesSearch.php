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


  function getFlyesSearch()
  {
    global $gestambu;
    global $diaIni;
    global $diaFin;
    // global $console;
    global $offset;
    global $limit;
  $query = "SELECT vuelosanitario.idVuelo as '#', vuelosanitario.idCia, vuelosanitario.tipo as Tipo, vuelosanitario.hc as HC, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%y') AS Dia, vuelosanitario.nombre, vuelosanitario.apellidos,
    vuelosanitario.locRec as Reco, vuelosanitario.locTras as Tras, vuelosanitario.hora as Hora, vuelosanitario.estVuelo, vuelosanitario.fecha, vuelosanitario.incub as INC, vuelosanitario.idvta as IV, vuelosanitario.comp as CIA,
    cia.idCia, cia.ciaNom as Aseg,
    vueloref.idRefV, vueloref.idVuelo, vueloref.estVuelo, vueloref.medico as Med, vueloref.due as Due, vueloref.pediatra as Ped,
    estados.idEst, estados.vaEst as Est ";
  $query2 = "FROM vuelosanitario
    LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
    LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
    LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
  WHERE vuelosanitario.fecha BETWEEN '$diaIni' AND '$diaFin'";

  $total = mysqli_query($gestambu, "SELECT COUNT(*) as total ".$query2);
  $query = mysqli_query($gestambu, $query.$query2." limit $limit offset $offset");

  return [$query,mysqli_fetch_object($total)->total];
  }

$data = getFlyesSearch();
$total = $data[1];
$data = $data[0];


// $console->log(
//   $data->fetch_assoc()
// );

$response = [];
while($rwList = $data->fetch_assoc()) {

  $href = "/ops/mostrar/editVuelo.php?iden=".$rwList['idVuelo'];


  $rwList['#'] = '<a style="color: black" class="linkBlank" href="'.$href.'" title= "Editar">Ver</a>';
  if($rwList['Tipo'] =='1'){
    $rwList['Tipo'] = "Convencional";

  } elseif($rwList['Tipo'] =='2'){

    $rwList['Tipo'] = "Critico";

  } elseif($rwList['Tipo'] =='3'){

    $rwList['Tipo'] = "Retorno";

  }

  $rwList['Hora'] = date('H:i', strtotime($rwList['Hora']));
  $rwList['Nombre'] = $rwList['Nombre'] . ' ' . $rwList['apellidos'];
  $response[] = $rwList;
}

echo json_encode(["recordsTotal" => $total,
    "recordsFiltered" => $total, "data" => $response]);

