<?php
  include './tabService.php';
?>
<div class="tab-pane active" id="tab_1">
  <div class="box-tools">
    <div class="input-group input-group-sm" style="width: 150px;">
      <input type="text" name="table_search" class="form-control pull-right" placeholder="Busqueda" id="ambTerm" onkeyup="doSearch()">
      <div class="input-group-btn">
        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
      </div>
    </div>
  </div>
  <div class="box-body table-condensed no-padding">
    <table class="table table-hover h6" id="datosAmb">
      <thead class="table-bordered">
        <th>#</th>
        <th></th>
        <th></th>
        <th>Hora</th>
        <th>Provincia</th>
        <th>Nombre</th>
        <th>Cia.</th>
        <th>Tipo</th>
        <th>Recurso</th>
        <th>Recoger</th>
        <th>Loc.</th>
        <th>Trasladar</th>
        <th>Loc.</th>
        <th>V.Ida</th>
        <th>V.Vta</th>
        <th>Re. ida</th>
        <th>Fin ida</th>
        <th>Re. vta</th>
        <th>Fin vta</th>
        <th>Estado</th>
      </thead>
      <?php
      if ($numSqlAmbu == 0) {
      ?>
        <tr>
          <td colspan="18">
            <div>No se encontraron resultados
          </td>
        </tr>
        <?php
      } else {
        $icont = 0;
        while ($rwAmbTab = mysqli_fetch_array($sqlAmbu)) {
          $sinContenido = noCampo($rwAmbTab['tipo']);
        ?>
          <tr class="<?php
                      if (nuevoSer($rwAmbTab['creadoNu'], $rwAmbTab['estServ']) == 1) {
                        echo "newSer";
                        $colBlanco = 1;
                      } else {
                        if (@verUltima($rwAmbTab['continuado']) == 1) {
                          echo "bg-ultima";
                          $colBlanco = 1;
                        } else {
                          if ($rwAmbTab['prioridad'] == 1) {
                            echo "bg-urgente";
                            $colBlanco = 1;
                          } elseif ($rwAmbTab['prioridad'] == 2) {
                            echo "bg-preferente";
                            $colBlanco = 1;
                          } else {
                            if (@$icont++ % 2) {
                              echo "colorZebra";
                              $colBlanco = 0;
                            } else {
                              $colBlanco = 0;
                            }
                          }
                        }
                      }
                      ?>">
            <!-- Botonera para contenido expandido del servicio -->
            <td class="h5 sinmar"><a class="<?php if ($colBlanco == 1) {
                                              echo "linkBlank";
                                            } ?>" href="/ops/mostrar/editServ.php?iden=<?php echo $rwAmbTab['idSv']; ?>" data-toggle="tooltip" title="Editar"><i class="fa fa-pencil-square-o"></i></a></td>
            <!-- Abrir modal para ver servicio completo -->
            <td class="h5 sinmar"><a class="<?php if ($colBlanco == 1) {
                                              echo "linkBlank";
                                            } ?>" href="#" data-target="#modal-<?php echo $rwAmbTab['idSv']; ?>" data-toggle="modal" title="Ver servicio">
                <i class="fa fa-<?php echo icoEsTec($rwAmbTab['estTec'], $rwAmbTab['estTecVta'], $rwAmbTab['estServ'], $rwAmbTab['idvta']); ?>" onclick="limpiar('copyAnswer-<?php echo $rwAmbTab['idSv']; ?>')"></i>
              </a>
            </td>
            <td class="h5 sinmar"><?php echo compImpreso($rwAmbTab['idSv']); ?></td>
            <!-- Modal -->
            <div id="modal-<?php echo $rwAmbTab['idSv']; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Contenido modal - ver servicio-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Servicio para: <?php echo $rwAmbTab['idSv'] . " - " . $rwAmbTab['nombre'] . " " . $rwAmbTab['apellidos']; ?> <span id="copyAnswer-<?php echo $rwAmbTab['idSv']; ?>" class="label label-success"></span></h4>
                  </div>
                  <div class="modal-body">
                    <div id="textoCopiar-<?php echo $rwAmbTab['idSv']; ?>">
                      <?php
                      echo date('H:i', strtotime($rwAmbTab['hora'])) . "-" . $rwAmbTab['ciaNom'] . "-"
                        . $rwAmbTab['nomSer'] . "-" . $rwAmbTab['recoger'] . "-" . $rwAmbTab['locRec'] . "-" . $rwAmbTab['trasladar'] . "-"
                        . $rwAmbTab['locTras'] . "-" . $rwAmbTab['obs'];
                      ?>
                    </div>
                    <?php ambComple($rwAmbTab['recurso'], $rwAmbTab['enfermero'], $rwAmbTab['medico'], $rwAmbTab['nomRecu']); ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="copiarAlPortapapeles('textoCopiar-<?php echo $rwAmbTab['idSv']; ?>', 'copyAnswer-<?php echo $rwAmbTab['idSv']; ?>')">Copiar texto</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>

              </div>
            </div><!-- fin ventana modal -->
            <td data-toggle="tooltip" title="Demora: <?php mostrarEstados($rwAmbTab['demora']); ?>"><?php echo date('H:i', strtotime($rwAmbTab['hora'])); ?></td>
            <td><?php provValor($rwAmbTab['provincia']); ?></td>
            <td><?php echo $rwAmbTab['nombre']; ?></td>
            <td><?php echo $rwAmbTab['ciaNom']; ?></td>
            <td><?php if (@verUltima($rwAmbTab['continuado']) == 1) {
                  echo "U L T I M A";
                } else {
                  echo $rwAmbTab['nomSer'];
                } ?></td>
            <td><?php echo ambComple($rwAmbTab['recurso'], $rwAmbTab['enfermero'], $rwAmbTab['medico'], $rwAmbTab['nomRecu']); ?></td>
            <td data-toggle="tooltip" title="<?php mostrarEstados($rwAmbTab['estTec']); ?>"><?php echo $rwAmbTab['recoger']; ?></td>
            <td><?php echo $rwAmbTab['locRec']; ?></td>
            <td><?php echo $rwAmbTab['trasladar']; ?></td>
            <td><?php echo $rwAmbTab['locTras']; ?></td>
            <?php if ($rwAmbTab['idvta'] == 3) { ?>
              <td><i class="fa fa-close"></i></td>
            <?php } else { ?>
              <td>
                <div class="vhlist" id="vhIda-<?php echo $rwAmbTab['idSv']; ?>"><strong><?php mostrarVehiculo($rwAmbTab['vhIda']); ?></strong></div>
              </td>
            <?php }
            if ($rwAmbTab['idvta'] == 2) { ?>
              <td><i class="fa fa-close"></i></td>
            <?php } elseif ($sinContenido == 1) { ?>
              <td></td>
            <?php } else { ?>
              <td>
                <div class="vhlist" id="vhVta-<?php echo $rwAmbTab['idSv']; ?>"><strong><?php mostrarVehiculo($rwAmbTab['vhVta']); ?></strong></div>
              </td>
            <?php }
            if ($rwAmbTab['idvta'] == 3) { ?>
              <td colspan="2"><i class="anulado"> - S I N &nbsp; I D A - </i></td>
            <?php } else { ?>
              <td>
                <div class="hora" id="idReco-<?php echo $rwAmbTab['idSv']; ?>"><?php echo $rwAmbTab['idReco']; ?></div>
              </td>
              <td>
                <div class="hora" id="idFin-<?php echo $rwAmbTab['idSv']; ?>"><?php echo $rwAmbTab['idFin']; ?></div>
              </td>
            <?php }
            if ($rwAmbTab['idvta'] == 2) { ?>
              <td colspan="2"><i class="anulado"> - S I N &nbsp; V U E L T A - </i></td>
            <?php } elseif ($sinContenido == 1) { ?>
              <td colspan="2"></td>
            <?php } else { ?>
              <td>
                <div class="hora" id="vtaReco-<?php echo $rwAmbTab['idSv']; ?>"><?php echo $rwAmbTab['vtaReco']; ?></div>
              </td>
              <td>
                <div class="hora" id="vtaFin-<?php echo $rwAmbTab['idSv']; ?>"><?php echo $rwAmbTab['vtaFin']; ?></div>
              </td>
            <?php } ?>
            <td>
              <div class="<?php estJeditable($rwAmbTab['idvta']); ?>" id="estServ-<?php echo $rwAmbTab['idSv']; ?>"><?php echo $rwAmbTab['vaEst']; ?></div>
            </td>
          </tr>
      <?php }
      } ?>
    </table>
  </div>
  <!-- /.box-body -->
</div>
<script language="javascript">
  function doSearch() {

    var tableReg = document.getElementById('datosAmb');
    var searchText = document.getElementById('ambTerm').value.toLowerCase();
    var cellsOfRow = "";
    var found = false;
    var compareWith = "";
    // Recorremos todas las filas con contenido de la tabla
    for (var i = 1; i < tableReg.rows.length; i++) {
      cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
      found = false;
      // Recorremos todas las celdas
      for (var j = 0; j < cellsOfRow.length && !found; j++) {

        compareWith = cellsOfRow[j].innerHTML.toLowerCase();
        // Buscamos el texto en el contenido de la celda
        if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)) {
          found = true;
        }
      }
      if (found) {
        tableReg.rows[i].style.display = '';
      } else {
        // si no ha encontrado ninguna coincidencia, esconde la
        // fila de la tabla
        tableReg.rows[i].style.display = 'none';
      }
    }
  }

  function copiarAlPortapapeles(id_elemento, copyAnswer) {
    var aux = document.createElement("input");

    aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);

    var answer = document.getElementById(copyAnswer);
    var successful = document.execCommand('copy');

    if (successful) answer.innerHTML = '¡ Copiado !';
  }

  function limpiar(elementSeleccionado) {
    document.getElementById(elementSeleccionado).innerHTML = "";
  }
</script>
<!-- Jeditable -->
<script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
<script src="/ops/referencia/jeditable/jeditableIndex.js"></script>

