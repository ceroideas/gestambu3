<?php
session_start();
include '../../functions/function.php';
nonUser();

if(isset($_POST['fIni'])) {
  $diaIni = $_POST['fIni'];
} else {
  $diaIni = date("Y-m-d");
}

if(isset($_POST['fFin'])) {
  $diaFin = $_POST['fFin'];
} else {
  $diaFin = date("Y-m-d");
}
/* Listados cómputo */
$lisTras = mysqli_query($gestambu, "SELECT provincia, fecha, continuado, codRuta, tipo, estServ, idvta,
		SUM(IF(recurso = '4', 1, 0)) VM,
		SUM(IF(recurso = '4', 1, 0)) VM,
		SUM(IF(recurso = '5', 1, 0)) TAXI,
		SUM(IF(tipo IN('15','18','23'), 1, 0)) EVENTOS,
		SUM(IF(recurso = '7', 1, 0) AND (codRuta > '0') AND (idvta != '1')) RUTASIM,
		SUM(IF(recurso = '7', 1, 0) AND (codRuta > '0') AND (idvta = '1')) RUTAIV,
		SUM(IF(recurso = '1', 1, 0) AND (continuado > '0') AND (idvta = '1')) IDVTACOM,
		SUM(IF(recurso = '1', 1, 0) AND (continuado > '0') AND (idvta != '1')) IDVTASIM,
		SUM(IF(recurso = '3', 1, 0) AND (continuado = '0') AND (codRuta = '0') AND (idvta = '1')) SVACOM,
		SUM(IF(recurso = '3', 1, 0) AND (continuado = '0') AND (codRuta = '0') AND (idvta != '1')) SVASIM,
		SUM(IF(recurso = '1', 1, 0) AND (continuado = '0') AND (codRuta = '0') AND (idvta = '1')) SVBCOM,
		SUM(IF(recurso = '1', 1, 0) AND (continuado = '0') AND (codRuta = '0') AND (idvta != '1')) SVBSIM
	FROM servicio
	WHERE fecha BETWEEN '$diaIni' AND '$diaFin' AND estServ !='15'
	GROUP BY provincia
");

/* Listados vuelos */
$lisVuelo = mysqli_query($gestambu, "SELECT tipo, fecha, estVuelo,
		SUM(IF(tipo = '1', 1, 0)) CONV,
		SUM(IF(tipo = '2', 1, 0)) CRIT,
		SUM(IF(tipo = '3', 1, 0)) RETOR,
		SUM(IF(tipo = '4', 1, 0)) TRAS
	FROM vuelosanitario
	WHERE fecha BETWEEN '$diaIni' AND '$diaFin' AND estVuelo !='15'
");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cómputo | GestAmbu 3.0 </title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/docs/bootstrap/css/bootstrap.min.css">
  <!-- Fuentes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/docs/plugins/datatables/dataTables.bootstrap.css">
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
  @media print {
    a[href]:after {
      content: none !important;
    }
  }
/*
  .sinmar {
    margin: 1px;
    padding-top: 3px;
    padding-bottom: 3px;
  }
  .table>tbody>tr>td {
    padding: 1px;
  }
  .aumText {
    font-size: 0.95em;
  }
  .alert a {
    text-decoration: none;
    color: #98FB98;
  }
*/
  </style>
</head>
<!-- Se agrega la clase sidebar-collapse para ocultar el menu en la carga del sitio -->
<!-- fixed para mantener menu, pero al estar minimizado se expande automanicamente,
fixed no es compatible con sidebar-mini -->
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<!-- Barra de sitio -->
<div class="wrapper">

<?php include '../inc/supbar.php'; ?>

<?php include '../inc/menubar.php'; ?>

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
        <li class="active"><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Estadísticas</li>
        <li class="active">Cómputo</li>
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
                Filtro para la tabla:
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
              <div class="col-md-8 col-md-offset-3">
                <!-- form start -->
                <form class="form-vertical" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group col-md-3 col-sm-3 col-xs-4">
                      <label>Inicio: </label>
                      <div class="input-group">
                        <input type="date" class="form-control" placeholder="DNI sin guiones ni espacios" name="fIni" value="<?php echo $diaIni; ?>">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-4">
                      <label>Final: </label>
                      <div class="input-group">
                        <input type="date" class="form-control" placeholder="DNI sin guiones ni espacios" name="fFin" value="<?php echo $diaFin; ?>">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-4">
                      <label> &nbsp; </label>
                      <div class="input-group">
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

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Ambulancia: </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-conndensed">
            <tr>
              <th>PROVINCIA</th>
              <th>S.V.A.</th>
              <th>S.V.B.</th>
              <th>ID/VTA</th>
              <th>RUTA</th>
              <th>V_M</th>
			  <th>TAXI</th>
              <th>EVENTOS</th>
            </tr>
			<?php while($rwList = mysqli_fetch_array($lisTras)) { ?>
            <tr>
			  <td><?php provValor($rwList['provincia']);?></td>
			  <td><?php echo (($rwList['SVACOM']*2) + $rwList['SVASIM']);?></td>
			  <td><?php echo (($rwList['SVBCOM']*2) + $rwList['SVBSIM']);?></td>
			  <td><?php echo (($rwList['IDVTACOM']*2)+$rwList['IDVTASIM']);?></td>
			  <td><?php echo (($rwList['RUTAIV']*2)+$rwList['RUTASIM']);?></td>
			  <td><?php echo $rwList['VM'];?></td>
			  <td><?php echo $rwList['TAXI'];?></td>
			  <td><?php echo $rwList['EVENTOS'];?></td>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Vuelos sanitarios: </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-conndensed">
            <tr>
              <th>CONVENCIONAL</th>
              <th>CRITICO</th>
              <th>RETORNO</th>
              <th>TRASPLANTE</th>
            </tr>
			<?php while($rwVuelo = mysqli_fetch_array($lisVuelo)) { ?>
            <tr>
			  <td><?php echo $rwVuelo['CONV'];?></td>
			  <td><?php echo $rwVuelo['CRIT'];?></td>
			  <td><?php echo $rwVuelo['RETOR'];?></td>
			  <td><?php echo $rwVuelo['TRAS'];?></td>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
	  
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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
<!-- DataTables -->
<script src="/docs/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/docs/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/docs/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/docs/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/docs/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/docs/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({
      //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
      "pageLength": 25,
      "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
