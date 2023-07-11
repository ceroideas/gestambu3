<?php
include 'function.php';

// Motrar todos los errores de PHP
error_reporting(-1);

// No mostrar los errores de PHP
error_reporting(0);

// Motrar todos los errores de PHP
error_reporting(E_ALL);

// Motrar todos los errores de PHP
ini_set('error_reporting', E_ALL);

if(@$_POST['enviar'] == 'Importar') {
	//obtenemos el archivo .csv
	$tipo = $_FILES['archivo']['type'];

	$tamanio = $_FILES['archivo']['size'];

	$archivotmp = $_FILES['archivo']['tmp_name'];

	//cargamos el archivo
	$lineas = file($archivotmp);

	//inicializamos variable a 0, esto nos ayudará a indicarle que no lea la primera línea
	$i=0;

	//Recorremos el bucle para leer línea por línea
	foreach ($lineas as $linea_num => $linea)
	{
	   //abrimos bucle
	   /*si es diferente a 0 significa que no se encuentra en la primera línea
	   (con los títulos de las columnas) y por lo tanto puede leerla*/
	   if($i != 0)
	   {
		   //abrimos condición, solo entrará en la condición a partir de la segunda pasada del bucle.
		   /* La funcion explode nos ayuda a delimitar los campos, por lo tanto irá
		   leyendo hasta que encuentre un ; */
		   $datos = explode(";",$linea);

		   //Almacenamos los datos que vamos leyendo en una variable
		   $idCia = trim($datos[0]);
		   $pacDNI = trim($datos[1]);
		   $pNombre = trim($datos[2]);
		   $pApellidos = trim($datos[3]);
		   $edad = trim($datos[4]);
		   $sexo = trim($datos[5]);
		   $poliza = trim($datos[6]);
		   $autorizacion = trim($datos[7]);
		   $delegacion= trim($datos[8]);
		   $tlf1 = trim($datos[9]);
		   $tlf2 = trim($datos[10]);
		   $obs = trim($datos[11]);
		   $direccion = trim($datos[12]);
		   $localidad = trim($datos[13]);
		   $provincia = trim($datos[14]);
		   $segMed = trim($datos[15]);
		   $tipoSeg = trim($datos[16]);
		   $medAsig = trim($datos[17]);
		   $pauta = trim($datos[18]);
		   $fallecido = trim($datos[19]);
		   
		   //guardamos en base de datos la línea leida
		   $sqlIn = "INSERT INTO paciente(idCia, pacDNI, pNombre, pApellidos, edad, sexo, poliza, autorizacion, delegacion, tlf1, tlf2, obs, direccion, localidad, provincia, segMed, tipoSeg, medAsig, pauta, fallecido)
			VALUES('$idCia', '$pacDNI', '$pNombre', '$pApellidos', '$edad', '$sexo', '$poliza', '$autorizacion', '$delegacion', '$tlf1', '$tlf2', '$obs', '$direccion', '$localidad', '$provincia', '$segMed', '$tipoSeg', '$medAsig', '$pauta', '$fallecido')";
			if(mysqli_query($gestambu, $sqlIn)) {
				$dt = '1';
			} else {
			  echo "Error: " . $sqlIn . "<br>" . mysqli_error($gestambu);
			}
		   /*
			echo "Aseg: ".$ciaNom."<br/>";
			echo "Tlf: ".$ciaTlf."<br/>";
			echo "mail: ".$ciaTlf."<br/>";
			*/
		   //cerramos condición
	   }

	   /*Cuando pase la primera pasada se incrementará nuestro valor y a la siguiente pasada ya
	   entraremos en la condición, de esta manera conseguimos que no lea la primera línea.*/
	   $i++;
	   //cerramos bucle
	}
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gestambu 3.0 | Incio de sesión</title>
  <!-- Ancho de pantalla -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/docs/bootstrap/css/bootstrap.min.css">
  <!-- Fuentes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Estilo del tema -->
  <link rel="stylesheet" href="/docs/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/docs/plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Gest</b>Ambu</a> 3.0
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
<form enctype="multipart/form-data" method="post">
   <input id="archivo" accept=".csv" name="archivo" type="file" />
   <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
   <input name="enviar" type="submit" value="Importar" />
</form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="/docs/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/docs/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/docs/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
<?php mysqli_close($gestambu); ?>
