<?php
/* Calculo de ida/vueta */
# servicios únicos: > 9 < 90
# servicios id/vta: > 89 < 119
# Especial UVI: 120(uvi primaria) solo ida
# Especial UVI: 130(uvi secundaria) puede ser ida y vuelta
$sqlVta = mysqli_query($gestambu, "SELECT cod_demanda, vuelta, fecha_asistencia, estado, hora_asistencia FROM asisaasistencia WHERE cod_demanda='$codeAsisa' AND vuelta='S' AND fecha_asistencia = '$fechaAsist' GROUP BY fecha_asistencia");
$rwSqlVta = mysqli_fetch_assoc($sqlVta);
$numSqlVta = mysqli_num_rows($sqlVta);
$sqlIda = mysqli_query($gestambu, "SELECT cod_demanda, vuelta, fecha_asistencia, estado, hora_asistencia FROM asisaasistencia WHERE cod_demanda='$codeAsisa' AND vuelta='N' AND fecha_asistencia = '$fechaAsist' GROUP BY fecha_asistencia");
$rwSqlIda = mysqli_fetch_assoc($sqlIda);
$numSqlIda = mysqli_num_rows($sqlIda);

if($numSqlIda == 0 && $numSqlVta == 0 ) {
  //No hay registros ni para ida ni para vuelta
  $calI_V = "";
  $estRes = "15";
} elseif($numSqlIda == 1 && $numSqlVta == 0) {   //Hay registro de ida pero no de vuelta
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 119 ) {
    if($rwSqlIda['estado'] > 5 ) { // El servicio no tiene vuelta y la ida esta anulada
      $calI_V = "2";
      $estRes = "15";
      $menObs = "";
      $hDCsta = 1; //Hora de consulta - activada o desactivada
      $mosVta = 0; // Mostrar vuelta - activado o desactivado
      $mensaLog = "Servicio creado sin vuelta y la ida anulada";
      $barraEst = "Anulada ida y sin vuelta";
    } elseif($rwSqlIda['estado'] == 5 ) {
      $calI_V = "2";
      $estRes = "14"; // Al no tener registro de vuelta se trata como servicio único lo pone como finalizado
      $menObs = "";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida finalizada y sin vuelta";
      $barraEst = "Ida finalizada y sin vuelta";
    } elseif($rwSqlIda['estado'] == 2 ) {
      $calI_V = "2";
      $estRes = "1"; // Al no tener registro de vuelta se trata como servicio y lo pone como pendiente
      $menObs = "SOLO HAY QUE HACER LA IDA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Creado sólamente la ida, no tiene registro de vuelta";
      $barraEst = "Sólo hay que hacer la ida";
    } else {
      $calI_V = "2";
      $estRes = "1";
      $menObs = "SOLO HAY QUE HACER LA IDA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Sin regsitro que comparar - estandar ida 2 estado 1";
      $barraEst = "Sólo hay que hacer la ida";
    }
  } elseif($rwAsisa['cod_servicio'] == 120 || $rwAsisa['cod_servicio'] == 130) { // Uvi primaria - no tiene vuelta
    if($rwSqlIda['estado'] > 5 ) { // El servicio no tiene vuelta y la ida esta anulada
      $calI_V = "2"; //Muestra solo check de ida
      $estRes = "15";
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Creado sin registro de vuelta con estado anulado.";
      $barraEst = "UVI Anulada";
    } elseif($rwSqlIda['estado'] == 5 ) {
      $calI_V = "2";
      $estRes = "14"; // Al no tener registro de vuelta se trata como servicio único lo pon como finalizado
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Creado sin registro de vuelta con estado finalizado.";
      $barraEst = "UVI Finalizada";
    } elseif($rwSqlIda['estado'] == 2 ) {
      $calI_V = "2";
      $estRes = "1"; // Al no tener registro de vuelta se trata como servicio único y lo pone como pendiente
      $hDCsta = 1; //Pone hora del servicio
      $mosVta = 0;
      $mensaLog = "Creado sin registro de vuelta con estado pendiente";
      $barraEst = "";
    } else {
      $calI_V = "2";
      $estRes = "1";
      $menObs = "SOLO HAY QUE HACER LA IDA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Sin regsitro que comparar - estandar ida 2 estado 1";
      $barraEst = "Sólo hay que hacer la ida";
    }
  } else { // Son servicio con sólamente con ida
    if($rwSqlIda['estado'] > 5 ) { // El servicio no tiene vuelta y la ida esta anulada
      $calI_V = ""; // No tiene que marcar ninguna casilla de ida o vuelta
      $estRes = "15";
      $menObs = "SERVICIO ANULADO";
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Servicio creado como Anulado";
      $barraEst = "Anulado";
    } elseif($rwSqlIda['estado'] == 5 ) {
      $calI_V = "";
      $estRes = "14"; // Al no tener registro de vuelta se trata como servicio único lo pon como finalizado
      $menObs = "FINALIZADO";
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Servicio creado como Finalizado";
      $barraEst = "";
    } elseif($rwSqlIda['estado'] == 2 ) {
      $calI_V = "";
      $estRes = "1"; // Al no tener registro de vuelta se trata como servicio y lo pone como pendiente
      $menObs = "";
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Creado como pendiente ida";
      $barraEst = "";
    } else {
      $calI_V = "";
      $estRes = "1";
      $menObs = "";
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Estado desconocido";
      $barraEst = "?__?";
    }
  }
} elseif($numSqlIda == 1 && $numSqlVta == 1) {
  //Hay registro de ida y de vuelta
  //Hay registro de ida pero no de vuelta
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 119 ) {
    if($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] > 5) { // Anulada ida y vuelta
      $calI_V = "1";
      $estRes = "15";
      $menObs = "SERVICIO ANULADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como Anulado";
      $barraEst = "Anulado";
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 5) { //Anulada ida y vuelta finalizada
      $calI_V = "3";
      $estRes = "14"; // Pone como solo vuelta y finalizada vuelta
      $menObs = "ANULADA IDA Y FINALIZADA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como Anulado la ida y Finalizada la vuelta";
      $barraEst = "Anulada ida y Finalizada vuelta";
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 2) {
      $calI_V = "3";
      $estRes = "1"; // Pone como solo vuelta y estado de vuelta pendiente. la ida esta anulada
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como Anulado la ida y vuelta pendiente";
      $barraEst = "Anulada ida y vuelta pendiente";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] > 5) { // La ida esta finalizada y la vuelta anulada
      $calI_V = "2";
      $estRes = "14"; // Al tener la vuelta anulada, lo pone como solo ida y lo trata como servicio único
      $menObs = "VUELTA ANULADA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado vuelta anulada e ida finalizada";
      $barraEst = "Anulada vuelta e ida finalizada";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 5) { // ida finalizada y vuelta finalizada
      $calI_V = "1";
      $estRes = "10";
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como finalizado";
      $barraEst = "Finalizado";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 2) { // ida finalizada y vuelta pendiente
      $calI_V = "1";
      $estRes = "4";
      $menObs = "PENDIENTE LA VUELTA, FINALIZADA IDA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como finalizada la ida y pendiente la vuelta";
      $barraEst = "Pendiente vuelta";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] > 5) { // ida pendeinte y vuelta anulada
      $calI_V = "2";
      $estRes = "1"; // se trata como servicio único, ya que tiene la vuelta anulada
      $menObs = "ANULADA VUELTA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como anulada la vuelta y pendiente la ida";
      $barraEst = "Pendiente ida, anulada vuelta";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 5) { // ida pendeinte y vuelta finalizada
      $calI_V = "1";
      $estRes = "1"; // No se puede poner un estado como finalizada vuelta porque no se mostraría. Es un caso raro porque se habría realizado la vuelta pero no se habría llevado al paciente a consulta.
      $menObs = "PENDIENTE IDA, FINALIZADA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como pendiente la ida y finalizada la vuelta";
      $barraEst = "Ida pendiente, vuelta finalizada";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 2) { // ida pendeinte y vuelta pendiente
      $calI_V = "1";
      $estRes = "1";
      $menObs = "";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Creado como ida y vuelta pendientes";
      $barraEst = "";
    } else { // al tratarse de servicios que tienen registro de ida y vuelta los pone pendientes
      $calI_V = "1";
      $estRes = "1";
      $menObs = "";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Sin datos para comparar. Estandar idvta 1 estado 1 ";
      $barraEst = "";
    }
  } elseif($rwAsisa['cod_servicio'] == 120 || $rwAsisa['cod_servicio'] == 130 ) { // Uvi primaria - no tiene vuelta - lo trata como ida y vuelta al tener registro de ida y de vuelta
    if($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] > 5) { // Anulada ida y vuelta
      $calI_V = "1";
      $estRes = "15";
      $menObs = "ANULADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Creado como anulada ida y vuelta";
      $barraEst = "Anulado";
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 5) { //Anulada ida y vuelta finalizada
      $calI_V = "3";
      $estRes = "10"; // Pone como solo vuelta y finalizada vuelta
      $menObs = "ANULADA IDA Y FINALIZADA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida anulada y vuelta finalizada";
      $barraEst = "Ida anulada, vuelta finalizada";
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 2) {
      $calI_V = "3";
      $estRes = "1"; // Pone como solo vuelta y estado de vuelta pendiente. la ida esta anulada
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida anulada y vuelta pendiente";
      $barraEst = "Ida anulada, vuelta pendiente";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] > 5) { // La ida esta finalizada y la vuelta anulada
      $calI_V = "2";
      $estRes = "14"; // Al tener la vuelta anulada, lo pone como solo ida y lo trata como servicio único
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida finalizada y vuelta anulada";
      $barraEst = "Ida finalizada, vuelta anulada";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 5) { // ida finalizada y vuelta finalizada
      $calI_V = "1";
      $estRes = "14";
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida finalizada y vuelta finalizada";
      $barraEst = "Ida finalizada, vuelta finalizada";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 2) { // ida finalizada y vuelta pendiente
      $calI_V = "1";
      $estRes = "4";
      $menObs = "FIN IDA, VTA. PENDIENTE";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida finalizada y vuelta pendiente";
      $barraEst = "Ida finalizada, vuelta pendiente";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] > 5) { // ida pendeinte y vuelta anulada
      $calI_V = "2";
      $estRes = "1"; // se trata como servicio único, ya que tiene la vuelta anulada
      $menObs = "SOLO HACER IDA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida pendiente y vuelta anulada";
      $barraEst = "Ida pendiente, vuelta anulada";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 5) { // ida pendeinte y vuelta finalizada
      $calI_V = "1";
      $estRes = "1"; // No se puede poner un estado como finalizada vuelta porque no se mostraría. Es un caso raro porque se habría realizado la vuelta pero no se habría llevado al paciente a consulta.
      $menObs = "PENDT. IDA, FINALIZADA VUELTA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida pendiente y vuelta finalizada";
      $barraEst = "Ida pendiente, vuelta finalizada";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 2) { // ida pendeinte y vuelta pendiente
      $calI_V = "1";
      $estRes = "1";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida pendiente y vuelta pendiente";
    } else { // al tratarse de servicios que tienen registro de ida y vuelta los pone pendientes
      $calI_V = "1";
      $estRes = "1";
      $menObs = "";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Sin datos para comparar, estandar idvta 1 estado 1";
      $barraEst = "";
    }
  } else { // servicios con ida y vuelta
    if($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] > 5) { // Anulada ida y vuelta
      $calI_V = "1";
      $estRes = "15";
      $menObs = "ANULADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Creado como anulada ida y vuelta";
      $barraEst = "Anulado";
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 5) { //Anulada ida y vuelta finalizada
      $calI_V = "3";
      $estRes = "14"; // Pone como solo vuelta y finalizada vuelta
      $menObs = "ANULADA IDA Y FINALIZADA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida anulada y vuelta finalizada";
      $barraEst = "Ida anulada, vuelta finalizada";
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 2) {
      $calI_V = "3";
      $estRes = "1"; // Pone como solo vuelta y estado de vuelta pendiente. la ida esta anulada
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida anulada y vuelta pendiente";
      $barraEst = "Ida anulada, vuelta pendiente";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] > 5) { // La ida esta finalizada y la vuelta anulada
      $calI_V = "2";
      $estRes = "14"; // Al tener la vuelta anulada, lo pone como solo ida y lo trata como servicio único
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida finalizada y vuelta anulada";
      $barraEst = "Ida finalizada, vuelta anulada";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 5) { // ida finalizada y vuelta finalizada
      $calI_V = "1";
      $estRes = "10";
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida finalizada y vuelta finalizada";
      $barraEst = "Ida finalizada, vuelta finalizada";
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 2) { // ida finalizada y vuelta pendiente
      $calI_V = "1";
      $estRes = "4";
      $menObs = "FIN IDA, VTA. PENDIENTE";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida finalizada y vuelta pendiente";
      $barraEst = "Ida finalizada, vuelta pendiente";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] > 5) { // ida pendeinte y vuelta anulada
      $calI_V = "2";
      $estRes = "1";
      $menObs = "SOLO HACER IDA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida pendiente y vuelta anulada";
      $barraEst = "Ida pendiente, vuelta anulada";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 5) { // ida pendeinte y vuelta finalizada
      $calI_V = "1";
      $estRes = "1"; // No se puede poner un estado como finalizada vuelta porque no se mostraría. Es un caso raro porque se habría realizado la vuelta pero no se habría llevado al paciente a consulta.
      $menObs = "PENDT. IDA, FINALIZADA VUELTA";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Servicio creado como ida pendiente y vuelta finalizada";
      $barraEst = "Ida pendiente, vuelta finalizada";
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 2) { // ida pendeinte y vuelta pendiente
      $calI_V = "1";
      $estRes = "1";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado como ida pendiente y vuelta pendiente";
    } else { // al tratarse de servicios que tienen registro de ida y vuelta los pone pendientes
      $calI_V = "1";
      $estRes = "1";
      $menObs = "";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Sin registro que comparar. Estandar ida y vuelta pendientes";
      $barraEst = "";	  
    }
  }
} elseif($numSqlIda == 0 && $numSqlVta == 1) {
  //No hay registro de ida pero si de vuelta
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 119 ) {
    if($rwSqlVta['estado'] > 5 ) { //Sin registro de ida y vuelta anulada
      $calI_V = "3";
      $estRes = "15";
      $menObs = "ANULADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y la vuelta anulada";
      $barraEst = "Anulada vuelta y sin ida";
    } elseif($rwSqlVta['estado'] == 5 ) { // finalizada la vuelta
      $calI_V = "3";
      $estRes = "14"; // No tiene registro de ida
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta finalizada";
      $barraEst = "Vuelta finalizada, sin ida";
    } elseif($rwSqlVta['estado'] == 2 ) {
      $calI_V = "3";
      $estRes = "1"; // Pendiente la vuelta, no hay registro de ida
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta pendiente";
      $barraEst = "Vuelta pendiente, sin ida";
    } else {
      $calI_V = "3";
      $estRes = "1";
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Sin registro para comparar. Pendiente vuelta";
      $barraEst = "Vuelta pendiente, sin ida";
    }
  } elseif($rwAsisa['cod_servicio'] == 120 || $rwAsisa['cod_servicio'] == 130 ) { //
    if($rwSqlVta['estado'] > 5 ) { // El servicio no tiene ida y la vuelta está anulada
      $calI_V = "3";
      $estRes = "15";
      $menObs = "ANULADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta anulada";
      $barraEst = "Vuelta anulada, sin ida";
    } elseif($rwSqlVta['estado'] == 5 ) {
      $calI_V = "3";
      $estRes = "14"; // al no tener registro de ida se trata como un servicio único.
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta finalizada";
      $barraEst = "Vuelta finalizada, sin ida";
    } elseif($rwSqlVta['estado'] == 2 ) {
      $calI_V = "3";
      $estRes = "1";
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta pendiente";
      $barraEst = "Vuelta pendiente, sin ida";
    } else {
      $calI_V = "3";
      $estRes = "1";
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Sin registro para comparar. Pendiente vuelta";
      $barraEst = "Vuelta pendiente, sin ida";
    }
  } else { // Son servicio con sólamente con vuelta
    if($rwSqlVta['estado'] > 5 ) { // El servicio no tiene ida, la ida esta anulada . - son servicios únicos
      $calI_V = "3";
      $estRes = "15";
      $menObs = "ANULADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta anulada";
      $barraEst = "Vuelta anulada, sin ida";
    } elseif($rwSqlVta['estado'] == 5 ) {
      $calI_V = "3";
      $estRes = "14";
      $menObs = "FINALIZADO";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta finalizada";
      $barraEst = "Vuelta finalizada, sin ida";
    } elseif($rwSqlVta['estado'] == 2 ) {
      $calI_V = "3";
      $estRes = "1";
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 1;
      $mosVta = 1;
      $mensaLog = "Servicio creado sin ida y con la vuelta pendiente";
      $barraEst = "Vuelta pendiente, sin ida";
    } else {
      $calI_V = "3";
      $estRes = "1";
      $menObs = "SOLO HACER LA VUELTA";
      $hDCsta = 0;
      $mosVta = 0;
      $mensaLog = "Sin registro para comparar. Pendiente vuelta";
      $barraEst = "Vuelta pendiente, sin ida";
    }
  }
} else {
  //Otros casos
  $calI_V = "";
  $estRes = "1";
}
//echo "Mensaje Observaciones :".$menObs."<br />";
//echo "Mensaje Log :".$mensaLog."<br />";
//echo "Mensaje Barra :".$barraEst."<br />";
?>
