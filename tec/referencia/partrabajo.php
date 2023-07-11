<?php
session_start();
include '../../functions/function.php';
nonUser();
$usuario = $_SESSION['userId'];

$sqlPart = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.fecha, servicio.hora, servicio.tipo, servicio.recurso, servicio.nombre, servicio.locRec, servicio.locTras,
    servi.idServi, servi.nomSer, servi.icono,
    recurso.idRecu, recurso.recuCorto,
    partrabajo.idSv, partrabajo.idUser, partrabajo.kmReco, partrabajo.kmFin, partrabajo.idvta,
    serhorario.idRefSv, serhorario.idReco, serhorario.idFin
  FROM servicio
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
    LEFT JOIN partrabajo ON servicio.idSv = partrabajo.idSv
    LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
  WHERE partrabajo.idUser = '$usuario'
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Parte de trabajo | GestAmbu 3.0 </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/docs/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/docs/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="/docs/dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="/docs/plugins/tableExp/css/bootstrap-table-expandable.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">

<?php include '../inc/supbar.php'; ?>
<?php include '../inc/menubar.php'; ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Parte de trabajo
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><a href="#"> Parte de trabajo</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="box box-info no-print">
        <div class="box-header with-border">
          <h3 class="box-title">Parte de trabajo</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-vertical">
          <div class="box-body">
            <div class="form-group">
              <div class="col-sm-2">
                <input type="date" class="form-control" id="inputEmail3" placeholder="Inicio">
              </div>
              <div class="col-sm-2">
                <input type="date" class="form-control" id="inputEmail3" placeholder="Fin">
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-info">Consultar</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Parte de trabajo</h3><small> del 21-10-17 al 21-10-17</small>
              <h5>Nombre del técnico</h5>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Id</th>
                  <th>Paciente</th>
                  <th>Tipo</th>
                  <th>Trayecto</th>
                  <th>Km/hora Inicio</th>
                  <th>Km/hora Fin</th>
                  <th>Recurso</th>
                </tr>
                <!--
                  Seguir aquí - Cambiar localizades de recogida según el servicio haya sido de ida o vuelta
                  se puede comprobar en parte de trabajo en la columna idavta
                  hay que aplicar filtro de ambulancia para saber exactamente si fue de ida o vuelta
                -->
                <?php
                  while ($rwPart = mysqli_fetch_assoc($sqlPart)) {
                    $selIV = $rwPart['idvta'];
                ?>
                <tr>
                  <td rowspan="2" align="center"><?php echo $rwPart['idSv']; ?></td>
                  <td rowspan="2"><?php echo $rwPart['nombre']; ?></td>
                  <td rowspan="2"><i class="fa fa-<?php echo $rwPart['icono']; ?>"></i></td>
                  <td><?php echo $rwPart['locRec']; ?></td>
                  <td><?php echo $rwPart['kmReco']; ?></td>
                  <td><?php echo $rwPart['kmFin']; ?></td>
                  <td rowspan="2"><?php echo $rwPart['recuCorto']; ?></td>
                </tr>
                <tr>
                  <td><?php echo $rwPart['locTras']; ?></td>
                  <td><?php if($selIV == 2) { echo $rwPart['idReco']; } elseif($selIV == 3 ) { echo $rwPart['vtaReco']; } ?></td>
                  <td><?php if($selIV == 2) { echo $rwPart['idFin']; } elseif($selIV == 3) { echo $rwPart['vtaFin']; } ?></td>
                </tr>
              <?php } ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include 'inc/pie.php'; ?>
<?php //include 'inc/bcontrol.php'; ?>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
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
</body>
