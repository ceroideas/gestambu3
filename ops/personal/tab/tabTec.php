<?php
session_start();
include '../../../functions/function.php';

/* Categorías */
# 1 - Administrador
# 2 - Coordinación
# 3 - Operaciones
# 4 - Admin-Vehículos
# 5 - Técnico
# 6 - Enfermero
# 7 - Médico
# 8 - Ayudante
# 9 - Prácticas

if(isset($_GET['tabName'])) {
  $cate = $_GET['tabName'];
  if($cate == 'tec') {
    $selCate = "'5', '8', '9'";
  } elseif($cate == 'med') {
    $selCate = "'7'";
  } elseif($cate == 'due') {
    $selCate = "'6'";
  } elseif($cate == 'ope') {
    $selCate = "'3'";
  }
}

if(isset($_GET['opcion'])) {
  $opcion = $_GET['opcion'];
} else {
  $opcion = '2';
}

if($opcion == 1) {
  $sqlTec = mysqli_query($gestambu, "SELECT * FROM user WHERE usCate IN($selCate) AND usEst = '1' ORDER BY usProv ASC");
} elseif($opcion == 2) {
  $sqlTec = mysqli_query($gestambu, "SELECT * FROM user WHERE usCate IN($selCate) AND usEst = '0' ORDER BY usProv ASC");
}


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
    		 <th>Nombre</th>
         <th>Cate.</th>
         <th>DNI</th>
         <th>Tlf.</th>
         <th>Tlf2.</th>
         <th>email</th>
         <th>Dirección</th>
         <th>Provincia</th>
       </thead>
        <?php
			    $icont = 0;
          while($rwTecTab = mysqli_fetch_array($sqlTec)) {
       ?>
       <tr class="<?php
             if(@$icont++ % 2) {
               echo "colorZebra";
               $colBlanco = 0;
             } else {
               $colBlanco = 0;
             }
          ?>">
		      <td><?php echo $icont; ?><a href="perfil.php?user=<?php echo $rwTecTab['userId'];?>"> <i class="fa fa-user"></i> </a></td>
          <td><?php echo $rwTecTab['usNom']." ".$rwTecTab['usApe']; ?></td>
          <td><?php mostValorCate($rwTecTab['usCate']);?></td>
          <td><?php echo $rwTecTab['usDNI'];?></td>
          <td><?php echo $rwTecTab['usTlf'];?></td>
          <td><?php echo $rwTecTab['usTlf2'];?></td>
          <td><?php echo $rwTecTab['usEmail'];?></td>
          <td><?php echo $rwTecTab['usDirec']." - ".$rwTecTab['usLoc'];?></td>
          <td><?php provValor($rwTecTab['usProv']);?></td>
       </tr>
       <?php } ?>
     </table>
   </div>
   <!-- /.box-body -->
 </div>
 <script language="javascript">
   function doSearch() {

     var tableReg = document.getElementById('datosAmb');
     var searchText = document.getElementById('ambTerm').value.toLowerCase();
     var cellsOfRow="";
     var found=false;
     var compareWith="";
     // Recorremos todas las filas con contenido de la tabla
     for (var i = 1; i < tableReg.rows.length; i++) {
       cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
       found = false;
       // Recorremos todas las celdas
       for (var j = 0; j < cellsOfRow.length && !found; j++){

         compareWith = cellsOfRow[j].innerHTML.toLowerCase();
         // Buscamos el texto en el contenido de la celda
         if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)){
           found = true;
         }
       }
       if(found)
       {
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

     if(successful) answer.innerHTML = '¡ Copiado !';
   }
   function limpiar(elementSeleccionado) {
      document.getElementById(elementSeleccionado).innerHTML="";
   }
 </script>
 <!-- Jeditable -->
 <script src="/docs/plugins/jeditable/jquery.jeditable.js"></script>
 <script src="/ops/referencia/jeditable/jeditableIndex.js"></script>
