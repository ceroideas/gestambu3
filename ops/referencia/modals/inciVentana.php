<!-- Inicidencias -->
<button type="button" class="btn btn-box-tool btn-info" data-target="#incidencia" data-toggle="modal" title="Agregar incidencia"><i class="fa fa-exclamation"></i> </button>
<!-- Modal -->
<div id="incidencia" class="modal fade modal-warning" role="dialog">
  <div class="modal-dialog">
    <!-- Contenido modal - incidencia-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Indencia de servicio: </h4>
        <h5><?php if(@$regOk == 1) { echo $mensa; } ?></h5>
      </div>
      <form name="incModal" class="form-vertical" action="" onsubmit="guardarModalInci(); return false">
        <div class="modal-body">
          <div id="resultado"></div>
          <div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="incHora" class="control-label">Hora:</label>
            <input type="time" class="form-control" name="incHora">
          </div>
          <div class="form-group">
            <label for="descInci" class="control-label">Descripción:</label>
            <textarea class="form-control" rows="3" name="descInci"></textarea>
          </div>
          <div class="form-group">
            <label for="" class="control-label">Motivo:</label>
            <select class="form-control" name="motivoInci">
              <option value="0">-- Selecciona opción --</option>
              <option value="1">Anulado por demora</option>
              <option value="2">Rechaza asistencia</option>
              <option value="3">Ausente en domicilio</option>
              <option value="4">Otros motivos</option>
            </select>
          </div>
          <input type="hidden" class="form-control" name="userInci" value="<?php echo $_SESSION['userId']; ?>">
          <input type="hidden" class="form-control" name="idSv" value="<?php echo $registro; ?>">
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="guardarModal" class="btn btn-outline">Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- fin ventana modal -->
