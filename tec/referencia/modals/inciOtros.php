<!-- Registro de km -->
<!-- Modal Registro km llegada-->
<div id="inciOtros" class="modal fade modal-info" role="dialog">
  <div class="modal-dialog">
    <!-- Contenido modal - incidencia otros motivos-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Descripción de incidencia </h4>
        <?php if($rwLiServ['recurso'] == 2) { echo "Servicio finalizado. Pulsa cerrar para continuar."; } else { if(@$regOkFin == 1) { echo @$mensaFin; } } ?>
      </div>
      <form name="insInci" id="insInci" class="form-vertical" method="post" action="" onsubmit="enviarOtros(<?php echo $rwLiServ['idSv']; ?>, <?php echo $usuario; ?>); return false">

        <div class="modal-body">
          <div id="resultadoInci"></div>
          <div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="descInci" class="control-label">Incidencia:</label>
            <textarea class="form-control" rows="3" placeholder="Observaciones" name="descInci"></textarea>
          </div>
        </div>
        <div class="clearfix"></div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="guardarModalInci" class="btn btn-outline">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- fin ventana modal -->
