<?php
# Conexiones para GestAmbu
# Modificar archivo para conectar en otros servidores
# Jgarcia - 2016

/* ACTIVAR ERRORES */
/*
error_reporting(E_ALL);

ini_set('display_errors', '1');
error_reporting(-1);
error_reporting(0);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
*/
/*  require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

$console = new \bdk\Debug(array(
  'collect' => true,
  'output' => true,
  'logRequestInfo' => true, // this is the default (all other examples use `false`),
  'logResponse' => true, // this is the default (other other examples use `false`),
)); 
 */


$servidor    = "localhost";
$usuario     = "root";
$contrasenia = "";
$nombreDb    = "gestambu3";
$versionGest = "GestAmbu 3.0";

/*   $servidor    = "db";
  $usuario     = "root";
  $contrasenia = "d4t4-B4$3$";
  $nombreDb    = "gestambu3";
  $versionGest = "GestAmbu 3.0"; */
 
$gestambu  = mysqli_connect("$servidor", "$usuario", "$contrasenia", "$nombreDb")  or die("Error conectando a la BBDD");
//echo "conectado correctamente";
//include 'API/SOAPclient.php';

/* cambiar el conjunto de caracteres a utf8 */
if (!mysqli_set_charset($gestambu, "utf8")) {
  // printf("Error cargando el conjunto de caracteres ññ utf8: %s\n", mysqli_error($gestambu));
  exit();
} else {
  //printf("Conjunto de caracteres actual: ññ %s\n", mysqli_character_set_name($gestambu));
}


/* comprueba la conexión */
if (mysqli_connect_errno()) {
  echo "No se pudo conectar a la Base de Datos" . mysqli_connect_error();
}

/* Seleccion de zona local */
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'spanish');

/* Límite de km */
$limKm     = 2000;
$preLit    = 1.5;

/* Restar horas */
function RestarHoras($horaini, $horafin)
{
  $horai = substr($horaini, 0, 2);
  $mini = substr($horaini, 3, 2);
  $segi = substr($horaini, 6, 2);

  $horaf = substr($horafin, 0, 2);
  $minf = substr($horafin, 3, 2);
  $segf = substr($horafin, 6, 2);

  $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
  $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

  $dif = $fin - $ini;

  $difh = floor($dif / 3600);
  $difm = floor(($dif - ($difh * 3600)) / 60);
  $difs = $dif - ($difm * 60) - ($difh * 3600);
  return date("H:i:s", mktime($difh, $difm, $difs));
}

/* Fecha en español */
function fechaEs()
{
  $arrayMeses = array(
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
  );

  $arrayDias = array(
    'Domingo', 'Lunes', 'Martes',
    'Miércoles', 'Jueves', 'Viernes', 'Sábado'
  );

  echo $arrayDias[date('w')] . ", " . date('d') . " de " . $arrayMeses[date('m') - 1] . " de " . date('Y');
}
function fechaNom($date)
{
  $dia = explode("-", $date, 3);
  $year = $dia[0];
  $month = (string)(int)$dia[1];
  $day = (string)(int)$dia[2];

  $dias = array("D", "L", "M", "X", "J", "V", "S");
  $tomadia = $dias[intval((date("w", mktime(0, 0, 0, $month, $day, $year))))];

  $meses = array("", "En", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Agost", "Sept", "Oct", "Nov", "Dic");

  //return $tomadia.", ".$day." de ".$meses[$month]." de ".$year;
  return $day . " " . $tomadia . " de " . $meses[$month];
}

function fechaFmt($fechaDada)
{
  $fecha = new DateTime($fechaDada);
  $arregloFecha = date_format($fecha, 'd-m-Y');
  return $arregloFecha;
}
function fechaFmtCorta($fechaDadaCorta)
{
  $fecha = new DateTime($fechaDadaCorta);
  $arregloFechaCorta = date_format($fecha, 'd-m-y');
  return $arregloFechaCorta;
}
/* Fecha timestamp */
function fechaGDB()
{
  $fechaM = date('Y-m-d H:i:s');
  return $fechaM;
}

/* Valor para provincia */
function provValor($provincia)
{
  if ($provincia == '29') {
    $provSalida = "Málaga";
  } elseif ($provincia == '41') {
    $provSalida = "Sevilla";
  } elseif ($provincia == '11') {
    $provSalida = "Cádiz";
  } elseif ($provincia == '21') {
    $provSalida = "Huelva";
  } elseif ($provincia == '52') {
    $provSalida = "Melilla";
  } elseif ($provincia == '14') {
    $provSalida = "Córdoba";
  } elseif ($provincia == '0') {
    $provSalida = "General";
  } else {
    $provSalida = "Sin provincia conocida";
  }
  echo $provSalida;
}

function provValor2($provincia)
{
  if ($provincia == '29') {
    $provSalida = "Málaga";
  } elseif ($provincia == '41') {
    $provSalida = "Sevilla";
  } elseif ($provincia == '11') {
    $provSalida = "Cádiz";
  } elseif ($provincia == '21') {
    $provSalida = "Huelva";
  } elseif ($provincia == '52') {
    $provSalida = "Melilla";
  } elseif ($provincia == '14') {
    $provSalida = "Córdoba";
  } elseif ($provincia == '0') {
    $provSalida = "General";
  } else {
    $provSalida = "Sin provincia conocida";
  }
  return $provSalida;
}

/* Valor para provincia */
function provValorRet($rwProv)
{
  if ($rwProv == '29') {
    $provSalida = "Málaga";
  } elseif ($rwProv == '41') {
    $provSalida = "Sevilla";
  } elseif ($rwProv == '11') {
    $provSalida = "Cádiz";
  } elseif ($rwProv == '0') {
    $provSalida = "General";
  } elseif ($rwProv == '21') {
    $provSalida = "Huelva";
  } elseif ($rwProv == '52') {
    $provSalida = "Melilla";
  } elseif ($rwProv == '14') {
    $provSalida = "Córdoba";
  } else {
    $provSalida = "Sin provincia conocida";
  }
  return $provSalida;
}

/* Usuario no registrado */
function nonUser()
{
  if (empty($_SESSION['userId'])) {
    header("Location: /nonuser.php"); //crear pagina sin usuario
  }
}

/* Cálculo para días festivos */
function festivo($fecha, $hora)
{
  $sdm = array("domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo");
  $nf = date("w", strtotime($fecha));
  $d_fst = $sdm[$nf];
  if (isset($hora)) {
    $h_fst = $hora;
  } else {
    $h_fst = date('H:i');
  }

  if ($h_fst >= "22:00" || $h_fst <= "08:00") {
    $fst = "1";
  } elseif ($d_fst == "domingo") {
    $fst = "1";
  } elseif ($d_fst == "sabado" and $h_fst >= "14:00") {
    $fst = "1";
  } else {
    $fst = "0";
  }
  return $fst;
}

/* Valor para ambulancias, uvi con: due, medico */
function ambComple($recurso, $due, $med, $nombreRecu)
{
  if ($recurso == '1') {
    if ($due == '1' && $med == '1') {
      echo "AMB+MED+DUE";
    } else if ($due === '' && $med == '1') {
      echo "AMB+MED";
    } else if ($due === '' && $med === '') {
      echo "AMB";
    } else if ($due == '1' && $med === '') {
      echo "AMB+DUE";
    }
  } else if ($recurso == '3') {
    if ($due == '1' && $med == '1') {
      echo "UVI+MED+DUE";
    } else if ($due === '' && $med == '1') {
      echo "UVI+MED";
    } else if ($due === '' && $med === '') {
      echo "UVI";
    } else if ($due == '1' && $med === '') {
      echo "UVI+DUE";
    }
  } else {
    echo $nombreRecu;
  }
}

function ambComple2($recurso, $due, $med, $nombreRecu)
{
  if ($recurso == '1') {
    if ($due == '1' && $med == '1') {
      return "AMB+MED+DUE";
    } else if ($due === '' && $med == '1') {
      return "AMB+MED";
    } else if ($due === '' && $med === '') {
      return "AMB";
    } else if ($due == '1' && $med === '') {
      return "AMB+DUE";
    }
  } else if ($recurso == '3') {
    if ($due == '1' && $med == '1') {
      return "UVI+MED+DUE";
    } else if ($due === '' && $med == '1') {
      return "UVI+MED";
    } else if ($due === '' && $med === '') {
      return "UVI";
    } else if ($due == '1' && $med === '') {
      return "UVI+DUE";
    }
  } else {
    return $nombreRecu;
  }
}

/* Valores para vuelos */
function valoresVuelos($vVuelo)
{
  if ($vVuelo == 1) {
    echo "CONVENCIONAL";
  } elseif ($vVuelo == 2) {
    echo "CRITICO";
  } elseif ($vVuelo == 3) {
    echo "RETORNO";
  } elseif ($vVuelo == 4) {
    echo "TRASLPLANTE";
  } else {
    echo "No identificado";
  }
}

function valoresVuelos2($vVuelo)
{
  if ($vVuelo == 1) {
    return "CONVENCIONAL";
  } elseif ($vVuelo == 2) {
    return "CRITICO";
  } elseif ($vVuelo == 3) {
    return "RETORNO";
  } elseif ($vVuelo == 4) {
    return "TRASLPLANTE";
  } else {
    return "No identificado";
  }
}

/* Valores para jeditable - index - estado */
function estJeditable($valorEstado)
{
  if ($valorEstado == '1') {
    echo "tieneVuelta";
  } else {
    echo "sinVuelta";
  }
}
function estJeditable1($valorEstado)
{
  if ($valorEstado == '1') {
    return "tieneVuelta";
  } else {
    return "sinVuelta";
  }
}

/* Valores para tipo de vehículo */
function tipoVehiculo($tipVh)
{
  if ($tipVh == 1) {
    echo "U.V.I.";
  } elseif ($tipVh == 2) {
    echo "Convencional";
  } elseif ($tipVh == 3) {
    echo "Colectiva";
  } elseif ($tipVh == 4) {
    echo "V.I.R.";
  } elseif ($tipVh == 5) {
    echo "moto";
  } else {
    echo "No registrado";
  }
}
/* Valor vacío */
function valorVacio($valorDato)
{
  if ($valorDato != '') {
    return $valorDato;
  } else {
    return '0';
  }
}
/* No mostrar valor 0 */
function noZero($ValorZero)
{
  if ($ValorZero != '0') {
    echo $ValorZero;
  } else {
    echo '';
  }
}
/* Contraseña automática */
function autoPass()
{
  $psswd = substr(md5(microtime()), 1, 8);
  return $psswd;
}

/* Mostrar Compañia */
function mostrarCia($numCia)
{
  global $gestambu;
  $mostCia = mysqli_query($gestambu, "SELECT idCia, ciaNom FROM cia WHERE idCia = '$numCia'");
  $rwMostCia = mysqli_fetch_assoc($mostCia);
  return $rwMostCia['ciaNom'];
}
/* Mostrar nombre vehículo */
function mostrarVehiculo($vehiculo)
{
  // Contempla también cuando el valor 0, ha de mostrar valor vacío
  global $gestambu;
  $vhMst = $vehiculo;

  if ($vhMst == 0) {
    echo " -- ";
  } else {
    $mostrarVh = mysqli_query($gestambu, "SELECT idVh, matricula FROM vehiculo WHERE idVh = '$vhMst'");
    $rwMostrarVh = mysqli_fetch_assoc($mostrarVh);
    $matricula = $rwMostrarVh['matricula'];
    $matExp = explode("-", $matricula);
    echo $matExp[0];
  }
}



function mostrarVehiculo2($vehiculo)
{
  // Contempla también cuando el valor 0, ha de mostrar valor vacío
  global $gestambu;
  $vhMst = $vehiculo;

  if ($vhMst == 0) {
    return " -- ";
  } else {
    $mostrarVh = mysqli_query($gestambu, "SELECT idVh, matricula FROM vehiculo WHERE idVh = '$vhMst'");
    $rwMostrarVh = mysqli_fetch_assoc($mostrarVh);
    $matricula = $rwMostrarVh['matricula'];
    $matExp = explode("-", $matricula);
    return $matExp[0];
  }
}

/* Mostrar Técnico */
function mostrarTecnico($tecnicoAmbu)
{
  // Contempla también cuando el valor 0, ha de mostrar valor vacío
  global $gestambu;
  $tecAmb = $tecnicoAmbu;

  if ($tecAmb == 0 || $tecAmb == '') {
    echo " -- ";
  } else {
    $mostrarTec = mysqli_query($gestambu, "SELECT userId, usNom, usApe FROM user WHERE userId = '$tecAmb'");
    $rwMostrarTec = mysqli_fetch_assoc($mostrarTec);
    echo $rwMostrarTec['usNom'] . " " . $rwMostrarTec['usApe'];
  }
}

/* Mostrar estados */
function mostrarEstados($mstEst)
{
  global $gestambu;

  $mostrarEst = mysqli_query($gestambu, "SELECT idEst, vaEst FROM estados WHERE idEst = '$mstEst'");
  $rwMostrarEst = mysqli_fetch_assoc($mostrarEst);
  echo $rwMostrarEst['vaEst'];
}

function mostrarEstados2($mstEst)
{
  global $gestambu;

  $mostrarEst = mysqli_query($gestambu, "SELECT idEst, vaEst FROM estados WHERE idEst = '$mstEst'");
  $rwMostrarEst = mysqli_fetch_assoc($mostrarEst);
  return $rwMostrarEst['vaEst'];
}

/* Pauta de sesiones */
function pautaSesion($valorSesion)
{
  if ($valorSesion == 1) {
    echo "Cada 24h";
  } elseif ($valorSesion == 2) {
    echo "Cada 48h";
  } elseif ($valorSesion == 3) {
    echo "Cada 72h";
  } elseif ($valorSesion == 4) {
    echo "De Lunes a Viernes";
  } elseif ($valorSesion == 5) {
    echo "Lunes - Miércoles - Viernes";
  } elseif ($valorSesion == 6) {
    echo "Martes - Jueves - Sábados";
  } elseif ($valorSesion == 7) {
    echo "Sábados y Domingos";
  } else {
    echo "Pauta no especificada";
  }
}

/* Calcular tanto por ciento %  -> para mostrar servicios continuados */
function tantoxcien($vMaximo, $vRestante)
{
  $vMinimo = $vMaximo - $vRestante;
  $divMaxMin = ($vMinimo * 100) / $vMaximo;

  return round($divMaxMin);
}

/* Acortar Texto */
function recortar_texto($texto, $limite = 100)
{
  $texto = trim($texto);
  $texto = strip_tags($texto);
  $tamano = strlen($texto);
  $resultado = '';
  if ($tamano <= $limite) {
    return $texto;
  } else {
    $texto = substr($texto, 0, $limite);
    $palabras = explode(' ', $texto);
    $resultado = implode(' ', $palabras);
    $resultado .= '...';
  }
  return $resultado;
}

/* Cambiar fecha */
function cambiarFecha($fechaDada)
{
  $nuevaFecha = explode("-", $fechaDada);
  $anio = $nuevaFecha[0];
  $mes = $nuevaFecha[1];
  $dia = $nuevaFecha[2];

  return $dia . "-" . $mes . "-" . $anio;
}
/* Funciones para "arreglos de hora" */
function sinHora($compHora)
{
  if ($compHora == '00:00:00') {
    return "";
  } else {
    return $compHora;
  }
}
function sinHoraSeg($compHoraSeg)
{
  if ($compHoraSeg == '00:00') {
    return "";
  } else {
    return $compHoraSeg;
  }
}

function arregloHora($horaCambiar)
{
  if (strlen($horaCambiar) == '8') {
    $hFormat = $horaCambiar;
    return $hFormat;
  } elseif (strlen($horaCambiar) == '5') {
    $hFormat = $horaCambiar . ":00";
    return $hFormat;
  }
}

/* Mostrar icono de usuario */
function mostrarIcoUser($usarioVer)
{
  global $gestambu;

  $mostIcoUser = mysqli_query($gestambu, "SELECT userId, usImg FROM user WHERE userId = '$usarioVer'");
  $rwMost = mysqli_fetch_assoc($mostIcoUser);

  $imagenMost= @$rwMost['usImg'];

  if ($imagenMost == '') {
    return "user.png";
  } else {
    return $imagenMost;
  }
}

/* Insertar mensaje en Log */
function guardarLog($msjLog, $userId, $obsText, $servicioID)
{
  global $gestambu;

  $sqlGuardar = mysqli_query($gestambu, "INSERT INTO loguser (idLog, userId, obsText, idSv) VALUES ('$msjLog', '$userId', '$obsText', '$servicioID')");
}

/* Insertar mensaje en Log - continuado */
function guardarLogCont($msjLog, $userId, $obsText, $servicioID)
{
  global $gestambu;

  $verCont = mysqli_query($gestambu, "SELECT idSv, continuado, estServ FROM servicio WHERE continuado ='$servicioID' AND estServ NOT IN('10', '14')");
  while ($rwCont = mysqli_fetch_array($verCont)) {
    $regId = $rwCont['idSv'];
    $sqlGuardar = mysqli_query($gestambu, "INSERT INTO loguser (idLog, userId, obsText, idSv) VALUES ('$msjLog', '$userId', '$obsText', '$regId')");
  }
}

/* Motivos de incidencia */
function mostMotv($mostSel)
{
  if ($mostSel == 0) {
    echo "No especificado";
  } elseif ($mostSel == 1) {
    echo "Anulado por demora";
  } elseif ($mostSel == 2) {
    echo "Rechaza Asistencia";
  } elseif ($mostSel == 3) {
    echo "Ausente en domicilio";
  } elseif ($mostSel == 4) {
    echo "Otros motivos";
  } else {
    echo "No especificado";
  }
}

/* Comprobar servicio impreso */
function compImpreso($servComp)
{
  global $gestambu;

  $compSerImp = mysqli_query($gestambu, "SELECT idLog, idSv FROM loguser WHERE idSv = '$servComp' AND idLog = '4' ");
  $numLog = mysqli_num_rows($compSerImp);

  if ($numLog > '0') {
    $impresion = '<i class="fa fa-print"></i>';
    return $impresion;
  }
}

/* Ver última sesión */
function verUltima($numeSesion)
{
  global $gestambu;

  if ($numeSesion != '0') {
    $compUlti = mysqli_query($gestambu, "SELECT COUNT(servicio.continuado) AS restantes, servicio.continuado, servicio.estServ
		  FROM servicio
		  WHERE servicio.continuado = '$numeSesion' AND servicio.estServ NOT IN('10', '14', '15')
		  GROUP BY servicio.continuado
		  ");
    $rwCompUlti = mysqli_fetch_array($compUlti);
    $ultiComp = $rwCompUlti['restantes'];

    if ($ultiComp == '1') {
      $sucUlti = 1;
    } else {
      $sucUlti = 2;
    }
  }
  return @$sucUlti;
}

/* Nueva entrada de servicio */
function nuevoSer($valorHora, $estadServ)
{
  $hServ    = explode(" ", $valorHora);
  $fechaTab = $hServ[0];
  $horaTab  = $hServ[1];

  //Extraer hora, minuto y segundo
  $data_c2 = explode(":", $horaTab);
  $hour_c  = $data_c2[0];
  $min_c   = $data_c2[1];

  //Resta de fechas
  $f1 = new DateTime($fechaTab);
  $f2 = new DateTime(date("Y-m-d"));
  $interval = $f1->diff($f2);

  //Variables para hora
  $r_h = $hour_c - (date("H"));
  $r_m = $min_c - (date("i"));

  if ($estadServ == '1') {
    if ($interval->format('%R%a') != '+0') {
      $dsv = "0";
    } else {
      if ($r_h != '0') {
        $dsv = "0";
      } else {
        if ($r_m >= '-5' & $r_m <= '5') {
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

/* Calculo de pauta */
function calculoPauta($pauta)
{
  if (empty($pauta)) {
    return "PAUTA PENDIENTE";
  } else {
    $calPa = explode("-", $pauta);
    $canti = $calPa[0];
    $perio = $calPa[1];

    if ($perio == '1') {
      if ($canti == '1') {
        $dsm = "DIA";
      } else {
        $dsm = "DIAS";
      }
    } elseif ($perio == '2') {
      if ($canti == '1') {
        $dsm = "SEMANA";
      } else {
        $dsm = "SEMANAS";
      }
    } elseif ($perio == '3') {
      if ($canti == '1') {
        $dsm = "MES";
      } else {
        $dsm = "MESES";
      }
    } else {
      $dsm = " - - ";
    }

    return $canti . " " . $dsm;
  }
}

/* Funciones para demanda de asisa - principal */
function sanear_string($string)
{

  $string = trim($string);

  //Cambia los valores # por Ñ -> reconocido por Asisa
  # Problema con la DB de Asisa y la DB de Asisa24h (RAD)
  $string = str_replace('#', 'Ñ', $string);
  return $string;
}
function muestraHorAsisa($horAsisa)
{
  $hora = substr($horAsisa, 0, 2);
  $minuto = substr($horAsisa, 2, 2);
  return $hora . ":" . $minuto;
}

function muestraFechAsisa($fechAsisa)
{
  $dia  = substr($fechAsisa, 0, 2);
  $mes  = substr($fechAsisa, 2, 2);
  $anio = substr($fechAsisa, 4, 4);

  return $anio . "-" . $mes . "-" . $dia;
}

function fechAsisa($asisaFecha)
{
  if (strlen($asisaFecha) == 8) {
    //Se corresponde a fecha
    $dia  = substr($asisaFecha, 0, 2);
    $mes  = substr($asisaFecha, 2, 2);
    $anio = substr($asisaFecha, 4, 4);

    return $dia . "-" . $mes . "-" . $anio;
  } elseif (strlen($asisaFecha) == 4) {
    //Se corresponde a hora
    $hora = substr($asisaFecha, 0, 2);
    $min  = substr($asisaFecha, 2, 2);

    return $hora . ":" . $min;
  }
}

function autoAsisa($coDemanda)
{
  $contar    =  strlen($coDemanda);
  $resContar = $contar - 5;
  $orden = $contar - 5;
  $norden    = substr($coDemanda, -5, 1);
  $auto      = substr($coDemanda, 0, $resContar);

  return $auto . "." . $norden;
}

function formatHorAsisa($horaFormato)
{
  //Para horas formato H:i:s
  $datosHora = explode(":", $horaFormato);
  $arregloHora = $datosHora[0] . $datosHora[1];
}
function formatHorAsisaEstado($horaFormatoEstado)
{
  //Para horas formato H:i:s
  $datosHora = explode(":", $horaFormatoEstado);
  $arregloHoraEstado = $datosHora[0] . $datosHora[1];
  return $arregloHoraEstado;
}
function convertPoli($numPoliza)
{
  $forPoli = explode("/", $numPoliza);
  if (isset($forPoli[2])) {
    if (strlen($forPoli[2]) < 2) {
      $benef = "0" . $forPoli[2];
    } else {
      $benef = $forPoli[2];
    }
    $poli = $forPoli[0] . $forPoli[1] . $benef;
  } elseif (isset($forPoli[1]) && !isset($forPoli[2])) {
    if (strlen($forPoli[0]) < 3) {
      if (strlen($forPoli[0]) < 2) {
        $parte2 = "0" . $forPoli[0];
      }
    } else {
      $parte1 = $forPoli[0];
    }
    if (strlen($forPoli[1]) < 3) {
      if (strlen($forPoli[1]) < 2) {
        $parte2 = "0" . $forPoli[1];
      }
    } else {
      $parte2 = $forPoli[1];
    }
    $poli = $parte1 . $parte2;
  } elseif (!isset($forPoli[1]) && !isset($forPoli[2])) {
    $poli = $forPoli[0];
  }

  return $poli;
}
# Horas para notificaciones de Asisa
function horaManualEncamino($fechaDada)
{
  $fechAgr    = $fechaDada . ":00";
  $fecha      = date($fechAgr);
  $nuevafecha = strtotime('-30 minute', strtotime($fecha));
  $nuevafecha = date('Hi', $nuevafecha);

  return $nuevafecha; // cambiado echo por return OK
}
function horaEstadoEncamino($fechaEstado) {
	$fecha = date($fechaEstado);
	$nuevafecha = strtotime ( '-32 minute' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Hi' , $nuevafecha );
	
	return $nuevafecha; // cambiado echo por return OK
}

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
	return $nuevafecha; // cambiado echo por return OK
}
/*
function horaDBEncamino($EstadoEncamino) {
	$fecha = date($EstadoEncamino);
	$nuevafecha = strtotime ( '-32 minute' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Hi' , $nuevafecha );
	
	return $nuevafecha;
}
*/
function horaDBEncamino($ahora, $activacion, $valorHora)
{
  //Compara las fechas para saber si el servicio es hoy
  $actAsisa  = explode(" ", $activacion);
  $fechAsisa = $actAsisa[0];
  $horAsisa  = $actAsisa[1];
  $sepAhora  = explode(" ", $ahora);
  $fechAhora = $sepAhora[0];
  $horAhora  = $sepAhora[1];

  $horaDada  = $valorHora . ":00";
  $horaRest  = date($horaDada);
  $nuevaHora = strtotime('-30 minute', strtotime($horaRest));
  $nuevaHora = date('H:i:s', $nuevaHora);

  # Si la hora resultante es menor a la activación se ha de sumar 2 min a la hora de activación
  $hAsisaSum = date($horAsisa);
  $hasisafor = strtotime('+2 minute', strtotime($hAsisaSum));
  $hasisafor = date('H:i:s', $hasisafor);

  if ($fechAsisa == $fechAhora) {
    $fechaHoy = 1;
  } else {
    $fechaHoy = 0;
  }

  //echo "hora dada -30min ".$nuevaHora."<br />";
  //echo "Hora de activacion ".$horAsisa."<br />";

  //Comprueba el horario de activación si el día es igual
  if ($fechaHoy == 1) {
    //Comprueba si la hora resultante es menor que la hora de activación
    if ($nuevaHora < $horAsisa) {
      //Comprueba la hora de suma + 2 y el valor dado
      if ($hasisafor > $horaDada) {
        # Si la hora resultante es > la hora de encamino sera la hora de activación
        $horAsisa = explode(":", $horAsisa);
        $horaEstado = $horAsisa[0] . $horAsisa[1];
        return $horaEstado;
      } else {
        $hasisafor = explode(":", $hasisafor);
        $horaEstado = $hasisafor[0] . $hasisafor[1];
        return $horaEstado;
      }
    } else {
      $nuevaHora = explode(":", $nuevaHora);
      $horaEstado = $nuevaHora[0] . $nuevaHora[1];
      return $horaEstado;
    }
  } else {
    //Cuando la fecha es mayor o menor, no hace las comprobaciones
    $nuevaHora = explode(":", $nuevaHora);
    $horaEstado = $nuevaHora[0] . $nuevaHora[1];
    return $horaEstado;
  }
}
# Arreglo fecha timestamp para asisa
function arregloTimeStamp($fechaRecep, $horaRecep)
{
  $dia   = substr($fechaRecep, 0, 2);
  $mes   = substr($fechaRecep, 2, 2);
  $anio  = substr($fechaRecep, 4, 7);
  $hora  = substr($horaRecep, 0, 2);
  $min   = substr($horaRecep, 2, 2);

  $arregloFecha = $anio . "-" . $mes . "-" . $dia;
  $arregloHora  = $hora . ":" . $min . ":00";

  $arreglo = $arregloFecha . " " . $arregloHora;
  return $arreglo;
}
function obtenerHoraActivacion($fechaCambiar)
{
  $obtHora = explode(" ", $fechaCambiar);
  $horaCompleta = $obtHora[1];
  $forHora = explode(":", $horaCompleta);
  $horaActivacion = $forHora[0] . $forHora[1];

  return $horaActivacion;
}
# Estados para notificaciones de Asisa
function estaNotiAsisa($estadoNoti)
{
  if ($estadoNoti == 2) {
    $estText = "Solicitado";
  } elseif ($estadoNoti == 3) {
    $estText = "Activado";
  } elseif ($estadoNoti == 4) {
    $estText = "En Origen";
  } elseif ($estadoNoti == 5) {
    $estText = "Finalizado";
  } elseif ($estadoNoti == 6) {
    $estText = "Cancelado";
  } elseif ($estadoNoti == 7) {
    $estText = "Anulado";
  } else {
    $estText = "Estado no reconocido";
  }
  return $estText;
}
# 
function mostHoraVtAsisa($codigoAsisa, $fechaRealizacion)
{
  global $gestambu;

  $sqlMost = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado 
		FROM asisaasistencia 
		WHERE cod_demanda ='$codigoAsisa' AND fecha_asistencia='$fechaRealizacion' AND vuelta = 'S' 
		GROUP BY fecha_asistencia
		");
  $rwFila = mysqli_fetch_assoc($sqlMost);
  $valorVuelta = $rwFila['hora_asistencia'];

  return $valorVuelta;
}

function cModi($campo1, $campo2)
{
  if ($campo1 == $campo2) {
    $modif = "";
  } elseif (empty($campo1) && empty($campo2)) {
    $modif = "";
  } else {
    $modif = "modificado";
    $actMod = 1;
  }
  echo $modif;
}
function cModiDos($campo1, $campo2)
{
  if ($campo1 == $campo2) {
    $actMod = 0;
  } else {
    $actMod = 1;
  }
  return $actMod;
}

/* Varlor ida y vuelta */
function valorIdVta($cIdvta, $ida, $vta)
{
  if (isset($cIdvta)) {
    $cIdvta = $cIdvta;
  } else {
    $cIdvta = "";
  }
  if (isset($ida)) {
    $ida = $ida;
  } else {
    $ida = "";
  }
  if (isset($vta)) {
    $vta = $vta;
  } else {
    $vta = "";
  }
  if (empty($cIdvta) && empty($ida) && empty($vta)) {
    $idvta = "";
  } elseif (empty($cIdvta) && empty($ida) && $vta == 3) {
    $idvta = "3";
  } elseif (empty($cIdvta) && $ida == 2 && empty($vta)) {
    $idvta = "2";
  } elseif (empty($cIdvta) && $ida == 2 && $vta == 3) {
    $idvta = "1";
  } elseif ($cIdvta == 1 && $ida == 2 && $vta == 3) {
    $idvta = "1";
  } elseif ($cIdvta == 1 && $ida == 2 && empty($vta)) {
    $idvta = "1";
  } elseif ($cIdvta == 1 && empty($ida) && empty($vta)) {
    $idvta = "1";
  } else {
    $idvta = "";
  }
  return $idvta;
}
/* No mostrar campo para editar Index */
function noCampo($verCampo)
{
  if ($verCampo == 1 || $verCampo == 11 || $verCampo == 14 || $verCampo == 15 || $verCampo == 23) {
    $sinContenido = 1;
  } else {
    $sinContenido = 0;
  }
  return $sinContenido;
}

/* Muestra iconos según el estado del técnico */
function icoEsTec($tecIda, $tecVta, $esTab, $estIdVta)
{
  # $estIdVta: 1, 2, 3 y vacío
  # estado: 1 - admite estado de ida y vuelta
  # $esTab: comprueba cuando el servicio está adjudicado únicamente
  # comprobar el estado de tabla para saber si nos encontros en la ida o en la vuelta
  # estado vacío y estado 2 : para ida
  # estado 3: vuelta
  # Estados de técnico: 6-Recibido 7-En camino 9-Pdt. confirmar 12-En destino 13-Fin trayecto 18-En movimiento
  if ($estIdVta == 1) {
    if ($esTab == 2) { // ida adjudicada
      $verEstado = $tecIda;
    } elseif ($esTab == 5) { // vuelta adjudicada
      $verEstado = $tecVta;
    } else {
      $verEstado = "";
    }
  } elseif ($estIdVta == 3) { // sólo vuelta
    $verEstado = $tecVta;
  } elseif ($estIdVta == 2 || empty($estIdVta)) {
    $verEstado = $tecIda;
  } else {
    $verEstado = "";
  }
  if ($verEstado == 6) {
    $icoEstado = "circle";
  } elseif ($verEstado == 7) {
    $icoEstado = "location-arrow";
  } elseif ($verEstado == 9) {
    $icoEstado = "hourglass-half";
  } elseif ($verEstado == 12) {
    $icoEstado = "home";
  } elseif ($verEstado == 13) {
    $icoEstado = "check";
  } elseif ($verEstado == 18) {
    $icoEstado = "sign-out";
  } else {
    $icoEstado = "ambulance";
  }
  return $icoEstado;
}
/* Funcciones para tec */
function mostCate($valorCate)
{
  if ($valorCate == 1) {
    $texCate = "Técnico";
  } elseif ($valorCate == 2) {
    $texCate = "Ayudante";
  } elseif ($valorCate == 3) {
    $texCate = "Enfermero";
  } elseif ($valorCate == 4) {
    $texCate = "Médico";
  } else {
    $texCate = "";
  }
  return $texCate;
}

function mostValorCate($mostCt)
{
  global $gestambu;

  $sqlMost = mysqli_query($gestambu, "SELECT idCate, cate FROM cate WHERE idCate = '$mostCt'");
  $rwMost  = mysqli_fetch_assoc($sqlMost);

  echo $rwMost['cate'];
}

function mostTick($valorTick)
{
  if ($valorTick == '1') {
    //echo "<i class=\"fa fa-check\"></i>";
    echo "SI";
  } else {
    echo "";
  }
}
function mostTickIco($valorTickIco)
{
  if ($valorTickIco == '1') {
    echo "<i class=\"fa fa-check\"></i>";
  } else {
    echo "";
  }
}

function colorFondoCont($suspenso, $ultima)
{
  if ($suspenso == '16') {
    echo "success";
  } else {
    if ($ultima == '1') {
      echo "warning";
    } else {
      echo "info";
    }
  }
}
# Comprobar si el servicio tiene incidencia
function compInci($idInci)
{
  global $gestambu;
  $compInci = mysqli_query($gestambu, "SELECT idSv FROM incidencia WHERE idSV = '$idInci'");
  $numInci = mysqli_num_rows($compInci);
  if ($numInci > 0) {
    $inComp = "fa-exclamation-triangle";
    echo $inComp;
  } else {
    $inComp = "fa-pencil-square-o";
    echo $inComp;
  }
}

function console_log($output, $with_script_tags = true)
{
  $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
    ');';
  if ($with_script_tags) {
    $js_code = '<script>' . $js_code . '</script>';
  }
  echo $js_code;
}
