<?php
session_start();
include '../../../functions/function.php';
nonUser();

/*
Recuperación de archivo, muestra las horas de consultas de ambulancia. se ha de introducir uno a uno la hora de recogida.
*/

/* Pendiente */
# Validación para los campos Prioridad y Demora
# incluir continutado en historial de paciente
# Recogida de variables de pauta y guardar en tabla a parte
# Comprobar registros guardado de paciente

//Recogida de variables
@$sesiones   = trim(mysqli_real_escape_string($gestambu, $_GET['sesiones'])) / 2;

$pauGuardar  = 0;

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {
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
  $hora      = trim(mysqli_real_escape_string($gestambu, $_POST['hora']));
  $horaFor   = $hora.":00";
  $deleg     = trim(mysqli_real_escape_string($gestambu, $_POST['delegacion']));
  $tipo      = trim(mysqli_real_escape_string($gestambu, $_POST['tipo']));
  $recurso   = trim(mysqli_real_escape_string($gestambu, $_POST['recurso']));
  @$medico   = trim(mysqli_real_escape_string($gestambu, $_POST['medico']));
  @$due      = trim(mysqli_real_escape_string($gestambu, $_POST['enfermero']));
  @$idvta    = trim(mysqli_real_escape_string($gestambu, $_POST['idvta']));
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

  /* Carga variables para tabla serinfo */
  # Si no hay datos de hora, se guarda como: 00:00:00
  $prioridad = trim(mysqli_real_escape_string($gestambu, $_POST['prioridad']));
  $demora    = trim(mysqli_real_escape_string($gestambu, $_POST['demora']));
  $tconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['tconsulta']));
  $hconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta']));


  /* Calculo dia festivo */
  @$fst = festivo($fecha, $hora);

  /* Comprobación si existe paciente - tabla paciente */

  include '../../../referencia/compNuevoPaciente.php';

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
        echo "Error: 97 " . $refcontIns . "<br>" . mysqli_error($gestambu);
      }

      for ($nS=1; $nS<=$sesiones; $nS++) {
        //Inserta en la tabla servicio
        $fecha = trim(mysqli_real_escape_string($gestambu, $_POST['fecha'.$nS.'']));
        $sqlServicio ="INSERT INTO servicio (continuado, idCia, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs)
          VALUES ('$rhResul', '$cia', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs')
          ";
        //Inserta en la tabla serinfo
        if(mysqli_query($gestambu,$sqlServicio)) {
          // Ultimo id ingresado con $gestambu
          $idInsertado = mysqli_insert_id($gestambu);

          //Inserta registro en tabla serestados
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serestados (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla serpersonal
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serpersonal (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla serinfo
          $serinfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, tconsulta, hconsulta) VALUES ('$idInsertado', '$prioridad', '$demora', '$tconsulta', '$hconsulta')";

          if(mysqli_query($gestambu, $serinfoIns)) {
            $mensa   = "Ficha insertada correctamente.";
            $mensaOk = '1';

            /* Mensajes de log */
            $obsText = "- Servicio continuado";
            $usuario = $_SESSION['userId'];
            guardarLog('5', $usuario, $obsText, $idInsertado);

          } else {
            echo "Error: 128 " . $serinfoIns . "<br>" . mysqli_error($gestambu);
          }
        } else {
          echo "Error: 132" . $sqlServicio . "<br>" . mysqli_error($gestambu);
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
        $fecha = trim(mysqli_real_escape_string($gestambu, $_POST['fecha'.$nS.'']));
        $sqlServicio = "INSERT INTO servicio (continuado, idCia, idPac, DNIPac, poliza, autorizacion, provincia, tipo, recurso, fecha, hora, delegacion, medico, enfermero, idvta, fest, nombre, apellidos, tlf1, tlf2, sexo, edad, recoger, locRec, trasladar, locTras, obs)
          VALUES ('$rhResul', '$cia', '$pacienteID', '$DNI', '$poliza', '$auto', '$prov', '$tipo', '$recurso', '$fecha', '$horaFor', '$deleg', '$medico', '$due', '$idvta', '$fst', '$nombre', '$apellidos', '$tlf1', '$tlf2', '$sexo', '$edadTab', '$recoger', '$locRec', '$trasladar', '$locTras', '$obs')
          ";
        //Inserta en la tabla serinfo
        if(mysqli_query($gestambu,$sqlServicio)) {
          // Ultimo id ingresado con $gestambu
          $idInsertado = mysqli_insert_id($gestambu);

          //Inserta registro en tabla serestados
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serestados (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla serpersonal
          $serestadoIns = mysqli_query($gestambu, "INSERT INTO serpersonal (idSv) VALUES ('$idInsertado')");
          //Inserta registro en tabla serinfo
          $serinfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, tconsulta, hconsulta) VALUES ('$idInsertado', '$prioridad', '$demora', '$tconsulta', '$hconsulta')";
          if(mysqli_query($gestambu, $serinfoIns)) {
            $mensa   = "Ficha insertada correctamente.";
            $mensaOk = '1';

            /* Mensajes de log */
            $obsText = "- Servicio continuado Asisa24h";
            $usuario = $_SESSION['userId'];
            guardarLog('5', $usuario, $obsText, $idInsertado);

          } else {
            echo "Error: 177 " . $serinfoIns . "<br>" . mysqli_error($gestambu);
          }
        } else {
          echo "Error: 180 " . $sqlServicio . "<br>" . mysqli_error($gestambu);
        }
      }
    }
}

/* Carga de datos tabla asisademanda */
$asisaID = $_GET['idemanda'];

$tabDemanda = mysqli_query($gestambu, "SELECT * FROM asisademanda WHERE idemanda = '$asisaID'");
$rwTabDem = mysqli_fetch_assoc($tabDemanda);

$codeAsisa = $rwTabDem['cod_demanda'];
$tipAsisa  = $rwTabDem['cod_servicio'];

$asisAsisa = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado,  COUNT(cod_demanda) AS numSesion FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' AND estado < 5 ");
$rwAst = mysqli_fetch_assoc($asisAsisa);

if($rwAst['numSesion'] > 1 ) {
  $serIdvta = 1;
} else {
  $serIdvta = 0;
}

$tipoAsisa = mysqli_query($gestambu, "SELECT * FROM codigoasisa WHERE codigo='$tipAsisa' ");
$rwTipAsisa = mysqli_fetch_assoc($tipoAsisa);

# Desactiva campos si es un servicio continuado de Ambulancia
if($rwTipAsisa['idRecu'] == '1') {
  $incactivo = "disabled";
} else {
  $incativo = "";
}


# Edad del paciente
$edaDatos = explode(" ", $rwTabDem['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = $edaDatos[1];

/* Datos para selección */
# Aseguradora
$cia = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom ASC
  ");

# Tipo de servicio
$tServ = mysqli_query($gestambu,
  "SELECT idServi, nomSer
  FROM servi
  ORDER BY nomSer ASC
  ");

# Tipo de recurso
$tRecu = mysqli_query($gestambu,
  "SELECT idRecu, nomRecu
  FROM recurso
  ORDER BY nomRecu ASC
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
              <h3 class="box-title">Nuevo continuado</h3>

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
                <?php } ?>
                <!-- /Mensajes -->
                  <div class="col-md-6 col-sm-6 col-xs-8 form-group">
                    <label id="valCia">Compañía: </label>
                    <select class="form-control" name="idCia" id="idCia">
                      <option value="">-- Selecciona compañía --</option>
                      <?php
                      while($rCia = mysqli_fetch_assoc($cia)) {
                        if($rCia['idCia'] == '1') {
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
                    <select class="form-control" name="provincia" id="prov">
                      <?php $provincia= substr($rwTabDem['poblacion_origen'], 0,2); ?>
                      <option value="">-- Selecciona Provincia --</option>
                      <option value="11" <?php if($provincia == '11') { echo "selected"; } ?>>Cádiz</option>
					  <option value="14" <?php if($provincia == '14') { echo "selected"; } ?>>Córdoba</option>					  
                      <option value="29" <?php if($provincia == '29') { echo "selected"; } ?>>Málaga</option>
					  <option value="52" <?php if($provincia == '52') { echo "selected"; } ?>>Melilla</option>
                      <option value="41" <?php if($provincia == '41') { echo "selected"; } ?>>Sevilla</option>
					  <option value="21" <?php if($provincia == '21') { echo "selected"; } ?>>Huelva</option>
                    </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>DNI: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="DNIPac">
                      <div class="input-group-addon">
                        <i class="fa">D</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Póliza: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Póliza" name="poliza" value="<?php echo $rwTabDem['num_poliza']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">P</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Autorización: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Autorización" name="autorizacion" value="<?php echo autoAsisa($rwTabDem['cod_demanda']); ?>">
                      <div class="input-group-addon">
                        <i class="fa">A</i>
                      </div>
                    </div>
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
                      <input type="time" class="form-control" name="hora" id="hora" value="<?php if($rwTipAsisa['idRecu'] > 1) {echo date("H:i");} ?>" <?php echo $incactivo; ?>>
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Delegación: </label>
                      <select class="form-control" name="delegacion">
                        <option value="0">-- No definida --</option>
                        <?php
                          while($rDelg = mysqli_fetch_assoc($lsDeleg)) {
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

                  <!-- Referente al tipo de servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valTipo">Tipo: </label>
                      <select class="form-control" name="tipo" id="tipo">
                        <option value="">-- Tipo de servicio --</option>
                        <?php
                          while($rServ = mysqli_fetch_assoc($tServ)) {
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
                          while($rRecu = mysqli_fetch_assoc($tRecu)) {
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
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label id="valIdavta">Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" id="idvta" value="1" <?php if($serIdvta == '1') { echo "checked"; } ?>>
                      </label>
                    </div>
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
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos" value="<?php echo $rwTabDem['apellido1']." ".$rwTabDem['apellido2']; ?>">
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
                        <option value="años" <?php if($texEdad == 'años') { echo "selected"; }?>>Años</option>
                        <option value="meses" <?php if($texEdad == 'años') { echo "selected"; }?>>Meses</option>
                        <option value="dias" <?php if($texEdad == 'años') { echo "selected"; }?>>Días</option>
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
                        <option value="0"> - Sin prioridad - </option>
                        <option value="1">P1</option>
                        <option value="2">P2</option>
                        <option value="3">P3</option>
                      </select>
                    <input type="time" class="form-control has-feedback" name="hconsulta" title="Hora a la que está citado" value="<?php if($serIdvta == 1) { echo muestraHorAsisa($rwAst['hora_asistencia']); } ?>" <?php echo $incactivo; ?>>
                    <span class="help-block h6">H. de consulta</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Demora: </label>
                    <input type="time" class="form-control" name="demora" title="demora de demora dado a la compañía" value="" disabled>
                    <input type="time" class="form-control has-feedback" name="tconsulta" title="Tiempo que estará en consulta" value="">
                    <span class="help-block h6">T. en consulta</span>
                  </div>
                  <div class="clearfix"></div>
                  <!-- Sesiones -->
                  <?php
                  $colSesion = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' AND estado < 5 AND vuelta = 'N' ");
                  $i   = 1;
                  $fch = 1;
                  $hr  = 1;
                  ?>
                  <div class="box-body no-padding">
                    <table class="table table-condensed">
                      <tbody>
                        <tr>
                          <th width="5%">Nº Sesion</th>
                          <th width="5%">Fecha Asistencia</th>
                          <th width="5%">Hora de consulta</th>
                          <th width="5%">Hora de recogida</th>
                          <th width="30%">--</th>
                        </tr>
                        <?php while($rwSesion = mysqli_fetch_array($colSesion)) { ?>
                        <tr>
                          <td>Sesión <?php echo $i++; ?></td>
                          <td><input type="date" class="form-control" name="fecha<?php echo $fch++; ?>" value="<?php echo muestraFechAsisa($rwSesion['fecha_asistencia']); ?>"></td>
                          <td><input type="time" class="form-control" name="consu<?php echo $fch++; ?>" value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>"></td>
                          <td><input type="time" class="form-control" name="hora<?php echo $hr++; ?>" value="" required></td>
                          <td></td>
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="clearfix"></div>
                  <p></p>
                  <!-- /Sesiones -->

                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="hidden" name="pauta" value="<?php echo $pauGuardar; ?>">
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
