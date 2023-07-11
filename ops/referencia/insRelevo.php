<?php
session_start();
include '../../functions/function.php';
nonUser();

if(@$_POST['guardar'] == 'enviar') {
  /* Datos a insertar */
  $userId   = $_SESSION['userId'];
  $textoRel = trim(mysqli_real_escape_string($gestambu, $_POST['textoRel']));
  $tipo     = trim(mysqli_real_escape_string($gestambu, $_POST['tipo']));
  $hora     = trim(mysqli_real_escape_string($gestambu, $_POST['horaRel']));

  /* Insertar datos de incidencia */
  $insInci = "INSERT INTO relevo (userId, horaRel, textoRel, tipo) VALUES ('$userId', '$hora', '$textoRel', '$tipo')";

  if(mysqli_query($gestambu, $insInci)) {
    $mensa   = "Se registro la nueva entrada";
    $mensaOk = '1';

    /* Mensajes de log */
    $obsText = "nueva entrada de relevo";
    guardarLog('5', $userId, $obsText, '0');

  } else {
    echo "Error: " . $insInci . "<br>" . mysqli_error($gestambu);
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Relevo</title>
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
          Descripción de incidencia
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
          <li><a href="#"><i class="fa fa-dashboard"></i> Operador</a></li>
          <li><a href="#">Fecha</a></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="box-body">
          <form role="form" method="post">
            <!-- textarea -->
            <div class="form-group">
              <label>Descripción</label>
              <textarea class="form-control" name="textoRel" rows="5" placeholder="Incidencia ..."></textarea>
            </div>
            <!-- select -->
            <div class="clearfix"></div>
            <div class="form-group col-md-7 col-sm-7 col-xs-7">
              <label>Etiqueta: </label>
              <select class="form-control" name="tipo">
                <option value="1">Comentario</option>
                <option value="2">Importante</option>
                <option value="3">Mantener</option>
              </select>
            </div>
            <div class="form-group col-md-5 col-sm-5 col-xs-5">
              <label>Hora: </label>
              <input class="form-control" type="time" name="horaRel">
            </div>
            <div class="clearfix"></div>
            <button type="reset" class="btn btn-primary">Cancelar</button>
            <button type="submit" name="guardar" value="enviar" class="btn btn-success validar">Guardar</button>
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
</script>
</body>
</html>
