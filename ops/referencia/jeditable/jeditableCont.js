/* Edita los campos de index */
$(document).ready(function() {

	 // Edita texto en general
   $('.texto').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 cancel: '<button class="btn btn-sm" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-sm btn-primary" type="submit" >Guardar</button>'
 		});
	 // Edita el campo de horas
   $('.horas').editable('/ops/referencia/jeditable/saveHoraCont.php', {
		 cancel: '<button class="btn btn-sm" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-sm btn-primary" type="submit" >Guardar</button>'
 		});
	 // Edita el campo de estados
	 $('.edtEst').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 loadurl:'/ops/referencia/jeditable/listEstCont.php',
		 type   : 'select',
		 submit : 'OK'
	 });
	 $('.edtEstDue').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 loadurl:'/ops/referencia/jeditable/listEstContDue.php',
		 type   : 'select',
		 submit : 'OK'
	 });
	 // Edita campo de provincia
	 $('.provincia').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 data:'{"11":"Cádiz", "29":"Málaga", "41":"Sevilla"}',
		 type   : 'select',
		 submit : 'OK'
	 });
	 // Edita campo de provincia
	 $('.pauta').editable('/ops/referencia/jeditable/savelistadoPauta.php', {
		 data:'{"0":"Pauta desconocida", "1":"Cada 24h", "2":"Cada 48h", "3":"Cada 72h", "4":"De lunes a viernes", "5":"Lunes - Miércoles - Viernes", "6":"Martes - Jueves - Sábados", "7":"Sábados y domingos"}',
		 type   : 'select',
		 submit : 'OK'
	 });
	 // Acciones para el continuado
	 $('.accionTrat').editable('/ops/referencia/jeditable/saveAccionTrat.php', {
		 data:'{"0":"- Acción -", "1":"Suspender tratamiento", "2":"Reanudar tratamiento" , "3":"Finalizar tratamiento (alta)", "4":"Anular tratamiento"}',
		 type   : 'select',
		 submit : 'OK'
	 });
	 // Edita el campo de tipo
	 $('.edTipo').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 loadurl:'/ops/referencia/jeditable/listTipo.php',
		 type   : 'select',
		 submit : 'OK'
	 });
   // Edita el campo de recurso
	 $('.recurso').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 loadurl:'/ops/referencia/jeditable/listRecu.php',
		 type   : 'select',
		 submit : 'OK'
	 });
   // Edita el campo de ruta
	 $('.edRuta').editable('/ops/referencia/jeditable/saveRuta.php', {
		 loadurl:'/ops/referencia/jeditable/listRutas.php',
		 type   : 'select',
		 submit : 'OK'
	 });
	 // Edita el campo de delegación
	 $('.editDeleg').editable('/ops/referencia/jeditable/saveTextoCont.php', {
		 loadurl:'/ops/referencia/jeditable/listDelegacion.php',
		 type   : 'select',
		 submit : 'OK'
	 });
	 // Edita el campo de numero sesiones - tabla refcont.
	 $('.totalSesion').editable('/ops/referencia/jeditable/saveSesionesCont.php', {
		 cancel: '<button class="btn btn-sm" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-sm btn-primary" type="submit" >Guardar</button>'
	 });	 
 });
