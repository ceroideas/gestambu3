/* Edita los campos de index */
$(document).ready(function() {

	 //Edita el campo de horas
	 $('.texto').editable('funciones/textogeneral.php', {
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
	 $('.hora').editable('funciones/horasgeneral.php', {
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
	 $('.factura').editable('funciones/factura.php', {
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });	 
 });