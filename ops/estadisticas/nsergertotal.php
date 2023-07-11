<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../../functions/function.php';
nonUser();

if(isset($_POST['fecha_ini'])) {
  $fini = $_POST['fecha_ini'];
} else {
  $fini = date("Y-m-d");
}

if(isset($_POST['fecha_fin'])) {
  $ffin = $_POST['fecha_fin'];
} else {
  $ffin = date("Y-m-d");
}

if(isset($_POST['ciaNomSel'])) {
  $cia = $_POST['ciaNomSel'];
} else {
  $cia = "0";
}

if(isset($_POST['provincia'])) {
  $prov = $_POST['provincia'];
} else {
  $prov = "29";
}
if($cia == '0') {	
	$nser = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.fecha, servicio.provincia, servicio.estServ FROM servicio WHERE servicio.fecha BETWEEN '$fini' AND '$ffin' AND servicio.provincia ='$prov' AND servicio.estServ !='15'");
	$row_nser = mysqli_fetch_assoc($nser);
	$totalRows_nser = mysqli_num_rows($nser);

	$nser_ca = mysqli_query($gestambu,"SELECT servicio.idCia, servicio.fecha, servicio.provincia, servicio.estServ, servicio.locRec, servicio.recurso, servicio.enfermero, servicio.medico, 
		SUM(IF(servicio.tipo='1', 1, 0)) URG, 
		SUM(IF(servicio.recurso='3', 1, 0)) UVI, 
		SUM(IF(servicio.tipo='1', 1, 0) AND (servicio.enfermero='1' AND servicio.medico !='1')) ADUE,  
		SUM(IF(servicio.tipo ='3', 1, 0)) CONSU, 
		SUM(IF(servicio.tipo ='7', 1, 0) AND (servicio.recurso !='7')) RHB, 
		SUM(IF(servicio.tipo ='8', 1, 0)) LOGO, 
		SUM(IF(servicio.tipo ='5', 1, 0)) DIAL, 
		SUM(IF(servicio.tipo ='6', 1, 0)) RADIO, 
		SUM(IF(servicio.tipo ='11', 1, 0)) ALTA, 
		SUM(IF(servicio.tipo ='14', 1, 0)) INGRE, 
		SUM(IF(servicio.tipo !='1', 1, 0) AND (servicio.tipo NOT IN(2,4,9,15,16,17,18,19,20,21,22,23,25) AND (servicio.recurso !='7'))) PROG, 
		SUM(IF(servicio.tipo ='10', 1, 0)) SECU, 
		SUM(IF(servicio.tipo ='17', 1, 0)) CTLF, 
		SUM(IF(servicio.tipo IN(4,20,21,22), 1, 0)) ENFE, 
		SUM(IF(servicio.tipo ='2', 1, 0)) V_M, 
		SUM(IF(servicio.tipo ='9', 1, 0)) SEG,
		SUM(IF(servicio.recurso='7', 1,0)) RUTA		
	FROM servicio WHERE servicio.fecha BETWEEN '$fini' AND '$ffin' AND servicio.provincia ='$prov' AND servicio.estServ !='15'");
	
	$row_nser_ca = mysqli_fetch_assoc($nser_ca);
	$totalRows_nser_ca = mysqli_num_rows($nser_ca);


	$list_ciaNom = mysqli_query($gestambu, "SELECT cia.idCia, cia.ciaNom FROM cia ORDER BY cia.ciaNom ASC");

} else {
	$nser = mysqli_query($gestambu, "SELECT servicio.idCia, servicio.fecha, servicio.provincia, servicio.estServ FROM servicio WHERE servicio.idCia ='$cia' AND servicio.fecha BETWEEN '$fini' AND '$ffin' AND servicio.provincia ='$prov' AND servicio.estServ !='15'");
	$row_nser = mysqli_fetch_assoc($nser);
	$totalRows_nser = mysqli_num_rows($nser);

	$nser_ca = mysqli_query($gestambu,"SELECT servicio.idCia, servicio.fecha, servicio.provincia, servicio.estServ, servicio.locRec, servicio.recurso, servicio.enfermero, 
		SUM(IF(servicio.tipo='1', 1, 0)) URG, 
		SUM(IF(servicio.recurso='3', 1, 0)) UVI, 
		SUM(IF(servicio.tipo='1', 1, 0) AND (servicio.enfermero='1') AND (servicio.medico !='1')) ADUE,  
		SUM(IF(servicio.tipo ='3', 1, 0)) CONSU, 
		SUM(IF(servicio.tipo ='7', 1, 0)  AND (servicio.recurso !='7')) RHB, 
		SUM(IF(servicio.tipo ='8', 1, 0)) LOGO, 
		SUM(IF(servicio.tipo ='5', 1, 0)) DIAL, 
		SUM(IF(servicio.tipo ='6', 1, 0)) RADIO, 
		SUM(IF(servicio.tipo ='11', 1, 0)) ALTA, 
		SUM(IF(servicio.tipo ='14', 1, 0)) INGRE, 
		SUM(IF(servicio.tipo !='1', 1, 0) AND (servicio.tipo NOT IN(2,4,9,15,16,17,18,19,20,21,22,23,25))) PROG, 
		SUM(IF(servicio.tipo ='10', 1, 0)) SECU, 
		SUM(IF(servicio.tipo ='17', 1, 0)) CTLF, 
		SUM(IF(servicio.tipo IN(4,20,21,22), 1, 0)) ENFE, 
		SUM(IF(servicio.tipo ='2', 1, 0)) V_M, 
		SUM(IF(servicio.tipo ='9', 1, 0)) SEG,
		SUM(IF(servicio.recurso='7', 1,0)) RUTA
	FROM servicio WHERE servicio.idCia ='$cia' AND servicio.fecha BETWEEN '$fini' AND '$ffin' AND servicio.provincia ='$prov' AND servicio.estServ !='15'");
	
	$row_nser_ca = mysqli_fetch_assoc($nser_ca);
	$totalRows_nser_ca = mysqli_num_rows($nser_ca);


	$list_ciaNom = mysqli_query($gestambu, "SELECT cia.idCia, cia.ciaNom FROM cia ORDER BY cia.ciaNom ASC");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos/nserv.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="pag">
    <div id="selector">
        <form name="filtrado" class="inte" action="" method="post">
        <label>Fecha inicio: </label>
        <input name="fecha_ini" class="fecha" type="date" value="<?php if (isset($_POST['fecha_ini'])) { echo $_POST['fecha_ini']; } ?>"/>
        <label>Fecha fin: </label>
        <input name="fecha_fin" class="fecha" type="date" value="<?php if (isset($_POST['fecha_fin'])) { echo $_POST['fecha_fin']; } ?>" />
        <?php @$ciaNom2 = $_POST['ciaNom'];?>
        <select name="ciaNomSel">
          <option value ="0">-- Todas -- </option>
          <?php
			while($rCia = mysqli_fetch_assoc($list_ciaNom)) {
			if($_POST['ciaNomSel'] == $rCia['idCia']) {
			  $seleccion = "selected";
			} else {
			  $seleccion = "";
			}
			echo "<option value='".$rCia['idCia']."' ".$seleccion.">".$rCia['ciaNom']."</option>\n";
			}
            ?>
        </select>
        <label>Provincia:</label>
        <select name="provincia">
          <option value="11" <?php if($prov == '11') {echo "selected=\"selected\"";} ?>>Cádiz</option>
		  <option value="14" <?php if($prov == '11') {echo "selected=\"selected\"";} ?>>Córodoba</option>		  
          <option value="29" <?php if($prov == '29') {echo "selected=\"selected\"";} ?>>Málaga</option>
		  <option value="52" <?php if($prov == '52') {echo "selected=\"selected\"";} ?>>Melilla</option>
		  <option value="21" <?php if($prov == '21') {echo "selected=\"selected\"";} ?>>Huelva</option>		  
          <option value="41" <?php if($prov == '41') {echo "selected=\"selected\"";} ?>>Sevilla</option>
        </select>
        <input name="consultar" type="submit" value="Consultar" />                                         
        </form>
    </div>
    	<div id="titu">
        	<h1>Servicios Totales <?php echo provValor($prov); ?></h1>
            <h3></h3>
        </div>
        <div id="cadiz">
        	<h4>
			<?php 
				if($_POST['ciaNomSel'] == 0) {
					echo "Todas"; 
				} else {
					echo mostrarCia($_POST['ciaNomSel']);
					}		
			?>
			</h4>
            <div class="port">
            	<div class="total portt">
                	<div class="ptitu">
                    Totales
                    </div>
                	<div class="stitu">
                    Servicios
                    </div> 
                    <div class="canti">
                    <?php echo $row_nser_ca['URG'] + $row_nser_ca['PROG']  ?>
                    </div>                   
                </div>
                <div class="urg portt">
                	<div class="ptitu">
                    Urgencias
                    </div>
                	<div class="stitu">
                    Servicios
                    </div>
                    <div class="canti">
                    <?php echo $row_nser_ca['URG']; ?>
                    </div>                                         
                </div>
                <div class="prog portt">
                	<div class="ptitu">
                    Programados
                    </div>
                	<div class="stitu">
                    Servicios
                    </div> 
                    <div class="canti">
                    <?php echo $row_nser_ca['PROG']; ?>
                    </div>                                        
                </div>
            </div>
            <div class="space"></div>
            <div class="portdes">
                <div class="desprog">
                	<div class="tprog">
                    	<h3>Programados</h3>
                        <span>Desglose</span>
                    </div>
                    <div class="ctprog">
                    	<div class="tbprog">           
                            <table class="inte" width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="75%">Consultas</td>
                                <td width="25%"><?php echo $row_nser_ca['CONSU']; ?></td>
                              </tr>
                              <tr>
                                <td>Rehabilitación</td>
                                <td><?php echo $row_nser_ca['RHB']; ?></td>
                              </tr>
                              <tr>
                                <td>Logopeda</td>
                                <td><?php echo $row_nser_ca['LOGO']; ?></td>
                              </tr>
                              <tr>
                                <td>Diálisis</td>
                                <td><?php echo $row_nser_ca['DIAL']; ?></td>
                              </tr>
                              <tr>
                                <td>Radioterápia</td>
                                <td><?php echo $row_nser_ca['RADIO']; ?></td>
                              </tr>
                              <tr>
                                <td>Alta</td>
                                <td><?php echo $row_nser_ca['ALTA']; ?></td>
                              </tr>
                              <tr>
                                <td>Ingreso</td>
                                <td><?php echo $row_nser_ca['INGRE']; ?></td>
                              </tr>
                              <tr>
                                <td>Hospitalarios</td>
                                <td><?php echo $row_nser_ca['SECU']; ?></td>
                              </tr>
                              <tr>
                                <td>Ruta</td>
                                <td><?php echo $row_nser_ca['RUTA']; ?></td>
                              </tr>                               
                            </table>
                        </div>
                    </div>
                </div>            	
                <div class="desurg">
                	<div class="turg">
                    	<h3>Urgencias</h3>
                        <span>Desglose</span>
                    </div>
                    <div class="tburg">
                    <table class="inte" width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>UVI</td>
                        <td><?php echo $row_nser_ca['UVI']; ?></td>
                      </tr>
                      <tr>
                        <td>Amb + Due</td>
                        <td><?php echo $row_nser_ca['ADUE']; ?></td>
                      </tr>
                      <tr>
                        <td>Convencional</td>
                        <td><?php echo $row_nser_ca['URG']-($row_nser_ca['UVI']+$row_nser_ca['ADUE']); ?></td>
                      </tr>
                    </table>
                    </div>
                </div>
                <div class="desprog">
                	<div class="tprog">
                    	<h3>Domiciliarios</h3>
                        <span>Desglose</span>
                    </div>
                    <div class="ctprog">
                    	<div class="tbprog">           
                            <table class="inte" width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="75%">C_Tlf</td>
                                <td width="25%"><?php echo $row_nser_ca['CTLF']; ?></td>
                              </tr>
                              <tr>
                                <td>Visitas médicas</td>
                                <td><?php echo $row_nser_ca['V_M']; ?></td>
                              </tr>
                              <tr>
                                <td>Seg. Médico</td>
                                <td><?php echo $row_nser_ca['SEG']; ?></td>
                              </tr>
                              <tr>
                                <td>Enfermería</td>
                                <td><?php echo $row_nser_ca['ENFE']; ?></td>
                              </tr>
                            </table>
                        </div>
                    </div>
                </div>			
            </div>
        </div>       
    </div>
</body>
</html>
<?php
mysqli_free_result($nser);

mysqli_free_result($nser_ca);

//mysqli_free_result($rad);

mysqli_free_result($list_ciaNom);

?>
