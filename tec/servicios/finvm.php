<?php
session_start();
include '../../functions/function.php';
nonUser();

if(@$_POST['guardar'] == 'enviar') {
  # Recoge datos
  $diagnostico = trim(mysqli_real_escape_string($gestambu, $_POST['diagnostico']));
  $identi      = trim(mysqli_real_escape_string($gestambu, $_POST['identi']));
  # guarda diagnóstico
  # guarda km finalizado
  # guarda hora de finalizado
  # modifica estado a finalizado
  # envia notificación a asisa - con diagnóstico
  $diagUp = "UPDATE especial SET diagnostico = '$diagnostico' WHERE idSv = '$identi'";
  if(mysqli_query($gestambu,$diagUp)) {
	$dUp = 1;
  } else {
	echo "ERROR";
  }

}

$usuario = $_SESSION['userId'];
$idSv = $_GET['iden'];
$demAsisa = $_GET['demAsisa'];

# Vehículo registrado
$vhRegis = mysqli_query($gestambu, "SELECT reguardia.idGuardia, reguardia.idUser, reguardia.cate, reguardia.gEst, regambu.regId, regambu.idGuardia, regambu.ambu, regambu.estAmbu
  FROM reguardia
    LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
  WHERE reguardia.gEst = 1 AND reguardia.idUser = '$usuario' AND regambu.estAmbu IN('1','3')
");
$rwVhRegis = mysqli_fetch_assoc($vhRegis);
$ambUser   = $rwVhRegis['ambu'];

# Lista de servicios
$servList = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.coDemanda, servicio.idCia, servicio.tipo, servicio.recurso, servicio.fecha, DATE_FORMAT(servicio.hora, '%H:%i') AS hora, servicio.medico, servicio.enfermero,
  servicio.idvta, servicio.nombre, servicio.apellidos, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.obs, servicio.estServ,
  cia.idCia, cia.ciaNom,
  servi.idServi, servi.nomSer, servi.icono, servi.bgColor,
  recurso.idRecu, recurso.nomRecu, recurso.recuCorto,
  serestados.idEst, serestados.idSv, serestados.vhIda, serestados.vhVta, serestados.estTec, serestados.estTecVta,
  serinfo.idSv, serinfo.prioridad
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idREcu
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
  WHERE servicio.idSv = '$idSv' AND servicio.estServ IN ('2', '11')
  ");
$rwLiServ = mysqli_fetch_assoc($servList);

/* Listado cie10 */
$cie10 = mysqli_query($gestambu, "SELECT * FROM cie10 ORDER BY denominacion ASC  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Diagnóstico | GestAmbu 3.0 </title>
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="/docs/dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="/docs/plugins/tableExp/css/bootstrap-table-expandable.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  @media (min-width: 768px) {
    .dl-horizontal dt {
      width: 180px;

    }
    .dl-horizontal dd {
      margin-left: 200px;
    }
  }
  .info-box-icon {
    background: rgba(0,0,0,0);
  }
  .modMargen {
    margin-bottom: 5px;
    min-height: 105px;
  }
  .box-body {
    padding: 0px;
  }
  .content {
    padding: 5px;
    padding-left: 15px;
    padding-right: 15px;
  }
  .modText {
    font-size: 0.9em;
  }
  .box-header {
    padding: 5px 10px 0px 10px;
  }
  A:link {text-decoration: none }
  A:visited {color: black;  font-family: arial; text-decoration: none }
  A:hover { color: black; font-family: arial; text-decoration: none }
  *{outline:none !important;}*:focus {outline: none !important;}textarea:focus, input:focus{outline: none !important;}	a{text-decoration: none !important;outline: none !important;}
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">

<?php include '../inc/supbar.php'; ?>
<?php include '../inc/menubar.php'; ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Diagnóstico
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="/tec/servicios/general.php">Listado de servicios</a></li>
        <li class="active">Detalles</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
      if($rwLiServ['medico'] == '1' AND $rwLiServ['enfermero'] == '1') {
        $complAmbu = "+MED+DUE";
      } elseif($rwLiServ['medico'] == '1' AND $rwLiServ['enfermero'] == '') {
        $complAmbu = "+MED";
      } elseif($rwLiServ['medico'] == '' AND $rwLiServ['enfermero'] == '1') {
        $complAmbu = "+DUE";
      } elseif($rwLiServ['medico'] == '' AND $rwLiServ['enfermero'] == '') {
        $complAmbu = "";
      } else {
        $complAmbu = "";
      }

      if($rwLiServ['fecha'] != date('Y-m-d')) {
        $fechAnte = " <i class=\"fa fa-exclamation-triangle\"></i>".$rwLiServ['fecha'];
      } else {
        $fechAnte = "";
      }

      if($rwLiServ['estServ'] == '5') {
        $vueltaAsig = "-VTA";
      } else {
        $vueltaAsig = "";
      }
      if($rwLiServ['prioridad'] == '1') {
        $prio = "<i>- URGENTE - </i>";
      } elseif($rwLiServ['prioridad'] == '2') {
        $prio = "<i>- PREFERENTE - </i>";
      } else {
        $prio = "";
      }
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <div class="box-header with-border">
          <h3 class="box-title">Diagnóstico: <?php echo $rwLiServ['idSv']; ?></h3>
        </div>
        <!-- Mensajes -->
        <div id="resultado"></div>
        <!-- /Mensajes -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <dl class="dl-horizontal">
                <dt>Nombre: </dt>
                <dd><?php echo $rwLiServ['nombre']." ".$rwLiServ['apellidos']." - ".$rwLiServ['edad']; ?></dd>
                <dt>Compañía: </dt>
                <dd><?php echo $rwLiServ['ciaNom']; ?></dd>
                <dt>Servicio: </dt>
                <dd><?php echo $rwLiServ['nomSer']." - ".$rwLiServ['recuCorto'].$vueltaAsig; ?></dd>
                <dt>Recoger: </dt>
                <dd><?php echo $rwLiServ['recoger']." - ".$rwLiServ['locRec']; ?></dd>
                <dt>Observaciones: </dt>
                <dd><?php echo $rwLiServ['obs']; ?></dd>				
              </dl>
            </div>
          </div>
        </div>
      </div>
      <div class="box-footer no-padding">
        <form name="diaG" id="diaG" action="" method="post" onsubmit="guardarDiagnostico(<?php echo $rwLiServ['idSv']; ?>); return false">
          <ul class="nav nav-pills nav-stacked">
            <li><a href=""><i class="fa fa-exclamation-triangle"></i> Se guardarán los km de llegada como km de finalizado.</a></li>
            <li>
              <select class="form-control" name="diagnostico" required="">
                <option value="">-- Sin diagnóstico --</option>
                <?php
                while($rwCie = mysqli_fetch_assoc($cie10)) {
                  echo "<option value='".$rwCie['codigo']."'>".$rwCie['denominacion']."</option>\n";
                }
                ?>
              </select>
            </li>
          </ul>
          <h4>
		  <input type="hidden" name="identi" value="<?php echo $rwLiServ['idSv']; ?>"/>
            <button
              type="submit" name="guardar" value="enviar" class="btn btn-info pull-right"
              onclick="enviarFinVM(<?php echo $rwLiServ['idEst']; ?>, <?php echo $rwLiServ['idSv']; ?>, <?php echo $rwLiServ['estServ']; ?>, <?php if(empty($rwLiServ['idvta'])) { echo "0"; } else { echo $rwLiServ['idvta']; } ?>, <?php echo $demAsisa; ?>, <?php echo $rwLiServ['idCia']; ?>, <?php echo $rwLiServ['recurso']; ?>);">Enviar y finalizar</button>
          </h4>
        </form>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include '../inc/pie.php'; ?>
<?php //include '../inc/bcontrol.php'; ?>

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
<script src="/tec/js/detaservi.js"></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
