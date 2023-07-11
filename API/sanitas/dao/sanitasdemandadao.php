<?php
/**
 * @author Curro
 * @since 17/02/2022
 * @description Clase DAO para manipulacion de datos referentes a las demandas de sanitas
 */
include('../entidades/sanitasdemanda.php');
class SanitasDemandaDAO {
  
    const MODO_SELECT_DEMANDAS_NUEVAS= 0; //selecciona demandas nuevas
    const MODO_SELECT_DEMANDA_ID= 1; //selecciona demanda por id
    
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
        return "Objeto DAO para sanitasDemanda";
    }
    /**
     * @desc Funcion para insertar en la base de datos una demanda de sanitas
     * Con fecha actual
     * @author Curro
     * @since 17/02/2022
     * @param  SanitasDemanda $insertDemanda
     * @return boolean true si la inserccion fue correcta / false error
     */
    function insertSanitasDemandaNow($insertDemanda){
        $insertDemanda->setFecha_recepcionNow(); //fecha de recepcion ahora
        $insertDemanda->setEstado_proceso(SanitasDemanda::ESTADO_PROCESO_NUEVO); //estado recibido nuevo
        $insertar = $this->insertSanitasDemanda($insertDemanda);   
        return $insertar;
    }
    
    /**
     * @desc Funcion que inserta una demanda en la base de datos
     * @author Curro
     * @since 17/02/2022
     * @param SanitasDemanda $insertDemanda
     * @return boolean true si la inserccion fue correcta / false error
     */
    function insertSanitasDemanda($insertDemanda){
        $stmt = mysqli_prepare($this->getConexionDB(), "INSERT INTO sanitasdemanda(idAviso, tipo_aviso, tipo_aviso_nombre, 
                                            estado, estado_nombre, fecha_aviso, 
                                            fecha_recepcion, ciap2, tipo_recurso,
                                            tipo_recurso_nombre, nombre, apellido1, 
                                            apellido2, edad, telefono_1, 
                                            telefono_2,idCliente,cliente_documento,cliente_tipo_documento,
                                            ciap2_nombre,idRecurso,estado_proceso,
                                            destino_codpostal,destino_idprovincia,destino_direccion,
                                            origen_codpostal,origen_idprovincia,origen_direccion,idPrioridad,
                                            origen_idmunicipio,destino_idmunicipio,cliente_poliza,sexo,autorizacion,observaciones,tipo_edad,
                                            codigo_sanitas, codigo_sanitas_nombre,hospital_destino,hospital_origen) 
                                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");      
        
        mysqli_stmt_bind_param($stmt, 'iisissssissssississsiiiisiisiiissississs', 
            $insertDemanda->getIdAviso(), 
            $insertDemanda->getTipo_aviso(),
            $insertDemanda->getTipo_aviso_nombre(),
            $insertDemanda->getEstado(),
            $insertDemanda->getEstado_nombre(),
            $insertDemanda->getFecha_aviso_string(),
            $insertDemanda->getFecha_recepcion(),
            $insertDemanda->getCiap2(),
            $insertDemanda->getTipo_recurso(),
            $insertDemanda->getTipo_recurso_nombre(),
            $insertDemanda->getNombre(),
            $insertDemanda->getApellido1(),
            $insertDemanda->getApellido2(),
            $insertDemanda->getEdad(),
            $insertDemanda->getTelefono_1(),
            $insertDemanda->getTelefono_2(),
            $insertDemanda->getIdCliente(),
            $insertDemanda->getClienteDocumento(),
            $insertDemanda->getClienteTipoDocumento(),
            $insertDemanda->getCiap2_nombre(),
            $insertDemanda->getIdRecurso(),
            $insertDemanda->getEstado_proceso(),
            $insertDemanda->getDestinoCodPostal(),
            $insertDemanda->getDestinoIdProvincia(),
            $insertDemanda->getDestinoDireccion(),
            $insertDemanda->getOrigenCodPostal(),
            $insertDemanda->getOrigenIdProvincia(),
            $insertDemanda->getOrigenDireccion(),
            $insertDemanda->getIdPrioridad(),
            $insertDemanda->getOrigenIdMunicipio(),
            $insertDemanda->getDestinoIdMunicipio(),
            $insertDemanda->getClientePoliza(),
            $insertDemanda->getSexo(),
            $insertDemanda->getAutorizacion(),
            $insertDemanda->getObservaciones(),
            $insertDemanda->getTipoEdad(),
            $insertDemanda->getCodigoSanitas(),
            $insertDemanda->getCodigoSanitas_Nombre(),
            $insertDemanda->getHospitalDestino(),
            $insertDemanda->getHospitalOrigen()
            );
        var_dump($insertDemanda);
        $reslt = $stmt->execute();
        if( $insertDemanda->getTipo_recurso() == SanitasDemanda::INDICE_AMBULANCIAS_PROGRAMADAS){
            $reslt = $this->insertSanitasDemandaProgramada($insertDemanda);  
        }  
        return $reslt;
    }
    /**
     * @desc Funcion que obtiene una demanda de sanitas de la base de datos
     * @author Curro
     * @since 21/02/2022
     * @param int $mode modo de acceso
     * @param Array $param array de parametros
     * @return array<SanitasDemanda>
     */
    function selectSanitasDemanda($mode,$param){ 
            $salida = array();
            $fields='';
            switch ($mode) {
                case $this::MODO_SELECT_DEMANDAS_NUEVAS:
                    $fields=', provincias1.provincia as origenprovincia, provincias2.provincia as destinoprovincia, municipios1.municipio as origenmunicipio, municipios2.municipio as destinomunicipio ';
                    $joins = " LEFT JOIN provincias as provincias1 on provincias1.id = sanitasdemanda.origen_idprovincia LEFT JOIN provincias as provincias2 on provincias2.id = sanitasdemanda.destino_idprovincia ";
                    $joins.= " LEFT JOIN municipios as municipios1 on municipios1.id = sanitasdemanda.origen_idmunicipio LEFT JOIN municipios as municipios2 on municipios2.id = sanitasdemanda.destino_idmunicipio ";
                    $wheres= " where estado_proceso=0 order by idAviso Limit 20";
                break;
                case $this::MODO_SELECT_DEMANDA_ID:
                    $fields=', provincias1.provincia as origenprovincia, provincias2.provincia as destinoprovincia, municipios1.municipio as origenmunicipio, municipios2.municipio as destinomunicipio ';
                    $joins = " LEFT JOIN provincias as provincias1 on provincias1.id = sanitasdemanda.origen_idprovincia LEFT JOIN provincias as provincias2 on provincias2.id = sanitasdemanda.destino_idprovincia ";
                    $joins.= " LEFT JOIN municipios as municipios1 on municipios1.id = sanitasdemanda.origen_idmunicipio LEFT JOIN municipios as municipios2 on municipios2.id = sanitasdemanda.destino_idmunicipio ";
                    $wheres= " where idAviso = ". $param['idAviso'] ." ";
                break;
            }
            $query = "Select idAviso, tipo_aviso, tipo_aviso_nombre,
                                            estado, estado_nombre, fecha_aviso,
                                            fecha_recepcion, ciap2, tipo_recurso,
                                            tipo_recurso_nombre, nombre, apellido1,
                                            apellido2, edad, telefono_1,
                                            telefono_2,idCliente,cliente_documento,cliente_tipo_documento,
                                            ciap2_nombre,idRecurso,estado_proceso,origen_codpostal,origen_direccion,
                                            origen_idprovincia,destino_direccion,destino_codpostal,destino_idprovincia, 
                                            idPrioridad, origen_idmunicipio, destino_idmunicipio, cliente_poliza, sexo, autorizacion,observaciones,tipo_edad, codigo_sanitas, codigo_sanitas_nombre, hospital_destino, hospital_origen". $fields ." From sanitasdemanda " . $joins . " "  . $wheres;
            
            $stmt = mysqli_query($this->getConexionDB(),$query);
            while($rwSanita = mysqli_fetch_array($stmt)){
                $nuevaDemanda = new SanitasDemanda();
                $nuevaDemanda->setIdAviso($rwSanita ['idAviso']);
                $nuevaDemanda->setNombre($rwSanita ['nombre']);
                $nuevaDemanda->setApellido1($rwSanita ['apellido1']);
                $nuevaDemanda->setApellido2($rwSanita ['apellido2']);
                $nuevaDemanda->setTelefono_1($rwSanita ['telefono_1']);
                $nuevaDemanda->setTelefono_2($rwSanita ['telefono_2']);
                $nuevaDemanda->setClienteDocumento($rwSanita ['cliente_documento']);
                $nuevaDemanda->setEdad($rwSanita ['edad']);
                $nuevaDemanda->setFecha_recepcion($rwSanita ['fecha_recepcion']);
                $nuevaDemanda->setFecha_aviso($rwSanita ['fecha_aviso']);
                $nuevaDemanda->setDestinoDireccion($rwSanita ['destino_direccion']);
                $nuevaDemanda->setOrigenDireccion($rwSanita ['origen_direccion']);
                $nuevaDemanda->setIdPrioridad($rwSanita ['idPrioridad']);
                $nuevaDemanda->setDestinoIdMunicipio($rwSanita ['destino_idmunicipio']);
                $nuevaDemanda->setOrigenIdMunicipio($rwSanita ['origen_idmunicipio']);
                $nuevaDemanda->setDestinoIdProvincia($rwSanita ['destino_idprovincia']);
                $nuevaDemanda->setOrigenIdProvincia($rwSanita ['origen_idprovincia']);
                $nuevaDemanda->setClientePoliza($rwSanita ['cliente_poliza']);
                $nuevaDemanda->setSexo($rwSanita ['sexo']);
                $nuevaDemanda->setAutorizacion($rwSanita ['autorizacion']);
                $nuevaDemanda->setObservaciones($rwSanita ['observaciones']);
                $nuevaDemanda->setTipoEdad($rwSanita ['tipo_edad']);
                $nuevaDemanda->setTipo_recurso($rwSanita ['tipo_recurso']);
                $nuevaDemanda->setTipo_aviso($rwSanita ['tipo_aviso']);
                $nuevaDemanda->setCodigoSanitas($rwSanita ['codigo_sanitas']);
                $nuevaDemanda->setCodigoSanitas_nombre($rwSanita ['codigo_sanitas_nombre']);
                $nuevaDemanda->setHospitalDestino($rwSanita ['hospital_destino']);
                $nuevaDemanda->setHospitalOrigen($rwSanita ['hospital_origen']);
                switch ($mode) {
                    case $this::MODO_SELECT_DEMANDAS_NUEVAS:
                    case $this::MODO_SELECT_DEMANDA_ID:
                        $nuevaDemanda->setDestinoProvinciaNombre($rwSanita ['destinoprovincia']);
                        $nuevaDemanda->setOrigenProvinciaNombre($rwSanita ['origenprovincia']);
                        $nuevaDemanda->setDestinoMunicipioNombre($rwSanita ['destinomunicipio']);
                        $nuevaDemanda->setOrigenMunicipioNombre($rwSanita ['origenmunicipio']);
                    break;
                }              
                array_push($salida,$nuevaDemanda);
            }
            return $salida;

    }
    /**
     * @desc Funcion que obtiene los datos de una asistencia programada
     * @author Curro
     * @since 03/04/2022
     * @param int $mode modo de acceso
     * @param Array $param array de parametros
     * @param SanitasDemanda $demanda demanda a la que se añadiran los datos de programacion
     * @return SanitasDemanda $demanda demanda devuelta con sus datos de programacion
     */
    function selectSanitasDemandaProgramada($mode,$param, $demanda){ 
        $fields='';
        switch ($mode) {
            case $this::MODO_SELECT_DEMANDA_ID:
                $fields='';
                $joins = "";
                $wheres= " where idAviso = ". $param['idAviso'] ." ";
                break;
        }
        $query = "Select idProgramada,idAviso,fecha_recogida,fecha_llegada,codigo_motivo,motivo_nombre,regreso,fecha_regreso
                                                    ". $fields ." From sanitasdemanda_programada" . $joins . " "  . $wheres;
        
        $stmt = mysqli_query($this->getConexionDB(),$query);
        while($rwSanita = mysqli_fetch_array($stmt)){
            $demanda->setProgramadaFechaLlegada($rwSanita ['fecha_llegada']);
            $demanda->setProgramadaFechaRecogida($rwSanita ['fecha_recogida']);
            $demanda->setProgramadaFechaRegreso($rwSanita ['fecha_regreso']);
            $demanda->setProgramadaCodigoMotivo($rwSanita ['codigo_motivo']);
            $demanda->setProgramadaRegreso($rwSanita ['regreso']);
            $demanda->setProgramadaIdProgramada($rwSanita ['idProgramada']);
            $demanda->setProgramadaMotivoNombre($rwSanita ['motivo_nombre']);
        }
        return $demanda;
        
    }
    /**
     * @desc Funcion que inserta en la base de datos la tambla con los datos de asistenacia programada de SAnitas
     * @author Curro
     * @since 03/04/2022
     * @param SanitasDemanda $insertDemanda
     * @return boolean true si la inserccion fue correcta / false error
     */
    function insertSanitasDemandaProgramada($insertDemanda){
        $stmt = mysqli_prepare($this->getConexionDB(), "INSERT INTO sanitasdemanda_programada(idAviso,fecha_recogida,fecha_llegada,codigo_motivo,motivo_nombre,regreso,fecha_regreso)
                                        VALUES (?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'issisis',
            $insertDemanda->getIdAviso(),
            $insertDemanda->getProgramadaFechaRecogida_string(),
            $insertDemanda->getProgramadaFechaLlegada_string(),
            $insertDemanda->getProgramadaCodigoMotivo(),
            $insertDemanda->getProgramadaMotivoNombre(),
            $insertDemanda->getProgramadaRegreso(),
            $insertDemanda->getProgramadaFechaRegreso_string()
            );
        $reslt = $stmt->execute();
        return $reslt;
    }
 
}


?>