<?php
session_start();
include '../../../functions/function.php';
nonUser();

if(isset($_POST['fIni'])) {
  $diaIni = $_POST['fIni'];
} else {
  $diaIni = date("Y-m-d");
}

if(isset($_POST['fFin'])) {
  $diaFin = $_POST['fFin'];
} else {
  $diaFin = date("Y-m-d");
}

$lisTable = mysqli_query($gestambu, "SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.tlf1, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed, paciente.tipoSeg,
    paciente.medAsig, paciente.pauta, paciente.fallecido, cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe, provincias.id, provincias.provincia AS nomProv
  FROM paciente
    LEFT JOIN cia ON paciente.idCia = cia.idCia
    LEFT JOIN user ON paciente.medAsig = user.userId
    LEFT JOIN provincias ON paciente.provincia = provincias.id
  WHERE paciente.segMed > 0
  ORDER BY paciente.pNombre ASC
  ");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Tabla de seguimiento </title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/docs/bootstrap/css/bootstrap.min.css">
  <!-- Fuentes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/docs/plugins/datatables/dataTables.bootstrap.css">
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
  <style>
  @media print {
    a[href]:after {
      content: none !important;
    }
  }
  .aumText {
    font-size: 0.88em;
  }
  .table-bordered>tbody>tr>td {
    padding: 4px;
    border: 0px solid #f4f4f4;
  }
  .table-striped>tbody>tr:hover {
    background-color: #f1e8bd;
  }
  </style>
</head>
<!-- Se agrega la clase sidebar-collapse para ocultar el menu en la carga del sitio -->
<!-- fixed para mantener menu, pero al estar minimizado se expande automanicamente,
fixed no es compatible con sidebar-mini -->
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<!-- Barra de sitio -->
<div class="wrapper">

<?php include '../../inc/supbar.php'; ?>

<?php include '../../inc/menubar.php'; ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <!-- fecha actual o fecha si se escoge otra para visualizar -->
        <!-- Espacio reservado para texto -->
        <small><?php echo fechaEs(); ?></small>
      </h1>
      <!-- Navegador de posición de página (migas) -->
      <ol class="breadcrumb">
        <li class="active"><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Tabla de seguimiento</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Pacientes con seguimiento médico: </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped aumText">
            <thead>
            <tr>
              <th>#</th>
              <th>Cia.</th>
              <th>provincia</th>
              <th>Tipo</th>
              <th>Paciente</th>
              <th>Tlf</th>
              <th>Dirección</th>
              <th>Localidad</th>
              <th>Médico</th>
              <th>Pauta</th>
              <th>--</th>
            </tr>
            </thead>
            <tbody>
            <?php
              while($rwList = mysqli_fetch_assoc($lisTable)) {
                if($rwList['tipoSeg'] == 1 ) {
                  $tipoSeg = "Crónico";
                } elseif ($rwList['tipoSeg'] == 2) {
                  $tipoSeg = "Paliativo";
                } else {
                  $tipoSeg = " -- ";
                }
                if($rwList['fallecido'] == '1') {
                  $obser = "FALLECIDO";
                } else {
                  if($rwList['segMed'] == '2') {
                    $obser = "SIN SEGUIMIENTO";
                  } else {
                    $obser = " -- ";
                  }
                  $obser = " -- ";
                }
            ?>
            <tr>
              <td><a href="/ops/mostrar/fichaPac.php?iden=<?php echo $rwList['idPac']; ?>">Ficha</a></td>
              <td><?php echo $rwList['ciaNom']; ?></td>
              <td><?php echo $rwList['nomProv']; ?></td>
              <td><?php echo $tipoSeg; ?></td>
              <td><?php echo $rwList['pNombre']." ".$rwList['pApellidos']; ?></td>
              <td><?php echo $rwList['tlf1']; ?></td>
              <td><?php echo $rwList['direccion']; ?></td>
              <td><?php echo $rwList['localidad']; ?></td>
              <td><?php echo $rwList['usNom']." ".$rwList['usApe']; ?></td>
              <td><?php echo calculoPauta($rwList['pauta']); ?></td>
              <td><?php echo $obser; ?></td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include '../../inc/pie.php'; ?>

<?php include '../../inc/bcontrol.php'; ?>

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->
<script src="/docs/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="/docs/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="/docs/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/docs/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/docs/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/docs/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/docs/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/docs/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({
      //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
      "pageLength": 25,
      "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
