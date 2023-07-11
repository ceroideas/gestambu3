<?php
session_start();
include '../../functions/function.php';
nonUser();

//Insertar datos en DB
if(@$_POST['guardar'] == 'enviar') {
	
	$medAnterior = trim(mysqli_real_escape_string($gestambu, $_POST['mdAnt']));
	$medCambio   = trim(mysqli_real_escape_string($gestambu, $_POST['mdCamb']));
	
	$segUp = "UPDATE paciente
      SET medAsig='$medCambio'
      WHERE medAsig='$medAnterior' AND segMed='1'
      ";
	if(mysqli_query($gestambu,$segUp)) {
		$mensa   = "Cambio de médico realizado correctamente";
		$mensaOk = '1';
	
		/* Mensajes de log */
		//Pendiente dejar reflejado cambio de médico en la ficha
		/*
		$obsText = "cambio de médico";
		$usuario = $_SESSION['userId'];
		$servicioID = $identi;
		guardarLog('3', $usuario, $obsText, $servicioID);
		*/
	} else {
		$mensa   = "Error: " . $servicioUp . "<br>" . mysqli_error($gestambu);
		$mensaOk = '1';	
	}
}
# Médicos
$medicoA = mysqli_query($gestambu,
  "SELECT userId, usNom, usApe, usCate, usEst
  FROM user
  WHERE usCate ='7'
  ORDER BY usNom ASC
  ");
$medicoC = mysqli_query($gestambu,
  "SELECT userId, usNom, usApe, usCate, usEst
  FROM user
  WHERE usCate ='7' AND usEst ='1'
  ORDER BY usNom ASC
  ");  
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cambio de médico| GestAmbu 3.0 </title>
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
        Cambio de médico:
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Seguimientos médicos</a></li>
        <li class="active">Cambio médico</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Cambio de médico de seguimiento:</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <form action="" method="post" class="form-horizontal">
              <div class="box-body">
				  <!-- Mensajes -->
				  <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
				  <div class="alert alert-warning alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<i class="icon fa fa-check"></i> <?php echo $mensa; ?>
					- Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
				  </div>
				  <?php } ?>
				  <!-- /Mensajes -->			  
				<div class="form-group col-md-2"></div>
				<div class="form-group col-md-4 col-sm-4 col-xs-12">
					<label>Médico actual: </label>
                    <select class="form-control" name="mdAnt">
                      <option value="">-- Selecciona médico actual --</option>
                      <?php
                      while($mdAnt = mysqli_fetch_assoc($medicoA)) {
                        echo "<option value='".$mdAnt['userId']."'>".$mdAnt['usNom']." ".$mdAnt['usApe']."</option>\n";
                      }
                      ?>
                    </select>
				</div>
				<div class="form-group col-md-4 col-sm-4 col-xs-12">
					<label>Médico cambio: </label>
                    <select class="form-control" name="mdCamb">
                      <option value="">-- Selecciona médico a cambiar --</option>
                      <?php
                      while($mdCambio = mysqli_fetch_assoc($medicoC)) {
                        echo "<option value='".$mdCambio['userId']."'>".$mdCambio['usNom']." ".$mdCambio['usApe']."</option>\n";
                      }
                      ?>
                    </select>
				</div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <div class="col-md-8 col-sm-8"></div>
                  <button type="reset" class="btn btn-default">Cancelar</button>
                  <button type="submit" class="btn btn-info" name="guardar" value="enviar" >Cambiar</button>
              </div>
              <!-- /.box-footer-->
            </form>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
	  
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include '../inc/pie.php'; ?>

<?php include '../inc/bcontrol.php'; ?>

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
<!-- Validación nuevo usuario -->
<!-- <script src="/ops/js/validacionNuser.js"></script> -->
</body>
</html>
<?php
mysqli_close($gestambu);
?>
