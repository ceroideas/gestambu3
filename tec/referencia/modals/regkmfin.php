<!-- Registro de km -->
<!-- Modal Registro km llegada-->
<div id="regkmfin" class="modal fade modal-info" role="dialog">
  <div class="modal-dialog">
    <!-- Contenido modal - registro de km-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registro de km: </h4>
        <?php if($rwLiServ['recurso'] == 2) { echo "Servicio finalizado. Pulsa cerrar para continuar."; } else { if(@$regOkFin == 1) { echo @$mensaFin; } } ?>
      </div>
      <form name="incModalFin" class="form-vertical" method="post" action="" onsubmit="insModKmFin(<?php if($rwLiServ['estServ'] == 5 || $rwLiServ['idvta'] == 3) { echo "3"; } else { echo "2"; } ?>); return false">
      <?php if($rwLiServ['recurso'] == 2) {  } else {?>
        <div class="modal-body">
          <div id="resultadoFin"></div>
          <div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="incKm" class="control-label">Km:</label>
            <input type="number" class="form-control" name="incKm">
          </div>
          <input type="hidden" name="user" value="<?php echo $_SESSION['userId']; ?>">
          <input type="hidden" name="idSv" value="<?php echo $idSv; ?>">
          <input type="hidden" name="idVh" value="<?php echo $ambUser; ?>">
          <input type="hidden" name="idvtaKm" value="<?php echo $rwLiServ['idvta']; ?>">
          <input type="hidden" name="estKm" value="<?php echo $rwLiServ['estServ']; ?>">
        </div>
        <div class="clearfix"></div>
      <?php } ?>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="guardarModalKm" class="btn btn-outline">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- fin ventana modal -->
