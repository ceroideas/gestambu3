<?php
/**
 * @author Curro
 * @since 17/02/2022
 * @description Clase DAO para manipulacion de datos referentes a las demandas de sanitas
 */

class SanitasDAO {
    
    const MODO_SELECT_CODIGOS_SANITAS= 0; //selecciona codigo de sanitas por servicio y recurso
    const MODO_SELECT_CODIGOS_SANITAS_byCodigoSanitas= 0; //selecciona codigo de sanitas por codigoSanitas
    
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
        return "Objeto DAO general para sanitas";
    }
    
    
    /**
     * @desc Funcion que obtiene la tabla de cruce de codigo sanitas
     * @author Curro
     * @since 24/03/2022
     * @param int $mode modo de acceso
     * @param Array $param array de parametros
     * @return Array de codigoServicioEncontrado
     */
    function selectSanitasCODIGO($mode,$param){
        $salida = array();
        switch ($mode) {
            case $this::MODO_SELECT_CODIGOS_SANITAS:
                $wheres= " where codigo_servicio = ? AND  codigo_recurso = ? AND codigo_sanitas = ?";
                break;
            case $this::MODO_SELECT_CODIGOS_SANITAS_byCodigoSanitas:
                $wheres= " where codigo_sanitas = ?";
                break;
        }
        $query = "Select codigo_servicio, codigo_recurso, idServi, idRecu, codigo_sanitas from codigosanitas ".  $wheres;
        $stmt = mysqli_prepare($this->getConexionDB(), $query);
        switch ($mode) {
            case $this::MODO_SELECT_CODIGOS_SANITAS:
                mysqli_stmt_bind_param($stmt, 'iii',
                $param['codigo_servicio'],
                $param['codigo_recurso'],
                $param['codigo_sanitas']
                );
            break;
            case $this::MODO_SELECT_CODIGOS_SANITAS_byCodigoSanitas:
                mysqli_stmt_bind_param($stmt, 'i',
                $param['codigo_sanitas']
                );
            break;
        }
        $salida = array();
        $stmt->execute();
        $stmt->bind_result($codigo_servicio,$codigo_recurso,$idServi,$idRecu,$codigo_sanitas);
        while ($stmt->fetch()) {
            $aux =  ["codigo_servicio"=>$codigo_servicio,"codigo_recurso"=>$codigo_recurso,"idServi"=>$idServi, "idRecu"=>$idRecu,"codigo_sanitas"=>$codigo_sanitas];
            array_push($salida,$aux);
        }
        return $salida;        
    }
}
?>