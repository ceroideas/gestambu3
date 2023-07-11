<?php
session_start();
//Datos para guardar horas
include '../../../functions/function.php';
$jDataText = explode("-", $_POST['id']);

$jCampoText  = $jDataText['0']; //Nombre del campo
$jRhText     = $jDataText['1']; //Id del registro (para este caso se necesita la columna de continuado : rh2017/....)
$textComp    = $jDataText['2']; //Selector para guardar en todos los registros o con restriccion (para los campos de direccion)
$jValorText  = $_POST['value']; // valor por el cual reemplazar

//Actualiza el registro
if($textComp == 'restr') {
	$jAcText = "UPDATE servicio SET  ".$jCampoText." = '".$jValorText."'
								WHERE continuado = '$jRhText' AND estServ NOT IN('10', '14') ";
} elseif($textComp == 'unico') {
	$jAcText = "UPDATE servicio SET  ".$jCampoText." = '".$jValorText."'
								WHERE idSv = '$jRhText' ";
} else {
	$jAcText = "UPDATE servicio SET  ".$jCampoText." = '".$jValorText."'
								WHERE continuado = '$jRhText' ";
}

if(mysqli_query($gestambu,$jAcText)) {
	if($jCampoText == 'provincia') {
		echo provValor($jValorText);
	} elseif($jCampoText == 'estServ') {
		$queryEst = mysqli_query($gestambu, "SELECT idEst, vaEst
		  FROM estados
		  WHERE idEst = '$jValorText'
		");
		$rwQueryEst = mysqli_fetch_assoc($queryEst);
		echo $rwQueryEst['vaEst'];
		
		/* Mensajes de log */
		$obsText = ": ".$jCampoText." - ".$jValorText;
		$usuario = $_SESSION['userId'];
		guardarLog('16', $usuario, $obsText, $jRhText);
	
	} elseif($jCampoText == 'tipo') {
		$querytipo = mysqli_query($gestambu, "SELECT idServi, nomSer
		  FROM servi
		  WHERE idServi = '$jValorText'
		");
		$rwQueryTipo = mysqli_fetch_assoc($querytipo);
		echo $rwQueryTipo['nomSer'];
	} elseif($jCampoText == 'delegacion') {
		$queryDel = mysqli_query($gestambu, "SELECT id, provincia
		  FROM provincias
		  WHERE id = '$jValorText'
		");
		$rwQueryDel = mysqli_fetch_assoc($queryDel);
		echo $rwQueryDel['provincia'];
	} else {
		echo $jValorText;
	}
	
} else {
	echo "Error: " . $jAcText . "<br>" . mysqli_error($gestambu);
}

/* Mensajes de log */
$obsText = $jCampoText." modificado a: ".$jValorText;
$usuario = $_SESSION['userId'];
guardarLogCont('3', $usuario, $obsText, $jRhText);

?>
