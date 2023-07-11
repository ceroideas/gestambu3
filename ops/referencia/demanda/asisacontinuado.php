<?php
session_start();
include '../../../functions/function.php';
nonUser();

/* Pendiente */
# Validación para los campos Prioridad y Demora
# incluir continutado en historial de paciente
# Recogida de variables de pauta y guardar en tabla a parte
# Comprobar registros guardado de paciente
# Modificar numero de sesiones con groupo por fecha

//Recogida de variables
@$sesiones   = trim(mysqli_real_escape_string($gestambu, $_GET['sesiones']));

$pauGuardar  = 0;

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
    $sesiones = trim(mysqli_real_escape_string($gestambu, $_POST['sesiones']));
    $pauta    = trim(mysqli_real_escape_string($gestambu, $_POST['pauta']));
    /* Obtener último id */
    $obUltId = mysqli_query($gestambu, "SELECT idSv
      FROM servicio
      ORDER BY idSv DESC LIMIT 1
    ");
    $rwUltId = mysqli_fetch_assoc($obUltId);
    $rhAum   = $rwUltId['idSv'] + 1;
    $anio    = date("Y");

    $rhResul = "RH$anio/$rhAum";

    /* Carga de datos del formulario y limpieza */

    $cia       = trim(mysqli_real_escape_string($gestambu, $_POST['idCia']));
    $prov      = trim(mysqli_real_escape_string($gestambu, $_POST['provincia']));
    $DNI       = trim(mysqli_real_escape_string($gestambu, $_POST['DNIPac']));
    $poliza    = trim(mysqli_real_escape_string($gestambu, $_POST['poliza']));
    $auto      = trim(mysqli_real_escape_string($gestambu, $_POST['autorizacion']));
    //$fecha     = trim(mysqli_real_escape_string($gestambu, $_POST['fecha']));
    @$hora     = trim(mysqli_real_escape_string($gestambu, $_POST['hora']));
    @$horaFor  = $hora.":00";
    $deleg     = trim(mysqli_real_escape_string($gestambu, $_POST['delegacion']));
    $tipo      = trim(mysqli_real_escape_string($gestambu, $_POST['tipo']));
    $recurso   = trim(mysqli_real_escape_string($gestambu, $_POST['recurso']));
    @$medico   = trim(mysqli_real_escape_string($gestambu, $_POST['medico']));
    @$due      = trim(mysqli_real_escape_string($gestambu, $_POST['enfermero']));
    $nombre    = trim(mysqli_real_escape_string($gestambu, strtoupper($_POST['nombre'])));
    $apellidos = trim(mysqli_real_escape_string($gestambu, strtoupper($_POST['apellidos'])));
    $tlf1      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf1']));
    $tlf2      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf2']));
    $sexo      = trim(mysqli_real_escape_string($gestambu, $_POST['sexo']));
    $edad      = trim(mysqli_real_escape_string($gestambu, $_POST['edad']));
    $edadTit   = trim(mysqli_real_escape_string($gestambu, $_POST['edadTit']));
    $recoger   = trim(mysqli_real_escape_string($gestambu, strtoupper($_POST['recoger'])));
    $locRec    = trim(mysqli_real_escape_string($gestambu, $_POST['locRec']));
    $trasladar = trim(mysqli_real_escape_string($gestambu, strtoupper($_POST['trasladar'])));
    $locTras   = trim(mysqli_real_escape_string($gestambu, $_POST['locTras']));
    $obs       = trim(mysqli_real_escape_string($gestambu, $_POST['obs']));
    $edadTab   = $edad." ".$edadTit;

    /* Carga variables para tabla serinfo */
    # Si no hay datos de hora, se guarda como: 00:00:00
    $prioridad  = trim(mysqli_real_escape_string($gestambu, $_POST['prioridad']));
    $demora     = trim(mysqli_real_escape_string($gestambu, $_POST['demora']));
    //$tconsulta  = trim(mysqli_real_escape_string($gestambu, $_POST['tconsulta']));
    @$hconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta']));
    $desiOps    = trim(mysqli_real_escape_string($gestambu, $_POST['desigual']));
    $idemandAsisa = trim(mysqli_real_escape_string($gestambu, $_POST['idemandAsisa']));

    /* Carga datos especiales */
    @$oxigeno  = trim(mysqli_real_escape_string($gestambu, $_POST['ox']));
    @$rampa    = trim(mysqli_real_escape_string($gestambu, $_POST['rampa']));
    @$dostec   = trim(mysqli_real_escape_string($gestambu, $_POST['dostec']));
    @$prescrip = trim(mysqli_real_escape_string($gestambu, $_POST['prescrip']));

    /* Calculo dia festivo */
    @$fst = festivo($fecha, $hora);

    /* Comprobación si existe paciente - tabla paciente */

    include '../../referencia/compNuevoPaciente.php';

    /* Guardado de datos en la DB */
    # comprueba si existe id en la tabla paciente

    if(empty($pacienteID)) {
      //Inserta registro en tabla refcontinuado
      $refcontIns = "INSERT INTO refcontinuado (numCont, sesiones, pauta) VALUES ('$rhResul', '$sesiones', '$pauta')";

      if(mysqli_query($gestambu, $refcontIns)) {
        $mensaRef = '1';

        /* Mensajes de log */
        $obsText = ":".$nombre." ".$apellidos."**".$rhResul;
        $usuario = $_SESSION['userId'];
        $servicioID = '0';
        guardarLog('8', $usuario, $obsText, $servicioID);

      } else {
        echo "Error: 110 " . $refcontIns . "<br>" . mysqli_error($gestambu);
      }

      for ($nS=1; $nS<=$sesiones; $nS++) {
        //Inserta en la tabla servicio
        $fecha = trim(mysqli_real_escape_string($gestambu, $_POST['fecha'.$nS.'']));
        $ideasistencia = trim(mysqli_real_escape_string($gestambu, $_POST['idasistencia'.$nS.'']));
        $coDemanda  = trim(mysqli_real_escape_string($gestambu, $_POST['coDemanda']));
        $menGuarda2 = trim(mysqli_real_escape_string($gestambu, $_POST['menGuarda'.$nS.'']));
        $hvuelta    = trim(mysqli_real_escape_string($gestambu, $_POST['hvuelta'.$nS.'']));
        $mensaLog   = trim(mysqli_real_escape_string($gestambu, $_POST['mensalog'.$nS.'']));
        @$idvta    = trim(mysqli_real_escape_string($gestambu, $_POST['idvtaRes'.$nS.'']));
        //Datos para agregar en observaciones
        if($menGuarda2 = 1 ) {
          $agr     = trim(mysqli_real_escape_string($gestambu, $_POST['textAgregar'.$nS.'']));
          $estServ = trim(mysqli_real_escape_string($gestambu, $_POST['estServ'.$nS.'']));
          $obs     = trim(mysqli_real_escape_string($gestambu, $_POST['obs'])).$agr;
        } else {
          $estServ = trim(mysqli_real_escape_string($gestambu, $_POST['estServ'.$nS.'']));
          $obs     = trim(mysqli_real_escape_string($gestambu, $_POST['obs']));
        }
        if($desiOps != 1) {
          $horaFor   = $horaFor;
          $hconsulta = $hconsulta;
        } else {
          $horaFor   = trim(mysqli_real_escape_string($gestambu, $_POST['hora'.$nS.'']));
          $hconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta'.$nS.''])).":00";
        }

        $sqlServicio ="INSERT INTO servicio (coDemanda, idemanda, idasistencia, continuado, idCia, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs, estServ)
          VALUES ('$coDemanda','$idemandAsisa','$ideasistencia','$rhResul', '$cia', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs', '$estServ')
          ";
        //Inserta en la tabla serinfo
        if(mysqli_query($gestambu,$sqlServicio)) {
          // Ultimo id ingresado con $gestambu
          $idInsertado = mysqli_insert_id($gestambu);

          //Inserta registro en tabla serestados
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serestados (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla serpersonal
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serpersonal (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla especial
          $especialIns = mysqli_query($gestambu, "INSERT INTO especial (idSv, ox, rampa, dTec, prescriptor) VALUES ('$idInsertado', '$oxigeno', '$rampa', '$dostec', '$prescrip')");

          //Inserta registro en tabla serinfo
          $serinfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, hconsulta, hvuelta) VALUES ('$idInsertado', '$prioridad', '$demora', '$hconsulta', '$hvuelta')";

          if(mysqli_query($gestambu, $serinfoIns)) {
            $mensa   = "Ficha insertada correctamente.";
            $mensaOk = '1';

            /* Mensajes de log */
            $obsText = "- Servicio continuado Asisa 24h";
            $usuario = $_SESSION['userId'];

            /* Actualiza estado de asisa demanda */
            $demandaUp = mysqli_query($gestambu, "UPDATE asisademanda SET nuevo='0' WHERE idemanda='$idemandAsisa' ");

            guardarLog('5', $usuario, $obsText, $idInsertado);
            if($menGuarda2 == 1) {
              guardarLog('18', $usuario, $mensaLog, $idInsertado);
            }
          } else {
            echo "Error: 170 " . $serinfoIns . "<br>" . mysqli_error($gestambu);
          }
        } else {
          echo "Error: 134" . $sqlServicio . "<br>" . mysqli_error($gestambu);
        }
      }
    } else {
      //Inserta registro en tabla refcontinuado
      $refcontIns = "INSERT INTO refcontinuado (numCont, sesiones, pauta) VALUES ('$rhResul', '$sesiones', '$pauta')";

      if(mysqli_query($gestambu, $refcontIns)) {
        $mensaRef = '1';

        /* Mensajes de log */
        $obsText = ":".$nombre." ".$apellidos."**".$rhResul;
        $usuario = $_SESSION['userId'];
        $servicioID = '0';
        guardarLog('8', $usuario, $obsText, $servicioID);

      } else {
        echo "Error: 148 " . $refcontIns . "<br>" . mysqli_error($gestambu);
      }

      for ($nS=1; $nS<=$sesiones; $nS++) {
        $fecha      = trim(mysqli_real_escape_string($gestambu, $_POST['fecha'.$nS.'']));
        $ideasistencia = trim(mysqli_real_escape_string($gestambu, $_POST['idasistencia'.$nS.'']));
        $coDemanda  = trim(mysqli_real_escape_string($gestambu, $_POST['coDemanda']));
        $menGuarda2 = trim(mysqli_real_escape_string($gestambu, $_POST['menGuarda'.$nS.'']));
        $hvuelta    = trim(mysqli_real_escape_string($gestambu, $_POST['hvuelta'.$nS.'']));
        $mensaLog   = trim(mysqli_real_escape_string($gestambu, $_POST['mensalog'.$nS.'']));
        @$idvta    = trim(mysqli_real_escape_string($gestambu, $_POST['idvtaRes'.$nS.'']));
        //Datos para agregar en observaciones
        if($menGuarda2 = 1 ) {
          $agr     = trim(mysqli_real_escape_string($gestambu, $_POST['textAgregar'.$nS.'']));
          $estServ = trim(mysqli_real_escape_string($gestambu, $_POST['estServ'.$nS.'']));
          $obs     = trim(mysqli_real_escape_string($gestambu, $_POST['obs'])).$agr;
        } else {
          $estServ = trim(mysqli_real_escape_string($gestambu, $_POST['estServ'.$nS.'']));
          $obs     = trim(mysqli_real_escape_string($gestambu, $_POST['obs']));
        }

        if($desiOps == 1) {
          $horaFor   = trim(mysqli_real_escape_string($gestambu, $_POST['hora'.$nS.'']));
          $hconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta'.$nS.'']));
        }
        $sqlServicio = "INSERT INTO servicio (coDemanda, idemanda, idasistencia, continuado, idCia, idPac, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs, estServ)
          VALUES ('$coDemanda','$idemandAsisa','$ideasistencia','$rhResul', '$cia', '$pacienteID', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs', '$estServ')
          ";
        //Inserta en la tabla serinfo
        if(mysqli_query($gestambu,$sqlServicio)) {
          // Ultimo id ingresado con $gestambu
          $idInsertado = mysqli_insert_id($gestambu);

          //Inserta registro en tabla serestados
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serestados (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla serpersonal
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serpersonal (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla especial
          $especialIns = mysqli_query($gestambu, "INSERT INTO especial (idSv, ox, rampa, dTec, prescriptor) VALUES ('$idInsertado', '$oxigeno', '$rampa', '$dostec', '$prescrip')");
          //Inserta registro en tabla serinfo
          $serinfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, hconsulta, hvuelta) VALUES ('$idInsertado', '$prioridad', '$demora', '$hconsulta', '$hvuelta')";
          if(mysqli_query($gestambu, $serinfoIns)) {
            $mensa   = "Ficha insertada correctamente.";
            $mensaOk = '1';

            /* Mensajes de log */
            $obsText = "- Servicio continuado Asisa24h";
            $usuario = $_SESSION['userId'];

            /* Actualiza estado de asisa demanda */
            $demandaUp = mysqli_query($gestambu, "UPDATE asisademanda SET nuevo='0' WHERE idemanda='$idemandAsisa' ");

            guardarLog('5', $usuario, $obsText, $idInsertado);
            if($menGuarda2 == 1) {
              guardarLog('18', $usuario, $mensaLog, $idInsertado);
            }

          } else {
            echo "Error: 248 " . $serinfoIns . "<br>" . mysqli_error($gestambu);
          }
        } else {
          echo "Error: 251 " . $sqlServicio . "<br>" . mysqli_error($gestambu);
        }
      }
    }
  }
			/* Notificación para Asisa */
			 
			 //Parametros obligatorios
			 $colaborador  = 'AANDALUC';
			 $cod_demanda  = $coDemanda;

			 //Parametros obligatorios según el caso
			 $estado             = "1";
			 $fecha_estado       = "";
			 $hora_estado        = "";
			 $fecha_realizacion  = "";
			 $hora_realizacion   = "";

			 include '../../../API/noti_est_ambu.php'; // Notificaciones			
			  
}

/* Carga de datos tabla asisademanda */
$asisaID = $_GET['idemanda'];

$tabDemanda = mysqli_query($gestambu, "SELECT * FROM asisademanda WHERE idemanda = '$asisaID'");
$rwTabDem = mysqli_fetch_assoc($tabDemanda);

$codeAsisa = $rwTabDem['cod_demanda'];
$tipAsisa  = $rwTabDem['cod_servicio'];

$asisAsisa = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado,  COUNT(cod_demanda) AS numSesion FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' AND estado < 5 ");
$rwAst = mysqli_fetch_assoc($asisAsisa);


if($tipAsisa >= '99' && $tipAsisa <= '116'  ) {
  $serIdvta = "1";
} else {
  $serIdvta = "0";
}

$tipoAsisa = mysqli_query($gestambu, "SELECT * FROM codigoasisa WHERE codigo='$tipAsisa' ");
$rwTipAsisa = mysqli_fetch_assoc($tipoAsisa);

# Comprueba si hay algún horario distinto
if($rwTabDem['cod_servicio'] > 60 && $rwTabDem['cod_servicio'] < 68) {
	$compIgual = mysqli_query($gestambu, "SELECT hora_asistencia, cod_demanda FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' GROUP BY hora_asistencia"); //GROUP BY hora_asistencia
	$nIgual = mysqli_num_rows($compIgual);	
} else {
	$compIgual = mysqli_query($gestambu, "SELECT hora_asistencia, cod_demanda FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' AND vuelta = 'N' GROUP BY hora_asistencia");
	$nIgual = mysqli_num_rows($compIgual);
}


# Marca los servicios cuando hay algun horario distinto, seleccionando otra visualización del continuado
if($nIgual == 1 ) {
  $desigual = 0;
  $inactivo = "";
} else {
  $desigual = 1;
  $inactivo = "disabled";
}

# Edad del paciente
$edaDatos = explode(" ", $rwTabDem['edad']);
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
  <title>GestAmbu 3.0 | Nuevo servicio</title>
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
        Formulario servicio continuado
        <small>Asisa 24h</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Nuevo continuado</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo continuado  <?php if($desigual == 1 ) { echo "<span><i class=\"fa fa-exclamation-triangle\"></i> Horas distintas"; } ?></h3>
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
                <div class="col-md-10 col-md-offset-1">
                <!-- Mensajes -->
                <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fa fa-check"></i> admin<?php echo $mensa; ?>
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
                      <?php $provincia= substr($rwTabDem['poblacion_origen'], 0,2); ?>
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
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="DNIPac">
                      <div class="input-group-addon">
                        <i class="fa">D</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Póliza: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Póliza" name="poliza" value="<?php echo convertPoli($rwTabDem['num_poliza']); ?>">
                      <div class="input-group-addon">
                        <i class="fa">P</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Autorización: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Autorización" name="autorizacion" value="<?php echo autoAsisa($rwTabDem['cod_demanda']); ?>" readonly>
                      <div class="input-group-addon">
                        <i class="fa">A</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Delegación: </label>
                      <select class="form-control" name="delegacion">
                        <option value="0">-- No definida --</option>
                        <?php
                           foreach($lsDeleg as $rDelg){
                            if($rwTabDem['delegacion'] == $rDelg['id']) {
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
                    <label>Sesiones: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="sesiones" id="fecha" value="<?php echo $sesiones ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valHora">Hora: </label>
                    <div class="input-group">
                      <input type="time" class="form-control" name="hora" id="hora" value="<?php if($desigual == 1) { echo date("H:i"); } else { } ?>"  <?php if($desigual == 1) { echo "title=\"Este campo no especifica la hora de recogida. Campo desactivado.\" readonly"; } else { echo "required"; } ?>>
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Oxígeno
                        <input class="col-md-8" type="checkbox" class="minimal" name="ox" id="ox" value="1" <?php  if($rwTabDem['amb_oxigeno'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Rampa
                        <input class="col-md-8" type="checkbox" class="minimal" name="rampa" id="rampa" value="1" <?php  if($rwTabDem['amb_rampa'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>2Técnicos
                        <input class="col-md-8" type="checkbox" class="minimal" name="dostec" id="dostect" value="1" <?php  if($rwTabDem['amb_dostecnicos'] == 'S') { echo "checked"; }; ?>>
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
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label id="valMedico">Médico
                        <input class="col-md-8" type="checkbox" class="minimal" name="medico" id="medico" value="1" <?php if($rwTipAsisa['idRecu'] == '4') { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label id="valDue">Due
                        <input class="col-md-8" type="checkbox" class="minimal" name="enfermero" id="due" value="1" <?php if($rwTipAsisa['idRecu'] == '2') { echo "checked"; } ?>>
                      </label>
                    </div>
                    <!-- El calculo de ida y vuelta se hace en otra sección
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label id="valIdavta">Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" id="idvta" value="1" <?php //if($serIdvta == '1') { echo "checked"; } ?>>
                      </label>
                    </div>
                    -->
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" value="<?php echo $rwTabDem['nombre']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos" value="<?php echo sanear_string($rwTabDem['apellido1']." ".$rwTabDem['apellido2']); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>


                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 1" name="tlf1" value="<?php echo $rwTabDem['telefono_contacto1']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 2" name="tlf2" value="<?php echo $rwTabDem['telefono_contacto2']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Sexo: </label>
                      <select class="form-control" name="sexo">
                        <option value="">---</option>
                        <option value="H" <?php if($rwTabDem['sexo'] == 'H') { echo "selected"; }?>>Hombre</option>
                        <option value="M" <?php if($rwTabDem['sexo'] == 'M') { echo "selected"; }?>>Mujer</option>
                      </select>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Edad: </label>
                    <input type="text" class="form-control" placeholder="Edad" name="edad" value="<?php echo $numEdad; ?>" >
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
                      <input type="text" class="form-control" name="recoger" id="recoger" value="<?php echo $rwTabDem['direccion_origen']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-home"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locRec" value="<?php echo $rwTabDem['poblacion_origen_nombre']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Trasladar: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="trasladar" value="<?php echo $rwTabDem['direccion_destino']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-share"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locTras" value="<?php echo $rwTabDem['poblacion_destino_nombre']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>

                  <!-- textarea -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label>Observaciones</label>
                    <textarea class="form-control" rows="3" placeholder="Observaciones" name="obs"><?php echo $rwTabDem['motivo']." - ".$rwTabDem['observaciones_p']." - ".$rwTabDem['observaciones_s']; ?></textarea>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> Prioridad </label>
                    <!-- Ha de definirse de acuerdo con las prioridadades de las compañías -->
                      <select class="form-control" name="prioridad">
                        <option value="3" <?php if($rwTabDem['prioridad'] == '3') { echo "selected"; }?>> - Sin prioridad - </option>
                        <option value="1" <?php if($rwTabDem['prioridad'] == '1') { echo "selected"; }?>>Urgente</option>
                        <option value="2" <?php if($rwTabDem['prioridad'] == '2') { echo "selected"; }?>>Preferente</option>
                        <option value="4" <?php if($rwTabDem['prioridad'] == '4') { echo "selected"; }?>>Hora fija</option>
                        <option value="5" <?php if($rwTabDem['prioridad'] == '5') { echo "selected"; }?>>Hora desde</option>
                      </select>
                    <input type="time" class="form-control has-feedback" name="hconsulta" title="Hora a la que está citado" value="" readonly>
                    <span class="help-block h6">H. de consulta</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Demora: </label>
                    <input type="time" class="form-control" name="demora" title="demora de demora dado a la compañía" value="" readonly>
                    <input type="time" class="form-control has-feedback" name="tconsulta" title="Tiempo que estará en consulta" value="" readonly>
                    <span class="help-block h6">T. en consulta</span>
                  </div>
                  <div class="clearfix"></div>
                  <!-- Sesiones -->
                  <?php
                  if($rwTabDem['cod_servicio'] > 60 && $rwTabDem['cod_servicio'] < 68) {
                    $colSesion = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado FROM asisaasistencia WHERE cod_demanda ='$codeAsisa'");
                  } else {
                    $colSesion = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' GROUP BY fecha_asistencia");
                  }

                  $i   = 1;
                  $fch = 1;
                  $cst = 1;
                  $hr  = 1;
                  $hrV = 1;
                  $dam = 1;
                  $txA = 1;
                  $sta = 1;
                  $msG = 1;
                  $msL = 1;
                  $ivC = 1;
                  include 'horadistinta.php';

                  ?>
                  <div class="clearfix"></div>
                  <p></p>
                  <!-- /Sesiones -->

                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="hidden" name="pauta" value="<?php echo $pauGuardar; ?>">
                      <input type="hidden" name="idemandAsisa" value="<?php echo $_GET['idemanda']; ?>" >
                      <input type="hidden" name="prescrip" value="<?php echo $rwTabDem['prescriptor']; ?>">
                      <button type="reset" class="btn btn-primary">Cancelar</button>
                      <button type="submit" name="guardar" value="enviar" class="btn btn-success validar">Guardar</button>
                    </div>
                  </div>
                </div>
              <div class="col-md-2"></div>
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
<script src="/ops/referencia/validarNuevoServicio.js"></script>
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
