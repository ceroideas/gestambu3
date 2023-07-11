<?php
error_reporting(1);
include $_SERVER['DOCUMENT_ROOT'] . '/functions/function.php';

if(isset($_GET['prov'])) {
  if($_GET['prov'] == 0 ) {
    $provTab = "'29', '11', '41', '21', '14','52'";
  } else {
    $provin = $_GET['prov'];
    $provTab = "'".$provin."'";
  }
} else {
  $provTab = "'29', '11', '41', '21', '14','52'";
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

1 AMBULANCIA
2 ENFERMERO
3 U.V.I.
4 V_MEDICA
5 TAXI

*/
/* Muestra servicios Finalizados o Activos - hoy */
if($esp == '1') {
  $finalWhere = "IN('10', '14')";
} else {
  $finalWhere = "NOT IN('10', '14', '15', '16')";
}

$limit = 10;
$offset = 10;

if(isset($_POST['start'])) {
  $offset = $_POST['start'];
}

if(isset($_POST['length'])) {
  $limit = $_POST['length'];
}

$search = $_POST['search']['value'];

function getRoute()
{
  global $gestambu;
  global $varFecha;
  global $finalWhere;
  global $provTab;
  global $offset;
  global $limit;
  global $search;

  $query = "SELECT  servicio.idSv as '#', servicio.idCia, servicio.provincia, servicio.continuado, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.medico as medNom, servicio.enfermero, servicio.idvta, servicio.nombre as Nombre, servicio.recoger as Recoger,
  servicio.locRec as Loc, servicio.trasladar as Trasladar, servicio.locTras, servicio.estServ, servicio.apellidos, servicio.obs, servicio.creadoNu, cia.idCia, cia.ciaNom as Cia, servicio.codRuta,
  servi.idServi, servi.nomSer,
  recurso.idRecu, recurso.nomRecu,
  estados.idEst, estados.vaEst as Estado,
  serhorario.horaId, serhorario.idRefSv, DATE_FORMAT(serhorario.idReco, '%H:%i') AS idReco, DATE_FORMAT(serhorario.idFin, '%H:%i') AS idFin, DATE_FORMAT(serhorario.vtaReco, '%H:%i') AS vtaReco, DATE_FORMAT(serhorario.vtaFin, '%H:%i') AS vtaFin,
  serestados.idSv, serestados.vhIda, serestados.vhVta, serestados.estTec, serestados.estTecVta,
  serinfo.idSv, DATE_FORMAT(serinfo.demora, '%H:%i') AS demora, serinfo.prioridad,
  serpersonal.idSv, serpersonal.perId, serpersonal.tecIda, serpersonal.dueIda, serpersonal.medIda,
  ruta.codRuta, ruta.nomRuta, ruta.prov ";

    $query2 = "FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
    LEFT JOIN estados ON servicio.estServ = estados.idEst
    LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
    LEFT JOIN serpersonal ON servicio.idSv = serpersonal.idSv
    LEFT JOIN ruta ON servicio.codRuta = ruta.codRuta
  WHERE servicio.fecha ='$varFecha'
        AND servicio.recurso = '7'
        AND servicio.estServ ".$finalWhere."
        AND servicio.provincia IN ($provTab)
  ORDER BY hora ASC";

  $total = mysqli_query($gestambu, "SELECT COUNT(*) as total ".$query2);
  $query = mysqli_query($gestambu, $query.$query2." limit $limit offset $offset");

  return [$query,mysqli_fetch_object($total)->total];
}

$data = getRoute();
$total = $data[1];
$data = $data[0];

// $console->log($data);
$response = [];
while ($rwList = $data->fetch_assoc()) {

  $sinContenido = noCampo($rwList['tipo']);
  $bgTD = "";

  if(nuevoSer($rwList['creadoNu'], $rwList['estServ']) == 1) {
       $bgTD = "newSer";
       $colBlanco = 1;
     } else {
       if(@verUltima($rwList['continuado']) == 1) {
         $bgTD = "bg-ultima";
         $colBlanco = 1;
       } else {
         if($rwList['prioridad'] == 1) {
           $bgTD = "bg-urgente";
           $colBlanco = 1;
         } elseif($rwList['prioridad'] == 2) {
           $bgTD = "bg-preferente";
           $colBlanco = 1;
         } else {
           if(@$icont++ % 2) {
             $bgTD = "colorZebra";
             $colBlanco = 0;
           } else {
             $colBlanco = 0;
           }
         }
       }
     }

  $rwList['Hora'] = '<span data-toggle="tooltip" title="Demora: '.mostrarEstados2($rwList['demora']).'">'.date('H:i', strtotime($rwList['hora'])).'</span>';
  $rwList['Provincia'] = provValor2($rwList['provincia']);
  $rwList['Tipo'] = "";
  $href = "/ops/mostrar/editServ.php?iden=".$rwList['idSv'];

  
  $modal = '<div id="modal-'.$rwList['idSv'].'" class="modal fade" role="dialog">
               <div class="modal-dialog">

                 <!-- Contenido modal - ver servicio-->
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Servicio para: '.$rwList['idSv']." - ".$rwList['Nombre']." ".$rwList['apellidos'].' <span id="copyAnswer-'.$rwList['idSv'].'" class="label label-success"></span></h4>
                   </div>
                   <div class="modal-body">
                     <div id="textoCopiar-'.$rwList['idSv'].'">'.date('H:i', strtotime($rwList['hora']))."-".$rwList['Cia']."-"
.$rwList['nomSer']."-".$rwList['Recoger']."-".$rwList['Loc']."-".$rwList['Trasladar']."-"
.$rwList['locTras']."-".$rwList['obs'].'
                     </div>
                      '.ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomRecu']).'
                   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default" onclick="copiarAlPortapapeles(\'textoCopiar-'.$rwList['idSv'].'\', \'copyAnswer-'.$rwList['idSv'].'\')">Copiar texto</button>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                   </div>
                 </div>

               </div>
             </div>';

  $rwList["Icono"] = '<a title="Ver servicio" style="color: black" class="linkBlank" data-toggle="modal" data-toggle="modal" data-target="#modal-'.$rwList['idSv'].'" href="#" title="Editar"><i class="fa fa-'.icoEsTec($rwList['estTec'],$rwList['estTecVta'], $rwList['estServ'], $rwList['idvta']).'"   onclick="limpiar(\'copyAnswer-\' '.$rwList['idSv'].')"></i></a>'.$modal;

  $rwList['#'] = '<a style="color: black" class="linkBlank" href="'.$href.'" title= "Editar" data-bg="'.$bgTD.'"><i class="fa fa-pencil-square-o"></i></a>';
  if (@verUltima($rwList['continuado']) == 1) {
    $rwList['Tipo'] = "U L T I M A";
  } else {
    $rwList['Tipo'] = $rwList['nomSer'];
  }

  $rwList['Loc-2'] = $rwList['locTras'];
  $rwList['Recurso'] = ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomRecu']);

  $estado = $rwList["Estado"];
  $rwList["Estado"] = '<div class="'.estJeditable1($rwList['idvta']).'" id="estServ-'.$rwList['idSv'].'">'.$estado.'</div>';


  if ($rwList['idvta'] == 3) {
    $rwList['V-ida'] = '<i class="fa fa-close"></i>';
    $rwList['Re-ida'] = "SIN IDA";
    $rwList['Fin-ida'] = "SIN IDA";
  }else{
    $rwList['V-ida'] = '<div class="vhlist" id="vhIda-'.$rwAmbTab['idSv'].'"><strong>'.mostrarVehiculo2($rwAmbTab['vhIda']).'</strong></div>';
    $rwList['Re-ida'] = '<div class="hora" id="idReco-'.$rwAmbTab['idSv'].'">'.$rwAmbTab['idReco'].'</div>';
    $rwList['Fin-ida'] = '<div class="hora" id="idFin-'.$rwAmbTab['idSv'].'">'.$rwAmbTab['idFin'].'</div>';
  }

  if ($rwList['idvta'] == 2) {
    $rwList['V-vta'] = '<i class="fa fa-close"></i>';
    $rwList['Re-vta'] = " SIN VUELTA";
    $rwList['Fin-vta'] = " SIN VUELTA";
  }elseif($sinContenido == 1) {
    $rwList['V-vta'] = "";
    $rwList['Re-vta'] = "";
    $rwList['Fin-vta'] = "";
  }else{
    $rwList['V-vta'] = '<div class="vhlist" id="vhVta-'.$rwAmbTab['idSv'].'"><strong>'.mostrarVehiculo2($rwAmbTab['vhVta']).'</strong></div>';
    $rwList['Re-vta'] = '<div class="hora" id="vtaReco-'.$rwAmbTab['idSv'].'">'.$rwAmbTab['vtaReco'].'</div>';
    $rwList['Fin-vta'] = '<div class="hora" id="vtaFin-'.$rwAmbTab['idSv'].'">'.$rwAmbTab['vtaFin'].'</div>';
  }
  
  $response[] = $rwList;
}


echo json_encode(["recordsTotal" => $total,
    "recordsFiltered" => $total, "data" => $response]);