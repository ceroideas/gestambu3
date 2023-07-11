<?php
/* Filtro para comparar */
$codigoFiltro = $rwSesion['cod_demanda'];
$ideAsist     = $rwSesion['idasistencia'];
$fechaAsist   = $rwSesion['fecha_asistencia'];

$compTabAsist = mysqli_query($gestambu,
  "SELECT servicio.idSv, servicio.coDemanda, servicio.idasistencia, servicio.estServ, servicio.fecha, servicio.hora,
  serinfo.idSv, serinfo.hconsulta, serinfo.hvuelta, serinfo.idInfo
  FROM servicio
    LEFT JOIN serinfo ON servicio.idSv = serinfo.idSv
  WHERE servicio.coDemanda='$codigoFiltro' AND servicio.idasistencia='$ideAsist' ");
$rwCompTab = mysqli_fetch_assoc($compTabAsist);
$idsVMod   = $rwCompTab['idSv'];
$estadoTab = $rwCompTab['estServ'];

/* Mostrar hora de vuelta */
$asisaVuelta = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado
  FROM asisaasistencia
  WHERE cod_demanda='$codigoFiltro' AND fecha_asistencia='$fechaAsist' AND vuelta='S'
");
$numVuelta = mysqli_num_rows($asisaVuelta);
$rwAsisaVt = mysqli_fetch_assoc($asisaVuelta);
# $rwSesion -> para las idas: vuelta='N'
$numIda    = mysqli_num_rows($colSesion);

/* Mostrar modificación de horas de vuelta */
$horaDAsisa = $rwAsisaVt['hora_asistencia'];
$horaDTabla = $rwCompTab['hvuelta'];
$estIda = $rwSesion['estado'];
$estVta = $rwAsisaVt['estado'];

/* comprobación para notificaciones */
# comprobamos si el servicio es de ida y vuelta
if($serIdvta == 1 ) {
# Servicio de ida y vuelta
# Comprobar estados de servicio
  if($estIda > 5 && $estVta > 5 ) { // Anulada ida y anulada vta
    if($estadoTab == 1) {   //Estado pendiente en la tabla servicios
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; //Deja el servicio como anulado
    } elseif($estadoTab == 2) { //ida adj o ida fin
      $textDesp  = "Servicio anulado, pero ya se estaba realizando la ida.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; //Deja el servicio como anulado
    } elseif($estadoTab == 3) {
      $textDesp  = "Servicio anulado, pero ya se ha finalizado la ida. Contactar con Asisa para modificarlo.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; //Tiene que cambiar el servicio a ingreso y quitar ida/vta
    } elseif($estadoTab == 4) {
      $textDesp  = "Servicio anulado, pero ya se ha finalizado la ida. Contactar con Asisa para modificarlo.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; //Tiene que cambiar el servicio a ingreso y quitar ida/vta
    } elseif($estadoTab == 10) {
      $textDesp  = "Servicio anulado, pero ya se ha finalizado ida y vuelta. Contactar con Asisa para modificarlo.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "10"; // Finalizado
    } elseif($estadoTab == 15) {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    } elseif($estadoTab == 16) {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    } else {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    }
  } elseif($estIda > 5 && $estVta == 5) { // Anulada ida y pendiente vuelta
    if($estadoTab == 1) {   //Estado pendiente en la tabla servicios
      $textDesp  = "Anulada la ida y finalizada la vuelta.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "13"; //Deja el servicio como anulado
    } elseif($estadoTab == 2) {
      $textDesp  = "Servicio adjudicado, pero anulan la ida y finalizan la vuelta.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "10"; //Deja el servicio como anulado
    } elseif($estadoTab == 3) {
      $textDesp  = "Anulada ida y finalizada vuelta. Se había finalizado la ida y estaba pendiente la vuelta. Contactar con Asisa para modificarlo";
      $modEstado = "readonly";
      $filaColor = "danger";
      $estadoTs  = "10"; //Tiene que cambiar el servicio a ingreso y quitar ida/vta
    } elseif($estadoTab == 4) {
      $textDesp  = "Anulada ida y finalizada la vuelta.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "10"; //Tiene que cambiar el servicio a ingreso y quitar ida/vta
    } elseif($estadoTab == 10) {
      $textDesp  = "Finalizada vuelta y anulada ida.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "10"; // Finalizado
    } elseif($estadoTab == 15) {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    } elseif($estadoTab == 16) {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    } else {
      $textDesp  = "Ida anulada y vuelta finalizada";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "10"; // Anulado
    }
  } elseif($estIda > 5 && empty($estVta)) { // ida anulada y no existe registro de vuelta
    if($estadoTab == 1) {   //Estado pendiente en la tabla servicios
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; //Deja el servicio como anulado
    } elseif($estadoTab == 2) { //ida adj o ida fin
      $textDesp  = "Servicio anulado, pero ya se estaba realizando la ida.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; //Deja el servicio como anulado
    } elseif($estadoTab == 3) {
      $textDesp  = "Servicio anulado, no hay registro de vuelta.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15";
    } elseif($estadoTab == 4) {
      $textDesp  = "Anulada la ida y vuelta sin registro. Ya se había realizado la ida. Contactar con Asisa para modificarlo.";
      $modEstado = "readonly";
      $filaColor = "danger";
      $estadoTs  = "10";
    } elseif($estadoTab == 10) {
      $textDesp  = "Sin registro de vuelta, pero se habia realizado ida y vuelta. Contactar con Asisa para modificarlo.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "10"; // Finalizado
    } elseif($estadoTab == 15) {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    } elseif($estadoTab == 16) {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    } else {
      $textDesp  = "Servicio anulado.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "15"; // Anulado
    }
  } elseif($estIda > 5 && $estVta == 2) { // ida anulada y vuelta pendiente
    if($estadoTab == 1) {   //Estado pendiente en la tabla servicios
      $textDesp  = "Anulada ida y vuelta pendiente.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "4";
    } elseif($estadoTab == 2) { //ida adj o ida fin
      $textDesp  = "Anulada ida y vuelta pendiente. Se había adjudicado la ida.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "4"; //Deja el servicio como anulado
    } elseif($estadoTab == 3) {
      $textDesp  = "Anulada ida, pero ya se había realizado la ida. Contactar con Asisa para modificarlo.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "4";
    } elseif($estadoTab == 4) {
      $textDesp  = "Anulada ida y vuelta pendiente.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "4";
    } elseif($estadoTab == 10) {
      $textDesp  = "Anulada ida, vuelta pendiente. Ya se había finalizado el servicio. Contactar con Asisa para modificarlo.";
      $modEstado = "readonly";
      $filaColor = "danger";
      $estadoTs  = "10";
    } elseif($estadoTab == 15) {
      $textDesp  = "Servicio anulado. Pero hay que realizar la vuelta.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "4";
    } elseif($estadoTab == 16) {
      $textDesp  = "Servicio en suspenso. Hay que realizar la vuelta.";
      $modEstado = "readonly";
      $filaColor = "info";
      $estadoTs  = "4";
    } else {
      $textDesp  = "Anulada la ida pero hay que realizar la vuelta.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "4";
    }
  }
# Comprobar fecha de asistencia
# Comprobar hora de consulta
# Comprobar hora de vuelta
} else {
//Servicio único
}

if(empty($horaDTabla)) {
  //Si la hora de vuelta está vacia - tabla serinfo
  if(empty($horaDAsisa)) {
    //Hora de asistencia vacia
    $estHvuelta = "";
    $horaVuelta = muestraHorAsisa($rwAsisaVt['hora_asistencia']);
    $textHvta   = "";
  } else {
    //Hora de asistencia con datos
    $estHvuelta = "modificado";
    $horaVuelta = muestraHorAsisa($rwAsisaVt['hora_asistencia']);
    $textHvta   = " Hora de vuelta modificada por Asisa";
  }
} else {
  //Si contiente hora grabada - tavbla serinfo
  if(empty($horaDTabla)) {
    //Hora de asistencia vacia
    $estHvuelta = "modificado";
    $horaVuelta = muestraHorAsisa($rwAsisaVt['hora_asistencia']);
    $textHvta   = " Hora de vuelta modificada por Asisa";
  } else {
    //Hora de asistencia con datos - ambas tienen datos
    if(muestraHorAsisa($horaDAsisa).":00" == $horaDTabla ){
      //Si ambos datos son iguales
      $estHvuelta = "";
      $horaVuelta = muestraHorAsisa($rwAsisaVt['hora_asistencia']);
      $textHvta   = "";
    } else {
      //Los datos son distintos
      if($serIdvta == 1 ) {
        $estHvuelta = "modificado";
        $horaVuelta = muestraHorAsisa($rwAsisaVt['hora_asistencia']);
        $textHvta   = " Hora de vuelta modificada por Asisa";
      } else {
        $estHvuelta = "";
        $horaVuelta = muestraHorAsisa($rwAsisaVt['hora_asistencia']);
        $textHvta   = "";
      }
    }
  }
}

/* Servicios según estados de asisa */
# servicio marcado como Anulado por asisa - seguitr aqui -> 6: cancelado / 7: anulado
# para vuelta pendiente hay que cambiar la recogida y poner la hora de la tabla serinfo - hvuelta
if($rwSesion['estado'] > '5') {
  # Servicio anulado o cancelado por asisa
  # Si el servicio ya se encuentra finalizado, no deja hacer el cambio a anulado
  # Agrega una nota a incidencias comentando el cambio
  if($rwCompTab['estServ'] == '13') {
    if($rwAsisaVt['estado'] > '5') {
      # Servicio anulado tanto ida como vuelta
      $textDesp  = "Servicio anulado o cancelado por asisa, pero ya se encontraba realizado.";
      $modEstado = "readonly";
      $filaColor = "danger";
      $estadoTs  = "13"; //Deja el servicio como anulado
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    } else {
      //Anulada ida pero vuelta activa
      if($serIdvta == 1 ) {
        $textDesp  = "Anulada IDA por asisa, pero ya se encontraba realizado.";
        $modEstado = "readonly";
        $filaColor = "danger";
        $estadoTs  = "4"; // pone el servicio como vuelta pendiente
        guardarLog('18', $usuario, $textDesp, $idsVMod);
      } else {
        $textDesp  = "Anulado.";
        $modEstado = "readonly";
        $filaColor = "danger";
        $estadoTs  = "15"; // servicio de vuelta pendiente, no tiene sentido para un servicio que no tiene vuelta
      }
    }
  } elseif($rwCompTab['estServ'] == '15') {
    # Servicio anulado
    if($rwAsisaVt['estado'] > '5') {
      # Servicio activado desde un anulado pero la vuelta anulada
      $textDesp  = "Servicio anulado, tanto ida como vuelta.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "15";
    } else {
      # Servicio activado desde un anulado, hay que hacer la vuelta
      if($serIdvta == 1 ) {
        $textDesp  = "Servicio anulado pero hay que hacer la vuelta.";
        $modEstado = "";
        $filaColor = "info";
        $estadoTs  = "4";
      } else {
        $textDesp  = "Servicio anulado";
        $modEstado = "";
        $filaColor = "info";
        $estadoTs  = "15";
      }
    }
  } elseif($rwCompTab['estServ'] == '14') {
    # Servicio finalizado - solamente para servicios sin vuelta
    if($serIdvta == 1 ){
      //Solo es para servicios sin ida y vuelta
      $textDesp  = "";
      $modEstado = "";
      $filaColor = "";
      $estadoTs  = "";
    } else {
      if($rwAsisaVt['estado'] > '5') {
        # Servicio activado desde un anulado pero la vuelta anulada
        $textDesp  = "Servicio finalizado, ya se encuentra realizado. No se puede anular. Contactar con Asisa para modificarlo.";
        $modEstado = "";
        $filaColor = "danger";
        $estadoTs  = "14";
        guardarLog('18', $usuario, $textDesp, $idsVMod);
      } else {
        # Servicio activado desde un anulado, hay que hacer la vuelta
        $textDesp  = "Servicio finalizado, ya se encuentra realizado. No se puede volver a activar. Contactar con Asisa para modificarlo.";
        $modEstado = "";
        $filaColor = "info";
        $estadoTs  = "14";
        guardarLog('18', $usuario, $textDesp, $idsVMod);
      }
    }
  } elseif($rwCompTab['estServ'] == '16') {
    # Servicio en suspenso
    if($rwAsisaVt['estado'] > '5') {
      # Servicio activado desde un anulado pero la vuelta anulada
      $textDesp  = "Servicio anulado. Se cambia de servicio en suspenso a anulado.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "15";
    } else {
      # Servicio activado desde un anulado, hay que hacer la vuelta
      $textDesp  = "Servicio anulado pero hay que hacer la vuelta.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "4";
    }
  } else {
    # Cuando el estado es distinto a: Anulado, En suspenso o Finalizado
    if($rwAsisaVt['estado'] > '5') {
      # Servicio activado desde un anulado pero la vuelta anulada
      $textDesp  = "Servicio anulado, tanto la ida como la vuelta.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "15";
    } else {
      # Servicio activado desde un anulado, hay que hacer la vuelta
      if($serIdvta == 1 ) {
        $textDesp  = "Anulada ida pero activa la VUELTA.";
        $estadoTs  = "4";
      } else {
        $textDesp  = "Servicio anulado.";
        $estadoTs  = "15";
      }
      $modEstado = "";
      $filaColor = "info";
    }
  }
} elseif($rwSesion['estado'] > '2') {
  # Cambiado a asignado por asisa
  if($rwCompTab['estServ'] == '13') {
    if($rwAsisaVt['estado'] > '5') {
      # Servicio anulada la vuelta e ida activa, aunque ya estaba como finalizado en servicios
      $textDesp  = "Activada la ida, aunque el servicio ya se encontraba como finalizado.";
      $modEstado = "readonly";
      $filaColor = "warning";
      $estadoTs  = "1";
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    } else {
      // Servicio finalizado pero activo de nuevo ida y vuelta
      $textDesp  = "Servicio activo IDA y VUELTA, pero ya se encontraba realizado.";
      $modEstado = "readonly";
      $filaColor = "warning";
      $estadoTs  = "1"; // pone el servicio como vuelta pendiente
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    }
  } elseif($rwCompTab['estServ'] == '15') {
    # Servicio anulado
    if($rwAsisaVt['estado'] > '5') {
      # Servicio activado desde un anulado pero la vuelta anulada
      $textDesp  = "Servicio anulado pero activa la IDA y anulada la vuelta.";
      $modEstado = "";
      $filaColor = "warning";
      $estadoTs  = "1";
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    } else {
      # Servicio activado desde un anulado, hay que hacer la vuelta
      $textDesp  = "Servicio anulado pero vuelto a activar ida y vuelta.";
      $modEstado = "";
      $filaColor = "warning";
      $estadoTs  = "1";
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    }
  } elseif($rwCompTab['estServ'] == '16') {
    # Servicio en suspenso
    if($rwAsisaVt['estado'] > '5') {
      # Servicio activado desde un anulado pero la vuelta anulada
      $textDesp  = "Servicio en suspenso pero activada solamente la IDA.";
      $modEstado = "";
      $filaColor = "warning";
      $estadoTs  = "1";
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    } else {
      # Servicio activado desde un anulado, hay que hacer la vuelta
      $textDesp  = "Servicio en suspenso pero vuelto a activar ida y vuelta.";
      $modEstado = "";
      $filaColor = "warning";
      $estadoTs  = "1";
      guardarLog('18', $usuario, $textDesp, $idsVMod);
    }
  } else {
    # Cuando el estado es distinto a: Anulado, En suspenso o Finalizado
    if($rwAsisaVt['estado'] > '5') {
      # Servicio activado desde un anulado pero la vuelta anulada
      $textDesp  = "Anulada la VUELTA.";
      $modEstado = "";
      $filaColor = "warning";
      $estadoTs  = "1";
    } else {
      # Se hace tanto ida como vuelta
      $textDesp  = "";
      $modEstado = "";
      $filaColor = "";
      $estadoTs  = $rwCompTab['estServ'];
    }
  }
} elseif($rwSesion['estado'] == '2') {
  if($rwCompTab['estServ'] == '13') {
    $textDesp  = "Servicio finalizado. Ya se ha realizado, no se puede modificar un finalizado. Contactar con Asisa para modificarlo";
    $modEstado = "readonly";
    $filaColor = "info";
    $estadoTs  = "13";
    guardarLog('18', $usuario, $textDesp, $idsVMod);
  } elseif($rwCompTab['estServ'] == '14') {
    $textDesp  = "Servicio finalizado. Ya se ha realizado, no se puede modificar un finalizado. Conctactar con Asisa para modificarlo";
    $modEstado = "readonly";
    $filaColor = "info";
    $estadoTs  = "14";
    guardarLog('18', $usuario, $textDesp, $idsVMod);
  } elseif($rwCompTab['estServ'] == '15') {
    $textDesp  = "Servicio activado de nuevo por Asisa.";
    $modEstado = "";
    $filaColor = "info";
    $estadoTs  = "1";
    guardarLog('18', $usuario, $textDesp, $idsVMod);
  } else {
    $textDesp  = "";
    $modEstado = "";
    $filaColor = "";
    $estadoTs  = $rwCompTab['estServ'];
  }
} elseif($rwSesion['estado'] == 5) {
  //Estado ida finalizado
  if($serIdvta == 1 ){
    //Servicio de id/vta
    if($rwAsisaVt['estado'] > '5') {
      $textDesp  = "Finalizada ida y anulada la vuelta.";
      $modEstado = "";
      $filaColor = "warning";
      $estadoTs  = "13";
    } elseif($rwAsisaVt['estado'] == '5') {
      $textDesp  = "Servicio finalizado.";
      $modEstado = "";
      $filaColor = "";
      $estadoTs  = "10";
    } elseif($rwAsisaVt['estado'] == '2') {
      $textDesp  = "Finalizada ida y pendiente vuelta";
      $modEstado = "";
      $filaColor = "";
      $estadoTs  = "4";
    } else {
      $textDesp  = "";
      $modEstado = "";
      $filaColor = "";
      $estadoTs  = $rwCompTab['estServ'];
    }
  } else {
    //Sólo ida
  }
} else {
  # Si no hay ninguna modificacion comprueba el estado de servicio.
  if($rwCompTab['estServ'] == '13') {
    $textDesp  = "Servicio finalizado. No se puede modificar desde esta pantalla.";
    $modEstado = "readonly";
    $filaColor = "info";
    $estadoTs  = "13";
    guardarLog('18', $usuario, $textDesp, $idsVMod);
  } elseif($rwCompTab['estServ'] == '14') {
    $textDesp  = "Servicio finalizado. No se puede modificar desde esta pantalla.";
    $modEstado = "readonly";
    $filaColor = "info";
    $estadoTs  = "14";
    guardarLog('18', $usuario, $textDesp, $idsVMod);
  } elseif($rwCompTab['estServ'] == '15') {
    if($rwSesion['estado'] == '2') {
      $textDesp  = "Servicio activado de nuevo por Asisa.";
      $modEstado = "";
      $filaColor = "info";
      $estadoTs  = "1";
    } elseif($rwSesion['estado'] > '5') {
      $textDesp  = "Servicio Anulado.";
      $modEstado = "";
      $filaColor = "danger";
      $estadoTs  = "15";
    }
  } else {
    $textDesp  = "";
    $modEstado = "";
    $filaColor = "";
    $estadoTs  = $rwCompTab['estServ'];
  }
}

/* Si se ha modificado la hora, el campo hora de recogida quedará vacío */
if(muestraHorAsisa($rwSesion['hora_asistencia']).":00" !== $rwCompTab['hconsulta']) {
  $horaReco = "";
} else {
  $horaReco = $rwCompTab['hora'];
}

/* Texto para modificaciones */
if(cModiDos(muestraFechAsisa($rwSesion['fecha_asistencia']), $rwCompTab['fecha']) == '1') {
  $textFecha = " Se ha modicado la fecha de asistencia.";
  guardarLog('17', $usuario, $textFecha, $idsVMod);
} else {
  $textFecha = "";
}
if($serIdvta == 1) {
  //Cuando no son servicios de ida y vuelta
  if(cModiDos(muestraHorAsisa($rwSesion['hora_asistencia']).":00", $rwCompTab['hconsulta']) == '1'){
    $textHora = " Se ha modicado la hora de consulta.";
    guardarLog('11', $usuario, $textHora, $idsVMod);
  } else {
    $textHora = "";
  }
} else {
  //Cuando son servicios de ida y vuelta
  if(cModiDos(muestraHorAsisa($rwSesion['hora_asistencia']).":00", $rwCompTab['hora']) == '1'){
    $textHora = " Se ha modicado la hora de consulta.";
    guardarLog('11', $usuario, $textHora, $idsVMod);
  } else {
    $textHora = "";
  }
}

/* Muestra cuando un servicio se ha agregado y no existe correspondencia en la tabla servicios */
if(empty($rwCompTab['idSv'])) {
  $textAgregar = " Se ha agregado nuevo servicio.";
} else {
  $textAgregar = "";
}
/* Muestra estado cuando se agrega un servicio nuevo */
if(empty($estadoTs)) {
  if($serIdvta == 1) {
    //El servicio es de ida y vuelta
    if($rwSesion['estado'] > '5') {
      if($rwAsisaVt['estado'] > '5') {
        //Si ida y vuelta están anuladas
        $estadoTs = "15";
      } elseif($rwAsisaVt['estado'] == '5') {
        //Si la ida esta anulada y la vuelta finalizada
        $estadoTs = "10";
      } elseif($rwAsisaVt['estado'] == '2') {
        //Si la ida esta anulada y la vuelta activa
        $estadoTs = "4";
      } else {
        $estadoTs = "1";
      }
    } elseif($rwSesion['estado'] == '5') {
      if($rwAsisaVt['estado'] > '5') {
        //Ida finalizada y vuelta anulada
        $estadoTs = "13";
      } elseif($rwAsisaVt['estado'] == '5') {
        //Ida y vuelta finalizadas
        $estadoTs = "13";
      } elseif($rwAsisaVt['estado'] == '2') {
        //ida finalizada y vuelta pendiente
        $estadoTs = "4";
      } else {
        $estadoTs = "1";
      }
    } elseif($rwSesion['estado'] == '2') {
      if($rwAsisaVt['estado'] > '5') {
        //ida pendiente y vuelta anulada
        $estadoTs = "1";
      } elseif($rwAsisaVt['estado'] == '5') {
        //ida pendiente y vuelta finalizada
        $estadoTs = "1";
      } elseif($rwAsisaVt['estado'] == '2') {
        //ida pendiente y vuelta pendiente
        $estadoTs = "1";
      } else {
        $estadoTs = "1";
      }
    } else {
      //Otros
      $estadoTs = "1";
    }
  } else {
    //Servicio sin ida y vuelta
    if($rwSesion['estado'] > '5') {
      //El servicio esta anulado
      $estadoTs = "15";
    } elseif($rwSesion['estado'] == '5') {
      //El servicio está finalizado
      $estadoTs = "14";
    } else {
      //Otros
      $estadoTs = "1";
    }
  }
}
?>
