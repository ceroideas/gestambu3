<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Pendiente */
# Validación para los campos Prioridad y Demora

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {

  /* Carga de datos del formulario y limpieza */
  $user      = $_SESSION['userId'];
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
  $horaFor   = $hora.":00";
  $petFor    = $hpeti.":00";

  /* Guardado de datos en la DB */
  # comprueba si existe id en la tabla paciente

  $sqlServicio = "INSERT INTO vuelosanitario (idCia, dni, tipo, comp, hc, fecha, hora, sexo, edad, medico, due, idvta, pediatra, incub, nombre, apellidos, acomp, dniacomp, recoger, locRec, trasladar, locTras, obs, precioAmb, hpeti, user)
    VALUES ('$cia','$dni', '$tipo', '$comp', '$hc', '$fecha', '$horaFor', '$sexo', '$edadTab', '$medico', '$due', '$idvta', '$pediatra', '$incub', '$nombre', '$apellidos', '$acomp', '$dniacomp', '$recoger', '$locRec',
      '$trasladar', '$locTras', '$obs', '$precioAmb', '$petFor', '$user')
    ";


  if(mysqli_query($gestambu,$sqlServicio)) {
    //echo "Datos insertados correctamente, en tabla servicio";
    // Ultimo id ingresado con $gestambu
    $idInsertado = mysqli_insert_id($gestambu);
    $mensa = "Vuelo guardado correctamente";
    $mensaOk = '1';

    //Inserta registro en tabla serinfo
    $vuelorefIns = "INSERT INTO vueloref (idVuelo, estVuelo) VALUES ('$idInsertado', '1')";

    if(mysqli_query($gestambu, $vuelorefIns)) {
      $mensa = "Vuelo guardado correctamente";
      $mensaOk = '1';
    } else {
      echo "Error: " . $vuelorefIns . "<br>" . mysqli_error($gestambu);
    }
  } else {
    echo "Error: " . $sqlServicio . "<br>" . mysqli_error($gestambu);
  }
}

/* Datos para selección */
# Aseguradora
$cia = mysqli_query($gestambu,
  "SELECT idCia, ciaNom
  FROM cia
  ORDER BY ciaNom DESC
  ");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Nuevo vuelo</title>
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
        Formulario de nuevo vuelo sanitario
        <small>Completa los datos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Nuevo vuelo</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-plane"></i> Nuevo vuelo</h3>

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
                  <div class="col-md-8 col-sm-8 col-xs-8 form-group">
                    <label>Compañía: </label>
                    <select class="form-control" name="idCia" id="idCia" required="">
                      <option value="">-- Selecciona compañía --</option>
                      <?php
                      while($rCia = mysqli_fetch_assoc($cia)) {
                        echo "<option value='".$rCia['idCia']."'>".$rCia['ciaNom']."</option>\n";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                    <label>DNI: </label>
                    <input type="text" class="form-control" placeholder="DNI" name="dni">
                  </div>

                  <div class="clearfix"></div>
                  <!-- Datos referentes al vuelo -->
                  <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                    <label>Tipo: </label>
                    <select class="form-control" name="tipo" required="">
                      <option value="">-- Tipo de servicio --</option>
                      <option value="1">Convencional</option>
                      <option value="2">Crítico</option>
                      <option value="3">Retorno</option>
					  <option value="4">Trasplante</option>
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Comp.: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Aseguradora" name="comp">
                      <div class="input-group-addon">
                        <i class="fa">C</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Nº Historia: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Número de historial" name="hc">
                      <div class="input-group-addon">
                        <i class="fa">HC</i>
                      </div>
                    </div>
                  </div>
				  <div class="clearfix"></div>
                  <!-- Referente al servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Fecha: </label>
                    <div class="input-group">
                      <input type="date" class="form-control" name="fecha" value="<?php echo date("Y-m-d"); ?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Hora: </label>
                    <div class="input-group">
                      <input type="time" class="form-control" name="hora" value="<?php echo date("H:i");?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Sexo: </label>
                      <select class="form-control" name="sexo">
                        <option value="">---</option>
                        <option value="H">Hombre</option>
                        <option value="M">Mujer</option>
                      </select>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Edad: </label>
                    <input type="text" class="form-control" placeholder="Edad" name="edad" value="" >
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> -- </label>
                      <select class="form-control" name="edadTit">
                        <option value="años">Años</option>
                        <option value="meses">Meses</option>
                        <option value="dias">Días</option>
                      </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al tipo de servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <labe>Médico
                        <input class="col-md-8" type="checkbox" class="minimal" name="medico" value="1">
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Due
                        <input class="col-md-8" type="checkbox" class="minimal" name="due" value="1">
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" value="1">
                      </label>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Pediatra
                        <input class="col-md-8" type="checkbox" class="minimal" name="pediatra" value="1">
                      </label>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                      <label>Incubadora
                        <input class="col-md-8" type="checkbox" class="minimal" name="incubadora" value="1">
                      </label>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required>
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos">
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
                      <input type="text" class="form-control" name="recoger">
                      <div class="input-group-addon">
                        <i class="fa fa-home"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locRec">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Trasladar: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="trasladar">
                      <div class="input-group-addon">
                        <i class="fa fa-share"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locTras">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>

                  <!-- textarea -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label>Observaciones</label>
                    <textarea class="form-control" rows="3" placeholder="Observaciones" name="obs"></textarea>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Precio Amb. externa: </label>
                    <input type="text" class="form-control" name="precioAmb" value="">
                    <span class="help-block h6">* Si se contrata amb. externa</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Hora de petición: </label>
                    <input type="time" class="form-control" name="hpeti" value="" required>
                  </div>

                  <div class="clearfix"></div>

                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Acompañante: </label>
                    <input type="text" class="form-control" name="acomp">
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">DNI: </label>
                    <input type="text" class="form-control" name="dniacomp">
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
            <div class="box-footer">

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
