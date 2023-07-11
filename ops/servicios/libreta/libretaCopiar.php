<?php
session_start();
include '../../../functions/function.php';
nonUser();

if(isset($_POST['diaMostrar'])) {
  $diaMostrar = $_POST['diaMostrar'];
} else {
  $diaMostrar = date("Y-m-d");
}

if(isset($_GET['prov'])) {
  $provincia = $_GET['prov'];
} else {
  $provincia = '29';
}

if(isset($_GET['recuSel'])) {
  $recuSel = $_GET['recuSel'];
} else {
  $recuSel = '1';
}
//para ver rutas poner recurso 7
if($recuSel == 1) {
  //Ambulancias
  $listLibreta = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.medico, servicio.enfermero, servicio.idvta, servicio.nombre, servicio.orden,
      servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, servicio.obs, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto, serestados.idSv, serestados.vhIda,
      vehiculo.idVh, vehiculo.matricula,
	  especial.idSv,especial.ox, especial.rampa, especial.dTec	  
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
	  LEFT JOIN especial ON especial.idSv = servicio.idSv	  
    WHERE servicio.fecha = '$diaMostrar' AND servicio.provincia='$provincia' AND servicio.recurso IN('1', '3', '5') AND estServ NOT IN('3','10','14','15','16')
    ORDER BY servicio.orden, servicio.hora, servicio.nombre ASC
    ");
} elseif($recuSel == 2 ) {
  //Enfermería
  $listLibreta = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.enfermero, servicio.nombre, servicio.orden, servicio.obs,
      servicio.recoger, servicio.locRec, servicio.estServ, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto, serestados.idSv, serestados.vhIda, vehiculo.idVh, vehiculo.matricula
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
    WHERE servicio.fecha = '$diaMostrar' AND servicio.provincia='$provincia' AND servicio.recurso = '2' AND estServ NOT IN('3','10','14','15','16')
    ORDER BY servicio.codRuta, servicio.orden, servicio.hora, servicio.nombre ASC
    ");	
} elseif($recuSel == 7 ) {
  //Enfermería
  $listLibreta = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.enfermero, servicio.nombre, servicio.orden, servicio.obs,
      servicio.recoger, servicio.locRec, servicio.estServ, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto, serestados.idSv, serestados.vhIda, vehiculo.idVh, vehiculo.matricula
    FROM servicio
      LEFT JOIN cia ON servicio.idCia = cia.idCia
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
      LEFT JOIN serestados ON servicio.idSv = serestados.idSv
      LEFT JOIN vehiculo ON serestados.vhIda = vehiculo.idVh
    WHERE servicio.fecha = '$diaMostrar' AND servicio.provincia='$provincia' AND servicio.recurso = '7' AND estServ NOT IN('3','10','14','15','16')
    ORDER BY servicio.codRuta, servicio.orden, servicio.hora, servicio.nombre ASC
    ");	
}

$numLibreta = mysqli_num_rows($listLibreta);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Libreta de: <?php provValor($provincia); ?>-<?php if($recuSel == '1') { echo "Ambulancia"; } elseif($recuSel == 2) { echo "Enfermero";} ?></title>
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
  <style>
  <style>
  .sinmar {
    margin: 1px;
    padding-top: 3px;
    padding-bottom: 3px;
  }
  .table>tbody>tr>td {
    padding: 1px;
  }
  .aumText {
    font-size: 0.85em;
  }
  .alert a {
    text-decoration: none;
    color: #98FB98;
  }
  @media print {
    a[href]:after {
      content: none !important;
    }
  }
  .box {
	  padding-left: 5px;
	  padding-right: 5px;
  }
  .nav-tabs-custom>.tab-content {
	  padding: 2px;
  }
  .libretaCss {
	  font-size: 12px;
	  font-family: Calibri, Arial, sans-serif;
	  padding: 2px;
  }
  </style>
  </style>
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
        <!-- fecha actual o fecha si se escoge otra para visualizar -->
        <!-- Espacio reservado para texto -->
        <small><?php echo fechaEs(); ?></small>
      </h1>
      <!-- Navegador de posición de página (migas) -->
      <ol class="breadcrumb">
        <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Servicios</li>
        <li class="active">Libreta: <?php provValor($provincia); ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-<?php if($recuSel == '1') { echo "ambulance"; } elseif($recuSel == 2) { echo "cut";} ?>"></i>
                Filtro para servicios:
                <strong><?php provValor($provincia); ?>-<i><?php if($recuSel == '1') { echo "Ambulancia"; } elseif($recuSel == 2) { echo "Enfermero";} ?></i></strong>
              </h3>
              <!-- opciones para poder cerrar ventana o contraer -->
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>

            <div class="box-body">
              <div class="col-md-4 col-md-offset-4">
                <!-- form start -->
                <form class="form-horizontal" action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="fechaConsu" class="col-sm-2 control-label">Fecha:</label>

                      <div class="col-md-6 col-sm-10">
                        <input type="date" class="form-control" name="diaMostrar" value="<?php echo $diaMostrar; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-7 col-md-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Consultar</button>
                        <a href="/ops/referencia/pdf/libretaPdf.php?provincia=<?php echo $provincia; ?>&fecha=<?php echo $diaMostrar; ?>&recuSel=<?php echo $recuSel; ?>" role="button" class="btn btn-info pull-right" target="_blank"><i class="fa fa-print"></i> Imprimir</a>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-footer -->
                </form>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
      <!-- Listado sortable -->
      <div class="box box-primary">
        <div class="box-header">
          <i class="ion ion-clipboard"></i>

          <h3 class="box-title">Servicios para día: <strong> <?php echo cambiarFecha($diaMostrar); ?></strong></h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if($numLibreta == 0) { echo "No existen resultados para la fecha seleccionada."; } else { ?>
          <div class="box-body table-condensed no-padding">
                <?php
                  $i = 1;
                  while($rwList = mysqli_fetch_array($listLibreta)) {
                    $id = $rwList['idSv'];
					/* ULTIMA SESION */
					$codConti = $rwList['continuado'];
					if($codConti != '0') {
						//Es continuado
						$sqlComp = mysqli_query($gestambu, "SELECT continuado, estServ FROM servicio WHERE continuado='$codConti' AND estServ NOT IN('10','14','15')");
						$numComp = mysqli_num_rows($sqlComp);
						
						if($numComp == '1') {
							$selUlti = 1;
							$ulText = "ULTIMA";
							$obsText = "ULTIMA, RENOVAR??";
						} else {
							$selUlti = 0;
							$ulText = "";
							$obsText = "";						
						}
					} else {
						$selUlti = 0;
						$ulText = "";
						$obsText = "";						
					}
					/* ESPECIAL */
					if($rwList['ox'] == '1') {
						$oxEsp = "(CON OX)";
					} else {
						$oxEsp = "";
					}
					if($rwList['rampa'] == '1') {
						$rampaEsp = "(RAMPA)";
					} else {
						$rampaEsp = "";
					}
					if($rwList['dTec'] == '1') {
						$dTecEsp = "(2 TEC.)";
					} else {
						$dTecEsp = "";
					}					
                ?>
				<div class="libretaCss">
				  <small>
                  <?php echo substr($rwList['hora'], 0, 5); ?>
                  <strong><?php if($selUlti == '1') { echo $ulText; } else { @ambComple($rwList['recurso'], $rwList['enfermero'], $rwList['medico'], $rwList['recuCorto']);} ?></strong>
                  <?php echo substr($rwList['recoger'], 0 ,19); ?> - 
                  <strong><?php echo $rwList['locRec']; ?></strong> - 
                  <?php echo substr(@$rwList['trasladar'], 0, 19); ?> - 
                  <strong><?php echo @$rwList['locTras']; ?></strong> - <?php if($rwList['idvta'] == 1) { echo "<em> Y VUELTA -</em>"; } ?>
                  <strong><?php echo $rwList['matricula']; ?></strong>
				  <?php echo "<u>".$oxEsp.$rampaEsp.$dTecEsp."</u>"; ?>
				  </small>
				</div>
                <?php } ?>
          </div>
          <?php } ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix no-border">

        </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
<script src="/docs/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
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
<!-- Jeditable -->
<script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
<script src="/ops/referencia/jeditable/jeditableLibreta.js"></script>
<!-- Sortable jquerUI -->
<script>
$( function() {
  $( "#sortable" ).sortable({
    placeholder: "ui-state-highlight",
    update: function(event, ui) {
      var ordenPuntos = $(this).sortable('toArray').toString();
      $.ajax({
        type: 'POST',
        url: '/ops/referencia/libreta/reordenarSlider.php',
        dataType: 'json',
        data: {
          accion: 'ordenar',
          puntos: ordenPuntos
        }
      });
    }
  });
  $( "#sortable" ).disableSelection();
});
</script>
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
