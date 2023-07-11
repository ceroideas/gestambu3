/* Edita los campos de libreta */
$(document).ready(function() {
	 //Listado para vehículos
	 $('.vhlist').editable('/ops/referencia/jeditable/savelistadoVh.php', {
		 loadurl:'/ops/referencia/jeditable/listadoVh.php',
		 type   : 'select',
		 submit : 'OK'
	 });
 });
