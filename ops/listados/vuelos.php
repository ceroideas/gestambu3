<?php
session_start();
include '../../functions/function.php';
include '../../shared/pagination.php';
nonUser();

if(isset($_POST['diaIni'])) {
  $diaIni = $_POST['diaIni'];
} else {

  if(isset($_GET['diaIni'])) {
    $diaIni = $_GET['diaIni'];
  } else {
    $diaIni = date('Y-m-d');
  }
}

if(isset($_POST['diaFin'])) {
  $diaFin = $_POST['diaFin'];
} else {

  if(isset($_GET['diaFin'])) {
    $diaFin = $_GET['diaFin'];
  } else { 
  $hoy = date('Y-m-d');
  $diaFin = strtotime('+1 day', strtotime($hoy));
  $diaFin = date('Y-m-d', $diaFin);
  }
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$total_records = mysqli_fetch_array(mysqli_query($gestambu, "SELECT COUNT(*) FROM vuelosanitario WHERE fecha BETWEEN '$diaIni' AND '$diaFin'"))[0];
$total_pages = getPages($total_records, 10);

$sqlCons = mysqli_query($gestambu, "SELECT vuelosanitario.idVuelo, vuelosanitario.idCia, vuelosanitario.tipo, vuelosanitario.comp, vuelosanitario.hc, vuelosanitario.fecha, vuelosanitario.incub, 
		vuelosanitario.nombre, vuelosanitario.apellidos, vuelosanitario.recoger, vuelosanitario.locRec, vuelosanitario.trasladar, vuelosanitario.locTras, vuelosanitario.obs, vuelosanitario.hpeti, vuelosanitario.numVuelo, vuelosanitario.estVuelo, 
		vueloref.idVuelo, vueloref.estVuelo, vueloref.medico AS medV, vueloref.due AS dueV, vueloref.pediatra AS pedV , vueloref.hSalida, vueloref.hLlegada, vueloref.hVuelta, vueloref.hLlegada2,
		cia.idCia, cia.ciaNom
	FROM vuelosanitario
		LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
		LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
	WHERE vuelosanitario.fecha BETWEEN '$diaIni' AND '$diaFin' AND vueloref.estVuelo !='15'
	ORDER BY vuelosanitario.fecha, vuelosanitario.nombre ASC
  LIMIT ".getOffsetAndLimit($page,10));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Vuelos | GestAmbu 3.0 </title>
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
  <link rel="stylesheet" href="/ops/css/editserv.css">
  <style>
    .box {
      margin-bottom: 5px;
      font-size: 12px;
    }
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
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Listado Vuelos:
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Listado Vuelos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Selección de fechas -->
      <div class="box-body">
        <div class="col-md-12">
          <!-- form start -->
          <form class="form-vertical" id="#searchBetweenForm" action="" method="POST">
            <div class="box-body">
              <div class="form-group col-md-2 col-sm-4 col-xs-12">
                <label>Inicio: </label>
                <input type="date" class="form-control" name="diaIni" value="<?php echo $diaIni; ?>">

              </div>
              <div class="form-group col-md-2 col-sm-4 col-xs-12">
                <label>Final: </label>
                <input type="date" class="form-control" name="diaFin" value="<?php echo $diaFin; ?>">
              </div>
			  <div class="form-group col-md-1 col-sm-1 col-xs-1">
				<label> &nbsp; </label>
				<div class="input-group">
					<i class="fa fa-calculator"></i> Resultados: <strong><?php echo mysqli_num_rows($sqlCons); ?></strong>
				</div>
			  </div>¡
              <div class="form-group col-md-4 col-sm-2 col-xs-12">
                <label> &nbsp; </label>
                <div class="input-group">			
                  <button type="submit" class="btn btn-default">Consultar</button>
				  <a href="#" class="btn btn-info"><i class="fa fa-print"></i> Imprimir</a>
                </div>
              </div>
            </div>
            <!-- /.box-footer -->
          </form>
        </div>
      </div>
      <!-- /. Selección de fechas -->

      <!-- Resultado -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"> 
			<form action="funciones/ficheroexp.php" method="post" target="_blank" id="FormularioExportacion">
				Exportar a Excel  <img src="funciones/l_excel.png" class="botonExcel" />
				<input type="hidden" id="datos_a_enviar" name="datos_a_enviar"  />
			</form>			
		  </h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>

        <!-- contenido -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover" id="Exportar_a_Excel">
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Cia.</th>
              <th>Nombre</th>
              <th>Comp.</th>
			  <th>H.C.</th>
              <th>Tipo</th>
              <th>Inc.</th>
              <th>Med.</th>
              <th>Due</th>
			  <th>Pediatra</th>
              <th>--</th>
              <th>Recoger</th>
              <th>--</th>
              <th>Trasladar</th>
			  <th>Salida</th>
			  <th>Llegada</th>
			  <th>Salida</th>
			  <th>Llegada</th>
              <th>Activación</th>
			  <th>Nº vuelo</th>
			  <th>Obs</th>
            </tr>
			<?php while($rwCons = mysqli_fetch_array($sqlCons)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editVuelo.php?iden=<?php echo $rwCons['idVuelo']; ?>" target="_blank"><i class="fa fa-edit"></i></a></td>
              <td><?php echo fechaFmtCorta($rwCons['fecha']); ?></td>
              <td><strong><small><?php echo $rwCons['ciaNom']; ?></small></strong></td>
              <td><?php echo $rwCons['nombre']." ".$rwCons['apellidos']; ?></td>
              <td><?php echo $rwCons['comp']; ?></td>
			  <td><?php echo $rwCons['hc']; ?></td>
              <td><strong><?php if($rwCons['tipo'] == 1) { echo "CONV."; } elseif($rwCons['tipo'] == 2) { echo "CRITICO"; } elseif($rwCons['tipo'] == 3) { echo "RETORNO"; }else { echo "TRASPLANTE"; } ?></strong></td>
              <td><?php mostTickIco($rwCons['incub']); ?></td>
              <td><?php echo $rwCons['medV']; ?></td>
              <td><?php echo $rwCons['dueV']; ?></td>
			  <td><?php echo $rwCons['pedV']; ?></td>			  
              <td><?php echo $rwCons['recoger']; ?></td>
              <td><strong><?php echo $rwCons['locRec']; ?></strong></td>
              <td><?php echo $rwCons['trasladar']; ?></td>              
              <td><strong><?php echo $rwCons['locTras']; ?></strong></td>
			  <td><?php echo sinHora($rwCons['hSalida']); ?></td>
			  <td><?php echo sinHora($rwCons['hLlegada']); ?></td>
			  <td><?php echo sinHora($rwCons['hVuelta']); ?></td>
			  <td><?php echo sinHora($rwCons['hLlegada2']); ?></td>
			  <td><?php echo sinHora($rwCons['hpeti']); ?></td>
			  <td><?php echo $rwCons['numVuelo']; ?></td>
			  <td><?php echo $rwCons['obs']; ?></td>
            </tr>
			<?php } ?>
          </table>


        <?php displayPaginateComponent($total_records, $total_pages, $page); ?>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. Resultado -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
<script src="/ops/listados/funciones/listgeneral.js"></script>
<!-- Excel -->
<script type='text/javascript' src='funciones/excel_js.js'></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
