<?php
session_start();
include '../../../functions/function.php';

$puntos = explode(',',$_POST['puntos']);
            $consulta = 'UPDATE servicio SET orden = CASE idSv '.PHP_EOL;
            foreach ($puntos as $index => $id){
              $idPunto = explode('-', $id);
              $idPunto = mysqli_real_escape_string($gestambu,$idPunto[1]);
              $orden = mysqli_real_escape_string($gestambu, ($index + 1));
                $consulta .= 'WHEN '.$idPunto.' THEN '.$orden.''.PHP_EOL;
            }
            $consulta .= 'ELSE orden '.PHP_EOL.' END';
            echo $consulta;
            if (mysqli_query($gestambu, $consulta)){
                $devolver = array ('realizado' => true);
			} else {
				$devolver ='na de na';
			}
if ($devolver)
		echo json_encode($devolver);
?>
