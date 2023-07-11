<?php
session_start();
include '../../functions/function.php';

// No guarda el checkbox due

$sqlVuelo = mysqli_query($gestambu,
  "SELECT vuelosanitario.idVuelo, vuelosanitario.idCia, vuelosanitario.tipo, DATE_FORMAT(vuelosanitario.fecha, '%d-%m-%Y') AS fecha, vuelosanitario.hora, vuelosanitario.medico, vuelosanitario.due, vuelosanitario.idvta, vuelosanitario.incub, vuelosanitario.pediatra, vuelosanitario.nombre,
    vuelosanitario.recoger, vuelosanitario.locRec, vuelosanitario.trasladar, vuelosanitario.locTras, cia.idCia, cia.ciaNom, vueloref.idVuelo, vueloref.estVuelo, vueloref.medico AS medNom, vueloref.due AS dueNom, vueloref.pediatra AS pedNom,
    estados.idEst, estados.vaEst
  FROM vuelosanitario
    LEFT JOIN cia ON vuelosanitario.idCia = cia.idCia
    LEFT JOIN vueloref ON vuelosanitario.idVuelo = vueloref.idVuelo
    LEFT JOIN estados ON vueloref.estVuelo = estados.idEst
  WHERE vueloref.estVuelo NOT IN ('14', '15')
  ORDER BY vuelosanitario.fecha, vuelosanitario.hora ASC
");
$numSqlVuelo = mysqli_num_rows($sqlVuelo);
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
   <div class="box-body table-responsive no-padding">
     <table class="table table-hover h6" id="datosAmb">
       <thead>
         <th>#</th>
         <th>Fecha</th>
         <th>Hora</th>
         <th>Aseguradora</th>
         <th>Nombre</th>
         <th>Tipo</th>
         <th>Id/vta</th>
         <th>Médico</th>
         <th>Due</th>
         <th>Pediatra</th>
         <th>Incubadora</th>
         <th>Recoger</th>
         <th>Loc.</th>
         <th>Trasladar</th>
         <th>Loc.</th>
         <th>Médico</th>
         <th>Due</th>
         <th>Pediatra</th>
         <th>Estado</th>
       </thead>
       <?php
        if($numSqlVuelo == 0 ) {
        ?>
        <tr>
          <td colspan="18"><div>No se encontraron resultados</td>
        </tr>
        <?php
        } else {
          while($rwVueloTab = mysqli_fetch_array($sqlVuelo)) {
       ?>
       <tr>
         <td class="h5 sinmar">
             <a href="/ops/mostrar/editVuelo.php?iden=<?php echo $rwVueloTab['idVuelo']; ?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="fa fa-pencil-square-o"></i></a>
         </td>
         <td><?php echo $rwVueloTab['fecha']; ?></td>
         <td><?php echo date('H:i', strtotime($rwVueloTab['hora'])); ?></td>
         <td><?php echo $rwVueloTab['ciaNom']; ?></td>
         <td><?php echo $rwVueloTab['nombre']; ?></td>
         <td><?php valoresVuelos($rwVueloTab['tipo']); ?></td>
         <td><?php if($rwVueloTab['idvta'] == 1) { echo "<i class=\"fa fa-check\"></i>"; }; ?></td>
         <td><?php if($rwVueloTab['medico'] == 1) { echo "<i class=\"fa fa-check\"></i>"; }; ?></td>
         <td><?php if($rwVueloTab['due'] == 1) { echo "<i class=\"fa fa-check\"></i>"; }; ?></td>
         <td><?php if($rwVueloTab['pediatra'] == 1) { echo "<i class=\"fa fa-check\"></i>"; }; ?></td>
         <td><?php if($rwVueloTab['incub'] == 1) { echo "<i class=\"fa fa-check\"></i>"; }; ?></td>
         <td><?php echo $rwVueloTab['recoger']; ?></td>
         <td><?php echo $rwVueloTab['locRec']; ?></td>
         <td><?php echo $rwVueloTab['trasladar']; ?></td>
         <td><?php echo $rwVueloTab['locTras']; ?></td>
         <td><?php echo $rwVueloTab['medNom']; ?></td>
         <td><?php echo $rwVueloTab['dueNom']; ?></td>
         <td><?php echo $rwVueloTab['pedNom']; ?></td>
         <td><div class="estadoVuelo" id="estVuelo-<?php echo $rwVueloTab['idVuelo']; ?>"><?php echo $rwVueloTab['vaEst']; ?></div></td>
       </tr>
       <?php } } ?>
     </table>
   </div>
   <!-- /.box-body -->
 </div>
 <!-- Jeditable -->
 <script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
 <script src="/ops/referencia/jeditable/jeditableVuelo.js"></script>
