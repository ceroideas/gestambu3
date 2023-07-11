<?php
session_start();
include '../../../functions/function.php';
nonUser();

if(isset($_POST['diaMostrar'])) {
  $diaMostrar = $_POST['diaMostrar'];
} else {
  $diaMostrar = date("Y-m-d");
}

$finiFor = $diaMostrar." 00:00:00";
$ffinFor = $diaMostrar." 23:59:59";

$lstNota = mysqli_query($gestambu, "SELECT notas.idNota, notas.descNota, notas.vhId, notas.userId, notas.tecnico, notas.notaEst, notas.cerrada, DATE_FORMAT(notas.creado, '%d-%m-%Y %H:%i:%s') AS creadoFor, DATE_FORMAT(notas.cerrada, '%d-%m-%Y %H:%i:%s') AS closeFor,
  user.userId, user.usNom, user.usApe, vehiculo.idVh, vehiculo.matricula
  FROM notas
    LEFT JOIN user ON notas.userId = user.userId
    LEFT JOIN vehiculo ON notas.vhId = vehiculo.idVh
  WHERE notas.creado BETWEEN '$finiFor' AND '$ffinFor'
  ORDER BY notas.creado ASC
  ");

$numLibreta = mysqli_num_rows($lstNota);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Notas de vehículos | GestAmbu 3.0</title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/docs/bootstrap/css/bootstrap.min.css">
  <!-- Fuentes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tema -->
  <link rel="stylesheet" href="/docs/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/docs/dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  <style>
  .sinmar {
    margin: 1px;
    padding-top: 3px;
    padding-bottom: 3px;
  }
  .table>tbody>tr>td {
    padding: 1px;
  }
  .aumText {
    font-size: 0.85em;
  }
  .alert a {
    text-decoration: none;
    color: #98FB98;
  }
  @media print {
    a[href]:after {
      content: none !important;
    }
  }
  .box {
	  padding-left: 5px;
	  padding-right: 5px;
  }
  .nav-tabs-custom>.tab-content {
	  padding: 2px;
  }

  </style>
  </style>
</head>
<!-- Se agrega la clase sidebar-collapse para ocultar el menu en la carga del sitio -->
<!-- fixed para mantener menu, pero al estar minimizado se expande automanicamente,
fixed no es compatible con sidebar-mini -->
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<!-- Barra de sitio -->
<div class="wrapper">

<?php include '../../inc/supbar.php'; ?>

<?php include '../../inc/menubar.php'; ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- fecha actual o fecha si se escoge otra para visualizar -->
        <!-- Espacio reservado para texto -->
        <small><?php echo fechaEs(); ?></small>
      </h1>
      <!-- Navegador de posición de página (migas) -->
      <ol class="breadcrumb">
        <li class=""><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Notas </li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-file"></i>
                Notas de vehículos
              </h3>
              <!-- opciones para poder cerrar ventana o contraer -->
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
              <div class="col-md-4 col-md-offset-4">
                <!-- form start -->
                <form class="form-horizontal" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="fechaConsu" class="col-sm-2 control-label">Fecha:</label>

                      <div class="col-md-6 col-sm-10">
                        <input type="date" class="form-control" name="diaMostrar" value="<?php echo $diaMostrar; ?>">
                        <button type="submit" class="btn btn-default">Consultar</button>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-footer -->
                </form>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
      <!-- Listado sortable -->
      <div class="box box-primary">
        <div class="box-header">
          <i class="ion ion-clipboard"></i>

          <h3 class="box-title">Servicios para día: <strong> <?php echo cambiarFecha($diaMostrar); ?></strong></h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if($numLibreta == 0) { echo "No existen resultados para la fecha seleccionada."; } else { ?>
          <div class="box-body table-condensed no-padding">
            <table class="table table-hover aumText">
                <tr>
                  <th></th>
                  <th>Para:</th>
                  <th>Enviada</th>
                  <th>Usuario</th>
                  <th>Texto</th>
                  <th>Vista/cerrada</th>
                  <th>#</th>
                </tr>
              <tbody class="todo-list">
                <?php while($rwList = mysqli_fetch_array($lstNota)) { ?>
                <tr>
                  <td><?php echo $rwList['idNota']; ?></td>
                  <td><?php echo $rwList['matricula']; ?></td>
                  <td><?php echo $rwList['creadoFor']; ?></td>
                  <td><?php echo $rwList['usNom']." ".$rwList['usApe']; ?></td>
                  <td><?php echo $rwList['descNota']; ?></td>
                  <td><?php mostrarTecnico($rwList['tecnico']); ?></td>
                  <td><?php if($rwList['notaEst'] == '0') { echo $rwList['closeFor']; } ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <?php } ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix no-border">

        </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include '../../inc/pie.php'; ?>

<?php //include '../../inc/bcontrol.php'; ?>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
<script src="/docs/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="/docs/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/docs/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/docs/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/docs/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/docs/dist/js/demo.js"></script>
<!-- Table Expandible -->
<script src="/docs/plugins/tableExp/js/bootstrap-table-expandable.js"></script>
<!-- Jeditable -->
<script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
<script src="/ops/referencia/jeditable/jeditableLibreta.js"></script>
<!-- Sortable jquerUI -->
<script>
$( function() {
  $( "#sortable" ).sortable({
    placeholder: "ui-state-highlight",
    update: function(event, ui) {
      var ordenPuntos = $(this).sortable('toArray').toString();
      $.ajax({
        type: 'POST',
        url: '/ops/referencia/libreta/reordenarSlider.php',
        dataType: 'json',
        data: {
          accion: 'ordenar',
          puntos: ordenPuntos
        }
      });
    }
  });
  $( "#sortable" ).disableSelection();
});
</script>
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
