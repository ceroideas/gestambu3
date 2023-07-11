<?php



include ('clientSanitas.php');

echo 'initTEST';
$msgMock='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<avisos24h>
<aviso>
<servicio>
    <idAviso>1001033</idAviso>
    <tipoAviso>1</tipoAviso>
    <descTipoAviso>URGENCIA</descTipoAviso>
    <idEstado>4</idEstado>
    <descEstado>PENDIENTE ACTIVADOR</descEstado>
    <fechaInicio>2018-03-27 09:56:33.0</fechaInicio>
    <ciap2>A03</ciap2>
    <descCiap2>FIEBRE</descCiap2>
    <idRecurso>1408626</idRecurso>
    <tipoRecurso>1</tipoRecurso>
    <descTipoRecurso>MEDICO A DOMICILIO</descTipoRecurso>
    <idEstadoRecurso>4</idEstadoRecurso>
    <descEstadoRecurso>ASIGNADO</descEstadoRecurso>
    <autorizacion>180201199</autorizacion>
    <tipoDu>6</tipoDu>
    <numDu>6505440120</numDu>
    <codigoSanitas>7015</codigoSanitas>
    <descCodigoSanitas>CONSULTA URGENTE A DOMICILIO</descCodigoSanitas>
    <idPrestador>97729</idPrestador>
    <descPrestador>ASINSA SERVICIOS SANITARIOS, S.L.</descPrestador>
    <fechaPrevista>2018-03-27 10:30:00.0</fechaPrevista>
    <idPrioridad>1</idPrioridad>
    <descPrioridad>NORMAL</descPrioridad>
    <reclamacion>0</reclamacion>
    <observaciones>fiebre 38.5c, meg, mucosidad, tos.
    no ram, madre lactante.
    </observaciones>
</servicio>
<cliente>
    <idCliente>4373156</idCliente>
    <nombre>MARTA</nombre>
    <primerApellido>LOPEZ</primerApellido>
    <segundoApellido>TRIPIANA</segundoApellido>
    <tipoDocumento>NIF</tipoDocumento>
    <documento>47710926W</documento>
    <fechaNacimiento>1978-08-12 00:00:00.0</fechaNacimiento>
    <edad>39</edad>
    <tipoEdad>ANOS</tipoEdad>
    <tipoSexo>MUJER</tipoSexo>
    <compania>1</compania>
    <poliza>82093876</poliza>
    <colectivo>0</colectivo>
</cliente>
<telefono>
    <movil>935650163</movil>
    <fijo>687457063</fijo>
</telefono>
<direccion>
    <direccionOrigen>
    <tipoVia>C/</tipoVia>
    <direccion>ROCAFORT 39/43</direccion>
    <restoVia>ESC DERCHA 5 2</restoVia>
    <idCodigoPostal>08015</idCodigoPostal>
    <idMunicipio>193</idMunicipio>
    <descMunicipio>BARCELONA</descMunicipio>
    <idProvincia>8</idProvincia>
    <descProvincia>BARCELONA</descProvincia>
    <idPais>34</idPais>
    <descPais>ESPANA</descPais>
    </direccionOrigen>
<direccionDestino/>
</direccion>
</aviso>
</avisos24h>';

$msgMock2='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<avisos24h>
<aviso>
<servicio>
    <idAviso>1001050</idAviso>
    <tipoAviso>1</tipoAviso>
    <descTipoAviso>URGENCIA</descTipoAviso>
    <idEstado>4</idEstado>
    <descEstado>PENDIENTE ACTIVADOR</descEstado>
    <fechaInicio>2020-03-27 20:56:33.0</fechaInicio>
    <ciap2>A03</ciap2>
    <descCiap2>FIEBRE</descCiap2>
    <idRecurso>1408626</idRecurso>
    <tipoRecurso>2</tipoRecurso>
    <descTipoRecurso>AMBULANCIA</descTipoRecurso>
    <idEstadoRecurso>4</idEstadoRecurso>
    <descEstadoRecurso>ASIGNADO</descEstadoRecurso>
    <autorizacion>180201199</autorizacion>
    <tipoDu>6</tipoDu>
    <numDu>6505440120</numDu>
    <codigoSanitas>7924</codigoSanitas>
    <descCodigoSanitas>CONSULTA URGENTE A DOMICILIO</descCodigoSanitas>
    <idPrestador>97729</idPrestador>
    <descPrestador>ASINSA SERVICIOS SANITARIOS, S.L.</descPrestador>
    <fechaPrevista>2018-03-27 10:30:00.0</fechaPrevista>
    <idPrioridad>2</idPrioridad>
    <descPrioridad>Urgente</descPrioridad>
    <reclamacion>0</reclamacion>
    <observaciones>fiebre 38.5c, meg, mucosidad, tos.
    no ram, madre lactante.
    </observaciones>
</servicio>
<cliente>
    <idCliente>4373156</idCliente>
    <nombre>Pepito</nombre>
    <primerApellido>GRillo</primerApellido>
    <segundoApellido>TRIPIANA</segundoApellido>
    <tipoDocumento>NIF</tipoDocumento>
    <documento>47710926W</documento>
    <fechaNacimiento>1978-08-12 00:00:00.0</fechaNacimiento>
    <edad>39</edad>
    <tipoEdad>ANOS</tipoEdad>
    <tipoSexo>MUJER</tipoSexo>
    <compania>1</compania>
    <poliza>66666666</poliza>
    <colectivo>0</colectivo>
</cliente>
<telefono>
    <movil>935650163</movil>
    <fijo>687457063</fijo>
</telefono>
<direccion>
    <direccionOrigen>
    <tipoVia>C/</tipoVia>
    <direccion>Anonima 39/43</direccion>
    <restoVia>ESC DERCHA 5 2</restoVia>
    <idCodigoPostal>29001</idCodigoPostal>
    <idMunicipio>45000</idMunicipio>
    <descMunicipio>ZAFRA</descMunicipio>
    <idProvincia>29</idProvincia>
    <descProvincia>Malaga</descProvincia>
    <idPais>34</idPais>
    <descPais>ESPANA</descPais>
    </direccionOrigen>
<direccionDestino/>
</direccion>
</aviso>
</avisos24h>';


$msgMock3 =  '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<avisos24h>
<aviso>
<servicio>
    <idAviso>411297</idAviso>
    <tipoAviso>8</tipoAviso>
    <descTipoAviso>TRASLADO</descTipoAviso>
    <idEstado>4</idEstado>
    <descEstado>PENDIENTE ACTIVADOR</descEstado>
    <fechaInicio>2022-02-23 01:25:07.0</fechaInicio>
    <ciap2>T99</ciap2>
    <descCiap2>OTR PROBL ENDOCR/METAB/NUTRI</descCiap2>
    <idRecurso>552479</idRecurso>
    <tipoRecurso>3</tipoRecurso>
    <descTipoRecurso>AMBULANCIA PROGRAMADA</descTipoRecurso>
    <idEstadoRecurso>4</idEstadoRecurso>
    <descEstadoRecurso>ASIGNADO</descEstadoRecurso>
    <autorizacion>128745498</autorizacion>
    <tipoDu>3</tipoDu>
    <numDu>3313245189</numDu>
    <codigoSanitas>7914</codigoSanitas>
    <descCodigoSanitas>SERVICIO AMBULANCIA</descCodigoSanitas>
    <idPrestador>67614</idPrestador>
    <descPrestador>PROVEEDOR</descPrestador>
    <fechaPrevista>2022-02-23 11:00:00.0</fechaPrevista>
    <idPrioridad>1</idPrioridad>
    <descPrioridad>NORMAL</descPrioridad>
    <reclamacion>0</reclamacion>
    <observaciones>CAIDA ACCIDENTAL CON TX COSTAL Y EN PIE DCHO Y TCE, DOLOR EN ESTAS ZONAS. NO HERIDA, CONSCIENTE Y ORIENTADA,
    AP: HTA, OSTEOPOROSIS
    SE ENVIA AMB</observaciones>
    <ambulanciaProgramada>
        <fechaRecogida>2022-02-23 12:00:00.0</fechaRecogida>
        <fechaLlegadaDestino>2022-02-23 12:38:00.0</fechaLlegadaDestino>
        <idMotivo>1</idMotivo>
        <descMotivo>ALTA HOSPITALARIA</descMotivo>
        <conRegreso>true</conRegreso>
        <fechaRegreso>2022-02-23 14:00:00.0</fechaRegreso>
    </ambulanciaProgramada>
</servicio>
<cliente>
    <idCliente>1114818</idCliente>
    <nombre>PEPITO</nombre>
    <primerApellido>GRILLO</primerApellido>
    <segundoApellido>GRILLO</segundoApellido>
    <tipoDocumento>NIF</tipoDocumento>
    <documento>01457710Q</documento>
    <fechaNacimiento>1932-01-01 00:00:00.0</fechaNacimiento>
    <edad>83</edad>
    <tipoEdad>ANOS</tipoEdad>
    <tipoSexo>VARON</tipoSexo>
    <compania>1</compania>
    <poliza>10140253</poliza>
    <colectivo>0</colectivo>
</cliente>
<telefono>
<movil>916729918</movil>
</telefono>
<direccion>
        <direccionOrigen>
            <tipoVia>C/</tipoVia>
            <direccion>SAN ROBERTO</direccion>
            <numeroCalle>7</numeroCalle>
            <restoVia>5 D</restoVia>
            <idCodigoPostal>28021</idCodigoPostal>
            <idMunicipio>796</idMunicipio>
            <descMunicipio>MADRID</descMunicipio>
            <idProvincia>28</idProvincia>
            <descProvincia>MADRID</descProvincia>
            <idPais>34</idPais>
            <descPais>ESPANA</descPais>
        </direccionOrigen>
        <direccionDestino>
                <tipoVia>C/</tipoVia>
                <direccion>DIEGO DE VELÁZQUEZ</direccion>
                <restoVia>1</restoVia>
                <idCodigoPostal>28223</idCodigoPostal>
                <idMunicipio>1150</idMunicipio>
                <descMunicipio>POZUELO DE ALARCON</descMunicipio>
                <idProvincia>28</idProvincia>
                <descProvincia>MADRID</descProvincia>
                <idPais>34</idPais>
                <descPais>ESPANA</descPais>
                <hospital>HOSPITAL QUIRON MADRID</hospital>
            </direccionDestino>
        </direccion>
    </aviso>
</avisos24h>';

$a = new ClientSanitas();
$a->procesaMensajeSanitas($msgMock);
$a->procesaMensajeSanitas($msgMock2);
$a->procesaMensajeSanitas($msgMock3);
//while (1) {
 /*   try {   
        //$stomp = new Stomp('failover://(tcp://10.7.244.128:61616,tcp://10.7.244.129:61616)?randomize=false');
         // $stomp = new Stomp('tcp://88.26.195.158:61616'); 
         
        //$stomp = new Stomp('tcp://10.19.137.111:61616'); 
        $stomp = new Stomp('tcp://10.7.244.128:61616', 'system','manager'); 
        $isSubscribe = $stomp->subscribe('Queue.seg.sps.avisos.andalucia.recepcion');
        
        while($isSubscribe){
            if ($stomp->hasFrame()) {
                $frame = $stomp->readFrame();
                if ($frame != NULL) {
                    print "Received: " . $frame->body . " - time now is " . date("Y-m-d H:i:s"). "\n";
                    //            $stomp->ack($frame);
                }
                //       sleep(1);
            }
            else {
                print "No frames to read\n";
            }
            echo 'pasa';
            Sleep(5000);
        }
       // $stomp = new Stomp(failover:(tcp://10.7.244.128:61616,tcp://10.7.244.129:61616)?initialReconnectDelay=100&randomize=false);
   } catch(StompException $e) {
        die('Connection failed: ' . $e->getMessage());
    }*/
/*Sleep(1000);
}*/

/*echo 'TEST de 29';
    
    try {
        //$stomp = new Stomp('failover://(tcp://10.7.244.128:61616,tcp://10.7.244.129:61616)?randomize=false');
        // $stomp = new Stomp('tcp://88.26.195.158:61616');
        
        //$stomp = new Stomp('tcp://10.19.137.111:61616');
        $stomp = new Stomp('tcp://10.7.244.129:61616', 'system','manager');
        $isSubscribe = $stomp->subscribe('Queue.seg.sps.avisos.andalucia.recepcion');
        
        while($isSubscribe){
            if ($stomp->hasFrame()) {
                $frame = $stomp->readFrame();
                if ($frame != NULL) {
                    print "Received: " . $frame->body . " - time now is " . date("Y-m-d H:i:s"). "\n";
                    //            $stomp->ack($frame);
                }
                //       sleep(1);
            }
            else {
                print "No frames to read\n";
            }
            echo 'pasa';
            Sleep(5000);
        }
        // $stomp = new Stomp(failover:(tcp://10.7.244.128:61616,tcp://10.7.244.129:61616)?initialReconnectDelay=100&randomize=false);
    } catch(StompException $e) {
        die('Connection failed: ' . $e->getMessage());
    }



echo 'FinTEST';*/

?>