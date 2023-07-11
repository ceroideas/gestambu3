<?php

/* Seleccion de zona local */
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');

$link = "Minions-5.mp3";

$fecha = date("Hi");
echo $fecha;

//$audio = "<audio src='".$link."' autoplay></audio>";

//echo $audio;

/* Nueva entrada de servicio */
/*
function nuevoSer($valorHora, $estadServ) {
  $hServ    = explode(" ", $valorHora);
  $fechaTab = $hServ[0];
  $horaTab  = $hServ[1];

  //Extraer hora, minuto y segundo
  $data_c2 = explode(":",$horaTab);
  $hour_c  = $data_c2[0];
  $min_c   = $data_c2[1];

  //Resta de fechas
  $f1 = new DateTime($fechaTab);
  $f2 = new DateTime(date("Y-m-d"));
  $interval = $f1->diff($f2);

  //Variables para hora
  $r_h = $hour_c-(date("H"));
  $r_m = $min_c-(date("i"));

  if($estadServ =='1') {
  	if($interval->format('%R%a') != '+0') {
  		$dsv = "0";
  	} else {
  		if($r_h != '0') {
  			$dsv = "0";
  		}else {
  			if($r_m >= '-5' & $r_m <= '5') {
  				//intervalo entre -5 y 5
  				$dsv = "1";
  			} else {
  				$dsv = "0";
  			}
  		}
  	}
  } else {
  	$dsv = "0";
  }
  return $dsv;
}

echo nuevoSer("2017-11-03 13:24:11", "1");
*/
 ?>
