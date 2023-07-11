<!-- Inicidencias -->
<button type="button" class="btn btn-box-tool btn-info" data-target="#incidencia" data-toggle="modal" title="Agregar nota"><i class="fa fa-file-text-o"></i> </button>
<!-- Modal -->
<div id="incidencia" class="modal fade modal-info" role="dialog">
  <div class="modal-dialog">
    <!-- Contenido modal - incidencia-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nueva nota de paciente: </h4>
        <h5><?php if(@$regOk == 1) { echo $mensa; } ?></h5>
      </div>
      <form name="incModal" class="form-vertical" action="" onsubmit="guardarModalInci(); return false">
        <div class="modal-body">
          <div id="resultado"></div>
          <div id="datos_ajax_register"></div>
          <div class="form-group">
            <label for="descInci" class="control-label">Descripción:</label>
            <textarea class="form-control" rows="4" name="descNota"></textarea>
          </div>
          <input type="hidden" class="form-control" name="userNota" value="<?php echo $_SESSION['userId']; ?>">
          <input type="hidden" class="form-control" name="idPac" value="<?php echo $registro; ?>">
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
