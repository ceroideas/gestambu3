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
  $diaFin = $diaIni;
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

include 'funciones/sqlResumen.php';

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
  <title>Resumen actividad | GestAmbu 3.0 </title>
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
        Resumen de actividad:
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Resumen de actividad:</li>
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
              <div class="form-group col-md-2 col-sm-4 col-xs-12">
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
                <label> -- </label>
				<h5>
					<i class="fa fa-print"></i> 
					<a href="/ops/referencia/pdf/lstActividadPdf.php?selProv=<?php echo $selProv;?>&diaIni=<?php echo $diaIni;?>&diaFin=<?php echo $diaFin;?>&ciaSel=<?php echo $aSelc; ?>">
					Imprimir</a>				
				</h5>
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

      <!-- Ambulancias urgentes urbanas -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
			<i class="fa fa-calculator"></i> Ambulancias Urgentes Urbanas : <strong><?php echo mysqli_num_rows($sqlAmbUrg); ?></strong>	
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
              <th>Llamada</th>
              <th>Nombre</th>
              <th>Poliza</th>
			  <th>Auto.</th>
              <th>Recurso</th>
			  <th>Tipo</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Llegada</th>
              <th>Final</th>
            </tr>
			<?php while($rwAmbUrg = mysqli_fetch_array($sqlAmbUrg)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwAmbUrg['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwAmbUrg['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwAmbUrg['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwAmbUrg['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
			  <td><?php echo fechaFmt($rwAmbUrg['fecha']); ?></td>
              <td><?php echo date('H:i', strtotime($rwAmbUrg['hora'])); ?></td>
              <td><?php echo $rwAmbUrg['nombre']." ".$rwAmbUrg['apellidos']; ?></td>
              <td><div class="texto" id="poliza-<?php echo $rwAmbUrg['idSv'];?>"><?php if(empty($rwAmbUrg['poliza'])) { echo "&nbsp;"; } else { if($rwAmbUrg['idCia'] == '3') { echo $rwAmbUrg['poliza']."·"; } else { echo $rwAmbUrg['poliza']; } } ?></div></td>
			  <td><div class="texto" id="autorizacion-<?php echo $rwAmbUrg['idSv'];?>"><?php if(empty($rwAmbUrg['autorizacion'])) { echo "&nbsp;"; } else { echo $rwAmbUrg['autorizacion']; } ?></div></td>
              <td><?php if($rwAmbUrg['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwAmbUrg['recuCorto']; } ?></td>
              <td><?php echo $rwAmbUrg['nomSer']; ?></td>
			  <td><?php mostTick($rwAmbUrg['idvta']); ?></td>
              <td><?php mostTick($rwAmbUrg['medico']); ?></td>
              <td><?php mostTick($rwAmbUrg['enfermero']); ?></td>
              <td><?php mostTick($rwAmbUrg['fest']); ?></td>
              <td><div class="texto" id="locRec-<?php echo $rwAmbUrg['idSv'];?>"><?php echo $rwAmbUrg['locRec']; ?></div></td>
              <td><div class="texto" id="locTras-<?php echo $rwAmbUrg['idSv'];?>"><?php echo $rwAmbUrg['locTras']; ?></div></td>
              <td><div class="hora" id="idReco-<?php echo $rwAmbUrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbUrg['idReco']))); ?></div></td><!-- Cambiar por hora -->
              <td><div class="hora" id="idFin-<?php echo $rwAmbUrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbUrg['idFin']))); ?></div></td>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. fin -->
	  
      <!-- Ambulancias urgentes interurbanas -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
			<i class="fa fa-calculator"></i> Ambulancias Urgentes Interurbanas : <strong><?php echo mysqli_num_rows($sqlAmbNoUrg); ?></strong>	
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
              <th>Llamada</th>
              <th>Nombre</th>
              <th>Poliza</th>
			  <th>Auto.</th>
              <th>Recurso</th>
			  <th>Tipo</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Llegada</th>
              <th>Final</th>
            </tr>
			<?php while($rwAmbNoUrg = mysqli_fetch_array($sqlAmbNoUrg)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwAmbNoUrg['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwAmbNoUrg['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwAmbNoUrg['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwAmbNoUrg['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
			  <td><?php echo fechaFmt($rwAmbNoUrg['fecha']); ?></td>
              <td><?php echo date('H:i', strtotime($rwAmbNoUrg['hora'])); ?></td>
              <td><?php echo $rwAmbNoUrg['nombre']." ".$rwAmbNoUrg['apellidos']; ?></td>
              <td><div class="texto" id="poliza-<?php echo $rwAmbNoUrg['idSv'];?>"><?php if(empty($rwAmbNoUrg['poliza'])) { echo "&nbsp;"; } else { if($rwAmbNoUrg['idCia'] == '3') { echo $rwAmbNoUrg['poliza']."·"; } else { echo $rwAmbNoUrg['poliza']; } } ?></div></td>
			  <td><div class="texto" id="autorizacion-<?php echo $rwAmbNoUrg['idSv'];?>"><?php if(empty($rwAmbNoUrg['autorizacion'])) { echo "&nbsp;"; } else { echo $rwAmbNoUrg['autorizacion']; } ?></div></td>
              <td><?php if($rwAmbNoUrg['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwAmbNoUrg['recuCorto']; } ?></td>
              <td><?php echo $rwAmbNoUrg['nomSer']; ?></td>
			  <td><?php mostTick($rwAmbNoUrg['idvta']); ?></td>
              <td><?php mostTick($rwAmbNoUrg['medico']); ?></td>
              <td><?php mostTick($rwAmbNoUrg['enfermero']); ?></td>
              <td><?php mostTick($rwAmbNoUrg['fest']); ?></td>
              <td><div class="texto" id="locRec-<?php echo $rwAmbNoUrg['idSv'];?>"><?php echo $rwAmbNoUrg['locRec']; ?></div></td>
              <td><div class="texto" id="locTras-<?php echo $rwAmbNoUrg['idSv'];?>"><?php echo $rwAmbNoUrg['locTras']; ?></div></td>
              <td><div class="hora" id="idReco-<?php echo $rwAmbNoUrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbNoUrg['idReco']))); ?></div></td>
              <td><div class="hora" id="idFin-<?php echo $rwAmbNoUrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbNoUrg['idFin']))); ?></div></td>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. fin -->	  

      <!-- Ambulancias programadas urbanas -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
			<i class="fa fa-calculator"></i> Ambulancias Programamdas Urbanas : <strong><?php echo mysqli_num_rows($sqlAmbPrg); ?></strong>	
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
              <th>Llamada</th>
              <th>Nombre</th>
              <th>Poliza</th>
			  <th>Auto.</th>
              <th>Recurso</th>
			  <th>Tipo</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Llegada</th>
              <th>Final</th>
            </tr>
			<?php while($rwAmbPrg = mysqli_fetch_array($sqlAmbPrg)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwAmbPrg['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwAmbPrg['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwAmbPrg['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwAmbPrg['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
			  <td><?php echo fechaFmt($rwAmbPrg['fecha']); ?></td>
              <td><?php echo date('H:i', strtotime($rwAmbPrg['hora'])); ?></td>
              <td><?php echo $rwAmbPrg['nombre']." ".$rwAmbPrg['apellidos']; ?></td>
              <td><div class="texto" id="poliza-<?php echo $rwAmbPrg['idSv'];?>"><?php if(empty($rwAmbPrg['poliza'])) { echo "&nbsp;"; } else { if($rwAmbPrg['idCia'] == '3') { echo $rwAmbPrg['poliza']."·"; } else { echo $rwAmbPrg['poliza']; } } ?></div></td>
			  <td><div class="texto" id="autorizacion-<?php echo $rwAmbPrg['idSv'];?>"><?php if(empty($rwAmbPrg['autorizacion'])) { echo "&nbsp;"; } else { echo $rwAmbPrg['autorizacion']; } ?></div></td>
              <td><?php if($rwAmbPrg['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwAmbPrg['recuCorto']; } ?></td>
              <td><?php echo $rwAmbPrg['nomSer']; ?></td>
			  <td><?php mostTick($rwAmbPrg['idvta']); ?></td>
              <td><?php mostTick($rwAmbPrg['medico']); ?></td>
              <td><?php mostTick($rwAmbPrg['enfermero']); ?></td>
              <td><?php mostTick($rwAmbPrg['fest']); ?></td>
              <td><div class="texto" id="locRec-<?php echo $rwAmbPrg['idSv'];?>"><?php echo $rwAmbPrg['locRec']; ?></div></td>
              <td><div class="texto" id="locTras-<?php echo $rwAmbPrg['idSv'];?>"><?php echo $rwAmbPrg['locTras']; ?></div></td>
              <td><div class="hora" id="idReco-<?php echo $rwAmbPrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrg['idReco']))); ?></div></td>
			  <?php if($rwAmbPrg['idvta'] == '1' || $rwAmbPrg['idvta'] == '3' ){ ?>
              <td><div class="hora" id="vtaFin-<?php echo $rwAmbPrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrg['vtaFin']))); ?></div></td>
			  <?php } elseif($rwAmbPrg['idvta'] == '2') {?>
              <td><div class="hora" id="idFin-<?php echo $rwAmbPrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrg['idFin']))); ?></div></td>
			  <?php } else { ?>
			  <td><div class="hora" id="idFin-<?php echo $rwAmbPrg['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrg['idFin']))); ?></div></td>
			  <?php } ?>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. fin -->

      <!-- Ambulancias programadas interurbanas -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
			<i class="fa fa-calculator"></i> Ambulancias Programamdas Interurbanas : <strong><?php echo mysqli_num_rows($sqlAmbPrgNUrb); ?></strong>	
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
              <th>Llamada</th>
              <th>Nombre</th>
              <th>Poliza</th>
			  <th>Auto.</th>
              <th>Recurso</th>
			  <th>Tipo</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Llegada</th>
              <th>Final</th>
            </tr>
			<?php while($rwAmbPrgNUrb = mysqli_fetch_array($sqlAmbPrgNUrb)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwAmbPrgNUrb['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwAmbPrgNUrb['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwAmbPrgNUrb['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwAmbPrgNUrb['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
			  <td><?php echo fechaFmt($rwAmbPrgNUrb['fecha']); ?></td>
              <td><?php echo date('H:i', strtotime($rwAmbPrgNUrb['hora'])); ?></td>
              <td><?php echo $rwAmbPrgNUrb['nombre']." ".$rwAmbPrgNUrb['apellidos']; ?></td>
              <td><div class="texto" id="poliza-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php if(empty($rwAmbPrgNUrb['poliza'])) { echo "&nbsp;"; } else { if($rwAmbPrgNUrb['idCia'] == '3') { echo $rwAmbPrgNUrb['poliza']."·"; } else { echo $rwAmbPrgNUrb['poliza']; } } ?></div></td>
			  <td><div class="texto" id="autorizacion-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php if(empty($rwAmbPrgNUrb['autorizacion'])) { echo "&nbsp;"; } else { echo $rwAmbPrgNUrb['autorizacion']; } ?></div></td>
              <td><?php if($rwAmbPrgNUrb['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwAmbPrgNUrb['recuCorto']; } ?></td>
              <td><?php echo $rwAmbPrgNUrb['nomSer']; ?></td>
			  <td><?php mostTick($rwAmbPrgNUrb['idvta']); ?></td>
              <td><?php mostTick($rwAmbPrgNUrb['medico']); ?></td>
              <td><?php mostTick($rwAmbPrgNUrb['enfermero']); ?></td>
              <td><?php mostTick($rwAmbPrgNUrb['fest']); ?></td>
              <td><div class="texto" id="locRec-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php echo $rwAmbPrgNUrb['locRec']; ?></div></td>
              <td><div class="texto" id="locTras-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php echo $rwAmbPrgNUrb['locTras']; ?></div></td>
              <td><div class="hora" id="idReco-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrgNUrb['idReco']))); ?></div></td>
			  <?php if($rwAmbPrgNUrb['idvta'] == '1' || $rwAmbPrgNUrb['idvta'] == '3' ){ ?>
              <td><div class="hora" id="vtaFin-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrgNUrb['vtaFin']))); ?></div></td>
			  <?php } elseif($rwAmbPrgNUrb['idvta'] == '2') {?>
              <td><div class="hora" id="idFin-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrgNUrb['idFin']))); ?></div></td>
			  <?php } else { ?>
			  <td><div class="hora" id="idFin-<?php echo $rwAmbPrgNUrb['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwAmbPrgNUrb['idFin']))); ?></div></td>
			  <?php } ?>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. fin -->

      <!-- Visitas médicas urgentes urbanas -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
			<i class="fa fa-calculator"></i> Visitas médicas urgentes urbanas : <strong><?php echo mysqli_num_rows($sqlVmedica); ?></strong>	
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
              <th>Llamada</th>
              <th>Nombre</th>
              <th>Poliza</th>
			  <th>Auto.</th>
              <th>Recurso</th>
			  <th>Tipo</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Llegada</th>
              <th>Final</th>
            </tr>
			<?php while($rwVmedica = mysqli_fetch_array($sqlVmedica)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwVmedica['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwVmedica['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwVmedica['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwVmedica['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
			  <td><?php echo fechaFmt($rwVmedica['fecha']); ?></td>
              <td><?php echo date('H:i', strtotime($rwVmedica['hora'])); ?></td>
              <td><?php echo $rwVmedica['nombre']." ".$rwVmedica['apellidos']; ?></td>
              <td><div class="texto" id="poliza-<?php echo $rwVmedica['idSv'];?>"><?php if(empty($rwVmedica['poliza'])) { echo "&nbsp;"; } else { if($rwVmedica['idCia'] == '3') { echo $rwVmedica['poliza']."·"; } else { echo $rwVmedica['poliza']; } } ?></div></td>
			  <td><div class="texto" id="autorizacion-<?php echo $rwVmedica['idSv'];?>"><?php if(empty($rwVmedica['autorizacion'])) { echo "&nbsp;"; } else { echo $rwVmedica['autorizacion']; } ?></div></td>
              <td><?php if($rwVmedica['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwVmedica['recuCorto']; } ?></td>
              <td><?php echo $rwVmedica['nomSer']; ?></td>
			  <td><?php mostTick($rwVmedica['idvta']); ?></td>
              <td><?php mostTick($rwVmedica['medico']); ?></td>
              <td><?php mostTick($rwVmedica['enfermero']); ?></td>
              <td><?php mostTick($rwVmedica['fest']); ?></td>
              <td><div class="texto" id="locRec-<?php echo $rwVmedica['idSv'];?>"><?php echo $rwVmedica['locRec']; ?></div></td>
              <td></td>
              <td><div class="hora" id="idReco-<?php echo $rwVmedica['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwVmedica['idReco']))); ?></div></td><!-- Cambiar por hora -->
              <td><div class="hora" id="idFin-<?php echo $rwVmedica['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwVmedica['idFin']))); ?></div></td>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. fin --> 

      <!-- Visitas médicas urgentes interurbanas -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
			<i class="fa fa-calculator"></i> Visitas médicas urgentes interurbanas : <strong><?php echo mysqli_num_rows($sqlVmedica); ?></strong>	
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
              <th>Llamada</th>
              <th>Nombre</th>
              <th>Poliza</th>
			  <th>Auto.</th>
              <th>Recurso</th>
			  <th>Tipo</th>
              <th>Id/vta</th>
              <th>Med.</th>
              <th>Due</th>
              <th>Fest/noct.</th>
              <th>Recoger</th>
              <th>Trasladar</th>
              <th>Llegada</th>
              <th>Final</th>
            </tr>
			<?php while($rwVMNurb = mysqli_fetch_array($sqlVMNourb)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwVMNurb['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php if($rwVMNurb['estServ'] == '17') { echo "NO LISTAR"; }?> <?php compPaCro($rwVMNurb['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwVMNurb['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
			  <td><?php echo fechaFmt($rwVMNurb['fecha']); ?></td>
              <td><?php echo date('H:i', strtotime($rwVMNurb['hora'])); ?></td>
              <td><?php echo $rwVMNurb['nombre']." ".$rwVMNurb['apellidos']; ?></td>
              <td><div class="texto" id="poliza-<?php echo $rwVMNurb['idSv'];?>"><?php if(empty($rwVMNurb['poliza'])) { echo "&nbsp;"; } else { if($rwVMNurb['idCia'] == '3') { echo $rwVMNurb['poliza']."·"; } else { echo $rwVMNurb['poliza']; } } ?></div></td>
			  <td><div class="texto" id="autorizacion-<?php echo $rwVMNurb['idSv'];?>"><?php if(empty($rwVMNurb['autorizacion'])) { echo "&nbsp;"; } else { echo $rwVMNurb['autorizacion']; } ?></div></td>
              <td><?php if($rwVMNurb['tipo'] == '9') { echo "SEG_MED"; } else { echo $rwVMNurb['recuCorto']; } ?></td>
              <td><?php echo $rwVMNurb['nomSer']; ?></td>
			  <td><?php mostTick($rwVMNurb['idvta']); ?></td>
              <td><?php mostTick($rwVMNurb['medico']); ?></td>
              <td><?php mostTick($rwVMNurb['enfermero']); ?></td>
              <td><?php mostTick($rwVMNurb['fest']); ?></td>
              <td><div class="texto" id="locRec-<?php echo $rwVMNurb['idSv'];?>"><?php echo $rwVMNurb['locRec']; ?></div></td>
              <td></td>
              <td><div class="hora" id="idReco-<?php echo $rwVMNurb['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwVMNurb['idReco']))); ?></div></td><!-- Cambiar por hora -->
              <td><div class="hora" id="idFin-<?php echo $rwVMNurb['idSv'];?>"><?php echo sinHoraSeg(date('H:i', strtotime($rwVMNurb['idFin']))); ?></div></td>
            </tr>
			<?php } ?>
          </table>
        </div>
        <!-- /. contenido -->

      </div>
      <!-- /. fin --> 	  
	  
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
</body>
</html>
<?php
mysqli_close($gestambu);
?>
