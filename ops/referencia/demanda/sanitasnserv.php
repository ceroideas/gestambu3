<?php
session_start();
include '../../../functions/function.php';
include '../../../API/sanitas/entidades/sanitasdemanda.php';
include '../../../API/sanitas/entidades/sanitasasistencia.php';
require_once '../../../API/sanitas/dao/sanitasdemandadao.php';
require_once '../../../API/sanitas/dao/sanitasasistenciadao.php';
require_once '../../../API/sanitas/dao/sanitasdao.php';
nonUser();


/* Guardado de datos en la DB al enviar formulario*/
if(@$_POST['guardar'] == 'enviar') {
    $daoAsistencia = new SanitasAsistenciaDAO();
    $daoAsistencia->setConexionDB($gestambu);
    $aux = new SanitasAsistencia();
    $aux->setEstado(0);
    $aux->setFecha_asistenciaNow();
    $aux->setIdAviso($_POST['idAvisoSanitas']);
    $result = $daoAsistencia->insertSanitasAsistencia($aux);
    if($result == true){
        $mensa = "Servicio guardado correctamente";
        $mensaOk  = 1;
    }else{
        $textInfo = "Error guardando el servicio";
        $mensaOk  = 0;
    }
}

/* Recogida de datos tabla sanitas */
$idemanda = $_GET['idemanda'];
$dao = new SanitasDemandaDAO();
$dao->setConexionDB($gestambu);
$param = ["idAviso"=>$idemanda];
$arraySanitas = $dao->selectSanitasDemanda($dao::MODO_SELECT_DEMANDA_ID,$param);
$demandaSanitas =  $arraySanitas[0];





// $asisAsisa = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado,  COUNT(cod_demanda) AS numSesion FROM asisaasistencia WHERE cod_demanda ='$codeAsisa'");
// $rwAst = mysqli_fetch_assoc($asisAsisa);
// $fechaAsist= $rwAst['fecha_asistencia'];

// if($rwAst['numSesion'] > 1 ) {
//   $serIdvta = 1;
// } else {
//   $serIdvta = 0;
// }





$daoSanitas = new SanitasDAO();
$daoSanitas->setConexionDB($gestambu);
$param2 = ["codigo_servicio"=>$demandaSanitas->getTipo_aviso(),"codigo_recurso"=>$demandaSanitas->getTipo_recurso(), "codigo_sanitas"=>$demandaSanitas->getCodigoSanitas()];
$resultado = $daoSanitas->selectSanitasCODIGO($daoSanitas::MODO_SELECT_CODIGOS_SANITAS,$param2);
if(isset($resultado) && sizeof($resultado) > 0){
    $rwTipSanitas = $resultado[0];
    $_SESSION['tipo_sanitas'.$demandaSanitas->getTipo_aviso().$demandaSanitas->getTipo_recurso().$demandaSanitas->getCodigoSanitas()]=$resultado[0];
}



/* Calculo de ida/vueta */
include 'ref/filtronserv.php';

/* Datos para selección */



/*Esto esta a pincho de primeras hay que ver si va ser algo configurable y modificar en consecuencia*/
# Tipo de servicio
if(isset($_SESSION['tipo_servicio']) && count($_SESSION['tipo_servicio']) >0){
    $tServ =$_SESSION['tipo_servicio'];
}else{
    $tServ = mysqli_query($gestambu,
        "SELECT idServi, nomSer
  FROM servi
  ORDER BY nomSer ASC
  ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($tServ)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['tipo_servicio'] = $aux;
    $tServ =$_SESSION['tipo_servicio'];
}





/*Esto esta a pincho de primeras hay que ver si va ser algo configurable y modificar en consecuencia*/
# Tipo de recurso
//$tRecu = SanitasDemanda::TIPOS_POSIBLES_RECURSOS;
if(isset($_SESSION['tipo_recurso']) && count($_SESSION['tipo_recurso']) >0){
    $tRecu =$_SESSION['tipo_recurso'];
}else{
    $tRecu = mysqli_query($gestambu,
        "SELECT idRecu, nomRecu
  FROM recurso
  ORDER BY nomRecu ASC
  ");
    $aux = array();
    while($rRecu2 = mysqli_fetch_assoc($tRecu)) {
        array_push($aux,$rRecu2);
    }
    $_SESSION['tipo_recurso'] = $aux;
    $tRecu =$_SESSION['tipo_recurso'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Nuevo servicio Sanitas</title>
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
  <style>
  .box-body {
	  background-color: #e8eff1;
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
        Formulario de nuevo servicio
        <small>Datos Sanitas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/ops/index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Nuevo servicio Sanitas</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo servicio SANITAS<?php if(isset($barraEst)) { echo " - ".$barraEst; } ?></h3>

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
                <div class="col-md-1"></div>
                <div class="col-md-10">
                <!-- Mensajes -->
                <?php if(isset($_POST['guardar']) && $mensaOk == 1) { ?>
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
                  - Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
                </div>
                <?php }  if(isset($textInfo)) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="danger" aria-hidden="true">&times;</button>
                  <i class="icon fa fa-exclamation-triangle"></i> <?php echo $textInfo; ?>
                  - Volver a <a href="/ops/index.php"><i class="icon fa fa-home"></i> inicio</a>
                </div>
                <?php } ?>
                <!-- /Mensajes -->
                  <div class="col-md-6 col-sm-6 col-xs-8 form-group">
                    <label id="valCia">Compañía: </label>
                    <select class="form-control" name="idCia" id="idCia">
					  <option value="">-- Selecciona compañía --</option>
					  <?php 
						echo "<option value='3'  selected>Sanitas</option>\n";
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-4 form-group">
                    <label id="valProv">Provincia: </label>
                    <select class="form-control" name="provincia" id="prov" required>
                      <?php $provincia= $demandaSanitas->getOrigenIdProvincia(); ?>
                      <option value="">-- Selecciona Provincia --</option>
					  <option value="11" <?php if($provincia == '11') { echo "selected"; } ?>>Cádiz</option>
					  <option value="14" <?php if($provincia == '14') { echo "selected"; } ?>>Córdoba</option>
                      <option value="29" <?php if($provincia == '29') { echo "selected"; } ?>>Málaga</option>
					  <option value="52" <?php if($provincia == '52') { echo "selected"; } ?>>Melilla</option>
					  <option value="21" <?php if($provincia == '21') { echo "selected"; } ?>>Huelva</option>
                      <option value="41" <?php if($provincia == '41') { echo "selected"; } ?>>Sevilla</option>					  
                    </select>
                  </div>

                  <div class="clearfix"></div>

                  <!-- Datos de identificación del paciente -->
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Documento Paciente: </label>
                    <input type="text" class="form-control" placeholder="Documento paciente" name="DNIPac" value="<?php echo  $demandaSanitas->getClienteDocumento();?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Póliza: </label>
                    <input type="text" class="form-control" placeholder="Póliza" name="poliza" value="<?php echo $demandaSanitas->getClientePoliza(); ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                    <label>Autorización: </label>
                    <input type="text" class="form-control" placeholder="Autorización" name="autorizacion" value="<?php echo $demandaSanitas->getAutorizacion(); ?>">
                  </div>
                  <div class="form-group col-md-3 col-sm-3 col-xs-3">
                   <!--   <label>Delegación: </label>
                      <select class="form-control" name="delegacion">
                        <option value="0">-- No definida --</option>
                        <?php
                         /* while($rDelg = mysqli_fetch_assoc($lsDeleg)) {
                            if($rwAsisa['delegacion'] == $rDelg['id']) {
                              $seleccion = "selected";
                            } else {
                              $seleccion = "";
                            }
                            echo "<option value='".$rDelg['id']."' ".$seleccion.">".$rDelg['provincia']."</option>\n";
                          }*/
                         ?>
                      </select>-->
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valFecha">Fecha: </label>
                    <div class="input-group">
                      <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo date_format($demandaSanitas->getFecha_aviso(),SanitasDemanda::FORMATO_FECHA_DEMANDAS_FRONT); ?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valHora">Hora: </label>
                    <div class="input-group">
                      <input type="time" class="form-control" name="hora" id="hora" value="<?php echo date_format($demandaSanitas->getFecha_aviso(),SanitasDemanda::FORMATO_HORA_DEMANDAS_GESTAMBU); ?>" required="">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Oxígeno
                        <input class="col-md-8" type="checkbox" class="minimal" name="ox" id="ox" value="1" <?php  //if($rwAsisa['amb_oxigeno'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Rampa
                        <input class="col-md-8" type="checkbox" class="minimal" name="rampa" id="rampa" value="1" <?php //  if($rwAsisa['amb_rampa'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                      <label>Técnicos
                        <input class="col-md-8" type="checkbox" class="minimal" name="dostec" id="dostect" value="1" <?php // if($rwAsisa['amb_dostecnicos'] == 'S') { echo "checked"; }; ?>>
                      </label>
                    </div>
                  </div>  -->

                  <div class="clearfix"></div>

                  <!-- Referente al tipo de servicio -->
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valTipo">Tipo: </label>
                      <select class="form-control" name="tipo" id="tipo">
                        <option value="">-- Tipo de servicio --</option>
                        <?php
                           foreach($tServ as $rServ){ 
                            if($rwTipSanitas['idServi'] == $rServ['idServi']) {
                              $seleccion = "selected";
                            } else {
                              $seleccion = "";
                            }
                            echo "<option value='".$rServ['idServi']."' ".$seleccion.">".$rServ['nomSer']."</option>\n";
                          }
                         ?>
                      </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label id="valRecurso">Recurso: </label>
                      <select class="form-control" name="recurso" id="recurso">
                        <option value="">-- Recurso --</option>
                        <?php
                        foreach($tRecu as $rRecu){
                            if($rwTipSanitas['idRecu'] == $rRecu['idRecu']) {
                                $seleccion = "selected";
                            } else {
                                $seleccion = "";
                            }
                            echo "<option value='".$rRecu['idRecu']."' ".$seleccion.">".$rRecu['nomRecu']."</option>\n";
                        }
                       /* foreach ($tRecu as $clave => $valor) {
                            if($demandaSanitas->getTipo_recurso() == $clave) {
                              $seleccion = "selected";
                            } else {
                              $seleccion = "";
                            }
                            echo "<option value='".$clave."' ".$seleccion.">".$valor."</option>\n";
                          }*/
                         ?>
                      </select>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <!--  <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valMedico">Med.
                        <input class="col-md-8" type="checkbox" class="minimal" name="medico" id="medico" value="1" <?php// if($rwTipAsisa['idRecu'] == '4' || $rwTipAsisa['idRecu'] == '3' || $rwAsisa['amb_medico'] == 'S') { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valDue">Due
                        <input class="col-md-8" type="checkbox" class="minimal" name="enfermero" id="due" value="1" <?php //if($rwTipAsisa['idRecu'] == '2' || $rwTipAsisa['idRecu'] == '3' || $rwAsisa['amb_enfermeria'] == 'S') { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Ida
                        <input class="col-md-8" type="checkbox" class="minimal" name="ida" id="idvta" value="2" <?php// if($calI_V == '2') { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="vta" id="idvta" value="3" <?php// if($calI_V == '3') { echo "checked"; } ?>>
                      </label>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                      <label id="valIdavta">Ida/vta
                        <input class="col-md-8" type="checkbox" class="minimal" name="idvta" id="idvta" value="1" <?php //if($calI_V == '1') { echo "checked"; } ?>>
                      </label>
                    </div>-->
                  </div>

                  <div class="clearfix"></div>

                  <!-- Referente al paciente -->
                  <div class="form-group col-md-5 col-sm-6 col-xs-5">
                    <label class="control-label" id="valNombre">Nombre: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="ABONADO o PACIENTE si se desconocen los datos" name="nombre" required id="nombre" value="<?php echo $demandaSanitas->getNombre(); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-7 col-sm-6 col-xs-7">
                    <label class="control-label">Apellidos: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Apellidos" name="apellidos" value="<?php echo $demandaSanitas->getApellido1(). ' '. $demandaSanitas->getApellido2(); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                  </div>


                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 1" name="tlf1"  value="<?php echo $demandaSanitas->getTelefono_1(); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-3 col-xs-3">
                    <label class="control-label">Teléfono: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Teléfono 2" name="tlf2" value="<?php echo $demandaSanitas->getTelefono_2(); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-phone"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Sexo: </label>
                      <select class="form-control" name="sexo">
                        <option value="">---</option>
                        <option value="Mujer" <?php if( $demandaSanitas->getSexo() == 'MUJER') { echo "selected"; }?>>Mujer</option>
                        <option value="Varon" <?php if( $demandaSanitas->getSexo()== 'VARON') { echo "selected"; }?>>Hombre</option>
                      </select>
                  </div>
                  <div class="form-group col-md-1 col-sm-2 col-xs-2">
                    <label class="control-label">Edad: </label>
                    <input type="text" class="form-control" placeholder="Edad" name="edad" value="<?php echo $demandaSanitas->getEdad(); ?>" >
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> -- </label>
                      <select class="form-control" name="edadTit">
                        <option value="ANOS" <?php if($demandaSanitas->getTipoEdad() == 'ANOS') { echo "selected"; }?>>Años</option>
                        <option value="MESES" <?php if($demandaSanitas->getTipoEdad() == 'MESES') { echo "selected"; }?>>Meses</option>
                      </select>
                  </div>

                  <div class="clearfix"></div>
                  <!-- Referente a dónde se realiza el servicio -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label" id="valRecoger">Recoger: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="recoger" id="recoger" value="<?php echo $demandaSanitas->getOrigenDireccion(); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-home"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locRec" value="<?php echo $demandaSanitas->getOrigenMunicipioNombre(); ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  
                <?php if($demandaSanitas->getHospitalOrigen()!=null) { ?>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Hospital de origen: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="hospOrigen" value="<?php echo $demandaSanitas->getHospitalOrigen(); ?>" readonly>
                      <div class="input-group-addon">
                        <i class="fa">H</i>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                  
                  <div class="clearfix"></div>
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label class="control-label">Trasladar: </label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="trasladar" value="<?php echo $demandaSanitas->getDestinoDireccion(); ?>">
                      <div class="input-group-addon">
                        <i class="fa fa-share"></i>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Localidad: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="locTras" value="<?php echo $demandaSanitas->getDestinoMunicipioNombre(); ?>">
                      <div class="input-group-addon">
                        <i class="fa">L</i>
                      </div>
                    </div>
                  </div>
                  
                 <?php if($demandaSanitas->getHospitalDestino()!=null) { ?>
                  <div class="form-group col-md-4 col-sm-4 col-xs-4">
                    <label class="control-label">Hospital de destino: </label>
                    <div class="input-group">
                      <input type="text" class="form-control localidad" name="hospOrigen" value="<?php echo $demandaSanitas->getHospitalDestino(); ?>" readonly>
                      <div class="input-group-addon">
                        <i class="fa">H</i>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                  <div class="clearfix"></div>

                  <!-- textarea -->
                  <div class="form-group col-md-8 col-sm-8 col-xs-8">
                    <label>Observaciones</label>
                    <textarea class="form-control" rows="3" placeholder="Observaciones" name="obs"><?php echo $demandaSanitas->getObservaciones();?></textarea>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label"> Prioridad </label>
                    <!-- Ha de definirse de acuerdo con las prioridadades de las compañías -->
                      <select class="form-control" name="prioridad">
                        <option value="3" <?php if($demandaSanitas->getIdPrioridad() == '3') { echo "selected"; }?>> Muy Urgente </option>
                        <option value="1" <?php if($demandaSanitas->getIdPrioridad() == '1') { echo "selected"; }?>>Normal</option>
                        <option value="2" <?php if($demandaSanitas->getIdPrioridad() == '2') { echo "selected"; }?>>Urgente</option>
                      </select>
                    <input type="time" class="form-control has-feedback" name="hconsulta" title="Hora a la que está citado" value="<?php if(isset($hDCsta) && $hDCsta == 1) { echo muestraHorAsisa($rwAst['hora_asistencia']); } ?>">
                    <span class="help-block h6">H. de consulta</span>
                  </div>
                  <div class="form-group col-md-2 col-sm-2 col-xs-2">
                    <label class="control-label">Demora: </label>
                    <input type="time" class="form-control" name="demora" title="demora de demora dado a la compañía" value="">
                    <input type="time" class="form-control has-feedback" name="hvuelta" title="Hora de vuelta" value="<?php if(isset($mosVta) && $mosVta == 1) { echo muestraHorAsisa($rwSqlVta['hora_asistencia']); } ?>" <?php if(@$mosVta != 1) { echo "readonly"; }?>>
                    <span class="help-block h6">H. Vuelta</span>
                  </div>
                  <!-- col-md-offset-3 -->
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="hidden" name="idAvisoSanitas" value="<?php echo $demandaSanitas->getIdAviso(); ?>" >
                      <input type="hidden" name="coDemanda" value="<?php echo $codeAsisa; ?>" >
                      <input type="hidden" name="idasistencia" value="<?php echo $rwAst['idasistencia']; ?>" >
                      <input type="hidden" name="mensalog" value="<?php echo @$mensaLog; ?>" >
                      <input type="hidden" name="estServ" value="<?php echo @$estRes; ?>" >
                      <input type="hidden" name="prescrip" value="<?php echo $rwAsisa['prescriptor']; ?>" >
                      <input type="hidden" name="activacion" value="<?php echo $rwAsisa['fecharecepcion']; ?>" >
					  <button type="reset" class="btn btn-primary">Cancelar</button>
                      <button type="submit" name="guardar" value="enviar" class="btn btn-success validar">Guardar</button>
                    </div>
                  </div>
                </div>
              <div class="col-md-1"></div>
              </form>
            </div>
            <!-- /.box-body -->
			<!--
            <div class="box-footer">

            </div>
			-->
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

<?php //include '../inc/pie.php'; ?>

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
<!-- Validación para nuevo servicio -->
<script src="../referencia/validarNuevoServicio.js"></script>
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
