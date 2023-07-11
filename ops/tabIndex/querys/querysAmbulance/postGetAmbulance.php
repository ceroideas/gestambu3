<?php
include $_SERVER['DOCUMENT_ROOT'] . '/functions/function.php';
//Recoge variable provincia, si no, adquiere el valor por defecto 29 (Málaga)
if (isset($_GET['prov'])) {
  if ($_GET['prov'] == 0) {
    $provTab = "'29', '11', '41', '21', '52', '14'";
  } /*elseif($_GET['prov'] == 41) { // Excluido por peticion de que se dejen de mostrar los servicios de huelva en sevilla
   $provTab = "'41', '21'";//Los servicios de Huelva aparecen también en Sevilla
   }*/else {
    $provin = $_GET['prov'];
    $provTab = "'" . $provin . "'";
  }
} else {
  $provTab = "'29', '11', '41', '21', '52', '14'";
}

if (isset($_GET['final'])) {
  $esp = $_GET['final'];
} else {
  $esp = 0;
}

$varFecha = date("Y-m-d");
/* fecha para servicio */
if (isset($_GET['filFecha'])) {
  $varFecha = $_GET['filFecha'];
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

/* Recurso
1	AMBULANCIA
2	ENFERMERO
3	U.V.I.
4	V_MEDICA
5	TAXI
*/
/* Muestra servicios Finalizados o Activos - hoy */
if ($esp == '1') {
  $finalWhere = "IN('10', '14', '17')";
} else {
  $finalWhere = "NOT IN('10', '14', '15', '16', '17')";
}

function getAbulance()
{
  global $gestambu;
  global $varFecha;
  global $finalWhere;
  global $provTab;
  global $offset;
  global $limit;
  global $search;
  
  $query = "SELECT servicio.idSv , servicio.idCia, servicio.provincia as Provincia, servicio.continuado, servicio.tipo as Tipo, servicio.recurso, servicio.fecha, servicio.hora as Hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre as Nombre, servicio.recoger as Recoger,
  servicio.edad, servicio.locRec as Loc, servicio.trasladar as Trasladar, servicio.locTras as 'Loc 2', servicio.obs, servicio.estServ, servicio.apellidos, servicio.obs, servicio.creadoNu, cia.idCia, cia.ciaNom as Cia,
  servi.idServi, servi.nomSer,
  recurso.idRecu, recurso.nomRecu,
  estados.idEst, estados.vaEst as Estado,
  serhorario.horaId, serhorario.idRefSv, DATE_FORMAT(serhorario.idReco, '%H:%i') AS idReco, DATE_FORMAT(serhorario.idFin, '%H:%i') AS idFin, DATE_FORMAT(serhorario.vtaReco, '%H:%i') AS vtaReco, DATE_FORMAT(serhorario.vtaFin, '%H:%i') AS vtaFin,
  serestados.idSv, serestados.vhIda, serestados.vhVta , serestados.estTec, serestados.estTecVta,
  serinfo.idSv, DATE_FORMAT(serinfo.demora, '%H:%i') AS demora, serinfo.prioridad, DATE_FORMAT(serinfo.hvuelta, '%H:%i') AS HVTA,
  serpersonal.idSv, serpersonal.perId, serpersonal.tecIda, serpersonal.dueIda, serpersonal.medIda ";
  $query2 = 
  "FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
    LEFT JOIN estados ON servicio.estServ = estados.idEst
    LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
    LEFT JOIN serpersonal ON servicio.idSv = serpersonal.idSv
  WHERE 
    Nombre like '%".$search."%'
    AND servicio.fecha ='$varFecha'
    AND servicio.recurso IN('1', '3', '5')
    AND servicio.estServ " . $finalWhere . "
    AND servicio.Provincia IN($provTab) 
  ORDER BY hora ASC";

$total = mysqli_query($gestambu, "SELECT COUNT(*) as total ".$query2);
$query = mysqli_query($gestambu, $query.$query2." limit $limit offset $offset");
  
return [$query,mysqli_fetch_object($total)->total];
}

// $console->log($offset);

$data = getAbulance();
$total = $data[1];
$data = $data[0];

// $console->log($data);
$response = [];
while ($rwList = $data->fetch_assoc()) {
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
   $textMsj = "URGENTE";
       } elseif($rwList['prioridad'] == 2) {
         $bgTD = "bg-preferente";
         $colBlanco = 1;
   $textMsj = "PREFERENTE";
       } else {
         if(@$icont++ % 2) {
           $bgTD = "colorZebra";
           $colBlanco = 0;
         } else {
           $colBlanco = 0;
         }
   $textMsj = "";
       }
     }
   }

  $href = "/ops/mostrar/editServ.php?iden=".$rwList['idSv'];
  $ambulancia = json_encode($rwList);

  $rwList['#'] = '<a style="color: black" class="linkBlank" href="'.$href.'" title= "Editar" data-bg="'.$bgTD.'"><i class="fa fa-pencil-square-o"></i></a>';
/*   $rwList["Estado"] = '<a title="Ver servicio" style="color: black" class="linkBlank" data-toggle="modal" data-toggle="modal" data-target="#modal-test" href="#" title= "Editar"><i class="fa fa-'.icoEsTec($rwList['estTec'],$rwList['estTecVta'], $rwList['estServ'], $rwList['idvta']).'"   onclick="limpiar('.$rwList['idSv'].','.$rwList['Nombre'].','.$rwList['hora'].','.$rwList['Cia'].','.$rwList['nomSer'].','.$rwList['recoger'].','.$rwList['locRec'].','.$rwList['trasladar'].','.$rwList['locTras'].','.$rwList['obs'].')"></i></a>';
  */
  
$id = $rwList['idSv'] ;
  // $rwList["-"] = compImpreso($rwList['idSv']);

  $estado = $rwList["Estado"];
  
  $rwList["Estado"] = '<div class="'.estJeditable1($rwList['idvta']).'" id="estServ-'.$rwList['idSv'].'">'.$estado.'</div>';

$modal = '
<div id="modal-'.$rwList['idSv'].'" class="modal fade" role="dialog">
               <div class="modal-dialog">

                 <!-- Contenido modal - ver servicio-->
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Servicio para: '.$rwList['idSv']." - ".$rwList['Nombre']." ".$rwList['apellidos'].' <span id="copyAnswer-'.$rwList['idSv'].'" class="label label-success"></span></h4>
                   </div>
                   <div class="modal-body">
<div id="textoCopiar-'.$rwList['idSv'].'">'." ".$textMsj." ".date('H:i', strtotime($rwList['Hora']))."-".$rwList['Cia']."-".$rwList['Nombre']."-"
.$rwList['nomSer']."-".$rwList['Recoger']."-".$rwList['Loc']."-".$rwList['Trasladar']."-"
.$rwList['Loc 2']."-".$rwList['obs']."-".ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomRecu']).'</div>
'.ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomRecu']).'</div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default" onclick="copiarAlPortapapeles(\'textoCopiar-'.$rwList['idSv'].'\',\'copyAnswer-'.$rwList['idSv'].'\')">Copiar texto</button>
                     <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                   </div>
                 </div>

               </div>
             </div>';

  $rwList["Icono"] = '<a title="Ver servicio" style="color: black" class="linkBlank" data-toggle="modal" data-toggle="modal" data-target="#modal-'.$rwList['idSv'].'" href="#" title="Editar"><i class="fa fa-'.icoEsTec($rwList['estTec'],$rwList['estTecVta'], $rwList['estServ'], $rwList['idvta']).'"   onclick="limpiar(\'copyAnswer-\' '.$rwList['idSv'].')"></i></a>'.$modal;

  $rwList["Hora"] = '<span data-toggle="tooltip" title="Demora: '.sinHoraSeg($rwList['demora']).'">'.date('H:i', strtotime($rwList['Hora'])).'</span>';
  $rwList["Recoger"] = '<span data-toggle="tooltip" title="'.$rwList['edad'].'-'.$rwList['obs'].'">'.$rwList['Recoger'].'</span>';
 


/*   <a class="" href="#" data-target="#modal-462159" data-toggle="modal" title="Ver servicio">
       <i class="fa fa-ambulance" onclick="limpiar('copyAnswer-462159')"></i>
      </a>
 */
  if (@verUltima($rwList['continuado']) == 1) {
    $rwList['Tipo'] = "U L T I M A";
  } else {
    $rwList['Tipo'] =  $rwList['nomSer'];
  }
  $rwList['Recurso'] = ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomRecu']);
  $rwList['V-Ida'] = mostrarVehiculo2($rwList['vhIda']);
  $rwList['V-Vta'] = mostrarVehiculo2($rwList['vhVta']);

  if($rwList['idvta'] == 3){

    $rwList['Re-ida'] = " SIN IDA";
    $rwList['Fin-ida'] = " SIN IDA";

  } else {

    $rwList['Re-ida'] = sinHoraSeg($rwList['idReco']);
    $rwList['Fin-ida'] = sinHoraSeg($rwList['idFin']);

  }

  if($rwList['idvta'] == 2){

    $rwList['Re-vta'] = " SIN SIN VUELTA";
    $rwList['Fin-vta'] = " SIN VUELTA";

  } else {

    $rwList['Re-vta'] = sinHoraSeg($rwList['vtaReco']);
    $rwList['Fin-vta'] = sinHoraSeg($rwList['vtaFin']);

  }

  
 
  $response[] = $rwList;
}

 echo json_encode(["recordsTotal" => $total,
    "recordsFiltered" => $total, "data" => $response]);


       


