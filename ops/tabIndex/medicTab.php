<?php

$idTable = 'medic';


$keysTable = [
    [
        'data' => '#'
    ],
    [
        'data' => 'Icono'
    ],
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
        'data' => 'Recoger'
    ],
    [
        'data' => 'Loc'
    ],
    [
        'data' => 'Observaciones'
    ],
    [
        'data' => 'Vehiculo'
    ],
    [
        'data' => 'Domicilio'
    ],
    [
        'data' => 'Finalizado'
    ],
    [
        'data' => 'Med'
    ],
    [
        'data' => 'Estado'
    ],
];
$keysMedic = json_encode($keysTable);
?>


<div class="tab-pane active">
  <div class="box-body table-responsive no-padding">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable.service.php'?>
  </div>
  <!-- /.box-body -->
</div>
<script>
  const idTableMedic = 'medic';
  let urlMedic = '/gestambu/www/ops/tabIndex/querys/queryMedic/postGetMedic.php'
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable-medic.js.php' ?>