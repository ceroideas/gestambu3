<?php
session_start();
include '../../../functions/function.php';
nonUser();

$numRh = $_GET['numRh'];

$sqlCont = mysqli_query($gestambu, "SELECT servicio.continuado, servicio.poliza, servicio.autorizacion, servicio.idCia, servicio.provincia, servicio.tipo, servicio.recurso, servicio.fecha, servicio.hora, servicio.idvta, servicio.delegacion,
   servicio.medico, servicio.enfermero, servicio.fest, servicio.nombre, servicio.apellidos, servicio.codRuta, servicio.tlf1, servicio.tlf2, servicio.recoger, servicio.locRec, servicio.trasladar, servicio.locTras, servicio.obs, cia.idCia, cia.ciaNom,
   servi.idServi, servi.nomSer, refcontinuado.numCont, refcontinuado.sesiones, refcontinuado.pauta, provincias.id, provincias.provincia AS mostDelg
  FROM servicio
    LEFT JOIN cia ON servicio.idCia = cia.idCia
    LEFT JOIN servi ON servicio.tipo = servi.idServi
    LEFT JOIN refcontinuado ON servicio.continuado = refcontinuado.numCont
    LEFT JOIN provincias ON servicio.delegacion = provincias.id
  WHERE servicio.continuado = '$numRh'
  ");
$rwHoja = mysqli_fetch_assoc($sqlCont);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GestAmbu 3.0 | Continuado - Impreso: <?php  echo $_SESSION['userId']." ".$_SESSION['usNom']."// ".date("d-m-Y H:i:s"); ?></title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="/ops/css/impConti.css">

</head>

<body>
<div class="logo">
	<img src="/ops/img/logo_amba.png" width="150" />
</div>
<div class="en_d">
	<div class="txt_en">Datos de paciente</div>
</div>
<div class="pax">
  <table class="tpax" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" colspan="8" class="tbtxt"><?php echo $rwHoja['ciaNom']." - "; provValor($rwHoja['provincia']); ?></td>
        <td align="center" colspan="2"><?php echo $rwHoja['continuado']; ?> - <strong>Delegación:</strong> <?php if($rwHoja['delegacion']==0) { echo "No especificada"; } else { echo $rwHoja['mostDelg']; } ?></td>
      </tr>
      <tr>
        <td colspan="10" height="30" class="nombre"><?php echo $rwHoja['nombre']." ".$rwHoja['apellidos']; ?></td>
      </tr>
      <tr>
        <td colspan="4" class="tbtxt">POLIZA</td>
        <td colspan="2"><?php echo $rwHoja['poliza']; ?></td>
        <td colspan="2" rowspan="2" align="center" class="hora"><?php echo substr($rwHoja['hora'], 0,5); ?></td>
        <td width="86" class="tbtxt">TLF.</td>
        <td width="139"><?php echo $rwHoja['tlf1']; ?></td>
      </tr>
      <tr>
        <td colspan="4" class="tbtxt">AUTORIZACION</td>
        <td colspan="2"><?php echo $rwHoja['autorizacion']; ?></td>
        <td class="tbtxt">TLF.</td>
        <td><?php echo $rwHoja['tlf2']; ?></td>
      </tr>
      <tr>
        <td width="85" class="tbtxt">IDA/VTA</td>
        <td width="40"><?php if($rwHoja['idvta']==1) { echo "X"; } ?></td>
        <td width="55" class="tbtxt">AMB</td>
        <td width="46">
          <?php
            if($rwHoja['recurso'] == 1 ) {
              echo "X";
            } elseif($rwHoja['recurso'] == 3) {
              echo "UVI";
            }
          ?>
        </td>
        <td colspan="2" class="tbtxt">S_ENFERMERIA

        </td>
        <td width="83"><?php if($rwHoja['recurso']==2) { echo "X"; } ?></td>
        <td width="103" class="tbtxt">V_M</td>
        <td>&nbsp;</td>
        <td><?php echo $rwHoja['nomSer']; ?></td>
      </tr>
      <tr>
        <td colspan="3" class="tbtxt">DUE</td>
        <td><?php if($rwHoja['enfermero']==1) { echo "X"; } ?></td>
        <td width="111" class="tbtxt">MEDICO</td>
        <td width="48"><?php if($rwHoja['medico']==1) { echo "X"; } ?></td>
        <td class="tbtxt">INICIO</td>
        <td><?php echo $rwHoja['fecha']; ?></td>
        <td class="tbtxt fintd">SESIONES</td>
        <td><?php echo $rwHoja['sesiones']; ?><?php if($rwHoja['codRuta'] !='0') { echo "<strong> - RUTA</strong>"; } ?></td>
    </tr>
  </table>
</div>
<div class="espacio"></div>
<div class="en_d">
	<div class="txt_en">Datos de servicio</div>
</div>
<div class="direcc">
  <table class="tpax" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="81%" class="tbtxt">RECOGER</td>
        <td width="19%" class="tbtxt">LOCALIDAD</td>
      </tr>
      <tr>
        <td height="50"><?php echo $rwHoja['recoger']; ?></td>
        <td><?php echo $rwHoja['locRec']; ?></td>
      </tr>
      <tr>
        <td class="tbtxt">TRASLADAR</td>
        <td class="tbtxt">LOCALIDAD</td>
      </tr>
      <tr>
        <td height="50"><?php echo $rwHoja['trasladar']; ?></td>
        <td><?php echo $rwHoja['locTras']; ?></td>
      </tr>
    </table>
</div>
<div class="espacio"></div>
<div class="en_d">
	<div class="txt_en">Otros datos</div>
</div>
<div>
<table class="tnum" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="tbtxt">OBSERVACIONES - <strong>PAUTA:</strong> <?php pautaSesion($rwHoja['pauta']); ?> </td>
  </tr>
  <tr>
    <td align="left" height="100"><?php echo $rwHoja['obs']; ?></td>
  </tr>
</table>

</div>
<div class="espacio"></div>
<div class="en_d">
	<div class="txt_en">Calendario</div>
</div>
<div>
<table class="tnum" width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
		$mes=array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
		for ($i=0; $i<=11; $i++) {
			echo "<tr>";
			echo "<td>$mes[$i]</td>";
				for ($x=1; $x<=31; $x++) {
				echo "<td>$x</td>";
			}
		}
		echo "</tr>";
	?>
</table>

</div>
</body>
</html>
<?php
mysqli_close($gestambu);
?>
