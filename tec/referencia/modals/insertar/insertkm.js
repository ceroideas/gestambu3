// JavaScript Document
// Función para recoger los datos de PHP según el navegador, se usa siempre.
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

//Función para recoger los datos del formulario y enviarlos por post
function insModKm(valor){

  //div donde se mostrará lo resultados
  divResultado = document.getElementById('resultado');
  //recogemos los valores de los inputs
	selector=valor; // 1: recogida 2: finalizado
	km=document.incModal.incKm.value;
	usuario=document.incModal.user.value;
	referencia=document.incModal.idSv.value;
	matricula=document.incModal.idVh.value;
	idvtaKm=document.incModal.idvtaKm.value;
	estKm=document.incModal.estKm.value;
	recuKm=document.incModal.recuKm.value;

  //instanciamos el objetoAjax
  ajax=objetoAjax();

  //uso del medotod POST
  ajax.open("POST", "/tec/referencia/modals/insertar/instkm.php",true);
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
	ajax.send("km="+km+"&user="+usuario+"&referencia="+referencia+"&idvtaKm="+idvtaKm+"&matricula="+matricula+"&estKm="+estKm+"&selector="+selector+"&recuKm="+recuKm);
}

function insModKmFin(valor){

  //div donde se mostrará lo resultados
  divResultado = document.getElementById('resultado');
  //recogemos los valores de los inputs
	selector=valor; // 1: recogida 2: finalizado
	km=document.incModalFin.incKm.value;
	usuario=document.incModalFin.user.value;
	referencia=document.incModalFin.idSv.value;
	matricula=document.incModalFin.idVh.value;
	idvtaKm=document.incModalFin.idvtaKm.value;
	estKm=document.incModalFin.estKm.value;

  //instanciamos el objetoAjax
  ajax=objetoAjax();

  //uso del medotod POST
  ajax.open("POST", "/tec/referencia/modals/insertar/instkmfin.php",true);
  //cuando el objeto XMLHttpRequest cambia de estado, la función se inicia
  ajax.onreadystatechange=function() {
	  //la función responseText tiene todos los datos pedidos al servidor
  	if (ajax.readyState==4) {
  		//mostrar resultados en esta capa
		resultadoFin.innerHTML = ajax.responseText;
  		//llamar a funcion para limpiar los inputs
		LimpiarCamposFin();
	}
};
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores a registro.php para que inserte los datos
	ajax.send("km="+km+"&user="+usuario+"&referencia="+referencia+"&idvtaKm="+idvtaKm+"&matricula="+matricula+"&estKm="+estKm+"&selector="+selector);
}

//función para limpiar los campos

function LimpiarCampos(){
	document.incModal.incKm.value="";
	//document.incModal.userInci.value="";
	//document.incModal.idSv.value="";
}
function LimpiarCamposFin(){
	document.incModal.incKm.value="";
	//document.incModal.userInci.value="";
	//document.incModal.idSv.value="";
}
