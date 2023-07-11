<?php
session_start();
include '../../functions/function.php';
nonUser();


//Categorías
$selCat = mysqli_query($gestambu, "SELECT * FROM cate ORDER BY idCate asc");

//Insertar datos en DB
if(@$_POST['guardar'] == 'enviar') {

  $usNom   = trim(mysqli_real_escape_string($gestambu, $_POST['usNom']));
  $usApe   = trim(mysqli_real_escape_string($gestambu, $_POST['usApe']));
  $usDNI   = trim(mysqli_real_escape_string($gestambu, $_POST['usDNI']));
  $usTlf   = trim(mysqli_real_escape_string($gestambu, $_POST['usTlf']));
  $usTlf2  = trim(mysqli_real_escape_string($gestambu, $_POST['usTlf2']));
  $usEmail = trim(mysqli_real_escape_string($gestambu, $_POST['usEmail']));
  $usCate  = trim(mysqli_real_escape_string($gestambu, $_POST['usCate']));
  $usDirec = trim(mysqli_real_escape_string($gestambu, $_POST['usDirec']));
  $usLoc   = trim(mysqli_real_escape_string($gestambu, $_POST['usLoc']));
  $usProv  = trim(mysqli_real_escape_string($gestambu, $_POST['usProv']));
  $userId  = trim(mysqli_real_escape_string($gestambu, $_POST['userId']));
  $usEst   = trim(mysqli_real_escape_string($gestambu, $_POST['usEst']));

  $sql = "UPDATE user set usNom='$usNom', usApe='$usApe', usDNI='$usDNI', usTlf='$usTlf', usTlf2='$usTlf2', usEmail='$usEmail', usDirec='$usDirec', usLoc='$usLoc', usCate='$usCate', usProv='$usProv', usEst='$usEst'
    WHERE userId = '$userId'
  ";

  if(mysqli_query($gestambu,$sql)) {
    $mensa = "<span class=\"help-block\"><i class=\"fa fa-check\"></i>El registro se ha acutalizado correctamente</span>";
    $menCl = "has-success";
  } else {
    $mensa = "<span class=\"help-block\"><i class=\"fa fa-check\"></i>Error: " . $sql . "<br>" . mysqli_error($gestambu)."</span>";
    $menCl = "has-error";
  }

}

if(isset($_GET['user'])) {
  $idUser = $_GET['user'];
}

$sqlUser = mysqli_query($gestambu, "SELECT * FROM user WHERE userId = '$idUser'");
$rwUser  = mysqli_fetch_assoc($sqlUser);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Perfil | GestAmbu 3.0</title>
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
        Perfil
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="/ops/personal/lstpersonal.php"><i class="fa fa-user"></i>Usuarios</a></li>
        <li class="active">Nuevo usuario</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $rwUser['usNom']." ".$rwUser['usApe']; ?></h3>

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
                  <div class="form-group <?php if(isset($_POST['guardar'])) {echo $menCl; } ?>">
                    <label for="usNom" class="col-sm-2 control-label">Nombre: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usNom" placeholder="Nombre" title="Ha de ingresar algún caracter" value="<?php echo $rwUser['usNom']; ?>" required />
                      <?php if(isset($_POST['guardar'])) {echo $mensa; } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usApe" class="col-sm-2 control-label">Apellidos: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usApe" placeholder="Apellidos" title="Ha de ingresar algún caracter" value="<?php echo $rwUser['usApe']; ?>" required />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usDNI" class="col-sm-2 control-label">DNI: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usDNI" placeholder="DNI" value="<?php echo $rwUser['usDNI']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usApe" class="col-sm-2 control-label">Teléfono: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usTlf" placeholder="Teléfono" value="<?php echo $rwUser['usTlf']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usApe" class="col-sm-2 control-label">Teléfono: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usTlf2" placeholder="Pon 0 si no hay 2º teléfono" value="<?php echo $rwUser['usTlf2']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usEmail" class="col-sm-2 control-label">Email: </label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="usEmail" placeholder="Email" value="<?php echo $rwUser['usEmail']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usDirec" class="col-sm-2 control-label">Dirección: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usDirec" placeholder="Dirección" value="<?php echo $rwUser['usDirec']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usLoc" class="col-sm-2 control-label">Localidad: </label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="usLoc" placeholder="Localidad" value="<?php echo $rwUser['usLoc']; ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="usCate" class="col-sm-2 control-label">Categoría: </label>
                    <div class="col-sm-10">
                      <select class="form-control" required name="usCate">
                        <option value="">-- Selecciona categoría --</option>
                        <?php
                        while($vCate = mysqli_fetch_array($selCat)) {
                          if($rwUser['usCate'] == $vCate['idCate']) {
                            $seleccion = "selected";
                          } else {
                            $seleccion = "";
                          }
                          echo "<option value='".$vCate['idCate']."' ".$seleccion.">".$vCate['cate']."</option>\n";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group has-feedback">
                    <label for="usProv" class="col-sm-2 control-label">Provincia: </label>
                    <div class="col-sm-10 has-feedback">
                      <select class="form-control" name="usProv">
                        <option value="">-- Selecciona Provincia --</option>
                        <option value="29" <?php if($rwUser['usProv'] == '29') {echo "selected=\"selected\""; } ?>>Málaga</option>
                        <option value="41" <?php if($rwUser['usProv'] == '41') {echo "selected=\"selected\""; } ?>>Sevilla</option>
                        <option value="11" <?php if($rwUser['usProv'] == '11') {echo "selected=\"selected\""; } ?>>Cádiz</option>
                      </select>
                      <span class="help-block">* No es necesario para operadores ni administradores</span>
                    </div>
                  </div>
                  <div class="form-group has-feedback">
                    <label for="usProv" class="col-sm-2 control-label">Estado: </label>
                    <div class="col-sm-10 has-feedback">
                      <select class="form-control" name="usEst">
                        <option value="">-- Selecciona Provincia --</option>
                        <option value="1" <?php if($rwUser['usEst'] == '1') {echo "selected=\"selected\""; } ?>>Activo</option>
                        <option value="0" <?php if($rwUser['usEst'] == '0') {echo "selected=\"selected\""; } ?>>Inactivo</option>
                      </select>
                      <span class="help-block"></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-2"></div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <div class="col-md-3 col-sm-3"></div>
                  <input type="hidden" name="userId" value="<?php echo $rwUser['userId']; ?>" />
                  <button type="reset" class="btn btn-default">Cancelar</button>
                  <button type="submit" name="guardar" value="enviar" class="btn btn-info">Guardar</button>
              </div>
              <!-- /.box-footer-->
            </form>
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
