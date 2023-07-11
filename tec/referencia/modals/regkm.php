<!-- Registro de km -->
<!-- Modal Registro km llegada-->
<div id="regkm" class="modal fade modal-info" role="dialog">
  <div class="modal-dialog">
    <!-- Contenido modal - registro de km-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Registro de km: </h4>
        <?php if(@$regOk == 1) { echo $mensa; } ?>
      </div>
      <form name="incModal" id="incModal" class="form-vertical" method="post" action="" onsubmit="insModKm(<?php if($rwLiServ['estServ'] == 5 || $rwLiServ['idvta'] == 3) { echo "3"; } else { echo "2"; } ?>); return false">
        <div class="modal-body">
          <div id="resultado"></div>
          <div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="incKm" class="control-label">Km:</label>
            <input type="number" class="form-control" name="incKm">
          </div>
          <input type="hidden" name="user" value="<?php echo $_SESSION['userId']; ?>">
          <input type="hidden" name="idSv" value="<?php echo $idSv; ?>">
          <input type="hidden" name="idVh" value="<?php echo $ambUser; ?>">
          <input type="hidden" name="idvtaKm" value="<?php if(empty($rwLiServ['idvta'])) { echo "0"; } else { echo $rwLiServ['idvta']; } ?>">
          <input type="hidden" name="estKm" value="<?php echo $rwLiServ['estServ']; ?>">
          <input type="hidden" name="recuKm" value="<?php echo $rwLiServ['recurso']; ?>">
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="guardarModalKm" class="btn btn-outline">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- fin ventana modal -->
