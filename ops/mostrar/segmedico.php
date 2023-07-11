<?php
session_start();
include '../../functions/function.php';
nonUser();

if(isset($_GET['medSel'])) {
  $medSel = $_GET['medSel'];
} else {
  $medSel = '0';
}

/* Total pacientes */
$totalPac = mysqli_query($gestambu, "SELECT COUNT(segMed) AS pax, segMed, fallecido FROM paciente WHERE segMed > '0' AND fallecido = '0'");
$rwTotalPac = mysqli_fetch_assoc($totalPac);

/* Total Crónicos */

/* Total Paliativos */

/* Total Fallecidos */
$totalFa = mysqli_query($gestambu, "SELECT COUNT(segMed) AS pax, segMed, fallecido FROM paciente WHERE segMed > '0' AND fallecido = '1'");
$rwTotalFa = mysqli_fetch_assoc($totalFa);

/* Médico seleccionado */

$selMed = mysqli_query($gestambu, "SELECT userId, usNom, usApe FROM user WHERE userId = '$medSel'");
$rwSelMed = mysqli_fetch_assoc($selMed);

/* Listado de médicos con pacientes de seguimiento */

$medSeg = mysqli_query($gestambu, "SELECT paciente.segMed, paciente.medAsig, COUNT(paciente.medAsig) AS total, paciente.fallecido,  user.userId, user.usNom, user.usApe, user.usCate
  FROM paciente
    LEFT JOIN user ON paciente.medAsig = user.userId
  WHERE paciente.segMed = '1' AND user.usCate = '7' AND paciente.fallecido = '0'
  GROUP BY paciente.medAsig
  ");

/* Datos de paciente */

$datPac = mysqli_query($gestambu, "SELECT paciente.idPac, paciente.idCia, paciente.segMed, paciente.medAsig, paciente.pNombre, paciente.tlf1, paciente.tlf2, paciente.direccion, paciente.localidad, paciente.pApellidos, paciente.provincia,
  paciente.fallecido, paciente.pauta, cia.idCia, cia.ciaNom
  FROM paciente
    LEFT JOIN cia ON paciente.idCia = cia.idCia
  WHERE paciente.segMed = '1' AND paciente.fallecido = '0' AND paciente.medAsig = '$medSel'
  ");

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Seguimiento Médico</title>
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
  <style>
    .box {
      margin-bottom: 5px;
    }
    .box-body {
      padding: 5px;
    }
    .box-footer {
      padding: 5px;
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
        <i><strong>Seguimiento médico:</strong></i>
        <?php
          if($medSel == '0') {
            echo "Sin médico selecionado";
          } else {
            echo $rwSelMed['usNom']. " ".$rwSelMed['usApe'];
          }
        ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Seguimiento médico</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <div class="clearfix"></div>
              <h3 class="profile-username text-center">Pacientes Actuales</h3>

              <p class="text-muted text-center">- Listado de médicos -</p>

              <ul class="list-group list-group-unbordered">
                <?php while($rwMed = mysqli_fetch_array($medSeg)) { ?>
                <li class="list-group-item">
                  <b>
                    <?php
                      if($medSel == $rwMed['medAsig']) {
                        echo "<i class=\"fa fa-arrow-right\"> </i>";
                      } else {

                      }
                    ?>
                    <a href="segmedico.php?medSel=<?php echo $rwMed['medAsig']; ?>"><?php echo $rwMed['usNom']." ".$rwMed['usApe']; ?></a>
                  </b>
                  <a class="pull-right"><?php echo $rwMed['total']; ?></a>
                </li>
                <?php } ?>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Datos de pacientes</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <strong> Total de pacientes</strong>
              <p class="text-muted"><?php echo $rwTotalPac['pax']; ?></p>

              <strong> Crónicos</strong>
              <p class="text-muted">--</p>

              <strong> Paliativos</strong>
              <p class="text-muted">--</p>

              <strong> Fallecidos</strong>
              <p class="text-muted"><?php echo $rwTotalFa['pax']; ?></p>
              <hr>
              <!-- Notas de paciente
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notas</strong>

              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
              -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <?php if($medSel == '0') { ?>
            <div class="box box-solid box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Sin selección</h3>
              </div><!-- /.box-header -->
              <div class="box-body">
                No hay médico seleccionado
              </div><!-- /.box-body -->
            </div><!-- /.box -->
          <?php
            } else {
              while($rwDatPac = mysqli_fetch_array($datPac)) {
                $pacSeg = $rwDatPac['idPac'];
                $diaSeg = mysqli_query($gestambu, "SELECT idSv, idPac, DATE_FORMAT(fecha,'%d-%m-%Y' ) AS fechaFor, tipo, estServ
                  FROM servicio
                  WHERE tipo = '9' AND idPac = '$pacSeg'
                  ORDER BY fecha DESC
                  LIMIT 3
                  ");
          ?>
          <div class="box box-solid box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwDatPac['idPac']; ?>" title="Ver ficha"><?php echo $rwDatPac['pNombre']." ".$rwDatPac['pApellidos']." - ".$rwDatPac['ciaNom']." - ";  ?></a>
              </h3>
              <div class="box-tools pull-right">
                <!-- los botones se posicionan aquí -->
                <span class="label label-primary"><?php echo calculoPauta($rwDatPac['pauta']); ?></span>
              </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
              <?php echo "<strong>Dirección:</strong> ".$rwDatPac['direccion']." - ".$rwDatPac['localidad']; ?>
              <div>
                <?php echo "<strong>Tlf1.</strong> ".$rwDatPac['tlf1']; ?>  <?php echo "<strong>Tlf2.</strong> ".$rwDatPac['tlf2']; ?>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
              <?php
              while($rwSeg = mysqli_fetch_array($diaSeg)) {
                echo "<strong>Fecha: </strong><a href=\"/ops/mostrar/editServ.php?iden=".$rwSeg['idSv']."\">".$rwSeg['fechaFor']."</a><br/> ";
              }
              ?>
            </div><!-- box-footer -->
          </div><!-- /.box -->
          <?php } } ?>
        </div>
        <!-- /.col -->
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
<!-- Carga de tab -->
</body>
</html>
<?php
mysqli_close($gestambu);
?>
