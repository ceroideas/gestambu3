<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jData = explode("-", $_POST['id']);

$jCampo      = $jData['0']; //Nombre del campo
$jId         = $jData['1']; //Id del registro
$jValor      = $_POST['value']; // valor por el cual reemplazar

//Comprueba si existe registro en tabla serhorario
$compHorarios = mysqli_query($gestambu, "SELECT * FROM serhorario WHERE idRefSv = '$jId' ");
$rwCompHora   = mysqli_fetch_assoc($compHorarios);
$numHorarios  = mysqli_num_rows($compHorarios);
$compCia      = mysqli_query($gestambu, "SELECT servicio.idSv,servicio.idCia,servicio.coDemanda,servicio.idasistencia, asisademanda.cod_demanda, asisademanda.cod_servicio 
	FROM servicio LEFT JOIN asisademanda ON servicio.coDemanda = asisademanda.cod_demanda WHERE servicio.idSv ='$jId'");
$rwCompCia    = mysqli_fetch_assoc($compCia);

if($numHorarios == '0') {
	//Crea nuevo resgistro
	//El campo de hora es un VARCHAR(5) si se utiliza para cálculos de horas se tendrá que formatear a hora H:i:s
	$valorHora   = "";
	$insHorarios = "INSERT INTO serhorario (idRefSv, ".$jCampo.") VALUES ('$jId', '$jValor') ";
	if(mysqli_query($gestambu,$insHorarios)) {
		echo $jValor;
		/* Mensajes de log */
		$obsText = $jCampo." : ".$jValor;
		$usuario = $_SESSION['userId'];
		guardarLog('11', $usuario, $obsText, $jId);
	} else {
		echo "Error: " . $insHorarios . "<br>" . mysqli_error($gestambu);
	}
} else {
	//Actualiza el registro
	$valorHora = $rwCompHora[$jCampo];
	$jActHora  = "UPDATE serhorario SET  ".$jCampo." = '".$jValor."'
								WHERE idRefSv = ".$jId." ";
	if(mysqli_query($gestambu,$jActHora)) {

		/* Mensajes de log */
		$obsText = $jCampo." : ".$jValor;
		$usuario = $_SESSION['userId'];
		guardarLog('11', $usuario, $obsText, $jId);
		
	} else {
		echo "Error: " . $jActHora . "<br>" . mysqli_error($gestambu);
	}
	echo $jValor;	
}

/* Notificaciones Asisa */
//No reportar estado 3 cuando sea hora de finalizado
if(($rwCompCia['idCia'] == 1 || $rwCompCia['idCia'] == 103) && $rwCompCia['coDemanda'] > 1 ) { 
	//Envia notificación cuando la compañía es Asisa y existe notificacion en Código de demanda
	//Agregada compañía HLA -> 103
	//if(empty($valorHora)) { //Cuando no existe una hora determinada
		$codDemandAsisa = $rwCompCia['coDemanda'];
		$idasistencia   = $rwCompCia['idasistencia'];
		$codAmbu        = $rwCompCia['cod_servicio']; 

		//Calculo de estado

		if($jCampo == "idReco" || $jCampo == "vtaReco") {
			$estAsisa = "4";
		} elseif($jCampo == "idFin" || $jCampo == "vtaFin") {
			$estAsisa = "5";		
		}
		//Cálculo para ida y vuelta
		if($codAmbu > 69 && $codAmbu < 131) { // codigo de servicio de ambulancia
			//Si no reporta correctamente probar con id y vuelta: 1 - 2 - 3
			if($jCampo == "idReco" || $jCampo == "idFin") {
				$vueltaAbr = "N";
			} elseif($jCampo == "vtaReco" || $jCampo == "vtaFin") {
				$vueltaAbr = "S";
			}
		} else { // Para servicios que no son ambulancia no reportarlos
			$vueltaAbr = "";
		}
		//Enviar a la vuelta la hora que llama el paciente, cambio de hora de vuelta - tabla serinfo -> hvuelta
		if($vueltaAbr == "") {
			$Asisa24 = mysqli_query($gestambu, " SELECT asisaasistencia.idasistencia, asisaasistencia.cod_demanda, asisaasistencia.hora_asistencia, asisaasistencia.fecha_asistencia, asisaasistencia.vuelta, 
					asisademanda.fecha_peticion, asisademanda.hora_peticion
				FROM asisaasistencia 
					LEFT JOIN asisademanda ON asisaasistencia.cod_demanda = asisademanda.cod_demanda
				WHERE asisaasistencia.idasistencia = '$idasistencia'
			
			");
			/*
			$Asisa24 = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.coDemanda, servicio.idemanda, servicio.idasistencia,
					asisaasistencia.fecha_asistencia, asisaasistencia.hora_asistencia, asisaasistencia.vuelta,
					asisademanda.cod_demanda, asisademanda.fecharecepcion, asisademanda.fecha_peticion, asisademanda.hora_peticion, asisademanda.cod_servicio,
					serinfo.idSv, serinfo.hvuelta
				FROM servicio
					LEFT JOIN asisademanda ON servicio.coDemanda = asisademanda.cod_demanda
					LEFT JOIN asisaasistencia ON servicio.coDemanda = asisaasistencia.cod_demanda
					LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
				WHERE servicio.idSv ='$jId' AND servicio.coDemanda = '$codDemandAsisa' AND servicio.idasistencia ='$idasistencia'");
			$prueba = "SELECT asisaasistencia.idasistencia, asisaasistencia.cod_demanda, asisaasistencia.hora_asistencia, asisaasistencia.vuelta, asisademanda.fecha_peticion, asisademanda.hora_peticion
				FROM asisaasistencia 
					LEFT JOIN asisademanda ON asisaasistencia.cod_demanda = asisademanda.cod_demanda
				WHERE asisaasistencia.idasistencia = '$idasistencia'
			";
			*/
		} else {
			$Asisa24 = mysqli_query($gestambu, "SELECT servicio.idSv, servicio.coDemanda, servicio.idemanda, servicio.idasistencia,
					asisaasistencia.fecha_asistencia, asisaasistencia.hora_asistencia, asisaasistencia.vuelta,
					asisademanda.cod_demanda, asisademanda.fecharecepcion, asisademanda.fecha_peticion, asisademanda.hora_peticion, asisademanda.cod_servicio,
					serinfo.idSv, serinfo.hvuelta
				FROM servicio
					LEFT JOIN asisademanda ON servicio.coDemanda = asisademanda.cod_demanda
					LEFT JOIN asisaasistencia ON servicio.coDemanda = asisaasistencia.cod_demanda
					LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
				WHERE servicio.coDemanda = '$codDemandAsisa' AND servicio.idasistencia ='$idasistencia' AND asisaasistencia.vuelta = '$vueltaAbr'");	
		}
		
		$rwAsisa24 = mysqli_fetch_assoc($Asisa24);	
		/*
		echo "coDemanda :".$codDemandAsisa."<br />";
		echo "idasistencia :".$idasistencia."<br />";
		echo "vuelta :".$vueltaAbr."<br />";
		*/
		//Parametros obligatorios
		$colaborador  = 'AANDALUC';
		$cod_demanda  = $codDemandAsisa;
		$vuelta       = $vueltaAbr;
		$fecha_peti   = $rwAsisa24['fecha_peticion'];
		if(empty($rwAsisa24['activacion'])) {
			$hora_peti    = $rwAsisa24['hora_peticion'];	
		} else {
			$hora_peti    = $rwAsisa24['activacion'];	
		}
	
		if($jValor == "00:00") { //Valor nulo, tiene que volver a estado solicitado(2)
			$estadoSO           = "2";
			$fecha_estado       = "";
			$fecha_realizacion  = date("dmY");
			$ahoraSO            = date("Hi");
			$activacionSO       = arregloTimeStamp($fecha_peti, $hora_peti);
			$f_activacionSO     = horaDBEncamino($ahoraSO, $activacionSO, $jValor);
			if(empty($rwAsisa24['hora_asistencia'])) {
				$hora_realizacionSO	= formatHorAsisaEstado($jValor);
				$hora_estadoSO      = "";				
			} else {
				$hora_realizacionSO = $rwAsisa24['hora_asistencia'];
				$hora_estadoSO      = "";
			}
			$pendienteEvolucion = "";
			$terminacion        = '';
			$observaciones      = "";
			$volverEstado       = 1;			
		} else {
			$volverEstado = 0;
			/* Enviar en camino */
			# Envia en camino al poner hora en idReco o idFin
			//Parametros obligatorios según el caso
			if($jCampo == "idReco" || $jCampo == "vtaReco") {
				$estadoCA           = "3";
				$fecha_estado       = date("dmY");		
				$fecha_realizacion  = date("dmY");
				$ahora              = date("Y-m-d H:i:s");
				$activacionCA       = arregloTimeStamp($fecha_peti, $hora_peti);
				$f_activacionCA     = horaDBEncamino($ahora, $activacionCA, $jValor);

				if(empty($rwAsisa24['hora_asistencia'])) {
					if($jCampo == "vtaReco") {
						if($rwAsisa24['hvuelta'] !="00:00:00") {
							$hora_realizacionCA	= formatHorAsisaEstado($rwAsisa24['hvuelta']);
							$hora_estadoCA      = $f_activacionCA;	
						} else {
							$hora_realizacionCA	= formatHorAsisaEstado($jValor);
							$hora_estadoCA      = $f_activacionCA;
						}
					} else {
						$hora_realizacionCA	= formatHorAsisaEstado($jValor);
						$hora_estadoCA      = $f_activacionCA;					
					}		
				} else {
					$hora_realizacionCA = $rwAsisa24['hora_asistencia'];
					$hora_estadoCA      = $f_activacionCA;
				}
				
				$pendienteEvolucion = "";
				$terminacion        = '';
				$observaciones      = "";
				$notiEncamino = 1;
				/* Mensajes de log */
				$obsTextC = "Estado activado(3): ".$hora_estadoCA;
				guardarLog('24', $usuario, $obsTextC, $jId);
			} else {
				$notiEncamino = 0;
			}
			/* Enviar estado según horario */
			//Parametros obligatorios según el caso
			$estado             = $estAsisa;
			$fecha_estado       = date("dmY");
			$hora_estado        = formatHorAsisaEstado($jValor); //Cambiar a $jValor
			$fecha_realizacion  = date("dmY");
			
			if(empty($rwAsisa24['hora_asistencia'])) {
				$hora_realizacion	= formatHorAsisaEstado($jValor);		
			} else {
				$hora_realizacion   = $rwAsisa24['hora_asistencia'];
			}		
			
			$pendienteEvolucion = "";
			$terminacion        = '';
			$observaciones      = "";
			$diagnostico1       = "";
			$diagnostico2       = "";
			/* Mensajes de log */
			$estText = estaNotiAsisa($estAsisa);
			$obsTextD = "Estado ".$estText." :".$hora_estado;
			guardarLog('24', $usuario, $obsTextD, $jId);				
		}
		
		include '../../../API/noti_est_encamino.php'; // Notificaciones

	//}
}
?>