<?php 
$fechaCambiar = "2018-04-30 10:23:25";

function obtenerHoraActivacion($fechaCambiar) {
	$obtHora = explode(" ", $fechaCambiar);
	$horaCompleta = $obtHora[1];
	$forHora = explode(":", $horaCompleta);
	$horaActivacion = $forHora[0].$forHora[1];
	
	return $horaActivacion;
}

echo obtenerHoraActivacion($fechaCambiar);


?>