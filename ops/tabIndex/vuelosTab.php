<?php
include $_SERVER['DOCUMENT_ROOT'].'/ops/tabIndex/querys/getFlyes.php';
include $_SERVER['DOCUMENT_ROOT'].'/utils/utils.php';

$idTable = 'flyes';
$keysTable = [
  [
      'data' => '#'
  ],
  [
      'data' => 'Fecha'
  ],
  [
      'data' => 'Hora'
  ],
  [
      'data' => 'Aseguradora'
  ],
  [
      'data' => 'Nombre'
  ],
  [
      'data' => 'Tipo'
  ],
  [
      'data' => 'Id/Vta'
  ],
  [
      'data' => 'Medico'
  ],
  [
      'data' => 'Due'
  ],
  [
      'data' => 'Pediatra'
  ],
  [
      'data' => 'Incubadora'
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
    'data' => 'Loc2'
  ],
  [
    'data' => 'Medico2'
  ],
  [
    'data' => 'Due2'
  ],
  [
    'data' => 'Pediatra2'
  ],
  [
    'data' => 'Estado'
  ],

];

$keys = json_encode($keysTable);

?>


<div class="tab-pane active">
  <div class="box-body table-responsive no-padding">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable.service.php'?>
  </div>
  <!-- /.box-body -->
</div>
<script>
  const idTable = 'flyes';
  let url = '/gestambu/www/ops/tabIndex/querys/postGetFlyes.php'
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/services/datatable/datatable.js.php' ?>
