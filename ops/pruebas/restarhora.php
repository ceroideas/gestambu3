<?php
/* Seleccion de zona local */
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');

/* ACTIVAR ERRORES */
error_reporting(E_ALL);

ini_set('display_errors', '1');
error_reporting(-1);
error_reporting(0);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$ahora = date("Y-m-d H:i:s");
$activacion = date("2018-03-14 08:02:00");
$valorHora = "09:45";

function horaDBEncamino($ahora, $activacion, $valorHora) {
	//Compara las fechas para saber si el servicio es hoy
	$actAsisa  = explode(" ", $activacion);
	$fechAsisa = $actAsisa[0];
	$horAsisa  = $actAsisa[1];
	$sepAhora  = explode(" ", $ahora);
	$fechAhora = $sepAhora[0];
	$horAhora  = $sepAhora[1];

	$horaDada = $valorHora.":00";
	$horaRest = date($horaDada);
	$nuevaHora = strtotime ( '-32 minute' , strtotime ( $horaRest ) ) ;
	$nuevaHora = date ( 'H:i:s' , $nuevaHora );

	# Si la hora resultante es menor a la activación se ha de sumar 2 min a la hora de activación
	$hAsisaSum = date($horAsisa);
	$hasisafor = strtotime ( '+2 minute' , strtotime ( $hAsisaSum ) ) ;
	$hasisafor = date ( 'H:i:s' , $hasisafor );
	
	if($fechAsisa == $fechAhora) {
		$fechaHoy = 1;
	} else {
		$fechaHoy = 0;
	}

	//echo "hora dada -32min ".$nuevaHora."<br />";
	//echo "Hora de activacion ".$horAsisa."<br />";
		
	//Comprueba el horario de activación si el día es igual
	if($fechaHoy == 1) {
		//Comprueba si la hora resultante es menor que la hora de activación
		if($nuevaHora < $horAsisa) {
			//Comprueba la hora de suma + 2 y el valor dado
			if($hasisafor > $horaDada) {
				# Si la hora resultante es > la hora de encamino sera la hora de activación
				$horAsisa = explode(":", $horAsisa);
				$horaEstado = $horAsisa[0].$horAsisa[1];
				return $horaEstado;
			} else {
				$hasisafor = explode(":", $hasisafor);
				$horaEstado = $hasisafor[0].$hasisafor[1];
				return $horaEstado;
			}			
		} else {
			$nuevaHora = explode(":", $nuevaHora);
			$horaEstado = $nuevaHora[0].$nuevaHora[1];
			return $horaEstado;
		}			
	} else {
		//Cuando la fecha es mayor o menor, no hace las comprobaciones
		$nuevaHora = explode(":", $nuevaHora);
		$horaEstado = $nuevaHora[0].$nuevaHora[1];
		return $horaEstado;
	}	
}

echo horaDBEncamino($ahora, $activacion, $valorHora);


/*

function horaDBEncamino($EstadoEncamino) {
	$fecha = date($EstadoEncamino);
	$nuevafecha = strtotime ( '-32 minute' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Hi' , $nuevafecha );
	
	echo $nuevafecha;
}
$hora = "16:25:00";
horaDBEncamino($hora);



function manualConHAsisa($horaoper, $horasisa) {
	$horAgr  = $horaoper.":00";
	$fechaOp = date($horAgr);
	//Formatear hora de DB Asisa
	$hora = substr($horasisa, 0,2);
    $min  = substr($horasisa, 2,2);
	$horaasisaFor = $hora.":".$min.":00";
	//Restar hora
	$horaRest = strtotime('-30 minute' , strtotime ($fechaOp));
	$horaRest = date('H:i:s', $horaRest);
	
	if($horaasisaFor > $horaRest){
		$horaFinal = strtotime('+2 minute' , strtotime ($horaasisaFor));
	} else {
		$horaFinal = strtotime('-30 minute' , strtotime ($fechaOp));
	}
	$nuevafecha = date('Hi', $horaFinal);
	echo $nuevafecha;
}
$horaoper = "11:52";
$horasisa = "1148";

manualConHAsisa($horaoper, $horasisa);

$horaoperaciones = "13:45:00";
$horadbasisa = "1300";
$pruebahora = date("H:i");

function horaManualEncamino($fechaDada) {
	$fechAgr    = $fechaDada.":00";
	$fecha      = date($fechAgr);
	$nuevafecha = strtotime ( '-30 minute' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Hi' , $nuevafecha );
	 
	echo $nuevafecha;
}

function horaDBEncamino($EstadoEncamino) {
	$fecha = date($EstadoEncamino);
	$nuevafecha = strtotime ( '-32 minute' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Hi' , $nuevafecha );
	
	echo $nuevafecha;
}

horaManualEncamino($pruebahora);


*/


/*
function manualConHAsisa($horaoper, $horasisa) {
	$fechaOp = date($horaoper);
	//Formatear hora de DB Asisa
	$hora = substr($horasisa, 0,2);
    $min  = substr($horasisa, 2,2);
	$horaasisaFor = $hora.":".$min.":00";
	//Restar hora
	$horaRest = strtotime('-30 minute' , strtotime ($fechaOp));
	$horaRest = date('H:i:s', $horaRest);
	
	if($horaasisaFor > $horaRest){
		$horaFinal = strtotime('+2 minute' , strtotime ($horaasisaFor));
	} else {
		$horaFinal = strtotime('-30 minute' , strtotime ($fechaOp));
	}
	$nuevafecha = date('H:i:s', $horaFinal);
	echo $nuevafecha;
}

manualConHAsisa($horaoperaciones, $horadbasisa);


function horaManualEncamino($fechaDada) {
	$fecha = date($fechaDada);
	$nuevafecha = strtotime ( '-30 minute' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Hi' , $nuevafecha );
	 
	echo $nuevafecha;
}


$final = horaManualEncamino($fecha);
echo $final;
*/

?>