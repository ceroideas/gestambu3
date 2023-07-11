<?php
session_start();
include '../../../../functions/function.php';
nonUser();

$km         = trim(mysqli_real_escape_string($gestambu, $_POST['km']));
$usuario    = trim(mysqli_real_escape_string($gestambu, $_POST['user']));
$referencia = trim(mysqli_real_escape_string($gestambu, $_POST['referencia']));
$idvtaKm    = trim(mysqli_real_escape_string($gestambu, $_POST['idvtaKm']));
$matricula  = trim(mysqli_real_escape_string($gestambu, $_POST['matricula']));
$estKm      = trim(mysqli_real_escape_string($gestambu, $_POST['estKm']));
$selector   = trim(mysqli_real_escape_string($gestambu, $_POST['selector']));
$ahora      = date("H:i");
$recuKm     = trim(mysqli_real_escape_string($gestambu, $_POST['recuKm']));

/* Comprobar */
$sqlComp = mysqli_query($gestambu, "SELECT * FROM partrabajo WHERE idSv = '$referencia' AND idvta = '$selector'");
$numComp = mysqli_num_rows($sqlComp);
$rwComp  = mysqli_fetch_assoc($sqlComp);

/*
echo "km:".$km."<br />";
echo "user:".$usuario."<br />";
echo "ref:".$referencia."<br />";
echo "idvta:".$idvtaKm."<br />";
echo "matricula:".$matricula."<br />";
echo "estado:".$estKm."<br />";
echo "selector:".$selector."<br />";

if($numComp == 0 ) {
  $sqlInciKm = "INSERT INTO partrabajo (idSv, idVh, idUser, kmReco, idvta) VALUES ('$referencia', '$matricula', '$usuario', '$km', '2')";
} else {
  $sqlInciKm = "UPDATE partrabajo SET idVh='$matricula', idUser='$usuario', kmReco='$km', idvta='$idvtaKm' WHERE idSv='$referencia' AND idvta='2' ";
}
*/

if(empty($idvtaKm) || $idvtaKm == 2 || $idvtaKm == 0) { // Urgencias, v_m, due...
  if($recuKm == 2 || $recuKm == 4) { // visita medica o due -> guarda km de fin como de llegada
    if($numComp == 0) {
      $sqlInciKm = "INSERT INTO partrabajo (idSv, idVh, idUser, kmReco, kmFin, idvta) VALUES ('$referencia', '$matricula', '$usuario', '$km', '$km', '2')";
    } else {
      $sqlInciKm = "UPDATE partrabajo SET idVh='$matricula', idUser='$usuario', kmReco='$km',  kmFin='$km', idvta='2' WHERE idSv='$referencia' AND idvta='2' ";
    }
  } else {
    if($numComp == 0) {
      $sqlInciKm = "INSERT INTO partrabajo (idSv, idVh, idUser, kmReco, idvta) VALUES ('$referencia', '$matricula', '$usuario', '$km', '2')";
    } else {
      $sqlInciKm = "UPDATE partrabajo SET idVh='$matricula', idUser='$usuario', kmReco='$km', idvta='2' WHERE idSv='$referencia' AND idvta='2' ";
    }
  }
} elseif($idvtaKm == 1) { // Consultas y programados con id/vta
  if($selector == 2) { // Selecciona el servicio de ida-> consta de km recogida y km de fin
    if($numComp == 0 ) {
      $sqlInciKm = "INSERT INTO partrabajo (idSv, idVh, idUser, kmReco, idvta) VALUES ('$referencia', '$matricula', '$usuario', '$km', '2')";
    } else {
      $sqlInciKm = "UPDATE partrabajo SET idVh='$matricula', idUser='$usuario', kmReco='$km', idvta='2' WHERE idSv='$referencia' AND idvta='2' ";
    }
  } elseif($selector == 3) { // Selecciona la vuelta del servicio
    if($numComp == 0 ) {
      $sqlInciKm = "INSERT INTO partrabajo (idSv, idVh, idUser, kmReco, idvta) VALUES ('$referencia', '$matricula', '$usuario', '$km', '3')";
    } else {
      $sqlInciKm = "UPDATE partrabajo SET idVh='$matricula', idUser='$usuario', kmReco='$km', idvta='3' WHERE idSv='$referencia' AND idvta='3' ";
    }
  }
} elseif($idvtaKm == 3) {
  if($numComp == 0 ) {
    $sqlInciKm = "INSERT INTO partrabajo (idSv, idVh, idUser, kmReco, idvta) VALUES ('$referencia', '$matricula', '$usuario', '$km', '3')";
  } else {
    $sqlInciKm = "UPDATE partrabajo SET idVh='$matricula', idUser='$usuario', kmReco='$km', idvta='3' WHERE idSv='$referencia' AND idvta='3' ";
  }
}

if(mysqli_query($gestambu, $sqlInciKm)) {
  if($recuKm == '4' || $recuKm == '2') { //Guarda km de finalizado (visitas médicas o enfermería)
    $sqlFin = mysqli_query($gestambu, "SELECT idPart, idSv, idvta FROM partrabajo WHERE idSv = '$referencia' AND idvta = '2'");
    $rwFin  = mysqli_fetch_assoc($sqlFin);
    $kmActu = $rwFin['idSv'];
    $finUp  = mysqli_query($gestambu, "UPDATE partrabajo SET kmFin='$km' WHERE idSv='$kmActu'"); // Marca los mismos km de inicio o de fin
  }
  echo "<div class=\"bg-yellow\"><div class=\"box-header\"><strong><h5> Km en destino registrados. Puedes cerrar la ventana.</h5></strong></div></div>";
  $regOk   = 1;
  $obsText = "Guardado km en destino ".$km." hora: ".$ahora;
  guardarLog('19', $usuario, $obsText, $referencia);
} else {
  echo "Error: " . $sqlInciKm . "<br>" . mysqli_error($gestambu);
}

?>
