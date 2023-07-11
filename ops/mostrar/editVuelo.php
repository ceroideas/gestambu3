<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Pendiente */
# Mostrar mensaje al actualizar datos

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

# Datos para el servicio
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM vuelosanitario
  WHERE idVuelo = '$registro'
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

# Datos de referencia
$vueloRef = mysqli_query($gestambu, "SELECT *
  FROM vueloref
  WHERE idVuelo = '$registro'
  ");
$rwRef = mysqli_fetch_assoc($vueloRef);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = $edaDatos[1];

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {
  //PEndiente guardado de mostrar ficha

  /* Carga de datos del formulario y limpieza */

  $cia       = trim(mysqli_real_escape_string($gestambu, $_POST['idCia']));
  $dni       = trim(mysqli_real_escape_string($gestambu, $_POST['dni']));
  $tipo      = trim(mysqli_real_escape_string($gestambu, $_POST['tipo']));
  $comp      = trim(mysqli_real_escape_string($gestambu, $_POST['comp']));
  $hc        = trim(mysqli_real_escape_string($gestambu, $_POST['hc']));
  $fecha     = trim(mysqli_real_escape_string($gestambu, $_POST['fecha']));
  $hora      = trim(mysqli_real_escape_string($gestambu, $_POST['hora']));
  $sexo      = trim(mysqli_real_escape_string($gestambu, $_POST['sexo']));
  $edad      = trim(mysqli_real_escape_string($gestambu, $_POST['edad']));
  $edadTit   = trim(mysqli_real_escape_string($gestambu, $_POST['edadTit']));
  @$medico   = trim(mysqli_real_escape_string($gestambu, $_POST['medico']));
  @$due      = trim(mysqli_real_escape_string($gestambu, $_POST['due']));
  @$idvta    = trim(mysqli_real_escape_string($gestambu, $_POST['idvta']));
  @$pediatra = trim(mysqli_real_escape_string($gestambu, $_POST['pediatra']));
  @$incub    = trim(mysqli_real_escape_string($gestambu, $_POST['incubadora']));
  $nombre    = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['nombre'])));
  $apellidos = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['apellidos'])));
  $acomp     = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['acomp'])));
  $dniacomp  = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['dniacomp'])));
  $recoger   = trim(mysqli_real_escape_string($gestambu, $_POST['recoger']));
  $locRec    = trim(mysqli_real_escape_string($gestambu, $_POST['locRec']));
  $trasladar = trim(mysqli_real_escape_string($gestambu, $_POST['trasladar']));
  $locTras   = trim(mysqli_real_escape_string($gestambu, $_POST['locTras']));
  $obs       = trim(mysqli_real_escape_string($gestambu, $_POST['obs']));
  $precioAmb = trim(mysqli_real_escape_string($gestambu, $_POST['precioAmb']));
  $hpeti     = trim(mysqli_real_escape_string($gestambu, $_POST['hpeti']));
  $edadTab   = $edad." ".$edadTit;
  $identi    = $rwPacMos['idVuelo'];
  $estVuelo  = trim(mysqli_real_escape_string($gestambu, $_POST['estVuelo']));
  $numVuelo  = trim(mysqli_real_escape_string($gestambu, $_POST['numVuelo']));
  /* carga de datos referente al personal */
  $estVuelo   = trim(mysqli_real_escape_string($gestambu, $_POST['estVuelo']));
  $medV      = trim(mysqli_real_escape_string($gestambu, $_POST['medV']));
  $dueV      = trim(mysqli_real_escape_string($gestambu, $_POST['dueV']));
  $pedV      = trim(mysqli_real_escape_string($gestambu, $_POST['pedV']));
  $locSalida = trim(mysqli_real_escape_string($gestambu, $_POST['locSalida']));
  $hSalida   = trim(mysqli_real_escape_string($gestambu, $_POST['hSalida']));
  $locLlega  = trim(mysqli_real_escape_string($gestambu, $_POST['locLlega']));
  $hLlega    = trim(mysqli_real_escape_string($gestambu, $_POST['hLlega']));
  $locVuelta = trim(mysqli_real_escape_string($gestambu, $_POST['locVuelta']));
  $hVuelta   = trim(mysqli_real_escape_string($gestambu, $_POST['hVuelta']));
  $locLlega2 = trim(mysqli_real_escape_string($gestambu, $_POST['locLlega2']));
  $hVuelta2  = trim(mysqli_real_escape_string($gestambu, $_POST['hLlega2']));

  /* Arreglo para horas */
  /*
  if(strlen($hora) == '8') {
    $horaFor = $hora;
  } elseif(strlen($hora) == '5') {
    $horaFor = $hora.":00";
  }

  if(strlen($hpeti) == '8') {
    $hpetiFor = $hpeti;
  } elseif(strlen($hpeti) == '5') {
    $hpetiFor = $hpeti.":00";
  }
*/

  /* Acualización de registro */
  # Actualiza la tabla servicio
  $vuelosanitarioUp = "UPDATE vuelosanitario
    SET idCia='$cia', dni='$dni', tipo='$tipo', comp='$comp', hc='$hc', fecha='$fecha', hora='".arregloHora($hora)."', sexo='$sexo', edad='$edadTab', medico='$medico', due='$due', idvta='$idvta', pediatra='$pediatra', incub='$incub',
    nombre='$nombre', apellidos='$apellidos', recoger='$recoger', locRec='$locRec', trasladar='$trasladar', locTras='$locTras', obs='$obs', precioAmb='$precioAmb', hpeti='".arregloHora($hpeti)."', estVuelo='$estVuelo', numVuelo='$numVuelo',
	acomp='$acomp', dniacomp='$dniacomp'
    WHERE idVuelo = '$identi'
    ";

  /* Ingreso de registros en tablas secundarias */
  if(mysqli_query($gestambu,$vuelosanitarioUp)) {
    //Actualiza la tabla vueloRef
    $vueloRefUp = "UPDATE vueloref
      SET estVuelo='$estVuelo', medico='$medV', due='$dueV', pediatra='$pedV', locSalida='$locSalida', hSalida='".arregloHora($hSalida)."', locLlegada='$locLlega', hLlegada='".arregloHora($hLlega)."', locVuelta='$locVuelta',
      hVuelta='".arregloHora($hVuelta)."', locLlegada2='$locLlega2', hLlegada2='".arregloHora($hVuelta2)."'
      WHERE idVuelo = '$identi'
      ";
    if(mysqli_query($gestambu, $vueloRefUp)) {
      $msj = "Actualizada tabla: vuelosanitario y vueloref";
    } else {
      echo "Error: " . $vueloRefUp . "<br/>" . mysqli_error($gestambu);
    }
  } else {
    echo "Error: " . $vuelosanitarioUp . "<br/>" . mysqli_error($gestambu);
  }

/* --> fin de acualizacion de tablas secundarias  */

/* Recarga los datos con las nuevas actualizaciones */

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

# Datos para el servicio
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM vuelosanitario
  WHERE idVuelo = '$registro'
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

# Datos de referencia
$vueloRef = mysqli_query($gestambu, "SELECT *
  FROM vueloref
  WHERE idVuelo = '$registro'
  ");
$rwRef = mysqli_fetch_assoc($vueloRef);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = $edaDatos[1];


} // .fin de actualizar

/* Datos para selección */
# Aseguradora
$cia = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom DESC
  ");

# Listado para estados con vuelta
$estCV = mysqli_query($gestambu, "SELECT idEst, vaEst
  FROM estados
  WHERE idEst IN('1','11','14','15')
  ORDER BY vaEst ASC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Mostrar Vuelo Sanitario</title>
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
  .sinmar {
    margin: 1px;
    padding-top: 3px;
    padding-bottom: 3px;
  }
  .table>tbody>tr>td {
    padding: 1px;
  }
  .aumText {
    font-size: 0.94em;
  }
  .box-body {
      font-size: 11px;
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
  .col-xs-3, .col-xs-2 {
    padding-right: 10px;
    padding-left: 10px;
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
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Servicio id:
        <small><?php echo $rwPacMos['idVuelo']; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Ficha de servicio</li>
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
                Vuelo: ID - <?php echo $rwPacMos['idVuelo']; ?> <?php if(isset($msj)) { echo " >>>> ".$msj; } ?>
              </h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form class="form-vertical form-label-left" action="" method="post">
                <!-- Compañia / provincia -->
                <div class="col-md-2"></div>
                <div class="col-md-8">
                  <div class="col-md-8 col-sm-8 col-xs-8 form-group">
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
                  <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                    <label>DNI: </label>
                    <input type="text" class="form-control" placeholder="DNI" name="dni" value="<?php echo $rwPacMos['dni']; ?>">
                  </div>

                  <div class="clearfix"></div>

                  <!-- Datos referentes al vuelo -->
                  <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                    <label>Tipo: </label>
                    <select class="form-control" name="tipo" required="">
                      <option value="">-- Tipo de servicio --</option>
                      <option value="1" <?php if($rwPacMos['tipo'] == 1) { echo "selected"; } ?>>Convencional</option>
                      <option value="2" <?php if($rwPacMos['tipo'] == 2) { echo "selected"; } ?>>Crítico</option>
                      <option value="3" <?php if($rwPacMos['tipo'] == 3) { echo "selected"; } ?>>Retorno</option>
					  <option value="4" <?php if($rwPacMos['tipo'] == 4) { echo "selected"; } ?>>Trasplante</option>
                    </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Comp.: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Aseguradora" name="comp" value="<?php echo $rwPacMos['comp']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">C</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Nº Historia: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Número de historial" name="hc" value="<?php echo $rwPacMos['hc']; ?>">
                      <div class="input-group-addon">
                        <i class="fa">HC</i>
                      </div>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-3">
                    <label>Fecha: </label>
                      <input type="date" class="form-control" name="fecha" value="<?php echo $rwPacMos['fecha']; ?>" required="">
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-3">
                    <label>Hora: </label>
                      <input type="time" class="form-control" name="hora" value="<?php echo $rwPacMos['hora']; ?>" required="">
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Sexo: </label>
                      <select class="form-control" name="sexo">
                        <option value="">---</option>
                        <option value="H" <?php if($rwPacMos['sexo'] == 'H') { echo "selected"; } ?>>H</option>
                        <option value="M" <?php if($rwPacMos['sexo'] == 'M') { echo "selected"; } ?>>M</option>
                      </select>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Edad: </label>
                    <input type="text" class="form-control" placeholder="Edad" name="edad" value="<?php echo $numEdad; ?>" >
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> -- </label>
                      <select class="form-control" name="edadTit">
                        <option value="años" <?php if($texEdad == 'años') { echo "selected"; } ?>>Años</option>
                        <option value="meses" <?php if($texEdad == 'meses') { echo "selected"; } ?>>Meses</option>
                        <option value="dias" <?php if($texEdad == 'dias') { echo "selected"; } ?>>Días</option>
                      </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al tipo de servicio -->
                  <div class="form-group col-md-6 col-sm-6 col-xs-6">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <labe>Médico</label>
                      <input class="col-md-8" type="checkbox" class="minimal" name="medico" value="1" <?php if($rwPacMos['medico'] == 1) { echo "checked"; } ?>>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Due</label>
                      <input class="col-md-8" type="checkbox" class="minimal" name="due" value="1" <?php if($rwPacMos['due'] == 1) { echo "checked"; } ?>>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Ida/vta</label>
                      <input class="col-md-8" type="checkbox" class="minimal" name="idvta" value="1" <?php if($rwPacMos['idvta'] == 1) { echo "checked"; } ?>>
                    </div>
                  </div>
                  <div class="form-group col-md-6 col-sm-6 col-xs-6">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Pediatra</label>
                      <input class="col-md-8" type="checkbox" class="minimal" name="pediatra" value="1" <?php if($rwPacMos['pediatra'] == 1) { echo "checked"; } ?> >
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                      <label>Incubadora</label>
                      <input class="col-md-8" type="checkbox" class="minimal" name="incubadora" value="1" <?php if($rwPacMos['incub'] == 1) { echo "checked"; } ?>>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required value="<?php echo $rwPacMos['nombre']; ?>">
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

                  <div class="clearfix"></div>
                  <!-- Referente a dónde se realiza el servicio -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label" id="valRecoger">Recoger: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="recoger" value="<?php echo $rwPacMos['recoger']; ?>">
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
                    <label class="control-label">Amb.externa: </label>
                    <input type="text" class="form-control" name="precioAmb" value="<?php echo $rwPacMos['precioAmb']; ?>">
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Hora de petición: </label>
                    <input type="time" class="form-control" name="hpeti" value="<?php echo $rwPacMos['hpeti']; ?>">
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <input type="text" class="form-control" name="numVuelo" value="<?php echo $rwPacMos['numVuelo']; ?>" placeholder="Número de vuelo">
                  </div>
                  <div class="clearfix"></div>

                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Acompañante: </label>
                    <input type="text" class="form-control" name="acomp" value="<?php echo $rwPacMos['acomp']; ?>">
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">DNI: </label>
                    <input type="text" class="form-control" name="dniacomp" value="<?php echo $rwPacMos['dniacomp']; ?>" >
                  </div>
				  
                  <div class="clearfix"></div>
                  <!-- Personal de servicio -->
                  <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> Realizado </a></li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                          <div class="col-md-6 col-sm-16 col-xs-6 input-group-sm">
                            <label class="control-label"> Estado general </label>
                              <select class="form-control" name="estVuelo" required="">
                                <option value=""> - Estado - </option>
                                <?php
                                  while($conVuelta = mysqli_fetch_assoc($estCV)) {
                                    if($rwRef['estVuelo'] == $conVuelta['idEst']) {
                                      $seleccion = "selected";
                                    } else {
                                      $seleccion = "";
                                    }
                                    echo "<option value='".$conVuelta['idEst']."' ".$seleccion.">".$conVuelta['vaEst']."</option>\n";
                                  }
                                 ?>
                              </select>
                          </div>
                          <div class="clearfix"></div>

                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Médico: </label>
                            <input type="text" class="form-control automed" name="medV" value="<?php echo $rwRef['medico']; ?>">
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Enfermero: </label>
                            <input type="text" class="form-control autodue input-group-sm" name="dueV" value="<?php echo $rwRef['due']; ?>">
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6 input-group-sm">
                            <label class="control-label">Pediatra: </label>
                            <input type="text" class="form-control input-group-sm" name="pedV" value="<?php echo $rwRef['pediatra']; ?>">
                          </div>

                          <div class="clearfix"></div>

                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">Loc.Salida: </label>
                            <input type="text" class="form-control input-group-sm" name="locSalida" value="<?php echo $rwRef['locSalida']; ?>">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">H.Salida: </label>
                            <input type="time" class="form-control input-group-sm" name="hSalida" value="<?php echo sinHora($rwRef['hSalida']); ?>">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">Loc.Llegada: </label>
                            <input type="text" class="form-control input-group-sm" name="locLlega" value="<?php echo $rwRef['locLlegada']; ?>">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">H.Llegada: </label>
                            <input type="time" class="form-control input-group-sm" name="hLlega" value="<?php echo sinHora($rwRef['hLlegada']); ?>">
                          </div>

                          <div class="clearfix"></div>

                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">Loc.Vuelta: </label>
                            <input type="text" class="form-control input-group-sm" name="locVuelta" value="<?php echo $rwRef['locVuelta']; ?>">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">H.Vuelta: </label>
                            <input type="time" class="form-control input-group-sm" name="hVuelta" value="<?php echo sinHora($rwRef['hVuelta']); ?>">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">Loc.Llegada: </label>
                            <input type="text" class="form-control input-group-sm" name="locLlega2" value="<?php echo $rwRef['locLlegada2']; ?>">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label">H.Llegada: </label>
                            <input type="time" class="form-control input-group-sm" name="hLlega2" value="<?php echo sinHora($rwRef['hLlegada2']); ?>">
                          </div>

                          <div class="clearfix"></div>

                        </div>
                      </div>
                      <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                  </div>
                  <!-- / Personal de servicio -->

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
	$(".autotec").autocomplete({
    minLength: 3,
		source: '/ops/referencia/autotrabj/autotec.php'
	});
  $(".autodue").autocomplete({
    minLength: 3,
		source: '/ops/referencia/autotrabj/autodue.php'
	});
  $(".automed").autocomplete({
    minLength: 3,
		source: '/ops/referencia/autotrabj/automed.php'
	});
  $(".autoayu").autocomplete({
    minLength: 3,
		source: '/ops/referencia/autotrabj/autoayu.php'
	});
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
