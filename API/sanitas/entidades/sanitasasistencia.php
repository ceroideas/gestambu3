<?php
/**
 * @since 17/02/2022
 * @author Curro
 * @description Clase que implementa las asistencias registradas de sanitas
 */
class SanitasAsistencia {
    
    
    
    const ENTIDAD_ESTADO = 330;  //este valor actualmente esta asi fijado pos Sanitas
    
    private $idAviso;
    private $estado;
    private $idAsistencia;
    private $fecha_asistencia; //fecha de grabacion del registro
    private $idPrestador;
    private $idRecurso;
    private $observaciones;
    private $fecha_prevista;
    
    function __constructor($id){
        $this->idAviso = $id;
    }
    //------------geters y seters-----------------
    function setIdAviso($entrada){
        $this->idAviso=$entrada;
    }
    function getIdAviso(){
        return $this->idAviso;
    }
    
    function setEstado($entrada){
        $this->estado=$entrada;
    }
    function getEstado(){
        return $this->estado;
    }
    
    function setIdAsistencia($entrada){
        $this->idAsistencia=$entrada;
    }
    function getIdAsistencia(){
        return $this->idAsistencia;
    }
    
    function setFecha_asistencia($entrada){
        $this->fecha_asistencia=$entrada;
    }
    function getFecha_asistencia(){
        return $this->fecha_asistencia;
    }
    
    function setFecha_asistenciaNow(){
        $this->fecha_asistencia=date("Y-m-d H:i:s");
    }
    
    function getIdPrestador(){
        return $this->idPrestador;
    }
    
    function setIdPrestador($entrada){
        $this->idPrestador=$entrada;
    }
    function getIdRecurso(){
        return $this->idPrestador;
    }
    
    function setIdRecurso($entrada){
        $this->idRecurso=$entrada;
    }   
    
    function setFecha_prevista($entrada){
        $this->fecha_prevista=$entrada;
    }
    function getFecha_prevista(){
        return $this->fecha_prevista;
    }
    
    function getObservaciones(){
        return $this->entidadEstado;
    }
    
    function setObservaciones($entrada){
        $this->entidadEstado=$entrada;
    }
    
    function  __toString() {
        return "Este aviso de sanitas es &nbsp;".$this->getIdAviso()."&nbsp;&nbsp;";
    }
    
}
?>