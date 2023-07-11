/* Edita los campos de index */
$(document).ready(function() {

	 // Edita el cuadro de estado - cuando existe ida y vuelta
   $('.estadoVuelo').editable('/ops/referencia/jeditable/saveVueloEstados.php', {
		 loadurl:'/ops/referencia/jeditable/guardarEstadoSV.php',
		 type   : 'select',
		 submit : 'OK'
	 });
 });
