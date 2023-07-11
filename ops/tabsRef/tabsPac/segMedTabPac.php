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

//Muestra los resultados que contengan la identifiación del paciente

$datPac = mysqli_query($gestambu,"SELECT servicio.idSv, DATE_FORMAT(servicio.fecha, '%d-%m-%y') AS fechaFor, servicio.fecha, servicio.hora, servicio.tipo, servicio.recurso, servicio.provincia, servicio.autorizacion, servicio.recoger, servicio.locRec, servicio.obs, servicio.estServ,
    estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto
  FROM servicio
    LEFT JOIN estados ON servicio.estServ = estados.idEst
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
  WHERE servicio.idPac = '$identPac' AND servicio.recurso = '4' AND servicio.tipo = '9'
  ORDER BY servicio.fecha DESC
  ");

?>
<div class="box-header">
  <h3 class="box-title">Visitas Médicas</h3>
</div>
<!-- /.box-header -->
<div class="box-body table-responsive no-padding">
  <table class="table table-hover">
    <tr>
      <th>Acción</th>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Tipo</th>
      <th>Provincia</th>
      <th>Auto.</th>
      <th>Recoger</th>
      <th>Loc.Rec.</th>
      <th>Obs.</th>
      <th>Estado</th>
    </tr>
    <?php while($rwDatPac = mysqli_fetch_array($datPac)) { ?>
    <tr>
      <td><a href="/ops/mostrar/editServ.php?iden=<?php echo $rwDatPac['idSv']; ?>" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil-square-o"></i></a></td>
      <td><?php echo $rwDatPac['fechaFor']; ?></td>
      <td><?php echo $rwDatPac['hora']; ?></td>
      <td><i class="fa fa-<?php echo $rwDatPac['icono']; ?>"></i> <?php echo $rwDatPac['nomSer']; ?></td>
      <td><?php provValor($rwDatPac['provincia']); ?></td>
      <td><?php echo $rwDatPac['autorizacion']; ?></td>
      <td><?php echo $rwDatPac['recoger']; ?></td>
      <td><?php echo $rwDatPac['locRec']; ?></td>
      <td><?php echo $rwDatPac['obs']; ?></td>
      <td><?php echo $rwDatPac['vaEst']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
<!-- /.box-body -->
