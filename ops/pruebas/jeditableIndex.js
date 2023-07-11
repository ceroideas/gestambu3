/* Edita los campos de index */
$(document).ready(function() {

 $('.editar').editable('saveIndexEstados.php', {
		 data:'{"0":"- Acción -", "1":"Suspender tratamiento", "2":"Reanudar tratamiento" , "3":"Finalizar tratamiento (alta)", "4":"Anular tratamiento"}',
		 type   : 'select',
		 submit : 'OK'
	 });	 
 });
