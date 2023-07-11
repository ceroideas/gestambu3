<?php 

class RelevoQuerys {
    
    public static function getQuery($q, $diaIni, $diaFin) {
        $querys = array("incMant" => "SELECT relevo.idRel, relevo.userId, relevo.horaRel, relevo.textoRel, relevo.enviado, relevo.tipo, relevo.estRel, relevo.modificado, user.userId, user.usNom, user.usApe
        FROM relevo
          LEFT JOIN user ON relevo.userId = user.userId
        WHERE relevo.estRel = '1' AND relevo.tipo = '3'
        ORDER BY relevo.enviado, relevo.horaRel ASC ",
        "inciSv" => "SELECT relevo.idRel, relevo.userId, relevo.horaRel, relevo.textoRel, relevo.enviado, relevo.tipo, relevo.estRel, relevo.modificado, user.userId, user.usNom, user.usApe
        FROM relevo
          LEFT JOIN user ON relevo.userId = user.userId
        WHERE relevo.enviado BETWEEN '$diaIni 08:00:00' AND '$diaFin 07:59:59' AND relevo.estRel = '1' AND relevo.tipo IN('1', '2')
        ORDER BY relevo.enviado, relevo.horaRel ASC ",
        "inciExp" => "SELECT incidencia.idInci, incidencia.idSv, incidencia.incHora, incidencia.descInci, incidencia.userInci, incidencia.motivoInci, incidencia.enviaInci, user.userId, user.usNom, user.usApe,
            servicio.idSv, servicio.nombre, servicio.apellidos, servicio.tipo, servi.idServi, servi.nomSer
          FROM incidencia
            LEFT JOIN user ON incidencia.userInci = user.userId
            LEFT JOIN servicio ON incidencia.idSv = servicio.idSv
            LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE incidencia.enviaInci BETWEEN '$diaIni 08:00:00' AND '$diaFin 07:59:59'
          ORDER BY incidencia.idSv, incidencia.enviaInci ASC "
        );

        return $querys[$q];
    }

    public static function getCountQuery($q, $diaIni, $diaFin) {
        $querys = array(
        "incMant" => "SELECT COUNT(*) FROM relevo
          LEFT JOIN user ON relevo.userId = user.userId
        WHERE relevo.estRel = '1' AND relevo.tipo = '3'
        ORDER BY relevo.enviado, relevo.horaRel ASC ",
        "inciSv" => "SELECT  COUNT(*) FROM relevo
          LEFT JOIN user ON relevo.userId = user.userId
        WHERE relevo.enviado BETWEEN '$diaIni 08:00:00' AND '$diaFin 07:59:59' AND relevo.estRel = '1' AND relevo.tipo IN('1', '2')
        ORDER BY relevo.enviado, relevo.horaRel ASC ",
        "inciExp" => "SELECT COUNT(*) FROM incidencia
            LEFT JOIN user ON incidencia.userInci = user.userId
            LEFT JOIN servicio ON incidencia.idSv = servicio.idSv
            LEFT JOIN servi ON servicio.tipo = servi.idServi
          WHERE incidencia.enviaInci BETWEEN '$diaIni 08:00:00' AND '$diaFin 07:59:59'
          ORDER BY incidencia.idSv, incidencia.enviaInci ASC "
        );

        return $querys[$q];
    }
}

?>