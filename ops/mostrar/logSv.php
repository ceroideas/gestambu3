<?php
session_start();
include '../../functions/function.php';
nonUser();

if(isset($_GET['iden'])) {
  $registro = $_GET['iden'];
} else {
  $registro = "";
}

/* Mensajes de log */
/*
$obsText = "Ha visaulizado el registro";
$usuario = $_SESSION['userId'];
guardarLog('6', $usuario, $obsText, $registro);
*/

/* Datos de Log de servicio */
$logSv = mysqli_query($gestambu, "SELECT loguser.idLogUser, loguser.idLog, loguser.userId, loguser.obsText, loguser.idSv, loguser.sendLog, user.userId, user.usNom, user.usApe, msjlog.idLog, msjlog.msjLog
  FROM loguser
    LEFT JOIN user ON loguser.userId = user.userId
    LEFT JOIN msjlog ON loguser.idLog = msjlog.idLog
  WHERE loguser.idSv = '$registro'
  ORDER BY loguser.sendLog ASC
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | LogServicio | Idservicio </title>
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
        Log de servicio :
        <small><?php echo $registro; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="/ops/mostrar/editServ.php?iden=<?php echo $registro; ?>">Ficha de servicio</a></li>
        <li class="active">Log de servicio</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Chat box -->
      <div class="box box-success">
        <div class="box-header">
          <i class="fa fa-tags"></i>

          <h3 class="box-title">Registros realizados al servicio: <i>id <?php echo $registro; ?></i></h3>

          <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
          </div>
        </div>
        <div class="box-body chat" id="chat-box">
          <!-- chat item -->
          <?php while($rwLog = mysqli_fetch_assoc($logSv)) { ?>
          <div class="item">
            <img src="/ico/users/<?php echo mostrarIcoUser($rwLog['userId']); ?>" alt="user image" class="online">

            <p class="message">
              <a href="#" class="name">
                <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $rwLog['sendLog']; ?></small>
                 - <?php echo $rwLog['usNom']." ".$rwLog['usApe']; ?>
              </a>
              <?php echo $rwLog['msjLog']; ?> - <?php echo $rwLog['obsText']; ?>
            </p>
          </div>
          <?php } ?>
          <!-- /. Chat item -->
        </div>
      </div>
      <!-- /.box (chat box) -->

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

</body>
</html>
<?php
mysqli_close($gestambu);
?>
