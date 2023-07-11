<?php
session_start();
include '../../functions/function.php';
nonUser();
include '../../shared/pagination.php';
include '../../shared/querys/relevos.querys.php';

if(isset($_GET['diaIni'])) {
  $diaIni = $_GET['diaIni'];
} else {
  $diaIni = date('Y-m-d');
}

if(isset($_GET['diaFin'])) {
  $diaFin = $_GET['diaFin'];
} else {
  $hoy = date('Y-m-d');
  $diaFin = strtotime('+1 day', strtotime($hoy));
  $diaFin = date('Y-m-d', $diaFin);
}

// PAGES NUMBERS
$incMant_page = isset($_GET['page']) ? $_GET['page'] : 1;
$inciSv_page = isset($_GET['pageA']) ? $_GET['pageA'] : 1;
$inciExp_page =  isset($_GET['pageB']) ? $_GET['pageB'] : 1;

/* Relevo mantener */

$incMant_total_records = mysqli_fetch_array(mysqli_query($gestambu, RelevoQuerys::getCountQuery("incMant",$diaIni,$diaFin)))[0];
$inciSv_total_records = mysqli_fetch_array(mysqli_query($gestambu, RelevoQuerys::getCountQuery("inciSv",$diaIni,$diaFin)))[0];
$inciExp_total_records = mysqli_fetch_array(mysqli_query($gestambu, RelevoQuerys::getCountQuery("inciExp",$diaIni,$diaFin)))[0];
$incMant_total_pages = getPages($incMant_total_records, 10);
$inciSv_total_pages = getPages($inciSv_total_records, 10);
$inciExp_total_pages = getPages($inciExp_total_records, 10);


$incMant = mysqli_query($gestambu, RelevoQuerys::getQuery("incMant",$diaIni,$diaFin)."LIMIT".getOffsetAndLimit($incMant_page, 10));

/* Relevo diario */
$inciSv = mysqli_query($gestambu, RelevoQuerys::getQuery("inciSv",$diaIni,$diaFin)."LIMIT".getOffsetAndLimit($inciSv_page, 10));

/* Entrada de servicios con Incidencias */
$inciExp = mysqli_query($gestambu, RelevoQuerys::getQuery("inciExp",$diaIni,$diaFin)."LIMIT".getOffsetAndLimit($inciExp_page, 10));

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Parte de relevo </title>
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
  <style>
    .box {
      margin-bottom: 5px;
      font-size: 11px;
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
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Parte de relevo:
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Relevo</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Selección de fechas -->
      <div class="box-body">
        <div class="col-md-8 col-md-offset-3">
          <!-- form start -->
          <form class="form-vertical" id="searchBetweenForm" action="" method="POST">
            <div class="box-body">
              <div class="form-group col-md-3 col-sm-3 col-xs-4">
                <label>Inicio: </label>
                <div class="input-group">
                  <input type="date" class="form-control" placeholder="DNI sin guiones ni espacios" name="diaIni" value="<?=$diaIni; ?>">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-3 col-sm-3 col-xs-4">
                <label>Final: </label>
                <div class="input-group">
                  <input type="date" class="form-control" placeholder="DNI sin guiones ni espacios" name="diaFin" value="<?=$diaFin; ?>">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-3 col-sm-3 col-xs-4">
                <label> &nbsp; </label>
                <div class="input-group">
                  <button type="submit" class="btn btn-default">Consultar</button>
                </div>
              </div>
            </div>
            <!-- /.box-footer -->
          </form>
        </div>
      </div>
      <!-- /. Selección de fechas -->

      <!-- Incidencias Mantener -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Entradas importantes - Mantenidas</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>

        <!-- /.box-header -->
        <div class="box-body chat" id="chat-box">
          <?php while($rwMante = mysqli_fetch_assoc($incMant)) { ?>
          <!-- chat item -->
          <div class="item">
            <img src="/ico/users/<?php echo mostrarIcoUser($rwMante['userId']); ?>" alt="user image" class="online">

            <p class="message">
              <?php $enlace = "/ops/referencia/modRelevo.php?idRel=".$rwMante['idRel']; ?>
              <a href="#" onclick="abrirVentana('<?php echo $enlace; ?>')"><i class="fa fa-pencil-square-o"></i> Editar</a>
              <a href="#" class="name">
                <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $rwMante['enviado']; ?></small>
                - <?php echo $rwMante['usNom']." ".$rwMante['usApe']; ?>
                <?php
                  if(!empty($rwMante['modificado'])) {
                    $colMod = @explode("/", $rwDia['modificado']);
                    $fechaMod = @$colMod[0];
                    $userMod  = @$colMod[1];
                    $idMod    = @$colMod[2];

                    echo "- Modificado: <small class=\"text-muted\">".$fechaMod." - ".$userMod."</small>";
                  }
                ?>
              </a>
              <?php
                if($rwMante['tipo'] == 2 ) {
                  echo '<strong>';
                }
                if($rwMante['horaRel'] == '00:00:00') {
                  echo "";
                } else {
                  echo substr($rwMante['horaRel'], 0, 5)." ";
                }
                echo $rwMante['textoRel'];
                if($rwMante['tipo'] == 2 ) {
                   echo '</strong>';
                }
              ?>
            </p>
          </div>
          <!-- /.item -->
          <!-- chat item -->
          <?php }?>
          <?displayPaginateComponent($incMant_total_records,$incMant_total_pages, $incMant_page, "page");?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. Incidencias Mantener -->

      <!-- Relevo -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Relevo: </h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>

        <!-- /.box-header -->
        <div class="box-body chat" id="chat-box">
          <?php while($rwDia = mysqli_fetch_assoc($inciSv)) { ?>
          <!-- chat item -->
          <div class="item">
            <img src="/ico/users/<?php echo mostrarIcoUser($rwDia['userId']); ?>" alt="user image" class="online">

            <p class="message">
              <?php $enlace = "/ops/referencia/modRelevo.php?idRel=".$rwDia['idRel']; ?>
              <a href="#" onclick="abrirVentana('<?php echo $enlace; ?>')"><i class="fa fa-pencil-square-o"></i> Editar</a>
              <a href="#" class="name">
                <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $rwDia['enviado']; ?></small>
                - <?php echo $rwDia['usNom']." ".$rwDia['usApe']; ?>
                <?php
                  if(!empty($rwDia['modificado'])) {
                    $colMod = explode("/", $rwDia['modificado']);
                    $fechaMod = $colMod[0];
                    $userMod  = $colMod[1];
                    $idMod    = $colMod[2];

                    echo "- Modificado: <small class=\"text-muted\">".$fechaMod." - ".$userMod."</small>";
                  }
                ?>
              </a>
              <?php
                if($rwDia['tipo'] == 2 ) {
                  echo '<strong>';
                }

                if($rwDia['horaRel'] == '00:00:00') {
                  echo "";
                } else {
                  echo substr($rwDia['horaRel'], 0, 5)." ";
                }

                echo $rwDia['textoRel'];
                if($rwDia['tipo'] == 2 ) {
                   echo '</strong>';
                }
              ?>
            </p>
          </div>
          <!-- /.item -->
          <!-- /.chat item -->
          <?php } ?>
          <?displayPaginateComponent($inciSv_total_records,$inciSv_total_pages, $inciSv_page, "pageA");?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. Relevo -->

      <!-- Incidencia de expediente -->
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Incidencias en servicios: </h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>

        <!-- /.box-header -->
        <div class="box-body chat" id="chat-box">
          <?php while($rwExp = mysqli_fetch_assoc($inciExp)) { ?>
          <!-- chat item -->
          <div class="item">
            <img src="/ico/users/<?php echo mostrarIcoUser($rwExp['userInci']); ?>" alt="user image" class="online">

            <p class="message">
              <a href="/ops/mostrar/editServ.php?iden=<?php echo $rwExp['idSv']; ?>" target="_blank"><i class="fa fa-pencil-square-o"></i>
                Ver - <?php echo $rwExp['nomSer']." - ".$rwExp['nombre']." ".$rwExp['apellidos']; ?>
              </a>
              <a href="#" class="name">
                <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $rwExp['enviaInci']; ?></small>
                - <?php echo $rwExp['usNom']." ".$rwExp['usApe']; ?>
              </a>
              <?php echo $rwExp['descInci']; ?>
            </p>
          </div>
          <!-- /.item -->
          <!-- /.chat item -->
          <?php } ?>
          <?displayPaginateComponent($inciExp_total_records,$inciExp_total_pages, $inciExp_page, "pageB");?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /. Incidencia de expediente -->

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
<script>
function abrirVentana(url) {
  window.open(url,'','top=50,left=500,width=500,height=460') ;
}
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
