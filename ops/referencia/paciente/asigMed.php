<?php
session_start();
include '../../../functions/function.php';
nonUser();


//Insertar datos en DB
if(@$_POST['guardar'] == 'enviar') {

  $medAsig = trim(mysqli_real_escape_string($gestambu, $_POST['medAsig']));
  $idPac   = trim(mysqli_real_escape_string($gestambu, $_POST['idPac']));

  $sqlUp = "UPDATE paciente
    SET segMed='1', medAsig='$medAsig'
    WHERE idPAC = '$idPac'
  ";
  if(mysqli_query($gestambu,$sqlUp)) {
    $mensa   = "Médico asignado correctamente";
    $mensaOk = '1';
  } else {
    echo "Error: " . $sqlUp . "<br>" . mysqli_error($gestambu);
  }

}

# Datos paciente
$referencia = $_GET['iden'];

# listado de médicos
$lsMed = mysqli_query($gestambu, "SELECT userId, usNom, usApe, usCate
  FROM user
  WHERE usCate = '7'
  ORDER BY usNom ASC
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Asignar Seguimiento Médico</title>
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

<?php include '../../inc/supbar.php'; ?>

<?php include '../../inc/menubar.php'; ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Asignar seguimiento médico
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="/ops/mostrar/paciente.php?idPac=<?php echo $referencia; ?>">paciente</a></li>
        <li class="active">Asignar Seguimiento Médico</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Médico Asignado:</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <form action="" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                  <!-- Mensajes -->
                  <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
                  <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
                    - Volver a <a href="/ops/mostrar/paciente.php?idPac=<?php echo $referencia; ?>"><i class="icon fa fa-user"></i> Paciente</a>
                  </div>
                  <?php } ?>
                  <!-- /Mensajes -->
                  <div class="form-group ">
                    <label for="medAsig" class="control-label">Médico de seguimiento: </label>
                    <select class="form-control" name="medAsig">
                      <option value="0">-- Sin médico asignado --</option>
                      <?php
                        while($rLsMed = mysqli_fetch_assoc($lsMed)) {
                          echo "<option value='".$rLsMed['userId']."'>".$rLsMed['usNom']." ".$rLsMed['usApe']."</option>\n";
                        }
                       ?>
                    </select>
                  </div>
                  <input type="hidden" name="idPac" value="<?php echo $referencia; ?>">
              </div>
                <div class="col-md-2"></div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <div class="col-md-3 col-sm-3"></div>
                  <button type="reset" class="btn btn-default">Cancelar</button>
                  <button type="submit" class="btn btn-info" name="guardar" value="enviar" >Guardar</button>
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

<?php include '../../inc/pie.php'; ?>

<?php include '../../inc/bcontrol.php'; ?>

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
