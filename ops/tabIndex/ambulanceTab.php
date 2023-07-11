<?php



$idTable = 'ambulance';


$keysTable = [
    [
        'data' => '#'
    ],
    [
        'data' => 'Icono'
    ],
    /*[
        'data' => '-'
    ],*/
    [
        'data' => 'Hora'
    ],
    [
        'data' => 'Provincia'
    ],
    [
        'data' => 'Nombre'
    ],
    [
        'data' => 'Cia'
    ],
    [
        'data' => 'Tipo'
    ],
    [
        'data' => 'Recurso'
    ],
    [
        'data' => 'Recoger'
    ],
    [
        'data' => 'Loc'
    ],
    [
        'data' => 'Trasladar'
    ],
    [
        'data' => 'Loc 2'
    ],
    [
        'data' => 'V-Ida'
    ],
    [
        'data' => 'V-Vta'
    ],
    [
        'data' => 'Re-ida'
    ],
    [
        'data' => 'Fin-ida'
    ],
    [
        'data' => 'Re-vta'
    ],
    [
        'data' => 'Fin-vta'
    ],
    [
        'data' => 'Estado'
    ],
];
$keysAmbulance = json_encode($keysTable);
?>


<div class="tab-pane active">

  <div class="box-body table-responsive no-padding">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable.service.php'?>
  </div>
  <!-- /.box-body -->
</div>
<script>
  const idTableAmbulance = 'ambulance';
  let urlAmbulalnce = '/gestambu/www/ops/tabIndex/querys/querysAmbulance/postGetAmbulance.php'
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable-ambulance.js.php' ?>