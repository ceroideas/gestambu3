<?php
require_once ("../../functions/function.php");
require_once('entidades/sanitasdemanda.php');
require_once('dao/sanitasdemandadao.php');
class ClientSanitas{
    
    /**
     * @desc funcion que ejecuta el procesado de un mensaje de entrada
     * @param String $msgIn
     * @author Curro
     * @since 17/02/2022
     */
     function procesaMensajeSanitas($msgIn) {
        global $gestambu;
        $utf8 = utf8_decode($msgIn);
        $mensajeParse = $this->parseMsgSanitas($utf8);  
        $objectDao = new SanitasDemandaDAO();
        $objectDao->setConexionDB($gestambu);
        $objectDao->insertSanitasDemandaNow($mensajeParse);
    }
    
    /**
     * @desc Funcion que parsea un mensaje de entrada en un objeto de demanda
     * @param String $msgIn
     * @return SanitasDemanda
     * @author Curro
     * @since 17/02/2022
     */
    function parseMsgSanitas($msgIn) {
        $mensaje = new SimpleXMLElement($msgIn);
        $nuevoMensaje = new SanitasDemanda();
        //parte aviso
        $nuevoMensaje->setIdAviso($mensaje->aviso->servicio->idAviso);
        $nuevoMensaje->setTipo_aviso($mensaje->aviso->servicio->tipoAviso);
        $nuevoMensaje->setTipo_aviso_nombre($mensaje->aviso->servicio->descTipoAviso);   
        $nuevoMensaje->setEstado($mensaje->aviso->servicio->idEstado);
        $nuevoMensaje->setEstado_nombre($mensaje->aviso->servicio->descEstado);
        $nuevoMensaje->setFecha_aviso($mensaje->aviso->servicio->fechaInicio);
        $nuevoMensaje->setCiap2($mensaje->aviso->servicio->ciap2); 
        $nuevoMensaje->setCiap2_nombre($mensaje->aviso->servicio->descCiap2); 
        $nuevoMensaje->setIdRecurso($mensaje->aviso->servicio->idRecurso); 
        $nuevoMensaje->setTipo_recurso($mensaje->aviso->servicio->tipoRecurso);
        $nuevoMensaje->setTipo_recurso_nombre($mensaje->aviso->servicio->descTipoRecurso);
        $nuevoMensaje->setAutorizacion($mensaje->aviso->servicio->autorizacion);
        $nuevoMensaje->setIdPrioridad($mensaje->aviso->servicio->idPrioridad);
        $nuevoMensaje->setObservaciones($mensaje->aviso->servicio->observaciones);
        $nuevoMensaje->setCodigoSanitas($mensaje->aviso->servicio->codigoSanitas);
        $nuevoMensaje->setCodigoSanitas_nombre($mensaje->aviso->servicio->descCodigoSanitas);
        if($nuevoMensaje->getTipo_recurso() == SanitasDemanda::INDICE_AMBULANCIAS_PROGRAMADAS){ //parte de ambulancias programadas
            $nuevoMensaje->setProgramadaCodigoMotivo($mensaje->aviso->servicio->ambulanciaProgramada->idMotivo);
            $nuevoMensaje->setProgramadaFechaLlegada($mensaje->aviso->servicio->ambulanciaProgramada->fechaLlegadaDestino);
            $nuevoMensaje->setProgramadaFechaRecogida($mensaje->aviso->servicio->ambulanciaProgramada->fechaRecogida);
            $nuevoMensaje->setProgramadaFechaRegreso($mensaje->aviso->servicio->ambulanciaProgramada->fechaRegreso);
            $nuevoMensaje->setProgramadaMotivoNombre($mensaje->aviso->servicio->ambulanciaProgramada->descMotivo);
            $nuevoMensaje->setProgramadaRegreso($mensaje->aviso->servicio->ambulanciaProgramada->conRegreso);
        }     
        //parte cliente
        $nuevoMensaje->setIdCliente($mensaje->aviso->cliente->idCliente);
        $nuevoMensaje->setNombre($mensaje->aviso->cliente->nombre);
        $nuevoMensaje->setApellido1($mensaje->aviso->cliente->primerApellido);
        $nuevoMensaje->setApellido2($mensaje->aviso->cliente->segundoApellido);
        $nuevoMensaje->setClienteTipoDocumento($mensaje->aviso->cliente->tipoDocumento);
        $nuevoMensaje->setClienteDocumento($mensaje->aviso->cliente->documento);
        $nuevoMensaje->setEdad($mensaje->aviso->cliente->edad);
        $nuevoMensaje->setClientePoliza($mensaje->aviso->cliente->poliza);
        $nuevoMensaje->setSexo($mensaje->aviso->cliente->tipoSexo);
        $nuevoMensaje->setTipoEdad($mensaje->aviso->cliente->tipoEdad);
        //telefono
        $nuevoMensaje->setTelefono_1($mensaje->aviso->telefono->fijo);
        $nuevoMensaje->setTelefono_2($mensaje->aviso->telefono->movil);
        //direcciones
        //origen
        $nuevoMensaje->setOrigenDireccion($mensaje->aviso->direccion->direccionOrigen->direccion.' '.$mensaje->aviso->direccion->direccionOrigen->numeroCalle.' '.$mensaje->aviso->direccion->direccionOrigen->restoVia);
        $nuevoMensaje->setOrigenIdProvincia($mensaje->aviso->direccion->direccionOrigen->idProvincia);
        $nuevoMensaje->setOrigenCodPostal($mensaje->aviso->direccion->direccionOrigen->idCodigoPostal);
        $nuevoMensaje->setOrigenIdMunicipio($mensaje->aviso->direccion->direccionOrigen->idMunicipio);
        $nuevoMensaje->setHospitalOrigen($mensaje->aviso->direccion->direccionOrigen->hospital);
        //destion
        $nuevoMensaje->setDestinoDireccion($mensaje->aviso->direccion->direccionDestino->direccion.' '.$mensaje->aviso->direccion->direccionOrigen->numeroCalle.' '.$mensaje->aviso->direccion->direccionDestino->restoVia);
        $nuevoMensaje->setDestinoIdProvincia($mensaje->aviso->direccion->direccionDestino->idProvincia);
        $nuevoMensaje->setDestinoCodPostal($mensaje->aviso->direccion->direccionDestino->idCodigoPostal);
        $nuevoMensaje->setDestinoIdMunicipio($mensaje->aviso->direccion->direccionDestino->idMunicipio);
        $nuevoMensaje->setHospitalDestino($mensaje->aviso->direccion->direccionDestino->hospital);
        return $nuevoMensaje;
    }
}

?>