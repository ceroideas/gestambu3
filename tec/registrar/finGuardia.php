<?php
session_start();
include '../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];
// estAmbu: 1 (actual) / 2(cambiada) / 0(cerrado)
$compGuardia = mysqli_query($gestambu, "SELECT reguardia.idGuardia, reguardia.turno, reguardia.cate, reguardia.idUser, reguardia.gEst, regambu.regId, regambu.idGuardia, regambu.ambu, regambu.estAmbu
  FROM reguardia
    LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
  WHERE reguardia.idUser = '$usuario' AND reguardia.gEst = '1' AND regambu.estAmbu IN('1','3')
  ");
$rwCompGuardia = mysqli_fetch_assoc($compGuardia);
$numCompGuardia = mysqli_num_rows($compGuardia);

if($numCompGuardia == 0) {
  $mensa = "No tienes ninguna guardia registrada";
}

/* Datos */
$idGuardia = $rwCompGuardia['idGuardia'];
$turno     = $rwCompGuardia['turno'];
$cate      = $rwCompGuardia['cate'];
# Vehículos
$listVh = mysqli_query($gestambu, "SELECT *
  FROM vehiculo
  WHERE estado = '1'
  ORDER BY matricula ASC
  ");

if(@$_POST['guardar'] == 'enviar') {
  /* Recogida de variables */
  $ambu      = trim(mysqli_real_escape_string($gestambu, $_POST['ambu']));
  $kmFin     = trim(mysqli_real_escape_string($gestambu, $_POST['kmFin']));
  $gHoraFin  = trim(mysqli_real_escape_string($gestambu, $_POST['gHoraFin']));
  $gFechaFin = trim(mysqli_real_escape_string($gestambu, $_POST['gFechaFin']));
  $gHoraFor  = $gHoraFin.":00";
  $gEst      = 0;
  $ahora     = date("Y-m-d H:i:s");

  /* comprobar que no se excede el límite de km */
  $compLim = mysqli_query($gestambu, "SELECT *
    FROM vehiculo
    WHERE idVh = '$ambu'
    ");
  $rwCompLim = mysqli_fetch_assoc($compLim);
  $kmRegistro = $rwCompLim['kmActual'];

  $difKm = abs($kmRegistro - $kmFin);
  if($cate > 1) { // médicos, dues y ayudantes pueden cerrar la sesión especificar km, no hace comprobación de km y no los guarda
    $guardaUp = "UPDATE reguardia
      SET gHoraFin='$gHoraFor', gFechaFin='$gFechaFin', gEst='$gEst', regFin = '$ahora'
      WHERE idGuardia = '$idGuardia' AND idUser = '$usuario'
    ";

    if(mysqli_query($gestambu,$guardaUp)) {
      $finAmb = mysqli_query($gestambu, "UPDATE regambu SET envioFin='$ahora', estAmbu='0' WHERE idGuardia = '$idGuardia' AND estAmbu ='3'");
      $mensa2 = "Se ha finalizado la guardia correctamente.";
      $seColor = "info";
    } else {
      echo "Error: " . $guardaUp . "<br/>" . mysqli_error($gestambu);
    }
  } else {
    if($difKm > $limKm) {
      if($turno == 5) { // para viajes tendrá un límite de (2 dias conduciendo a 120 = 5760km)
        if($difkm > 5760) {
          $mensa2 = "Los km. introducidos son incorrectos. Último registro: ".$kmRegistro;
          $seColor = "danger";
        } else {
          $guardaUp = "UPDATE reguardia
            SET gHoraFin='$gHoraFor', gFechaFin='$gFechaFin', gEst='$gEst', regFin = '$ahora'
            WHERE idGuardia = '$idGuardia' AND idUser = '$usuario'
          ";

          if(mysqli_query($gestambu,$guardaUp)) {
            $finAmb = mysqli_query($gestambu, "UPDATE regambu SET kmFin='$kmFin', envioFin='$ahora', estAmbu='0' WHERE idGuardia = '$idGuardia' AND estAmbu='1'");
            $mensa2 = "Se ha finalizado la guardia correctamente.";
            $seColor = "info";
          } else {
            echo "Error: " . $guardaUp . "<br/>" . mysqli_error($gestambu);
          }
        }
      } else { // para días normales mantiene el límite en $limKm
        $mensa2 = "Los km. introducidos son incorrectos. Último registro: ".$kmRegistro;
        $seColor = "danger";
      }
    } else { // Si no sobrepasa el límite actualiza la tabla de registro de guardia en kmfin
      $guardaUp = "UPDATE reguardia
        SET gHoraFin='$gHoraFor', gFechaFin='$gFechaFin', gEst='$gEst', regFin = '$ahora'
        WHERE idGuardia = '$idGuardia' AND idUser = '$usuario'
      ";

      if(mysqli_query($gestambu,$guardaUp)) {
        $finAmb = mysqli_query($gestambu, "UPDATE regambu SET kmFin='$kmFin', envioFin='$ahora', estAmbu='0' WHERE idGuardia = '$idGuardia' AND estAmbu='1'");
        $mensa2 = "Se ha finalizado la guardia correctamente.";
        $seColor = "info";
      } else {
        echo "Error: " . $guardaUp . "<br/>" . mysqli_error($gestambu);
      }
    } // fin comp. técnico
  }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Finalización de guardia | GestAmbu 3.0 </title>
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
        Finalización de guardia
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Fin de guardia</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Formulario de finalización de guardia</h3>

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
              <?php
              if($numCompGuardia == 0 ) { ?>
              <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> <?php echo $mensa;?>
                - Volver a <a href="/tec/index.php"><i class="icon fa fa-home"></i> inicio</a>
              </div>
              <?php } ?>
              <?php
              if(isset($_POST['guardar'])) { ?>
              <div class="alert alert-<?php echo $seColor; ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i> <?php echo $mensa2;?>
                - Volver a <a href="/tec/index.php"><i class="icon fa fa-home"></i> inicio</a>
              </div>
              <?php } ?>
              <!-- fin mensajes -->
              <form class="form-vertical form-label-left" action="" method="post">
                <div class="form-group">
                  <label>Ambulancia: </label>
                  <select class="form-control" name="ambu">
                    <option value="0">-- Sin ambulancia --</option>
                    <?php
                    while($rwGAmb = mysqli_fetch_assoc($listVh)) {
                      if($rwCompGuardia['ambu'] == $rwGAmb['idVh']) {
                        $seleccion = "selected";
                      } else {
                        $seleccion = "";
                      }
                      echo "<option value='".$rwGAmb['idVh']."' ".$seleccion.">".$rwGAmb['matricula']."</option>\n";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Hora de finalización: </label>
                  <div class="input-group">
                    <input type="time" class="form-control" name="gHoraFin" value="<?php echo date("H:i"); ?>" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Fecha de finalización: </label>
                  <div class="input-group">
                    <input type="date" class="form-control" name="gFechaFin" value="<?php echo date("Y-m-d"); ?>" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Km de finalización: </label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="kmFin" placeholder="0 si no estás registrado como conductor" value="" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-tachometer"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-9 col-sm-9 col-xs-12">
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

<?php //include '../inc/pie.php'; ?>
<?php //include '../inc/bcontrol.php'; ?>

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
