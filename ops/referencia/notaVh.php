<?php
session_start();
include '../../functions/function.php';
nonUser();

$userId   = $_SESSION['userId'];

if(@$_POST['guardar'] == 'enviar') {
  /* Datos a insertar */

  $textoNota = trim(mysqli_real_escape_string($gestambu, $_POST['textoNota']));
  $ambu      = trim(mysqli_real_escape_string($gestambu, $_POST['ambu']));
  $ahora     = date("Y-m-d H:i:s");
  /* Insertar datos de incidencia */
  $insNota = "INSERT INTO notas (descNota, vhId, userId, creado ) VALUES ('$textoNota', '$ambu', '$userId', '$ahora')";

  if(mysqli_query($gestambu, $insNota)) {
    $mensa   = "Nota enviada correctamente";
    $mensaOk = '1';

    /* Mensajes de log */
    $obsText = "Creada nota para vehículo ".$ambu;
    guardarLog('21', $userId, $obsText, '0');

  } else {
    echo "Error: " . $insNota . "<br>" . mysqli_error($gestambu);
  }
}
/* Vehículos */
$lsVh = mysqli_query($gestambu,"SELECT idVh, matricula, estado
FROM vehiculo
WHERE estado != '0'
ORDER BY matricula ASC
");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Nota para Vehículo | GestAmbu 3.0 </title>
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
  <style>
    .content {
      padding: 5px;
    }
    .content-header {
      padding: 3px 15px 0 15px;
    }
    .box-body {
      padding: 10px;
    }
    .form-group {
      margin-bottom: 5px;
    }
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="#" class="navbar-brand"><span class="logo-lg"><b>Ambulancias</b>Andalucía</span></a>
        </div>
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Nota a enviar:
        </h1>
        <!-- Mensajes -->
        <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
        <div class="alert alert-warning alert-dismissible" id="msjRel">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
        </div>
        <?php } ?>
        <!-- /Mensajes -->
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> <?php mostrarTecnico($userId); ?></a></li>
          <li><a href="#"><?php echo date("d-m-Y"); ?></a></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="box-body">
          <form role="form" method="post">
            <!-- textarea -->
            <div class="form-group">
              <label>Descripción</label>
              <textarea class="form-control" name="textoNota" rows="5" placeholder="Texto, máximo 175 caractéres ..." maxlength="175" onKeyDown="cuenta()" onKeyUp="cuenta()"></textarea>
            </div>
            <!-- select -->
            <div class="clearfix"></div>
            <div class="form-group col-md-7 col-sm-7 col-xs-7">
              <label>Vehículo: </label>
              <select class="form-control" name="ambu" required>
                <option value=""> - Selecciona vehículo - </option>
                <?php
                  while($rVh = mysqli_fetch_assoc($lsVh)) {
                    echo "<option value='".$rVh['idVh']."'>".$rVh['matricula']."</option>\n";
                  }
                  ?>
              </select>
            </div>
            <div class="form-group col-md-5 col-sm-5 col-xs-5">
              <label>Caractéres: </label>
              <input class="form-control" type="text" name="caracteres">
            </div>
            <div class="clearfix"></div>
            <button type="reset" class="btn btn-primary">Cancelar</button>
            <button type="submit" name="guardar" value="enviar" class="btn btn-success validar"><i class="fa fa-send"></i> Enviar</button>
          </form>
        </div>
        <!-- /.box-body -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
</div>
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
<script type="text/javascript">
$(document).ready(function() {
    setTimeout(function() {
        $("#msjRel").fadeOut(2000);
    },5000);
});
function cuenta(){
  document.forms[0].caracteres.value=document.forms[0].textoNota.value.length
}
</script>
</body>
</html>
