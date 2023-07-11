<?php
include '../../functions/function.php';

/* Mensajes de demora de tiempo
  # Crea un mensaje cuando no se a adjudicado un servicio
  # No tiene en cuenta la provincia seleccionada, muestra el mensaje para todas
  # No tiene encuenta servicios de enfermería, exceto "extracciones"
  # Tiempos:
  # 5  minutos   -> info
  # 15 minutos   -> success
  # 25 minutos   -> warning
  # > 35 minutos -> danger
*/

$mensajes = mysqli_query($gestambu,
  "SELECT servicio.idSv, servicio.provincia, servicio.tipo, servicio.recurso, servicio.enfermero, servicio.medico, servicio.fecha, servicio.hora, servicio.nombre, servicio.apellidos,  servicio.locRec, servicio.estServ,
    servi.idServi, servi.nomSer, servi.icono, recurso.idRecu, recurso.recuCorto
  FROM servicio
    LEFT JOIN servi ON servicio.tipo = servi.idServi
	LEFT JOIN recurso ON servicio.recurso = recurso.idRecu
  WHERE servicio.fecha = CURDATE()
    AND servicio.estServ = '1'
    AND TIMEDIFF(CURTIME(), servicio.hora) > '00:04:59'
    AND servicio.tipo NOT IN ('4', '20', '21')
  ORDER BY servicio.hora
");
$numMensajes = mysqli_num_rows($mensajes);

  while($rwMensajes = mysqli_fetch_array($mensajes)) {
    if($numMensajes == "0") {
      //Si el número de servicios resultantes es 0 no hay mensajes
      $dif = "00:00:00";
    } else {
      //Si no, definimos hora actual y tomamos la hora de cada servicio
      $horActual = date("H:i:s");
      $horaServ  = $rwMensajes['hora'];

      //Cálculo de resultado positivo o negativo
      if($horActual > $horaServ ) {
        // Si la hora actual es mayor a la del servicio lo registramos como positivo
        // Registramos la diferencia entre horas
        $dif = date("H:i:s", (strtotime("00:00:00") + strtotime($horActual)) - strtotime($horaServ));
        $resultado = 1;
      } elseif($horaServ > $horActual) {
        //Si la hora del servicio es mayor a la actual lo registramos como negativo
        // $dif = date("H:i:s", (strtotime("00:00:00") + strtotime($horActual)) - strtotime($horaServ)); -> valorar si es necesario
        $resultado = 0;
      }

      //Cálculo para etiquetas de mensaje
      if($dif > "00:35:00" & $resultado == '1' ) {
        $alerta = "danger";
        $tagSim = "[4]";
      } elseif($dif > "00:25:00" & $resultado == '1' ) {
        $alerta = "warning";
        $tagSim = "[3]";
      } elseif($dif > "00:15:00" & $resultado == '1' ) {
        $alerta = "success";
        $tagSim = "[2]";
      } elseif($dif > "00:05:00" & $resultado == '1' ) {
        $alerta = "info";
        $tagSim = "[1]";
      } else {
        $aletar = "sin_alerta";
      }

    } //fin else (mensajes)
?>
<div class="alert alert-<?php echo $alerta; ?> alert-dismissible sinmar">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <i class="icon fa fa-<?php echo $rwMensajes['icono']; ?>"></i> <?php echo $tagSim." ".$rwMensajes['hora']; ?> -
  <?php echo ambComple($rwMensajes['recurso'], $rwMensajes['enfermero'], $rwMensajes['medico'], $rwMensajes['recuCorto']); ?>
  <strong><a class="sinlinea" href="/ops/mostrar/editServ.php?iden=<?php echo $rwMensajes['idSv']; ?>"><?php echo $rwMensajes['nombre']." ".$rwMensajes['apellidos']; ?></a></strong>
  <strong> · Provincia: </strong> <?php provValor($rwMensajes['provincia']); ?>
  <strong> · Tipo: </strong> <?php echo $rwMensajes['nomSer']; ?>
  <strong> · Localidad: </strong> <?php echo $rwMensajes['locRec']; ?>
</div>
<?php  } //fin while ?>
