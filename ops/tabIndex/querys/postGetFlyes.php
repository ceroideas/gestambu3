<?php
  error_reporting(0);
  include $_SERVER['DOCUMENT_ROOT'] . '/functions/function.php';


  $limit = 10;
$offset = 10;

if(isset($_POST['start'])) {
  $offset = $_POST['start'];
}

if(isset($_POST['length'])) {
  $limit = $_POST['length'];
}

$search = $_POST['search']['value'];
  
  function getFlyes()
  {
    global $gestambu;
    global $limit;
    global $offset;
    global $search;

    $query = "SELECT vuelosanitario.idVuelo, vuelosanitario.idCia, vuelosanitario.tipo, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%Y') AS Fecha, vuelosanitario.hora as Hora, vuelosanitario.medico as Medico, vuelosanitario.due, vuelosanitario.idvta as 'Id/Vta', vuelosanitario.incub as Incubadora, vuelosanitario.pediatra as Pediatra, vuelosanitario.nombre as Nombre,
    vuelosanitario.recoger as Recoger, vuelosanitario.locRec as Loc, vuelosanitario.trasladar as Trasladar, vuelosanitario.locTras as Loc2, cia.idCia, cia.ciaNom as Aseguradora, vueloref.idVuelo, vueloref.estVuelo, vueloref.medico AS 'Medico2', vueloref.due AS Due2, vueloref.pediatra AS Pediatra2,
    estados.idEst, estados.vaEst as Estado ";
    $query2 = "FROM vuelosanitario
    LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
    LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
    LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
    WHERE Nombre like '%".$search."%' AND vueloref.estVuelo NOT IN ('14', '15')
    ORDER BY vuelosanitario.fecha, vuelosanitario.hora ASC";

    $total = mysqli_query($gestambu, "SELECT COUNT(*) as total ".$query2);
    $query = mysqli_query($gestambu, $query.$query2." limit $limit offset $offset");

    return [$query,mysqli_fetch_object($total)->total];
  }

$data = getFlyes();
$total = $data[1];
$data = $data[0];


/* $console->log(
  $data->fetch_assoc()
); */

$response = [];
while($rwList = $data->fetch_assoc()) {  
  

if($rwList['Medico2'] == null){

    $rwList['Medico2'] = "";

}

if($rwList['Due2'] == null){

  $rwList['Due2'] = "";

}

if($rwList['Pediatra2'] == null){

  $rwList['Pediatra2'] = "";

}

  if($rwList['Medico'] == 1){
     $rwList['Medico'] = "<i class=\"fa fa-check\"></i>"; 
    
  
    };

    if($rwList['due'] == 1){
      $rwList['Due'] = "<i class=\"fa fa-check\"></i>"; 
     
   
     };

     if($rwList['Pediatra'] == 1){
      $rwList['Pediatra'] = "<i class=\"fa fa-check\"></i>"; 
     
   
     };

     if($rwList['Incubadora'] == 1){
      $rwList['Incubadora'] = "<i class=\"fa fa-check\"></i>"; 
     
   
     };

     $rwList['Estado'] = '<div class="estadoVuelo" id="estVuelo-'.$rwList['idVuelo'].'">'.$rwList['vaEst'].'</div>';

  $href = "/ops/mostrar/editVuelo.php?iden=".$rwList['idVuelo'];

  $rwList['Tipo'] = valoresVuelos2($rwList['tipo']);

  $rwList['#'] = '<a style="color: black" class="linkBlank" href="'.$href.'" title= "Editar"><i class="fa fa-pencil-square-o"></i></a>';
  
  $response[] = $rwList;
}

echo json_encode(["recordsTotal" => $total,
    "recordsFiltered" => $total, "data" => $response]);
