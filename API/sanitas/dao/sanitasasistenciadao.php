<?php
/**
 * @author Curro
 * @since 17/02/2022
 * @description Clase DAO para manipulacion de datos referentes a las demandas de sanitas
 */
include('../entidades/sanitasasistencia.php');
class SanitasAsistenciaDAO {
    
    const MODO_SELECT_ASISTENCIA_POR_IDAVISO= 0; //selecciona asistencia de sanitas por idaviso
    
    private $conexionDB; //contiene la conexion a la base datos
    //------------geters y seters-----------------
    function setConexionDB($entrada){
        $this->conexionDB=$entrada;
    }
    function getConexionDB(){
        return $this->conexionDB;
    }
    //-------fin de geters y seters--------------
    function __constructor($conn){
        $this->setConexionDB($conn);
    }
    function  __toString() {
        return "Objeto DAO para manejar las asistencias de sanitas";
    }
    
    
    /**
     * @desc Funcion que obtiene asistencias de sanitas
     * @author Curro
     * @since 24/03/2022
     * @param int $mode modo de acceso
     * @param Array $param array de parametros
     * @return Array de codigoServicioEncontrado
     */
    function selectSanitasAsistencia($mode,$param){
        $salida = array();
        switch ($mode) {
            case $this::MODO_SELECT_ASISTENCIA_POR_IDAVISO:
                $wheres= " where idAviso = ? ORDER BY fecha_asistencia desc";
                break;
        }
        $query = "Select idAsistencia, estado, idAviso, fecha_asistencia from sanitasasistencia ".  $wheres;
        $stmt = mysqli_prepare($this->getConexionDB(), $query);
        switch ($mode) {
            case $this::MODO_SELECT_CODIGOS_SANITAS:
                mysqli_stmt_bind_param($stmt, 'i',
                $param['id_aviso']
                );
                break;
        }
        $salida = array();
        $stmt->execute();
        $stmt->bind_result($codigo_servicio,$codigo_recurso,$idServi,$idRecu);
        while ($stmt->fetch()) {
            $aux =  ["codigo_servicio"=>$codigo_servicio,"codigo_recurso"=>$codigo_recurso,"idServi"=>$idServi, "idRecu"=>$idRecu];
            array_push($salida,$aux);
        }
        return $salida;
    }
    
    /**
     * @desc Funcion que inserta una asistencia en la base de datos
     * @author Curro
     * @since 25/03/2022
     * @param SanitasAsistencia $insertAsistencia
     * @return boolean true si la inserccion fue correcta / false error
     */
    function insertSanitasAsistencia($insertAsistencia){
        $stmt = mysqli_prepare($this->getConexionDB(), "INSERT INTO sanitasasistencia(idAviso, estado, fecha_asistencia)
                                            VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmt, 'iis',
            $insertAsistencia->getIdAviso(),
            $insertAsistencia->getEstado(),
            $insertAsistencia->getFecha_asistencia()
            );
        
        var_dump($insertDemanda);
        $reslt = $stmt->execute();
        return $reslt;
    }
}

?>