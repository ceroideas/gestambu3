/* Edita los campos de libreta */
$(document).ready(function() {
	 //Listado para vehículos
	 $('.poli').editable('/ops/referencia/jeditable/savePoli.php', {
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
 });
