<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Pendiente */
# Modificar nota en linea

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

# Datos para el servicio
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM paciente
  WHERE idPac = '$registro'
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
@$numEdad  = $edaDatos[0];
@$texEdad  = $edaDatos[1];

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {

  /* Carga de datos del formulario y limpieza */

  $cia       = trim(mysqli_real_escape_string($gestambu, $_POST['idCia']));
  $prov      = trim(mysqli_real_escape_string($gestambu, $_POST['provincia']));
  $DNI       = trim(mysqli_real_escape_string($gestambu, $_POST['DNIPac']));
  $poliza    = trim(mysqli_real_escape_string($gestambu, $_POST['poliza']));
  $deleg     = trim(mysqli_real_escape_string($gestambu, $_POST['delegacion']));
  $nombre    = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['nombre'])));
  $apellidos = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['apellidos'])));
  $tlf1      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf1']));
  $tlf2      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf2']));
  $sexo      = trim(mysqli_real_escape_string($gestambu, $_POST['sexo']));
  $edad      = trim(mysqli_real_escape_string($gestambu, $_POST['edad']));
  $edadTit   = trim(mysqli_real_escape_string($gestambu, $_POST['edadTit']));
  $recoger   = trim(mysqli_real_escape_string($gestambu, $_POST['recoger']));
  $locRec    = trim(mysqli_real_escape_string($gestambu, $_POST['locRec']));
  $idPac     = trim(mysqli_real_escape_string($gestambu, $_POST['idPac']));
  $edadTab   = $edad." ".$edadTit;
  @$segMed   = trim(mysqli_real_escape_string($gestambu, $_POST['segMed']));
  @$nSegMed  = trim(mysqli_real_escape_string($gestambu, $_POST['nSegMed']));
  @$fallecido= trim(mysqli_real_escape_string($gestambu, $_POST['fallecido']));
  @$duplicado= trim(mysqli_real_escape_string($gestambu, $_POST['duplicado']));
  $medAsig   = trim(mysqli_real_escape_string($gestambu, $_POST['medAsig']));
  @$numPa    = trim(mysqli_real_escape_string($gestambu, $_POST['numPa']));
  @$tiempo   = trim(mysqli_real_escape_string($gestambu, $_POST['tiempo']));
  @$tipoSeg   = trim(mysqli_real_escape_string($gestambu, $_POST['tipoSeg']));

  if(!empty($numPa) && !empty($tiempo)) {
    $pauta = $numPa."-".$tiempo;
  }

  if(empty($segMed)) {
    $segMed = '0';
  } else {
    $segMed = trim(mysqli_real_escape_string($gestambu, $_POST['segMed']));
  }

  if(empty($fallecido)) {
    $fallecido = '0';
  } else {
    $fallecido = trim(mysqli_real_escape_string($gestambu, $_POST['fallecido']));
  }

  if($nSegMed == '2') {
    $seguimiento = '2';
  } else {
    $seguimiento = $segMed;
  }
  if(@$duplicado == '2') {
	$fallecido = '2';
  } else {
	$fallecido = $fallecido;
  }

  //Cuando se retira: Paciente con seguimiento, actualiza y pone el médico a 0
  if($seguimiento == '0') {
    $medAsig = '0';
  }

  /* Acualización de registro */
  if(isset($pauta)) {
    # Actualiza la tabla servicio
    $pacienteUp = "UPDATE paciente
      SET idCia='$cia', pacDNI='$DNI', poliza='$poliza', provincia='$prov', delegacion='$deleg', pNombre='$nombre', pApellidos='$apellidos', tlf1='$tlf1',
        tlf2='$tlf2', sexo='$sexo', edad='$edadTab', direccion='$recoger', localidad='$locRec', segMed='$seguimiento', tipoSeg='$tipoSeg', fallecido='$fallecido', medAsig = '$medAsig',
        pauta='$pauta'
      WHERE idPac = '$idPac'
      ";

    if(mysqli_query($gestambu,$pacienteUp)) {
      $mensa   = "Ficha actualizada correctamente";
      $mensaOk = '1';
	  # Actualiza todos los servicios referentes al paciente: Póliza, Delegación, Edad, Sexo, Nombre ?? y Apellidos ?? (COMPROBAR-060218) 
	  $serviUpPac = mysqli_query($gestambu, "UPDATE servicio SET DNIpac='$DNI', poliza='$poliza', delegacion='$deleg', sexo='$sexo', edad='$edadTab', nombre='$nombre', apellidos='$apellidos'
		  WHERE idPac = '$idPac'
	  ");	  
    } else {
      echo "Error: " . $pacienteUp . "<br>" . mysqli_error($gestambu);
    }
  } else {
    # Actualiza la tabla servicio
    $pacienteUp = "UPDATE paciente
      SET idCia='$cia', pacDNI='$DNI', poliza='$poliza', provincia='$prov', delegacion='$deleg', pNombre='$nombre', pApellidos='$apellidos', tlf1='$tlf1',
        tlf2='$tlf2', sexo='$sexo', edad='$edadTab', direccion='$recoger', localidad='$locRec', segMed='$seguimiento', tipoSeg='$tipoSeg', fallecido='$fallecido', medAsig = '$medAsig'
      WHERE idPac = '$idPac'
      ";

    if(mysqli_query($gestambu,$pacienteUp)) {
      $mensa   = "Ficha actualizada correctamente";
      $mensaOk = '1';
	  # Actualiza todos los servicios referentes al paciente: Póliza, Delegación, Edad, Sexo, Nombre ?? y Apellidos ?? (COMPROBAR-060218) 
	  $serviUpPac = mysqli_query($gestambu, "UPDATE servicio SET DNIpac='$DNI', poliza='$poliza', delegacion='$deleg', sexo='$sexo', edad='$edadTab', nombre='$nombre', apellidos='$apellidos'
		  WHERE idPac = '$idPac'
	  ");	  
    } else {
      echo "Error: " . $pacienteUp . "<br>" . mysqli_error($gestambu);
    }
  }

} // .fin de actualizar

/* Recarga los datos con las nuevas actualizaciones */

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

# Datos de ficha de paciente
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM paciente
  WHERE idPac = '$registro'
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
@$numEdad  = $edaDatos[0];
@$texEdad  = $edaDatos[1];

# Datos para pauta
if(!empty($rwPacMos['pauta'])) {
  $pautaR = explode("-", $rwPacMos['pauta']);
  $numR = $pautaR[0];
  $tmpR = $pautaR[1];
}

/* Datos para selección */
# Aseguradora
$cia = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom DESC
  ");

# Listado para delegaciones
$lsDeleg = mysqli_query($gestambu,"SELECT *
FROM provincias
ORDER BY provincia ASC
");

#listado de médicos
$lsMed = mysqli_query($gestambu, "SELECT userId, usNom, usApe, usCate
  FROM user
  WHERE usCate = '7'
  ORDER BY usNom ASC
  ");

# Datos para pestaña Notas de paciente
$lsNotaPac = mysqli_query($gestambu, "SELECT * FROM pacnota WHERE idPac = '$registro' ORDER BY creaNota DESC ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Ficha de paciente | H.C. <?php echo $rwPacMos['idPac']; ?></title>
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
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Historial:
        <small><?php echo $rwPacMos['idPac']; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="/ops/mostrar/paciente.php?idPac=<?php echo $rwPacMos['idPac']; ?>">Paciente</a></li>
        <li class="active">Ficha de paciente</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">
                Ficha (<?php echo $rwPacMos['idPac']; ?>):  <?php echo $rwPacMos['pNombre']." ".$rwPacMos['pApellidos']; ?>
              </h3>

              <div class="box-tools pull-right">
                <!-- button with a dropdown -->
                <div class="btn-group">
                  <?php include('../referencia/modals/notaPac.php'); ?>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" title="Opciones">
                    <i class="fa fa-bars"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwPacMos['idPac']; ?>&selRec=1"><i class="fa fa-ambulance"></i>Crear ambulancia</a></li>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwPacMos['idPac']; ?>&selRec=3"><i class="fa fa-heartbeat"></i>Crear U.V.I.</a></li>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwPacMos['idPac']; ?>&selRec=2"><i class="fa fa-eyedropper"></i>Crear enfermería</a></li>
                    <li><a href="/ops/referencia/crear/vincuPac.php?iden=<?php echo $rwPacMos['idPac']; ?>&selRec=4"><i class="fa fa-stethoscope"></i>Crear médico</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="fa fa-calendar"></i>Crear continuado</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="fa fa-file-text-o"></i>Agregar Nota</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Eliminar">
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
                    <select class="form-control" name="provincia" id="prov" required="">
                      <option value="">-- Selecciona Provincia --</option>
                      <option value="29" <?php if($rwPacMos['provincia'] == '29') {echo "selected=\"selected\""; } ?>>Málaga</option>
					  <option value="52" <?php if($rwPacMos['provincia'] == '52') {echo "selected=\"selected\""; } ?>>Melilla</option>
                      <option value="41" <?php if($rwPacMos['provincia'] == '41') {echo "selected=\"selected\""; } ?>>Sevilla</option>
                      <option value="11" <?php if($rwPacMos['provincia'] == '11') {echo "selected=\"selected\""; } ?>>Cádiz</option>
					  <option value="14" <?php if($rwPacMos['provincia'] == '14') {echo "selected=\"selected\""; } ?>>Córdoba</option>
					  <option value="21" <?php if($rwPacMos['provincia'] == '21') {echo "selected=\"selected\""; } ?>>Huelva</option>
                    </select>
                  </div>
                  <div class="clearfix"></div>
                  <?php if($rwPacMos['segMed'] == 1) { ?>
                  <!-- Para paciente con seguimiento médico -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Pauta: </label>
                    <input type="number" class="form-control localidad" name="numPa" value="<?php if(isset($numR)) { echo $numR; } ?>" placeholder="Sólo son válidos números">
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label"> -- </label>
                    <select class="form-control" name="tiempo">
                      <option value="">-- Selecciona tiempo --</option>
                      <option value="1" <?php if(isset($tmpR) && $tmpR == '1') {echo "selected=\"selected\""; } ?>> Días</option>
                      <option value="2" <?php if(isset($tmpR) && $tmpR == '2') {echo "selected=\"selected\""; } ?>> Semanas</option>
                      <option value="3" <?php if(isset($tmpR) && $tmpR == '3') {echo "selected=\"selected\""; } ?>> Meses</option>
                    </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label"> Tipo: </label>
                    <select class="form-control" name="tipoSeg">
                      <option value="">-- No definido --</option>
                      <option value="1" <?php if($rwPacMos['tipoSeg'] == '1') {echo "selected=\"selected\""; } ?>> Crónico</option>
                      <option value="2" <?php if($rwPacMos['tipoSeg'] == '2') {echo "selected=\"selected\""; } ?>> Paliativo</option>
                    </select>
                  </div>
                  <div class="clearfix"></div>
                  <!-- /. pauta -->
                  <?php } ?>

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>DNI: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="DNIPac" value="<?php echo $rwPacMos['pacDNI']; ?>">
                      <div class="input-group-addon">
                        <i class="fa"> D</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Póliza: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Póliza" name="poliza" value="<?php echo $rwPacMos['poliza']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">P</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
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

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" value="<?php echo $rwPacMos['pNombre']; ?>">
                      <input type="hidden" name="idPac" value="<?php echo $rwPacMos['idPac']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos"  value="<?php echo $rwPacMos['pApellidos']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 1" name="tlf1"  value="<?php echo $rwPacMos['tlf1']; ?>">
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
                    <input type="text" class="form-control" placeholder="Edad" name="edad" value="<?php echo $numEdad; ?>" >
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
                    <label class="control-label" id="valRecoger">Dirección: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="recoger" id="recoger" value="<?php echo $rwPacMos['direccion']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-home"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locRec" value="<?php echo $rwPacMos['localidad']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>

                  <!-- Personal de servicio -->
                  <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> Notas de paciente </a></li>
                        <li><a href="#tab_2" data-toggle="tab"> Seguimiento Médico </a></li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                          <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                              <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Usuario</th>
                                <th>Descripción</th>
                              </tr>
                              <?php while($rwNota = mysqli_fetch_array($lsNotaPac)) {
                                      $tmpGuarda = explode(" ", $rwNota['creaNota']);
                                      $fechaG = $tmpGuarda[0];
                                      $horaG  = $tmpGuarda[1];
                                      $fechaForm = cambiarFecha($fechaG);
                              ?>
                              <tr>
                                <td><?php echo $fechaForm; ?></td>
                                <td><?php echo $horaG; ?></td>
                                <td><?php echo $rwNota['userId']; ?></td>
                                <td><?php echo $rwNota['descNota']; ?></td>
                              </tr>
                              <?php } ?>
                            </table>
                          </div>
                        </div>					  
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                          <div class="checkbox">
                            <label><input type="checkbox" name="segMed" <?php if($rwPacMos['segMed'] == '1') { echo "checked"; } elseif($rwPacMos['segMed'] == '2') { echo "checked"; } ?> value="1">Paciente con seguimiento</label>
                          </div>
                          <div class="checkbox">
                            <label><input type="checkbox" name="nSegMed" <?php if($rwPacMos['segMed'] == '2') { echo "checked"; } ?> value="2">No es necesario seguimiento médico</label>
                          </div>
                          <div class="checkbox">
                            <label><input type="checkbox" name="fallecido" <?php if($rwPacMos['fallecido'] == '1') { echo "checked"; } ?> value="1">Fallecido</label>
                          </div>
                          <div class="checkbox">
                            <label><input type="checkbox" name="duplicado" <?php if($rwPacMos['fallecido'] == '2') { echo "checked"; } ?> value="2">Duplicado</label>
                          </div>						  
                          <div class="form-group">
                            <label class="control-label">Médico Asignado</label>
                            <select class="form-control" name="medAsig">
                              <option value="0">-- Sin médico asignado --</option>
                              <?php
                                while($rLsMed = mysqli_fetch_assoc($lsMed)) {
                                  if($rwPacMos['medAsig'] == $rLsMed['userId']) {
                                    $seleccion = "selected";
                                  } else {
                                    $seleccion = "";
                                  }
                                  echo "<option value='".$rLsMed['userId']."' ".$seleccion.">".$rLsMed['usNom']." ".$rLsMed['usApe']."</option>\n";
                                }
                               ?>
                            </select>
                          </div>
                        </div>
                        <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                  </div>
                  <!-- / Personal de servicio -->
                  <div class="clearfix"></div>
                  <!-- col-md-offset-3 -->
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <button type="reset" class="btn btn-primary">Cancelar</button>
                      <button type="submit" name="guardar" value="enviar" class="btn btn-success">Actualizar</button>
                    </div>
                  </div>
                </div>
              <div class="col-md-2"></div>
              </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            <span class="h6"><?php echo $_SESSION['usNom']." >> ".$_SESSION['userId']." >> "; echo fechaEs(); echo " ".date("H:i:s"); ?> - <!-- Con incidencias --> -</span>
            </div>
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

<?php include '../inc/pie.php'; ?>

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
<script src="/ops/js/inserNota.js"></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
