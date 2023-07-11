<?php
session_start();
include '../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];
$listVh = mysqli_query($gestambu, "SELECT * FROM vehiculo WHERE estado = '1' ORDER BY matricula ASC ");

# Comprobar si hay guardia registrada
$compGuardia = mysqli_query($gestambu, "SELECT reguardia.idGuardia, reguardia.cate, reguardia.turno, reguardia.idUser, reguardia.gEst,
  regambu.regId, regambu.idGuardia, regambu.ambu, regambu.estAmbu,
  vehiculo.idVh, vehiculo.matricula
  FROM reguardia
    LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
    LEFT JOIN vehiculo ON regambu.ambu = vehiculo.idVh
  WHERE reguardia.idUser = '$usuario' AND reguardia.gEst= '1' AND regambu.estAmbu IN ('3','1')");
$rwCompGuardia  = mysqli_fetch_assoc($compGuardia);
$numCompGuardia = mysqli_num_rows($compGuardia);
$idGuardia      = $rwCompGuardia['idGuardia'];
$ambu           = $rwCompGuardia['idVh'];
$reGuardId      = $rwCompGuardia['regId'];

if($numCompGuardia == 0) {
  $mensa = "No tienes guardia registrada. No puede realizar un cambio de vehículo";
}

if(@$_POST['guardar'] == 'enviar') {
  if($numCompGuardia == 0 ) {
    $mensa2 = "No tienes guardia registrada. No puede realizar un cambio de vehículo";
    $seColor = "danger";
  } else {
    /* Recogida de variables */
    $kmFin   = trim(mysqli_real_escape_string($gestambu, $_POST['kmfin']));
    $kmIni   = trim(mysqli_real_escape_string($gestambu, $_POST['kmincambio']));
    $AmbCamb = trim(mysqli_real_escape_string($gestambu, $_POST['ambucambio']));
    $cate    = trim(mysqli_real_escape_string($gestambu, $_POST['cate']));
    $ahora   = date("Y-m-d H:i:s");

    # comprobar que no se excede el límite de km anterior
    $compLim    = mysqli_query($gestambu, "SELECT * FROM vehiculo WHERE idVh = '$ambu' ");
    $rwCompLim  = mysqli_fetch_assoc($compLim);
    $kmRegistro = $rwCompLim['kmActual'];

    # comprobar que no se excede el límite de km cambio
    $compLimCamb    = mysqli_query($gestambu, "SELECT * FROM vehiculo WHERE idVh = '$AmbCamb' ");
    $rwCompLimCamb  = mysqli_fetch_assoc($compLimCamb);
    $kmRegistroCamb = $rwCompLimCamb['kmActual'];

    $difKmAnt  = abs($kmRegistro - $kmFin); //Diferencia ambu anterior
    $difKmCamb = abs($kmRegistroCamb - $kmIni); //Diferencia ambu cambio

    # Comprobar que no existe duplicado de ambulancia - se realiza con la ambulancia de cambio
    $compDupli = mysqli_query($gestambu, "SELECT reguardia.idUser, reguardia.gEst,
        user.userId, user.usNom, user.usApe, user.usCate, regambu.ambu, regambu.estAmbu
      FROM reguardia
        LEFT JOIN user ON reguardia.idUser = user.userId
        LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
      WHERE gEst = '1' AND regambu.ambu = '$AmbCamb' AND user.usCate = '5' AND regambu.estAmbu = '1'
      ");
    $numDupli = mysqli_num_rows($compDupli);
    $rwDupli  = mysqli_fetch_assoc($compDupli);

    if($cate > 1) { //para médicos, dues o ayudantes no guarda registro de km - se entiende que se ha de tener una guardia regsitrada para cambiar
      //Crea nuevo registro en regambu
      $insNAmbu = "INSERT INTO regambu (idGuardia, ambu, envioInicio, estAmbu) VALUES ('$idGuardia', '$AmbCamb', '$ahora', '3')";

      if(mysqli_query($gestambu,$insNAmbu)) {
        // Cambiar de vehículo no actualiza la tabla reguardia - crea nuevo registro en regambu y marca el anterior con un 2 (cambion de vehículo)
        $guardaUp = mysqli_query($gestambu, "UPDATE regambu SET envioFin='$ahora', estAmbu='2' WHERE idGuardia = '$idGuardia' AND regId = '$reGuardId' AND ambu='$ambu' ");

        $mensa2 = "Se ha registrado con éxito el cambio de vehículo";
        $seColor = "info";
      } else {
        echo "Error: " . $insNAmbu . "<br/>" . mysqli_error($gestambu);
      }
    } else { // técnicos
      if($difKmAnt > $limKm ) { // vehículo a dejar
        $mensa2 = "Los km. introducidos para el vehículo a dejar son incorrectos. Último registro: ".$kmRegistro." km.";
        $seColor = "danger";
      } elseif($difKmCamb > $limKm) { // vehículo a cambiar
        $mensa2 = "Los km. introducidos para el vehículo a coger son incorrectos. Último registro: ".$kmRegistroCamb." km.";
        $seColor = "danger";
      } elseif($numDupli > 0) {
        $mensa2 = "El técnico ".$rwDupli['usNom']." ".$rwDupli['usApe']." no ha cerrado su guardia. Contacta para poder cambiar de vehículo. ".$numDupli;
        $seColor = "danger";
      } else {
        //Crea nuevo registro en regambu
        $insNAmbu = "INSERT INTO regambu (idGuardia, ambu, kmIni, envioInicio, estAmbu) VALUES ('$idGuardia', '$AmbCamb', '$kmIni', '$ahora', '1')";

        if(mysqli_query($gestambu, $insNAmbu)) {
          //Acualiza el registro anterior y los km de la ambulancia
          $guardaUp = mysqli_query($gestambu, "UPDATE regambu SET kmFin='$kmFin', envioFin='$ahora', estAmbu='2' WHERE idGuardia = '$idGuardia' AND regId = '$reGuardId' ");
          $kmUpAnte = mysqli_query($gestambu, "UPDATE vehiculo SET kmActual='$kmFin' WHERE idVh = '$ambu'");
          $kmUpCamb = mysqli_query($gestambu, "UPDATE vehiculo SET kmActual='$kmIni' WHERE idVh = '$AmbCamb'");

          $mensa2 = "Vehículo cambiado con éxito ";
          $seColor = "info";

        } else {
          echo "Error: " . $insNAmbu . "<br>" . mysqli_error($gestambu);
        }
      }

    } //fin tec

  }
}//fin guardar
# Comprobar km registrados y km finales
# Comprobar vehículo - no tendría que estar activo
# Comprobar km de inicio de nuevo vehículo
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cambio de vehículo | GestAmbu 3.0 </title>
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
        Cambio de vehículo
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Cambio de vehículo</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Formulario de cambio de vehículo</h3>

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
                <i class="icon fa fa-exclamation"></i> <?php echo $mensa;?>
              </div>
              <?php }
              if(isset($_POST['guardar'])) { ?>
              <div class="alert alert-<?php echo $seColor; ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-exclamation"></i> <?php echo $mensa2;?>
              </div>
              <?php } ?>
              <form class="form-vertical form-label-left" action="" method="post">
                <div class="form-group">
                  <label>Vehículo a dejar: </label>
                  <input type="text" class="form-control" placeholder="Matrícula" value="<?php echo $rwCompGuardia['matricula']; ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Km finales: </label>
                  <div class="input-group">
                    <input type="number" name="kmfin" class="form-control" placeholder="km finales" value="" >
                    <div class="input-group-addon">
                      <i class="fa fa-tachometer"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Nuevo vehículo: </label>
                  <select class="form-control" name="ambucambio" required="">
                    <option value="">-- Selecciona Ambulancia --</option>
                    <option value="0">-- Sin ambulancia --</option>
                    <?php
                    while($rwListVh = mysqli_fetch_assoc($listVh)) {
                      echo "<option value='".$rwListVh['idVh']."'>".$rwListVh['matricula']."</option>\n";
                    }
                     ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Km iniciales: </label>
                  <div class="input-group">
                    <input type="number" name="kmincambio" class="form-control" placeholder="km inciales" value="" >
                    <div class="input-group-addon">
                      <i class="fa fa-tachometer"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="hidden" name="cate" value="<?php echo $rwCompGuardia['cate']; ?>" >
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
