<?php
include '../../functions/function.php';
$diaIni = "2018-01-15";
$diaFin = "2018-01-16";
/* Relevo mantener */
$incMant = mysqli_query($gestambu, "SELECT relevo.idRel, relevo.userId, relevo.horaRel, relevo.textoRel, relevo.enviado, relevo.tipo, relevo.estRel, relevo.modificado, user.userId, user.usNom, user.usApe
  FROM relevo
    LEFT JOIN user ON relevo.userId = user.userId
  WHERE relevo.estRel = '1' AND relevo.tipo = '3'
  ORDER BY relevo.enviado, relevo.horaRel ASC
  ");

/* Relevo diario */
$inciSv = mysqli_query($gestambu, "SELECT relevo.idRel, relevo.userId, relevo.horaRel, relevo.textoRel, relevo.enviado, relevo.tipo, relevo.estRel, relevo.modificado, user.userId, user.usNom, user.usApe
  FROM relevo
    LEFT JOIN user ON relevo.userId = user.userId
  WHERE relevo.enviado BETWEEN '$diaIni 08:00:00' AND '$diaFin 07:59:59' AND relevo.estRel = '1' AND relevo.tipo IN('1', '2')
  ORDER BY relevo.enviado, relevo.horaRel ASC
  ");

/* Entrada de servicios con Incidencias */
$inciExp = mysqli_query($gestambu, "SELECT incidencia.idInci, incidencia.idSv, incidencia.incHora, incidencia.descInci, incidencia.userInci, incidencia.motivoInci, incidencia.enviaInci, user.userId, user.usNom, user.usApe,
    servicio.idSv, servicio.nombre, servicio.apellidos, servicio.tipo, servi.idServi, servi.nomSer
  FROM incidencia
    LEFT JOIN user ON incidencia.userInci = user.userId
    LEFT JOIN servicio ON incidencia.idSv = servicio.idSv
    LEFT JOIN servi ON servicio.tipo = servi.idServi
  WHERE incidencia.enviaInci BETWEEN '$diaIni 08:00:00' AND '$diaFin 07:59:59'
  ORDER BY incidencia.enviaInci ASC
  ");
?>
<html lang="es">

<body>
<table>
  <tr>
    <th>Company</th>
    <th>Contact</th>
    <th>Country</th>
  </tr>
  <tr>
    <td>Alfreds Futterkiste</td>
    <td>Maria Anders</td>
    <td>Germany</td>
  </tr>
  <tr>
    <td>Centro comercial Moctezuma</td>
    <td>Francisco Chang</td>
    <td>Mexico</td>
  </tr>
  <tr>
    <td>Ernst Handel</td>
    <td>Roland Mendel</td>
    <td>Austria</td>
  </tr>
  <tr>
    <td>Island Trading</td>
    <td>Helen Bennett</td>
    <td>UK</td>
  </tr>
  <tr>
    <td>Laughing Bacchus Winecellars</td>
    <td>Yoshi Tannamuri</td>
    <td>Canada</td>
  </tr>
  <tr>
    <td>Magazzini Alimentari Riuniti</td>
    <td>Giovanni Rovelli</td>
    <td>Italy</td>
  </tr>
</table>

</body>
</html>
<?php
mysqli_close($gestambu);
?>
