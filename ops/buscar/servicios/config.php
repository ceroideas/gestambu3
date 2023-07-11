<?php
include '../../../functions/function.php';
class Buscador {
	var $host='localhost',$user='root',$pass='d4t4-B4$3$',$db='gestambu3',$c_Servidor='Se conecto con el servidor correctamente',$i_Servidor='No se conecto con el servidor',$c_DB='Se conecto con la DB',$i_DB='No se pudo conectar con la DB';

	function Conectar() {
		if (!@mysql_connect($this->host,$this->user,$this->pass)) {
			print $this->i_Servidor;
		} else {
			if (!@mysql_select_db($this->db)) {
				print $this->i_DB;
			}
		}
	}

	function Buscar($q) {
		$q = utf8_decode($q);
		$s = $_GET['s'];
		$chars = strlen($q);
		if ($chars <= 2) {
			print '<span class="help-block h6"> <i class="fa fa-exclamation-triangle"></i> Se ha de introducir como mínimo 3 carcteres para la busqueda</span>';
		}else {
			if ($s == 0) {
				print 'No hay criterios de busqueda';
			} elseif ($s == 1) {
				$query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.nombre LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} elseif ($s == 2) {
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.apellidos LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			}elseif ($s == 3){
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.tlf1 LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} elseif($s == 4) {
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.DNIPac LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} elseif($s == 5) {
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.poliza LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} elseif($s == 6) {
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.recoger LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} elseif($s == 7) {
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.autorizacion LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} elseif($s == 8) {
        $query = mysql_query("SELECT servicio.idSv, servicio.idPac, servicio.idCia, servicio.DNIPac, servicio.poliza, servicio.autorizacion, servicio.continuado, servicio.provincia AS locProv, servicio.tipo, servicio.recurso, DATE_FORMAT(servicio.fecha, '%d-%m-%Y') AS fecha,
				 		servicio.hora, servicio.delegacion, servicio.medico, servicio.enfermero, servicio.nombre, servicio.apellidos, servicio.tlf1, servicio.tlf2, servicio.edad, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.estServ,
						cia.idCia, cia.ciaNom, provincias.id, provincias.provincia, estados.idEst, estados.vaEst, servi.idServi, servi.nomSer, servi.icono
          FROM servicio
						LEFT JOIN cia ON servicio.idCia = cia.idCia
						LEFT JOIN provincias ON servicio.delegacion = provincias.id
						LEFT JOIN estados ON servicio.estServ = estados.idEst
						LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE servicio.continuado LIKE '%$q%'
          ORDER BY servicio.nombre, servicio.fecha ASC
          ");
			} else {
				$query = mysql_query("SELECT * FROM paciente WHERE pNombre LIKE '%$q%'");
			}
		}

		if (@mysql_num_rows(@$query)<=0) {
			print '
					<div class="box-footer">
						<span class="help-block h6">No se encontro ningun resultado:'.@mysql_num_rows(@$query).'</span>
					</div>';
		}else{
			print '<table class="table table-hover">
							<tr>
								<th>Acción</th>
								<th>ID</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Tipo</th>
								<th>Provincia</th>
								<th>Cia.</th>
								<th>DNI</th>
								<th>Paciente</th>
								<th>Póliza</th>
								<th>Auto.</th>
								<th>Tlf1</th>
								<th>Tlf2</th>
								<th>Recoger</th>
								<th>Loc.Rec.</th>
								<th>Trasladar</th>
								<th>Loc.Tras.</th>
								<th>Estado</th>
							</tr>';
				while (@$row = mysql_fetch_assoc(@$query)) {
					$provSel = provValorRet($row['locProv']);
				print '
					 <tr>
					   <td><a href="/ops/mostrar/editServ.php?iden='.$row['idSv'].'" target="_blank"><i class="fa fa-pencil-square-o"></i></a> <i class="fa fa-print"></i></td>
					   <td>'.$row['idSv'].'</td>
						 <td>'.$row['fecha'].'</td>
					   <td>'.substr($row['hora'], 0, 5).'</td>
					   <td><i class="fa fa-'.$row['icono'].'"></i> '.$row['nomSer'].'</td>
					   <td>'.$provSel.'</td>
					   <td>'.$row['ciaNom'].'</td>
					   <td>'.$row['DNIPac'].'</td>
					   <td>'.utf8_encode($row['nombre'].' '.$row['apellidos']).'</td>
					   <td>'.$row['poliza'].'</td>
					   <td>'.$row['autorizacion'].'</td>
					   <td>'.$row['tlf1'].'</td>
					   <td>'.$row['tlf2'].'</td>
					   <td>'.utf8_encode($row['recoger']).'</td>
					   <td>'.utf8_encode($row['locRec']).'</td>
					   <td>'.utf8_encode($row['trasladar']).'</td>
					   <td>'.utf8_encode($row['locTras']).'</td>
					   <td>'.$row['vaEst'].'</td>
					 </tr>';
			}
			print '</table>';
			print '
					<div class="box-footer">
						<span class="help-block h6">Registros encontrados segun criterios de busqueda: '.mysql_num_rows(@$query).'</span>
					</div>';
		}
	}

	function Eliminar($servicio_id) {
		if (mysql_query("DELETE FROM servicio WHERE servicio_id = '$servicio_id'")) {
			print "Se elimino el registro correctamente";
		}
	}
}

?>
