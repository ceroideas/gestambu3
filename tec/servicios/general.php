<?php
session_start();
include '../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];

# Vehículo registrado
$vhRegis = mysqli_query($gestambu, "SELECT reguardia.idGuardia, reguardia.idUser, reguardia.gEst, regambu.regId, regambu.idGuardia, regambu.ambu, regambu.estAmbu
  FROM reguardia
    LEFT JOIN regambu ON reguardia.idGuardia = regambu.idGuardia
  WHERE reguardia.gEst = 1 AND reguardia.idUser = '$usuario' AND regambu.estAmbu IN('1','3')
");
$numVhRegis= mysqli_num_rows($vhRegis);
$rwVhRegis = mysqli_fetch_assoc($vhRegis);
$ambUser   = $rwVhRegis['ambu'];
$ahora     = date("Y-m-d");

# Lista de servicios
$servList = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.idCia, servicio.continuado, servicio.tipo, servicio.recurso, servicio.fecha, DATE_FORMAT(servicio.hora, '%H:%i') AS hora, servicio.medico, 
  servicio.enfermero, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.obs, servicio.estServ, servicio.idvta,
  cia.idCia, cia.ciaNom,
  servi.idServi, servi.nomSer, servi.icono, servi.bgColor,
  recurso.idRecu, recurso.nomRecu, recurso.recuCorto,
  serestados.idEst, serestados.idSv, serestados.vhIda, serestados.vhVta, serestados.estTec,
  serinfo.idSv, serinfo.prioridad
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idREcu
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
  WHERE servicio.estServ IN ('2', '11') AND serestados.vhIda = '$ambUser'
    OR servicio.estServ = '5' AND serestados.vhVta = '$ambUser'
  ORDER BY servicio.fecha, serinfo.prioridad, servicio.hora ASC
  ");
$numRow = mysqli_num_rows($servList);

# Listado de mensajes
$msjList = mysqli_query($gestambu, "SELECT idNota, descNota, vhId, notaEst, DATE_FORMAT(creado, '%d-%m-%Y %H:%i') AS creadoFor, creado FROM notas WHERE vhId='$ambUser' AND notaEst='1' AND creado >= '$ahora 00:00:00' ORDER BY creado DESC");
$numMsj  = mysqli_num_rows($msjList);
/*
echo "
SELECT idNota, descNota, vhId, notaEst, DATE_FORMAT(creado, '%d-%m-%Y %H:%i') AS creadoFor, creado FROM notas WHERE vhId='$ambUser' AND notaEst='1' AND creado >= '$ahora 00:00:00' ORDER BY creado DESC
";
*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Servicios | GestAmbu 3.0 </title>
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
  A:link {text-decoration: none; color: white; }
  A:visited {color: white;  font-family: arial; text-decoration: none }
  A:hover { color: white; font-family: arial; text-decoration: none }
  *{outline:none !important;}*:focus {outline: none !important;}textarea:focus, input:focus{outline: none !important;}	a{text-decoration: none !important;outline: none !important;}
  .alert {
    margin-bottom: 5px;
  }
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
        Servicios programados
      </h1>
      <ol class="breadcrumb">
        <li><a href="/tec/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Servicios</a></li>
        <li class="active">Listado</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php
    if($numVhRegis == 0) { // usuario no registrado
    ?>
    <div class="modMargen">
      <div class="info-box modMargen">
        <div class="box-header">
          <h4>No estás registrado. No se pueden mostrar servicios adjudicados.</h4>
        </div>
      </div>
    </div>
    <?php
  } else { ?>
    <?php if($numMsj > 0) {
      while($rwMsj = mysqli_fetch_assoc($msjList)) {
    ?>

    <div class="box-body">
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="cerrarNota(<?php echo $rwMsj['idNota']; ?>, <?php echo $usuario; ?>);">&times;</button>
        <h4><i class="icon fa fa-info"></i> Mensaje - <?php echo $rwMsj['creadoFor']; ?></h4>
        <?php echo $rwMsj['descNota']; ?>
      </div>
    </div>
    <?php
      }
    }
      if($numRow > 0 ) {
        while($rwLiServ = mysqli_fetch_assoc($servList)) {
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
			$labP = "2";
          } elseif($rwLiServ['prioridad'] == '2') {
            $prio = "<i>- PREFERENTE - </i>";
			$labP = "1";
          } else {
            $prio = "";
			$labP = "0";
          }
        ?>
        <div class="modMargen">
          <div class="info-box bg-<?php if($labP == '1') { echo "orange"; } elseif($labP == '2') { echo "maroon"; } else { echo $rwLiServ['bgColor']; } ?> modMargen">
            <div class="box-header">
              <?php echo "<strong>".$prio.$rwLiServ['hora']."-".$rwLiServ['nomSer']."</strong>-".$rwLiServ['recuCorto'].$vueltaAsig.$complAmbu." ".$fechAnte; ?> <?php if(verUltima($rwLiServ['continuado']) == 1) { echo "<strong>ULTIMA-RENOVAR??</strong>"; } ?>
            </div>
            <a href="detaservi.php?iden=<?php echo $rwLiServ['idSv']; ?>" title="Ver servicio completo">
              <span class="info-box-icon"><i class="fa fa-<?php echo $rwLiServ['icono']; ?>"></i></span>
            </a>
            <div class="info-box-content">
              <span class="info-box-text modText"><?php echo $rwLiServ['ciaNom']; ?></span>
            <?php
              if($rwLiServ['estServ'] == 5) {
            ?>
              <span class="info-box-number modText"><?php echo $rwLiServ['trasladar']." - ".$rwLiServ['locTras']; ?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 0%"></div>
              </div>
                <span class="progress-description modText">
                  <?php
                   if($rwLiServ['idRecu'] == 2 || $rwLiServ['idRecu'] == 4 || $rwLiServ['idRecu'] == 6) {
                     echo $rwLiServ['obs'];
                   } else {
                     echo $rwLiServ['recoger']." - ".$rwLiServ['locRec'];
                   }
                  ?>
                </span>
            <?php } elseif($rwLiServ['estServ'] == 11 && $rwLiServ['idvta'] == 3) {  // sólo vuelta ?>
              <span class="info-box-number modText"><?php echo $rwLiServ['trasladar']." - ".$rwLiServ['locTras']; ?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 0%"></div>
              </div>
                <span class="progress-description modText">
                  <?php
                   if($rwLiServ['idRecu'] == 2 || $rwLiServ['idRecu'] == 4 || $rwLiServ['idRecu'] == 6) {
                     echo $rwLiServ['obs'];
                   } else {
                     echo $rwLiServ['recoger']." - ".$rwLiServ['locRec'];
                   }
                  ?>
                </span>
            <?php } else { ?>
              <span class="info-box-number modText"><?php echo $rwLiServ['recoger']." - ".$rwLiServ['locRec']; ?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 0%"></div>
              </div>
                <span class="progress-description modText">
                  <?php
                   if($rwLiServ['idRecu'] == 2 || $rwLiServ['idRecu'] == 4 || $rwLiServ['idRecu'] == 6) {
                     echo $rwLiServ['obs'];
                   } else {
                     echo $rwLiServ['trasladar']." - ".$rwLiServ['locTras'];
                   }
                  ?>
                </span>
            <?php } ?>
            </div>
          </div>
        </div>
      <?php } } else { ?>
        <div class="modMargen">
          <div class="info-box modMargen">
            <div class="box-header">
              <h4>No tienes servicios programados</h4>
            </div>
          </div>
        </div>
      <?php } }?>
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

<script>
function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function cerrarNota(idNota, tecnico) {
  idNota  = idNota;
  tecnico = tecnico;
  //instanciamos el objetoAjax
  ajax=objetoAjax();
  //usando del medoto POST
  //archivo que realizará la operacion

  ajax.open("POST", "/tec/js/cerrarNota.php",true);
  ajax.onreadystatechange=function() {
    if (ajax.readyState==4) {
      //mostrar un mensaje de actualizacion correcta
      //alert("Servicio marcado como: Recibido");
    }
  };
  //muy importante este encabezado ya que hacemos uso de un formulario
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
  ajax.send("idNota="+idNota+"&tecnico="+tecnico);
}

</script>

</body>
</html>
<?php
mysqli_close($gestambu);
?>
