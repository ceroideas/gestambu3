<?php
include("config.php");
$c = new Buscador;
$c->Conectar();
$q = $_GET['q'];
$s = $_GET['s'];


if ($q == null) {
	print '<span class="help-block h6"> <i class="fa fa-exclamation-triangle"></i> Ingresa algun dato para buscar</span>';
}else {
$c ->Buscar($q);
}

?>
