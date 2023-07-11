<?php
session_start();
include '../../../functions/function.php';
nonUser();

$usuario = $_SESSION['userId'];

//Recoge variable provincia, si no, adquiere el valor por defecto 29 (Málaga)
if(isset($_GET['codRuta'])) {
  $codRuta=$_GET['codRuta'];
} else {
  $codRuta='29';
}

$paCont = mysqli_query($gestambu, "SELECT servicio.idSv, COUNT(servicio.continuado) AS restantes, servicio.continuado, servicio.codRuta, servicio.idCia, servicio.poliza, servicio.autorizacion, servicio.provincia AS provPac, servicio.tipo, servicio.recurso,
   servicio.delegacion, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ, cia.idCia, cia.ciaNom, servi.idServi, servi.nomSer, servi.icono,
   recurso.idRecu, recurso.nomRecu, refcontinuado.numCont, refcontinuado.sesiones, refcontinuado.pauta, refcontinuado.estCont, serinfo.idSv, serinfo.tconsulta, serinfo.hconsulta, provincias.id, provincias.provincia,
   ruta.codRuta, ruta.nomRuta, ruta.prov, ruta.activa
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
    LEFT JOIN refcontinuado ON servicio.continuado = refcontinuado.numCont
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
    LEFT JOIN provincias ON servicio.delegacion = provincias.id
    LEFT JOIN ruta ON servicio.codRuta = ruta.codRuta
  WHERE servicio.continuado != '0' AND servicio.estServ NOT IN('10', '14', '15') AND servicio.recurso IN('1', '3', '5', '7') AND servicio.codRuta ='$codRuta' AND ruta.activa='1'
  GROUP BY servicio.continuado
  ORDER BY servicio.nombre ASC
  ");



?>
<div class="row">
  <!-- Detalles de continuado -->
  <?php while($rwCont = mysqli_fetch_array($paCont)) { ?>
  <div class="col-md-12">
    <div class="box box-<?php colorFondoCont($rwCont['estCont'], $rwCont['restantes']); ?> box-solid collapsed-box">
      <div class="box-header with-border">
        <span class="info-box-icon"><i class="fa fa-taxi"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><?php echo $rwCont['nombre']." ".$rwCont['apellidos']; ?></span>
          <span class="info-box-number"><?php echo $rwCont['nomSer']; if($rwCont['restantes'] == 1) { echo " - ULTIMA"; }; if($rwCont['codRuta'] == '0' && $rwCont['recurso'] == '7') { echo " <i>¡¡¡ Ruta no asignada !!!</i>"; }?></span>

          <div class="progress progress-xxs">
            <div class="progress-bar" style="width: <?php echo tantoxcien($rwCont['sesiones'], $rwCont['restantes']); ?>%"></div>
          </div>
              <span class="progress-description">
                <?php echo tantoxcien($rwCont['sesiones'], $rwCont['restantes']); ?>% de sesiones completas - Restantes: <?php echo $rwCont['restantes']; ?>
              </span>
        </div>
        <!-- /.info-box-content -->
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="col-md-3">
          <!-- Caja descripción -->
          <div class="box box-success">
            <div class="box-header box-success with-border">
              <h4 class="box-title">Descripción del servicio</h4>
              <div class="box-tools pull-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-gear"></i> Opciones <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/ops/referencia/continuado/agregar.php?numRh=<?php echo $rwCont['continuado']; ?>"><i class="fa fa-plus"></i>Agregar 1 servicio</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/ops/referencia/continuado/impCont.php?numRh=<?php echo $rwCont['continuado']; ?>" target="_blank"><i class="fa fa-print"></i>Imprimir expediente</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="/ops/nuevo/renovarCont.php?idCont=<?php echo $rwCont['continuado']; ?>"><i class="fa fa-sticky-note-o"></i>Renovar</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-condensed">
                <tr>
                  <td><strong>Sesiones</strong></td>
                  <td><div class="totalSesion" id="sesiones-<?php echo $rwCont['continuado']; ?>"><?php echo $rwCont['sesiones']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Id continuado</strong></td>
                  <td><?php echo $rwCont['continuado']; ?></td>
                </tr>
                <tr>
                  <td><strong>Quedan</strong></td>
                  <td><?php echo $rwCont['restantes']; ?> Sesiones</td>
                </tr>
                <tr>
                  <td><strong>Autorización</strong></td>
                  <td><div class="texto" id="autorizacion-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['autorizacion']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Tlf1</strong></td>
                  <td><div class="texto" id="tlf1-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['tlf1']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Tlf2</strong></td>
                  <td><div class="texto" id="tlf2-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['tlf2']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Delegación</strong></td>
                  <td><div class="editDeleg" id="delegacion-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['provincia']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Póliza</strong></td>
                  <td><div class="texto" id="poliza-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['poliza']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Provincia</strong></td>
                  <td><div class="provincia" id="provincia-<?php echo $rwCont['continuado']; ?>-comp"><?php provValor($rwCont['provPac']); ?></div></td>
                </tr>
                <tr>
                  <td><strong>Aseguradora</strong></td>
                  <td><?php echo $rwCont['ciaNom']; ?></td>
                </tr>
                <tr>
                  <td><strong>Tipo</strong></td>
                  <td><div class="edTipo" id="tipo-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['nomSer']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Recurso</strong></td>
                  <td><div class="recurso" id="recurso-<?php echo $rwCont['continuado']; ?>-comp"><?php echo $rwCont['nomRecu']; ?></div></td>
                </tr>
                <tr>
                  <td><strong>Pauta</strong></td>
                  <td><div class="pauta" id="pauta-<?php echo $rwCont['continuado'];?>"><?php pautaSesion($rwCont['pauta']); ?></div></td>
                </tr>
                <tr>
                  <td><strong>Hora de sesión</strong></td>
                  <td><div class="horas" id="hconsulta-<?php echo $rwCont['continuado'];?>-sInfo"><?php echo substr($rwCont['hconsulta'], 0, 5); ?></div></td>
                </tr>
                <tr>
                  <td><strong>Tiempo de sesión</strong></td>
                  <td><div class="horas" id="tconsulta-<?php echo $rwCont['continuado'];?>-sInfo"><?php echo substr($rwCont['tconsulta'], 0, 5); ?></div></td>
                </tr>
                <tr>
                  <td><strong>Acciones</strong></td>
                  <td><div class="accionTrat" id="estServ-<?php echo $rwCont['continuado'];?>"><?php mostrarEstados($rwCont['estCont']); ?></div></td>
                </tr>
                <tr>
                  <td><strong>Ruta</strong></td>
                  <td><div class="edRuta" id="codRuta-<?php echo $rwCont['continuado'];?>"><?php if($rwCont['codRuta'] == '0') { echo "SIN RUTA";} else { echo $rwCont['nomRuta']; } ?></div></td>
                </tr>
              </table>
            </div>
          </div>
          <!-- /. Caja descripcion -->
        </div>
        <!-- /. caja herramientas -->
        <!-- Tabla servicios -->
        <div class="col-md-9">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title">Datos del servicio: </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Recoger</th>
                  <th>Localidad</th>
                  <th>Trasladar</th>
                  <th>Localidad</th>
                </tr>
                <tr>
                  <td><div class="texto cajAument" id="recoger-<?php echo $rwCont['continuado']; ?>-restr"><?php echo $rwCont['recoger']; ?></div></td>
                  <td><div class="texto cajAument" id="locRec-<?php echo $rwCont['continuado']; ?>-restr"><?php echo $rwCont['locRec']; ?></div></td>
                  <td><div class="texto cajAument" id="trasladar-<?php echo $rwCont['continuado']; ?>-restr"><?php echo $rwCont['trasladar']; ?></div></td>
                  <td><div class="texto cajAument" id="locTras-<?php echo $rwCont['continuado']; ?>-restr"><?php echo $rwCont['locTras']; ?></div></td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title">Sesiones del servicio: </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Editar</th>
                  <th>Sesión</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Ida</th>
                  <th>Vuelta</th>
                  <th>Observaciones</th>
                  <th>Estado</th>
                </tr>
                <?php
                  $refCont  = $rwCont['continuado'];
                  $seleCont = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.fecha, servicio.hora, servicio.obs, servicio.estServ,
                      serpersonal.idSv, serpersonal.tecIda, serpersonal.tecVta, serestados.idSv, serestados.vhIda, serestados.vhVta, estados.idEst, estados.vaEst
                    FROM servicio
                      LEFT JOIN serpersonal ON serpersonal.idSv = servicio.idSv
                      LEFT JOIN serestados ON serestados.idSv = servicio.idSv
                      LEFT JOIN estados ON estados.idEst = servicio.estServ
                    WHERE continuado = '$refCont'
                    ORDER BY fecha ASC
                    ");
                  $contador = 1;
                  while($rwRefCont = mysqli_fetch_array($seleCont)) {
                    if($rwRefCont['estServ'] == 10 || $rwRefCont['estServ'] == 14 ) {
                      $colorTablaFin = "class=\"colorSesionFinal\"";
                    } else {
                      $colorTablaFin= "";
                    }
                ?>
                <tr <?php echo $colorTablaFin; ?>>
                  <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwRefCont['idSv']; ?>" target="_blank"><i class="fa fa-pencil"><i></a></td>
                  <td><?php echo $contador++; ?></td>
                  <td><div class="texto" id="fecha-<?php echo $rwRefCont['idSv'];?>-unico"><?php echo $rwRefCont['fecha']; ?></div></td>
                  <td><div class="horas" id="hora-<?php echo $rwRefCont['idSv'];?>-unico"><?php echo substr($rwRefCont['hora'], 0, 5); ?></div></td>
                  <td><?php echo $rwRefCont['tecIda']." / "; mostrarVehiculo($rwRefCont['vhIda']); ?></td>
                  <td><?php echo $rwRefCont['tecVta']." / "; mostrarVehiculo($rwRefCont['vhVta']); ?></td>
                  <td><div class="texto" id="obs-<?php echo $rwRefCont['idSv'];?>-unico"><?php echo $rwRefCont['obs']; ?></div></td>
                  <td><div class="edtEst" id="estServ-<?php echo $rwRefCont['idSv'];?>-unico"><?php echo $rwRefCont['vaEst']; ?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <?php } ?>
  <!-- /.Detalles de continuado -->
</div>
<!-- /.row -->
<!-- Jeditable -->
<script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
<!-- Remark: in production setting you would prefer to host jQuery and jQuery UI on Google -->
<script src="/ops/referencia/jeditable/jeditableCont.js"></script>
<script src="/ops/js/modalcont.js"></script>
