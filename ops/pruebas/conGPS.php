<?php
////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
//ACCESO: datos necesarios para el acceso

//Usuario y clave
$usuario = 'operaciones';
$clave   = 'coordinacion';

//String concatenado de 'usuario:clave'
$userpass = $usuario.':'.$clave;

//Codifica el usuario y clave en base64
$user_base64 = base64_encode($userpass);



////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
//AUTENTICACION: petición para obtener el token

// Definir los Headers con el TOKEN
$opt = array(
	'http' => array(
		'method' => "GET",
		'header' => array('Authorization: Basic '.$user_base64) 
	)
);

$context = stream_context_create($opt);

//Petición al Web Service y convierte el JSON devuelto en un array
$url       ='http://myoystacar.com:800/api/autenticacion/token';
$json      = file_get_contents($url, false, $context);
$respuesta = json_decode($json,true);

// Codifica el token recibido a base64 añadiendole ':' al final
$token64 = base64_encode($respuesta['token'].':');



////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
//GET_ALL_VEHICULOS: obtener los vehículos

// Definir los Headers con el TOKEN64
$opt = array(
	'http' => array(
		'method' => "GET",
		'header' => array("Authorization: Basic ".$token64)
	) 
);


$context = stream_context_create($opt);

// Petición al Web Service y convierte el JSON devuelto en un array
$url       ='http://myoystacar.com:800/api/usuario/get_all_vehiculos';
$json      = file_get_contents($url,false,$context);
$respuesta = json_decode($json,true);

// Ejemplo de mostrar el primer ID de vehiculo 
print_r ($respuesta)."<br /> ";

echo __LINE__;
?>