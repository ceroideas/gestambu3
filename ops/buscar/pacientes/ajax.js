// JavaScript Document
function Buscador(){
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

function Buscar() {
	q = document.getElementById('valor').value;
	s = document.getElementById('seleccion').value;
	c = document.getElementById('resultados');
	ajax = Buscador();
	ajax.open("GET","procesar.php?q="+q+"&s="+s);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4) {
			c.innerHTML = ajax.responseText;
		}
	};
	ajax.send(null);

}
