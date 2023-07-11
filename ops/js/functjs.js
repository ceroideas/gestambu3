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
function imprimir() {
	c = document.getElementById('check').value;

	if(c == 0) {
		s = document.getElementById('servicio_id').value;
		u = document.getElementById('user_crear').value;
		imp = Buscador();
		imp.open("GET","../referencia/insert_imp.php?servicio_id="+s+"&user="+u);
		imp.onreadystatechange = function() {
			if (imp.readyState == 4 ) {
				c.innerHTML = imp.responseText;
				}
			}
		imp.send(null)
		//document.getElementById('mega_m').style.display='none';
		//document.getElementById('cssmenu').style.visibility='hidden';
		//noprint.style.visibility = 'hidden'; 
		window.print();

	} else {
		if(confirm('El registro ya se ha imprimido. ¿Volver a imprimir?')) {
			s = document.getElementById('servicio_id').value;
			u = document.getElementById('user_crear').value;
			imp = Buscador();
			imp.open("GET","../referencia/insert_imp.php?servicio_id="+s+"&user="+u);
			imp.onreadystatechange = function() {
				if (imp.readyState == 4 ) {
					c.innerHTML = imp.responseText;
					}
				}
			imp.send(null)
			//document.getElementById('mega_m').style.display='none';
			//document.getElementById('cssmenu').style.visibility='hidden';
			//noprint.style.visibility = 'hidden'; 
			window.print()

		}else {
			return false;
		}
	}
}
