<?php
include '../../functions/function.php';

$idSv     = $_POST['idSv'];
$descInci = $_POST['inci'];
$userInci = $_POST['user'];
$incHora  = date("H:i:s");

$insInciTec = "INSERT INTO incidencia (idSv, incHora, descInci, userInci) VALUES ('$idSv', '$incHora', '$descInci', '$userInci') ";

if(mysqli_query($gestambu,$insInciTec)) {
	echo '  <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-check"></i> <?php echo $mensa; ?>
            - Incidencia registrada - Volver a <a href="/tec/servicios/general.php"><i class="icon fa fa-home"></i> General</a>
          </div>
  ';
} else {
	echo "Error: " . $insInciTec . "<br>" . mysqli_error($gestambu);
}
 ?>
