<?php
session_start();
include '../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];

# Vehículo registrado
$vhRegis = mysqli_query($gestambu, "SELECT idGuardia, idUser, ambu, gEst
  FROM reguardia
  WHERE gEst = 1 AND idUser = '$usuario'
");
$rwVhRegis = mysqli_fetch_assoc($vhRegis);
$ambUser   = $rwVhRegis['ambu'];

# Lista de servicios
$servList = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.idCia, servicio.tipo, servicio.recurso, servicio.fecha, DATE_FORMAT(servicio.hora, '%H:%i') AS hora, servicio.medico, servicio.enfermero, servicio.nombre,
  servicio.apellidos, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.obs, servicio.estServ,
  cia.idCia, cia.ciaNom,
  servi.idServi, servi.nomSer,
  recurso.idRecu, recurso.nomRecu,
  serestados.idEst, serestados.idSv, serestados.vhIda, serestados.vhVta, serestados.estTec
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idREcu
    LEFT JOIN serestados ON servicio.idSv = serestados.idSv
  WHERE servicio.estServ IN ('2', '11') AND serestados.vhIda = '$ambUser'
    OR servicio.estServ = '5' AND serestados.vhVta = '$ambUser'
  ORDER BY servicio.fecha, servicio.hora ASC
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="200" >  
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/docs/dist/css/skins/_all-skins.min.css">
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
        <li>Servicios</a></li>
        <li class="active">Registro de guardia</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="box-body">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <div id="mesaOk"></div>
        </div>
      </div>
    </div>
      <?php
      if($ambUser > 0 ) {
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
            $fechAnte = "//".$rwLiServ['fecha'];
          } else {
            $fechAnte = "";
          }

          if($rwLiServ['estServ'] == '5') {
            $vueltaAsig = "-VTA";
          } else {
            $vueltaAsig = "";
          }

        ?>
      <!-- Default box -->
      <div class="box box-success collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $rwLiServ['hora']." - ".$rwLiServ['nomSer'].$vueltaAsig.$complAmbu." ".$fechAnte; ?></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <dl class="dl-horizontal">
                <dt>ID: </dt>
                <dd><?php echo $rwLiServ['idSv']; ?></dd>
                <dt>Estado: </dt>
                <dd><?php mostrarEstados($rwLiServ['estTec']); ?></dd>
                <dt>Fecha: </dt>
                <dd><?php echo $rwLiServ['fecha']; ?></dd>
                <dt>Nombre: </dt>
                <dd><?php echo $rwLiServ['nombre']." ".$rwLiServ['apellidos']." - ".$rwLiServ['edad']; ?></dd>
                <dt>Compañía: </dt>
                <dd><?php echo $rwLiServ['ciaNom']; ?></dd>
                <dt>Servicio: </dt>
                <dd><?php echo $rwLiServ['nomSer']." - ".$rwLiServ['nomRecu'].$vueltaAsig; ?></dd>
            <?php
            if($rwLiServ['estServ'] == '5') { // VTA continuado
            ?>
                <dt>Recoger: </dt>
                <dd><?php echo $rwLiServ['trasladar']." - ".$rwLiServ['locTras']; ?></dd>
                <dt>Trasladar: </dt>
                <dd><?php echo $rwLiServ['recoger']." - ".$rwLiServ['locRec']; ?></dd>
            <?php } else { ?>
                <dt>Recoger: </dt>
                <dd><?php echo $rwLiServ['recoger']." - ".$rwLiServ['locRec']; ?></dd>
                <dt>Trasladar: </dt>
                <dd><?php echo $rwLiServ['trasladar']." - ".$rwLiServ['locTras']; ?></dd>
            <?php } ?>
                <dt>Observaciones: </dt>
                <dd><?php echo $rwLiServ['obs']; ?></dd>
              </dl>
              <div class="btn-toolbar text-center" role="toolbar">
                <button type="button" class="btn btn-info" onclick="enviarRecibido(<?php echo $rwLiServ['idEst']; ?>);">Recibido</button>
                <button type="button" class="btn btn-primary" onclick="enviarDestino(<?php echo $rwLiServ['idEst']; ?>, <?php echo $rwLiServ['idSv']; ?>);">En destino</button>
                <button type="button" class="btn btn-success" onclick="enviarFin(<?php echo $rwLiServ['idEst']; ?>, <?php echo $rwLiServ['idSv']; ?>);">Finalizado</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
      <?php } } else { ?>
        <!-- Default box -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Sin servicios adjudicados</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-6 col-md-offset-4">
                <h4>No tienes servicios programados</h4>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">

          </div>
          <!-- /.box-footer-->
        </div>
        <!-- /.box -->
      <?php } ?>
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
//Boton Recibido
function enviarRecibido(valor){
  cambiar = valor;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonRecibido.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			alert("Servicio marcado como: Recibido");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar);
}
//Boton En destino
function enviarDestino(valor, identi){
  cambiar = valor;
  idSv = identi;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonDestino.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			alert("Marcado como: En destino");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&iden="+idSv);
}
//Boton finalizado
function enviarFin(valor, identi){
  cambiar = valor;
  idSv = identi;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonFin.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			alert("Marcado como: finalizado - a la espera de finalizar servicio por el operador.");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&iden="+idSv);
}
</script>

</body>
</html>
