<?php
session_start();
include '../../functions/function.php';
nonUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Documentos</title>
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
        Registro de:
        <small>Nuevo usuario</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Documentos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Documentos</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>#</th>
                  <th>Nombre del documento</th>
                </tr>
                <tr>
                  <td><a href="parte_trabajo.pdf"><i class="fa fa-file-pdf-o"> -Parte de Trabajo</i></a></td>
                  <td>Parte de Trabajo (personal)</td>
                </tr>				
                <tr>
                  <td><a href="extras-ops.pdf"><i class="fa fa-file-pdf-o"> -Operaciones</i></a></td>
                  <td>Hoja de Extras (personal)</td>
                </tr>
                <tr>
                  <td><a href="hojaextras-personal.pdf"><i class="fa fa-file-pdf-o"> -Operaciones</i></a></td>
                  <td>Listado extras (operaciones)</td>
                </tr>				
                <tr>
                  <td><a href="caja.pdf"><i class="fa fa-file-pdf-o"> -Operaciones</i></a></td>
                  <td>Salidas de caja Operaciones</td>
                </tr>				
                <tr>
                  <td><a href="chequesasisa.pdf"><i class="fa fa-ambulance"> -Málaga</i></a></td>
                  <td>Cheques Ambulancia Asisa Málaga</td>
                </tr>
                <tr>
                  <td><a href="firmas.pdf"><i class="fa fa-file"> -Málaga</i></a></td>
                  <td>Firmas Continuadas Asepeyo Málaga</td>
                </tr>
                <tr>
                  <td><a href="gastos.pdf"><i class="fa fa-file"> -Málaga</i></a></td>
                  <td>Gastos Trasporte Terceros Asepeyo Málaga</td>
                </tr>				
                <tr>
                  <td><a href="AMBULANCIA_ASISA_SE.pdf"><i class="fa fa-ambulance"> -Sevilla</i></a></td>
                  <td>Cheques Ambulancia Asisa Sevilla</td>
                </tr>
                <tr>
                  <td><a href="MEDICO_ASISA_SE.pdf"><i class="fa fa-medkit"> -Sevilla</i></a></td>
                  <td>Cheques Médico Asisa Sevilla</td>
                </tr>
                <tr>
                  <td><a href="ENFERMERIA_ASISA_SE.pdf"><i class="fa fa-eyedropper"> -Sevilla</i></a></td>
                  <td>Cheques Enfermero Asisa Sevilla</td>
                </tr>
                <tr>
                  <td><a href="modificacion.jpg"><i class="fa fa-pencil"> -Sevilla</i></a></td>
                  <td>Modificación Ambulancia Asepeyo Sevilla</td>
                </tr>
                <tr>
                  <td><a href="cancelacion.jpg"><i class="fa fa-close"> -Sevilla</i></a></td>
                  <td>Cancelación Ambulancia Asepeyo Sevilla</td>
                </tr>
                <tr>
                  <td><a href="traslado.jpg"><i class="fa fa-automobile"> -Sevilla</i></a></td>
                  <td>Traslado Ambulancia Asepeyo Sevilla</td>
                </tr>				
              </table>
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
