<?php
session_start();
include '../functions/function.php';
nonUser();
$usuario = $_SESSION['userId'];

/* Pendiente */
# comprobación de km de vehículo
# cambio de Ambulancia
# sistema de registro de guardia (en base)
  # restricción de inicio de guardia si no se está en base
# guardado de posición gps -> depende de htmls
# parte de trabajo digital
  # guardado de parte de trabajo según los servicios que realice el técnico
# guardias o servicios de enfermeros o médicos
  # ¿cómo registrar vuelos sanitarios?

# Ambulancia
$mosAmbu = mysqli_query($gestambu, "SELECT reguardia.idGuardia, reguardia.cate, reguardia.idUser, reguardia.gEst,
  regambu.idGuardia, regambu.ambu, regambu.estAmbu,
  vehiculo.idVh, vehiculo.matricula
  FROM reguardia
    LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
    LEFT JOIN vehiculo ON regambu.ambu = vehiculo.idVh
  WHERE reguardia.gEst = 1 AND reguardia.idUser = '$usuario' AND regambu.estAmbu IN('1','3') ");
$rwMosAmbu = mysqli_fetch_assoc($mosAmbu);
$ambUser = $rwMosAmbu['ambu'];
$cate    = $rwMosAmbu['cate'];

# Número de servicios
$serPendientes = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.estServ, serestados.idSv, serestados.vhIda, serestados.vhVta
  FROM servicio
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
  WHERE servicio.estServ IN ('2', '11') AND serestados.vhIda = '$ambUser'
    OR servicio.estServ = '5' AND serestados.vhVta = '$ambUser'
  ");
$numSErPent = mysqli_num_rows($serPendientes);

# Último repostaje
$repo = mysqli_query($gestambu, "SELECT userId, vhId, kmRepo, envio FROM repostaje WHERE vhId='$ambUser' ORDER BY kmRepo DESC LIMIT 1");
$rwRepo = mysqli_fetch_array($repo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inicio | GestAmbu 3.0 </title>
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

<?php include 'inc/supbar.php'; ?>
<?php include 'inc/menubar.php'; ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pantalla principal
        <small>Información de usuario</small>
      </h1>
      <ol class="breadcrumb">
        <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Info boxes -->
      <div class="row">
	  <!-- Mensaje manual para técnicos -->
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-info"></i> ¡Notificación de diagnósticos! (4.- <i class="fa fa-sign-out"></i>)</h4> 
				A partir del 09-04-18 es obligatorio la notificación de los diagnósticos para todas las visitas médicas.
				<p></p>
				<p>Se han de notificar al finalizar el servicio.</p>
				
			</div>
		</div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-ambulance"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ambulancia Actual:</span>
              <span class="info-box-number"><?php echo $rwMosAmbu['matricula'] ?></span>
              <span class="info-box-number"><small><?php echo mostCate($cate); ?></small></span>
              <!-- Especificar los km actuales y la fecha de inicio-->
              <!-- Mostrar sin guardia registrada -->
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="servicios/general.php">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-file-text-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Servicios programados: </span>
                <span class="info-box-number"><?php echo $numSErPent; ?></span>
                <span class="info-box-number"><small>Selecciona para mostar</small></span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </a>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-dashboard"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Último repostaje: </span>
              <span class="info-box-number"><?php echo $rwRepo['kmRepo']." Km"; ?></span>
              <span><h6><?php echo $rwRepo['envio']; ?></h6></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa">O<sub>2</sub></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Oxígeno: </span>
              <span class="info-box-number">30<small>%</small></span>
			        <span class="info-box-number"><small>(sin función - es un ejemplo)</small></span>
            </div>

          </div>

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Default box -->
      <!-- // descomentar para ventana de mensajes
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Texto de ejemplo</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          Comenzar aquí
        </div>
        <div class="box-footer">
          Pie
        </div>

      </div>
      -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include 'inc/pie.php'; ?>
<?php //include 'inc/bcontrol.php'; ?>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../docs/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../docs/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../docs/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../docs/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../docs/dist/js/demo.js"></script>
<!-- Table Expandible -->
<script src="../docs/plugins/tableExp/js/bootstrap-table-expandable.js"></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
