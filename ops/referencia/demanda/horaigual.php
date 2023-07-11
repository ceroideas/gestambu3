<?php
while($rwSesion = mysqli_fetch_array($colSesion)) {
  echo "
  <div class=\"form-group col-md-2 col-sm-2 col-xs-2\">
    <label>".$i++." Sesion: </label>
    <input type=\"date\" class=\"form-control\" name=\"fecha".$fch++."\" value=\"".muestraFechAsisa($rwSesion['fecha_asistencia'])."\" >
  </div>";
}
?>
