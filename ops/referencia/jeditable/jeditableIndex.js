/* Edita los campos de index */
$(document).ready(function() {

	 // Edita el cuadro de estado - cuando existe ida y vuelta
   $('.tieneVuelta').editable('/ops/referencia/jeditable/saveIndexEstados.php', {
		 loadurl:'/ops/referencia/jeditable/guardarEstadoCV.php',
		 type   : 'select',
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
   // Edita el cuadro de estado - cuando no hay ida y vuelta
	 $('.sinVuelta').editable('/ops/referencia/jeditable/saveIndexEstados.php', {
		 loadurl:'/ops/referencia/jeditable/guardarEstadoSV.php',
		 type   : 'select',
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
	 //Edita el campo de horas
	 $('.hora').editable('/ops/referencia/jeditable/saveIndexHoras.php', {
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'		 
	 });
	 //Listado para vehículos
	 $('.vhlist').editable('/ops/referencia/jeditable/savelistadoVh.php', {
		 loadurl:'/ops/referencia/jeditable/listadoVh.php',
		 type   : 'select',
		 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
		 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
	 });
	 //Edita campo técnicos
	 $('.editTec').editable('/ops/referencia/jeditable/savePersonal.php', {
		 loadurl:'/ops/referencia/jeditable/listadoTecnicos.php',
		 type   : 'select',
		 submit : 'OK'
	 });
	 //Edita campo due
	 $('.editDue').editable('/ops/referencia/jeditable/savePersonal.php', {
		 loadurl:'/ops/referencia/jeditable/listadoDue.php',
		 type   : 'select',
		 submit : 'OK'
	 });
	 //Edita campo médico
	 $('.editMed').editable('/ops/referencia/jeditable/savePersonal.php', {
		 loadurl:'/ops/referencia/jeditable/listadoMed.php',
		 type   : 'select',
		 submit : 'OK'
	 });
 });
