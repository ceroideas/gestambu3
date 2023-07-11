<?php
session_start();
include '../../functions/function.php';
nonUser();

$selector    = trim(mysqli_real_escape_string($gestambu, $_POST['selector']));
$diagnostico = trim(mysqli_real_escape_string($gestambu, $_POST['diagnostico']));

/* Comprobar */

$sqlComp = mysqli_query($gestambu, "SELECT idSv FROM especial WHERE idSv = '$selector'");
$numComp = mysqli_num_rows($sqlComp);


if($numComp == 0) {
  $sqlDiag = "INSERT INTO especial (idSv, diagnostico) VALUES ('$selector', '$diagnostico')";
} else {
  $sqlDiag = "UPDATE especial SET diagnostico='$diagnostico' WHERE idSv='$selector'";
}
	echo $diagnostico."<br />";
  # Guarda diagnóstigo
	$lstDiag = mysqli_query($gestambu, "SELECT codigo, denominacion FROM cie10 WHERE codigo = '$diagnostico'");
	$rwDiag  = mysqli_fetch_assoc($lstDiag);
	$obsText = $rwDiag['denominacion'];
	$usuario = $_SESSION['userId'];
	$servicioID = $selector;
	guardarLog('26', $usuario, $obsText, $servicioID);

if(mysqli_query($gestambu, $sqlDiag)) {
  //echo "<div class=\"bg-yellow\"><div class=\"box-header\"><strong><h4> Se ha grabado con éxito el diagnóstico</h4></strong></div></div>";
  echo '  <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
            - Volver a <a href="/tec/servicios/general.php"><i class="icon fa fa-home"></i> General</a>
          </div>
  ';

} else {
  echo "Error: " . $sqlDiag . "<br>" . mysqli_error($gestambu);
}

?>
