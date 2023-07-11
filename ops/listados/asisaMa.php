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
if(isset($_POST['provincia'])) {
  $selProv = $_POST['provincia'];
} else {
  $selProv = '0';
}
if(isset($_POST['seleccion'])) {
  $seleccion=$_POST['seleccion'];
} else {
  $seleccion='0';
}

if($seleccion == '0') {
	$codRecu = "='0'";
	$deleg   = "='0'";
	$tipo    = "='0'"; 
} elseif($seleccion == '1') { //Ambu facturación
	$codRecu = "IN('1','3','7')";
	$deleg   = "= '53'";
	$tipo    = "IN('1','3','4','6','7','8','10','11','12','14','23','24')"; 
} elseif($seleccion == '2') { //Ambu conv
	$codRecu = "IN('1','3','7')";
	$deleg   = "!='53'";
	$tipo    = "IN('1','3','4','6','7','8','10','11','12','14','23','24')"; 
} elseif($seleccion == '3') { //Médico
	$codRecu = "='4'";
	$deleg   = ">'-1'";
	$tipo    = "='2'"; 
} elseif($seleccion == '4') { //Enfermero	
	$codRecu = "='2'";
	$deleg   = "> '-1'";
	$tipo    = "IN('4','20','21','22')"; 
} elseif($seleccion == '5') { //Seg_médico
	$codRecu = "='4'";
	$deleg   = ">'-1'";
	$tipo    = "='9'"; 
} else {
	$codRecu = "='0'";
	$deleg   = "='0'";
	$tipo    = "='0'"; 
}

$sqlCons = mysqli_query($gestambu, "
	SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono,
		especial.idSv, especial.prescriptor,
		serpersonal.idSv, serpersonal.dueIda, serpersonal.medIda
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN especial ON servicio.idSv = especial.idSv
		LEFT JOIN serpersonal ON servicio.idSv = serpersonal.idSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '1' AND servicio.recurso ".$codRecu." AND servicio.tipo ".$tipo."
			AND servicio.provincia = '$selProv' AND servicio.delegacion ".$deleg."
	ORDER BY servicio.fecha, servicio.nombre ASC 
	");
/*
echo "
	SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono,
		especial.idSv, especial.prescriptor
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN especial ON servicio.idSv = especial.idSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '1' AND servicio.recurso ".$codRecu." AND servicio.tipo ".$tipo."
			AND servicio.provincia = '$selProv' AND servicio.delegacion ".$deleg."
	ORDER BY servicio.fecha, servicio.nombre ASC 
";
*/
function compPaCro($idPaciente) {
  global $gestambu;
  $mostSeg = mysqli_query($gestambu, "SELECT idPac, segMed FROM paciente WHERE idPac = '$idPaciente' AND segMed = '1'");
  $numSeg = mysqli_num_rows($mostSeg);
  
  if($numSeg == '1') {
	  echo "<i class=\"fa fa-medkit\"></i>";
  }
}
function textoDesc($desRecu, $reco, $locRec, $trasla, $locTras) {
	if($desRecu == '1') {
		echo "Traslado urgente desde: ".$locRec." a ".$trasla." en ".$locTras;
	} elseif($desRecu == '2') {
		echo "Visita médica en: ".$locRec;
	} elseif($desRecu == '3') {
		echo "Traslado programado desde: ".$locRec." a ".$trasla." en ".$locTras." y vuelta.";				
	} elseif($desRecu == '4') {
		echo "Inyectable a domicilio en ".$locRec;
	} elseif($desRecu == '5') {
		echo "Servicio de diálisis desde: ".$locRec." a ".$locTras." y vuelta.";		
	} elseif($desRecu == '6') {
		echo "Servicio de radioterapia desde: ".$locRec." a ".$locTras." y vuelta.";		
	} elseif($desRecu == '7') {
		echo "Servicio continuado desde: ".$locRec." a ".$locTras." y vuelta.";		
	} elseif($desRecu == '8') {
		echo "Servicio de logopeda desde: ".$locRec." a ".$locTras." y vuelta.";		
	} elseif($desRecu == '9') {
		echo "Seguimiento médico en: ".$locRec;	
	} elseif($desRecu == '10') {
		echo "Traslado interhospitalario desde : ".$reco." en ".$locRec." a ".$trasla." en ".$locTras;		
	} elseif($desRecu == '11') {
		echo "Traslado desde: ".$reco." en ".$locRec." a domicilio en ".$locTras;		
	} elseif($desRecu == '12') {
		echo "Servicio de Aeropuerto: ".$reco." en ".$locRec." a a ".$trasla." en ".$locTras;
	} elseif($desRecu == '13') {
		echo "Traslado : ".$locRec." a ".$locTras;
	} elseif($desRecu == '14') {
		echo "Ingreso programado desde: ".$locRec." a ".$trasla." en ".$locTras;		
	} elseif($desRecu == '15') {
		echo "Festejos ".$locRec;
	} elseif($desRecu == '16') {
		echo "Escolta ".$locRec;		
	} elseif($desRecu == '17') {
		echo "Consulta Telefónica";		
	} elseif($desRecu == '18') {
		echo "Eventos ".$locRec;;			
	} elseif($desRecu == '19') {
		echo "Fit to Fly en ".$locRec;
	} elseif($desRecu == '20') {
		echo "Cura a domicilio en ".$locRec;		
	} elseif($desRecu == '21') {
		echo "Enfermería a domicilio en ".$locRec;		
	} elseif($desRecu == '22') {
		echo "Extracción de sangre en ".$locRec;		
	} elseif($desRecu == '23') {
		echo "Preventivo en ".$locRec;		
	} elseif($desRecu == '24') {
		echo "Traslado para hiperbarica desde : ".$locRec." a ".$trasla." en ".$locTras;		
	} else {
		echo "Servicio NO definido";
	}
}
function cambioFecha($fechaDada) {
	$fechaNueva = explode("-", $fechaDada);
	$anio = $fechaNueva[0];
	$mes = $fechaNueva[1];
	$dia = $fechaNueva[2];
	
	$fechaResul = $dia."-".$mes."-".$anio;
	return $fechaResul;
}
function presciptor($idSel, $nombreDue, $nombreMed) {
	if($idSel == '3' || $idSel == '5') {//Visita, Seguimiento
		if($nombreMed == '0') {
			echo "";
		} else {
			echo $nombreMed;		
		}
	} elseif($idSel == '4') {//Enfermería
		if($nombreDue == '0') {
			echo "";
		} else {
			echo $nombreDue;
		}
	} else { //Traslados
		echo "";
	}

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Asisa Málaga | GestAmbu 3.0 </title>
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
        <li class="active">Listado Asisa</li>
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
              <div class="form-group col-md-2">
                <label>Inicio: </label>
                <input type="date" class="form-control" name="diaIni" value="<?php echo $diaIni; ?>">
              </div>
              <div class="form-group col-md-2">
                <label>Final: </label>
                <input type="date" class="form-control" name="diaFin" value="<?php echo $diaFin; ?>">
              </div>
              <div class="form-group col-md-2">
                <label>Selección: </label>
				  <select class="form-control" name="seleccion" id="seleccion" required>
					<option value="">-- Selección --</option>
					<option value="1" <?php if(@$_POST['seleccion'] == 1) { echo "selected"; } ?>> Ambu-Factu </option>
					<option value="2" <?php if(@$_POST['seleccion'] == 2) { echo "selected"; } ?>> Ambu-Conv</option>
					<option value="3" <?php if(@$_POST['seleccion'] == 3) { echo "selected"; } ?>> Médico</option>
					<option value="4" <?php if(@$_POST['seleccion'] == 4) { echo "selected"; } ?>> Enfermero</option>
					<option value="5" <?php if(@$_POST['seleccion'] == 5) { echo "selected"; } ?>> Seg_Médico</option>
				  </select>
              </div>			  
              <div class="form-group col-md-2">
                <label>Provincia: </label>
                  <select class="form-control" name="provincia" required>
                    <option value="">-- Selecciona Provincia --</option>
					<option value="11" <?php if(@$_POST['provincia'] == 11) { echo "selected"; } ?>>Cádiz</option>
					<option value="14" <?php if(@$_POST['provincia'] == 14) { echo "selected"; } ?>>Córdoba</option>
                    <option value="29" <?php if(@$_POST['provincia'] == 29) { echo "selected"; } ?>>Málaga</option>
					<option value="52" <?php if(@$_POST['provincia'] == 29) { echo "selected"; } ?>>Melilla</option>
					<option value="21" <?php if(@$_POST['provincia'] == 21) { echo "selected"; } ?>>Huelva</option> 
                    <option value="41" <?php if(@$_POST['provincia'] == 41) { echo "selected"; } ?>>Sevilla</option>                    
                  </select>
              </div>
              <div class="form-group col-md-1">
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
			<a href="/ops/referencia/pdf/lstGeneralPdf.php?provincia=<?php echo $selProv;?>&diaIni=<?php echo $diaIni;?>&diaFin=<?php echo $diaFin;?>&selRecu=<?php echo $selRecu;?>&aseguradora=<?php echo $aSelc; ?>&delFac=<?php echo $selDele; ?>&asgNom=<?php echo $nomCida; ?>">
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
            <tr>
			  <th>#</th>
			  <th>Fecha</th>
              <th>Asegurado</th>
			  <th>Poliza</th>
              <th>Auto.</th>
              <th>Prescriptor</th>
              <th>Descripción</th>
              <th>Salida</th>
			  <th>H.Espera</th>
              <th>Km</th>
              <th>Tipo Amb.</th>
            </tr>
			<?php while($rwCons = mysqli_fetch_array($sqlCons)) { ?>
            <tr>
              <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwCons['idSv']; ?>" target="_blank"><i class="fa fa-edit"></i></a> <?php compPaCro($rwCons['idPac']);?> <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwCons['idPac']; ?>" target=_blank><i class="fa fa-user"></i></a></td>
              <td><?php echo cambioFecha($rwCons['fecha']); ?></td>
			  <td><?php echo $rwCons['nombre']." ".$rwCons['apellidos'];?></td>
			  <td><?php echo $rwCons['poliza']."·"; ?></td>
              <td><?php echo $rwCons['autorizacion']; ?></td>
              <td><?php echo presciptor($seleccion, $rwCons['dueIda'], $rwCons['medIda']); ?></td>
              <td><?php @textoDesc($rwCons['idServi'], $rwCons['recoger'], $rwCons['locRec'], $rwCons['trasladar'], $rwCons['locTras']); ?></td>
			  <td><?php if($rwCons['idvta'] == '1') { echo "2"; } else { echo "1"; }?></td>
              <td></td>
              <td><div class="texto" id="km-<?php echo $rwCons['idSv'];?>"><?php if($rwCons['km'] == '0') {echo "URBANO"; } else { echo $rwCons['km']; } ?></div></td>
			  <td><?php ambComple($rwCons['recurso'], $rwCons['enfermero'], $rwCons['medico'], $rwCons['recuCorto']); ?></td>
            </tr>
			<?php } ?>
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
</body>
</html>
<?php
mysqli_close($gestambu);
?>
