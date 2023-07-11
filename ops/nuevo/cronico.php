<?php
session_start();
include '../../functions/function.php';
nonUser();

/* Pendiente */
# Validación para los campos Prioridad y Demora

/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {

  /* Carga de datos del formulario y limpieza */

  $cia       = trim(mysqli_real_escape_string($gestambu, $_POST['idCia']));
  $prov      = trim(mysqli_real_escape_string($gestambu, $_POST['provincia']));
  $DNI       = trim(mysqli_real_escape_string($gestambu, $_POST['pacDNI']));
  $poliza    = trim(mysqli_real_escape_string($gestambu, $_POST['poliza']));
  $auto      = trim(mysqli_real_escape_string($gestambu, $_POST['autorizacion']));
  $deleg     = trim(mysqli_real_escape_string($gestambu, $_POST['delegacion']));
  $tipoSeg   = trim(mysqli_real_escape_string($gestambu, $_POST['tipoSeg']));
  $nombre    = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['nombre'])));
  $apellidos = strtoupper(trim(mysqli_real_escape_string($gestambu, $_POST['apellidos'])));
  $tlf1      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf1']));
  $tlf2      = trim(mysqli_real_escape_string($gestambu, $_POST['tlf2']));
  $sexo      = trim(mysqli_real_escape_string($gestambu, $_POST['sexo']));
  $edad      = trim(mysqli_real_escape_string($gestambu, $_POST['edad']));
  $edadTit   = trim(mysqli_real_escape_string($gestambu, $_POST['edadTit']));
  $direccion = trim(mysqli_real_escape_string($gestambu, $_POST['direccion']));
  $locRec    = trim(mysqli_real_escape_string($gestambu, $_POST['locRec']));
  $obs       = trim(mysqli_real_escape_string($gestambu, $_POST['obs']));
  $segMed    = trim(mysqli_real_escape_string($gestambu, $_POST['segMed']));
  $medAsig   = trim(mysqli_real_escape_string($gestambu, $_POST['medAsig']));
  $edadTab   = $edad." ".$edadTit;
  $tipo      = 0;

  /* Comprobación si existe paciente - tabla paciente */

  include '../referencia/compPaCronico.php';

  /* Si existe paciente en la DB paciente no guarda los datos y devuelve error, sino guarda los datos */

  if(empty($pacienteID)) {
    /* Guardado de datos en la DB */
    $sqlPac = "INSERT INTO paciente (idCia, pacDNI, pNombre, pApellidos, edad, sexo, poliza, autorizacion, delegacion, tlf1, tlf2, obs, direccion, localidad, provincia, segMed, tipoSeg, medAsig)
      VALUES ('$cia', '$DNI', '$nombre', '$apellidos', '$edadTab', '$sexo', '$poliza', '$auto', '$deleg', '$tlf1', '$tlf2', '$obs', '$direccion', '$locRec', '$prov', '$segMed', '$tipoSeg', '$medAsig')";

    if(mysqli_query($gestambu,$sqlPac)) {
      $mensa   = "Se ha creado un nuevo paciente";
      $mensaOk = '1';
      $colorMs = "success";
      $icoMs   = "check";

      /* Mensajes de log */
      $obsText = $nombre." ".$apellidos;
      $usuario = $_SESSION['userId'];
      guardarLog('15', $usuario, $obsText, '0');

    } else {
      echo "Error: " . $sqlPac . "<br>" . mysqli_error($gestambu);
    }
  } else {

    /* Mensajes de log */
    $obsText = "intento de crear paciente duplicado. Pac: ".$pacienteID;
    $usuario = $_SESSION['userId'];
    guardarLog('14', $usuario, $obsText, '0');

    $mensa   = "Error: Existe un paciente ya registrado - <i class=\"fa fa-user\"></i><a href=\"/ops/mostrar/paciente.php?idPac=".$pacienteID."\"> Ver paciente</a>";
    $mensaOk = '1';
    $colorMs = "danger";
    $icoMs   = "times";
  }
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

# listado de médicos
$lsMed = mysqli_query($gestambu, "SELECT userId, usNom, usApe, usCate
  FROM user
  WHERE usCate = '7'
  ORDER BY usNom ASC
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Nuevo paciente</title>
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
        Formulario de nuevo paciente
        <small>Completa los datos</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Nuevo paciente</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo paciente</h3>

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
                <div class="alert alert-<?php echo $colorMs; ?> alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fa fa-<?php echo $icoMs; ?>"></i> <?php echo $mensa; ?>
                  - Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
                </div>
                <?php } ?>
                <!-- /Mensajes -->
                  <div class="col-md-6 col-sm-6 col-xs-8 form-group">
                    <label id="valCia">Compañía: </label>
                    <select class="form-control" name="idCia" id="idCia" required>
                      <option value="">-- Selecciona compañía --</option>
                      <?php
                      while($rCia = mysqli_fetch_assoc($cia)) {
                        echo "<option value='".$rCia['idCia']."'>".$rCia['ciaNom']."</option>\n";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-4 form-group">
                    <label id="valProv">Provincia: </label>
                    <select class="form-control" name="provincia" id="prov" required>
                      <option value="">-- Selecciona Provincia --</option>
                      <option value="11">Cádiz</option>
					  <option value="14">Córdoba</option>
					  <option value="29">Málaga</option>
					  <option value="52">Melilla</option>
                      <option value="41">Sevilla</option>                   
					  <option value="21">Huelva</option>
                    </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>DNI: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="DNI sin guiones ni espacios" name="pacDNI">
                      <div class="input-group-addon">
                        <i class="fa">D</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Póliza: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Póliza" name="poliza">
                      <div class="input-group-addon">
                        <i class="fa">P</i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Autorización: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Autorización" name="autorizacion">
                      <div class="input-group-addon">
                        <i class="fa">A</i>
                      </div>
                    </div>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valTipo">Tipo: </label>
                      <select class="form-control" name="tipoSeg" id="tipo" required>
                        <option value="">-- Tipo de servicio --</option>
                        <option value="1"> Crónico</option>
                        <option value="2"> Paliativo</option>
                      </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Delegación: </label>
                      <select class="form-control" name="delegacion">
                        <option value="0">-- No definida --</option>
                        <?php
                          while($rDelg = mysqli_fetch_assoc($lsDeleg)) {
                            echo "<option value='".$rDelg['id']."'>".$rDelg['provincia']."</option>\n";
                          }
                         ?>
                      </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label>Médico Asignado: </label>
                      <select class="form-control" name="medAsig" required oninvalid="setCustomValidity('Especifica el médico asignado')">
                        <option value="0">-- No definida --</option>
                        <?php
                          while($rLsMed = mysqli_fetch_assoc($lsMed)) {
                            echo "<option value='".$rLsMed['userId']."'>".$rLsMed['usNom']." ".$rLsMed['usApe']."</option>\n";
                          }
                         ?>
                      </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" oninvalid="setCustomValidity('Especifica el nombre')">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos" required oninvalid="setCustomValidity('Especifica los apellidos')">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>


                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 1" name="tlf1"  value="">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 2" name="tlf2" value="">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
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
                  <!-- Referente a dónde se realiza el servicio -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label" id="valRecoger">Dirección: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="direccion" id="recoger">
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
                  <div class="clearfix"></div>

                  <!-- textarea -->
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label>Observaciones</label>
                    <textarea class="form-control" rows="3" placeholder="Observaciones" name="obs"></textarea>
                  </div>
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="hidden" value="1" name="segMed">
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

<?php include '../inc/pie.php'; ?>

<?php include '../inc/bcontrol.php'; ?>

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
