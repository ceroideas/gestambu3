<?php

include '../../functions/function.php';

//Recoge variable provincia, si no, adquiere el valor por defecto 29 (Málaga)
if(isset($_GET['prov'])) {
  if($_GET['prov'] == 0 ) {
    $provTab = "'29', '11', '41', '21', '52', '14'";
  } /*elseif($_GET['prov'] == 41) { // Excluido por peticion de que se dejen de mostrar los servicios de huelva en sevilla
  $provTab = "'41', '21'";//Los servicios de Huelva aparecen también en Sevilla
  }*/else {
    $provin = $_GET['prov'];
    $provTab = "'".$provin."'";
  }
} else {
  $provTab = "'29', '11', '41', '21', '52', '14'";
}

if(isset($_GET['final'])) {
  $esp = $_GET['final'];
} else {
  $esp = 0;
}

/* fecha para servicio */
if(isset($_GET['filFecha'])){
  $varFecha = $_GET['filFecha'];
} else {
  $varFecha = date("Y-m-d");
}
/* Recurso

1	AMBULANCIA
2	ENFERMERO
3	U.V.I.
4	V_MEDICA
5	TAXI

*/
/* Muestra servicios Finalizados o Activos - hoy */
if($esp == '1') {
  $finalWhere = "IN('10', '14', '17')";
} else {
  $finalWhere = "NOT IN('10', '14', '15', '16', '17')";
}


function getFlyes()
{
  global $gestambu;
    global $varFecha;
global $finalWhere;
    global $provTab;



    $sqlAmbu = mysqli_query($gestambu,
    "SELECT  servicio.idSv, servicio.idCia, servicio.provincia, servicio.continuado, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre, servicio.recoger,
    servicio.edad, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.obs, servicio.estServ, servicio.apellidos, servicio.obs, servicio.creadoNu, cia.idCia, cia.ciaNom,
    servi.idServi, servi.nomSer,
    recurso.idRecu, recurso.nomRecu,
    estados.idEst, estados.vaEst,
    serhorario.horaId, serhorario.idRefSv, DATE_FORMAT(serhorario.idReco, '%H:%i') AS idReco, DATE_FORMAT(serhorario.idFin, '%H:%i') AS idFin, DATE_FORMAT(serhorario.vtaReco, '%H:%i') AS vtaReco, DATE_FORMAT(serhorario.vtaFin, '%H:%i') AS vtaFin,
    serestados.idSv, serestados.vhIda, serestados.vhVta, serestados.estTec, serestados.estTecVta,
    serinfo.idSv, DATE_FORMAT(serinfo.demora, '%H:%i') AS demora, serinfo.prioridad, DATE_FORMAT(serinfo.hvuelta, '%H:%i') AS HVTA,
    serpersonal.idSv, serpersonal.perId, serpersonal.tecIda, serpersonal.dueIda, serpersonal.medIda
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN estados ON servicio.estServ = estados.idEst
      LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
      LEFT JOIN serpersonal ON servicio.idSv = serpersonal.idSv
    WHERE servicio.fecha ='$varFecha'
          AND servicio.recurso IN('1', '3', '5')
          AND servicio.estServ ".$finalWhere."
          AND servicio.provincia IN ($provTab)
    ORDER BY hora ASC
  ");
  return $sqlAmbu;
}

?>