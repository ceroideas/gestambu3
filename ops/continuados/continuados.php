<?php
session_start();
include '../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];

/* Pendiente */
# Tabs para enfermería
# Editar en el momento varios de los campos
# funcionalidad para "acciones"
# Modal para suspender tratamiento <---

$prov = $_GET['prov'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Continuados </title>
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
        <?php provValor($prov); ?>
        <small>Servicios continuados</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Continuados</a></li>
        <li class="active">Provincia: General</li>
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
              <li class="active"><a id="ambuClick" href="#tab_1" data-toggle="tab">Ambulancias</a></li>
              <li><a id="dueClick" href="#tab_2" data-toggle="tab">Enfermería</a></li>
			  <li><a id="medClick" href="#tab_3" data-toggle="tab">Médico</a></li>
              <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div id="ambuTab"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div id="dueTab"></div>
              <!-- /.tab-pane -->
              </div>
              <div class="tab-pane" id="tab_3">
                <div id="medTab"></div>
              <!-- /.tab-pane -->
              </div>			  
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
/* Carga tab sin cargar toda la db
 * Se deja ambulancia como permanente
 * la función autoactualizar solamente funciona para ambulancia ya que al clicar
 * en las demás pestañas se autoactualizaran solas
 * Recarga solamente la tab ambulancia, ya que, al moverse por las siguientes tabs
 * Se recargan automáticamente
 */
$(document).ready(function() {
  $.get('/ops/tabsRef/contiTab/ambConTab.php', {prov: "<?php echo isset($prov) ? $prov : 29 ?>", page: "<?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>"}, function(htmlexterno){
    $("#ambuTab").html(htmlexterno);
  });
  $("#dueClick").click(function() {
    $.get('/ops/tabsRef/contiTab/dueConTab.php', {prov: "<?php echo isset($prov) ? $prov : 29 ?>", page: "<?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>"}, function(htmlexterno){
      $("#dueTab").html(htmlexterno);
    });
  });
  $("#medClick").click(function() {
    $.get('/ops/tabsRef/contiTab/medConTab.php', {prov: "<?php echo isset($prov) ? $prov : 29 ?>", page: "<?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>"}, function(htmlexterno){
      $("#medTab").html(htmlexterno);
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
