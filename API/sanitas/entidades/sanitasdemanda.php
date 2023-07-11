<?php
/**
 * @since 17/02/2022
 * @author Curro
 * @description Clase que implementa las demandas de sanitas 
 */
class SanitasDemanda {
    
    const FORMATO_FECHA_DEMANDAS_GESTAMBU= 'd-m-Y';
    const FORMATO_FECHA_DEMANDAS_FRONT= 'Y-m-d';
    const FORMATO_HORA_DEMANDAS_GESTAMBU= 'H:i:s';
    
    const TIPOS_POSIBLES_URGENCIAS = ["1"=>"Normal", "2"=>"Urgente", "3"=>"Muy urgente"];
    const TIPOS_POSIBLES_RECURSOS = ["1"=>"MEDICO A DOMICILIO", "2"=>"AMBULANCIA URGENTE", "3"=>"AMBULANCIA PROGRAMADA", "4"=>"DUES", "5"=>"GESTION DE INGRESOS", "6"=>"OXIGENO","7"=>"AEROSOLES",
                                     "8"=>"HOSPITALIZACION DOMICILIARIA", "9"=>"ESPECIALISTA INTERCONSULTA", "10"=>"PRUEBAS DIAGNOSTICAS","11"=>"NOTIFICACION HD", "12"=>"DERIVADO A WELCOME","13"=>"PETICION NO VALIDA",
                                     "14"=>"SOLICITUD DE INFORMACION","15"=>"ASESORIA MEDICA","16"=>"TRIAJE"];
    
    /**
     * Valores posibles de estado del proceso
     * 0 cuando esta recien recida la demanda
     * 1 cuando la demanda esta procesada
     * @var integer
     */
    const ESTADO_PROCESO_NUEVO = 0;
    const ESTADO_PROCESO_SERVICIO_MODIFICABLE = 1;
    
    const INDICE_AMBULANCIAS_PROGRAMADAS= 3;
    
    private $idAviso;
    private $ciap2;
    private $ciap2_nombre;
    private $estado;
    private $estado_nombre;
    private $fecha_aviso;
    private $fecha_recepcion;
    private $fecha_prevista;
    private $idPrestador;
    private $idCliente;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $telefono_1;
    private $telefono_2;
    private $edad;
    private $sexo;
    private $tipo_aviso;
    private $tipo_aviso_nombre ;
    private $idRecurso ;
    private $tipo_recurso;
    private $tipo_recurso_nombre;
    private $clienteTipoDocumento;
    private $clienteDocumento;
    private $origenDireccion;
    private $origenIdProvincia;
    private $origenProvinciaNombre; // por cruce con tabla de provincias
    private $origenIdMunicipio; 
    private $origenMunicipioNombre;//por cruce con tabla de municipios
    private $origenCodPostal;
    private $destinoDireccion;
    private $destinoIdProvincia;
    private $destinoCodPostal;
    private $destinoProvinciaNombre;// por cruce con tabla de provincias
    private $destinoIdMunicipio; 
    private $destinoMunicipioNombre;// por cruce con tabla de municipios
    private $idPrioridad;
    private $clientePoliza;
    private $autorizacion;
    private $observaciones;
    private $tipoEdad;
    private $codigoSanitas;
    private $codigoSanitas_nombre;
    private $hospitalDestino;
    private $hospitalOrigen;
    
    
    //datos para demandas programadas
    
    private $programada_idProgramada;
    private $programada_fecha_recogida;
    private $programada_fecha_llegada;
    private $programada_codigo_motivo;
    private $programada_motivo_nombre;
    private $programada_regreso;
    private $programada_fecha_regreso;
    

  //  private $estado_proceso=0;
    
    
    function __constructor($id){
        $this->idAviso = $id;
    }
    //------------geters y seters-----------------
    function setApellido1($entrada){
        $this->apellido1=$entrada;
    }
    function getApellido1(){
        return $this->apellido1;
    }
    function setApellido2($entrada){
        $this->apellido2=$entrada;
    }
    function getApellido2(){
        return $this->apellido2;
    }
    function setCiap2($entrada){
        $this->ciap2=$entrada;
    }
    function getCiap2(){
        return $this->ciap2;
    }
    function setCiap2_nombre($entrada){
        $this->ciap2_nombre=$entrada;
    }
    function getCiap2_nombre(){
        return $this->ciap2_nombre;
    }
    function setEdad($entrada){
        $this->edad=$entrada;
    }
    function getEdad(){
        return $this->edad;
    }
    function setEstado($entrada){
        $this->estado=$entrada;
    }
    function getEstado(){
        return $this->estado;
    }
    function setEstado_nombre($entrada){
        $this->estado_nombre=$entrada;
    }
    function getEstado_nombre(){
        return $this->estado_nombre;
    }
    function setFecha_aviso($entrada){
        $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $entrada)));
        $this->fecha_aviso=$date;
    }
    function getFecha_aviso(){
        $date = date_create_from_format('Y-m-d H:i:s', $this->fecha_aviso);
        return $date;
    }
    
    function setFecha_prevista($entrada){
        $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $entrada)));
        $this->fecha_prevista=$date;
    }
    function getFecha_prevista(){
        $date = date_create_from_format('Y-m-d H:i:s', $this->fecha_prevista);
        return $date;
    }
    
    function getFecha_aviso_string(){
        return $this->fecha_aviso;
    }
    function setFecha_recepcionNow(){
        $this->fecha_recepcion=date("Y-m-d H:i:s"); 
    }
    function setFecha_recepcion($entrada){
        $this->fecha_recepcion=$entrada;
    }
    function getFecha_recepcion(){//devuelve la fecha del momento
        return $this->fecha_recepcion;
    }
    function setIdAviso($entrada){
        $this->idAviso=$entrada;
    }
    function getIdAviso(){
        return $this->idAviso;
    }
    function setNombre($entrada){
        $this->nombre=$entrada;
    }
    function getNombre(){
        return $this->nombre;
    }
    function setTelefono_1($entrada){
        $this->telefono_1=$entrada;
    }
    function getTelefono_1(){
        return $this->telefono_1;
    }
    function setTelefono_2($entrada){
        $this->telefono_2=$entrada;
    }
    function getTelefono_2(){
        return $this->telefono_2;
    }
    function setTipo_aviso($entrada){
        $this->tipo_aviso=$entrada;
    }
    function getTipo_aviso(){
        return $this->tipo_aviso;
    }
    function setTipo_aviso_nombre($entrada){
        $this->tipo_aviso_nombre=$entrada;
    }
    function getTipo_aviso_nombre(){
        return $this->tipo_aviso_nombre;
    }
    function setIdRecurso($entrada){
        $this->idRecurso=$entrada;
    }
    function getIdRecurso(){
        return $this->idRecurso;
    }
    function setTipo_recurso($entrada){
        $this->tipo_recurso=$entrada;
    }
    function getTipo_recurso(){
        return $this->tipo_recurso;
    }
    function setTipo_recurso_nombre($entrada){
        $this->tipo_recurso_nombre=$entrada;
    }
    function getTipo_recurso_nombre(){
        return $this->tipo_recurso_nombre;
    }
    function setIdCliente($entrada){
        $this->idCliente=$entrada;
    }
    function getIdCliente(){
        return $this->idCliente;
    }
    function setClienteDocumento($entrada){
        $this->clienteDocumento=$entrada;
    }
    function getClienteDocumento(){
        return $this->clienteDocumento;
    }
    function setClienteTipoDocumento($entrada){
        $this->clienteTipoDocumento=$entrada;
    }
    function getClienteTipoDocumento(){
        return $this->clienteTipoDocumento;
    }
    function setEstado_proceso($entrada){
        $this->estado_proceso=$entrada;
    }
    function getEstado_proceso(){
        return $this->estado_proceso;
    }
    
    function setOrigenDireccion($entrada){
        $this->origenDireccion=$entrada;
    }
    function getOrigenDireccion(){
        return $this->origenDireccion;
    }
    
    function setOrigenIdProvincia($entrada){
        $this->origenIdProvincia=$entrada;
    }
    function getOrigenIdProvincia(){
        return $this->origenIdProvincia;
    }
    
    function setOrigenCodPostal($entrada){
        $this->origenCodPostal=$entrada;
    }
    function getOrigenCodPostal(){
        return $this->origenCodPostal;
    }
    function setOrigenProvinciaNombre($entrada){
        $this->origenProvinciaNombre=$entrada;
    }
    function getOrigenProvinciaNombre(){
        return $this->origenProvinciaNombre;
    }
    function setOrigenIdMunicipio($entrada){
        $this->origenIdMunicipio=$entrada;
    }
    function getOrigenIdMunicipio(){
        return $this->origenIdMunicipio;
    }
    function setOrigenMunicipioNombre($entrada){
        $this->origenMunicipioNombre=$entrada;
    }
    function getOrigenMunicipioNombre(){
        return $this->origenMunicipioNombre;
    }
    function setDestinoDireccion($entrada){
        $this->destinoDireccion=$entrada;
    }
    function getDestinoDireccion(){
        return $this->destinoDireccion;
    }
    
    function setDestinoIdProvincia($entrada){
        $this->destinoIdProvincia=$entrada;
    }
    function getDestinoIdProvincia(){
        return $this->destinoIdProvincia;
    }
    
    function setDestinoCodPostal($entrada){
        $this->destinoCodPostal=$entrada;
    }
    function getDestinoCodPostal(){
        return $this->destinoCodPostal;
    }
    function setDestinoProvinciaNombre($entrada){
        $this->destinoProvinciaNombre=$entrada;
    }
    function getDestinoProvinciaNombre(){
        return $this->destinoProvinciaNombre;
    }
    function setDestinoIdMunicipio($entrada){
        $this->destinoIdMunicipio=$entrada;
    }
    function getDestinoIdMunicipio(){
        return $this->destinoIdMunicipio;
    }
    function setDestinoMunicipioNombre($entrada){
        $this->destinoMunicipioNombre=$entrada;
    }
    function getDestinoMunicipioNombre(){
        return $this->destinoMunicipioNombre;
    }
    function setIdPrioridad($entrada){
        $this->idPrioridad=$entrada;
    }
    function getIdPrioridad(){
        return $this->idPrioridad;
    }
    function setClientePoliza($entrada){
        $this->clientePoliza=$entrada;
    }
    function getClientePoliza(){
        return $this->clientePoliza;
    }
    function setSexo($entrada){
        $this->sexo=$entrada;
    }
    function getSexo(){
        return $this->sexo;
    }
    function setAutorizacion($entrada){
        $this->autorizacion=$entrada;
    }
    function getAutorizacion(){
        return $this->autorizacion;
    }
    function setObservaciones($entrada){
        $this->observaciones=$entrada;
    }
    function getObservaciones(){
        return $this->observaciones;
    }
    function setTipoEdad($entrada){
        $this->tipoEdad=$entrada;
    }
    function getTipoEdad(){
        return $this->tipoEdad;
    }
    
    function setIdPrestador($entrada){
        $this->idPrestador=$entrada;
    }
    function getIdPrestador(){
        return $this->idPrestador;
    }
    
    function setCodigoSanitas($entrada){
        $this->codigoSanitas=$entrada;
    }
    function getCodigoSanitas(){
        return $this->codigoSanitas;
    }
    
    function setCodigoSanitas_nombre($entrada){
        $this->codigoSanitas_nombre=$entrada;
    }
    function getCodigoSanitas_Nombre(){
        return $this->codigoSanitas_nombre;
    }
    
    function setHospitalDestino($entrada){
        $this->hospitalDestino=$entrada;
    }
    function getHospitalDestino(){
        return $this->hospitalDestino;
    }
    
    function setHospitalOrigen($entrada){
        $this->hospitalOrigen=$entrada;
    }
    function getHospitalOrigen(){
        return $this->hospitalOrigen;
    }
    
    
    //de las ambulancias programadas
    function setProgramadaFechaLlegada($entrada){
        $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $entrada)));
        $this->programada_fecha_llegada=$date;
    }
    function getProgramadaFechaLlegada(){
        $date = date_create_from_format('Y-m-d H:i:s', $this->programada_fecha_llegada);
        return $date;
    }
    function getProgramadaFechaLlegada_string(){
        return $this->programada_fecha_llegada;
    }
    
    function setProgramadaFechaRecogida($entrada){
        $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $entrada)));
        $this->programada_fecha_recogida=$date;
    }
    function getProgramadaFechaRecogida(){
        $date = date_create_from_format('Y-m-d H:i:s', $this->programada_fecha_recogida);
        return $date;
    } 
    function getProgramadaFechaRecogida_string(){
        return $this->programada_fecha_recogida;
    } 
    function setProgramadaFechaRegreso($entrada){
        $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $entrada)));
        $this->programada_fecha_regreso=$date;
    }
    function getProgramadaFechaRegreso(){
        $date = date_create_from_format('Y-m-d H:i:s', $this->programada_fecha_regreso);
        return $date;
    }
    function getProgramadaFechaRegreso_string(){
        return $this->programada_fecha_regreso;
    }
    
    function setProgramadaCodigoMotivo($entrada){
        $this->programada_codigo_motivo=$entrada;
    }
    function getProgramadaCodigoMotivo(){
        return $this->programada_codigo_motivo;
    } 
    function setProgramadaRegreso($entrada){
        $this->programada_regreso=$entrada;
    }
    function getProgramadaRegreso(){
        return $this->programada_regreso;
    }
    function setProgramadaIdProgramada($entrada){
        $this->programada_idProgramada=$entrada;
    }
    function getProgramadaIdProgramada(){
        return $this->programada_idProgramada;
    }
    function setProgramadaMotivoNombre($entrada){
        $this->programada_motivo_nombre=$entrada;
    }
    function getProgramadaMotivoNombre(){
        return $this->programada_motivo_nombre;
    }
    
    
    //-------fin de geters y seters--------------

    function  __toString() {
        return "Esta demanda de sanitas es &nbsp;".$this->getIdAviso()."&nbsp;&nbsp;";
    }
}
?>

