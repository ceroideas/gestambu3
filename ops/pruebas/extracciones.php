<?php
/* ACTIVAR ERRORES */
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../../functions/function.php';
/* Extracciones */

$extraccion = mysqli_query($gestambu, "SELECT provincia, tipo, fecha, provincia, estServ, locRec,
		SUM(IF(tipo= '22' AND provincia ='41' AND locRec = 'SEVILLA', 1, 0)) capSe,
		SUM(IF(tipo= '22' AND provincia ='41' AND locRec != 'SEVILLA', 1, 0)) extSe
	FROM servicio 
	WHERE tipo = '22' AND estServ NOT IN('15', '17', '14') AND provincia = '41'
	GROUP BY fecha
	ORDER BY fecha ASC
	LIMIT 7
	");
$extraccionMa = mysqli_query($gestambu, "SELECT provincia, tipo, fecha, provincia, estServ, locRec,
		SUM(IF(tipo= '22' AND provincia ='29' AND locRec = 'MALAGA', 1, 0)) capMa,
		SUM(IF(tipo= '22' AND provincia ='29' AND locRec != 'MALAGA', 1, 0)) extMa
	FROM servicio 
	WHERE tipo = '22' AND estServ NOT IN('15', '17', '14') AND provincia = '29'
	GROUP BY fecha
	ORDER BY fecha ASC
	LIMIT 7
	");	
?>
		<li class="dropdown">
		  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fa fa-tint"></i> SE <span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
<?php while($rwExt = mysqli_fetch_array($extraccion)) { ?>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#"><?php echo $rwExt['fecha']." ".$rwExt['capSe']." ".$rwExt['extSe']; ?></li>
<?php } ?>
		  </ul>
		</li>
		<li class="dropdown">
		  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fa fa-tint"></i> MA <span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
<?php while($rwExt = mysqli_fetch_array($extraccionMa)) { ?>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#"><?php echo $rwExt['fecha']." ".$rwExt['capMa']." ".$rwExt['extMa']; ?></li>
<?php } ?>
		  </ul>
		</li>		