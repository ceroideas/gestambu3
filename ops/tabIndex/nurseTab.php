<?php

$idTable = 'nurse';


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
        'data' => 'Final'
    ],
    [
        'data' => 'Due'
    ],
    [
        'data' => 'Estado'
    ],
];
$keysNurse = json_encode($keysTable);
?>


<div class="tab-pane active">
  <div class="box-body table-responsive no-padding">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable.service.php'?>
  </div>
  <!-- /.box-body -->
</div>
<script>
  const idTableNurse = 'nurse';
  let urlNurse = '/gestambu/www/ops/tabIndex/querys/queryNurse/postGetNurse.php'
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable-nurse.js.php' ?>