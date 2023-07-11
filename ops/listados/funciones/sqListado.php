<?php 
/* CONSULTA PARA LISTADOS */
# Para el listado de compañías facturables, en el apartado de ambulancias continuadas, sólo muestra los servicios continuados
# Para el resto de las demás compañías muestra los servicios de ambulancia y continuados juntos
if($aSelc == '-2') {
	if($selProv == '29') { // Málaga
		# No muestra ni Asisa ni Adeslas en el listado resultante
		if($seleCont == '0') {
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia NOT IN('1','2') AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");		
		} else {
			# Sólo muestra los servicios continuados
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia NOT IN('1','2') AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado != '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");			
		}
	} elseif($selProv == '41') { // Sevilla
		# No muestra Asisa
		if($seleCont == '0') {
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia != '1' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");		
		} else {
			# Sólo muestra los servicios continuados
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia != '1'  AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado != '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");
		}				
	} elseif($selProv == '11') { // Cádiz
		# Muestra todas las compañías
		if($seleCont == '0') {
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");		
		} else {
			# Sólo muestra los servicios continuados
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado != '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");
		}
	} else {
		# Muestra todos los servicios de las compañias 
		if($seleCont == '0') {
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");		
		} else {
			# Sólo muestra los servicios continuados
			$sqlCons = mysqli_query($gestambu, 
				"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.continuado != '0'
				ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");
		}				
	}
} elseif($aSelc == '-3') {
	# Eventos - no tiene encuenta filtrado por compañía ni es es o no un continuado
	# Para eventos no distingue el tipo de servicio. Puede ser cualquiera, siempre que tipifique como Evento, Preventivo, Festejo...
	$sqlCons = mysqli_query($gestambu, 
	"SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN factura ON servicio.idSv = factura.idSvTab
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.tipo IN ('15','18','23')
			AND servicio.provincia = '$selProv'
	ORDER BY servicio.idCia, servicio.fecha, servicio.nombre ASC ");
} elseif($aSelc == '-4' ) { //ASISA MALAGA NO FACTURADOS
	$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
			servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
			cia.idCia, cia.ciaNom,
			recurso.idRecu, recurso.recuCorto,
			servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
		FROM servicio
			LEFT JOIN cia ON servicio.idCia = cia.idCia
			LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
			LEFT JOIN servi ON servicio.tipo = servi.idServi
			LEFT JOIN factura ON servicio.idSv = factura.idSvTab
		WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '1' AND servicio.recurso IN ($codRecu) 
				AND servicio.provincia = '29' AND servicio.delegacion NOT IN('53', '29', '0')
		ORDER BY servicio.fecha, servicio.nombre ASC ");
} else {
	if($selRecu == '1') {
		/* AMBULANCIA */
		if($selProv == '29') { // Ambulancias - Málaga
			if($aSelc == '1') { // Asisa
				if($selDele == 0) { //Del.Actual
					if($seleCont == '0') { //No mostrar continuados
						/* Málaga-Asisa-Del.actual-No cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km,servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion !='53'
							ORDER BY servicio.fecha, servicio.nombre ASC ");
					} else { //Mostrar continuados
						/* Málaga-Asisa-Del.actual-Si Cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion !='53'
							ORDER BY servicio.fecha, servicio.nombre ASC ");
					}
				} else { //Del. Distinta
					if($seleCont == '0') { //No mostrar continuados
						/* Málaga-Asisa-Del.distinta-No cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion ='53'
							ORDER BY servicio.fecha, servicio.nombre ASC ");
					} else { //Mostrar continuados
						/* Málaga-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion ='53'
							ORDER BY servicio.fecha, servicio.nombre ASC ");				
					}			
				}
			} elseif($aSelc == '2') { //Adeslas
				if($selDele == 0) { //Del.Actual
					if($seleCont == '0') { //No mostrar continuados
						/* Málaga-Adeslas-Del.actual-No cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0','29')
							ORDER BY servicio.fecha, servicio.nombre ASC ");				
					} else { //Mostrar continuados
						/* Málaga-Adeslas-Del.actual-Si Cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion IN('0','29')
							ORDER BY servicio.fecha, servicio.nombre ASC ");				
					}
				} else { //Del. Distinta
					if($seleCont == '0') { //No mostrar continuados
						/* Málaga-Adeslas-Del.distinta-No cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0','29')
							ORDER BY servicio.fecha, servicio.nombre ASC ");
					} else { //Mostrar continuados
						/* Málaga-Adeslas-Del.distinta-Si Cont. */
						$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
								servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
								cia.idCia, cia.ciaNom,
								recurso.idRecu, recurso.recuCorto,
								servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
							FROM servicio
								LEFT JOIN cia ON servicio.idCia = cia.idCia
								LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
								LEFT JOIN servi ON servicio.tipo = servi.idServi
								LEFT JOIN factura ON servicio.idSv = factura.idSvTab
							WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
									AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0','29')
							ORDER BY servicio.fecha, servicio.nombre ASC ");
					}			
				}	
			} else { //Otras
				if($seleCont == '0') { //No mostrar continuados
					/* Málaga-Otras-Not cont. */
					$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
							LEFT JOIN factura ON servicio.idSv = factura.idSvTab
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
						ORDER BY servicio.fecha, servicio.nombre ASC ");			
				} else { //Mostrar continuados
					/* Málaga-Otras-Si Cont. */
					$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
							LEFT JOIN factura ON servicio.idSv = factura.idSvTab
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv'
						ORDER BY servicio.fecha, servicio.nombre ASC ");
				}			
			}
		} elseif($selProv == '41') { //Ambulancias - Sevilla
			if($aSelc == '1') { // Asisa
				if($asisaSe == '1') { //Asisa Rad
					if($selDele == 0) { //Del.Actual
						if($seleCont == '0') { //No mostrar continuados
							/* Sevilla-Asisa(RAD)-Del.actual-No cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41') AND servicio.coDemanda IS NOT NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						} else { //Mostrar continuados
							/* Sevilla-Asisa(RAD)-Del.actual-Si Cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NOT NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						}
					} else { //Del. Distinta
						if($seleCont == '0') { //No mostrar continuados
							/* Sevilla-Asisa-Del.distinta-No cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NOT NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						} else { //Mostrar continuados
							/* Sevilla-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NOT NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");				
						}			
					}										
				} elseif($asisaSe == '2') { //Asisa delegación
					if($selDele == 0) { //Del.Actual
						if($seleCont == '0') { //No mostrar continuados
							/* Sevilla-Asisa-Del.actual-No cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41') AND servicio.coDemanda IS NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						} else { //Mostrar continuados
							/* Sevilla-Asisa-Del.actual-Si Cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						}
					} else { //Del. Distinta
						if($seleCont == '0') { //No mostrar continuados
							/* Sevilla-Asisa-Del.distinta-No cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						} else { //Mostrar continuados
							/* Sevilla-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia,  
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac, servicio.tipo, servicio.coDemanda,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41') AND servicio.coDemanda IS NULL
								ORDER BY servicio.fecha, servicio.nombre ASC ");				
						}			
					}									
				} else {
					if($selDele == 0) { //Del.Actual
						if($seleCont == '0') { //No mostrar continuados
							/* Sevilla-Asisa-Del.actual-No cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion IN('0', '41')
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						} else { //Mostrar continuados
							/* Sevilla-Asisa-Del.actual-Si Cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' 
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						}
					} else { //Del. Distinta
						if($seleCont == '0') { //No mostrar continuados
							/* Sevilla-Asisa-Del.distinta-No cont. */
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.continuado = '0' AND servicio.delegacion NOT IN('0', '41')
								ORDER BY servicio.fecha, servicio.nombre ASC ");
						} else { //Mostrar continuados
							/* Sevilla-Asisa-Del.distinta-Si Cont. (solo marcados como del 53)*/
							$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
									servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
									cia.idCia, cia.ciaNom,
									recurso.idRecu, recurso.recuCorto,
									servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
								FROM servicio
									LEFT JOIN cia ON servicio.idCia = cia.idCia
									LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
									LEFT JOIN servi ON servicio.tipo = servi.idServi
									LEFT JOIN factura ON servicio.idSv = factura.idSvTab
								WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
										AND servicio.provincia = '$selProv' AND servicio.delegacion NOT IN('0', '41')
								ORDER BY servicio.fecha, servicio.nombre ASC ");				
						}			
					}
				}					
			} else {
				if($seleCont == '0') { //No mostrar continuados
					/* Sevilla-Otras-Not cont. */
					$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
							LEFT JOIN factura ON servicio.idSv = factura.idSvTab
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
						ORDER BY servicio.fecha, servicio.nombre ASC ");			
				} else { //Mostrar continuados
					/* Sevilla-Otras-Si Cont. */
					$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
							servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
							cia.idCia, cia.ciaNom,
							recurso.idRecu, recurso.recuCorto,
							servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
						FROM servicio
							LEFT JOIN cia ON servicio.idCia = cia.idCia
							LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
							LEFT JOIN servi ON servicio.tipo = servi.idServi
							LEFT JOIN factura ON servicio.idSv = factura.idSvTab
						WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
								AND servicio.provincia = '$selProv'
						ORDER BY servicio.fecha, servicio.nombre ASC ");
				}		
			}
		} else { // Ambulancias - Otras
			if($seleCont == '0') { //No mostrar continuados
				/* Sevilla-Otras-Not cont. */
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv' AND servicio.continuado = '0'
					ORDER BY servicio.fecha, servicio.nombre ASC ");			
			} else { //Mostrar continuados
				/* Sevilla-Otras-Si Cont. */
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv'
					ORDER BY servicio.fecha, servicio.nombre ASC ");
			}
		}
	/*	
	} elseif($selRecu == '2') {
	} elseif($selRecu == '3') {
	} elseif($selRecu == '5') {
	*/
	} elseif($selRecu == '4') {
		/* VISITA MEDICA */
		# Visitas médicas Sevilla - RAD / Delegación
		if($selProv == '41' AND $aSelc == '1') { // Filtro para Sevilla y Asisa
			if($asisaSe == '1') { //RAD
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						servicio.coDemanda, cia.idCia, cia.ciaNom, 
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv' AND servicio.tipo ='2' AND servicio.coDemanda IS NOT NULL
					ORDER BY servicio.fecha, servicio.nombre ASC ");			
			} elseif($asisaSe == '2') { //Delegación
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						servicio.coDemanda, cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv' AND servicio.tipo ='2' AND servicio.coDemanda IS NULL
					ORDER BY servicio.fecha, servicio.nombre ASC ");			
			} else { //Todos
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
							AND servicio.provincia = '$selProv' AND servicio.tipo ='2'
					ORDER BY servicio.fecha, servicio.nombre ASC ");			
			}
		} else {
			$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
						AND servicio.provincia = '$selProv' AND servicio.tipo ='2'
				ORDER BY servicio.fecha, servicio.nombre ASC ");
		}

	} elseif($selRecu == '6') {	
		/* SEG. MEDICO */
		$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
				servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
				cia.idCia, cia.ciaNom,
				recurso.idRecu, recurso.recuCorto,
				servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
			FROM servicio
				LEFT JOIN cia ON servicio.idCia = cia.idCia
				LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
				LEFT JOIN servi ON servicio.tipo = servi.idServi
				LEFT JOIN factura ON servicio.idSv = factura.idSvTab
			WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu) 
					AND servicio.provincia = '$selProv' AND servicio.tipo ='9'
			ORDER BY servicio.fecha, servicio.nombre ASC ");	
	} else {
		/* OTRO - TODAS */
		# Enfermería - Asisa Sevilla - RAD / Delegación		
		if($selProv == '41' AND $aSelc == '1') { // Filtro para Sevilla y Asisa
			if($asisaSe == '1') { //RAD
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						servicio.coDemanda, cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
							AND servicio.provincia = '$selProv' AND servicio.coDemanda IS NOT NULL
					ORDER BY servicio.fecha, servicio.nombre ASC ");
				//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'			
			} elseif($asisaSe == '2') { // Delegación
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						servicio.coDemanda, cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
							AND servicio.provincia = '$selProv' AND servicio.coDemanda IS NULL
					ORDER BY servicio.fecha, servicio.nombre ASC ");
				//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'				
			} else { //Todo
				$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
						servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
						cia.idCia, cia.ciaNom,
						recurso.idRecu, recurso.recuCorto,
						servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
					FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
						LEFT JOIN servi ON servicio.tipo = servi.idServi
						LEFT JOIN factura ON servicio.idSv = factura.idSvTab
					WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
							AND servicio.provincia = '$selProv'
					ORDER BY servicio.fecha, servicio.nombre ASC ");
				//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'			
			}
		} else {
			$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
					servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
					cia.idCia, cia.ciaNom,
					recurso.idRecu, recurso.recuCorto,
					servi.idServi, servi.nomSer, servi.icono, factura.idSvTab, factura.idFac, factura.numFac
				FROM servicio
					LEFT JOIN cia ON servicio.idCia = cia.idCia
					LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
					LEFT JOIN servi ON servicio.tipo = servi.idServi
					LEFT JOIN factura ON servicio.idSv = factura.idSvTab
				WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.recurso IN ($codRecu)
						AND servicio.provincia = '$selProv'
				ORDER BY servicio.fecha, servicio.nombre ASC ");
			//AND servicio.continuado = '0' AND servicio.locRec != 'MALAGA'		
		}			
	}
}

function compPaCro($idPaciente) {
  global $gestambu;
  $mostSeg = mysqli_query($gestambu, "SELECT idPac, segMed FROM paciente WHERE idPac = '$idPaciente' AND segMed = '1'");
  $numSeg = mysqli_num_rows($mostSeg);
  
  if($numSeg == '1') {
	  echo "<i class=\"fa fa-medkit\"></i>";
  }
}
?>