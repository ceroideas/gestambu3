function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
//Boton Recibido
function enviarRecibido(valor, estado){
  cambiar = valor;
  estadoTab = estado;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonRecibido.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			alert("Servicio marcado como: Recibido");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&estadoTab="+estadoTab);
}
//Boton En camino
function enviarEnCamino(valor, estado, demanda, cia, recurso, idvta){
  cambiar   = valor;
  estadoTab = estado;
  demanda   = demanda;
  cia       = cia;
  recurso   = recurso;
  idvta     = idvta;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonEnCamino.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			alert("Servicio marcado como: En camino");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&estadoTab="+estadoTab+"&demanda="+demanda+"&cia="+cia+"&recurso="+recurso+"&idvta="+idvta);
}
//Boton En destino
function enviarDestino(valor, identi, estado, idvta, demanda, cia, recurso){
  cambiar   = valor;
  idSv      = identi;
  estadoTab = estado;
  idvTab    = idvta;
  demanda   = demanda;
  cia       = cia;
  recurso   = recurso;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonDestino.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			//alert("Marcado como: En destino");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&iden="+idSv+"&estadoTab="+estadoTab+"&idvTab="+idvTab+"&demanda="+demanda+"&cia="+cia+"&recurso="+recurso);
}
//Botón en movimiento
function enviarEnMovimiento(valor, estado){
  cambiar = valor;
  estadoTab = estado;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonEnmovimiento.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			alert("Servicio marcado como: En movimiento");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&estadoTab="+estadoTab);
}
//Boton finalizado
function enviarFin(valor, identi,  estado, idvta, demanda, cia, recurso){
  cambiar   = valor;
  idSv      = identi;
  estadoTab = estado;
  idvTab    = idvta;
  demanda   = demanda;
  cia       = cia;
  recurso   = recurso;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonFin.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			//alert("Marcado como: finalizado - a la espera de finalizar servicio por el operador.");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&iden="+idSv+"&estadoTab="+estadoTab+"&idvTab="+idvTab+"&demanda="+demanda+"&cia="+cia+"&recurso="+recurso);
}

//Boton finalizado visitas médicas
function enviarFinVM(valor, identi,  estado, idvta, demanda, cia, recurso){
  cambiar   = valor;
  idSv      = identi;
  estadoTab = estado;
  idvTab    = idvta;
  demanda   = demanda;
  cia       = cia;
  recurso   = recurso;
	diagnostico=document.diaG.diagnostico.value;
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/guardaBotonFin.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			//alert("Marcado como: finalizado - a la espera de finalizar servicio por el operador.");
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("valorRecibido="+cambiar+"&iden="+idSv+"&estadoTab="+estadoTab+"&idvTab="+idvTab+"&demanda="+demanda+"&cia="+cia+"&recurso="+recurso+"&diagnostico="+diagnostico);
}

function guardarDiagnostico(valorId) {
	//recogemos los valores de los inputs
	selector=valorId;
	diagnostico=document.diaG.diagnostico.value;
	divResultado = document.getElementById('resultado');
	//instanciamos el objetoAjax
	ajax=objetoAjax();

	//uso del medotod POST
	ajax.open("POST", "/tec/referencia/instDiag.php",true);
	//cuando el objeto XMLHttpRequest cambia de estado, la función se inicia
	ajax.onreadystatechange=function() {
		//la función responseText tiene todos los datos pedidos al servidor
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			divResultado.innerHTML = ajax.responseText;
			//llamar a funcion para limpiar los inputs
		LimpiarCampos();
	}
};
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores a registro.php para que inserte los datos
	ajax.send("selector="+selector+"&diagnostico="+diagnostico);
}

function enviarOtros(idServicio, usuario) {
	idSv   = idServicio;
	inci   = document.insInci.descInci.value;
	user   = usuario;
	divResultado = document.getElementById('resultadoInci');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/insIncidencia.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			//alert("Incidencia guardada con éxito. "+inci);
			divResultado.innerHTML = ajax.responseText;
			LimpiarCamposInc();
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idSv="+idSv+"&inci="+inci+"&user="+user);
}

function enviarIncidencia(idServicio, incidencia, usuario) {
  idSv   = idServicio;
  inci   = incidencia;
	user   = usuario;
	divResultadoPre = document.getElementById('resultado');
	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion

	ajax.open("POST", "/tec/js/insIncidencia.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar un mensaje de actualizacion correcta
			//alert("Incidencia guardada con éxito. "+inci);
			divResultadoPre.innerHTML = ajax.responseText;
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idSv="+idSv+"&inci="+inci+"&user="+user);
}

function LimpiarCampos(){
	document.diaG.diagnostico.value="";
	//document.incModal.userInci.value="";
	//document.incModal.idSv.value="";
}
function LimpiarCamposInc(){
	document.insInci.descInci.value="";
}
