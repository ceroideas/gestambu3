<?php 
/* CONSULTA PARA LISTADOS */
if($selProv == '29') {
	$locProv = 'MALAGA';
} elseif($selProv == '41') {
	$locProv = 'SEVILLA';
} elseif($selProv == '11') {
	$locProv = 'ALGECIRAS';
} elseif($selProv == '21') {
	$locProv = 'HUELVA';
} elseif($selProv == '52') {
	$locProv = 'MELILLA';
} elseif($selProv == '14') {
	$locProv = 'CORDOBA';	
} else {
	$locProv = '0';
}


# Ambulancias Urbanas Urgentes
$sqlAmbUrg = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.hora, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.provincia = '$selProv' 
		AND servicio.locRec = '$locProv' AND servicio.locTras = '$locProv' AND servicio.tipo = '1'
	ORDER BY servicio.fecha, servicio.nombre ASC ");

# Ambulancias Interurbanas Urgentes
$sqlAmbNoUrg = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.hora, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.provincia = '$selProv'
		AND (servicio.locRec != '$locProv' OR servicio.locTras != '$locProv') AND servicio.tipo = '1'
	ORDER BY servicio.fecha, servicio.nombre ASC ");

# Ambulancias Programadas Urbanas
$sqlAmbPrg = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.hora, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, serhorario.idRefSv, serhorario.idReco, serhorario.idFin, serhorario.vtaReco,serhorario.vtaFin
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.provincia = '$selProv'
		AND servicio.locRec = '$locProv' AND servicio.locTras = '$locProv' AND servicio.tipo IN('3','5','6','7','8','10','11','12','14','24')
	ORDER BY servicio.fecha, servicio.nombre ASC ");

# Ambulancias Programadas Interurbanas
$sqlAmbPrgNUrb = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.hora, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, serhorario.idRefSv, serhorario.idReco, serhorario.idFin, serhorario.vtaReco,serhorario.vtaFin
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.provincia = '$selProv'
		AND (servicio.locRec != '$locProv' OR servicio.locTras != '$locProv') AND servicio.tipo IN('3','5','6','7','8','10','11','12','14','24')
	ORDER BY servicio.fecha, servicio.nombre ASC ");		

# Visitas Médicas Urgentes Urbanas
$sqlVmedica = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.hora, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.provincia = '$selProv' 
		AND servicio.locRec = '$locProv' AND servicio.tipo = '2'
	ORDER BY servicio.fecha, servicio.nombre ASC ");
	
# Visitas Médicas Urgentes Interurbanas
$sqlVMNourb = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.continuado, servicio.idCia, servicio.fecha, servicio.hora, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono, serhorario.idRefSv, serhorario.idReco, serhorario.idFin
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
		LEFT JOIN serhorario ON servicio.idSv = serhorario.idRefSv
	WHERE servicio.fecha BETWEEN '$diaIni' AND '$diaFin' AND servicio.estServ NOT IN('15','16') AND servicio.idCia = '$aSelc' AND servicio.provincia = '$selProv' 
		AND servicio.locRec != '$locProv' AND servicio.tipo = '2'
	ORDER BY servicio.fecha, servicio.nombre ASC ");
	
	
function compPaCro($idPaciente) {
  global $gestambu;
  $mostSeg = mysqli_query($gestambu, "SELECT idPac, segMed FROM paciente WHERE idPac = '$idPaciente' AND segMed = '1'");
  $numSeg = mysqli_num_rows($mostSeg);
  
  if($numSeg == '1') {
	  echo "<i class=\"fa fa-medkit\"></i>";
  }
}
?>