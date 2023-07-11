<?php
session_start();
include '../../functions/function.php';
nonUser();

if(isset($_GET['opcion'])) {
  $opcion = $_GET['opcion'];
} else {
  $opcion = '1';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Personal | GestAmbu 3.0</title>
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="/docs/dist/css/skins/skin-blue.min.css">
  <!-- Table Expandible -->
  <link rel="stylesheet" href="/docs/plugins/tableExp/css/bootstrap-table-expandable.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="icon" type="image/png" sizes="16x16" href="../ico/favicon-16x16.png">
  <link rel="stylesheet" href="/ops/css/index.css">
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
        Personal Activo
      </h1>
      <!-- Navegador de posición de página (migas) -->
      <ol class="breadcrumb">
        <li class=""><a href="/ops/index.php"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active"><a href="#"><i class="fa fa-user"></i> Usuarios</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Listado de personal</strong>
            </div>
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a id="tecClick" href="#tab_1" data-toggle="tab">Técnico</a></li>
                <li><a id="medicoClick" href="#tab_2" data-toggle="tab">Médico</a></li>
                <li><a id="dueClick" href="#tab_3" data-toggle="tab">Enfermero</a></li>
                <li><a id="opeClick" href="#tab_4" data-toggle="tab">Operador</a></li>
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    Opciones <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="?opcion=2">Inactivos</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="?opcion=1">Activos</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="/ops/soporte/nuser.php">Registrar</a></li>
                  </ul>
                </li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                  <div id="tecTab"></div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                  <div id="medicoTab"></div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                  <div id="dueTab"></div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4">
                  <div id="opeTab"></div>
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include 'inc/pie.php'; ?>

<?php //include 'inc/bcontrol.php'; ?>

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
<!-- Carga de tab -->
<script type="text/javascript">

$(document).ready(function() {
  $.get('tab/tabTec.php', {tabName: "tec", opcion: <?php echo $opcion; ?> }, function(htmlexterno){
    $("#tecTab").html(htmlexterno);
  });
 });
  $("#medicoClick").click(function() {
    $.get('tab/tabTec.php', {tabName: "med", opcion: <?php echo $opcion; ?>}, function(htmlexterno){
     $("#medicoTab").html(htmlexterno);
   });
  });
  $("#dueClick").click(function() {
    $.get('tab/tabTec.php', {tabName: "due", opcion: <?php echo $opcion; ?>}, function(htmlexterno){
     $("#dueTab").html(htmlexterno);
   });
  });
  $("#opeClick").click(function() {
    $.get('tab/tabTec.php', {tabName: "ope", opcion: <?php echo $opcion; ?>}, function(htmlexterno){
     $("#opeTab").html(htmlexterno);
   });
  });

</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
