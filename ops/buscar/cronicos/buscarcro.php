<?php
session_start();
include '../../../functions/function.php';
nonUser();

/* Buscador tabla pacientes */

$busPac = mysqli_query($gestambu, "SELECT *
  FROM paciente
  ORDER BY pNombre ASC
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Buscar</title>
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
        Buscador de:
        <small>Crónicos / Paliativos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Buscardor</a></li>
        <li class="active">Crónicos / Paliativos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Opciones para buscar:</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>

              <div class="box-body form-horizontal">
                <div class="col-md-8 col-md-offset-2">
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Búsqueda por: </label>
                      <select class="form-control" name="selec" id="seleccion" onselect="selec()">
                        <option value="0">Selecciona campo a buscar</option>
                        <option value="1">Nombre</option>
                        <option value="2">Apellidos</option>
                        <option value="3">Teléfono</option>
                        <option value="4">DNI</option>
                        <option value="5">Póliza</option>
                        <option value="6">Dirección</option>
                      </select>
                  </div>
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label>Parámetros: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Parámetros de búsqueda" id="valor" onkeyup="setTimeout('Buscar();','800')">
                      <div class="input-group-addon">
                        <i class="fa">P</i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <!--
              <div class="box-footer">
                <div class="col-md-offset-2">
                  <span class="help-block h6">** Los servicios marcados con (d) son paciente de delegaciones de fuera **</span>
                </div>
                <div class="col-md-3 col-sm-3"></div>
                <button type="reset" class="btn btn-default">Cancelar</button>
                <button type="submit" class="btn btn-info" name="guardar" value="enviar" >Guardar</button>
              </div>
              -->
              <!-- /.box-footer-->

          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Resultado de búsqueda: </h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding" id="resultados">

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.Fin de tabla -->
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
<!-- Buscar -->
<script src="ajax.js"></script>
</body>
</html>
<?php
// mysqli_close($gestambu);
?>
