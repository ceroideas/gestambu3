<?php
session_start();
include '../../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];
/* Pendiente */
# Guardar prioridades de asisa

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {
  /* Filtro de guardado */
  $coDemanda = trim(mysqli_real_escape_string($gestambu, $_POST['coDemanda']));
  $filGuarda = mysqli_query($gestambu, "SELECT cod_demanda, nuevo FROM asisademanda WHERE cod_demanda='$coDemanda'");
  $rwFilt    = mysqli_fetch_assoc($filGuarda);

  if($rwFilt['nuevo'] == 0 ) {
    $textInfo = "El servicio ya ha sido actualizado o guardado. No se puede volver a guardar";
    $mensaOk  = 0;
  } else {
    /* Carga de datos del formulario y limpieza */

    $cia       = trim(mysqli_real_escape_string($gestambu, $_POST['idCia']));
    $prov      = trim(mysqli_real_escape_string($gestambu, $_POST['provincia']));
    $DNI       = trim(mysqli_real_escape_string($gestambu, $_POST['DNIPac']));
    $poliza    = trim(mysqli_real_escape_string($gestambu, $_POST['poliza']));
    $auto      = trim(mysqli_real_escape_string($gestambu, $_POST['autorizacion']));
    $fecha     = trim(mysqli_real_escape_string($gestambu, $_POST['fecha']));
    $hora      = trim(mysqli_real_escape_string($gestambu, $_POST['hora']));
    $horaFor   = $hora.":00";
    $deleg     = trim(mysqli_real_escape_string($gestambu, $_POST['delegacion']));
    $tipo      = trim(mysqli_real_escape_string($gestambu, $_POST['tipo']));
    $recurso   = trim(mysqli_real_escape_string($gestambu, $_POST['recurso']));
    @$medico   = trim(mysqli_real_escape_string($gestambu, $_POST['medico']));
    @$due      = trim(mysqli_real_escape_string($gestambu, $_POST['enfermero']));
    @$cIdvta   = trim(mysqli_real_escape_string($gestambu, $_POST['idvta']));
    @$ida      = trim(mysqli_real_escape_string($gestambu, $_POST['ida']));
    @$vta      = trim(mysqli_real_escape_string($gestambu, $_POST['vta']));
    $nombre    = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['nombre'])));
    $apellidos = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['apellidos'])));
    $tlf1      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf1']));
    $tlf2      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf2']));
    $sexo      = trim(mysqli_real_escape_string($gestambu, $_POST['sexo']));
    $edad      = trim(mysqli_real_escape_string($gestambu, $_POST['edad']));
    $edadTit   = trim(mysqli_real_escape_string($gestambu, $_POST['edadTit']));
    $recoger   = trim(mysqli_real_escape_string($gestambu, $_POST['recoger']));
    $locRec    = trim(mysqli_real_escape_string($gestambu, $_POST['locRec']));
    $trasladar = trim(mysqli_real_escape_string($gestambu, $_POST['trasladar']));
    $locTras   = trim(mysqli_real_escape_string($gestambu, $_POST['locTras']));
    $obs       = trim(mysqli_real_escape_string($gestambu, $_POST['obs']));
    $edadTab   = $edad." ".$edadTit;
    $idenPac   = trim(mysqli_real_escape_string($gestambu, $_POST['idPac']));
    $identi    = trim(mysqli_real_escape_string($gestambu, $_POST['identi']));

    /* Carga variables para tabla serinfo */
    # Si no hay datos de hora, se guarda como: 00:00:00
    $prioridad    = trim(mysqli_real_escape_string($gestambu, $_POST['prioridad']));
    $demora       = trim(mysqli_real_escape_string($gestambu, $_POST['demora']));
    $hvuelta      = trim(mysqli_real_escape_string($gestambu, $_POST['hvuelta']));
    $hconsulta    = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta']));
    $idemandAsisa = trim(mysqli_real_escape_string($gestambu, $_POST['idemandAsisa']));
    $idasistencia = trim(mysqli_real_escape_string($gestambu, $_POST['idasistencia']));
    $estServ      = trim(mysqli_real_escape_string($gestambu, $_POST['estServ']));
    $mensaLog     = trim(mysqli_real_escape_string($gestambu, $_POST['mensaLog']));
    $gMenEsp      = trim(mysqli_real_escape_string($gestambu, $_POST['menSpecial']));

    /* Carga datos especiales */
    @$oxigeno  = trim(mysqli_real_escape_string($gestambu, $_POST['ox']));
    @$rampa    = trim(mysqli_real_escape_string($gestambu, $_POST['rampa']));
    @$dostec   = trim(mysqli_real_escape_string($gestambu, $_POST['dostec']));
    @$prescrip = trim(mysqli_real_escape_string($gestambu, $_POST['prescrip']));

    /* Calculo dia festivo */
    $fst = festivo($fecha, $hora);

    // Valor ida/vta
    if(empty($cIdvta) && empty($ida) && empty($vta)) {
      $idvta = "";
    } elseif(empty($cIdvta) && empty($ida) && $vta == 3) {
      $idvta = "3";
    } elseif(empty($cIdvta) && $ida == 2 && empty($vta)) {
      $idvta = "2";
    } elseif(empty($cIdvta) && $ida == 2 && $vta == 3 ) {
      $idvta = "1";
    } elseif($cIdvta == 1 && $ida == 2 && $vta == 3 ) {
      $idvta = "1";
    } elseif($cIdvta == 1 && $ida == 2 && empty($vta)) {
      $idvta = "1";
    } elseif($cIdvta == 1 && empty($ida) && empty($vta)) {
      $idvta = "1";
    } else {
      $idvta = "";
    }

    /* Comprobación si existe paciente - tabla paciente */

    include '../../referencia/compNuevoPaciente.php';

    /* Modifica servicio por asisa */
    # Comprueba los id de los pacientes, el resultante y el que aparece en la tabla servicio
    # Puede que se haya modificado el paciente
    if($pacienteID == $idenPac) {
      //Los id de paciente son iguales
      $servicioUp = "UPDATE servicio
        SET idCia='$cia', DNIPac='$DNI', poliza='$poliza', autorizacion='$auto', provincia='$prov', tipo='$tipo', recurso='$recurso', fecha='$fecha', hora='$hora',
          delegacion='$deleg', medico='$medico', enfermero='$due', idvta='$idvta', fest='$fst', nombre='$nombre', apellidos='$apellidos', tlf1='$tlf1',
          tlf2='$tlf2', sexo='$sexo', edad='$edadTab', recoger='$recoger', locRec='$locRec', trasladar='$trasladar', locTras='$locTras', obs='$obs', estServ='$estServ'
        WHERE idSv = '$identi'
        ";
    } else {
      //Los id de paciente son distintos
      $servicioUp = "UPDATE servicio
        SET idPac='$pacienteID', idCia='$cia', DNIPac='$DNI', poliza='$poliza', autorizacion='$auto', provincia='$prov', tipo='$tipo', recurso='$recurso', fecha='$fecha', hora='$hora',
          delegacion='$deleg', medico='$medico', enfermero='$due', idvta='$idvta', fest='$fst', nombre='$nombre', apellidos='$apellidos', tlf1='$tlf1',
          tlf2='$tlf2', sexo='$sexo', edad='$edadTab', recoger='$recoger', locRec='$locRec', trasladar='$trasladar', locTras='$locTras', obs='$obs', estServ='$estServ'
        WHERE idSv = '$identi'
        ";
    }

    # ·· serinfo ·· #
    # Comprueba si existe registro en la tabla serinfo

    $compSerInfo = mysqli_query($gestambu, "SELECT idSv FROM serinfo WHERE idSv = '$identi' ");
    $numCompInfo = mysqli_num_rows($compSerInfo);

    if($numCompInfo == 1 ) {
      //Actualiza el registro tabla serinfo
      $serinfoUp = "UPDATE serinfo
        SET prioridad='$prioridad', demora='$demora', hvuelta='$hvuelta', hconsulta='$hconsulta'
        WHERE idSv='$identi'
        ";
        if(mysqli_query($gestambu,$serinfoUp)) {
          //echo "Tabla serinfo acutalizada<br/>";
        } else {
          echo "Error:ln99 " . $serinfoUp . "<br/>" . mysqli_error($gestambu);
        }
    } else {
      //Crea un nuevo registro
      $serInfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, tconsulta, hconsulta)
        VALUES ('$identi', '$prioridad', '$demora', '$tconsulta', '$hconsulta')";

      if(mysqli_query($gestambu,$serInfoIns)) {
        //echo "Creado registro en tabla serinfo<br />";
      } else {
        echo "Error:ln109  " . $serInfoIns . "<br/>" . mysqli_error($gestambu);
      }
    }

    //Actualiza el registro tabla especial
    $speUp = mysqli_query($gestambu, "UPDATE especial SET ox='$oxigeno', rampa='$rampa', dTec='$dostec', prescriptor='$prescrip' WHERE idSv='$identi'");

	/* Actualiza estado de asisa demanda */
	$demandaUp = mysqli_query($gestambu, "UPDATE asisademanda SET nuevo='0' WHERE idemanda='$idemandAsisa' ");
	/* Notificación para Asisa */
		 
	//Parametros obligatorios
	$colaborador  = 'AANDALUC';
	$cod_demanda  = $coDemanda;
	$vuelta       = "";

	//Parametros obligatorios según el caso
	$estado             = "1";
	$fecha_estado       = "";
	$hora_estado        = "";
	$fecha_realizacion  = "";
	$hora_realizacion   = "";

	include '../../../API/noti_est_ambu.php'; // Notificaciones
	
    if(mysqli_query($gestambu,$servicioUp)) {
      $mensa   = "Ficha actualizada correctamente";
      $mensaOk = '1';

      /* Mensajes de log */
      $obsText = "mediante ficha - ASISA 24h";
      $usuario = $_SESSION['userId'];
      $servicioID = $identi;
      $divMensa = explode("##", $mensaLog);
      if(strlen($divMensa[0]) > 1) {
        guardarLog('3', $usuario, $divMensa[0], $servicioID);
      }
      if(strlen($divMensa[1]) > 1) {
        guardarLog('3', $usuario, $divMensa[1], $servicioID);
      }
      if(strlen($gMenEsp) > 1) {
        guardarLog('3', $usuario, $gMenEsp, $servicioID);
      } 
    } else {
      echo "Error:123 " . $servicioUp . "<br>" . mysqli_error($gestambu);
    }
  }

}

/* =========================================================================== */

/* Recogida de datos tabla asisa */
$idemanda = $_GET['idemanda'];

$dmAsisa = mysqli_query($gestambu, "SELECT * FROM asisademanda WHERE idemanda='$idemanda'");
$rwAsisa = mysqli_fetch_assoc($dmAsisa);

$codeAsisa = $rwAsisa['cod_demanda'];
$tipAsisa  = $rwAsisa['cod_servicio'];
$nuevo     = $rwAsisa['nuevo'];

if($nuevo == '2') {
  $asisAsisa = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado, COUNT(cod_demanda) AS numSesion FROM asisaasistencia WHERE cod_demanda ='$codeAsisa'");
} else {
  $asisAsisa = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado, COUNT(cod_demanda) AS numSesion FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' AND estado < 5 ");
}
$rwAst = mysqli_fetch_assoc($asisAsisa);
$compEst    = $rwAst['estado'];
$fechaAsist = $rwAst['fecha_asistencia'];

if($rwAst['numSesion'] > 1 ) {
  $serIdvta = 1;
} else {
  $serIdvta = 0;
}


if(isset($_SESSION['tipo_Asisa'.$tipAsisa]) &&  $_SESSION['tipo_Asisa'.$tipAsisa]['codigo'] == $tipAsisa){
    $rwTipAsisa=$_SESSION['tipo_Asisa'.$tipAsisa];
}else{
    $tipoAsisa = mysqli_query($gestambu, "SELECT * FROM codigoasisa WHERE codigo='$tipAsisa' ");
    $rwTipAsisa = mysqli_fetch_assoc($tipoAsisa);
    $_SESSION['tipo_Asisa'.$tipAsisa]=$rwTipAsisa;
}


/* Datos para comparar */
$compDemanda = mysqli_query($gestambu, "SELECT * FROM servicio WHERE coDemanda ='$codeAsisa'");
$rwCompDem   = mysqli_fetch_assoc($compDemanda);
$idComp      = $rwCompDem['idSv'];
$esTabla     = $rwCompDem['estServ'];
$estIdVta    = $rwCompDem['idvta'];

$horarios = mysqli_query($gestambu, "SELECT * FROM serinfo WHERE idSv='$idComp' ");
$rwHora   = mysqli_fetch_assoc($horarios);

/* Filtro de guardado */
$filGuarda2 = mysqli_query($gestambu, "SELECT cod_demanda, nuevo FROM asisademanda WHERE cod_demanda='$codeAsisa'");
$rwFilt2    = mysqli_fetch_assoc($filGuarda2);

if($rwFilt2['nuevo'] == 0 ) {
//
} else {
 include 'ref/filtromodnserv.php';
}

/* Comprobar estado de servicio */
if($compEst == '6') {
  $barraEstado = "danger";
  $textEstado  = "Cancelado servicio";
  $textBoton   = "Cancelar servicio";
  $colorBoton  = "danger";
  $estadoServ  = "15";
} elseif($compEst == '7') {
  $barraEstado = "danger";
  $textEstado  = "Anulado servicio";
  $textBoton   = "Anular servicio";
  $colorBoton  = "danger";
  $estadoServ  = "15";
} else {
  $barraEstado = "info";
  $textEstado  = "Modificar Servicio";
  $textBoton   = "Modificar servicio";
  $colorBoton  = "success";
  $estadoServ  = $rwCompDem['estServ'];
}

/* Valor para estado de servicio */

# Edad del paciente
$edaDatos = explode(" ", $rwAsisa['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = strtoupper($edaDatos[1]);

/* Datos para selección */
# Aseguradora
/*$cia = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom ASC
  ");*/

# Tipo de servicio
if(isset($_SESSION['tipo_servicio']) && count($_SESSION['tipo_servicio']) >0){
    $tServ =$_SESSION['tipo_servicio'];
}else{
    $tServ = mysqli_query($gestambu,
        "SELECT idServi, nomSer
  FROM servi
  ORDER BY nomSer ASC
  ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($tServ)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['tipo_servicio'] = $aux;
    $tServ =$_SESSION['tipo_servicio'];
}

# Tipo de recurso
if(isset($_SESSION['tipo_recurso']) && count($_SESSION['tipo_recurso']) >0){
    $tRecu =$_SESSION['tipo_recurso'];
}else{
    $tRecu = mysqli_query($gestambu,
        "SELECT idRecu, nomRecu
  FROM recurso
  ORDER BY nomRecu ASC
  ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($tRecu)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['tipo_recurso'] = $aux;
    $tRecu =$_SESSION['tipo_recurso'];
}


# Listado para delegaciones
if(isset($_SESSION['delegaciones']) && count($_SESSION['delegaciones']) >0){
    $lsDeleg=$_SESSION['delegaciones'];
}else{
    $lsDeleg = mysqli_query($gestambu,"SELECT *
      FROM provincias
      ORDER BY provincia ASC
    ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($lsDeleg)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['delegaciones'] = $aux;
    $lsDeleg =$_SESSION['delegaciones'];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Modificación | GestAmbu 3.0</title>
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
  <link href="/ops/css/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

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
  .box-body {
	  background-color: #e8eff1;
  }
  .modificado {
	background-color: #DCD236;
	border: 2px solid #A8A8A8;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
  font-weight: bold;
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
        Formulario de modificación de servicio
        <small>Datos Asisa 24h</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Modificación de servicio</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-<?php echo $barraEstado; ?> box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $textEstado." ".@$mensaLog; ?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form class="form-vertical form-label-left" action="" method="post">
                <!-- Compañia / provincia -->
                <div class="col-md-1"></div>
                <div class="col-md-10">
                <!-- Mensajes -->
                <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
                  - Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
                </div>
                <?php }  if(isset($textInfo)) { ?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="danger" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-exclamation-triangle"></i> <?php echo $textInfo; ?>
                    - Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
                  </div>
                <?php } ?>
                <!-- /Mensajes -->
                  <div class="col-md-6 col-sm-6 col-xs-8 form-group">
                    <label id="valCia">Compañía: </label>
                    <select class="form-control" name="idCia" id="idCia">
                      <option value="">-- Selecciona compañía --</option>
					  <?php 
						if($rwAsisa['num_poliza'] == "HLA SERVICES") {
							$valorCia = 103;
							$nomCia   = "HLA SERVICES";
						} else {
							$valorCia = 1;
							$nomCia   = "ASISA";  
						}
						echo "<option value='".$valorCia."'  selected>".$nomCia."</option>\n";
					  /*
                      while($rCia = mysqli_fetch_assoc($cia)) {
						if($rCia['idCia'] == '1') {
                          $seleccion = "selected";
                        } else {
                          $seleccion = "";
                        }
                        echo "<option value='".$rCia['idCia']."' ".$seleccion.">".$rCia['ciaNom']."</option>\n";
                      }
					  */
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-4 form-group">
                    <label id="valProv">Provincia: </label>
                    <select class="form-control" name="provincia" id="prov">
                      <?php $provincia= substr($rwAsisa['poblacion_origen'], 0,2); ?>
                      <option value="">-- Selecciona Provincia --</option>
                      <option value="11" <?php if($provincia == '11') { echo "selected"; } ?>>Cádiz</option>
					  <option value="14" <?php if($provincia == '14') { echo "selected"; } ?>>Córdoba</option>					  
                      <option value="29" <?php if($provincia == '29') { echo "selected"; } ?>>Málaga</option>
					  <option value="52" <?php if($provincia == '52') { echo "selected"; } ?>>Melilla</option>
					  <option value="21" <?php if($provincia == '21') { echo "selected"; } ?>>Huelva</option>					  
                      <option value="41" <?php if($provincia == '41') { echo "selected"; } ?>>Sevilla</option>
                    </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>DNI: </label>
                    <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="DNIPac">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Póliza: </label>
                    <input type="text" class="form-control <?php cModi(convertPoli($rwAsisa['num_poliza']), convertPoli($rwCompDem['poliza'])); ?>" placeholder="Póliza" name="poliza" value="<?php echo convertPoli($rwAsisa['num_poliza']); ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Autorización: </label>
                    <input type="text" class="form-control" placeholder="Autorización" name="autorizacion" value="<?php echo autoAsisa($rwAsisa['cod_demanda']); ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Delegación: </label>
                      <select class="form-control" name="delegacion">
                        <option value="0">-- No definida --</option>
                        <?php
                        foreach($lsDeleg as $rDelg){
                            if($rwAsisa['delegacion'] == $rDelg['id']) {
                              $seleccion = "selected";
                            } else {
                              $seleccion = "";
                            }
                            echo "<option value='".$rDelg['id']."' ".$seleccion.">".$rDelg['provincia']."</option>\n";
                          }
                         ?>
                      </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valFecha">Fecha: </label>
                    <div class="input-group">
                      <input type="date" class="form-control <?php cModi(muestraFechAsisa($rwAst['fecha_asistencia']), $rwCompDem['fecha']); ?>" name="fecha" id="fecha" value="<?php echo muestraFechAsisa($rwAst['fecha_asistencia']); ?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valHora">Hora: </label>
                    <div class="input-group">
                      <input type="time" class="form-control <?php if($rwAsisa['tipo_servicio'] == 'P') { cModi(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwHora['hconsulta']);} elseif($rwAsisa['tipo_servicio'] == 'U') { cModi(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwCompDem['hora']); } ?>" name="hora" id="hora"
                      value="<?php if(cModiDos(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwHora['hconsulta']) == 0 ) { echo $rwCompDem['hora']; } if($rwAsisa['tipo_servicio'] =='U') { echo muestraHorAsisa($rwAst['hora_asistencia']); } ?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Oxígeno
                        <input class="col-md-8" type="checkbox" class="minimal" name="ox" id="ox" value="1" <?php  if($rwAsisa['amb_oxigeno'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Rampa
                        <input class="col-md-8" type="checkbox" class="minimal" name="rampa" id="rampa" value="1" <?php  if($rwAsisa['amb_rampa'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>2Técnicos
                        <input class="col-md-8" type="checkbox" class="minimal" name="dostec" id="dostect" value="1" <?php  if($rwAsisa['amb_dostecnicos'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al tipo de servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valTipo">Tipo: </label>
                      <select class="form-control" name="tipo" id="tipo">
                        <option value="">-- Tipo de servicio --</option>
                        <?php
                        foreach($tServ as $rServ){
                            if($rwTipAsisa['idServi'] == $rServ['idServi']) {
                              $seleccion = "selected";
                            } else {
                              $seleccion = "";
                            }
                            echo "<option value='".$rServ['idServi']."' ".$seleccion.">".$rServ['nomSer']."</option>\n";
                          }
                         ?>
                      </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valRecurso">Recurso: </label>
                      <select class="form-control" name="recurso" id="recurso">
                        <option value="">-- Recurso --</option>
                        <?php
                          foreach($tRecu as $rRecu){
                            if($rwTipAsisa['idRecu'] == $rRecu['idRecu']) {
                              $seleccion = "selected";
                            } else {
                              $seleccion = "";
                            }
                            echo "<option value='".$rRecu['idRecu']."' ".$seleccion.">".$rRecu['nomRecu']."</option>\n";
                          }
                         ?>
                      </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valMedico">Médico
                        <input class="col-md-8" type="checkbox" class="minimal" name="medico" id="medico" value="1" <?php if($rwTipAsisa['idRecu'] == '4' || $rwTipAsisa['idRecu'] == '3' || $rwAsisa['amb_medico'] == 'S') { echo "checked"; }  ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valDue">Due
                        <input class="col-md-8" type="checkbox" class="minimal" name="enfermero" id="due" value="1" <?php if($rwTipAsisa['idRecu'] == '2' || $rwTipAsisa['idRecu'] == '3' || $rwAsisa['amb_enfermeria'] == 'S') { echo "checked"; }  ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Ida
                        <input class="col-md-8" type="checkbox" class="minimal" name="ida" id="idvta" value="2" <?php if(isset($calI_V) && $calI_V == '2') { echo "checked"; }  ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="vta" id="idvta" value="3" <?php if(isset($calI_V) && $calI_V == '3') { echo "checked"; }  ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" id="idvta" value="1" <?php if(isset($calI_V) && $calI_V == '1') { echo "checked"; }  ?>>
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control <?php cModi(strtoupper($rwAsisa['nombre']), strtoupper($rwCompDem['nombre'])); ?>" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" value="<?php echo strtoupper($rwAsisa['nombre']); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control <?php cModi(strtoupper($rwAsisa['apellido1']." ".$rwAsisa['apellido2']), strtoupper($rwCompDem['apellidos'])); ?>" placeholder="Apellidos" name="apellidos" value="<?php echo sanear_string(strtoupper($rwAsisa['apellido1']." ".$rwAsisa['apellido2'])); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>


                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control <?php cModi($rwAsisa['telefono_contacto1'], $rwCompDem['tlf1']); ?>" placeholder="Teléfono 1" name="tlf1"  value="<?php echo $rwAsisa['telefono_contacto1']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control <?php cModi($rwAsisa['telefono_contacto2'], $rwCompDem['tlf2']); ?>" placeholder="Teléfono 2" name="tlf2" value="<?php echo $rwAsisa['telefono_contacto2']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Sexo: </label>
                      <select class="form-control" name="sexo">
                        <option value="">---</option>
                        <option value="H" <?php if($rwAsisa['sexo'] == 'H') { echo "selected"; }?>>Hombre</option>
                        <option value="M" <?php if($rwAsisa['sexo'] == 'M') { echo "selected"; }?>>Mujer</option>
                      </select>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Edad: </label>
                    <input type="text" class="form-control <?php cModi($rwAsisa['edad'], $rwCompDem['edad']); ?>" placeholder="Edad" name="edad" value="<?php echo $numEdad; ?>" >
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> -- </label>
                      <select class="form-control" name="edadTit">
                        <option value="AÑOS" <?php if($texEdad == 'AÑOS') { echo "selected"; }?>>Años</option>
                        <option value="MESES" <?php if($texEdad == 'MESES') { echo "selected"; }?>>Meses</option>
                        <option value="DIAS" <?php if($texEdad == 'DIAS') { echo "selected"; }?>>Días</option>
                      </select>
                  </div>

                  <div class="clearfix"></div>
                  <!-- Referente a dónde se realiza el servicio -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label" id="valRecoger">Recoger: </label>
                    <div class="input-group">
                      <input type="text" class="form-control <?php cModi(strtoupper($rwAsisa['direccion_origen']), strtoupper($rwCompDem['recoger'])); ?>" name="recoger" id="recoger" value="<?php echo strtoupper($rwAsisa['direccion_origen']); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-home"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad <?php cModi(strtoupper($rwAsisa['poblacion_origen_nombre']), strtoupper($rwCompDem['locRec'])); ?>" name="locRec" value="<?php echo strtoupper($rwAsisa['poblacion_origen_nombre']); ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Trasladar: </label>
                    <div class="input-group">
                      <input type="text" class="form-control <?php if(empty($rwAsisa['direccion_destino']) OR empty($rwCompDem['trasladar'])) { echo ""; } else {cModi(strtoupper($rwAsisa['direccion_destino']), strtoupper($rwCompDem['trasladar']));} ?>" name="trasladar" value="<?php echo strtoupper($rwAsisa['direccion_destino']); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-share"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad <?php if(empty($rwAsisa['poblacion_destino_nombre']) OR empty($rwCompDem['locTras'])) { echo ""; } else {cModi(strtoupper($rwAsisa['poblacion_destino_nombre']), strtoupper($rwCompDem['locTras']));} ?>" name="locTras" value="<?php echo strtoupper($rwAsisa['poblacion_destino_nombre']); ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>

                  <!-- textarea -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label>Observaciones</label>
                    <textarea class="form-control <?php cModi($rwAsisa['motivo']." - ".$rwAsisa['observaciones_p']." - ".$rwAsisa['observaciones_s'], $rwCompDem['obs']."  "); ?>" rows="3" placeholder="Observaciones" name="obs"><?php echo $rwAsisa['motivo']." - ".$rwAsisa['observaciones_p']." - ".$rwAsisa['observaciones_s']." ".@$menObs; ?></textarea>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> Prioridad </label>
                    <!-- Ha de definirse de acuerdo con las prioridadades de las compañías -->
                      <select class="form-control <?php cModi($rwAsisa['prioridad'], $rwHora['prioridad']); ?>" name="prioridad">
                        <option value="3" <?php if($rwAsisa['prioridad'] == '3') { echo "selected"; }?>> - Sin prioridad - </option>
                        <option value="1" <?php if($rwAsisa['prioridad'] == '1') { echo "selected"; }?>>Urgente</option>
                        <option value="2" <?php if($rwAsisa['prioridad'] == '2') { echo "selected"; }?>>Preferente</option>
                        <option value="4" <?php if($rwAsisa['prioridad'] == '4') { echo "selected"; }?>>Hora fija</option>
                        <option value="5" <?php if($rwAsisa['prioridad'] == '5') { echo "selected"; }?>>Hora desde</option>
                      </select>
                    <input type="time" class="form-control has-feedback <?php if($rwAst['tipo_servicio'] =='P') { cModi( muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwHora['hconsulta']); }  ?>"
                      name="hconsulta" title="Hora a la que está citado" value="<?php if($rwAsisa['tipo_servicio'] =='P' && $rwAst['vuelta']=='N') { echo muestraHorAsisa($rwAst['hora_asistencia']); } ?>">
                    <span class="help-block h6">H. de consulta</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Demora: </label>
                    <input type="time" class="form-control" name="demora" title="demora de demora dado a la compañía" value="">
                    <input type="time" class="form-control has-feedback" name="hvuelta" title="Hora de vuelta" value="<?php if(isset($mosVta) && $mosVta == 1) { echo muestraHorAsisa($rwSqlVta['hora_asistencia']); } ?>" <?php if(@$mosVta != 1) { echo "readonly"; }?>">
                    <span class="help-block h6">H. vuelta</span>
                  </div>
                  <!-- col-md-offset-3 -->
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="hidden" name="idemandAsisa" value="<?php echo $rwAsisa['idemanda']; ?>" >
                      <input type="hidden" name="coDemanda" value="<?php echo $codeAsisa; ?>" >
                      <input type="hidden" name="idasistencia" value="<?php echo $rwAst['idasistencia']; ?>" >
                      <input type="hidden" name="idPac" value="<?php echo $rwCompDem['idPac']; ?>" >
                      <input type="hidden" name="estServ" value="<?php if(isset($estRes)) { echo $estRes; } else { echo $estadoServ; } ?>" >
                      <input type="hidden" name="identi" value="<?php echo $rwCompDem['idSv']; ?>" >
                      <input type="hidden" name="mensaLog" value="<?php echo $menLog; ?>" >
                      <input type="hidden" name="menSpecial" value="<?php echo $menSpecial; ?>" >
                      <input type="hidden" name="prescrip" value="<?php echo $rwAsisa['prescriptor']; ?>" >
                      <button type="submit" name="guardar" value="enviar" class="btn btn-<?php echo $colorBoton; ?> validar"><?php echo $textBoton; ?></button>
                    </div>
                  </div>
                </div>
              <div class="col-md-1"></div>
              </form>
            </div>
            <!-- /.box-body -->
			<!--
            <div class="box-footer">

            </div>
			-->
            <!-- /.box-footer-->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include '../inc/pie.php'; ?>

<?php //include '../inc/bcontrol.php'; ?>

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
<!-- Validación para nuevo servicio -->
<script src="../referencia/validarNuevoServicio.js"></script>
<!-- Autocomplete -->
<script>
$(document).ready(function () {
  $(".localidad").autocomplete({
    minLength: 3,
		source: '/ops/referencia/autotrabj/localidad.php'
	});
});
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
