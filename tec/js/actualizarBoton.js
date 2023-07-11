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

function enviarDatosEmpleado(){
	//donde se mostrará lo resultados
	divResultado = document.getElementById('resultado');
	divFormulario = document.getElementById('formulario');
	//valores de los inputs
	id=document.frmempleado.idempleado.value;
	nom=document.frmempleado.nombres.value;
	dep=document.frmempleado.departamento.value;
	suel=document.frmempleado.sueldo.value;

	//instanciamos el objetoAjax
	ajax=objetoAjax();
	//usando del medoto POST
	//archivo que realizará la operacion
	//actualizacion.php
	ajax.open("POST", "actualizacion.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar los nuevos registros en esta capa
			divResultado.innerHTML = ajax.responseText;
			//mostrar un mensaje de actualizacion correcta
			divFormulario.innerHTML = "<p style=\"border:1px solid red; width:400px;\">La actualizaci&oacute;n se realiz&oacute; correctamente</p>";
		}
	};
	//muy importante este encabezado ya que hacemos uso de un formulario
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	//enviando los valores
	ajax.send("idempleado="+id+"&nombres="+nom+"&departamento="+dep+"&sueldo="+suel);
}
