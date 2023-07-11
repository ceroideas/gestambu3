<?php
session_start();
include '../../../functions/function.php';
nonUser();

/* Pendiente */
# Validación para los campos Prioridad y Demora

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {

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
  $continuado= trim(mysqli_real_escape_string($gestambu, $_POST['continuado']));
  $codRuta   = trim(mysqli_real_escape_string($gestambu, $_POST['codRuta']));
  $edadTab   = $edad." ".$edadTit;

  /* Carga variables para tabla serinfo */
  # Si no hay datos de hora, se guarda como: 00:00:00
  $prioridad = trim(mysqli_real_escape_string($gestambu, $_POST['prioridad']));
  $demora    = trim(mysqli_real_escape_string($gestambu, $_POST['demora']));
  $hvuelta   = trim(mysqli_real_escape_string($gestambu, $_POST['hvuelta']));
  $hconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta']));

  /* Carga datos especiales */
  @$oxigeno  = trim(mysqli_real_escape_string($gestambu, $_POST['ox']));
  @$rampa    = trim(mysqli_real_escape_string($gestambu, $_POST['rampa']));
  @$dostec   = trim(mysqli_real_escape_string($gestambu, $_POST['dostec']));

  /* Calculo dia festivo */
  $fst = festivo($fecha, $hora);

  /* Toma la referencia del paciente del servicio anterior */
  $idPac     = trim(mysqli_real_escape_string($gestambu, $_POST['idPac']));

  /* Comprobar id de paciente */
  include '../../referencia/compNuevoPaciente.php';

  /* Calculo ida y vuelta */
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

  /* Guardado de datos en la DB */
  # comprueba si existe id en la tabla paciente
  if(empty($pacienteID)) {
    $sqlServicio = "INSERT INTO servicio (idCia, continuado, codRuta, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs)
      VALUES ('$cia', '$continuado', '$codRuta', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs')
      ";
  } else {
    if($pacienteID == $idPac) {
      $datosPac  = mysqli_query($gestambu, "SELECT idPac, pacDNI, poliza FROM paciente WHERE idPac = '$idPac'");
      $rwDatosPac = mysqli_fetch_assoc($datosPac);
      //Cuando existe paciente, completa los datos de póliza y DNI si éstos estuvieran vacíos
      if(empty($DNI)) {
        $DNI = $rwDatosPac['pacDNI'];
      }
      if(empty($poliza)) {
        $poliza = $rwDatosPac['poliza'];
      }

      $sqlServicio = "INSERT INTO servicio (idCia, idPac, continuado, codRuta, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs)
        VALUES ('$cia', '$idPac', '$continuado', '$codRuta', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs')
        ";
    } else {
      $datosPac  = mysqli_query($gestambu, "SELECT idPac, pacDNI, poliza FROM paciente WHERE idPac = '$pacienteID'");
      $rwDatosPac = mysqli_fetch_assoc($datosPac);
      //Cuando existe paciente, completa los datos de póliza y DNI si éstos estuvieran vacíos
      if(empty($DNI)) {
        $DNI = $rwDatosPac['pacDNI'];
      }
      if(empty($poliza)) {
        $poliza = $rwDatosPac['poliza'];
      }

      $sqlServicio = "INSERT INTO servicio (idCia, continuado, codRuta, idPac, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs)
        VALUES ('$cia','$continuado', '$codRuta', '$pacienteID', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs')
        ";
    }
  }

  if(mysqli_query($gestambu,$sqlServicio)) {
    //echo "Datos insertados correctamente, en tabla servicio";
    // Ultimo id ingresado con $gestambu
    $idInsertado = mysqli_insert_id($gestambu);

    //Inserta registro en tabla serhorario
    # Pendiente de valorar si es necesario

    //Inserta registro en tabla serestados
    $serestadoIns = mysqli_query($gestambu, "INSERT INTO serestados (idSv) VALUES ('$idInsertado')");

    //Inserta registro en tabla serpersonal
    $serestadoIns = mysqli_query($gestambu, "INSERT INTO serpersonal (idSv) VALUES ('$idInsertado')");

    //Inserta registro en tabla serinfo
    $serinfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, hvuelta, hconsulta) VALUES ('$idInsertado', '$prioridad', '$demora', '$hvuelta', '$hconsulta')";
	
	//Actualiza el registro de sesiones + 1
	$numConti = mysqli_query($gestambu, "SELECT idRefCont, numCont , sesiones FROM refcontinuado WHERE numCont ='$continuado' ");
	$rwConti = mysqli_fetch_assoc($numConti);
	$sesionConti = $rwConti['sesiones'];
	$sesionPlus = $sesionConti + 1;
	
    $actSesion = mysqli_query($gestambu, "UPDATE refcontinuado SET sesiones='$sesionPlus' WHERE numCont ='$continuado'");

    if(mysqli_query($gestambu, $serinfoIns)) {
      $mensa   = "Ficha insertada correctamente";
      $mensaOk = '1';
	  
      /* Mensajes de log */
      $obsText = "+1 sesión";
      $usuario = $_SESSION['userId'];
      guardarLog('23', $usuario, $obsText, $idInsertado);

    } else {
      echo "Error: " . $serinfoIns . "<br>" . mysqli_error($gestambu);
    }
  } else {
    echo "Error: " . $sqlServicio . "<br>" . mysqli_error($gestambu);
  }
}

/* Datos para servicio vinculado por ID */

if(isset($_GET['numRh'])) {
  $registro = $_GET['numRh'];
} else {
  $registro = "";
}

# Datos para el servicio
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM servicio
  WHERE continuado = '$registro'
  GROUP BY continuado
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = $edaDatos[1];

/* Datos para selección */
# Aseguradora
$cia = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom DESC
  ");

# Tipo de servicio
$tServ = mysqli_query($gestambu,
  "SELECT idServi, nomSer
  FROM servi
  ORDER BY nomSer DESC
  ");

# Tipo de recurso
$tRecu = mysqli_query($gestambu,
  "SELECT idRecu, nomRecu
  FROM recurso
  ORDER BY nomRecu DESC
  ");

# Listado para delegaciones
$lsDeleg = mysqli_query($gestambu,"SELECT *
  FROM provincias
  ORDER BY provincia ASC
");

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
        Servicio id:
        <small><?php echo $rwPacMos['idSv']; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Ficha de servicio vinculado</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo servicio</h3>

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
                <div class="col-md-2"></div>
                <div class="col-md-8">
                <!-- Mensajes -->
                <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
                  - Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
                </div>
                <?php } ?>
                <!-- /Mensajes -->
                  <div class="col-md-6 col-sm-6 col-xs-8 form-group">
                    <label id="valCia">Compañía: </label>
                    <select class="form-control" name="idCia" id="idCia">
                      <option value="">-- Selecciona compañía --</option>
                      <?php
                      while($rCia = mysqli_fetch_assoc($cia)) {
                        if($rwPacMos['idCia'] == $rCia['idCia']) {
                          $seleccion = "selected";
                        } else {
                          $seleccion = "";
                        }
                        echo "<option value='".$rCia['idCia']."' ".$seleccion.">".$rCia['ciaNom']."</option>\n";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-4 form-group">
                    <label id="valProv">Provincia: </label>
                    <select class="form-control" name="provincia" id="prov" required>
                      <option value="">-- Selecciona Provincia --</option>
					  <option value="11" <?php if($rwPacMos['provincia'] == '11') {echo "selected=\"selected\""; } ?>>Cádiz</option>
					  <option value="14" <?php if($rwPacMos['provincia'] == '14') {echo "selected=\"selected\""; } ?>>Córdoba</option>
                      <option value="29" <?php if($rwPacMos['provincia'] == '29') {echo "selected=\"selected\""; } ?>>Málaga</option>
					  <option value="52" <?php if($rwPacMos['provincia'] == '52') {echo "selected=\"selected\""; } ?>>Melilla</option>
					  <option value="21" <?php if($rwPacMos['provincia'] == '21') {echo "selected=\"selected\""; } ?>>Huelva</option>
                      <option value="41" <?php if($rwPacMos['provincia'] == '41') {echo "selected=\"selected\""; } ?>>Sevilla</option>                      
                    </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>DNI: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="DNIPac" value="<?php echo $rwPacMos['DNIPac']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">D</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Póliza: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Póliza" name="poliza" value="<?php echo $rwPacMos['poliza']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">P</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Autorización: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Autorización" name="autorizacion" value="">
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
                          while($rDelg = mysqli_fetch_assoc($lsDeleg)) {
                            if($rwPacMos['delegacion'] == $rDelg['id']) {
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
                      <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo date("Y-m-d"); ?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valHora">Hora: </label>
                    <div class="input-group">
                      <input type="time" class="form-control" name="hora" id="hora" value="<?php echo date("H:i");?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Oxígeno
                        <input class="col-md-8" type="checkbox" class="minimal" name="ox" id="ox" value="1">
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Rampa
                        <input class="col-md-8" type="checkbox" class="minimal" name="rampa" id="rampa" value="1">
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>2Técnicos
                        <input class="col-md-8" type="checkbox" class="minimal" name="dostec" id="dostec" value="1">
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al tipo de servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valTipo">Tipo: </label>
                      <select class="form-control" name="tipo" id="tipo">
                        <option value="" selected="">-- Tipo de servicio --</option>
                        <?php
                          while($rServ = mysqli_fetch_assoc($tServ)) {
                            if($rwPacMos['tipo'] == $rServ['idServi']) {
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
                          while($rRecu = mysqli_fetch_assoc($tRecu)) {
                            if($rwPacMos['recurso'] == $rRecu['idRecu']) {
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
                        <input class="col-md-8" type="checkbox" class="minimal" name="medico" id="medico" value="1">
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valDue">Due
                        <input class="col-md-8" type="checkbox" class="minimal" name="enfermero" id="due" value="1">
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIda">Ida
                        <input class="col-md-8" type="checkbox" class="minimal" name="ida" id="ida" value="2">
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valVta">Vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="vta" id="vta" value="3">
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" id="idvta" value="1">
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" value="<?php echo $rwPacMos['nombre']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos" value="<?php echo $rwPacMos['apellidos']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>


                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 1" name="tlf1" value="<?php echo $rwPacMos['tlf1']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 2" name="tlf2" value="<?php echo $rwPacMos['tlf2']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Sexo: </label>
                      <select class="form-control" name="sexo">
                        <option value="">---</option>
                        <option value="H" <?php if($rwPacMos['sexo'] == "H") { echo "selected"; } ?>>Hombre</option>
                        <option value="M" <?php if($rwPacMos['sexo'] == "M") { echo "selected"; } ?>>Mujer</option>
                      </select>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Edad: </label>
                    <input type="text" class="form-control" placeholder="Edad" name="edad" value="<?php echo $numEdad; ?>">
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> -- </label>
                      <select class="form-control" name="edadTit">
                        <option value="AÑOS" <?php if($texEdad == "AÑOS") { echo "selected"; } ?>>Años</option>
                        <option value="MESES" <?php if($texEdad == "MESES") { echo "selected"; } ?>>Meses</option>
                        <option value="DIAS" <?php if($texEdad == "DIAS") { echo "selected"; } ?>>Días</option>
                      </select>
                  </div>

                  <div class="clearfix"></div>
                  <!-- Referente a dónde se realiza el servicio -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label" id="valRecoger">Recoger: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="recoger" id="recoger" value="<?php echo $rwPacMos['recoger']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-home"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locRec" value="<?php echo $rwPacMos['locRec']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Trasladar: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="trasladar" value="<?php echo $rwPacMos['trasladar']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-share"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locTras" value="<?php echo $rwPacMos['locTras']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>

                  <!-- textarea -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label>Observaciones</label>
                    <textarea class="form-control" rows="3" placeholder="Observaciones" name="obs"><?php echo $rwPacMos['obs']; ?></textarea>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> Prioridad </label>
                    <!-- Ha de definirse de acuerdo con las prioridadades de las compañías -->
                      <select class="form-control" name="prioridad">
                        <option value="3"> - Sin prioridad - </option>
                        <option value="1">Urgente</option>
                        <option value="2">Preferente</option>
                        <option value="4">Hora fija</option>
                        <option value="5">Hora desde</option>
                      </select>
                    <input type="time" class="form-control has-feedback" name="hconsulta" title="Hora a la que está citado" value="<?php if($rwSerinfoMos['hconsulta'] == '00:00:00') { } else { echo $rwSerinfoMos['hconsulta']; } ?>">
                    <span class="help-block h6">H. de consulta</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Demora: </label>
                    <input type="time" class="form-control" name="demora" title="demora de demora dado a la compañía" value="<?php if($rwSerinfoMos['demora'] == '00:00:00') { } else { echo $rwSerinfoMos['demora']; } ?>">
                    <input type="time" class="form-control has-feedback" name="hvuelta" title="Hora de vuelta" value="<?php if($rwSerinfoMos['hvuelta'] == '00:00:00') { } else { echo $rwSerinfoMos['hvuelta']; } ?>">
                    <span class="help-block h6">H. vuelta</span>
                    <input type="hidden" name="idPac" value="<?php echo $rwPacMos['idPac']; ?>">
					<input type="hidden" name="continuado" value="<?php echo $registro; ?>">
					<input type="hidden" name="codRuta" value="<?php echo $rwPacMos['codRuta']; ?>">
                  </div>
                  <!-- col-md-offset-3 -->
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <button type="reset" class="btn btn-primary">Cancelar</button>
                      <button type="submit" name="guardar" value="enviar" class="btn btn-success validar">Guardar</button>
                    </div>
                  </div>
                </div>
              <div class="col-md-2"></div>
              </form>
            </div>
            <!-- /.box-body -->
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
