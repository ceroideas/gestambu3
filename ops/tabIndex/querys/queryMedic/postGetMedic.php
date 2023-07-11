<?php  
include $_SERVER['DOCUMENT_ROOT'] . '/functions/function.php';
$limit = 10;
$offset = 10;

if(isset($_POST['start'])) {
  $offset = $_POST['start'];
}

if(isset($_POST['length'])) {
  $limit = $_POST['length'];
}

if(isset($_GET['prov'])) {
    if($_GET['prov'] == 0 ) {
      $provTab = "'29', '11', '41', '21', '52', '14'";
    }  /*elseif($_GET['prov'] == 41) { // Excluido por peticion de que se dejen de mostrar los servicios de huelva en sevilla
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

  $search = $_POST['search']['value'];
  
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
    $finalWhere = "NOT IN('10', '14', '15', '16','17')";
  }

  function getMedic()
  {
    global $gestambu;
    global $varFecha;
    global $finalWhere;
    global $offset;
    global $limit;
    global $provTab;
    global $search;

  
    $query = "SELECT  servicio.idSv , servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre as Nombre, servicio.recoger as Recoger,
    servicio.locRec as Loc, servicio.trasladar, servicio.locTras, servicio.estServ, servicio.apellidos, servicio.obs, servicio.creadoNu, servicio.edad, cia.idCia, cia.ciaNom as Cia,
    servi.idServi, servi.nomSer as Tipo,
    recurso.idRecu, recurso.nomRecu,
    estados.idEst, estados.vaEst,
    serhorario.horaId, serhorario.idRefSv, DATE_FORMAT(serhorario.idReco, '%H:%i') AS idReco, DATE_FORMAT(serhorario.idFin, '%H:%i') AS idFin, DATE_FORMAT(serhorario.vtaReco, '%H:%i') AS vtaReco, DATE_FORMAT(serhorario.vtaFin, '%H:%i') AS vtaFin,
    serestados.idSv, serestados.vhIda, serestados.vhVta, serestados.estTec, serestados.estTecVta,
    serinfo.idSv, DATE_FORMAT(serinfo.demora, '%H:%i') AS demora, serinfo.prioridad,
    serpersonal.idSv,serpersonal.medIda ";
    $query2 = "FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
    LEFT JOIN estados ON servicio.estServ = estados.idEst
    LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
    LEFT JOIN serpersonal ON servicio.idSv = serpersonal.idSv
    WHERE Nombre like '%".$search."%'
        AND servicio.fecha='$varFecha'
        AND servicio.recurso IN('4','6')
        AND servicio.estServ ".$finalWhere."
        AND servicio.provincia IN ($provTab)
    ORDER BY hora ASC";

    $total = mysqli_query($gestambu, "SELECT COUNT(*) as total ".$query2);
    $query = mysqli_query($gestambu, $query.$query2." limit $limit offset $offset");

    return [$query,mysqli_fetch_object($total)->total];
  }
  
  $data = getMedic();
  $total = $data[1];
  $data = $data[0];

  $response = [];
  while ($rwList = $data->fetch_assoc()) {
    $bgTD = "";

    if(nuevoSer($rwList['creadoNu'], $rwList['estServ']) == 1) {
         $bgTD = "newSer";
         $colBlanco = 1;
     $prefe = "";
       } else {
         if(@verUltima($rwList['continuado']) == 1) {
           $bgTD = "bg-ultima";
           $colBlanco = 1;
      $prefe = "";       
         } else {
           if($rwList['prioridad'] == 1) {
             $bgTD = "bg-urgente";
             $colBlanco = 1;
       $prefe = "URGENTE";       
           } elseif($rwList['prioridad'] == 2) {
             $bgTD = "bg-preferente";
             $colBlanco = 1;
       $prefe = "PREFERENTE";
           } else {
             if(@$icont++ % 2) {
               $bgTD = "colorZebra";
               $colBlanco = 0;
         $prefe = "";
             } else {
               $colBlanco = 0;
         $prefe = "";
             }
           }
         }
       }


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
<p id="textoCopiar-'.$rwList['idSv'].'">'.$prefe."-".date('H:i', strtotime($rwList['hora']))."-".$rwList['Cia']."-".$rwList['Nombre']."-"
.$rwList['Tipo']."-".$rwList['Recoger']."-".$rwList['Loc']."-".$rwList['trasladar']."-"
.$rwList['locTras']."-".$rwList['edad']."-".$rwList['obs'].'
</p>'.ambComple2($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['nomRecu']).'
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

      $rwList["Hora"] = '<span data-toggle="tooltip" title="Demora: '.sinHoraSeg($rwList['demora']).'">'.date('H:i', strtotime($rwList['hora'])).'</span>';
      $rwList['Provincia'] = provValor2($rwList['provincia']);
       $rwList['Observaciones'] = $rwList['edad']."-".substr($rwList['obs'],0,40);
       $rwList['Vehiculo'] = mostrarVehiculo2($rwList['vhIda']);
    $rwList['Domicilio'] = $rwList['idReco'];
    $rwList['Finalizado'] = $rwList['idFin'];
    $rwList['Med'] = "";
    if($rwList['medIda'] != '0')
    {
      $rwList['Med'] = $rwList['medIda'];
    }

    $rwList['Estado'] = $rwList['vaEst'];

    $estado = $rwList["Estado"];
  
    $rwList["Estado"] = '<div class="'.estJeditable1($rwList['idvta']).'" id="estServ-'.$rwList['idSv'].'">'.$estado.'</div>';

    $response[] = $rwList;
}

  echo json_encode(["recordsTotal" => $total,
    "recordsFiltered" => $total, "data" => $response]);