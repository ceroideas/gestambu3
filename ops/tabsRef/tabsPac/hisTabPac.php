<?php
session_start();
include '../../../functions/function.php';
nonUser();
$usuario = $_SESSION['userId'];

//Recoge los datos de ID paciente
if(isset($_GET['pacID'])) {
  $identPac = $_GET['pacID'];
} else {
  $identPac = '1';
}

//Etiquetas de fecha
$etiFecha = mysqli_query($gestambu, "SELECT idPac, DATE_FORMAT(fecha, '%d-%b-%y') AS fechaFormat, fecha
  FROM servicio
  WHERE idPac = '$identPac'
  GROUP BY fecha
  ORDER BY fecha DESC
  ");
?>
<div class="box-header">
  <h3 class="box-title">Histórico</h3>
</div>
<!-- The timeline -->
<ul class="timeline timeline-inverse">
  <?php while($rwFecha = mysqli_fetch_array($etiFecha)) { ?>
  <!-- timeline time label -->
  <li class="time-label">
        <span class="bg-red">
          <?php echo $rwFecha['fechaFormat']; ?>
        </span>
  </li>
  <!-- /.timeline-label -->
  <?php
  //Resultados según la fecha seleccionada
  $selfecha = $rwFecha['fecha'];
  $datPac = mysqli_query($gestambu,"SELECT servicio.idSv, servicio.fecha, servicio.hora, servicio.tipo, servicio.recurso, servicio.provincia, servicio.autorizacion, servicio.recoger, servicio.locRec, servicio.obs, servicio.estServ, servicio.enfermero, servicio.medico,
      servicio.trasladar, servicio.locTras, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono, servi.bgColor, recurso.idRecu, recurso.recuCorto
    FROM servicio
      LEFT JOIN estados ON servicio.estServ = estados.idEst
      LEFT JOIN servi ON servicio.tipo = servi.idServi
      LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
    WHERE servicio.idPac = '$identPac' AND servicio.fecha = '$selfecha'
    ORDER BY servicio.fecha, servicio.hora DESC
    ");
  while($rwDatPac = mysqli_fetch_array($datPac)) {
  ?>
  <!-- timeline item -->
  <li>
    <i class="fa fa-<?php echo $rwDatPac['icono']; ?> bg-<?php echo $rwDatPac['bgColor']; ?>"></i>

    <div class="timeline-item">
      <span class="time"><i class="fa fa-clock-o"></i> <?php echo $rwDatPac['hora']; ?></span>

      <h3 class="timeline-header">
        <?php
          echo $rwDatPac['nomSer']." - ";
          ambComple($rwDatPac['recurso'], $rwDatPac['enfermero'], $rwDatPac['medico'], $rwDatPac['nomSer']);
        ?>
      </h3>

      <div class="timeline-body">
        <strong>Servicio:</strong><br />
        <?php
          echo "<strong>Recoger: </strong>".$rwDatPac['recoger']." - ".$rwDatPac['locRec']."<br />";
          if($rwDatPac['recurso'] == '1' || $rwDatPac['recurso'] == '3' || $rwDatPac['recurso'] == '5' ) {
            echo "<strong>Trasladar: </strong>".$rwDatPac['trasladar']." - ".$rwDatPac['locTras']."<br />";
          }
          echo "<strong>Observaciones: </strong>".$rwDatPac['obs'];
        ?>
      </div>
      <div class="timeline-footer">
        <a class="btn btn-primary btn-xs" href="/ops/mostrar/editServ.php?iden=<?php echo $rwDatPac['idSv']; ?>" target="_blank">Ver servicio</a>
      </div>
    </div>
  </li>
  <!-- END timeline item -->
  <?php   } ?>
  <?php } ?>
  <li>
    <i class="fa fa-clock-o bg-gray"></i>
  </li>
</ul>
<!-- /.box-body -->
