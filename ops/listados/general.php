<?php
session_start();
include '../../functions/function.php';
nonUser();

if(isset($_POST['diaIni'])) {
  $diaIni = $_POST['diaIni'];
} else {
  $diaIni = date('Y-m-d');
}

if(isset($_POST['diaFin'])) {
  $diaFin = $_POST['diaFin'];
} else {
  $hoy = date('Y-m-d');
  $diaFin = strtotime('+1 day', strtotime($hoy));
  $diaFin = date('Y-m-d', $diaFin);
}
if(isset($_POST['ciaLst'])) {
  $aSelc = $_POST['ciaLst'];
  $nomCida = mostrarCia($aSelc);
} else {
  $aSelc = '0';
  $nomCida = "No seleccionado";
}
if(isset($_POST['provincia'])) {
  $selProv = $_POST['provincia'];
} else {
  $selProv = '0';
}

if(isset($_POST['recurso'])) {
  $selRecu = $_POST['recurso'];
  if($selRecu == '1') { //Ambu
	  $codRecu = "'1','3','5'";	  
  } elseif($selRecu == '2') { //Ruta
	  $codRecu = "'7'";
  } elseif($selRecu == '3') { //Due
	  $codRecu = "'2'";	  
  } elseif($selRecu == '4') { //V_M
	  $codRecu = "'4'";	  
  } elseif($selRecu == '5') { //C_TLF
	  $codRecu = "'6'";	  
  } elseif($selRecu == '6') { //SEG_MED
	  $codRecu = "'4'";	  //Queda por especificar como realizarlo 
  } else {
	  $codRecu = "'0'";
  // No se especifica recurso para eventos
  }	  
} else {
  $selRecu = '0';
  $codRecu = "'0'";	  
}
if(isset($_POST['delFac'])) {
  $selDele = $_POST['delFac'];
} else {
  $selDele = '0';
}
if(isset($_POST['mostCont'])) { //0 no mostrar cont. - 1 mostrar cont
  $seleCont = $_POST['mostCont'];
} else {
  $seleCont = '0';
}
if(isset($_POST['asisaSe'])) { //Solamente Asisa Sevilla
	$asisaSe = $_POST['asisaSe'];
} else {
	$asisaSe = 0;
}

include 'funciones/sqListado.php';

# Aseguradora
$ciaLst = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom ASC
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Listado General| GestAmbu 3.0 </title>
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
        Listado General:
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Listado General</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Selección de fechas -->
      <div class="box-body">
        <div class="col-md-12">
          <!-- form start -->
          <form class="form-vertical" action="" method="POST">
            <div class="box-body">
              <div class="form-group col-md-2 col-sm-4 col-xs-12">
                <label>Inicio: </label>
                <input type="date" class="form-control" name="diaIni" value="<?php echo $diaIni; ?>">
              </div>
              <div class="form-group col-md-2 col-sm-4 col-xs-12">
                <label>Final: </label>
                <input type="date" class="form-control" name="diaFin" value="<?php echo $diaFin; ?>">
              </div>
              <div class="form-group col-md-2 col-sm-4 col-xs-12">
                <label>Aseguradora: </label>
                <div class="input-group">
                  <select class="form-control" name="ciaLst" id="recurso" required>
                    <option value="">-- Aseguradora --</option>
					<option value="-1">-- Todas --</option>
					<option value="-2" <?php if(@$_POST['ciaLst'] == '-2') { echo "selected"; }?>>-- Facturables --</option>
					<option value="-3" <?php if(@$_POST['ciaLst'] == '-3') { echo "selected"; }?>>-- Eventos --</option>
					<option value="-4" <?php if(@$_POST['ciaLst'] == '-4') { echo "selected"; }?>>-- Asisa Ma (nofact) --</option>
                    <?php
                      while($rwCia = mysqli_fetch_assoc($ciaLst)) {
                        if(@$_POST['ciaLst'] == $rwCia['idCia']) {
                          $seleccion = "selected";
                        } else {
                          $seleccion = "";
                        }
                        echo "<option value='".$rwCia['idCia']."' ".$seleccion.">".$rwCia['ciaNom']."</option>\n";
                      }
                     ?>
                  </select>
                </div>
              </div>			  
              <div class="form-group col-md-1 col-sm-2 col-xs-12">
                <label>Provincia: </label>
                  <select class="form-control" name="provincia" required>
                    <option value="">-- Selecciona Provincia --</option>
					<option value="11" <?php if(@$_POST['provincia'] == 11) { echo "selected"; } ?>>Cádiz</option>
					<option value="14" <?php if(@$_POST['provincia'] == 14) { echo "selected"; } ?>>Córdoba</option>
                    <option value="29" <?php if(@$_POST['provincia'] == 29) { echo "selected"; } ?>>Málaga</option>
					<option value="52" <?php if(@$_POST['provincia'] == 52) { echo "selected"; } ?>>Melilla</option>
					<option value="21" <?php if(@$_POST['provincia'] == 21) { echo "selected"; } ?>>Huelva</option>					
                    <option value="41" <?php if(@$_POST['provincia'] == 41) { echo "selected"; } ?>>Sevilla</option>                   
                  </select>
              </div>
              <div class="form-group col-md-1 col-sm-4 col-xs-12">
                <label>Recurso: </label>
				  <select class="form-control" name="recurso" id="recurso" required>
					<option value="">--  Recurso --</option>
					<option value="1" <?php if($selRecu == '1') { echo "selected"; } ?>>AMBULANCIA</option>
					<option value="2" <?php if($selRecu == '2') { echo "selected"; } ?>>RUTA</option>
					<option value="3" <?php if($selRecu == '3') { echo "selected"; } ?>>DUE</option>
					<option value="4" <?php if($selRecu == '4') { echo "selected"; } ?>>V_M</option>
					<option value="5" <?php if($selRecu == '5') { echo "selected"; } ?>>C_TLF.</option>
					<option value="6" <?php if($selRecu == '6') { echo "selected"; } ?>>SEG_MED.</option>
				  </select>
              </div>
              <div class="form-group col-md-1 col-sm-2 col-xs-12">
                <label>Delegación: </label>
				  <select class="form-control" name="delFac" title="Sólo para selección AMBULANCIA">
					<option value="0" <?php if($selDele == '0') { echo "selected"; } ?>>-- Actual --</option>
					<option value="1" <?php if($selDele == '1') { echo "selected"; } ?>>-- Facturable --</option>
				  </select>
              </div>
              <div class="form-group col-md-1 col-sm-2 col-xs-12">
                <label>Continuados: </label>
				  <select class="form-control" name="mostCont" title="Sólo para selección AMBULANCIA">
					<option value="0" <?php if($seleCont == '0') { echo "selected"; } ?>>-- No --</option>
					<option value="1" <?php if($seleCont == '1') { echo "selected"; } ?>>-- Si --</option>
				  </select>
              </div>
             <div class="form-group col-md-1 col-sm-2 col-xs-12">
                <label>Asisa Se: </label>
				  <select class="form-control" name="asisaSe" title="Sólamente para Asisa Sevilla">
					<option value="0" <?php if($asisaSe == '0') { echo "selected"; } ?>>-- No sel. --</option>
					<option value="1" <?php if($asisaSe == '1') { echo "selected"; } ?>> Asisa Rad</option>
					<option value="2" <?php if($asisaSe == '2') { echo "selected"; } ?>> Asisa Sevilla</option>
				  </select>
              </div>			  
              <div class="form-group col-md-1 col-sm-2 col-xs-12">
                <label> &nbsp; </label>
                <div class="input-group">
                  <button type="submit" class="btn btn-default">Consultar</button>
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
			<i class="fa fa-print"></i>
			<a href="/ops/referencia/pdf/lstGeneralPdf.php?provincia=<?php echo $selProv;?>&diaIni=<?php echo $diaIni;?>&diaFin=<?php echo $diaFin;?>&selRecu=<?php echo $selRecu;?>&aseguradora=<?php echo $aSelc; ?>&delFac=<?php echo $selDele; ?>&asgNom=<?php echo $nomCida; ?>&raDele=<?php echo $asisaSe; ?>">
			Imprimir</a> - 
			<i class="fa fa-calculator"></i> Resultados: <strong><?php echo mysqli_num_rows($sqlCons); ?></strong> - 
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
            <thead>
              
            <tr>
              <th>#</th>
      			  <th>Del.</th>
      			  <th>Factura</th>
              <th>Fecha</th>
              <th>Cia.</th>
              <th>Nombre</th>
              <th>Poliza</th>
			        <th>Auto.</th>
              <th>Recurso</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Km</th>
              <th>Tipo</th>
            </tr>
            </thead>
            <tbody id="serverSideTable" style="display: none;">
              
			      <?php while($rwCons = mysqli_fetch_array($sqlCons)) { ?>
              <tr>
                <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwCons['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwCons['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwCons['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwCons['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a> <?php echo compImpreso($rwCons['idSv']); ?></td>
                <td><?php if($rwCons['delegacion'] !='0') { echo $rwCons['delegacion']; } ?></td>
  			  <td><div class="factura" id="numFac-<?php echo $rwCons['idFac']; ?>-<?php echo $rwCons['idSv']; ?>"><?php echo $rwCons['numFac']; ?></div></td>
  			  <td><?php echo fechaFmt($rwCons['fecha']); ?></td>
                <td><?php echo $rwCons['ciaNom']; ?></td>
                <td><?php echo $rwCons['nombre']." ".$rwCons['apellidos']; ?></td>
                <td><div class="texto" id="poliza-<?php echo $rwCons['idSv'];?>"><?php if(empty($rwCons['poliza'])) { echo "&nbsp;"; } else { if($rwCons['idCia'] == '3') { echo $rwCons['poliza']."·"; } else { echo $rwCons['poliza']; } } ?></div></td>
  			  <td><div class="texto" id="autorizacion-<?php echo $rwCons['idSv'];?>"><?php if(empty($rwCons['autorizacion'])) { echo "&nbsp;"; } else { echo $rwCons['autorizacion']; } ?></div></td>
                <td><?php if($rwCons['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwCons['recuCorto']; } ?></td>
                <td><?php mostTick($rwCons['idvta']); ?></td>
                <td><?php mostTick($rwCons['medico']); ?></td>
                <td><?php mostTick($rwCons['enfermero']); ?></td>
                <td><?php mostTick($rwCons['fest']); ?></td>
                <td><div class="texto" id="locRec-<?php echo $rwCons['idSv'];?>"><?php echo $rwCons['locRec']; ?></div></td>
                <td><div class="texto" id="locTras-<?php echo $rwCons['idSv'];?>"><?php echo $rwCons['locTras']; ?></div></td>
                <td><div class="texto" id="km-<?php echo $rwCons['idSv'];?>"><?php echo $rwCons['km']; ?></div></td>
                <td><?php echo $rwCons['nomSer']; ?></td>
              </tr>
			      <?php } ?>
            </tbody>
          </table>
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

<style>
  .dataTables_filter {
    float: right;
  }
</style>

<script>
  $(function() {

    import('/docs/plugins/datatables/jquery.dataTables.min.js').then(_=>{

      import('/docs/plugins/datatables/dataTables.bootstrap.min.js').then(__=>{

        let table = '#Exportar_a_Excel';

        $(table).DataTable({
          //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
          "pageLength": 10,
          "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
          },
          "fnInitComplete": function(){
            $('#serverSideTable').show();
          }
        });
      /**/
      });
    });

  });
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
