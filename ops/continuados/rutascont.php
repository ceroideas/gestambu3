<?php
session_start();
include '../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];

$prov = 29;

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Rutas | GestAmbu 3.0 </title>
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
  <link href="/ops/css/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Estilos para operaciones -->
  <link rel="stylesheet" href="/ops/css/ops.css">
  <style>
    div.cajAument form input {
      width: 100%;
    }
  </style>
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
        Rutas
        <small> activas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Continuados</a></li>
        <li class="active">Rutas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- START CUSTOM TABS -->

      <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a id="ambuClick" href="#tab_1" data-toggle="tab">Asepeyo Málaga</a></li>
              <li><a id="ruta2" href="#tab_2" data-toggle="tab">Asepeyo Fuengirola</a></li>
			  <li><a id="ruta3" href="#tab_3" data-toggle="tab">Asepeyo Sevilla</a></li>
              <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div id="ruta1"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div id="ruta2Tab"></div>
              <!-- /.tab-pane -->
              </div>
              <div class="tab-pane" id="tab_3">
                <div id="ruta3Tab"></div>
              <!-- /.tab-pane -->
              </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->
      <!-- END CUSTOM TABS -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include '../inc/pie.php'; ?>

<?php //include '../inc/bcontrol.php'; ?>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
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
<script>
$(document).ready(function() {
  $.get('/ops/tabsRef/contiTab/rutaTab.php', {codRuta: "<?php echo "RT2018/1"; ?>"}, function(htmlexterno){
    $("#ruta1").html(htmlexterno);
  });
  $("#ruta2").click(function() {
    $.get('/ops/tabsRef/contiTab/rutaTab.php', {codRuta: "<?php echo "RT2018/2"; ?>"}, function(htmlexterno){
      $("#ruta2Tab").html(htmlexterno);
    });
  });
  $("#ruta3").click(function() {
    $.get('/ops/tabsRef/contiTab/rutaTab.php', {codRuta: "<?php echo "RT2018/4"; ?>"}, function(htmlexterno){
      $("#ruta3Tab").html(htmlexterno);
    });
  });  
});

</script>
<!-- <script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });</script> -->
</body>
</html>
<?php
mysqli_close($gestambu);
?>
