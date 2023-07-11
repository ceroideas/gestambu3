<?php 
/* CONSULTA PARA LISTADOS */
$sqlCons = mysqli_query($gestambu, "SELECT servicio.idSv, COUNT(servicio.continuado) AS sesiones, servicio.continuado, servicio.idCia, servicio.fecha, servicio.delegacion, servicio.poliza, servicio.autorizacion, servicio.provincia, servicio.tipo, 
		servicio.recurso, servicio.idvta,servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.locRec, servicio.locTras, servicio.km, servicio.estServ, servicio.idPac,
		cia.idCia, cia.ciaNom,
		recurso.idRecu, recurso.recuCorto,
		servi.idServi, servi.nomSer, servi.icono
	FROM servicio
		LEFT JOIN cia ON servicio.idCia = cia.idCia
		LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
		LEFT JOIN servi ON servicio.tipo = servi.idServi
	WHERE servicio.estServ NOT IN('15','16','17') AND servicio.continuado IN($seleccion)
			AND servicio.provincia = '$selProv'
	GROUP BY servicio.continuado
	ORDER BY servicio.nombre ASC 
	");
	
function compPaCro($idPaciente) {
  global $gestambu;
  $mostSeg = mysqli_query($gestambu, "SELECT idPac, segMed FROM paciente WHERE idPac = '$idPaciente' AND segMed = '1'");
  $numSeg = mysqli_num_rows($mostSeg);
  
  if($numSeg == '1') {
	  echo "<i class=\"fa fa-medkit\"></i>";
  }
}
?>