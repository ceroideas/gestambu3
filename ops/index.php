<?php
session_start();
include '../functions/function.php';
nonUser();

/* Valores para selección de soporte */
# Obtener provinca
if (isset($_GET['prov'])) {
  $prov = $_GET['prov'];
} else {
  $prov = 29;
}
# Ver servicios finalizados
if (isset($_GET['final'])) {
  $esp = $_GET['final'];
} else {
  $esp = 0;
}
# Conseguir fecha de ayer
if (isset($_GET['ayer'])) {
  $ayer = $_GET['ayer'];
  if ($ayer == '1') {
    $hoy = date('Y-m-d');
    $filFecha = strtotime('-1 day', strtotime($hoy));
    $filFecha = date('Y-m-d', $filFecha);
  }
} else {
  $ayer = '0';
  $filFecha = date('Y-m-d');
}
# Conseguir fecha de mañana
if (isset($_GET['manana'])) {
  $manana = $_GET['manana'];
  if ($manana == '1') {
    $hoy = date('Y-m-d');
    $filFecha = strtotime('+1 day', strtotime($hoy));
    $filFecha = date('Y-m-d', $filFecha);
  } else {
    $mañana = '0';
  }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Principal | GestAmbu 3.0</title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../docs/bootstrap/css/bootstrap.min.css">
  <!-- Fuentes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tema -->
  <link rel="stylesheet" href="../docs/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="../docs/dist/css/skins/skin-blue.min.css">
  <!-- Table Expandible -->
  <link rel="stylesheet" href="../docs/plugins/tableExp/css/bootstrap-table-expandable.css">

  <link rel="icon" type="image/png" sizes="16x16" href="../ico/favicon-16x16.png">
  <link rel="stylesheet" href="/ops/css/index.css">

  <script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="/docs/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="/docs/plugins/datatables/dataTables.bootstrap.min.js"></script>


</head>

<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
  <!-- Barra de sitio -->
  <div class="wrapper">


    <?php include 'inc/supbar.php'; ?>

    <?php include 'inc/menubar.php'; ?>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          <!-- fecha actual o fecha si se escoge otra para visualizar -->
          <!-- Espacio reservado para texto -->
          <small>
            <?php echo fechaEs(); ?>
          </small>
        </h1>
        <!-- Navegador de posición de página (migas) -->
        <ol class="breadcrumb">
          <?php include 'inc/extracciones.php'; ?>
          <li class="" title="Nueva nota para vehículo"><a href="#" onclick="nuevaNota()"><i class="fa fa-file"></i>
              Nueva nota</a></li>
          <li class=""><a href="referencia/notas/lstNotas.php"><i class="fa fa-sort-alpha-desc"></i> Lista de notas</a>
          </li>
          <li class=""><a href="/ops/servicios/lstvh/listVehiculos.php"><i class="fa fa-ambulance"></i> Vehículos
              registrados</a></li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="row">
          <div class="col-xs-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Servicios con incidencias</h3>
                <!-- opciones para poder cerrar ventana o contraer -->
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Minimizar">
                    <i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                    title="Cerrar">
                    <i class="fa fa-times"></i></button>
                </div>
              </div>

              <div class="box-body" id="mensajesHoras">
                <!-- mensajes con código de color -->

              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Servicios para: </h3><strong>
                  <?php provValor($prov); ?>
                </strong> -
                <?php if ($esp == '1') {
                  echo "Finalizados";
                } ?>
                <i>
                  <?php if (isset($ayer) && $ayer == '1') {
                    echo "Ayer";
                  } ?>
                </i>
                <i>
                  <?php if (isset($manana) && $manana == '1') {
                    echo "Mañana";
                  } ?>
                </i>
              </div>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a id="ambuClick" href="#tab_1" data-toggle="tab">Ambulancia</a></li>
                  <li><a id="medicoClick" href="#tab_2" data-toggle="tab">Médico</a></li>
                  <li><a id="dueClick" href="#tab_3" data-toggle="tab">Enfermero</a></li>
                  <li><a id="vuelosClick" href="#tab_4" data-toggle="tab">Vuelos</a></li>
                  <li><a id="rutaClick" href="#tab_5" data-toggle="tab">Ruta</a></li>
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      Provincias <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=0">General</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=11">Cádiz</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=14">Córdoba</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=21">Huelva</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=29">Málaga</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=52">Melilla</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="?prov=41">Sevilla</a></li>
                      <!-- Enlace a parte
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                    -->
                    </ul>
                  </li>
                  <li class="pull-right dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      <i class="fa fa-gear"></i> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li role="presentation"><a role="menuitem" tabindex="-1"
                          href="?prov=<?php echo $prov; ?>&final=1">Finalizados</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1"
                          href="?prov=<?php echo $prov; ?>&ayer=1">Ayer</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1"
                          href="?prov=<?php echo $prov; ?>&manana=1">Mañana</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="/ops/indexFecha/inFecha.php">Por
                          fechas</a></li>
                      <!-- Enlace a parte -->
                      <li role="presentation" class="divider"></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1"
                          href="/ops/referencia/supIndex/tablaServ.php">Tabla de servicios</a></li>
                    </ul>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <div id="ambuTab">
                      <?php include 'tabIndex/ambulanceTab.php'; ?>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                    <div id="medicoTab">
                      <?php include 'tabIndex/medicTab.php'; ?>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_3">
                    <div id="dueTab">
                      <?php include 'tabIndex/nurseTab.php'; ?>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane " id="tab_4">
                    <div id="vuelosTab">
                      <?php include 'tabIndex/vuelosTab.php'; ?>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_5">
                    <div id="rutaTab">
                      <?php include 'tabIndex/routeTab.php'; ?>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>

      </section>

      <div id="modal-test" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Contenido modal - ver servicio-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 id="modal-title" class="modal-title">Servicio para:
              </h4>
            </div>
            <div id="modal-body" class="modal-body">
              <div id="textoCopiar-<?php echo $rwAmbTab['idSv']; ?>">
                <?php
                echo " " . $textMsj . " " . date('H:i', strtotime($rwAmbTab['hora'])) . "-" . $rwAmbTab['ciaNom'] . "-" . $rwAmbTab['nombre'] . "-"
                  . $rwAmbTab['nomSer'] . "-" . $rwAmbTab['recoger'] . "-" . $rwAmbTab['locRec'] . "-" . $rwAmbTab['trasladar'] . "-"
                  . $rwAmbTab['locTras'] . "-" . $rwAmbTab['obs'] . "-" . ambComple($rwAmbTab['recurso'], $rwAmbTab['enfermero'], $rwAmbTab['medico'], $rwAmbTab['nomRecu']);
                ?></div>
              <?php ambComple($rwAmbTab['recurso'], $rwAmbTab['enfermero'], $rwAmbTab['medico'], $rwAmbTab['nomRecu']); ?>
            </div>
            <div id="modal-footer" class="modal-footer">

             
            </div>
          </div>

        </div>
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include 'inc/pie.php'; ?>

    <?php //include 'inc/bcontrol.php'; 
    ?>

    <!-- ./wrapper -->

    <!-- jQuery 2.2.3 -->

    <!-- Bootstrap 3.3.6 -->
    <script src="../docs/bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../docs/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../docs/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../docs/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../docs/dist/js/demo.js"></script>
    <!-- Table Expandible -->
    <script src="../docs/plugins/tableExp/js/bootstrap-table-expandable.js"></script>
    <!-- Carga de tab -->
    <script type="text/javascript">
      /* Carga tab sin cargar toda la db
       * Se deja ambulancia como permanente
       * la función autoactualizar solamente funciona para ambulancia ya que al clicar
       * en las demás pestañas se autoactualizaran solas
       * Recarga solamente la tab ambulancia, ya que, al moverse por las siguientes tabs
       * Se recargan automáticamente
       */


      /** 

      $("#vuelosClick").click(function() {
        $.get('tabIndex/vuelosTab.php', {
          coche: "Ford",
          modelo: "Focus",
          color: "rojo"
        }, function(htmlexterno) {
          $("#vuelosTab").html(htmlexterno);
        });
      });

      */


      function cargarMensa() {
        $.get('tabIndex/tabMensajes.php', function (htmlexterno) {
          $("#mensajesHoras").html(htmlexterno);
        });
      }
      $.get('tabIndex/tabMensajes.php', function (htmlexterno) {
        $("#mensajesHoras").html(htmlexterno);
      });

      setInterval(cargarMensa, 180000); // 180000 = 3 minutos

      function nuevaNota() {
        open('/ops/referencia/notaVh.php', '', 'top=50,left=500,width=500,height=400');
      }


      function copiarAlPortapapeles(id_elemento, copyAnswer) {
        var aux = document.createElement("input");

        aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);

        var answer = document.getElementById(copyAnswer);
        var successful = document.execCommand('copy');

        if (successful) answer.innerHTML = '¡ Copiado !';
      }
      function limpiar(titulo,body, id) {
        
        document.getElementById('modal-title').innerHTML = `<h4 id="modal-title" class="modal-title">Servicio para: ${titulo} </h4>`
        document.getElementById('modal-body').innerHTML = `
        <div id="textoCopiar-${id}">
        ${body}
        </div>`;
        document.getElementById('modal-footer').innerHTML = `<button id="modal-footer" type="button" class="btn btn-default"
                onclick="copiarAlPortapapeles('textoCopiar-${id}', 'copyAnswer-${id}' )">Copiar
                texto</button> 
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                `;    
        
      }
    </script>
</body>

</html>
<?php
mysqli_close($gestambu);
?>