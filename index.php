<?php
session_start();
include 'functions/function.php';
error_reporting(0);
if(isset($_SESSION['usCate'])) {
  //Cuando ya se ha iniciado sesion, redirige automáticamente a index.
  if($_SESSION['usCate'] < 5) {
    header("Location: /ops/index.php");
  } else {
    header("Location: /tec/index.php");
  }
} else {
    //Si no hay sesión registrada crea una nueva al enviar los datos
    if(isset($_POST['guardar'])) {


      //Inicio de variables de sesión

      $dni      = trim(mysqli_real_escape_string($gestambu, $_POST['usDNI']));
      $pwd      = trim(mysqli_real_escape_string($gestambu, $_POST['usPwd']));
      $compUser = mysqli_query($gestambu, "SELECT * FROM user WHERE usDNI='$dni' AND usPwd='$pwd'");
      // echo $compUser;
      $numComp  = mysqli_num_rows($compUser);
      $rCpUser  = mysqli_fetch_assoc($compUser);
      $comDNI   = mysqli_query($gestambu, "SELECT usDNI FROM user WHERE usDNI='$dni'");
      $numDNI   = mysqli_num_rows($comDNI);

      /* Comprobar duplicado de sesión
        # pendiente
      */

      if(empty($_POST['usDNI']) || empty($_POST['usPwd'])) {
        $mensa  = "El usuario o contraseña no han sido ingresados.";
        $mError = 1;
      } elseif($numDNI == 0 ) {
        $mensa = "Usuario incorrecto.";
        $mError = 1;
      } elseif($pwd !== $rCpUser['usPwd']) {
        $mensa = "Contraseña incorrecta.";
        $mError = 1;
      } elseif($numComp == 0) {
        $mensa = "Contraseña o usuario incorrectos.";
        $mError = 1;
      } elseif($numComp != 1) {
        $mensa = "No exite registro para los datos dados.";
        $mError = 1;
      } elseif($rCpUser['usEst'] != 1) {
        $mensa = "El usuario aún no está activado, consulta con el admimistrador.";
        $mError = 1;
      } else {

        $sqlUser = mysqli_query($gestambu, "SELECT * FROM user WHERE usDNI='$dni' AND usPwd='$pwd' AND usEst = '1'");
        if($row = mysqli_fetch_array($sqlUser)) {
          $_SESSION['userId'] = $row['userId'];
          $_SESSION['usNom'] = $row['usNom'];
          $_SESSION['usProv'] = $row['usProv'];
          $_SESSION['usCate'] = $row['usCate'];
          $usuario = $_SESSION['userId'];
		  $cate    = $_SESSION['usCate']; 

          /* Mensajes de log */
          $obsText = "Inicio de sesión";
          $servicioID = '0';
          guardarLog('1', $usuario, $obsText, $servicioID);

          /* Actualizar estado a Conectado */
          $estUp = mysqli_query($gestambu, "UPDATE user SET estUser='1' WHERE userId='$usuario'");

          //Header para redirigir según tipo de usuario
          # Administrador - 1
          # Operador / Cordinador - 2 / 3
          # Admin - Vehículos - 4
          # Técnico - 5
		  # Asepeyo Sevilla - 10
	      # Asepeyo Málaga - 11

          if($row['usCate'] < 4) {
            header("Location: /ops/index.php");
            } elseif($row['usCate'] == 10){ //Asepeyo Sevilla
            header("Location: /ops/servicios/libreta/libretaAs.php?prov=41&recuSel=7");
            } elseif($row['usCate'] == 11){ //Asepeyo Malaga
            header("Location: /ops/servicios/libreta/libretaAs.php?prov=29&recuSel=7");			
            } else {
                  header("Location: /tec/index.php");
                }

        } else {
          echo "<h4>A ocurrido un error al inciar la sesión</h4><br \>";
          echo "<h4>Comprueba el usuario y la contraseña introducidas.</h4><br \>";
          echo "<a href='javascript:history.back();'>Reintentar</a></h4>\n";
        }
      }
    }
}

mysqli_close($gestambu);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gestambu 3.0 | Incio de sesión</title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="docs/bootstrap/css/bootstrap.min.css">
  <!-- Fuentes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Estilo del tema -->
  <link rel="stylesheet" href="docs/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="docs/plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Gest</b>Ambu</a> 3.0
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Inicio de sesión</p>
    <?php if(isset($_POST['guardar']) && $mError == 1) { ?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $mensa; ?>
    </div>
    <?php } ?>
    <form action="" method="post">
      <div class="form-group has-feedback <?php if(isset($_POST['guardar'])) {echo $menCl; } ?>">
        <input type="text" name="usDNI" class="form-control" placeholder="DNI" required="" title="Escribe tu usuario">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <?php if(isset($_POST['guardar'])) {echo $mensa; } ?>
      </div>
      <div class="form-group has-feedback <?php if(isset($_POST['guardar'])) {echo $menCl; } ?>">
        <input type="password" name="usPwd" class="form-control" placeholder="Contraseña" required="" title="Es necesaria la contraseña">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <?php if(isset($_POST['guardar'])) {echo $mensa; } ?>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="guardar" value="enviar" class="btn btn-primary btn-block btn-flat">Comenzar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div class="social-auth-links text-center">
    <a href="#recu">Olvidé mi contraseña</a><br>
    <a href="#">www.ambuandalucia.es</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="docs/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="docs/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
