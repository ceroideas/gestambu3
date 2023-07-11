/* Edita los campos de index */
$(document).ready(function() {

	 //Edita el campo de horas
	 $('.texto').editable('funciones/textocontinuado.php', {
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
 });