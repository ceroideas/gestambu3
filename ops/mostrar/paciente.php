<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Datos para paciente */
$pacID = $_GET['idPac'];

$mosPac = mysqli_query($gestambu, "SELECT * FROM paciente WHERE idPac = '$pacID' ");
$rwMosPac = mysqli_fetch_assoc($mosPac);

/* Contadores */
# -> cuenta disntinto a ANULADO o SUSPENDIDO

# Ambulancia
$contAmbu = mysqli_query($gestambu, "SELECT recurso, estServ, idPac FROM servicio WHERE idPac = '$pacID' AND estServ NOT IN ('15', '16') AND recurso = '1' ");
$numAmbu = mysqli_num_rows($contAmbu);

# UVI
$contUVI = mysqli_query($gestambu, "SELECT recurso, estServ, idPac FROM servicio WHERE idPac = '$pacID' AND estServ NOT IN ('15', '16') AND recurso = '3' ");
$numUVI = mysqli_num_rows($contUVI);

# Enfermería
$contDue = mysqli_query($gestambu, "SELECT recurso, estServ, idPac FROM servicio WHERE idPac = '$pacID' AND estServ NOT IN ('15', '16') AND recurso = '2' ");
$numDue = mysqli_num_rows($contDue);

# Médico
$contMed = mysqli_query($gestambu, "SELECT recurso, estServ, idPac FROM servicio WHERE idPac = '$pacID' AND estServ NOT IN ('15', '16') AND recurso = '4' ");
$numMed = mysqli_num_rows($contMed);

# Seguimiento
$contSegMed = mysqli_query($gestambu, "SELECT tipo, estServ, idPac FROM servicio WHERE idPac = '$pacID' AND estServ NOT IN ('15', '16') AND tipo = '9' ");
$numSegMed = mysqli_num_rows($contSegMed);

# Ruta
$contRuta = mysqli_query($gestambu, "SELECT tipo, estServ, idPac FROM servicio WHERE idPac = '$pacID' AND estServ NOT IN ('15', '16') AND recurso = '7' ");
$numRuta = mysqli_num_rows($contRuta);

#Nota de paciente
$pacNota = mysqli_query($gestambu, "SELECT idPac, descNota, creaNota FROM pacnota WHERE idPac = '$pacID' ORDER BY creaNota DESC LIMIT 3");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | <?php echo $rwMosPac['pNombre']." ".$rwMosPac['pApellidos']; ?></title>
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
        <i>Historial:</i>
        <strong><?php echo $rwMosPac['idPac']; ?></strong>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Paciente</li>
        <li class="active"><?php echo $rwMosPac['pNombre']." ".$rwMosPac['pApellidos']; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <div class="box-tools pull-right">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" title="Opciones">
                    <i class="fa fa-bars"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwMosPac['idPac']; ?>&selRec=1"><i class="fa fa-ambulance"></i>Crear ambulancia</a></li>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwMosPac['idPac']; ?>&selRec=3"><i class="fa fa-heartbeat"></i>Crear U.V.I.</a></li>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwMosPac['idPac']; ?>&selRec=2"><i class="fa fa-eyedropper"></i>Crear enfermería</a></li>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwMosPac['idPac']; ?>&selRec=4"><i class="fa fa-stethoscope"></i>Crear médico</a></li>
                    <?php if($rwMosPac['segMed'] == 1) { ?>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwMosPac['idPac']; ?>&selRec=4"><i class="fa fa-stethoscope"></i>Crear seguimiento</a></li>
                    <?php } ?>
                    <li class="divider"></li>
                    <li><a href="/ops/nuevo/contVincuPac.php?iden=<?php echo $rwMosPac['idPac']; ?>"><i class="fa fa-calendar"></i>Crear continuado</a></li>
                    <li class="divider"></li>
                    <?php if($rwMosPac['segMed'] == 0 ) { ?>
                    <li><a href="/ops/referencia/paciente/asigMed.php?iden=<?php echo $rwMosPac['idPac'];?>"><i class="fa fa-tag"></i>Marcar como Seg. Médico</a></li>
                    <?php } ?>
                    <li><a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwMosPac['idPac']; ?>"><i class="fa fa-file-text-o"></i>Ver Ficha de paciente</a></li>
                  </ul>
                </div>
              </div>
              <div class="clearfix"></div>
              <h3 class="profile-username text-center"><?php echo $rwMosPac['pNombre']." ".$rwMosPac['pApellidos']; ?></h3>

              <p class="text-muted text-center"><?php echo mostrarCia($rwMosPac['idCia']); ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Ambulancias</b> <a class="pull-right"><?php echo $numAmbu; ?></a>
                </li>
                <li class="list-group-item">
                  <b>U.V.I.</b> <a class="pull-right"><?php echo $numUVI; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Enfermería</b> <a class="pull-right"><?php echo $numDue; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Médicos</b> <a class="pull-right"><?php echo $numMed; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Ruta</b> <a class="pull-right"><?php echo $numRuta; ?></a>
                </li>				
                <?php if($rwMosPac['segMed'] == 1 ) { ?>
                <li class="list-group-item">
                  <b>Seg. Médico</b>
                  (<?php if($rwMosPac['tipoSeg'] == '1') { echo " Crónico "; } elseif($rwMosPac['tipoSeg'] == '2') { echo " Paliativo "; } else { "No identificado"; } ?>)
                  <a class="pull-right"><?php echo $numSegMed; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Médico Asignado</b> <a class="pull-right"><?php mostrarTecnico($rwMosPac['medAsig']); ?></a>
                </li>
                <?php } ?>
                <?php if($rwMosPac['fallecido'] == '1') { ?>
                <li class="list-group-item">
                    <span class="label label-danger"><b> Fallecido</b></span>
                </li>
                <?php } ?>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Datos de paciente</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <strong><i class="fa fa-map-o margin-r-5"></i> Notas</strong>
              <?php while($rNota = mysqli_fetch_assoc($pacNota)) { ?>
			  <p class="text-muted"><?php echo "<strong>[".$rNota['creaNota']."]</strong> ".$rNota['descNota']; ?></p>
			  <?php } ?>
			  <div class="clearfix"></div>
			  
              <strong><i class="fa fa-list-alt margin-r-5"></i> Póliza</strong>
              <p class="text-muted"><div class="poli" id="poliza-<?php echo $rwMosPac['idPac']; ?>"><?php echo $rwMosPac['poliza']; ?></div></p>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Dirección</strong>
              <p class="text-muted"><?php echo $rwMosPac['direccion']." - ".$rwMosPac['localidad']; ?></p>

              <strong><i class="fa fa-phone margin-r-5"></i> Teléfonos</strong>
              <p class="text-muted"><?php echo $rwMosPac['tlf1']; ?></p>
              <p class="text-muted"><?php echo $rwMosPac['tlf2']; ?></p>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Edad</strong>
              <p class="text-muted"><?php echo $rwMosPac['edad']; ?></p>

              <hr>
              <!-- Notas de paciente
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notas</strong>

              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
              -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#ambulancia" data-toggle="tab">Ambulancia</a></li>
              <li><a href="#due" data-toggle="tab">Enfermería</a></li>
              <li><a href="#v_m" data-toggle="tab">Visitas médicas</a></li>
              <li><a href="#seg_med" data-toggle="tab">Seg. médico</a></li>
			  <li><a href="#ruta" data-toggle="tab">Rutas</a></li>
              <li><a href="#historial" data-toggle="tab">Historial</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="ambulancia">
                <div id="ambuTab"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="due">
                <div id="dueTab"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="v_m">
                <div id="vmTab"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="seg_med">
                <div id="segMedTab"></div>
              </div>
			  <!-- /.tab-pane -->
              <div class="tab-pane" id="ruta">
                <div id="ruTab"></div>
              </div>			  
              <!-- /.tab-pane -->
              <div class="tab-pane" id="historial">
                <div id="hisTab"></div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
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
<!-- Jeditable -->
<script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
<script src="/ops/referencia/jeditable/jeditableHPac.js"></script>
<!-- Carga de tab -->
<script type="text/javascript">
//Falta click.function
$(document).ready(function() {
  $.get('/ops/tabsRef/tabsPac/ambuTabPac.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#ambuTab").html(htmlexterno);
  });
  $.get('/ops/tabsRef/tabsPac/dueTabPac.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#dueTab").html(htmlexterno);
  });
  $.get('/ops/tabsRef/tabsPac/vmTabPac.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#vmTab").html(htmlexterno);
  });
  $.get('/ops/tabsRef/tabsPac/segMedTabPac.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#segMedTab").html(htmlexterno);
  });
  $.get('/ops/tabsRef/tabsPac/segMedTabPac.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#segMedTab").html(htmlexterno);
  });
  $.get('/ops/tabsRef/tabsPac/rutaTab.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#ruTab").html(htmlexterno);
  });  
  $.get('/ops/tabsRef/tabsPac/hisTabPac.php', {pacID: "<?php echo $pacID; ?>"}, function(htmlexterno){
    $("#hisTab").html(htmlexterno);
  });
});

</script>
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
