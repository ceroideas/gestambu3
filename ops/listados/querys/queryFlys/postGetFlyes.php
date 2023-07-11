<?php
include $_SERVER['DOCUMENT_ROOT'] . '/functions/function.php';
include $_SERVER['DOCUMENT_ROOT'] . '/shared/pagination.php';
if(isset($_POST['diaIni'])) {
    $diaIni = $_POST['diaIni'];
  } else {
  
    if(isset($_GET['diaIni'])) {
      $diaIni = $_GET['diaIni'];
    } else {
      $diaIni = date('Y-m-d');
    }
  }
  
  if(isset($_POST['diaFin'])) {
    $diaFin = $_POST['diaFin'];
  } else {
  
    if(isset($_GET['diaFin'])) {
      $diaFin = $_GET['diaFin'];
    } else { 
    $hoy = date('Y-m-d');
    $diaFin = strtotime('+1 day', strtotime($hoy));
    $diaFin = date('Y-m-d', $diaFin);
    }
  }




function getFlyes(){

    global $gestambu;
    global $diaIni;
    global $diaFin;
    global $offset;
    global $results_per_page;

    $query = "SELECT vuelosanitario.idVuelo, vuelosanitario.idCia, vuelosanitario.tipo, vuelosanitario.comp, vuelosanitario.hc, vuelosanitario.fecha, vuelosanitario.incub, 
    vuelosanitario.nombre, vuelosanitario.apellidos, vuelosanitario.recoger, vuelosanitario.locRec, vuelosanitario.trasladar, vuelosanitario.locTras, vuelosanitario.obs, vuelosanitario.hpeti, vuelosanitario.numVuelo, vuelosanitario.estVuelo, 
    vueloref.idVuelo, vueloref.estVuelo, vueloref.medico AS medV, vueloref.due AS dueV, vueloref.pediatra AS pedV , vueloref.hSalida, vueloref.hLlegada, vueloref.hVuelta, vueloref.hLlegada2,
    cia.idCia, cia.ciaNom
    FROM vuelosanitario
    LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
    LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
WHERE vuelosanitario.fecha BETWEEN '$diaIni' AND '$diaFin' AND vueloref.estVuelo !='15'
ORDER BY vuelosanitario.fecha, vuelosanitario.nombre ASC
LIMIT $offset, $results_per_page
";



    $querys = mysqli_query($gestambu, $query);

    return $querys;

}

$data = getFlyes();





?>
