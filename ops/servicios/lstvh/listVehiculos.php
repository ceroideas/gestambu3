<?php
session_start();
include '../../../functions/function.php';
nonUser();

/* Usuarios activos y ambulancia */
$userAct = mysqli_query($gestambu, "SELECT reguardia.idGuardia, reguardia.idUser, reguardia.turno, reguardia.cate, reguardia.gHoraIni, DATE_FORMAT(reguardia.gFechaIni, '%d-%m-%Y') AS fechaIni, reguardia.gEst,
	  user.userId, user.usNom, user.usApe, user.usCate,
	  regambu.idGuardia, regambu.ambu, regambu.estAmbu, regambu.kmIni,
	  vehiculo.idVh, vehiculo.matricula
	FROM reguardia
	  LEFT JOIN user ON reguardia.idUser = user.userId
	  LEFT JOIN regambu ON regambu.idGuardia = reguardia.idGuardia
	  LEFT JOIN vehiculo ON regambu.ambu = vehiculo.idVh	  
	WHERE reguardia.gEst = '1' AND regambu.estAmbu = '1'
	ORDER BY reguardia.gFechaIni ASC
	");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Listado de vehículos activos | GestAmbu 3.0</title>
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
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Listados</li>
        <li class="active">Vehículos activos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <!-- Listado sortable -->
      <div class="box box-primary">
        <div class="box-header">
          <i class="ion ion-clipboard"></i>

          <h3 class="box-title">Listado de vehículos</h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="box-body table-condensed no-padding">
            <table class="table table-hover aumText">
                <tr>
                  <th>#</th>
				  <th>Nombre</th>
                  <th>Ambulancia</th>
                  <th>Km Inicio</th>
                  <th>Día inicio</th>
                  <th>Hora inicio</th>
                  <th>Acciones</th>
                </tr>
              <tbody class="todo-list" id="" >
                <?php while($rwList = mysqli_fetch_array($userAct)) {?>
				<tr>
				  <td><?php echo $rwList['idGuardia']; ?></td>
				  <td><?php echo $rwList['usNom']." ".$rwList['usApe']; ?></td>
                  <td><?php echo $rwList['matricula']; ?></td>
                  <td><?php echo $rwList['kmIni']; ?></td>
                  <td><?php echo $rwList['fechaIni']; ?></td>
                  <td><?php echo $rwList['gHoraIni']; ?></td>
                  <td>
					<a href="finguardia.php?tec=<?php echo $rwList['userId']; ?>&ambu=<?php echo $rwList['ambu']; ?>&guardia=<?php echo $rwList['idGuardia']; ?>&turno=<?php echo $rwList['turno']; ?>&cate=<?php echo $rwList['usCate']; ?>" type="button" class="btn btn-primary btn-sm">
					<i class="fa fa-unlock"></i> Cerrar guardia</a>
				  </td>
                </tr>
				<?php } ?>
              </tbody>
            </table>
          </div>
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

<?php //include '../../inc/pie.php'; ?>

<?php //include '../../inc/bcontrol.php'; ?>

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
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
