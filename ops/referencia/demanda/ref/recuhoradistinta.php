<?php
/* Mostrar hora de vuelta */
$asisaVuelta = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado
  FROM asisaasistencia
  WHERE cod_demanda='$codigoFiltro' AND fecha_asistencia='$fechaAsist' AND vuelta='S'
");
$numVuelta = mysqli_num_rows($asisaVuelta);
$rwAsisaVt = mysqli_fetch_assoc($asisaVuelta);
/* Mostrar ida */
$asisaIda = mysqli_query($gestambu, "SELECT cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado
  FROM asisaasistencia
  WHERE cod_demanda='$codigoFiltro' AND fecha_asistencia='$fechaAsist' AND vuelta='N'
");
$numIda     = mysqli_num_rows($asisaIda);
$rwAsisaIda = mysqli_fetch_assoc($asisaIda);

/* Autotexto */


# Texto de ida o vuelta
if($numIda == 0 && $numVuelta == 0 ) {
  //Cuando no hay ni registro de ida ni vuelta
  $textAgregar = "";
  $esTabla     = "";
  $menGuarda   = "0";
} elseif($numIda == 1 && $numVuelta == 0 ) {
  //Cuando hay registro de ida pero no de vuelta
  if($rwAsisaIda['estado'] > 5 ) {
    //Cuando el estado es anulado o cancelado comprueba si es servicio de ida y vuelta o único
    if($rwDemanda['cod_servicio'] > 89 && $rwDemanda['cod_servicio'] < 119 ) {
      $textAgregar = "CREADO COMO ANULADO. SERVICIO SIN REGISTRO DE VUELTA.";
      $esTabla     = "15";
      $menGuarda   = "1";
    } else {
      $textAgregar = "ANULADO.";
      $esTabla     = "15";
      $menGuarda   = "1";
    }
  } elseif($rwAsisaIda['estado'] == 5) {
    //Cuando el servicio está finalizado
    if($rwDemanda['cod_servicio'] > 89 && $rwDemanda['cod_servicio'] < 119 ) {
      $textAgregar = "CREADO COMO FINALIZADO. SERVICIO SIN REGISTRO DE VUELTA.";
      $esTabla     = "14";
      $menGuarda   = "1";
    } else {
      $textAgregar = "CREADO COMO FINALIZADO.";
      $esTabla     = "14";
      $menGuarda   = "1";
    }
  } else {
    if($rwDemanda['cod_servicio'] > 89 && $rwDemanda['cod_servicio'] < 119 ) {
      $textAgregar = "CREADO SIN VUELTA, SOLO HACER LA IDA.";
      $esTabla     = "1";
      $menGuarda   = "1";
    } else {
      $textAgregar = "";
      $esTabla     = "1";
      $menGuarda   = "0";
    }
  }
} elseif($numIda == 0 && $numVuelta == 1 ) {
  //Cuando hay registro de vuelta pero no de ida
  if($rwAsisaVt['estado'] > 5 ) {
    //Cuando el estado es anulado o cancelado no hay que hacer comprobación de si es un servicio de ida o vuelta puesto que al tener vuelta activa se supone que es de ida y vuelta
    $textAgregar = "CREADO CON VUELTA ANULADO Y SIN REGISTRO DE IDA.";
    $esTabla     = "15";
    $menGuarda   = "1";
  } elseif($rwAsisaVt['estado'] == 5) {
    //Cuando el servicio está finalizado
    $textAgregar = "CREADO COMO FINALIZADO. SERVICIO SIN REGISTRO DE IDA.";
    $esTabla     = "10";
    $menGuarda   = "1";
  } else {
    $textAgregar = "";
    $esTabla     = "1";
    $menGuarda   = "0";
  }
} elseif($numIda == 1 && $numVuelta == 1 ) {
  //Cuando hay registro de ida y de vuelta
  if($rwAsisaIda['estado'] > 5 && $rwAsisaVt['estado'] > 5 ) {
    //Tanto ida y vuelta están anulados
    $textAgregar = "CREADO COMO ANULADO.";
    $esTabla     = "14";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] > 5 && $rwAsisaVt['estado'] == 5 ) {
    $textAgregar = "CREADO COMO IDA ANULADA Y VUELTA FINALIZADA.";
    $esTabla     = "10";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] > 5 && $rwAsisaVt['estado'] == 2 ) {
    $textAgregar = "CREADO COMO IDA ANULADA Y VUELTA PENDIENTE.";
    $esTabla     = "4";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] == 5 && $rwAsisaVt['estado'] > 5 ) {
    $textAgregar = "CREADO COMO FINALIZADO Y VUELTA ANULADA";
    $esTabla     = "10";
    $menGuarda   = "1";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] == 5 && $rwAsisaVt['estado'] == 2 ) {
    $textAgregar = "CREADO COMO IDA FINALIZADA Y VUELTA PENDIENTE.";
    $esTabla     = "4";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] == 5 && $rwAsisaVt['estado'] == 5 ) {
    $textAgregar = "CREADO FINALIZADO.";
    $esTabla     = "10";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] == 2 && $rwAsisaVt['estado'] > 5 ) {
    $textAgregar = "CREADO COMO PENDIENTE IDA Y VUELTA ANULADA.";
    $esTabla     = "1";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] == 2 && $rwAsisaVt['estado'] == 5 ) {
    $textAgregar = "CREADO COMO PENDIENTE Y VUELTA FINALIZADA.";
    $esTabla     = "1";
    $menGuarda   = "1";
  } elseif($rwAsisaIda['estado'] == 2 && $rwAsisaVt['estado'] == 2 ) {
    $textAgregar = "";
    $esTabla     = "1";
    $menGuarda   = "0";
  } else {
    $textAgregar = "ESTADO NO REGISTRADO";
    $esTabla     = "1";
    $menGuarda   = "1";
  }
} else {
  $textAgregar = "";
  $esTabla     = "1";
  $menGuarda   = "0";
}
 ?>
