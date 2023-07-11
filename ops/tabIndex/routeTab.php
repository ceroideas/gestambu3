<?php

$idTable = 'route';


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
        'data' => 'Loc-2'
    ],
    [
        'data' => 'V-ida'
    ],
    [
        'data' => 'V-vta'
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
$keysRoute = json_encode($keysTable);
?>


<div class="tab-pane active">
    <div class="box-body table-responsive no-padding">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/services/datatable/datatable.service.php' ?>
    </div>
    <!-- /.box-body -->
</div>
<script>
    const idTableRoute = 'route';
    let urlRoute = '/gestambu/www/ops/tabIndex/querys/queryRoute/postGetRoute.php'
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/services/datatable/datatable-route.js.php' ?>