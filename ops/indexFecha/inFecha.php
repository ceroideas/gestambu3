<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Valores para selección de soporte */
# Obtener provinca
if(isset($_GET['prov'])) {
  $prov = $_GET['prov'];
} else {
  $prov = 0;
}
# Conseguir fecha de ayer
if(isset($_GET['diaSel'])) {
  $filFecha = $_GET['diaSel'];
} else {
  $filFecha = date('0000-00-00'); // cambio date('Y-m-d');
}
# Opciones
if(isset($_GET['opcion'])) {
  $esp = $_GET['opcion'];
} else {
  $esp = "0";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Index por fecha | GestAmbu 3.0</title>
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

  <script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="/docs/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="/docs/plugins/datatables/dataTables.bootstrap.min.js"></script>
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
        <!-- fecha actual o fecha si se escoge otra para visualizar -->
        <!-- Espacio reservado para texto -->
        <small><?php echo fechaEs(); ?></small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Servicios por fecha</h3>
              <!-- opciones para poder cerrar ventana o contraer -->
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body" id="">
              <!-- formulario -->
			  <form class="form-group">
				  <div class="form-group col-md-2 col-sm-4 col-xs-12">
					<label>Fecha: </label>
					<input type="date" class="form-control" name="diaSel" value="<?php echo $filFecha; ?>">
				  </div>
				  <div class="form-group col-md-2 col-sm-2 col-xs-12">
					<label>Opción: </label>
				    <select class="form-control" name="opcion" id="recurso" required>
					  <option value="">-- Opción --</option>
					  <option value="1" <?php if($esp == '1') { echo "selected";} ?>> Finalizados </option>
					  <option value="2" <?php if($esp == '2') { echo "selected";} ?>> Activos </option>
				    </select>
				  </div>
				  <div class="form-group col-md-2 col-sm-2 col-xs-12">
					<label>Provincia: </label>
					<select class="form-control" name="prov" id="recurso" required>
					  <option value="">-- Provincia --</option>
					  <option value="0"  <?php if($prov == '0') { echo "selected"; } ?>> General </option>
					  <option value="11" <?php if($prov == '11') { echo "selected"; } ?>> Cádiz </option>
					  <option value="14" <?php if($prov == '14') { echo "selected"; } ?>> Córdoba </option>
					  <option value="29" <?php if($prov == '29') { echo "selected"; } ?>> Málaga </option>
					  <option value="52" <?php if($prov == '52') { echo "selected"; } ?>> Melilla </option>
					  <option value="21" <?php if($prov == '21') { echo "selected"; } ?>> Huelva </option>					  
					  <option value="41" <?php if($prov == '41') { echo "selected"; } ?>> Sevilla </option>				  
					</select>
				  </div>				  
				  <div class="form-group col-md-1 col-sm-2 col-xs-12">
					<label> &nbsp; </label>
					<div class="input-group">
					  <button type="submit" class="btn btn-default">Consultar</button>
					</div>
				  </div>
			  </form>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Servicios para: </h3><strong> <?php provValor($prov); ?></strong>	  
            </div>
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a id="ambuClick" href="#tab_1" data-toggle="tab">Ambulancia</a></li>
                <li><a id="medicoClick" href="#tab_2" data-toggle="tab">Médico</a></li>
                <li><a id="dueClick" href="#tab_3" data-toggle="tab">Enfermero</a></li>
                <li><a id="vuelosClick" href="#tab_4" data-toggle="tab">Vuelos</a></li>
                <li><a id="rutaClick" href="#tab_5" data-toggle="tab">Ruta</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                  <div id="ambuTab">
                    <?php include '../tabIndex/ambulanceTab.php'; ?>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                  <div id="medicoTab">
                    <?php include '../tabIndex/medicTab.php'; ?>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                  <div id="dueTab">
                    <?php include '../tabIndex/nurseTab.php'; ?>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane " id="tab_4">
                  <div id="vuelosTab">
                    <?php include '../tabIndex/vuelosTab.php'; ?>
                  </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_5">
                  <div id="rutaTab">
                    <?php include '../tabIndex/routeTab.php'; ?>
                  </div>
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

<?php include '../inc/pie.php'; ?>

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
/* Carga tab sin cargar toda la db
 * Se deja ambulancia como permanente
 * la función autoactualizar solamente funciona para ambulancia ya que al clicar
 * en las demás pestañas se autoactualizaran solas
 * Recarga solamente la tab ambulancia, ya que, al moverse por las siguientes tabs
 * Se recargan automáticamente
 */
/*$(document).ready(function() {
  $.get('tabIndex/ambulanciaTab.php', {prov: "<?php echo $prov; ?>", opcion:"<?php echo $esp; ?>", diaSel:"<?php echo $diaSel; ?>" }, function(htmlexterno){
    $("#ambuTab").html(htmlexterno);
  });
 });
  $("#medicoClick").click(function() {
   $.get('tabIndex/medicoTab.php', {prov: "<?php echo $prov; ?>", opcion:"<?php echo $esp; ?>", diaSel:"<?php echo $diaSel; ?>" }, function(htmlexterno){
     $("#medicoTab").html(htmlexterno);
   });
  });
  $("#dueClick").click(function() {
   $.get('tabIndex/dueTab.php', {prov: "<?php echo $prov; ?>", opcion:"<?php echo $esp; ?>", diaSel:"<?php echo $diaSel; ?>" }, function(htmlexterno){
     $("#dueTab").html(htmlexterno);
   });
  });
  $("#vuelosClick").click(function() {
   $.get('tabIndex/vuelosTab.php', {coche: "Ford", modelo: "Focus", color: "rojo"}, function(htmlexterno){
     $("#vuelosTab").html(htmlexterno);
  });
});
$("#rutaClick").click(function() {
 $.get('tabIndex/rutab.php', {prov: "<?php echo $prov; ?>", opcion:"<?php echo $esp; ?>", diaSel:"<?php echo $diaSel; ?>" }, function(htmlexterno){
   $("#rutaTab").html(htmlexterno);
 });
});*/


function nuevaNota() {
  open('/ops/referencia/notaVh.php','','top=50,left=500,width=500,height=400') ;
}
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
