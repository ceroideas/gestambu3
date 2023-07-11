<?php
session_start();
include '../../functions/function.php';
nonUser();


//Insertar datos en DB
if(@$_POST['guardar'] == 'enviar') {

  $vaEst = trim(mysqli_real_escape_string($gestambu, $_POST['vaEst']));
  $okOp = trim(mysqli_real_escape_string($gestambu, $_POST['okOp']));
  $okTec = trim(mysqli_real_escape_string($gestambu, $_POST['okTec']));

  $sql = "INSERT INTO estados (vaEst, okOp, okTec) VALUES ('$vaEst', '$okOp', '$okTec')";
  if(mysqli_query($gestambu,$sql)) {
    $mensa = "<span class=\"help-block\"><i class=\"fa fa-check\"></i>El registro se ha guardado correctamente</span>";
    $menCl = "has-success";
  } else {
    $mensa = "<span class=\"help-block\"><i class=\"fa fa-check\"></i>Error: " . $sql . "<br>" . mysqli_error($gestambu)."</span>";
    $menCl = "has-error";
  }

}

//Mostrar registros
$mosEstado = mysqli_query($gestambu, "SELECT * FROM estados ORDER BY idEst asc")
          or die("Error al buscar datos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Nuevo estado</title>
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
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
        Registro de:
        <small>Nuevo estado</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Soporte</a></li>
        <li class="active">Estados</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo estado:</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Minimizar">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Cerrar">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <form action="" method="post" class="form-horizontal">
              <div class="box-body">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                  <div class="col-md-4">
                    <div class="form-group <?php if(isset($_POST['guardar'])) {echo $menCl; } ?> ">
                      <label for="vaEst" class="control-label">Estado: </label>
                        <input type="text" class="form-control" name="vaEst" placeholder="Nombre del estado" title="Ha de ingresar algún caracter" required />
                        <?php if(isset($_POST['guardar'])) {echo $mensa; } ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group ">
                      <label for="okOP" class="control-label">Valido para operador: </label>
                      <select class="form-control" required name="okOp">
                        <option value="">-- Selecciona--</option>
                        <option value="0"> No válido </option>
                        <option value="1"> Si válido </option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="okTec" class="control-label">Valido para técnico: </label>
                      <select class="form-control" required name="okTec">
                        <option value="">-- Selecciona--</option>
                        <option value="0"> No válido </option>
                        <option value="1"> Si válido </option>
                      </select>
                    </div>
                  </div>
              </div>
                <div class="col-md-2"></div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <div class="col-md-3 col-sm-3"></div>
                  <button type="reset" class="btn btn-default">Cancelar</button>
                  <button type="submit" class="btn btn-info" name="guardar" value="enviar" >Guardar</button>
              </div>
              <!-- /.box-footer-->
            </form>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Categorías registradas</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Válido para operador</th>
                  <th>Válido para Técnico</th>
                </tr>
                <?php while($rwEstado = mysqli_fetch_array($mosEstado)) { ?>
                <tr>
                  <td><?php echo $rwEstado['idEst']; ?></td>
                  <td><?php echo $rwEstado['vaEst']; ?></td>
                  <td><?php if($rwEstado['okOp'] == 1) { echo "Si es váldio"; } else { echo "No puede ser utilizado"; }; ?></td>
                  <td><?php if($rwEstado['okTec'] == 1) { echo "Si es váldio"; } else { echo "No puede ser utilizado"; }; ?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.Fin de tabla -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include '../inc/pie.php'; ?>

<?php include '../inc/bcontrol.php'; ?>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
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
<!-- Validación nuevo usuario -->
<!-- <script src="/ops/js/validacionNuser.js"></script> -->
</body>
</html>
<?php
mysqli_close($gestambu);
?>
