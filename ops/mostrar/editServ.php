<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Pendiente */
# Validación para los campos Prioridad y Demora
# cambiar tamaño input

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

# Datos para el servicio
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM servicio
  WHERE idSv = '$registro'
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

$serpersoMostrar = mysqli_query($gestambu, "SELECT *
  FROM serpersonal
  WHERE idSv = '$registro'
  ");
$rwSerperMos = mysqli_fetch_assoc($serpersoMostrar);

$serestadosMostrar = mysqli_query($gestambu, "SELECT *
  FROM serestados
  WHERE idSv = '$registro'
  ");
$rwSerestadosMos = mysqli_fetch_assoc($serestadosMostrar);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = $edaDatos[1];

# Tabla serinfo
$serinfoMos = mysqli_query($gestambu, "SELECT *
  FROM serinfo
  WHERE idSv = '$registro'
  ");
$rwSerinfoMos = mysqli_fetch_assoc($serinfoMos);

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
  $edadTab   = $edad." ".$edadTit;
  $idPac     = trim(mysqli_real_escape_string($gestambu, $_POST['idPac']));
  $identi    = $rwPacMos['idSv'];
  $anterior  = trim(mysqli_real_escape_string($gestambu, $_POST['estAnterior']));

  /* carga de datos referente al personal y vehículos */
  $estServ   = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['estServ'])));
  $vhIda     = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['vhIda'])));
  $vhVta     = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['vhVta'])));
  $tecIda    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['tecIda'])));
  $aydIda    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['aydIda'])));
  $dueIda    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['dueIda'])));
  $medIda    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['medIda'])));
  $tecVta    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['tecVta'])));
  $aydVta    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['aydVta'])));
  $dueVta    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['dueVta'])));
  $medVta    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['medVta'])));

  /* Carga de datos referente a tabla serinfo */
  $prioridad = trim(mysqli_real_escape_string($gestambu, $_POST['prioridad']));
  $demora    = trim(mysqli_real_escape_string($gestambu, $_POST['demora']));
  $hvuelta   = trim(mysqli_real_escape_string($gestambu, $_POST['hvuelta']));
  $hconsulta = trim(mysqli_real_escape_string($gestambu, $_POST['hconsulta']));

  /* Carga datos especiales */
  @$oxigeno  = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['ox'])));
  @$rampa    = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['rampa'])));
  @$dostec   = valorVacio(trim(mysqli_real_escape_string($gestambu, $_POST['dostec'])));

  /* Comprobar id de paciente */
  include '../referencia/compNuevoPaciente.php';

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
  /* Cambio de estado al cambiar ida y vuelta */
  # si cambia de id/vta a otro estado lo pone como pendiente - igual con los demas cambios
  # pdt. comprobar esta; si es adj. cambiar a los estados correspondientes
  $valAnt = explode(" ", $anterior);
  @$idvtAnt = $valAnt[0];
  @$estAnt = $valAnt[1];

  if($idvta == $idvtAnt) {
    $estServ = $estServ;
  } elseif(empty($idvta) && empty($estAnt)) {
    $estServ = $estServ;
  } else {
    $estServ = 1;
  }

  /* Calculo dia festivo */
  $fst = festivo($fecha, $hora);
  /* Modificación de horario para tipo extracción (22)*/
  if($tipo == 22) { $horaFor = "07:00:00"; }

  /* Acualización de registro */
  if(empty($pacienteID)) {
    # Actualiza la tabla servicio
    $servicioUp = "UPDATE servicio
      SET idCia='$cia', idPac=NULL, DNIPac='$DNI', poliza='$poliza', autorizacion='$auto', provincia='$prov', tipo='$tipo', recurso='$recurso', fecha='$fecha', hora='$hora',
        delegacion='$deleg', medico='$medico', enfermero='$due', idvta='$idvta', fest='$fst', nombre='$nombre', apellidos='$apellidos', tlf1='$tlf1',
        tlf2='$tlf2', sexo='$sexo', edad='$edadTab', recoger='$recoger', locRec='$locRec', trasladar='$trasladar', locTras='$locTras', obs='$obs', estServ='$estServ'
      WHERE idSv = '$identi'
      ";
  } else {
    if($pacienteID == $idPac) {
      $servicioUp = "UPDATE servicio
        SET idCia='$cia', idPac='$idPac', DNIPac='$DNI', poliza='$poliza', autorizacion='$auto', provincia='$prov', tipo='$tipo', recurso='$recurso', fecha='$fecha', hora='$hora',
          delegacion='$deleg', medico='$medico', enfermero='$due', idvta='$idvta', fest='$fst', nombre='$nombre', apellidos='$apellidos', tlf1='$tlf1',
          tlf2='$tlf2', sexo='$sexo', edad='$edadTab', recoger='$recoger', locRec='$locRec', trasladar='$trasladar', locTras='$locTras', obs='$obs', estServ='$estServ'
        WHERE idSv = '$identi'
        ";
    } else {
      $servicioUp = "UPDATE servicio
        SET idCia='$cia', idPac='$pacienteID', DNIPac='$DNI', poliza='$poliza', autorizacion='$auto', provincia='$prov', tipo='$tipo', recurso='$recurso', fecha='$fecha', hora='$hora',
          delegacion='$deleg', medico='$medico', enfermero='$due', idvta='$idvta', fest='$fst', nombre='$nombre', apellidos='$apellidos', tlf1='$tlf1',
          tlf2='$tlf2', sexo='$sexo', edad='$edadTab', recoger='$recoger', locRec='$locRec', trasladar='$trasladar', locTras='$locTras', obs='$obs', estServ='$estServ'
        WHERE idSv = '$identi'
        ";
    }
  }

  /* Ingreso de registros en tablas secundarias */

  # ·· serpersonal ·· #

  # Comprueba si existe registro en tabla serpersonal
  $compSerpersonal = mysqli_query($gestambu, "SELECT idSv FROM serpersonal WHERE idSv = '$identi' ");
  $numCompSerp     = mysqli_num_rows($compSerpersonal);

  if($numCompSerp == 1 ) {
    //Actualiza el registro tabla serpersonal
    $serPersonalUp = "UPDATE serpersonal
      SET tecIda='$tecIda', aydIda='$aydIda', dueIda='$dueIda', medIda='$medIda', tecVta='$tecVta', aydVta='$aydVta', dueVta='$dueVta', medVta='$medVta'
      WHERE idSv='$identi'
      ";
      if(mysqli_query($gestambu,$serPersonalUp)) {
        //echo "Tabla serpersonal acutalizada<br/>";
      } else {
        echo "Error: " . $serPersonalUp . "<br/>" . mysqli_error($gestambu);
      }
  } elseif($numCompSerp == 0 ) {
    //Crea un nuevo registro
    $serPersonalIns = "INSERT INTO serpersonal (idSv, tecIda, aydIda, dueIda, medIda, tecVta, aydVta, dueVta, medVta)
      VALUES ('$identi', '$tecIda', '$aydIda', '$dueIda', '$medIda', '$tecVta', '$aydVta', '$dueVta', '$medVta')";

    if(mysqli_query($gestambu,$serPersonalIns)) {
      //echo "Creado registro en tabla personal<br />";
    } else {
      echo "Error: " . $serPersonalIns . "<br/>" . mysqli_error($gestambu);
    }
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
        echo "Error: " . $serinfoUp . "<br/>" . mysqli_error($gestambu);
      }
  } else {
    //Crea un nuevo registro
    $serInfoIns = "INSERT INTO serinfo (idSv, prioridad, demora, tconsulta, hconsulta)
      VALUES ('$identi', '$prioridad', '$demora', '$tconsulta', '$hconsulta')";

    if(mysqli_query($gestambu,$serInfoIns)) {
      //echo "Creado registro en tabla serinfo<br />";
    } else {
      echo "Error:  " . $serInfoIns . "<br/>" . mysqli_error($gestambu);
    }
  }

  # ·· serestados ·· #
  # Comprueba si existe registro en tabla serestados

  $compSerestados = mysqli_query($gestambu, "SELECT idSv FROM serestados WHERE idSv = '$identi' ");
  $numSerestados  = mysqli_num_rows($compSerestados);

  if($numSerestados == 1 ) {
    //Actualiza el registro
    $serestadosUp = "UPDATE serestados
      SET vhIda='$vhIda', vhVta='$vhVta'
      WHERE idSv='$identi'
      ";
      if(mysqli_query($gestambu,$serestadosUp)) {
        //echo "Tabla serestados acutalizada<br\>";
      } else {
        echo "Error: " . $serestadosUp . "<\br>" . mysqli_error($gestambu);
      }
  } elseif($numSerestados == 0 ) {
    //Crea un nuevo registro
    $serestadosIns = "INSERT INTO serestados (idSv, vhIda, vhVta)
      VALUES ('$identi', '$vhIda', '$vhVta')";

    if(mysqli_query($gestambu,$serestadosIns)) {
      //echo "Creado registro en tabla serestados<br\>";
    } else {
      echo "Error: " . $serestadosIns . "<\br>" . mysqli_error($gestambu);
    }
  }

  # ·· especial ·· #
  # Comprueba si existe registro en tabla especial

  $compEspecial = mysqli_query($gestambu, "SELECT idSv FROM especial WHERE idSv = '$identi' ");
  $numEspecial  = mysqli_num_rows($compEspecial);

  if($numEspecial == 1 ) {
    //Actualiza el registro
	$especialUp = "UPDATE especial
      SET ox='$oxigeno', rampa='$rampa', dTec='$dostec'
      WHERE idSv='$identi'
      ";
      if(mysqli_query($gestambu,$especialUp)) {
        //echo "Tabla serestados acutalizada<br\>";
      } else {
        echo "Error: " . $especialUp . "<\br>" . mysqli_error($gestambu);
      }
  } elseif($numEspecial == 0 ) {
    //Crea un nuevo registro
    $especialIns = "INSERT INTO especial (idSv, ox, rampa, dTec)
      VALUES ('$identi', '$oxigeno', '$rampa', '$dostec')";

    if(mysqli_query($gestambu,$especialIns)) {
      //echo "Creado registro en tabla serestados<br\>";
    } else {
      echo "Error: " . $especialIns . "<\br>" . mysqli_error($gestambu);
    }
  }

  if(mysqli_query($gestambu,$servicioUp)) {
    $mensa   = "Ficha actualizada correctamente";
    $mensaOk = '1';

    /* Mensajes de log */
    $obsText = "mediante ficha";
    $usuario = $_SESSION['userId'];
    $servicioID = $identi;
    guardarLog('3', $usuario, $obsText, $servicioID);
  } else {
    echo "Error: " . $servicioUp . "<br>" . mysqli_error($gestambu);
  }
/* --> fin de acualizacion de tablas secundarias  */

/* Recarga los datos con las nuevas actualizaciones */

# Modificar con modal que especifique si se ha guardado correctamente el servicio
# Ha de dar la opcion de mostrar el registro actualizado o desplazarse a otra página
# La carga de éstos datos muestra de nuevo el registro actualizado

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

# Datos para el servicio
$pacMostrar = mysqli_query($gestambu, "SELECT *
  FROM servicio
  WHERE idSv = '$registro'
  ");
$rwPacMos = mysqli_fetch_assoc($pacMostrar);

$serpersoMostrar = mysqli_query($gestambu, "SELECT *
  FROM serpersonal
  WHERE idSv = '$registro'
  ");
$rwSerperMos = mysqli_fetch_assoc($serpersoMostrar);

$serestadosMostrar = mysqli_query($gestambu, "SELECT *
  FROM serestados
  WHERE idSv = '$registro'
  ");
$rwSerestadosMos = mysqli_fetch_assoc($serestadosMostrar);

# Edad del paciente
$edaDatos = explode(" ", $rwPacMos['edad']);
$numEdad  = $edaDatos[0];
$texEdad  = $edaDatos[1];

# Tabla serinfo
$serinfoMos = mysqli_query($gestambu, "SELECT *
  FROM serinfo
  WHERE idSv = '$registro'
  ");
$rwSerinfoMos = mysqli_fetch_assoc($serinfoMos);


} // .fin de actualizar

/* Datos para selección */
# Aseguradora
if(isset($_SESSION['compania']) && count($_SESSION['compania']) >0){
    $cia =$_SESSION['compania'];
}else{
    $cia = mysqli_query($gestambu,
        "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom DESC
  ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($cia)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['compania'] = $aux;
    $cia =$_SESSION['compania'];
}

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

# especial
$tEspecial = mysqli_query($gestambu, "SELECT * FROM especial WHERE idSv='$registro' ");
$rwTesp = mysqli_fetch_assoc($tEspecial);

# Check impresión
$comp = mysqli_query($gestambu, "SELECT idLog, idSv FROM loguser WHERE idSv='$registro' AND idLog='4'");
$numfilas = mysqli_num_rows($comp);
if($numfilas == 0) {
  $check_imp = 0;
} else {
	$check_imp = 1;
}


# Listado de vehículos
if(isset($_SESSION['vehiculos_estado_no0']) && count($_SESSION['vehiculos_estado_no0']) >0){
    $lsVh =$_SESSION['vehiculos_estado_no0'];
}else{
    $lsVh = mysqli_query($gestambu,"SELECT idVh, matricula, estado
            FROM vehiculo
            WHERE estado != '0'
            ORDER BY matricula ASC
            ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($lsVh)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['vehiculos_estado_no0'] = $aux;
    $lsVh =$_SESSION['vehiculos_estado_no0'];
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

# Listado para estados con vuelta
$estCV = mysqli_query($gestambu, "SELECT idEst, vaEst
  FROM estados
  WHERE idEst IN('1','2','3','4','5','10','15','17')
  ORDER BY vaEst ASC
");

# Listado para estados sin vuelta
$estSV = mysqli_query($gestambu, "SELECT idEst, vaEst
  FROM estados
  WHERE idEst IN('1','11','14','15','17')
  ORDER BY vaEst ASC
");


# Comprobar si el servicio tiene incidencia
$compInci = mysqli_query($gestambu, "SELECT idSv FROM incidencia WHERE idSV = '$registro'");
$numInci  = mysqli_num_rows($compInci);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Mostrar servicio | H.C. <?php $rwPacMos['idPac']; ?></title>
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
  .alert {
	padding-bottom: 3px;
	padding-top: 3px;
	margin-bottom: 5px;
  }
  .content {
	  padding-bottom: 2px;
  }
  .box {
	margin-botton: 2px;
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
        <small><?php echo $rwPacMos['idSv']; ?></small> <?php if($rwPacMos['continuado'] != '0') {?> <small><i class="fa fa-clone"> <?php echo $rwPacMos['continuado'];?></i></button></small><?php } ?>
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
                <?php if($numInci > 0) { ?>
                <i class="fa fa-exclamation-triangle"></i>
                <?php } ?>
                Ficha: ID - <?php echo $rwPacMos['idSv']; ?>
              </h3>

              <div class="box-tools pull-right">
                <!-- button with a dropdown -->
                <div class="btn-group">
                  <?php include('../referencia/modals/inciVentana.php'); ?>
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" title="Opciones">
                    <i class="fa fa-bars"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="/ops/referencia/crear/servincu.php?iden=<?php echo $rwPacMos['idSv']; ?>&selRec=1"><i class="fa fa-ambulance"></i>Crear ambulancia</a></li>
                    <li><a href="/ops/referencia/crear/servincu.php?iden=<?php echo $rwPacMos['idSv']; ?>&selRec=3"><i class="fa fa-heartbeat"></i>Crear U.V.I.</a></li>
                    <li><a href="/ops/referencia/crear/servincu.php?iden=<?php echo $rwPacMos['idSv']; ?>&selRec=2"><i class="fa fa-eyedropper"></i>Crear enfermería</a></li>
                    <li><a href="/ops/referencia/crear/servincu.php?iden=<?php echo $rwPacMos['idSv']; ?>&selRec=4"><i class="fa fa-stethoscope"></i>Crear médico</a></li>
                    <li class="divider"></li>
                    <li><a href="/ops/nuevo/contVincu.php?iden=<?php echo $rwPacMos['idSv']; ?>"><i class="fa fa-calendar"></i>Crear continuado</a></li>
                    <li class="divider"></li>
					<?php if($_SESSION['usCate'] < '3') { ?>
                    <li><a href="/ops/mostrar/logSv.php?iden=<?php echo $rwPacMos['idSv']; ?>"><i class="fa fa-tag"></i>Ver registro</a></li>
					<?php } ?>
                    <li><a href="/ops/mostrar/inciMos.php?iden=<?php echo $rwPacMos['idSv']; ?>"><i class="fa fa-exclamation"></i>Ver incidencias</a></li>
					<li class="divider"></li>
					<li><a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwPacMos['idPac']; ?>"><i class="fa fa-file-text-o"></i>Ficha de paciente</a></li>
					<li><a href="/ops/mostrar/paciente.php?idPac=<?php echo $rwPacMos['idPac']; ?>"><i class="fa fa-pencil-square-o"></i>Historial de paciente</a></li>
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
                <div class="col-md-1"></div>
                <div class="col-md-10">
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
                      foreach($cia as $rCia){
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

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>DNI: </label>
                    <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="DNIPac" value="<?php echo $rwPacMos['DNIPac']; ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Póliza: </label>
                    <input type="text" class="form-control" placeholder="Póliza" name="poliza" value="<?php echo $rwPacMos['poliza']; ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Autorización: </label>
                    <input type="text" class="form-control" placeholder="Autorización" name="autorizacion" value="<?php echo $rwPacMos['autorizacion']; ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Delegación: </label>
                      <select class="form-control" name="delegacion">
                        <option value="0">-- No definida --</option>
                        <?php
                        foreach($lsDeleg as $rDelg){
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
                        <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo $rwPacMos['fecha']; ?>">
                        <div class="input-group-addon">
                          <?php if($rwPacMos['fest'] == '1') { ?>
						  <i class="fa"><strong>F/N</strong></i>
						  <?php } else { ?>
						  <i class="fa fa-calendar"></i>
						  <?php } ?>
                        </div>
                      </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valHora">Hora: </label>
                    <div class="input-group">
                      <input type="time" class="form-control" name="hora" id="hora" value="<?php echo $rwPacMos['hora']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Oxígeno
                        <input class="col-md-8" type="checkbox" class="minimal" name="ox" id="ox" value="1" <?php if($rwTesp['ox'] == 1) { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Rampa
                        <input class="col-md-8" type="checkbox" class="minimal" name="rampa" id="rampa" value="1" <?php if($rwTesp['rampa'] == 1) { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>2Técnicos
                        <input class="col-md-8" type="checkbox" class="minimal" name="dostec" id="dostec" value="1" <?php if($rwTesp['dTec'] == 1) { echo "checked"; } ?>>
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
                        foreach($tRecu as $rRecu){
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
                      <label id="valMedico">Med.
                        <input class="col-md-8" type="checkbox" class="minimal" name="medico" id="medico" value="1" <?php if($rwPacMos['medico'] == 1) { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valDue">Due
                        <input class="col-md-8" type="checkbox" class="minimal" name="enfermero" id="due" value="1" <?php if($rwPacMos['enfermero'] == 1) { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIda">Ida
                        <input class="col-md-8" type="checkbox" class="minimal" name="ida" id="ida" value="2" <?php if($rwPacMos['idvta'] == 2) { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valVta">Vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="vta" id="vta" value="3" <?php if($rwPacMos['idvta'] == 3) { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" id="idvta" value="1" <?php if($rwPacMos['idvta'] == 1) { echo "checked"; } ?>>
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" value="<?php echo $rwPacMos['nombre']; ?>">
                      <input type="hidden" name="idPac" value="<?php echo $rwPacMos['idPac']; ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos"  value="<?php echo $rwPacMos['apellidos']; ?>">
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
                      <select class="form-control" name="sexo" required>
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
                        <option value="3" <?php if($rwSerinfoMos['prioridad'] == '3') { echo "selected"; }?>> - Sin prioridad - </option>
                        <option value="1" <?php if($rwSerinfoMos['prioridad'] == '1') { echo "selected"; }?>>Urgente</option>
                        <option value="2" <?php if($rwSerinfoMos['prioridad'] == '2') { echo "selected"; }?>>Preferente</option>
                        <option value="4" <?php if($rwSerinfoMos['prioridad'] == '4') { echo "selected"; }?>>Hora fija</option>
                        <option value="5" <?php if($rwSerinfoMos['prioridad'] == '5') { echo "selected"; }?>>Hora desde</option>
                      </select>
                    <input type="time" class="form-control has-feedback" name="hconsulta" title="Hora a la que está citado" value="<?php if($rwSerinfoMos['hconsulta'] == '00:00:00') { } else { echo $rwSerinfoMos['hconsulta']; } ?>" >
                    <span class="help-block h6">H. de consulta</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Demora: </label>
                    <input type="time" class="form-control" name="demora" title="demora de demora dado a la compañía" value="<?php if($rwSerinfoMos['demora'] == '00:00:00') { } else { echo $rwSerinfoMos['demora']; } ?>">
                    <input type="time" class="form-control has-feedback" name="hvuelta" title="Hora de vuelta" value="<?php if($rwSerinfoMos['hvuelta'] == '00:00:00') { } else { echo $rwSerinfoMos['hvuelta']; } ?>">
                    <span class="help-block h6">H. vuelta</span>
                  </div>
                  <div class="clearfix"></div>
                  <!-- Personal de servicio -->
                  <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"> Ida <?php if($rwPacMos['idvta'] == 3) { echo "- Anulada -"; } ?></a></li>
                        <li><a href="#tab_2" data-toggle="tab"> Vuelta <?php if($rwPacMos['idvta'] == 2) { echo "- Anulada -"; } ?></a></li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label"> Vehículo - Ida </label>
                              <select class="form-control" name="vhIda" <?php if($rwPacMos['idvta'] == 3) { echo "readonly"; } ?>>
                                <option value="0"> - Selecciona vehículo - </option>
                                <?php
                                foreach($lsVh as $rVh){
                                    if($rwSerestadosMos['vhIda'] == $rVh['idVh']) {
                                      $seleccion = "selected";
                                    } else {
                                      $seleccion = "";
                                    }
                                    echo "<option value='".$rVh['idVh']."' ".$seleccion.">".$rVh['matricula']."</option>\n";
                                  }
                                 ?>
                              </select>
                          </div>
                          <div class="col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label"> Estado de ida </label>
                            <input type="text" class="form-control autotec" value="<?php if($rwPacMos['idvta'] == 3) { echo "Anulada ida"; } ?>" readonly>
                          </div>
                          <div class="col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label"> Estado general </label>
                              <select class="form-control" name="estServ" required="">
                                <option value=""> - Estado - </option>
                                <?php
                                if($rwPacMos['idvta'] == 1) {
                                  while($conVuelta = mysqli_fetch_assoc($estCV)) {
                                    if($rwPacMos['estServ'] == $conVuelta['idEst']) {
                                      $seleccion = "selected";
                                    } else {
                                      $seleccion = "";
                                    }
                                    echo "<option value='".$conVuelta['idEst']."' ".$seleccion.">".$conVuelta['vaEst']."</option>\n";
                                  }
                                } else {
                                  while($sinVT = mysqli_fetch_assoc($estSV)) {
                                    if($rwPacMos['estServ'] == $sinVT['idEst']) {
                                      $seleccion = "selected";
                                    } else {
                                      $seleccion = "";
                                    }
                                    echo "<option value='".$sinVT['idEst']."' ".$seleccion.">".$sinVT['vaEst']."</option>\n";
                                  }
                                }
                                 ?>
                              </select>
                          </div>
                          
                          <div class="form-group col-md-4 col-sm-4 col-xs-4">
                            <label class="control-label">Motivo: </label>
                            <div class="input-group">
                              <input type="text" class="form-control localidad" name="locTras" required value="<?php echo $rwPacMos['locTras']; ?>">
                              <div class="input-group-addon">
                                <i class="fa">L</i>
                              </div>
                            </div>
                          </div>
                                  
                          
                          
                          
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Técnico - Ida: </label>
                            <input type="text" class="form-control autotec" name="tecIda" value="<?php noZero($rwSerperMos['tecIda']); ?>" <?php if($rwPacMos['idvta'] == 3) { echo "readonly"; } ?>>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Ayudante - Ida: </label>
                            <input type="text" class="form-control autoayu" name="aydIda" value="<?php noZero($rwSerperMos['aydIda']); ?>" <?php if($rwPacMos['idvta'] == 3) { echo "readonly"; } ?>>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Médico - Ida: </label>
                            <input type="text" class="form-control automed" name="medIda" value="<?php noZero($rwSerperMos['medIda']); ?>" <?php if($rwPacMos['idvta'] == 3) { echo "readonly"; } ?>>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Enfermero - Ida: </label>
                            <input type="text" class="form-control autodue" name="dueIda" value="<?php noZero($rwSerperMos['dueIda']); ?>" <?php if($rwPacMos['idvta'] == 3) { echo "readonly"; } ?>>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label"> Vehículo - Vta </label>
                              <select class="form-control" name="vhVta" <?php if($rwPacMos['idvta'] == 2) { echo "readonly"; } ?>>
                                <option value="0"> - Selecciona vehículo - </option>
                                <?php
                                foreach($lsVh as $rVh2){
                                    if($rwSerestadosMos['vhVta'] == $rVh2['idVh']) {
                                      $seleccion = "selected";
                                    } else {
                                      $seleccion = "";
                                    }
                                    echo "<option value='".$rVh2['idVh']."' ".$seleccion.">".$rVh2['matricula']."</option>\n";
                                  }
                                 ?>
                              </select>
                          </div>
                          <div class="col-md-3 col-sm-3 col-xs-3">
                            <label class="control-label"> Estado de vuelta </label>
                            <input type="text" class="form-control autotec" value="<?php if($rwPacMos['idvta'] == 2) { echo "Anulada vuelta"; } ?>" readonly>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Técnico - Vta: </label>
                            <input type="text" class="form-control autotec" name="tecVta" value="<?php noZero($rwSerperMos['tecVta']); ?>" <?php if($rwPacMos['idvta'] == 2) { echo "readonly"; } ?>>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Ayudante - Vta: </label>
                            <input type="text" class="form-control autoayu" name="aydVta" value="<?php noZero($rwSerperMos['aydVta']); ?>" <?php if($rwPacMos['idvta'] == 2) { echo "readonly"; } ?>>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Médico - Vta: </label>
                            <input type="text" class="form-control automed" name="medVta" value="<?php noZero($rwSerperMos['medVta']); ?>" <?php if($rwPacMos['idvta'] == 2) { echo "readonly"; } ?>>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Enfermero - Vta: </label>
                            <input type="text" class="form-control autodue" name="dueVta" value="<?php noZero($rwSerperMos['dueVta']); ?>" <?php if($rwPacMos['idvta'] == 2) { echo "readonly"; } ?>>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                        <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                  </div>
                  <!-- / Personal de servicio -->

                  <!-- col-md-offset-3 -->
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12" id="noprint">
                      <input name="servicio_id" type="hidden" id="servicio_id" value="<?php echo $rwPacMos['idSv']; ?>" />
                      <input name="user_crear" type="hidden" id="user_crear" value="<?php echo $_SESSION['userId']; ?>" />
                      <input name="check" type="hidden" id="check" value="<?php echo $check_imp; ?>" />
                      <input name="estAnterior" type="hidden" value="<?php echo $rwPacMos['idvta']." ".$rwPacMos['estServ']; ?>" />
                      <a href="#" class="btn btn-info" onclick="imprimir()"><i class="fa fa-print"></i>Imprimir</a>
                      <button type="reset" class="btn btn-primary">Cancelar</button>
                      <button type="submit" name="guardar" value="enviar" class="btn btn-success validar">Guardar</button>
                    </div>
                  </div>
                </div>
              <div class="col-md-1"></div>
              </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            <span class="h6">
              <?php echo $_SESSION['usNom']." >> ".$_SESSION['userId']." >> "; echo fechaEs(); echo " ".date("H:i:s")." <i class=\"fa fa-user\"></i> ".$rwPacMos['idPac']; ?>
              <?php
              if($check_imp == 1) {
                  echo '- <i class="fa fa-print"></i> Ficha ya impresa';
              }
              ?>
            </span>
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
<!-- Funciones js -->
<script src="/ops/js/functjs.js"></script>
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
// disable mousewheel on a input number field when in focus
// (to prevent Cromium browsers change the value when scrolling)
$('form').on('focus', 'input[type=time]', function (e) {
  $(this).on('mousewheel.disableScroll', function (e) {
    e.preventDefault()
  })
})
$('form').on('blur', 'input[type=time]', function (e) {
  $(this).off('mousewheel.disableScroll')
})
$('form').on('focus', 'input[type=date]', function (e) {
  $(this).on('mousewheel.disableScroll', function (e) {
    e.preventDefault()
  })
})
$('form').on('blur', 'input[type=date]', function (e) {
  $(this).off('mousewheel.disableScroll')
})
</script>
<script src="/ops/js/insertModal.js"></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
