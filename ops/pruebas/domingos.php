<?php 
setlocale(LC_TIME,"es_CL"); 
$sunday_val      = 0; 
$saturday_val    = 6; 
$seconds_per_day = 86400; 
$d_weekend_days  = 0; 
/*
$p_start_date   = "2018-01-01";
$p_end_date     = "2018-12-31"; 

$start_date      = strtotime($p_start_date); 
$end_date        = strtotime($p_end_date); 

for($day_val = $start_date; $day_val <= $end_date; $day_val += $seconds_per_day){ 
    $pointer_day = date("w", $day_val); 

    if(($pointer_day == $sunday_val) || ($pointer_day == $saturday_val)){ 
        echo date("d/m/Y",$day_val)."<br>"; 
        $d_weekend_days++; 
    } 
     
}
*/
$fecha = date('2017-12-31');
//$nuevafecha = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
//$nuevafecha = date ( 'Y-m-d' , $nuevafecha );

for($i=1; $i <= 54; $i++) {
	$fecha = date('2017-12-31');
	$diaSum = "+".(7 * $i)." day";
	$nuevafecha = strtotime ( ''.$diaSum.'' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'd-m-y' , $nuevafecha );
	echo $nuevafecha."<br />";	
}

?>