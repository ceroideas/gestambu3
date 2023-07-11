<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Carga de datos */
$usuario = $_SESSION['userId'];

# Vehículos
$listVh = mysqli_query($gestambu, "SELECT idVh, matricula, estado FROM vehiculo WHERE estado = '1' ORDER BY matricula ASC ");

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
$limKm = 100000; //Cambio de límite para comenzar, restaurar 

if(@$_POST['guardar'] == 'enviar') {
  # Recogida de datos
  $ambuRepo = trim(mysqli_real_escape_string($gestambu, $_POST['ambu']));
  $kmRepo   = trim(mysqli_real_escape_string($gestambu, $_POST['kmrepo']));
  $user     = trim(mysqli_real_escape_string($gestambu, $_POST['usuario']));
  $litros   = trim(mysqli_real_escape_string($gestambu, $_POST['litros']));
  $precio   = trim(mysqli_real_escape_string($gestambu, $_POST['precio']));
  # Comprueba registro de repostaje anterior
  $compRepo    = mysqli_query($gestambu, "SELECT vhId, kmRepo, tipo FROM repostaje WHERE vhId = '$ambuRepo' AND tipo='1' ORDER BY kmRepo DESC LIMIT 1");
  $numCompRepo = mysqli_num_rows($compRepo);
  $rwCompRepo  = mysqli_fetch_assoc($compRepo);
  $kmAnterior  = $rwCompRepo['kmRepo'];

  $difKmRepo   = abs($kmRepo - $kmAnterior);
  $prXl        = $precio / $litros;
  if($numCompRepo == 0) { //No hay registros de repostaje - comprueba con tabla vehiculo -> kmActual
    $compKm   = mysqli_query($gestambu, "SELECT idVh, kmActual FROM vehiculo WHERE idVh = '$ambuRepo'");
    $rwCompKm = mysqli_fetch_assoc($compKm);
    $kmAcutal = $rwCompKm['kmActual'];
    $difZero  = abs($kmRepo - $kmAcutal);

    if($difZero > $limKm) {
      $mensa = "Hay error en lectura de km. La última actualización de km es: ".$kmAcutal." Km";
      $seColor = "danger";
      /* Mensajes de log */
      $obsText = "Error; técnico: ".$user." km repostar: ".$kmRepo." 1ºkm comp.: ".$kmAcutal." ambu: ".$ambuRepo;
      $usuario = $_SESSION['userId'];
      guardarLog('20', $usuario, $obsText, '0');
    } else {
      $insRepo = "INSERT INTO repostaje (userId, vhId, litros, precio, kmRepo) VALUES ('$user','$ambuRepo','$litros','$precio', '$kmRepo')";

      if(mysqli_query($gestambu,$insRepo)) {
        $mensa2 = "Guardado con éxito :) ";
        $seColor = "info";
      } else {
        echo "Error: " . $insRepo . "<br>" . mysqli_error($gestambu);
      }
    }
  } else { // Cuando hay registros guardados anteriormente
    if($difKmRepo > $limKm) { // 1º comprueba diferencia de km
      $mensa = "Hay error en lectura de km. La última actualización de km es: ".$kmAnterior." Km";
      $seColor = "danger";
      /* Mensajes de log */
      $obsText = "Error; técnico: ".$user." km repostar: ".$kmRepo." km anterior: ".$kmAnterior." ambu: ".$ambuRepo;
      $usuario = $_SESSION['userId'];
      guardarLog('20', $usuario, $obsText, '0');
    } else { // 2º comprueba precio / litro no exceda el límite
      if($prXl > $preLit) {
        $mensa = "Ha algún error de litros o precio. El precio del litro es: ".round($prXl, 3);
        $seColor = "danger";
        /* Mensajes de log */
        $obsText = "Error; técnico: ".$user." Litros: ".$litros." Precio: ".$precio." ambu: ".$ambuRepo;
        $usuario = $_SESSION['userId'];
        guardarLog('20', $usuario, $obsText, '0');
      } else {
        $insRepo = "INSERT INTO repostaje (userId, vhId, litros, precio, kmRepo) VALUES ('$user','$ambuRepo','$litros','$precio', '$kmRepo')";

        if(mysqli_query($gestambu,$insRepo)) {
          $mensa = "Guardado con éxito :) ";
          $seColor = "info";
        } else {
          echo "Error: " . $insRepo . "<br>" . mysqli_error($gestambu);
        }
      }
    }
  }

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Repostaje | GestAmbu 3.0 </title>
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/docs/dist/css/skins/_all-skins.min.css">
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
        Repostaje
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active"><i class="fa fa-battery-quarter"></i> Repostaje</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Formulario de repostaje</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
            <!-- Mensajes -->
            <?php if(isset($_POST['guardar'])) { ?>
            <div class="alert alert-<?php echo $seColor; ?> alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <i class="icon fa fa-exclamation"></i> <?php echo @$mensa;?>
            </div>
            <?php } ?>
              <form class="form-vertical form-label-left" action="" method="post">
                <div class="form-group">
                  <label>Ambulancia: </label>
                  <select class="form-control" name="ambu" required="">
                    <option value="">-- Selecciona Ambulancia --</option>
                    <option value="0">-- Sin ambulancia --</option>
                    <?php
                    while($rwListVh = mysqli_fetch_assoc($listVh)) {
                      if($ambUser == $rwListVh['idVh']) {
                        $seleccion = "selected";
                      } else {
                        $seleccion = "";
                      }
                      echo "<option value='".$rwListVh['idVh']."' ".$seleccion.">".$rwListVh['matricula']."</option>\n";
                    }
                     ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Litros: </label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="litros" placeholder="Usar punto, ejemplo: 12.457" value="" step="0.001" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Precio: </label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="precio"  placeholder="Usar punto, ejemplo: 12.457" value="" step="0.001" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Km vehículo: </label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="kmrepo" placeholder="Km al repostar" value="" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-tachometer"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
                    <button type="reset" class="btn btn-primary">Cancelar</button>
                    <button type="submit" name="guardar" value="enviar" class="btn btn-success validar">Registrar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

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
<!-- Table Expandible -->
<script src="/docs/plugins/tableExp/js/bootstrap-table-expandable.js"></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
