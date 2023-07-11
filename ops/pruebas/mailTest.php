<?php
/* PRUEBA PHPMAILER */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../docs/phpmailer/src/Exception.php';
require '../../docs/phpmailer/src/PHPMailer.php';
require '../../docs/phpmailer/src/SMTP.php';
/* ACTIVAR ERRORES */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$email_user 	= "notificaciones@ambuandalucia.es ";
$email_password = "notific77";
$the_subject 	= "Prueba de envío de notificaciones";
$address_to 	= "j.garcia@ambuandalucia.es";
$from_name		= "Notificaciones";

$phpmailer = new PHPMailer();

//Mensaje
$mensaje = "";
//Fin de mensaje

// ---------- datos de la cuenta de Gmail -------------------------------
$phpmailer->Username = $email_user;
$phpmailer->Password = $email_password; 
//-----------------------------------------------------------------------

// $phpmailer->SMTPDebug = 1;
$phpmailer->SMTPSecure = 'tls';
$phpmailer->Host = "smtp.1and1.es"; // GMail
$phpmailer->Port = 587;
$phpmailer->IsSMTP(); // use SMTP
$phpmailer->SMTPAuth = true;
$phpmailer->setFrom($phpmailer->Username,$from_name);
$phpmailer->AddAddress($address_to); // recipients email
$phpmailer->Subject = $the_subject;	
$phpmailer->Body .="<h1 stySSle='color:#3498db;'>Prueba de correo!</h1>";
$phpmailer->Body .= "<p>Mensaje personalizado</p>";
$phpmailer->Body .= "<p>Fecha y Hora: ".date("d-m-Y h:i:s")."</p>";
$phpmailer->IsHTML(true);

$phpmailer->Send();
?>