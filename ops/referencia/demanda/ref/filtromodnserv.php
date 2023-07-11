<?php
/* Calculo de ida/vueta */
# servicios únicos: > 9 < 90
# servicios id/vta: > 89 < 119
# Especial UVI: 120(uvi primaria) solo ida
# Especial UVI: 130(uvi secundaria) puede ser ida y vuelta
$sqlVta     = mysqli_query($gestambu, "SELECT cod_demanda, vuelta, fecha_asistencia, estado, hora_asistencia FROM asisaasistencia WHERE cod_demanda='$codeAsisa' AND vuelta='S' AND fecha_asistencia = '$fechaAsist' GROUP BY fecha_asistencia");
$rwSqlVta   = mysqli_fetch_assoc($sqlVta);
$numSqlVta  = mysqli_num_rows($sqlVta);
$sqlIda     = mysqli_query($gestambu, "SELECT cod_demanda, vuelta, fecha_asistencia, estado, hora_asistencia FROM asisaasistencia WHERE cod_demanda='$codeAsisa' AND vuelta='N' AND fecha_asistencia = '$fechaAsist' GROUP BY fecha_asistencia");
$rwSqlIda   = mysqli_fetch_assoc($sqlIda);
$numSqlIda  = mysqli_num_rows($sqlIda);
$sqlSpecial = mysqli_query($gestambu, "SELECT * FROM especial WHERE idSv = '$idComp'");
$rwSpecial  = mysqli_fetch_assoc($sqlSpecial);

$compTabAsist = mysqli_query($gestambu,
  "SELECT servicio.idSv, servicio.coDemanda, servicio.idasistencia, servicio.estServ, servicio.fecha, servicio.hora, servicio.idvta,
  serinfo.idSv, serinfo.hconsulta, serinfo.hvuelta, serinfo.idInfo, serinfo.prioridad
  FROM servicio
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
  WHERE servicio.coDemanda='$codeAsisa' AND servicio.idasistencia='$idComp' ");
$rwCompTab = mysqli_fetch_assoc($compTabAsist);
$idsVMod   = $rwCompTab['idSv'];
$estadoTab = $rwCompTab['estServ'];
$idvtaTab  = $rwCompTab['idvta'];

if($tipAsisa > 9 && $tipAsisa < 86) {
	$sqlIda     = mysqli_query($gestambu, "SELECT cod_demanda, vuelta, fecha_asistencia, estado, hora_asistencia FROM asisaasistencia WHERE cod_demanda='$codeAsisa' AND fecha_asistencia = '$fechaAsist' GROUP BY fecha_asistencia");
	$rwSqlIda   = mysqli_fetch_assoc($sqlIda);
	$numSqlIda = 1;
	$numSqlVta = 0;
}
/* Estados de técnico */
# Muestra el estado del recuro enviado
# Si el estado del técnico es "en camino" o "en domicilio" no anularía el servicio
# Estados válidos: 7: En camino / 8: Recogiendo / 12: En destino /13: Fin de trayecto
# Eliminada variable $menObs, cada vez que se actualizara el servicio agregaría texto - suprimirla en los archivos dependientes
$estRecurso = mysqli_query($gestambu, "SELECT idSv, estTec, estTecVta FROM serestados WHERE idSv = '$idsVMod'");
$rwEsTec    = mysqli_fetch_assoc($estRecurso);

if($numSqlIda == 0 && $numSqlVta == 0 ) {
  //No hay registros ni para ida ni para vuelta
  $calI_V = "";
  $estRes = "15";
} elseif($numSqlIda == 1 && $numSqlVta == 0) {   //Hay registro de ida pero no de vuelta
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 131 ) { // Códigos para ambulancia - agregados servicios de UVI - Servicios que puenden tener ida y vuelta
    if($rwSqlIda['estado'] > 5 ) {// El servicio no tiene vuelta y la ida esta anulada
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "2";
        $estRes = "15";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "Servicio agregado como Anulada ida, sin registro de vuelta";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como id/vta. Envían sólo ida Anulada. Cambio de estado";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero el servicio se estaba realizando. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "2";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero está marcado como id/vta. Cambio de estado a sólo ida anulada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envía la ida anulada y sin vuelta. Se había realizado la ida. Se da por realizado. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía la ida anulada y sin vuelta. Se había realizado la ida. Se da por realizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como id/vta, pero envían como ida anulada y sin vuelta. Modificado a Anulado";
            $modEstado = "readonly";
            $filaColor = "warning";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio Anulado";
            $modEstado = "readonly";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero el técnico estaba de camino o había realizado el servicio. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "2";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado. Ida anulada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía la ida anulada y sin vuelta. Se había realizado el servicio. Se da por facturable. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio se había finalizado. Se da por realizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "15"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificada ida Anulada y sin vuelta";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como solo vuelta. Se envía lo contrario. Modificado sólo ida y anulado";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados - Modificado a ida Anulada. En tabla: sólo vuelta y estado marca como ida adjudicada";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio se había finalizado. Es facturable. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "15"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificada ida Anulada y sin vuelta";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "(203) Marcado en tabla como id/vta. Envían sólo ida Anulada. Cambio de estado";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "(213) Se envia como anulado, pero el servicio se estaba realizando. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "2";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "(221) Se envia como anulado, pero está marcado como id/vta. Cambio de estado a sólo ida anulada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "(231) Se envía la ida anulada y sin vuelta. Se había realizado la ida. Se da por realizado. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "(239) Se envía la ida anulada y sin vuelta. Se había realizado la ida. Se da por realizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "15"; // Anulado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "(247) Marcado como id/vta, pero envían como ida anulada y sin vuelta. Modificado a Anulado";
            $modEstado = "readonly";
            $filaColor = "warning";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 5 ) {
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "2";
        $estRes = "14";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "(258) Servicio agregado como finalizada ida, sin registro de vuelta";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como id/vta. Envían sólo ida y finalizada. No se había realizado el servicio";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizado. En tabla marcado como id/vta. Finalizada sólo ida. El técnico estaba realizado el servicio";
              $modEstado = "readonly";
              $filaColor = "warning";
            } else {
              $calI_V = "2";
              $estRes = "14"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizado solo ida y sin vuelta. Cambio de estado a sólo ida finalizada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envía la ida finalizada y sin vuelta. Cambio a sólo ida y finalizado";
              $modEstado = "readonly";
              $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como ida y vuelta, pero sólo envían finalizada la ida y sin registro de vuelta";
            $modEstado = "readonly";
            $filaColor = "warning";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como id/vta, pero envían como ida finalizada y sin vuelta. Modificado";
            $modEstado = "readonly";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio finalizado. Según el estado: Pendiente, anulado o suspenso, no se puede dar por finalizado. Se cambia a estado Anulado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizada ida y sin regsitro de vuelta, el técnico estaba de camino o había realizado el servicio. Cambio a finalizada ida";
              $modEstado = "readonly";
              $filaColor = "info";
            } else {
              $calI_V = "2";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizado. No hay registro del técnico. Se da como ida finalizada";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio finalizado";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Finaliza servicio";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado: servicio finalizado";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como solo vuelta. Se envía lo contrario. Modificado a ida finalizada";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados - Modificado a ida finalizada. En tabla: sólo vuelta y estado marca como ida adjudicada";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados de ida y vuelta. Se cambia a finalizada ida";
            $modEstado = "";
            $filaColor = "info";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado a ida finalizada y sin vuelta";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Cambio de estado: Ida finalizada y sin vuelta";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Enviado con cambio de estado: Ida finalizada y sin vuelta";
              $modEstado = "readonly";
              $filaColor = "";
            } else {
              $calI_V = "2";
              $estRes = "14"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Cambio de estado, ida finalizada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Enviado cómo finalizado";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Concuerdan los datos enviados. Finalizada ida";
            $modEstado = "";
            $filaColor = "";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envío aceptado. Finalizada ida y sin vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 2 ) {
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "2";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "Servicio agregado como pendiente ida, sin registro de vuelta";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio pendiente ida, no tiene vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              $calI_V = "2";
              $estRes = "11";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se marca como pendiente, ya estaba anteriormente adjudicado como ida adjudicada. No tiene vuelta, error en estados";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "2";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estados, no tiene vuelta. Modificado a pendiente ida";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como pendiente ida. El servicio estaba ya finalizado. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como ida pendiente. El servicio está finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "warning";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como id/vta, se modifica a ida pendiente y sin vuelta";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado a pendiente ida y sin vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "11";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estados, es un estado de ida y vuelta. Se envía pendiente pero el técnico ya esta realizado el servicio";
              $modEstado = "";
              $filaColor = "info";
            } else {
              $calI_V = "2";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estados, se modifica a pendiente.";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error de estados, pero ya se había realizado un trayecto. Finalizada ida. Se envía como pendiente ida. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio está finalizado. Envían servicio pendiente. No se puede realizar la acción. Conctactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado: servicio pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados. Se modifica en tabla. Ida pendiente y sin vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados, se envía servicio pendiente de ida, pero está marcado como fin de vuelta. Se cambia el estado pero se da por realizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados de ida y vuelta. Finalizada vuelta, se marca como servicio finalizado. No se puede realizar la acción. Contactar con Asisa.";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados, se marca como pendiente la ida y sin vuelta";
            $modEstado = "";
            $filaColor = "warning";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Aceptado cambio de estado: pendiente ida y sin vuelta";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "11";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Servicio como adjudicada ida, se queda como adjudicado. Envían pendiente";
              $modEstado = "readonly";
              $filaColor = "";
            } else {
              $calI_V = "2";
              $estRes = "14"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Cambio de estado, ida finalizada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Enviado cómo finalizado";
              $modEstado = "readonly";
              $filaColor = "info";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio está finalizado. Envían pendiente, no se puede realizar esta acción. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envío aceptado. Pendiente la ida y sin vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } else {
      $calI_V = "2";
      $estRes = "1";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Sin regsitro que comparar - estandar ida 2 estado 1";
      $barraEst = "Sólo hay que hacer la ida";
    }
} else { // Son servicio con sólamente con ida *******************
    if($rwSqlIda['estado'] > 5 ) {// El servicio no tiene vuelta y la ida esta anulada
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "";
        $estRes = "15";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "Servicio agregado como anulado. Sin ida/vta";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida (No se hace comparacion de ida y vuelta)
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como id/vta. Envían sólo ida Anulada. Cambio de estado. Sin ida/vta";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero el servicio se estaba realizando. Sin ida/vta. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero está marcado como id/vta. Cambio de estado a sólo ida anulada. Sin ida/vta";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envía la ida anulada y sin vuelta. Se había realizado la ida. Sin ida/vta. Se da por realizado. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía la ida anulada y sin vuelta. Se había realizado la ida. Se da por realizado. Sin ida/vta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "15"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como id/vta, sin ida/vta. Modificado a Anulado";
            $modEstado = "readonly";
            $filaColor = "warning";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio Anulado. Sin ida/vta";
            $modEstado = "readonly";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero el técnico estaba de camino o había realizado el servicio. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado. Sin ida/vta";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía la ida anulada y sin vuelta. Se había realizado el servicio. Se da por facturable. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio se había finalizado. Se da por realizado. Sin ida/vta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificada ida Anulada y sin vuelta";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como solo vuelta. Se envía lo contrario. Modificado sólo ida y anulado";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados - Modificado a ida Anulada. En tabla: sólo vuelta y estado marca como ida adjudicada. Se finaliza el servicio de ida. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error de estado. Han enviado anulada ida y ya estaba la vuelta finalizada. Es facturable. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificada ida Anulada y sin vuelta";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envían sólo ida Anulada. Cambio de estado a anulado";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado, pero el servicio se estaba realizando. Se marca como facturable. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "";
              $estRes = "15"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como anulado. Cambio de estado a sólo ida anulada";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "E servicio tiene finalizada la ida. Envían ida anulada. Se da por realizado. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se había realizado la ida. Se da por realizado. Facturable. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "15"; // Anulado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Estado enviado aceptado. Modificado a Anulado";
            $modEstado = "readonly";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 5 ) {
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "";
        $estRes = "14";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "Agregado como servicio único y finalizado";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como id/vta. Envían sólo ida y finalizada. No se había realizado el servicio. Estaba pendiente";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizado. En tabla marcado como id/vta. El técnico estaba realizado el servicio";
              $modEstado = "readonly";
              $filaColor = "warning";
            } else {
              $calI_V = "";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizado. Cambio a finalizado. El servicio mostraba como pendiente";
              $modEstado = "readonly";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envía la ida finalizada y sin vuelta. Cambia a finalizado";
              $modEstado = "readonly";
              $filaColor = "info";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio pendiente, envían servicio finalizado. Estado: Finalizado";
            $modEstado = "readonly";
            $filaColor = "info";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como id/vta, se envía finalizado y sin vuelta. Estado: Finalizado";
            $modEstado = "readonly";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Según el estado: Pendiente, anulado o suspenso, no se puede dar por finalizado. Se cambia a estado Anulado. No se ha llegado a hacer el servicio. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 11 ) { // Tabla adjudicado
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Servicio finalizado. Estaba adjudicado";
              $modEstado = "readonly";
              $filaColor = "info";
            } else {
              $calI_V = "";
              $estRes = "14"; // Anulado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envia como finalizado. No hay registro del técnico. Se da como ida finalizada";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio finalizado. Hay error en estado, no puede tener como estado fin de ida. Actualizado correctamente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Cambio de estado en servicio: Finalizado";
            $modEstado = "";
            $filaColor = "";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado: servicio finalizado";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado en tabla como solo vuelta. Se envía lo contrario. Error en estado. Modificado correctamente";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados - Modificado a ida finalizada. En tabla: sólo vuelta y estado marca como ida adjudicada";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados de ida y vuelta. Modificado a estado correcto";
            $modEstado = "";
            $filaColor = "info";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estado, modificado a estado correcto";
            $modEstado = "";
            $filaColor = "info";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Cambio de estado: Finalizado. El servicio no se había realizado o estaba anulado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Enviado con cambio de estado: Finalizado";
              $modEstado = "readonly";
              $filaColor = "";
            } else {
              $calI_V = "";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Cambio de estado: Finalizado";
              $modEstado = "readonly";
              $filaColor = "";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
              //El servicio se da como realizada la ida.
              $calI_V = "";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Enviado cómo finalizado";
              $modEstado = "readonly";
              $filaColor = "info";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Concuerdan los datos enviados. Finalizado";
            $modEstado = "";
            $filaColor = "";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envío aceptado. Finalizado";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 2 ) {
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "Servicio agregado como pendiente. Sin ida/vta";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio pendiente ida. Sin ida/vta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 11 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "";
            $estRes = "11";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se había adjudicado el servicio. Se mantiene estado Adjudicado. No se puede volver a poner pendiente";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados. Ya estaba finalizada una ida. Se mantiene como finalizado. Concatar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados. Servicio finalizado. No se puede modificar estado a pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "warning";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados, marcado como id/vta. Se modifica a pendiente ida";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Estados concuerdan. Cambio de estado a pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 11 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "";
            $estRes = "11";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía pendiente pero ya estaba adjudicado. Se mantiene estado adjudicado";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se había realizado un trayecto. Se modifica estado correctamente y se deja como finalizado. No es posible marcarlo como pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio está finalizado. Envían servicio pendiente. No se puede realizar la acción. Conctactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado: servicio pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados. No se puede obtener un servicio de solo vuelta. Acción incorrecta. Modificado a pendiente";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados, pero marcado con un trayecto de ida finalizado. Se modifica a finalizado. No es posible cambiar estado a pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados. No se puede marcar como pendiente, el servicio ya estaba finalizado. Contactar con Asisa.";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados, se marca como pendiente la ida y sin vuelta";
            $modEstado = "";
            $filaColor = "warning";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio cambiado  anulado, pendiente o en suspenso. Nuevo estado: Pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "";
            $estRes = "11";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio adjudicado. No puede volver a estado pendiente. Estado: Pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = " Ya está finalizado. No es posible volver a marcarlo como pendinte. Estado: Finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio está finalizado. No es posible volver a estado pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envío aceptado. Marcado como servicio pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } else {
      $calI_V = "";
      $estRes = "1";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Sin regsitro que comparar. Estandar: servicio pendiente";
      $modEstado = "";
      $filaColor = "";
    }
  }
} elseif($numSqlIda == 1 && $numSqlVta == 1) {
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 131 ) { // agregado servicios de UVI: 120 y 130
    if($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] > 5) { // Anulada ida y anulada vuelta
      if(empty($estadoTab)) {
        $calI_V = "1";
        $estRes = "15";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Servicio Agregado. Ida y vuelta Anuladas";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Servicio con ida adjudicada o finalizada. No es posible anular la ida. Se marca como ida finalizada y vuelta anulada. Conctactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "1";
              $estRes = "15";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Anulada ida y vuelta. No se estaba realizando el servicio.";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y vuelta. El servicio ya se había realizado, tanto ida como vuelta. No es posible Anular el servicio. Se da por realizado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14"; // Finalizada ida
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "Realizada la ida. Se envía anulada ida y vuelta. Sólo es posible anular la vuelta. La ida se marca como finalizada. Conctactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y vuelta. Se había realizado la vuelta. Marcado como ida anulada y vuelta finalizada. No es posible anular la vuelta. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "15";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y vuelta";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y vuelta. Se ha realizado la ida y la vuelta. No es posible anular el servicio. Se marca como finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio anulada la ida y la vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y agregada vuelta como anulada";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Servicio con ida adjudicada. No se puede anular la ida, se marca como finalizada y vuelta anulada. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "1";
              $estRes = "15";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Anulada ida y vuelta. Agregada vuelta como anulada";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Servicio realizado ida y vuelta. Solo tenía una ida. Error en estado. Modificado a ida y vuelta finalizada. No se puede marcar como Anulado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "15"; // Finalizada ida
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y vuelta. Agregada vuelta anulada";
                $modEstado = "readonly";
                $filaColor = "";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada vuelta anulada. Error en estado, se estaba realizando la vuelta. Anulada la ida y finalizada la vuelta. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "15";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada vuelta anulada. Anulado servicio";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y vuelta. Error en estados. Se da por realizada la ida y la vuelta. No se puede marcar como Anulado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada vuelta anulada. Servicio anulado";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y vuelta. Agregada ida como anulada";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estados. Agregada vuelta como anulada. La ida ya se estaba realizando. No es posible anular la ida. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "1";
              $estRes = "15";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Agregada vuelta como anulada. Error en estados. Servicio anulado";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida como anulada. El servicio ya se había realizado la ida y la vuelta. No es posible anular el servicio. Marcado como servicio finalizado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14"; // Finalizada ida
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "Agregada ida como anulada. La ida ya se estaba realizando. No es posible anular el servicio. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida anulada. Servicio con vuelta realizándose. No es posible anular la vuelta. Marcado como ida anulada y vuelta finalizada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "15";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida como anulada. Servicio anulado";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada ida como anulada. La vuelta está finalizada. No es posible anular. Marcado como ida y vuelta finalizadas. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada ida como anulada. Servicio anulado";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado el tipo de servio. Anulada ida y vuelta";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estados. Agregada vuelta como anulada. La ida ya se estaba realizando. No es posible anular la ida. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "1";
              $estRes = "15";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Agregada vuelta como anulada. Error en estados. Servicio anulado";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida como anulada. El servicio ya se había realizado la ida y la vuelta. No es posible anular el servicio. Marcado como servicio finalizado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14"; // Finalizada ida
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "Agregada ida como anulada. La ida ya se estaba realizando. No es posible anular el servicio. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida anulada. Servicio con vuelta realizándose. No es posible anular la vuelta. Marcado como ida anulada y vuelta finalizada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "15";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida como anulada. Servicio anulado";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se da ida como finalizada. No es posible anular el servicio. Finalizada ida y vuelta. Conctactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estado. Ida y vuelta Anuladas";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 5) { // anulada la ida y finalizada la vuelta
      if(empty($estadoTab)) {
        $calI_V = "3";
        $estRes = "14";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida anulada y finalizada la vuelta";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y finalizada vuelta. No se había realizado la vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Enviada, anulada ida y finalizada vuelta. El servicio estaba con la ida realizandose. No es posible anular la ida. Servicio finalizado. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "3";
              $estRes = "15";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Enviado como: anulada ida y finalizada vuelta";
              $modEstado = "";
              $filaColor = "";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada la ida, pero se ha realizado tanto la ida como la vuelta. No es posible anular la ida. Conctactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14"; // Finalizada ida
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y finalizada vuelta. Se ha realizado al contrario. Modificado servicio a ida anulada y vuelta finalizada";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y vuelta finalizada";
                $modEstado = "";
                $filaColor = "";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Anulada ida y vuelta finalizada. No se ha realizado la vuelta";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida, pero el servicio estaba como finalizado ida y vuelta. No es posible anular la ida. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y finalizada vuelta. Cambio de estado";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada vuelta como finalizada. No se ha realizado la vuelta el servicio estaba como pendiente ida. Conctactar con asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "3";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Error en estados, no la ida está anulada pero se está realizando y vuelta está anulada y se ha enviado como finalizada. Marcado como ida anulado y vuelta finalizada. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "3";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No realizada ni ida ni vuelta. Marcado como ida anulada y vuelta finalizada";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Realizada ida y vuelta. No es posible anular la ida. Marcado como finalizado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14"; // Finalizada vta
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Realizada ida pero no la vuelta. Se modifica a ida anulada y vuelta finalizada.";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Cambio correcto. Anulada ida y finalizada vuelta";
                $modEstado = "readonly";
                $filaColor = "";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar vuelta. Cambiado a anulada ida y finalizada vuelta";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio finalizado. No se puede anular ida. Marcado como ida y vuelta finalizadas. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y finalizada vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con estados sin realizar o anulados. Se marca como asisa envía el servicio pero no se ha realizado la vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "3";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estados. Servicio con ida adjudicada. Se estaba realizando la ida. Se modifica a ida anulada y vuelta finalizada";
              $modEstado = "readonly";
              $filaColor = "warning";
            } else {
              $calI_V = "3";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Modificado a ida anulada y vuelta finalizada. No es posible comprobar el estado del técnico";
              $modEstado = "readonly";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la ida y la vuelta. Error en estados. No es posible anular la ida. Servicio finalizado. Conctactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14"; // Finalizada ida
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estados. Cambio en estados de ida. Se finaliza la vuelta y se anula la ida";
                $modEstado = "readonly";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Cambio realizado correctamente. Ida anulada y vuelta finalizada";
                $modEstado = "";
                $filaColor = "";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible comprobar estado de técnico. Marcado como ida anulada y vuelta finalizada";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio finalizado. No es posible anular la ida. Marcado como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado a anulada ida y finalizada vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No estaba contempado como servicio de ida y vuelta. Anulada la ida, pero no se ha realizado la vuelta. No se puede dar por realizada. Marcado como Anulado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "3";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Cambiado a servicio de ida y vuelta. Se modifica a ida anulada y vuelta finalizada";
              $modEstado = "readonly";
              $filaColor = "warning";
            } else {
              $calI_V = "3";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Cambio a servicio de id/vta. No se puede comprobar con técnico. Era un servicio con ida adjudicada. Marcado como ida anulada y vuelta finalizada";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estado, no era un servicio de id/vta pero tiene la ida y la vuelta realizadas. No se puede anular la ida. Servicio finalizado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14"; // Finalizada vta
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "La ida estaba realizada y la vuelta marcada como anulada. Se modifica a anulada la ida y finalizada la vuelta";
                $modEstado = "readonly";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estado pero concuerda con lo enviado por la compañía. Se mantiene como ida anulada y vuelta finalizada";
                $modEstado = "readonly";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar la vuelta con el técnico. Se mantiene como ida anulada y vuelta finalizada";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estados. Se ha finalizado el servicio, tanto ida como vuelta. Se mantiene como finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estado. Se ha agregado un trayecto. Se da como anulada ida y finalizada vuelta";
            $modEstado = "";
            $filaColor = "warning";
          }
        }
      }
    } elseif($rwSqlIda['estado'] > 5 && $rwSqlVta['estado'] == 2) {
      if(empty($estadoTab)) {
        $calI_V = "3";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida anulada y pendiente la vuelta";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Cambio a anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "4"; //vta pendiente
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Se modifica anulada la ida y pendiente la vuelta. El servicio estaba adjudicado y el ténico iba en camino. Se mantiene con ida finalizada y vuelta pendiente. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "3";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Modificado a: Anulada ida y Pendiente vuelta";
              $modEstado = "";
              $filaColor = "";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible modificar el servicio a anulada ida y pendiente vuelta. El servicio consta como finalizada ida y vuelta. Se mantiene como finalizado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la ida, no es posible comprobar vuelta. Se marca como Ida finalizada y pendiente la vuelta. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se está realizado la vuelta. No es posible modificarla a pendiente. Se mantiene como vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar la realización de la vuelta. Se modifica a ida anulada y vuelta pendiente";
                $modEstado = "readonly";
                $filaColor = "";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio realizado ida y vuelta. No es posible modificar la ida a anulada ni cambiar estado de vuelta a pendiente. Se mantiene como finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada vuelta pendiente. Se modifica a anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Agregada vuelta pendiente. No se puede anular la ida porque ya se está realizando. Se modifica a finalizada ida y pendiente vuelta. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "3";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No es posible comprobar el estado del técnico. Se marca como: Anulada ida y pendiente vuelta";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // vuelta adjudicada
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible anular la ida y poner estado de vuelta como pendiente. El servicio se está realizando. Se marca como finalizada ida y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Realizada la ida, no es posible confirmar la vuelta con el técnico. No se puede anular la ida. Marcado como ida finalizada y vuelta pendiente. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la vuelta, no es posible anular la vuelta y poner pendiente la vuelta. Se marca como anulada ida y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible comprobar vuelta. Se marca como ida anulada y pendiente vuelta";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se ha realizado la ida y la vuelta. No es posible anular la ida y poner pendiente la vuelta. Contactar con Asisa. El servicio está finalizado";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Error en estados. Ya se está realizando la ida. Servicio modificado a ida finalizada y vuelta pendiente. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "3";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No es posible confirmar estado de técnico. Se modifica a ida Anulada y vuelta pendiente";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la ida y la vuelta. Error en estados. No es posible anular la ida. Servicio finalizado. Conctactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Modificada a ida anulada y pendiente vuelta";
                $modEstado = "";
                $filaColor = "info";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la vuelta, no es posible ponerla como pendiente. Servicio marcado como anulada ida y adjudicada vuelta. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible comprobar estado de técnico. Cambiado a anulada ida y pendiente vuelta";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio finalizado. No es posible anular la ida y poner pendiente la vuelta. Se marca como servicio finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No estaba contempado como servicio de ida y vuelta. Agregada vuelta como pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Se está realizando la ida, no es posible anularla. Se marca como ida finalizada y vuelta pendiente. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "3";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Cambio a servicio de id/vta. No se puede comprobar con técnico. Modificado a anulada ida y pendiente vuelta";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede anular la ida ni marcar pendiente la vuelta. La ida ya se ha realizado. Se modifica a finalizada ida y adjudicada vuelta. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "14"; // Finalizada vta
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "La ida estaba realizada, no se puede comprobar estado de ténico a la vuelta. Se marca como ida finalizada y vuelta pendiente. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible poner la vuelta como pendiente, ya se está realizado. Se marca como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar la vuelta con el técnico. Se marca como ida anulada y vuelta pendiente";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con ida y vuelta finalizadas. No es posible anular la ida y poner pendiente la vuelta. El servicio se mantiene como finalizado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estado. Se ha agregado un trayecto. Anulada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "info";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] > 5) { // ida finalizada y vuelta anulada
      if(empty($estadoTab)) {
        $calI_V = "2";
        $estRes = "14";
        $hDCsta = 1;
        $mosVta = 0;
        $mensaLog = "Agregado como ida finalizada y vuelta anulada";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Cambio a ida finalizada y vuelta anulada. No se ha realizado la ida";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Anulada vuelta y finalizada ida";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible anular la vuelta, ya se está realizando. Marcado como ida y vuelta finalizadas. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "No es posible confirmar la vuelta con el técnico. Se marca como ida finalizada y vuelta anulada";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se ha realizado la ida pero se esta realizando la vuelta. Se marca como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "No es posible confirmar la ida. Se marca como ida finalizada y vuelta anulada";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se ha realizado ida y vuelta. Se marca como servicio finalizado ida y vuelta. No es posible anular la vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Finalizada ida y anulada vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Finalizada ida y anulada vuelta. Pero no se había asignado ningún recurso. Se marca como finalizada ida y anulada vuelta";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Anulada la vuelta, ida finalizada.";
              $modEstado = "";
              $filaColor = "info";
            } else {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No es posible comprobar el estado del técnico. Se marca como: finalizada ida y anulada vuelta";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // vuelta adjudicada
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Enviado como finalizada ida y vuelta anulada. Se está realizando la vuelta. Se marca como finalizada ida y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "Modificado como ida finalizada y anulada vuelta";
                $modEstado = "";
                $filaColor = "info";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Enviado como ida finalizada y anulada vuelta, pero no se había realizado la ida y se estaba realizando la vuelta. Se marca como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "15";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se ha realizado la ida y no es posible comprobar la vuelta. Marcado como servicio anulado. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio finalizado. No es posible anular la vuelta. Se marca como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Finalizada ida y anulada vuelta";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada ida como finalizada, pero el servicio no se estaba realizando o estaba anulado. No se puede marcar ida como finalizada. Servicio Anulado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Finalizada ida y anulada vuelta";
              $modEstado = "";
              $filaColor = "info";
            } else {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "No es posible confirmar estado de técnico. Se modifica a finalizada ida y anulada vuelta";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Ida y vuelta realizadas. No es posible marcar la vuelta como anulada. Servicio modificado a finalizada la ida y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "readonly";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Ida finalizada y vuelta anulada";
                $modEstado = "";
                $filaColor = "info";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se ha realizado la ida y es posible anular la vuelta. No se puede marcar la ida como finalizada. Marcado como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se ha realizado la ida y no se puede comprobar la realización de la vuelta. Se marca como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Finalizado. No es posible anular la vuelta. Se marca como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Finalizada ida y anulada vuelta";
            $modEstado = "";
            $filaColor = "info";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplada ida y vuelta. Pero no es posible finalizar la ida porque no se ha realizado. Marcado como servicio Anulado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No contemplado como ida y vuelta. Finalizada la ida y anulada la vuelta";
              $modEstado = "";
              $filaColor = "info";
            } else {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Cambio a servicio de id/vta. No se puede comprobar con técnico. Se modifica a finalizada ida y anulada vuelta";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // fin de vuelta (estado para ida/vta para finalizar)
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede marcar la vuelta como finalizada, se está realizando. Servicio marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5"; // Finalizada vta
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar el estado del técnico. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se ha realizado la ida y la vuelta se está realizando. Se ha enviado finalizada ida y anulada vuelta. Se marca como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "3";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar la vuelta con el técnico. No se ha realizado la ida. Marcado como ida anulada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible anular la vuelta porque el servicio ya está finalizado. Se marca como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Finalizada ida y anulada vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 5) {
      if(empty($estadoTab)) {
        $calI_V = "1";
        $estRes = "14";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida finalizada y vuelta finalizada";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible finalizar ida y vuelta porque no se ha realizado el servicio. Marcado como ida y vuelta anuladas. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "2";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "No es posible finalizar la vuelta porque no se ha realizado. Marcado como ida finalizada y anulada vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Servicio finalizado ida y vuelta";
                $modEstado = "";
                $filaColor = "info";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar la vuelta con el técnico. Se marca finalizada ida y vuelta";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar con estado de técnico. Finalizado ida y vuelta";
                $modEstado = "";
                $filaColor = "info";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de técnico. Finalizada ida y vuelta";
                $modEstado = "";
                $filaColor = "info";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio finalizado";
            $modEstado = "";
            $filaColor = "";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Finalizada ida y vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible finalizar ida y vuelta porque el servicio no se ha realizado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "No se ha realizado la vuelta. No es posible finalizar la vuelta. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            } else {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "No se ha realizado la vuelta. No es posible darla como finalizada. Guardado como ida finalizada y vuelta anulada. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10"; // vuelta adjudicada
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Finalizada la ida y la vuelta. Se ha agregado la vuelta finalizada";
                $modEstado = "";
                $filaColor = "";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar el estado del ténico de vuelta. Servicio finalizado. Se ha agregado la vuelta finalizada";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar la ida. Marcado como finalizado ida y vuelta. Se ha agregado la vuelta finalizada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar ida o vuelta. Servicio como finalizada ida y vuelta.Se ha agregado la vuelta finalizada";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se finaliza ida y vuelta";
            $modEstado = "";
            $filaColor = "";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Finalizado ida y vuelta. Se ha agregado la vuelta finalizada";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible dar el servicio por finalizado. La vuelta está pendiente. Se agrega la ida finalizada. Se marca como anulado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida (el servicio pasa a ser de ida y vuelta pero no está contemplado como tal)
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Finalizada ida y vuelta - Se agregó la ida como finalizada. No es posible confirmar que se haya realizado";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Finalizada ida y vuelta - Se agregó la ida como finalizada. No es posible confirmar que se haya realizado";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Servicio finalizado. El servicio no constaba de ida.";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirma estado de técnico. Finalizado";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirmar la realización de ida. Servicio finalizada ida y vuelta";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Sin regitro de realización por parte del técnico. Finalizada ida y vuelta";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Finalizada ida y vuelta";
            $modEstado = "";
            $filaColor = "";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Ida y vuelta finalizadas";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplada ida y vuelta. No es posible finalizar la ida y la vuelta. No se ha realizado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "10";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No contemplado como ida y vuelta. Finalizada ida y vuelta. No se había enviado registro de vuelta. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            } else {
              $calI_V = "1";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No se puede confirmar la realización por parte del ténico. No hay registro de ida y vuelta anterior. Servicio finalizado. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Finalizada ida y vuelta. No había registro de vuelta anterior";
                $modEstado = "";
                $filaColor = "info";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Finalizada ida y vuelta. No es posible comprobar registro de técnico. No hay registro anterior de vuelta";
                $modEstado = "";
                $filaColor = "info";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Sin registro anterior de vuelta. No se puede confirmar la realización de la ida. Finalizada ida y vuelta. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "10";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar la realización del técnico. Sin registro anterior de vuelta. Finalizada ida y vuelta. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplado como servicio de ida y vuelta. Está finalizado ambos. Finalizada ida y vuelta";
            $modEstado = "";
            $filaColor = "warning";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Sin registro de ser servicio de ida y vuelta. Modificado a ida y vuelta finalizadas";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 5 && $rwSqlVta['estado'] == 2) {
      if(empty($estadoTab)) {
        $calI_V = "1";
        $estRes = "4";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida finalizada y vuelta pendiente";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible finalizar la ida porque no se ha realizado, el servicio estaba como pendiente o anulado. Se cambia estado a anulada ida y pendiente vuelta. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado a finalizada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede marcar la vuelta como pendiente, ya se está realizando. Se marca como ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar la vuelta con el técnico. Se marca ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar el estado de ida del técnico. Finalizado ida y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de técnico ni de ida ni de vuelta. Marcado como ida finalizada y vuelta pendiente";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No se puede marcar la vuelta como pendiente, ya se ha realizado. Servicio maracado como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Ida finalizada y vuelta pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada vuelta como pendiente. La ida está finalizada, pero no se ha realizado. Marcado como ida anulada y vuelta pendiente. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Finalizada ida y vuelta pendiente";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // vuelta adjudicada
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la ida y la vuelta se está realizando. No se puede poner como pendiente. Se marca como ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de vuelta del técnico. Servicio con vuelta agregada. Se marca como pendiente la vuelta e ida finalizada";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprobar el estado de ida. Servicio con vuelta agregada. Se modifica a finalizada ida y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada la vuelta, servicio marcado como finalizada la ida y pendiente la vuelta";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "El servicio consta como finalizado. No es posible poner la vuelta como pendiente. Marcado como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Finalizada ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible dar la ida como finalizada, no se ha realizado. El servicio está pendiente la vuelta. Se agrega ida anulada y vuelta pendiente. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida (el servicio pasa a ser de ida y vuelta pero no está contemplado como tal)
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se agrega ida finalizada. Error en estado. Modificado a ida finalizada y pendiente vuelta";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "No se puede comparar estado de técnico. Se agrega ida finalizada. Modificado a ida finalizada y vuelta pendiente";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se está realizando la vuelta o está finalizada. Se modifica a ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirma estado de técnico. Se modifica a ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida finalizada. Se modifica el servicio a ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Sin regitro de realización por parte del técnico. Finalizada ida y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible marcar la vuelta como pendiente. El servicio ya se ha finalizado. Marcado como ida y vuelta finalizadas. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada ida como finalizada. La vuelta está pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplada ida y vuelta. No es posible agregar ida como finalizada porque no se ha realizado el servicio. Marcado como ida anulada y vuelta pendiente. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No contemplado como ida y vuelta. Finalizada ida y vuelta como pendiente";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No se puede confirmar la realización por parte del ténico. No hay registro de ida y vuelta anterior. Servicio con ida finalizada y vuelta pendiente";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede marcar la vuelta como pendiente, ya se está realizando. Marcado como ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprar los registros de técnico para ida y vuelta. Se marca como ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No hay registro para comparar de ida por parte del ténico. Se mantiene como ida finalizada y vuelta adjudicada";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar la realización del técnico. Sin registro anterior de vuelta. Finalizada ida y vuelta como pendiente";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplado como servicio de ida y vuelta. Se agrega ida y vuelta, pero el servicio consta como finalizado. No es posible ponerlo como pendiente. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Sin registro de ser servicio de ida y vuelta. Modificado a ida finalizada y vuelta pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] > 5) {
      if(empty($estadoTab)) {
        $calI_V = "2";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida pendiente y vuelta anulada";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Cambio a ida pendiente y anulada la vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "2";
            $estRes = "11";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado con ida pendiente, pero ya está adjudicada. Marcado como ida adjudicada y anulada la vuelta";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "Se ha realizado la ida, no se puede marcar como pendiente y la vuelta está adjudicada. Marcado como finalizada ida y anulada vuelta. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "11";
                $hDCsta = 1;
                $mosVta = 0;
                $mensaLog = "No es posible confirmar estado de técnico. La ida ya se está realizando no es posible marcarla como pendiente. Marcado como adjudicada ida y anulada vuelta";
                $modEstado = "";
                $filaColor = "warning";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se está realizando la vuelta, no se puede confirmar ida. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de técnico ni de ida ni de vuelta. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No se puede marcar como ida pendiente y vuelta anulada. El servicio esta finalizado tanto la ida como la vuelta. Marcado como finalizado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como ida pendiente y anulada vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Estados modificados correctamente. Ida pendiente y anulada vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "2";
            $estRes = "11";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados. Es un estado de ida y vuelta. La ida está marcada. Se modifica a ida adjudicada y vuelta anulada";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // vuelta adjudicada
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "La ida está finalizada y se está realizando la vuelta. Error en estados, no es un estado de ida y vuelta. Se marca como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de vuelta del técnico. Servicio con ida finalizada, se marca ida finalizada y vuelta adjudica. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirmar estado de técnico de ida, pero la vuelta está adjucada. Se marca como ida finalizada y vuelta anulada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "2";
                $estRes = "14";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirmar con estado de técnico pero la ida estaba realizada. Se marca como finalizada ida y vuelta anulada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estados, pero el servicio estaba como finalizado. No se puede poner pendiente la ida ni la vuelta. Marcado como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Pendiente ida y agregada anulada vuelta";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Agregada ida pendiente y anulada vuelta";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida (el servicio pasa a ser de ida y vuelta pero no está contemplado como tal)
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "11";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estado, estado de ida y vuelta. Agregado ida como pendiente pero ya se estaba realizando la vuelta. Modificado a ida adjudicada y anulada vuelta. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            } else {
              $calI_V = "2";
              $estRes = "11";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "No se puede comparar estado de técnico. Agregada ida anulada. Error en estados, modificado a ida adjudicada y vuelta anulada";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estados, servicio de solo vuelta, se agrega la ida como pendiente. Ya se estaba realizando la vuelta y la ida está finalizada. Se marca como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Ida realizada, no se puede marcar como pendiente. Se guarda como ida finalizada y vuelta pendiente. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida como pendiente y vta anulada. Ya se estaba realizando la vuelta. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Sin regitro de realización por parte del técnico. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "warning";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible marcar la ida como pendiente y anulada vuelta. El servicio estaba realizado. Error en estados. Se marca como finalizado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Agregada ida como pendiente y anulada la vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "No contemplada ida y vuelta. Cambiado a ida pendiente y anulada vuelta";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "2";
              $estRes = "11";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Error en estado. El servicio se estaba realizando. Marcado como ida adjudicada y anulada vuelta";
              $modEstado = "";
              $filaColor = "warning";
            } else {
              $calI_V = "2";
              $estRes = "1";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "No es un servicio de ida/vta modificado a ida pendiente y anulada vuelta";
              $modEstado = "";
              $filaColor = "info";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estados. Se estaba realizando el servicio de vuelta y la ida estaba finalizada. Marcado como finalizada ida y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "4";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprar los registros de técnico para ida y vuelta. Marcado como ida finalizada y vuelta pendiente. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Sin confirmación de ida por el técnico. Se está realizando la vuelta. No se puede poner ida como pendiente. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar la realización del técnico. Error en estados. Marcado como ida finalizada y vuelta adjudicada. No es posible marcar como pendiente la ida. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplado como servicio de ida y vuelta. Se agrega ida y vuelta, pero el servicio consta como finalizado. No es posible ponerlo como pendiente y vuelta anulada. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "2";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Sin registro de ser servicio de ida y vuelta. Modificado a ida pendiente y anulada vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 5) {//Caso extraño de ida pendiente y finalizada vuelta (no se puede marcar la vuelta como finalizado e ida pendiente) - se marca como vuelta anulada
      if(empty($estadoTab)) {
        $calI_V = "1";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida pendiente y vuelta finalizada";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
          $calI_V = "2";
          $estRes = "1";
          $hDCsta = 1;
          $mosVta = 0;
          $mensaLog = "Vuelta finalizada e ida pendiente. Se marca como vuelta anulada, no se puede reflejar esa convinación";
          $modEstado = "";
          $filaColor = "";
        } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
          $calI_V = "2";
          $estRes = "11";
          $hDCsta = 1;
          $mosVta = 0;
          $mensaLog = "Adjudicada ida, anulada vuelta (finalizada)";
          $modEstado = "";
          $filaColor = "warning";
        } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
          if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
            if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
              $calI_V = "1";
              $estRes = "10";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Finalizada ida, no se puede marcar como pendiente. Servicio finalizado. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            } else { // vuelta no realizada
              $calI_V = "1";
              $estRes = "10";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Finalizada ida, no se puede marcar como pendiente. Servicio finalizado. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            }
          } else {
            if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
              $calI_V = "1";
              $estRes = "10";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Realizándose la vuelta. Servicio finalizado, no se puede marcar ida como pendiente. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            } else { // vuelta no realizada
              $calI_V = "1";
              $estRes = "10";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No se puede marcar finalizada ida y pendiente vuelta. Ya se estaba realizando la vuelta. Marcado como finalizado. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            }
          }
        } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
          $calI_V = "1";
          $estRes = "10";
          $hDCsta = 1;
          $mosVta = 1;
          $mensaLog = "Servicio finalizado ida y vuelta. No se puede marcar ida como pendiente. Contactar con Asisa";
          $modEstado = "";
          $filaColor = "danger";
        } else { // se da por válido lo enviado por Asisa
          $calI_V = "1";
          $estRes = "10";
          $hDCsta = 1;
          $mosVta = 1;
          $mensaLog = "Finalizado, no se puede marcar ida como pendiente. Contactar con Asisa";
          $modEstado = "";
          $filaColor = "";
        }
      }
    } elseif($rwSqlIda['estado'] == 2 && $rwSqlVta['estado'] == 2) {//Ida pendiente y vuelta pendiente
      if(empty($estadoTab)) {
        $calI_V = "1";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida pendiente y vuelta pendiente";
        $modEstado = "";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1) {
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Cambio a ida pendiente y vuelta pendiente";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "1";
            $estRes = "2";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado a ida pendiente y vuelta pendiente. Ya estaba la ida adjudica. Marcado como ida adjudicada y vuelta pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Enviado como ida y vuelta pendiente. La ida se ha finalizado y la vuelta está adjudicada. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se ha realizado la ida y la vuelta está adjudicada. No se puede confirmar estado de técnico. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Se está realizando la vuelta, no se puede confirmar ida. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de técnico ni de ida ni de vuelta. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No se puede poner la ida y la vuelta como pendientes. El servicio está finalizado. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Marcado como ida y vuelta pendientes";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2) { // El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada vuelta como pendiente. Servicio cambiado a ida y vuelta pendientes";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "1";
            $estRes = "2";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Agregada ida como pendiente. Error en estado. Se modifica a ida adjudicada y vuelta pendiente";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5"; // vuelta adjudicada
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estado. Agregada ida como pendiente. Se ha finalizado la ida y la vuelta está adjudicada. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No es posible confirmar estado de vuelta del técnico. Servicio con ida finalizada, se marca ida finalizada y vuelta adjudica. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirmar estado de técnico de ida, pero la vuelta está adjucada. Se marca como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede confirmar con estado de técnico. Ida realizada. Se marca como finalizada ida y vuelta adjucada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estado. Se agrega la vuelta como pendinte pero el servicio ya estaba finalizado la ida y la vuelta. Marcado como finalizada ida y vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 11 ) { // Adjudicado
            $calI_V = "1";
            $estRes = "2";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se agrega un trayecto. Marcado como pediente y se queda adjudicada la ida";
            $modEstado = "";
            $filaColor = "";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada vuelta como pendiente. Marcado como ida y vuelta pendientes";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 3) {// El servicio en tabla sólo tenía un trayecto
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada ida pendiente y marcada vuelta como pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida (el servicio pasa a ser de ida y vuelta pero no está contemplado como tal)
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) {
              $calI_V = "1";
              $estRes = "2";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Error en estado, estado de ida y vuelta. Agregada ida como adjudicada, pero se estaba realizadon la ida. Marcado como ida adjudica y vuelta pendiente";
              $modEstado = "";
              $filaColor = "waning";
            } else {
              $calI_V = "1";
              $estRes = "2";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "Agregada ida, error en estado. El estado en tabla es de ida cuando en la ficha se marca como vuelta. Se modifica a ida adjudica y vuelta pendiente";
              $modEstado = "";
              $filaColor = "warning";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estados. Registros de ida completos en un servicio de solo vuelta. Se agrega ida, se marca como ida finalizada y vuelta adjudica. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estado. Registros erroneos en vuelta. Se marca como ida finalizada y vuelta adjudica. Se envió como pendiente ida y vuelta. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Vuelta realizándondose. Agrega ida como pendiente. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Agregada ida como pendiente. Ya se estaba haciendo la vuelta. Marcada como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error de estado. Es un estado de id/vta en un servicio de solo vuelta. Servicio marcado como finalizada ida y vuelta. No se puede poner como pendiente. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio marcado como ida y vueta pendientes";
            $modEstado = "";
            $filaColor = "";
          }
        } else {//NULL ida y vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // pendiente - anulado - suspenso
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No contemplada ida y vuelta. Cambiado a ida y vuelta pedientes";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // adj ida - comprobar si ya se estaba realizado la ida
            $calI_V = "1";
            $estRes = "2";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Agregada ida y vuelta en servicio que no lo contempla. Ida realizándose. Marcado como ida adjudicada y vuelta pendiente";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // estados en los que ya se ha realizado el trayecto de ida
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { // ida y vuelta ya realizadas por los tecnicos
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error de estado, no se puede tener registro de téncico en vuelta en un servicio que no lo contempla. Marcado como ida finalizada y vuelta adjudicada. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "5";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "No se puede comprar los registros de técnico para ida y vuelta. Marcado como ida finalizada y vuelta pendiente. Contactar con Asisa";
                $modEstado = "";
                $filaColor = "danger";
              }
            } else {
              if(($rwEsTec['estVta'] > 6 && $rwEsTec['estVta'] < 9) || ($rwEsTec['estVta'] > 11 && $rwEsTec['estVta'] < 14)) { //ida no realizada, vuelta realizada
                $calI_V = "1";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estado, no puede haber registros de vuelta en un servicio que no contempla la vuelta. Marcado como ida y vuelta pendiente";
                $modEstado = "";
                $filaColor = "warning";
              } else { // vuelta no realizada
                $calI_V = "1";
                $estRes = "1";
                $hDCsta = 1;
                $mosVta = 1;
                $mensaLog = "Error en estado. Marcado como ida y vuelta pendientes. No puede haber estados de servicio y vuelta marcados en un servicio que no lo contempla";
                $modEstado = "";
                $filaColor = "danger";
              }
            }
          } elseif($estadoTab == 10 ) { // fin de vuelta - con ida y vuelta = 1 - se da por valida la ida finalizada
            $calI_V = "1";
            $estRes = "10";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estado. Está finalizado. No se puede modificar a pendiente ida y vuelta. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // se da por válido lo enviado por Asisa
            $calI_V = "1";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Sin registro de ser servicio de ida y vuelta. Modificado a ida pendiente y pendiente vuelta";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } else {
      if(empty($estadoTab)) {
        $calI_V = "1";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como ida pendiente y vuelta pendiente. Se ha enviado un estado no contemplado para el filtro(recurso activado o recuroso en origen). Contactar con Asisa";
        $modEstado = "";
        $filaColor = "danger";
        $nuevoAgr = 1;
      } else {
        // Cuando asisa envía otros estados; 3: Recurso Activado / 4 Recurso en el origen - se mantiene el estado que tiene en tabla
        $calI_V = $idvtaTab;
        $estRes = $estadoTab;
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Se ha enviado un estado no contemplado para el filtro (recurso activado o recuroso en origen). Se mantiene el guardado en tabla. Contactar con Asisa";
        $modEstado = "";
        $filaColor = "danger";
      }
    }
  } else { // código - del 10 al 100 (incluidos inyectables  y visitas médicas)
    //Servicios que no puede tener registro de ida y vuelta - se está enviando registro de ida y vuelta
    $calI_V = $idvtaTab;
    $estRes = $estadoTab;
    $hDCsta = 1;
    $mosVta = 1;
    $mensaLog = "No se puede agregar registro de vuelta para los códigos del 10 al 90. Se mantiene los estados de tabla. No modificado Contactar con Asisa. ";
    $modEstado = "";
    $filaColor = "danger";
  }
} elseif($numSqlIda == 0 && $numSqlVta == 1) {   //No hay registro de ida pero si de vuelta
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 131 ) { // Códigos para ambulancia - agregados servicios de UVI - Servicios que puenden tener ida y vuelta
    if($rwSqlVta['estado'] > 5 ) {// Servicio sin ida y vuelta anulada
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "3";
        $estRes = "15";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como solo vuelta y anulada";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//Servicio marcado como ida y vuelta - enviado solo vuelta y anulada
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Marcado en tabla como id/vta. Cambio de estado a sin ida y vuelta anulada";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envían servicio sin ida, pero se ha realizado la ida y anulado la vuelta. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Envían servicio sin ida. En tabla está como ida y vuelta. Se ha finalizado la ida. Marcado como ida finalizada y anulada vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía servicio sin ida en un servicio con ida y vuelta. Se ha finalizado la ida y anulada la vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio sin ida y vuelta anulada";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio estaba marcado como sólo ida. Se envía modificación de sin ida y vuelta anulada";
            $modEstado = "readonly";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modifican a servicio sin ida y vuelta anulada. El servicio se encontraba como ida adjudicada. Se finaliza ida y se anula la vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estado, pero se ha finalizado la ida. Se envía modificación de servicio sin ida y vuelta anulada. Se marca como finalizada ida y anulada vuelta. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio con ida finalizada. Han modificado el servicio a sin ida y con vuelta anulada. No es posible realizar la acción. Se mantiene como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Cambio de estado de solo ida a: servicio sin ida y vuelta anulada";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Modificado a: servicio sin ida y vuelta anulada";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados - Se ha finalizado la ida. Se envía como servicio sin ida. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con vuelta finalizada. No es posible anularla. Se mantiene vuelta finalizada. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio sin ida y vuelta anulada";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Marcado como servicio sin ida y vuelta anulada";
            $modEstado = "readonly";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía anulada la vuelta y sin ida. El servicio tenía la ida como adjudicada. Marcado como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Enviado servicio sin ida. Pero la ida ya se ha realizado. Marcado como servicio con ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio finalizado. No se puede anular la vuelta. Marcado como ida finalizado y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "15"; // Anulado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio sin ida ni vuelt";
            $modEstado = "readonly";
            $filaColor = "warning";
          }
        }
      }
    } elseif($rwSqlVta['estado'] == 5 ) { // vuelta finalizada
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "3";
        $estRes = "14";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Agregado como vuelta finalizada y sin ida";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "1";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "En tabla como ida y vuelta. Modifican a servicio sin ida y vuelta finalizada. No es posible finalizar la vuelta porque no se ha realizado. Servicio anulado. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Se envía como anulada ida y vuelta finalizada. El servicio estaba con ida adjudicada. Se marca como Finalizada ida y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio con ida finalizada. No se puede marcar como servicio sin ida y vuelta finalizada. Guardado como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio con ida finalizada. No se puede marcar como servicio sin ida. Guardado como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Marcado como id/vta,envían servicio sin ida y vuelta finalizada";
            $modEstado = "readonly";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Enviado como servicio sin ida y vuelta finalizada. No se ha realizado la vuelta. El servicio se da como anulada la vuelta. Contactar con ASisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "2";
              $estRes = "14"; // Finalizado
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "Se envía como servicio sin ida y vuelta finalizada. Ya estaba realizándose la ida. No se puede modificar. Guardado como ida finalizada y vuelta anulada. Contactar con Asisa";
              $modEstado = "readonly";
              $filaColor = "danger";
            } else {
              $calI_V = "2";
              $estRes = "14";
              $hDCsta = 1;
              $mosVta = 0;
              $mensaLog = "El servicio tenía ida adjudicada. No se puede modificar a servicio sin ida. Se finalizada la ida y se anula la vuelta. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            //El servicio se da como realizada la ida.
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "El servicio está finalizado en la ida. No se puede guardar como servicio sin ida y vuelta finalizada. Se marca como finalizada ida y vuelta anulada. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "2";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Servicio en tabla como solo ida. Está finalizado. No se puede modificar a solo vuelta finalizada. Guardado como ida finalizada y vuelta anulada. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado a sólo vuelta y finalizada";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No se ha realizado el servicio de vuelta. No se puede finalizar la vuelta. Marcado como vuelta anulada. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 0;
            $mensaLog = "Error en estados - Modificado a servicio de solo vuelta y finalizada";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Los estados concuerdan. Guardado como servicio solo vuelta y finalizada";
            $modEstado = "";
            $filaColor = "";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado a solo vuelta y finalizada";
            $modEstado = "readonly";
            $filaColor = "";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "15";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicios sin ida y vuelta. Marcado como solo vuelta, pero no se puede finalizar la vuelta porque no se ha realizado. Anulada vuelta. Contactar con ASisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Adjudicada ida. Servicio cambiado a servicio sin vuelta y finalizada la vuelta";
            $modEstado = "readonly";
            $filaColor = "";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con ida finalizada. Se ha enviado como servicio sin vuelta. Guardado como servicio sin vuelta y cambiada la ida por la vuelta a finalizada";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio sin ida y vuelta. Finalizado. Se modifica a solo vuelta y finalizado";
            $modEstado = "";
            $filaColor = "warning";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modficiado a servicio solo vuelta y finalizado";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } elseif($rwSqlVta['estado'] == 2 ) {
      if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
        $calI_V = "3";
        $estRes = "1";
        $hDCsta = 1;
        $mosVta = 1;
        $mensaLog = "Servicio agregado como vuelta pendiente y sin ida";
        $modEstado = "readonly";
        $filaColor = "";
        $nuevoAgr = 1;
      } else {
        if($idvtaTab == 1 ) {//En la tabla marcado como ida y vuelta. Pero el servicio esta marcado como servicio de solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Modificado a servicio solo vuelta y pendiente";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se estaba realizando la ida, no es posible guardar el servicio como sin ida. Se marca como ida finalizada y pendiente vuelta. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se ha finalizado la ida, no se puede marcar como servicio sin ida. Guardado como ida finalizada y vuelta pendiente. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error de estados. El servicio estaba cómo finalizado. No se puede guardar como servicio sin ida. Marcado como ida finalizada y pendiente vuelta. Contactar con Asia";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Marcado como servicio con vuelta pendiente y sin ida";
            $modEstado = "";
            $filaColor = "info";
          }
        } elseif($idvtaTab == 2 ) {//En tabla marcado como solo ida
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio como solo ida y anulada. Se envía sólo vuelta y pendiente. Se modifica a sólo vuelta y pendiente";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            if(($rwEsTec['estTec'] > 6 && $rwEsTec['estTec'] < 9) || ($rwEsTec['estTec'] > 11 && $rwEsTec['estTec'] < 14)) { // En camino - recogiendo - en destino - fin trayecto
              //En tabla de técnico ya se estaba realizando - no se puede anular
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "No se puede guardar como servicio sin ida. La ida ya se está realizando. Se marca servicio como finalizada ida y pendiente vuelta. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            } else {
              $calI_V = "1";
              $estRes = "4";
              $hDCsta = 1;
              $mosVta = 1;
              $mensaLog = "La ida está adjudicada. No se puede cambiar a servicio sin ida. Modificado a servicio finalizada la ida y pendiente la vuelta. Contactar con Asisa";
              $modEstado = "";
              $filaColor = "danger";
            }
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "1";
            $estRes = "4";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con ida finalizada. No se puede guardar como servicio sin ida. Marcado como ida finalizada y vuelta pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "1";
            $estRes = "4"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con ida finalizada. No se puede marcar como servicio sin ida. Guardado como ida finalizada y vuelta pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Guardado como servicio sin ida y vuelta pendiente";
            $modEstado = "";
            $filaColor = "";
          }
        } elseif($idvtaTab == 3 ) {//En tabla marcado como solo vuelta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Se modifica a vuelta pendiente y servicio sin ida";
            $modEstado = "";
            $filaColor = "";
          } elseif($estadoTab > 1 && $estadoTab < 6 ) { // ida adj - fin ida - vta pdt - vta adj
            $calI_V = "3";
            $estRes = "1"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Error en estados, el estado de tabla corresponde a un servicio de ida y vuelta. Se marca como anulada la ida y pendiente vuelta";
            $modEstado = "";
            $filaColor = "warning";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio con vuelta finalizada, no es posible modificarla a vuelta pendiente. Se guarda como servicio sin ida y vuelta finalizada. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Guardado como servicio pendiente de vuelta y sin ida";
            $modEstado = "";
            $filaColor = "info";
          }
        } else { // servicio marcado como NULL id/vta
          if($estadoTab == 1 || ($estadoTab > 14 && $estadoTab < 17)) { // Tabla: Pendiente, Anulado, Suspenso
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Aceptado cambio de estado: modificado a servicio con vuelta pendiente";
            $modEstado = "";
            $filaColor = "info";
          } elseif($estadoTab == 2 ) { // Tabla ida adjudicada - Necesita comprobación con la tabla de técnicos
            $calI_V = "3";
            $estRes = "11";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio que no contempla ida y vuelta. Se marca como servicio sin ida, pero ya está adjudicado. Guardado como vuelta adjudicada";
            $modEstado = "readonly";
            $filaColor = "warning";
          } elseif($estadoTab > 2 && $estadoTab < 6) { // fin ida - vta pdt - vta adj
            $calI_V = "3";
            $estRes = "14"; // Finalizado
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "No es posible marca pendiente la vuelta. Ya se había finalizado. Se guarda como servicio con vuelta finalizada y sin ida. Contactar con Asisa";
            $modEstado = "";
            $filaColor = "danger";
          } elseif($estadoTab == 14 ) { // Servicio finalizado
            $calI_V = "3";
            $estRes = "14";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Servicio finalizado. No es posible guardar la vuelta pendiente. Contactar con Asisa";
            $modEstado = "readonly";
            $filaColor = "danger";
          } else { // En otro caso se acepta lo enviado por asisa
            $calI_V = "3";
            $estRes = "1";
            $hDCsta = 1;
            $mosVta = 1;
            $mensaLog = "Envío aceptado. Pendiente vuelta y servicio sin ida";
            $modEstado = "";
            $filaColor = "";
          }
        }
      }
    } else {
      $calI_V = "3";
      $estRes = "1";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "Sin regsitro que comparar - estandar vuelta 3 estado 1";
      $barraEst = "Sólo hay que hacer la vuelta";
    }
  } else { // Son servicio con sólamente con ida *******************
    if(empty($estadoTab)) { // El servicio no está creado - es un agregado - no hace comprobación de estados en tabla
      $calI_V = "";
      $estRes = "15";
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "No se puede marcar como vuelta los servicios como medicina general o ambulancias de urgencias. Creado como único. Contactar con Asisa";
      $modEstado = "";
      $filaColor = "danger";
      $nuevoAgr = 1;
    } else {
      $calI_V = $idvtaTab;
      $estRes = $estadoTab;
      $hDCsta = 1;
      $mosVta = 0;
      $mensaLog = "No es posible de enviar este tipo de servicios como servicios sin ida. Se mantiene el estado en el que están actualmente. Contactar con Asisa para una correcta modificación";
      $modEstado = "";
      $filaColor = "danger";
    }
  }
} else {
  //Otros casos
  if($rwAsisa['cod_servicio'] > 89 && $rwAsisa['cod_servicio'] < 131 ) {
    $calI_V = "1";
    $estRes = "1";
    $hDCsta = 1;
    $mosVta = 0;
    $mensaLog = "Sin regsitro que comparar. Estandar: servicio pendiente idvta";
    $modEstado = "";
    $filaColor = "";
  } else {
    $calI_V = "";
    $estRes = "1";
    $hDCsta = 1;
    $mosVta = 0;
    $mensaLog = "Sin regsitro que comparar. Estandar: servicio pendiente único";
    $modEstado = "";
    $filaColor = "";
  }
}

/* Comprobar cambios en horario o en estado */
if(cModiDos($estRes, $esTabla) == 0) {
  //No hay cambios de estado
  $textEsta = "";
} else {
  //Hay cambios de estado
  if(isset($barraEst)) {
    $textEsta = $barraEst;
  } else {
    $textEsta = "Cambio de estado en el servicio.";
  }
}

/* Otras comprobaciones */
if($rwAsisa['tipo_servicio'] == 'P') {
  //Servicio programado
  if(cModiDos(muestraFechAsisa($rwAst['fecha_asistencia']), $rwCompDem['fecha']) == 1) {
    $menCompF = "Fecha modificada por Asisa24h de ".$rwCompDem['fecha']." a ".muestraFechAsisa($rwAst['fecha_asistencia']);
  } else {
    $menCompF = "";
  }
  if(cModiDos(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwHora['hconsulta']) == 1){
    $menCompH = "Hora modificada por Asisa24h de ".$rwHora['hconsulta']." a ".muestraHorAsisa($rwAst['hora_asistencia']).":00";
  } else {
    $menCompH = "";
  }
} elseif($rwAsisa['tipo_servicio'] == 'U') {
  //Servicio urgente
  if(cModiDos(muestraFechAsisa($rwAst['fecha_asistencia']), $rwCompDem['fecha']) == 1) {
    $menCompF = "Fecha modificada por Asisa24h de ".$rwCompDem['fecha']." a ".muestraFechAsisa($rwAst['fecha_asistencia']);
  } else {
    $menCompF = "";
  }
  if(cModiDos(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwCompDem['hora']) == 1){
    $menCompH = "Hora modificada por Asisa24h de ".$rwCompDem['hora']." a ".muestraHorAsisa($rwAst['hora_asistencia']).":00";
  } else {
    $menCompH = "";
  }
}

/* Comprobar estado de servicio */
if($compEst == '6') {
 $barraEstado = "danger";
 $textEstado  = "Cancelado servicio";
 $textBoton   = "Cancelar servicio";
 $colorBoton  = "danger";
 $estadoServ  = "15";
} elseif($compEst == '7') {
 $barraEstado = "danger";
 $textEstado  = "Anulado servicio";
 $textBoton   = "Anular servicio";
 $colorBoton  = "danger";
 $estadoServ  = "15";
} else {
 $barraEstado = "info";
 $textEstado  = "Modificar Servicio";
 $textBoton   = "Modificar servicio";
 $colorBoton  = "success";
 $estadoServ  = $rwCompDem['estServ'];
}

/* Modificación en recogida o destino */
if(cModiDos($rwAsisa['direccion_origen'], $rwCompDem['recoger']) == 1 ) {
  $modiRecoger = "Dirección de recogida modificada.";
} else {
  $modiRecoger = "";
}
if(cModiDos($rwAsisa['poblacion_origen_nombre'], $rwCompDem['locRec']) == 1) {
  $modLocRec = "Localidad de recogida modificada.";
} else {
  $modLocRec = "";
}
if(empty($rwAsisa['direccion_destino']) OR empty($rwCompDem['trasladar'])) {
   $moDestino = "";
 } else {
   if(cModiDos($rwAsisa['direccion_destino'], $rwCompDem['trasladar']) == 1) {
     $moDestino = "Dirección de destino modificada.";
   } else {
     $moDestino = "";
   }
 }
if(empty($rwAsisa['poblacion_destino_nombre']) OR empty($rwCompDem['locTras'])) {
  $modLocTRas = "";
} else {
  if(cModiDos($rwAsisa['poblacion_destino_nombre'], $rwCompDem['locTras']) == 1) {
    $modLocTRas = "Localidad de destino modificada.";
  } else {
    $modLocTRas = "";
  }
}
/* Modificación en prioridad */
if($rwAsisa['prioridad'] != $rwCompTab['prioridad']) {
  $textPrio = "Prioridad modificada a: ".$rwAsisa['prioridad'];
} else {
  $textPrio = "";
}
/* Notificaciones tabla especial */
# Sólamente son válidas para ambulancia - no para servicios de médico o enfermero y tampoco para UVI
# Códigos desde 70 a 115
if($rwAsisa['cod_servicio'] > 69 && $rwAsisa['cod_servicio'] < 116) {
  # Oxígeno
if($rwAsisa['amb_oxigeno'] == 'S') {
  if($rwSpecial['ox'] == 1) {
    //No hay modificación
    $textOx = "";
  } else {
    //Modificado - piden oxígeno
    $textOx = "Modifican a requisito oxígeno";
  }
} elseif($rwAsisa['amb_oxigeno'] == 'N') {
  if($rwSpecial['ox'] == 1) {
    //Modificado - quitan requisito oxígeno
    $textOx = "Eliminan el requisito oxígeno";
  } else {
    //No hay modificación
    $textOx = "";
  }
} else {
  if($rwSpecial['ox'] == 1) {
    //Modificado - quitan requisito oxígeno
    $textOx = "Eliminan el requisito oxígeno";
  } else {
    //No hay modificación
    $textOx = "";
  }
}
# Rampa
if($rwAsisa['amb_rampa'] == 'S') {
  if($rwSpecial['rampa'] == 1) {
    //No hay modificación
    $textRamp = "";
  } else {
    //Modificado - piden oxígeno
    $textRamp = "Modifican a requisito rampa";
  }
} elseif($rwAsisa['amb_rampa'] == 'N') {
  if($rwSpecial['rampa'] == 1) {
    //Modificado - quitan requisito oxígeno
    $textRamp = "Eliminan el requisito rampa";
  } else {
    //No hay modificación
    $textRamp = "";
  }
} else {
  if($rwSpecial['rampa'] == 1) {
    //Modificado - quitan requisito oxígeno
    $textRamp = "Eliminan el requisito rampa";
  } else {
    //No hay modificación
    $textRamp = "";
  }
}
# Dos técnicos
if($rwAsisa['amb_dostecnicos'] == 'S') {
  if($rwSpecial['dTec'] == 1) {
    //No hay modificación
    $textDos = "";
  } else {
    //Modificado - piden oxígeno
    $textDos = "Modifican a requisito dos técnicos";
  }
} elseif($rwAsisa['amb_dostecnicos'] == 'N') {
  if($rwSpecial['dTec'] == 1) {
    //Modificado - quitan requisito oxígeno
    $textDos = "Eliminan el requisito dos técnicos";
  } else {
    //No hay modificación
    $textDos = "";
  }
} else {
  if($rwSpecial['dTec'] == 1) {
    //Modificado - quitan requisito oxígeno
    $textDos = "Eliminan el requisito dos técnicos";
  } else {
    //No hay modificación
    $textDos = "";
  }
}
# Enfermero
if($rwAsisa['amb_enfermeria'] == 'S') {
  if($rwCompDem['enfermero'] == 1) {
      //No hay modificación
      $textDue = "";
    } else {
      //Modificado - piden oxígeno
      $textDue = "Modifican a requisito enfermero";
    }
  } elseif($rwAsisa['amb_enfermeria'] == 'N') {
    if($rwCompDem['enfermero'] == 1) {
      //Modificado - quitan requisito oxígeno
      $textDue = "Eliminan el requisito enfermero";
    } else {
      //No hay modificación
      $textDue = "";
    }
  } else {
    if($rwCompDem['enfermero'] == 1) {
      //Modificado - quitan requisito oxígeno
      $textDue = "Eliminan el requisito enfermero";
    } else {
      //No hay modificación
      $textDue = "";
    }
  }
  # Médico
  if($rwAsisa['amb_medico'] == 'S') {
    if($rwCompDem['medico'] == 1) {
      //No hay modificación
      $textMed = "";
    } else {
      //Modificado - piden oxígeno
      $textMed = "Modifican a requisito médico";
    }
  } elseif($rwAsisa['amb_medico'] == 'N') {
    if($rwCompDem['medico'] == 1) {
      //Modificado - quitan requisito oxígeno
      $textMed = "Eliminan el requisito médico";
    } else {
      //No hay modificación
      $textMed = "";
    }
  } else {
    if($rwCompDem['medico'] == 1) {
      //Modificado - quitan requisito oxígeno
      $textMed = "Eliminan el requisito médico";
    } else {
      //No hay modificación
      $textMed = "";
    }
  }
} else {
  $textOx   = "";
  $textRamp = "";
  $textDos  = "";
  $textDue  = "";
  $textMed  = "";
}

/* Mensajes log finales */
if($estadoTab == $estRes) {
  $mensaLog = "";
} else {
  $mensaLog = $mensaLog;
}

if(empty($modiRecoger) && empty($modLocRec) && empty($moDestino) && empty($modLocTRas)) { // Sin cambio en direccion
  if(empty($mensaLog) && empty($menCompF) && empty($menCompH)) { // Sin modificar estado o fecha
    $menLog = "0##0"; //No guarda nada
  } else { // sin cambio en direccion y cambio en estado
    $menLog = $mensaLog." ".$menCompF." ".$menCompH."##"."0";
  }
} else { // cambio en direccion
  if(empty($mensaLog) && empty($menCompF) && empty($menCompH)) { // cambio en direccion pero sin cambio en esado
    $menLog = "##".$modiRecoger." ".$modLocRec." ".$moDestino." ".$modLocTRas;
  } else { // cambio en ambos
    $menLog = $mensaLog." ".$menCompF." ".$menCompH."##".$modiRecoger." ".$modLocRec." ".$moDestino." ".$modLocTRas;
  }
}
/*
if(empty($modiRecoger) && empty($modLocRec) && empty($moDestino) && empty($modLocTRas)) {
  $menLog = $mensaLog."|".$menCompF."|".$menCompH."##"."Sin cambios en dirección.";
} else {
  $menLog = $mensaLog."|".$menCompF."|".$menCompH."##".$modiRecoger."|".$modLocRec."|".$moDestino."|".$modLocTRas;
}
*/
if(empty($textOx) && empty($textRamp) && empty($textDos) && empty($textDue) && empty($textPrio)) {
  $menSpecial = "0";
} else {
  $menSpecial = "Cambio:".$textOx." ".$textRamp." ".$textDos." ".$textDue." ".$textMed." ".$textPrio;
}
//echo $menLog;
//echo $menSpecial;
//echo $mensaLog;
?>
