<?php
session_start();
include '../../../functions/function.php';
include '../../../API/sanitas/entidades/sanitasdemanda.php';
require_once '../../../API/sanitas/dao/sanitasdemandadao.php';
nonUser();

/* Carga de datos */
# Col estado_proceso = 0 : pendiente de guardar
# Col estado_proceso = 1 : Guardado
# Col estado_proceso = 2 : modficado
# Cuando Asisa envía servicio modificado: col nuevo = 2
# Realizar comprovación y si el número de registros es 0, tiene que tratarlo como nuevo servicio



$dao = new SanitasDemandaDAO();
$dao->setConexionDB($gestambu);
$arraySanitas = $dao->selectSanitasDemanda($dao::MODO_SELECT_DEMANDAS_NUEVAS);
$numCol = count($arraySanitas);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Demanda Sanitas</title>
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
  <!-- Table Expandible -->
  <link rel="stylesheet" href="/docs/plugins/tableExp/css/bootstrap-table-expandable.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="icon" type="image/png" sizes="16x16" href="../ico/favicon-16x16.png">
  <style>
  .sinmar {
    margin: 2px;
    padding-top: 3px;
    padding-bottom: 3px;
  }
  .table>tbody>tr>td {
    /* padding: 2px; */
  }
  .aumText {
    font-size: 0.81em;
  }
  .alert a {
    text-decoration: none;
    color: #98FB98;
  }
  @media print {
    a[href]:after {
      content: none !important;
    }
  }
  .col-xs-12 {
	  padding-left: 5px;
	  padding-right: 5px;
  }
  .nav-tabs-custom>.tab-content {
	  padding: 2px;
  }
  .flotIco {
	  float: left;
  }
  table {
	  border-spacing: 1px;
  }
  .table-hover tbody tr:hover td {
    background: #FFFFCC;
  }
  tr.colorZebra {
	 background-color: #FFF0FB;
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
		<?php include '../../inc/extracciones.php'; ?>
        <li class=""><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Demanda Sanitas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->

      <div class="row">

        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Servicios pendientes por guardar: </h3><strong> </strong>
            </div>
            <div class="box-body table-condensed no-padding">
              <table class="table table-hover h6">
                <thead>
                  <th>ID</th>
                  <th>Comienzo</th>
                  <th>Hora</th>
                  <th>Servicio</th>
                  <th>Tipo</th>
                  <th>Sesiones</th>
                  <th>Vehículo</th>
                  <th>Código</th>
                  <th>Prioridad</th>
                  <th>Paciente</th>
                  <th>Origen</th>
                  <th>Poblacion</th>
                  <th>Destino</th>
                  <th>Poblacion</th>
                  <th>Obs.</th>
                  <th>Acciones</th>
                </thead>
                <?php
                if($numCol == 0 ) {
                  echo "<tr><td colspan=\"16\">No hay nuevos registros para guardar.</td></tr>";
                } else {
                    foreach($arraySanitas as  $demandaSanitas) {

                  /*  $recep = explode(" ", $rwAsisa['fecharecepcion']);
                    $recepFecha = $recep[0];
                    $nuevo      = $rwAsisa['nuevo'];
                    $tipoServ   = $rwAsisa['tipo_servicio'];
                    $tipoVh     = $rwAsisa['tipo_vehiculo'];
                    $coDeman    = $rwAsisa['cod_demanda'];
					$codServ    = $rwAsisa['cod_servicio'];*/
					
// 					/* Modificado pero sin registro en tabla servicios */									
// 					$compModi = mysqli_query($gestambu, "SELECT coDemanda FROM servicio WHERE coDemanda = '$coDeman'");
// 					$numCompModi = mysqli_num_rows($compModi);
					
//                     //Muestra la fecha de inicio del servicio
//                     $sqlInicio = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, COUNT(cod_demanda) AS nSesiones, estado FROM asisaasistencia WHERE cod_demanda = '$coDeman'");
//                     $rwIni = mysqli_fetch_assoc($sqlInicio);
//                     $valorEstado = $rwIni['estado'];
					
// 					if($codServ > 60 && $codServ < 68) {
// 						$sqlSes = mysqli_query($gestambu, "SELECT  cod_demanda, fecha_asistencia, cod_demanda, estado FROM asisaasistencia WHERE cod_demanda = '$coDeman'");
// 					} else {
// 						$sqlSes = mysqli_query($gestambu, "SELECT  cod_demanda, fecha_asistencia, cod_demanda, estado FROM asisaasistencia WHERE cod_demanda = '$coDeman' AND vuelta='N'");
// 					}
// 					$numSes = mysqli_num_rows($sqlSes);
//                     // Tiene en cuenta los servicios anulados porque asisa puede activarlos y no tendríamos constancia de ellos
//                     if($numSes > 1) {
//                       //Es servicio continuado
//                       $servCont = 1;
//                     } else {
//                       //Es servicio único
//                       $servCont = 0;
//                     }

                     //Muestra servicio nuevo o modificado
                    if($demandaSanitas->getEstado_proceso() == SanitasDemanda::ESTADO_PROCESO_NUEVO) {
                       $textNuevo = "NUEVO";
                       $etiNuevo  = "success";
                       $textGuardar = "Guardar";
                       $colorGuardar = "btn-warning";
                    }else if($demandaSanitas->getEstado_proceso() == SanitasDemanda::ESTADO_PROCESO_SERVICIO_MODIFICABLE){
                        $textNuevo = "MODIFICADO";
                        $etiNuevo  = "warning";
                        $textGuardar = "Modificar";
                        $colorGuardar = "btn-warning";	
                    }

//                     } elseif($nuevo == '2') {
//                       //Comprueba si es continuado o único
//                       //Si es continuado, solamente muestra "modificado"
//                       if($servCont == '1') {
// 						if($numCompModi == '0') {
// 							$textNuevo = "Nuevo-Mod";
// 							$etiNuevo  = "warning";
// 							$textGuardar = "Guardar";
// 							$colorGuardar = "btn-warning";						
// 						} else {
// 							$textNuevo = "MODIFICADO";
// 							$etiNuevo  = "warning";
// 							$textGuardar = "Modificar";
// 							$colorGuardar = "btn-warning";						
// 						}
//                       } else {
//                         if($valorEstado == '6') {
//                           $textNuevo = "CANCELADO";
//                           $etiNuevo  = "danger";
//                           $textGuardar = "Cancelado";
//                           $colorGuardar = "btn-danger";
//                         } elseif($valorEstado == '7') {
//                           $textNuevo = "ANULADO";
//                           $etiNuevo  = "danger";
//                           $textGuardar = "Anulado";
//                           $colorGuardar = "btn-danger";
//                         } else {
// 							if($numCompModi == '0') {
// 								$textNuevo = "Nuevo-Mod";
// 								$etiNuevo  = "warning";
// 								$textGuardar = "Guardar";
// 								$colorGuardar = "btn-warning";						
// 							} else {
// 								$textNuevo = "MODIFICADO";
// 								$etiNuevo  = "warning";
// 								$textGuardar = "Modificar";
// 								$colorGuardar = "btn-warning";						
// 							}
//                         }
//                       }
//                     }

//                     //Muestra tipo de servicio urgente, programado o continuado
//                     if($servCont == 1) {
//                       $texTipo = "CONTINUADO";
//                       $etiTipo = "warning";
//                     } else {
//                       if($tipoServ == 'U') {
//                         $texTipo = "URGENTE";
//                         $etiTipo = "danger";
//                       } elseif($tipoServ == 'P') {
//                         $texTipo = "PROGRAMADO";
//                         $etiTipo = "primary";
//                       }
//                     }
// 					//Muestra la prioridad del servicio
                        if($demandaSanitas->getIdPrioridad()== 3) {
 						   $prioText = "<span class=\"label label-danger\"> MUY URGENTE</span>";
                        } elseif($demandaSanitas->getIdPrioridad() == 2) {
						   $prioText = "<span class=\"label label-warning\"> URGENTE</span>";
				        } elseif($demandaSanitas->getIdPrioridad() == 1) {
 						   $prioText = "<span class=\"label label-info\"> NORMAL</span>";
 					    } 
					
					
//                     //Muestra tipo de vehículo
//                     if($tipoVh == '1') {
//                       $textVh = "AMB";
//                     } elseif($tipoVh == '2') {
//                       $textVh = "<strong> U.V.I.</strong>";
//                     } else {
//                       $textVh = "";
//                     }*/
                ?>
                <tr>
                  <td><?php echo $demandaSanitas->getIdAviso(); ?></td>
                  <td><?php echo date_format($demandaSanitas->getFecha_aviso(),SanitasDemanda::FORMATO_FECHA_DEMANDAS_GESTAMBU); ?></td>
                  <td><?php echo date_format($demandaSanitas->getFecha_aviso(),SanitasDemanda::FORMATO_HORA_DEMANDAS_GESTAMBU); ?></td>
                  <td><span class="label label-<?php echo $etiNuevo; ?>"><?php echo $textNuevo; ?></span></td>
                  <td><span class="label label-<?php //echo $etiTipo; ?>"><?php //echo $texTipo; ?></span></td>
                  <td><?php //echo $numSes; ?></td>
                  <td><?php //echo $textVh; ?></td>
                  <td><?php //echo $rwAsisa['descripcion']; ?></td>
                  <td><?php echo $prioText; ?></td>
                  <td><?php echo $demandaSanitas->getNombre()." ".$demandaSanitas->getApellido1()." ".$demandaSanitas->getApellido2(); ?></td>
                  <td><?php echo $demandaSanitas->getOrigenDireccion();?></td>
                  <td><?php echo $demandaSanitas->getOrigenMunicipioNombre(); ?></td>
                  <td><?php echo $demandaSanitas->getDestinoDireccion();?></td>
                  <td><?php echo $demandaSanitas->getDestinoMunicipioNombre(); ?></td>
                  <td><?php //echo $rwAsisa['observaciones_s']; ?></td>
                  <td>
                    <a  <?php echo "href=\"/ops/referencia/demanda/sanitasnserv.php?idemanda=".$demandaSanitas->getIdAviso()."\""; ?> class="btn btn-block <?php echo $colorGuardar; ?> btn-sm">
                    	<?php echo $textGuardar; ?>
					</a>
                  </td>
                </tr>
              <?php } }?>
              </table>
            </div>
            <!-- /.box-body -->
            </div>
          </div>
          <!-- /.box -->
        </div>

      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php //include 'inc/pie.php'; ?>

<?php //include 'inc/bcontrol.php'; ?>

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
<!-- Table Expandible -->
<script src="/docs/plugins/tableExp/js/bootstrap-table-expandable.js"></script>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
