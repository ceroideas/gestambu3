<?php
if($rwFilt2['nuevo'] == 0 ) {
//
} else {
  /* Valores y texto para modificar idas y vueltas */
  if($serIdvta == 1) {
    //Cuando hay mas de un registro
    $compIdVta = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, estado, vuelta FROM asisaasistencia WHERE cod_demanda ='$codeAsisa' AND fecha_asistencia='$fechAst'");
    while($rwCIV = mysqli_fetch_assoc($compIdVta)) {
      if($rwCIV['estado'] > 5 && $rwCIV['vuelta'] == 'N') {
        $textInfo2= "Ida Anulada, sólo hay que hacerle la vuelta";
        $anularIda= 1;
        guardarLog('14', '1', $textInfo, $idComp);
      } elseif($rwCIV['estado'] > 5 && $rwCIV['vuelta'] == 'S') {
        $textInfo2= "Vuelta Anulada, sólo hay que hacerle la ida";
        $anularVta= 1;
      } elseif($rwCIV['estado'] == 5 && $rwCIV['vuelta'] == 'S') {
        $textInfo2= "Vuelta finalizada";
        $finVta   = 1;
      } elseif($rwCIV['estado'] == 5 && $rwCIV['vuelta'] == 'N') {
        $textInfo2= "Ida finalizada";
        $finIda   = 1;
      } elseif($rwCIV['estado'] == 2 && $rwCIV['vuelta'] == 'N') {
        $textInfo2= "Ida activada";
        $idAct    = 1;
      } elseif($rwCIV['estado'] == 2 && $rwCIV['vuelta'] == 'S') {
        $textInfo2= "Vuelta activada";
        $vtAct   = 1;
      }

      if(@$anularIda == 1 && @$anularVta == 1) {
        $textInfo = "SERVICIO ANULADO POR ASISA IDA Y VUELTA.";
        $autEstado = '15';
      }
      if(@$finVta == 1 && @$finIda == 1) {
        $textInfo = "SERVICIO FINALIZADO";
        $autEstado= '10';
      }
      if(@$idAct == 1 && @$vtAct == 1) {
        $textInfo = "";
        $autEstado= '1';
      }
    }
  } else {
    //Comparar estados
    if($rwAst['estado'] > 5 ) {
      if($rwCompDem['estServ'] == '15') {
        $autEstado= '15';
      } elseif($rwCompDem['estServ'] == '14') {
        $textInfo = "Anulado por Asisa pero servicio realizado. Contactar con Asisa para modificarlo. Han de dar nueva autorización.";
        $autEstado= '15';
        guardarLog('14', $usuario, $textInfo, $idComp);
      } elseif($rwCompDem['estServ'] < '8' && $rwCompDem['estServ'] !='1') {
        $textInfo = "Anulado por Asisa pero el recurso ya estaba en camino.";
        $autEstado= '14';
        guardarLog('14', $usuario, $textInfo, $idComp);
      } elseif($rwCompDem['estServ'] == '1') {
        $textInfo = "Servicio anulado.";
        $autEstado= '15';
        guardarLog('12', '1', $textInfo, $idComp);
      }
    } elseif($rwAst['estado'] == 5) {
      if($textInfo['estServ'] == '15') {
        $textInfo = "Servicio está como anulado, pero Asisa lo da por finalizado";
        $autEstado= '14';
        guardarLog('12', $usuario, $textInfo, $idComp);
      } elseif($rwCompDem['estServ'] == '14') {
        $autEstado= '14';
      } elseif($rwCompDem['estServ'] < '8' && $rwCompDem['estServ'] !='1') {
        $autEstado= "14";
      } elseif($rwCompDem['estServ'] == '1') {
        $textInfo = "Servicio está como pediente, pero Asisa lo da por finalizado";
        $autEstado= '14';
        guardarLog('12', $usuario, $textInfo, $idComp);
      }
    } elseif($rwAst['estado'] == 2) {
      if($rwCompDem['estServ'] == '15') {
        $textInfo = "Servicio anulado, vuelto a pedir ".date("H:i");
        $autEstado= '15';
        guardarLog('12', $usuario, $textInfo, $idComp);
      } elseif($rwCompDem['estServ'] == '14') {
        $textInfo = "Servicio finalizado, vuelto a activar por Asisa. No se puede modificar el estado. Contactar con Asisa para modificarlo. Han de dar nueva autorización.";
        $autEstado= '15';
        guardarLog('14', $usuario, $textInfo, $idComp);
      } elseif($rwCompDem['estServ'] < '8') {
        $autEstado= $rwCompDem['estServ'];
      } elseif($rwCompDem['estServ'] == '1') {
        $autEstado= $rwCompDem['estServ'];
      }
    }
  }
}
 // Fin comprobaciones si el servicio esta guardado o no -> tabla asisademanda['nuevo'] = '0'

 /* Otras comprobaciones */
 if($rwAsisa['tipo_servicio'] == 'P') {
   //Servicio programado
   if(cModiDos(muestraFechAsisa($rwAst['fecha_asistencia']), $rwCompDem['fecha']) == 1) {
     $menComp = "Fecha modificada por Asisa24h de ".$rwCompDem['fecha']." a ".muestraFechAsisa($rwAst['fecha_asistencia']);
     guardarLog('17', $usuario, $menComp , $idComp);
   }
   if(cModiDos(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwHora['hconsulta']) == 1){
     $menComp = "Hora modificada por Asisa24h de ".$rwHora['hconsulta']." a ".muestraHorAsisa($rwAst['hora_asistencia']).":00";
     guardarLog('11', $usuario, $menComp , $idComp);
   }
 } elseif($rwAsisa['tipo_servicio'] == 'U') {
   //Servicio urgente
   if(cModiDos(muestraFechAsisa($rwAst['fecha_asistencia']), $rwCompDem['fecha']) == 1) {
     $menComp = "Fecha modificada por Asisa24h de ".$rwCompDem['fecha']." a ".muestraFechAsisa($rwAst['fecha_asistencia']);
     guardarLog('17', $usuario, $menComp , $idComp);
   }
   if(cModiDos(muestraHorAsisa($rwAst['hora_asistencia']).":00", $rwCompDem['hora']) == 1){
     $menComp = "Hora modificada por Asisa24h de ".$rwCompDem['hora']." a ".muestraHorAsisa($rwAst['hora_asistencia']).":00";
     guardarLog('11', $usuario, $menComp , $idComp);
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
 ?>
