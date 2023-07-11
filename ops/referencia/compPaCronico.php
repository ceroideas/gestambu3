<?php
# Realiza comprobaciones con los datos facilitados
# Se realizan siempre que el valor del campo se > 3
# No realiza la comprobación con ABONADO o PACIENTE
# datos a comprobar:
# DNI - Póliza - Nombre / apellidos - aseguradora
# No guarda Observaciones
# No guarda paciente cuando es: Festejos, Eventos o Preventivos

/* Pendiente */
# Si el paciente no esta en la tabla de pacientes y el servicio es hospitalario, crearía por ahora un paciente con la dirección del hospital
# ¿Actualizar siempre los datos con una nueva insercción?
# Crear formulario para actualizar datos de ficha de paciente
# Para el calculo de la edad, se tiene que guardar el año de nacimiento (sólamente el año), se comprueba cada vez que se cargue este código y se actualiza si no fuera igual la diferencia de año con la edad

$countPoli = strlen($poliza);
$countApe  = strlen($apellidos);
$countTlf  = strlen($tlf1);
$countDNI  = strlen($DNI);

if($nombre == "ABONADO" || $nombre == "PACIENTE") {
  // No hace busqueda cuando se especifica ABONADO o PACIENTE
  $compPac = 0;
} elseif($tipo == '15' || $tipo == '18' || $tipo == '23' ) {
  // No hace busqueda cuando se especifica tipo: FESTEJOS, EVENTOS, PREVENTIVO
  $compPac = 0;
} else {
  if($countDNI > 3) {
    //Si DNI tiene valor comprueba en la DB si existe correspondencia
    $compDNI = mysqli_query($gestambu, "SELECT pacDNI, idPac FROM paciente WHERE pacDNI LIKE '%$DNI%' ");
    $rwDNI = mysqli_fetch_assoc($compDNI);
    $numDNI = mysqli_num_rows($compDNI);
    //Si al comprobar el valor de registros es 1 devuelve idpaciente sino da 0
    if($numDNI == 1) {
      $okDNI = 1;
      $pacienteID = $rwDNI['idPac'];
    } else {
      //Si DNI es distinto a 1 tiene que hacer comprobaciones antes de guardar al paciente
      //Comprobación de póliza cuando tiene mas de 3 caractéres
      if($countPoli > 3) {
        $compPoli = mysqli_query($gestambu, "SELECT poliza, idPac FROM paciente WHERE poliza LIKE '%$poliza%' ");
        $rwPoli = mysqli_fetch_assoc($compPoli);
        $numPoli = mysqli_num_rows($compPoli);
        //Si al comprobar el valor de registros es 1 devuelve idpaciente sino da 0
        //Las pólizas pueden ser compartidas, hay que comprobar el nombre y apellidos del paciente
        if($numPoli == 1) {
          //Se ha de hacer 3 comprobaciones más para ver si el paciente se corresponde - nombre, apellidos y compañía
          $compPac = mysqli_query($gestambu, "SELECT poliza, idPac, pNombre, pApellidos, idCia
            FROM paciente
            WHERE poliza LIKE '%$poliza%' AND pApellidos LIKE '%$apellidos%' AND pNombre LIKE '%$nombre%' AND idCia ='$cia'");
          $rwPac = mysqli_fetch_assoc($compPac);
          $numPac = mysqli_num_rows($compPac);
          //Devuelve los valores de comprobación
          if($numPac == 1) {
            //Si el resultado da 1 devuelve el id del paciente
            $okPac = 1;
            $pacienteID = $rwPac['idPac'];
          } else {
            //Sino, no hay concordancia con los datos facilitados
            //crea nuevo paciente en la tabla y devuelve el id
            $okPac = 0;
            //Si no existen apellidos no se creara un registro de paciente en la tabla
            if($countApe == 0) {
              $pacienteID = "";
            } else {
              //Devuelve la variable paciente vacia
              $pacienteID = "";
            }
          }
        } else {
          // $numPoli != 1
          //Se ha de hacer 3 comprobaciones más para ver si el paciente se corresponde - nombre, apellidos y compañía
          $compPac = mysqli_query($gestambu, "SELECT poliza, idPac, pNombre, pApellidos, idCia
            FROM paciente
            WHERE poliza LIKE '%$poliza%' AND pApellidos LIKE '%$apellidos%' AND pNombre LIKE '%$nombre%' AND idCia ='$cia'");
          $rwPac = mysqli_fetch_assoc($compPac);
          $numPac = mysqli_num_rows($compPac);
          //Devuelve los valores de comprobación
          if($numPac == 1) {
            //Si el resultado da 1 devuelve el id del paciente
            $okPac = 1;
            $pacienteID = $rwPac['idPac'];
          } else {
            //Sino, no hay concordancia con los datos facilitados
            //crea nuevo paciente en la tabla y devuelve el id
            $okPac = 0;
            //Si no existen apellidos no se creara un registro de paciente en la tabla
            if($countApe == 0) {
              $pacienteID = "";
            } else {
              //Devuelve la variable paciente vacia
              $pacienteID = "";
            }
          }
        }
      } else {
        // Si póliza no es mayor a 3
        //Se ha de hacer 3 comprobaciones más para ver si el paciente se corresponde - nombre, apellidos y compañía
        $compPac = mysqli_query($gestambu, "SELECT idPac, pNombre, pApellidos, idCia
          FROM paciente
          WHERE pApellidos LIKE '%$apellidos%' AND pNombre LIKE '%$nombre%' AND idCia ='$cia'");
        $rwPac = mysqli_fetch_assoc($compPac);
        $numPac = mysqli_num_rows($compPac);
        //Devuelve los valores de comprobación
        if($numPac == 1) {
          //Si el resultado da 1 devuelve el id del paciente
          $okPac = 1;
          $pacienteID = $rwPac['idPac'];
        } else {
          //Sino, no hay concordancia con los datos facilitados
          //crea nuevo paciente en la tabla y devuelve el id
          $okPac = 0;
          //Si no existen apellidos no se creara un registro de paciente en la tabla
          if($countApe == 0) {
            $pacienteID = "";
          } else {
            //Devuelve la variable paciente vacia
            $pacienteID = "";
          }
        }
      } //Fin $countPoli > 3
    }
  } else {
     // Si DNI < 3
    //Si no existe dni se comprueba con póliza y nombre
    //Primero comprueba si hay datos de póliza
    if($countPoli > 3 ){
      $compPoli = mysqli_query($gestambu, "SELECT poliza, idPac FROM paciente WHERE poliza LIKE '%$poliza%' ");
      $rwPoli = mysqli_fetch_assoc($compPoli);
      $numPoli = mysqli_num_rows($compPoli);
      //Si al comprobar el valor de registros es 1 devuelve idpaciente sino da 0
      //Las pólizas pueden ser compartidas, hay que comprobar el nombre y apellidos del paciente
      if($numPoli == 1) {
        //Se ha de hacer 2 comprobaciones más para ver si el paciente se corresponde
        $compPac = mysqli_query($gestambu, "SELECT poliza, idPac, pNombre, pApellidos, idCia
          FROM paciente
          WHERE poliza LIKE '%$poliza%' AND pApellidos LIKE '%$apellidos%' AND pNombre LIKE '%$nombre%' AND idCia ='$cia'");
        $rwPac = mysqli_fetch_assoc($compPac);
        $numPac = mysqli_num_rows($compPac);
        //Devuelve los valores de comprobación
        if($numPac == 1) {
          //Si el resultado da 1 devuelve el id del paciente
          $okPac = 1;
          $pacienteID = $rwPac['idPac'];
        } else {
          //Sino, no hay concordancia con los datos facilitados
          //crea nuevo paciente en la tabla y devuelve el id
          $okPac = 0;
          //Si no existen apellidos no se creara un registro de paciente en la tabla
          if($countApe == 0) {
            $pacienteID = "";
          } else {
            //Devuelve la variable paciente vacia
            $pacienteID = "";
          }
        }
      } else {
        //Cuando no se encuentra concordancia en póliza
        $compNomAp = mysqli_query($gestambu, "SELECT idPac, pNombre, pApellidos, idCia
          FROM paciente
          WHERE pApellidos LIKE '%$apellidos%' AND pNombre LIKE '%$nombre%' AND idCia ='$cia'");
        $rwCompNomAp = mysqli_fetch_assoc($compNomAp);
        $numCompNomAp = mysqli_num_rows($compNomAp);
        //Si hay concordancia devuelve id del paciente
        if($numCompNomAp == 1) {
          $okPac = 1;
          $pacienteID = $rwCompNomAp['idPac'];
        } else {
          //Si no existe apellidos no crea al paciente
          if($countApe == 0) {
            $pacienteID = "";
          } else {
            //Devuelve la variable paciente vacia
            $pacienteID = "";
          }
        }
      }
    } else {
      //Si no existe póliza
      //Cuando no se encuentra concordancia en póliza
      $compNomAp = mysqli_query($gestambu, "SELECT idPac, pNombre, pApellidos, idCia
        FROM paciente
        WHERE pApellidos LIKE '%$apellidos%' AND pNombre LIKE '%$nombre%' AND idCia ='$cia'");
      $rwCompNomAp = mysqli_fetch_assoc($compNomAp);
      $numCompNomAp = mysqli_num_rows($compNomAp);
      //Si hay concordancia devuelve id del paciente
      if($numCompNomAp == 1) {
        $okPac = 1;
        $pacienteID = $rwCompNomAp['idPac'];
      } else {
        //Si no existe apellidos no crea al paciente
        if($countApe == 0) {
          $pacienteID = "";
        } else {
          //Devuelve la variable paciente vacia
          $pacienteID = "";
        }
      }
    }
  }
}
?>
