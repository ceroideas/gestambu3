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

/* Listado Málaga */
$lstMa = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,	  
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16') AND servicio.provincia = '29'
  GROUP BY servicio.IdCia
  ");

/* Listado Sevilla */
$lstSe = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,	
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16') AND servicio.provincia = '41'
  GROUP BY servicio.IdCia
  ");

/* Listado Cádiz */
$lstCa = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,	
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16') AND servicio.provincia = '11'
  GROUP BY servicio.IdCia
  ");

/* Listado Huelva */
$lstHu = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,	
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16') AND servicio.provincia = '21'
  GROUP BY servicio.IdCia
  ");

/* Listado Córdoba */
$lstCo = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,	
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16') AND servicio.provincia = '14'
  GROUP BY servicio.IdCia
  ");

/* Listado Melilla */
$lstMe = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,	
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16') AND servicio.provincia = '52'
  GROUP BY servicio.IdCia
  ");

/* Listado Completo */
$lsTotal = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.provincia, servicio.tipo, servicio.idvta, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fecha, servicio.estServ,
      SUM(IF(servicio.recurso = '1' AND servicio.idvta !='1', 1, 0)) AMB,
	  SUM(IF(servicio.recurso = '1' AND servicio.idvta = '1', 1, 0)) AMBVTA,
	  SUM(IF(servicio.recurso = '1', 1, 0)) AMB,
      SUM(IF(servicio.recurso = '2', 1, 0)) DUE,
      SUM(IF(servicio.recurso = '3' AND servicio.idvta !='1', 1, 0)) UVI,
	  SUM(IF(servicio.recurso = '3' AND servicio.idvta ='1', 1, 0)) UVIVTA,
      SUM(IF(servicio.recurso = '4', 1, 0)) VM,
      SUM(IF(servicio.recurso = '5', 1, 0)) TAXI,
      SUM(IF(servicio.recurso = '6', 1, 0)) CTLF,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta !='1', 1, 0)) RUTA,
	  SUM(IF(servicio.recurso = '7' AND servicio.idvta = '1', 1, 0)) RUTAVTA,
    cia.idCia, cia.ciaNom
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
  WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15', '16')
  ");
$rwTotal = mysqli_fetch_assoc($lsTotal);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Número de servicios </title>
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
        <li class="active">Servicios entre fechas</li>
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
          <h3 class="box-title">Resultados del filtrado: </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-conndensed">
            <tr>
              <th>Aseguradora</th>
              <th>Ambulancia</th>
              <th>UVI</th>
              <th>V_médica</th>
              <th>Due</th>
              <th>Taxi</th>
			  <th>Ruta</th>
              <th>C_Tlf</th>
            </tr>
            <tr>
              <th colspan="8" class="bg-primary" >Cádiz</th>
            </tr>
            <?php while($rwCa = mysqli_fetch_assoc($lstCa)) { ?>
            <tr>
              <td><?php echo $rwCa['ciaNom']; ?></td>
              <td><?php echo $rwCa['AMB'] + (2 * $rwCa['AMBVTA']);?></td>
              <td><?php echo $rwCa['UVI'] + (2 * $rwCa['UVIVTA']); ?></td>
              <td><?php echo $rwCa['VM']; ?></td>
              <td><?php echo $rwCa['DUE']; ?></td>
              <td><?php echo $rwCa['TAXI']; ?></td>
			  <td><?php echo $rwCa['RUTA'] + (2 * $rwCa['RUTAVTA']); ?></td>
              <td><?php echo $rwCa['CTLF']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <th colspan="8" class="bg-primary" >Córdoba</th>
            </tr>
            <?php while($rwCa = mysqli_fetch_assoc($lstCo)) { ?>
            <tr>
              <td><?php echo $rwCa['ciaNom']; ?></td>
              <td><?php echo $rwCa['AMB'] + (2 * $rwCa['AMBVTA']);?></td>
              <td><?php echo $rwCa['UVI'] + (2 * $rwCa['UVIVTA']); ?></td>
              <td><?php echo $rwCa['VM']; ?></td>
              <td><?php echo $rwCa['DUE']; ?></td>
              <td><?php echo $rwCa['TAXI']; ?></td>
			  <td><?php echo $rwCa['RUTA'] + (2 * $rwCa['RUTAVTA']); ?></td>
              <td><?php echo $rwCa['CTLF']; ?></td>
            </tr>
            <?php } ?>			
            <tr>
              <th colspan="8" class="bg-primary" >Málaga</th>
            </tr>
            <?php while($rwMa = mysqli_fetch_assoc($lstMa)) { ?>
            <tr>
              <td><?php echo $rwMa['ciaNom']; ?></td>
              <td><?php echo $rwMa['AMB'] + (2 * $rwMa['AMBVTA']);?></td>
              <td><?php echo $rwMa['UVI'] + (2 * $rwMa['UVIVTA']); ?></td>
              <td><?php echo $rwMa['VM']; ?></td>
              <td><?php echo $rwMa['DUE']; ?></td>
              <td><?php echo $rwMa['TAXI']; ?></td>
			  <td><?php echo $rwMa['RUTA'] + (2 * $rwMa['RUTAVTA']); ?></td>
              <td><?php echo $rwMa['CTLF']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <th colspan="8" class="bg-primary" >Melilla</th>
            </tr>
            <?php while($rwMa = mysqli_fetch_assoc($lstMe)) { ?>
            <tr>
              <td><?php echo $rwMa['ciaNom']; ?></td>
              <td><?php echo $rwMa['AMB'] + (2 * $rwMa['AMBVTA']);?></td>
              <td><?php echo $rwMa['UVI'] + (2 * $rwMa['UVIVTA']); ?></td>
              <td><?php echo $rwMa['VM']; ?></td>
              <td><?php echo $rwMa['DUE']; ?></td>
              <td><?php echo $rwMa['TAXI']; ?></td>
			  <td><?php echo $rwMa['RUTA'] + (2 * $rwMa['RUTAVTA']); ?></td>
              <td><?php echo $rwMa['CTLF']; ?></td>
            </tr>
            <?php } ?>			
            <tr>
              <th colspan="8" class="bg-primary" >Sevilla</th>
            </tr>
            <?php while($rwSe = mysqli_fetch_assoc($lstSe)) { ?>
            <tr>
              <td><?php echo $rwSe['ciaNom']; ?></td>
              <td><?php echo $rwSe['AMB'] + (2 * $rwSe['AMBVTA']);?></td>
              <td><?php echo $rwSe['UVI'] + (2 * $rwSe['UVIVTA']); ?></td>
              <td><?php echo $rwSe['VM']; ?></td>
              <td><?php echo $rwSe['DUE']; ?></td>
              <td><?php echo $rwSe['TAXI']; ?></td>
			  <td><?php echo $rwSe['RUTA'] + (2 * $rwSe['RUTAVTA']); ?></td>
              <td><?php echo $rwSe['CTLF']; ?></td>
            </tr>
            <?php } ?>			
            <tr>
              <th colspan="8" class="bg-primary" >Huelva</th>
            </tr>
            <?php while($rwHu = mysqli_fetch_assoc($lstHu)) { ?>
            <tr>
              <td><?php echo $rwHu['ciaNom']; ?></td>
              <td><?php echo $rwHu['AMB'] + (2 * $rwCa['AMBVTA']);?></td>
              <td><?php echo $rwHu['UVI'] + (2 * $rwCa['UVIVTA']); ?></td>
              <td><?php echo $rwHu['VM']; ?></td>
              <td><?php echo $rwHu['DUE']; ?></td>
              <td><?php echo $rwHu['TAXI']; ?></td>
			  <td><?php echo $rwHu['RUTA'] + (2 * $rwCa['RUTAVTA']); ?></td>
              <td><?php echo $rwHu['CTLF']; ?></td>
            </tr>
            <?php } ?>

            <tr>
              <th colspan="8" class="bg-primary" >Total</th>
            </tr>
            <tr>
              <td></td>
              <td><?php echo $rwTotal['AMB'] + (2 * $rwTotal['AMBVTA']);?></td>
              <td><?php echo $rwTotal['UVI'] + (2 * $rwTotal['UVIVTA']); ?></td>
              <td><?php echo $rwTotal['VM']; ?></td>
              <td><?php echo $rwTotal['DUE']; ?></td>
              <td><?php echo $rwTotal['TAXI']; ?></td>
			  <td><?php echo $rwTotal['RUTA'] + (2 * $rwTotal['RUTAVTA']); ?></td>
              <td><?php echo $rwTotal['CTLF']; ?></td>
            </tr>
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
