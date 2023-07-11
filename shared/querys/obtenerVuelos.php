<?php

session_start();
include '../../functions/function.php';
nonUser();

if (isset($_POST['fIni'])) {
    $diaIni = $_POST['fIni'];
} else {
    $diaIni = date("Y-m-d");
}

if (isset($_POST['fFin'])) {
    $diaFin = $_POST['fFin'];
} else {
    $diaFin = date("Y-m-d");
}

$limit = $_REQUEST['length'];
$offset = $_REQUEST['start'];

$obtenerVuelosQuery = "SELECT vuelosanitario.idVuelo, vuelosanitario.idCia, vuelosanitario.tipo, vuelosanitario.hc, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%y') AS fechaFor, vuelosanitario.nombre, vuelosanitario.apellidos,
vuelosanitario.locRec, vuelosanitario.locTras, vuelosanitario.hora, vuelosanitario.estVuelo, vuelosanitario.fecha, vuelosanitario.incub, vuelosanitario.idvta, vuelosanitario.comp,
cia.idCia, cia.ciaNom,
vueloref.idRefV, vueloref.idVuelo, vueloref.estVuelo, vueloref.medico, vueloref.due, vueloref.pediatra,
estados.idEst, estados.vaEst
FROM vuelosanitario
LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
WHERE vuelosanitario.fecha BETWEEN '$diaIni' AND '$diaFin' LIMIT $offset, $limit";

$lisTable = mysqli_query($gestambu, $obtenerVuelosQuery);

$response = array();

while($rwList = $lisTable->fetch_assoc()) {
    $rwList['hora'] = date('H:i', strtotime($rwList['hora']));
    $response[] = $rwList;
}

echo json_encode(array("data" => $response));

/* echo !isset($response) ? $response : json_encode(
array(
"error" => "No hay vuelos",
"diaIni" =>  $_POST['fIni'],
"diaFin" => $_POST['fFin'],
"dex" => $obtenerVuelosQuery
)); */
?>