<?php
session_start();
include '../../functions/function.php';
nonUser();

$nomRuta = mysqli_query($gestambu, "SELECT codRuta, nomRuta, activa FROM ruta WHERE activa='1'");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Continuado</title>
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
  <!-- Estilos para operaciones -->
  <link rel="stylesheet" href="/ops/css/ops.css">
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
        Formulario de nuevo servicio continuado
        <small>Completa los datos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Continuado</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Selección de días</h3>

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
                  
                  <form class="form-horizontal form-label-left" action="continuado.php" method="post">
                    <div class="form-group">
                      <label for="sesiones" class="col-lg-2 control-label">Sesiones: </label>
                      <div class="col-lg-10">
                        <input type="text" class="form-control" name="sesiones" placeholder="Sesiones" required="" title="Has de especificar un número de sesiones">
                      </div>
                    </div>
                    <hr />
                    <span class="help-block"><small class="col-md-6 col-md-offset-3">* Usar sólamente cuando el completado sea "Automático". </small></span>

                    <div class="clearfix"></div>
                    <div class="form-group">
                      <label for="completado" class="col-lg-2 control-label">Completado: </label>
                      <div class="col-lg-10">
                        <select name="completado" class="form-control">
                          <option value="1">-- Manual --</option>
                          <option value="2">-- Automático --</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="pauta" class="col-lg-2 control-label">Pauta: </label>
                      <div class="col-lg-10">
                        <select name="pauta" class="form-control">
                          <option value="0">-- Selecciona pauta --</option>
                          <option value="1">Cada 24h</option>
                          <option value="2">Cada 48h</option>
                          <option value="3">Cada 72h</option>
                          <option value="4">De Lunes a Viernes</option>
                          <option value="5">Lunes - Miércoles - Viernes</option>
                          <option value="6">Martes - Jueves - Sábados</option>
                          <option value="7">Sábados y Domingos</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inicio" class="col-lg-2 control-label">Inicio: </label>
                      <div class="col-lg-3">
                        <input type="date" class="form-control" name="inicio">
                      </div>
                    </div>
                    <hr />
                    <span class="help-block"><small class="col-md-6 col-md-offset-3">* Usar sólamente cuando el continuado pertenece a una RUTA. </small></span>

                    <div class="clearfix"></div>
                    <div class="form-group">
                      <label for="completado" class="col-lg-2 control-label">Vincular a Ruta: </label>
                      <div class="col-lg-10">
                        <select name="codRuta" class="form-control">
                          <option value="0">-- Selecciona ruta --</option>
                          <?php
                          while($rRuta = mysqli_fetch_assoc($nomRuta)) {
                            echo "<option value='".$rRuta['codRuta']."'>".$rRuta['nomRuta']."</option>\n";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-md-6 col-md-offset-3">
                      <div class="col-md-offset-3">
                        <button type="reset" class="btn btn-primary">Borrar</button>
                        <button type="submit" class="btn btn-success">Enviar</button>
                      </div>
                    </div>
                  </form>

                </div>
              </div>
            </div>
            <!-- /.box-body -->

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
</body>
</html>
