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
		$s = $_GET['s'];
		$chars = strlen($q);
		if ($chars <= 2) {
			print '<span class="help-block h6"> <i class="fa fa-exclamation-triangle"></i> Se ha de introducir como mínimo 3 carcteres para la busqueda</span>';
		}else {
			if ($s == 0) {
				print 'No hay criterios de busqueda';
			} elseif ($s == 1) {
				$query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.fallecido, paciente.pacDNI, paciente.medAsig, paciente.tipoSeg, paciente.fallecido, cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
						LEFT JOIN user ON paciente.medAsig = user.userId
          WHERE paciente.pNombre LIKE '%$q%' AND paciente.segMed != '0'
          ORDER BY paciente.pNombre ASC
          ");
			} elseif ($s == 2) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.fallecido, paciente.pacDNI, paciente.medAsig, paciente.tipoSeg, cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
						LEFT JOIN user ON paciente.medAsig = user.userId
          WHERE pApellidos LIKE '%$q%' AND paciente.segMed != '0'
          ORDER BY pNombre ASC
          ");
			}elseif ($s == 3){
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.fallecido, paciente.pacDNI, paciente.medAsig, paciente.tipoSeg, paciente.fallecido,  cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
						LEFT JOIN user ON paciente.medAsig = user.userId
          WHERE paciente.tlf1 LIKE '%$q%' AND paciente.segMed != '0'
          ORDER BY paciente.pNombre ASC
          ");
			} elseif($s == 4) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.fallecido, paciente.pacDNI, paciente.medAsig, paciente.tipoSeg, paciente.fallecido, cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
						LEFT JOIN user ON paciente.medAsig = user.userId
          WHERE paciente.pacDNI LIKE '%$q%' AND paciente.segMed != '0'
          ORDER BY paciente.pNombre ASC
          ");
			} elseif($s == 5) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.fallecido, paciente.pacDNI, paciente.medAsig, paciente.tipoSeg, paciente.fallecido, cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
						LEFT JOIN user ON paciente.medAsig = user.userId
          WHERE paciente.poliza LIKE '%$q%' AND paciente.segMed != '0'
          ORDER BY pNombre ASC
          ");
			} elseif($s == 6) {
        $query = mysql_query("SELECT paciente.idPac, paciente.idCia, paciente.pNombre, paciente.pApellidos, paciente.edad, paciente.poliza, paciente.tlf1, paciente.tlf2, paciente.obs, paciente.direccion, paciente.localidad, paciente.provincia, paciente.segMed,
						paciente.fallecido, paciente.pacDNI, paciente.medAsig, paciente.tipoSeg, paciente.fallecido, cia.idCia, cia.ciaNom, user.userId, user.usNom, user.usApe
          FROM paciente
						LEFT JOIN cia ON paciente.idCia = cia.idCia
						LEFT JOIN user ON paciente.medAsig = user.userId
          WHERE paciente.direccion LIKE '%$q%' AND paciente.segMed != '0'
          ORDER BY paciente.pNombre ASC
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
								<th>Provincia</th>
								<th>Cia.</th>
								<th>Seg_Med.</th>
								<th>DNI</th>
								<th>Nombre</th>
								<th>Póliza</th>
								<th>Edad</th>
								<th>Prov.</th>
								<th>Tlf1</th>
								<th>Tlf2</th>
								<th>Dirección</th>
								<th>Localidad</th>
								<th>Médico</th>
								<th>Fallecido</th>
							</tr>';
				while (@$row = mysql_fetch_assoc(@$query)) {
					if($row['tipoSeg'] == 1 ) { $segMed = 'Crónico'; } elseif ($row['tipoSeg'] == 2) { $segMed = 'Paliativo'; } else { $segMed = ' -- '; }
					if($row['fallecido'] == 0 ) { $exitus = '--'; } else { $exitus = 'Fallecido'; }
					$provPac = provValorRet($row['provincia']);
				print '
					 <tr>
					   <td><a href="/ops/mostrar/paciente.php?idPac='.$row['idPac'].'" title="Ver ficha de paciente"><i class="fa fa-pencil-square-o"></i></a></td>
					   <td>'.$row['idPac'].'</td>
					   <td>'.$provPac.'</td>
					   <td>'.$row['ciaNom'].'</td>
						 <td>'.$segMed.'</td>
					   <td>'.$row['pacDNI'].'</td>
					   <td>'.$row['pNombre'].' '.$row['pApellidos'].'</td>
					   <td>'.$row['poliza'].'</td>
					   <td>'.$row['edad'].'</td>
					   <td>'.$provPac.'</td>
					   <td>'.$row['tlf1'].'</td>
					   <td>'.$row['tlf2'].'</td>
					   <td>'.$row['direccion'].'</td>
					   <td>'.$row['localidad'].'</td>
						 <td>'.$row['usNom'].' '.$row['usApe'].'</td>
					   <td>'.$exitus.'</td>
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
