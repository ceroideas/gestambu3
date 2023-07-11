<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Carga de datos */
$usuario = $_SESSION['userId'];

# Vehículos
$listVh = mysqli_query($gestambu, "SELECT *
  FROM vehiculo
  WHERE estado = '1'
  ORDER BY matricula ASC
  ");

# Comprobar si hay guardia registrada
$compGuardia = mysqli_query($gestambu, "SELECT *
  FROM reguardia
  WHERE idUser = '$usuario' AND gEst = '1'
  ");
$rwCompGuardia = mysqli_fetch_assoc($compGuardia);
$numCompGuardia = mysqli_num_rows($compGuardia);
if($numCompGuardia > 0) {
  $mensa = "Ya tienes una guardia registrada, id: ".$rwCompGuardia['idGuardia']." día: ".$rwCompGuardia['gFechaIni'];
}


if(@$_POST['guardar'] == 'enviar') {
  if($numCompGuardia > 0 ) {
    $mensa2 = "No puedes iniciar otra guardia, ya existe una creada";
  } else {
    /* Recogida de variables */
    $turno     = trim(mysqli_real_escape_string($gestambu, $_POST['turno']));
    $ambu      = trim(mysqli_real_escape_string($gestambu, $_POST['ambu']));
    $kmIni     = trim(mysqli_real_escape_string($gestambu, $_POST['kmIni']));
    $gHoraIni  = trim(mysqli_real_escape_string($gestambu, $_POST['gHoraIni']));
    $gFechaIni = trim(mysqli_real_escape_string($gestambu, $_POST['gFechaIni']));
    $extra     = trim(mysqli_real_escape_string($gestambu, $_POST['extra']));
    $cate      = trim(mysqli_real_escape_string($gestambu, $_POST['cate']));
    $gHoraFor  = $gHoraIni.":00";
    $gEst      = 1;
    $userId    = $_SESSION['userId'];
    $ahora     = date("Y-m-d H:i:s");

    // 1: técnico - 2: ayudante - 3: enfermero - 4: médico

    # Comprobar que los km de inicio coinciden con los anteriores
    $compKm   = mysqli_query($gestambu, "SELECT idVh, kmActual FROM vehiculo WHERE idVh = '$ambu' ");
    $rwCompKm = mysqli_fetch_assoc($compKm);
    $kmRegistro = $rwCompKm['kmActual'];

    $difKm = abs($kmRegistro - $kmIni);

    # Comprobar que no existe duplicado de ambulancia
    $compDupli = mysqli_query($gestambu, "SELECT reguardia.idUser, reguardia.gEst,
        user.userId, user.usNom, user.usApe, user.usCate, regambu.ambu, regambu.estAmbu
      FROM reguardia
        LEFT JOIN user ON reguardia.idUser = user.userId
        LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
      WHERE gEst = '1' AND regambu.ambu = '$ambu' AND user.usCate = '5' AND regambu.estAmbu = '1'
      ");
    $numDupli = mysqli_num_rows($compDupli);
    $rwDupli  = mysqli_fetch_assoc($compDupli);

    # Las comprobaciones las realiza con los técnicos - para médico, ayudante o enfermero; solamente guarda el registro de inicio y cierre de sesión. Tiene que especificar en qué vehículo está.
    if($cate > 1) { // médico, due, ayd.
      $insGuardia = "INSERT INTO reguardia
      (idUser, turno, cate, gHoraIni, gFechaIni, extra, gEst) VALUES
      ('$userId', '$turno', '$cate', '$gHoraFor', '$gFechaIni', '$extra', '$gEst')
      ";

      if(mysqli_query($gestambu,$insGuardia)) {
        $idInsertado = mysqli_insert_id($gestambu);
        //Crea registro en regambu
        $ambuIns = "INSERT INTO regambu (idGuardia, ambu, envioInicio, estAmbu) VALUES ('$idInsertado', '$ambu', '$ahora', '3')"; //no guarda km

        if(mysqli_query($gestambu,$ambuIns)) {
          $mensa2 = "Guardia y vehículo registrados con éxito ";
          $seColor = "info";
        } else {
          echo "Error: " . $ambuIns . "<br/>" . mysqli_error($gestambu);
        }
      } else {
        echo "Error: " . $insGuardia . "<br>" . mysqli_error($gestambu);
      }
    } else { // técnico
      if($difKm > $limKm) {
        $mensa2 = "Los km. introducidos son incorrectos. Último registro: ".$kmRegistro." km.";
        $seColor = "danger";
      } elseif($numDupli > 0 ) {
        $mensa2 = "El técnico ".$rwDupli['usNom']." ".$rwDupli['usApe']." no ha cerrado su guardia. Contacta para poder abrir una nueva.";
        $seColor = "danger";
      } else {
        $insGuardia = "INSERT INTO reguardia
        (idUser, turno, cate, gHoraIni, gFechaIni, extra, gEst) VALUES
        ('$userId', '$turno', '$cate', '$gHoraFor', '$gFechaIni', '$extra', '$gEst')
        ";

        if(mysqli_query($gestambu,$insGuardia)) {
          $idInsertado = mysqli_insert_id($gestambu);
          //Actualiza la tabla vehículo con los nuevos km
          $vehiculoUp = "UPDATE vehiculo
            SET kmActual='$kmIni'
            WHERE idVh='$ambu'
          ";
          //Crea registro en regambu
          $ambuIns = mysqli_query($gestambu, "INSERT INTO regambu (idGuardia, ambu, kmIni, envioInicio) VALUES ('$idInsertado', '$ambu', '$kmIni', '$ahora')");

          if(mysqli_query($gestambu,$vehiculoUp)) {
            $mensa2 = "Guardia y vehículo registrados con éxito ";
            $seColor = "info";
          } else {
            echo "Error: " . $vehiculoUp . "<br/>" . mysqli_error($gestambu);
          }
        } else {
          echo "Error: " . $insGuardia . "<br>" . mysqli_error($gestambu);
        }
      } // fin comp. tec
    } // fin comp.
  }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inicio de guardia | GestAmbu 3.0 </title>
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
        Inicio de guardia
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Registro de guardia</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Formulario de registro de guardia</h3>

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
            if($numCompGuardia > 0 ) { ?>
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
                  <label>Turno: </label>
                  <select class="form-control" name="turno" required="">
                    <option value="">-- Selecciona turno --</option>
                    <option value="1"> Turno 12h </option>
                    <option value="2"> Turno 24h </option>
                    <option value="3"> Extra </option>
                    <option value="4"> Turno 3-3 </option>
                    <option value="5"> Viaje </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Ambulancia: </label>
                  <select class="form-control" name="ambu" required="">
                    <option value="">-- Selecciona Ambulancia --</option>
                    <option value="0">-- Sin ambulancia --</option>
                    <?php
                    while($rwListVh = mysqli_fetch_assoc($listVh)) {
                      echo "<option value='".$rwListVh['idVh']."'>".$rwListVh['matricula']."</option>\n";
                    }
                     ?>
                  </select>
                  <select class="form-control" name="cate" required="">
                    <option value="">-- Categoría --</option>
                    <option value="1">Técnico</option>
                   <!--  opciones retiradas temporalmente para evitar problemas de visibilidad  -->
                   <!--  <option value="2">Ayudante</option>
                    <option value="3">Enfermero</option>
                    <option value="4">Médico</option> -->
                    <!-- Fin de opciones retiradas temporalmente -->
                  </select>
                </div>
                <div class="form-group">
                  <label>Hora de incio: </label>
                  <div class="input-group">
                    <input type="time" class="form-control" name="gHoraIni" value="<?php echo date("H:i"); ?>" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Fecha de inicio: </label>
                  <div class="input-group">
                    <input type="date" class="form-control" name="gFechaIni" value="<?php echo date("Y-m-d"); ?>" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Km de inicio: </label>
                  <div class="input-group">
                    <input type="number" class="form-control" name="kmIni" placeholder="Poner 0 si estás sin ambulancia" value="<?php echo @$_POST['kmIni']; ?>" required="">
                    <div class="input-group-addon">
                      <i class="fa fa-tachometer"></i>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Descripción extra: </label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="extra" placeholder="Sólamente para servicios extras" value="" >
                    <div class="input-group-addon">
                      <i class="fa fa-user-plus"></i>
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
