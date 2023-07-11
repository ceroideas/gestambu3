<?php
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
				$query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.pacDNI, paciente.fallecido, cia.idCia, cia.ciaNom
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
          WHERE pNombre LIKE '%$q%' AND paciente.fallecido !='2'
          ORDER BY pNombre ASC
          ");
			} elseif ($s == 2) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.pacDNI,  paciente.fallecido, cia.idCia, cia.ciaNom
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
          WHERE pApellidos LIKE '%$q%' AND paciente.fallecido !='2'
          ORDER BY pNombre ASC
          ");
			}elseif ($s == 3){
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.pacDNI, paciente.fallecido, cia.idCia, cia.ciaNom
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
          WHERE tlf1 LIKE '%$q%' AND paciente.fallecido !='2'
          ORDER BY pNombre ASC
          ");
			} elseif($s == 4) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.pacDNI, paciente.fallecido, cia.idCia, cia.ciaNom
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
          WHERE pacDNI LIKE '%$q%' AND paciente.fallecido !='2'
          ORDER BY pNombre ASC
          ");
			} elseif($s == 5) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.pacDNI, paciente.fallecido, cia.idCia, cia.ciaNom
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
          WHERE poliza LIKE '%$q%' AND paciente.fallecido !='2'
          ORDER BY pNombre ASC
          ");
			} elseif($s == 6) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.pacDNI, paciente.fallecido, cia.idCia, cia.ciaNom
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
          WHERE direccion LIKE '%$q%' AND paciente.fallecido !='2'
          ORDER BY pNombre ASC
          ");
			} else {
				$query = mysql_query("SELECT * FROM paciente WHERE pNombre LIKE '%$q%' AND paciente.fallecido !='2'");
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
								<th colspan="4">Crear</th>
								<th>ID</th>
								<th>Provincia</th>
								<th>Cia.</th>
								<th>Seg_Med.</th>
								<th>DNI</th>
								<th>Nombre</th>
								<th>Apellidos</th>
								<th>Póliza</th>
								<th>Edad</th>
								<th>Delg.</th>
								<th>Tlf1</th>
								<th>Tlf2</th>
								<th>Dirección</th>
								<th>Localidad</th>
							</tr>';
				while (@$row = mysql_fetch_assoc(@$query)) {
					if($row['segMed'] == 0 ) { $segMed = '--'; } else { $segMed = '<i class="fa fa-check"></i>'; }
				print '
					 <tr>
					   <td><a href="/ops/referencia/crear/vincuPac.php?iden='.$row['idPac'].'&selRec=1" title="Crear Ambulancia"><i class="fa fa-ambulance"></i></a></td>
					   <td><a href="/ops/referencia/crear/vincuPac.php?iden='.$row['idPac'].'&selRec=4" title="Crear Visita médica"><i class="fa fa-stethoscope"></i></a></td>
					   <td><a href="/ops/referencia/crear/vincuPac.php?iden='.$row['idPac'].'&selRec=2" title="Crear Enfermería"><i class="fa fa-eyedropper"></i></a></td>
					   <td><a href="/ops/mostrar/paciente.php?idPac='.$row['idPac'].'" title="Ver ficha de paciente"><i class="fa fa-pencil-square-o"></i></a></td>
					   <td>'.$row['idPac'].'</td>
					   <td>'.$row['provincia'].'</td>
					   <td>'.$row['ciaNom'].'</td>
						 <td>'.$segMed.'</td>
					   <td>'.utf8_encode($row['pacDNI']).'</td>
					   <td>'.utf8_encode($row['pNombre']).'</td>
					   <td>'.utf8_encode($row['pApellidos']).'</td>
					   <td>'.$row['poliza'].'</td>
					   <td>'.utf8_encode($row['edad']).'</td>
					   <td></td>
					   <td>'.$row['tlf1'].'</td>
					   <td>'.$row['tlf2'].'</td>
					   <td>'.utf8_encode($row['direccion']).'</td>
					   <td>'.utf8_encode($row['localidad']).'</td>
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
