<div class="box-body no-padding">
  <div class="box">
    <table class="table table-condensed">
      <tbody>
        <?php if($serIdvta == '1') { ?>
        <tr>
          <th width="5%">Nº Sesion</th>
          <th width="5%">Fecha Asistencia</th>
          <th width="5%">Hora de consulta</th>
          <th width="5%">Hora de recogida</th>
          <th width="5%">Hora de vuelta</th>
          <th width="1%">Ida</th>
          <th width="1%">Vta</th>
          <th width="1%">Id/vta</th>
          <th width="30%">Descripción de modificación</th>
        </tr>
      <?php } else { ?>
        <tr>
          <th width="5%">Nº Sesion</th>
          <th width="5%">Fecha Asistencia</th>
          <th width="5%">Hora de consulta</th>
          <th width="5%">Hora de recogida</th>
          <th width="40%">Descripción de modificación</th>
        </tr>
      <?php } ?>
        <?php
        /*
        # si el servicio ya se ha cursado y se ha finalizado, no se puede modificar
        */
          while($rwSesion = mysqli_fetch_array($colSesion)) {

            include 'ref/filtromodconti.php';
            /*
             * 2 = recurso no activado,
3 = recurso activado, 
4 = recurso en el origen,
5 = finalización/en destino
             */
            if($rwTabDem['cod_servicio'] <= 60 or $rwTabDem['cod_servicio'] >= 64) {
            switch ($rwSesion['estado']) {
                case 6:
                case 7: $mensaDescrip = $mensaLog = "ANULADO EN ASISA";
                    $estRes =  15;
                    break;
                case 5 : $mensaDescrip = $mensaLog = "FINALIZADO EN ASISA";
                    break;
                case 2 : $mensaDescrip = $mensaLog= "Pendiente en ASISA";
                    break;
                case 3 : $mensaDescrip = $mensaLog= "Activado en ASISA";
                    break;
                case 4 : $mensaDescrip = $mensaLog= "En origen en ASISA";
                    break;
            }
            }
            if($serIdvta == 1 ) {
        ?>
        <tr class="<?php echo $filaColor; ?>">
          <td>Sesión <?php echo $i++; ?></td>
          <td><input type="date" <?php echo $modEstado; ?> class="form-control <?php cModi(muestraFechAsisa($rwSesion['fecha_asistencia']), $rwCompTab['fecha']); ?>" name="fecha<?php echo $fch++; ?>" value="<?php echo muestraFechAsisa($rwSesion['fecha_asistencia']); ?>"></td>
          <td><input type="time" <?php echo $modEstado; ?> class="form-control <?php cModi(muestraHorAsisa($rwSesion['hora_asistencia']).":00", $rwCompTab['hconsulta']); ?>" name="hconsulta<?php echo $cst++; ?>" value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>"></td>
          <td><input type="time" <?php echo $modEstado; ?> class="form-control" name="hora<?php echo $hr++; ?>" value="" <?php if($desigual==1) {echo "required"; } else { echo "readonly title=\"Se guarda la hora del campo hora. No es necesario especicarlas por separado.\""; } ?>></td>
          <td><input type="time" class="form-control <?php //echo $estHvuelta; ?>" name="hvuelta<?php echo $hrV++; ?>" value="<?php echo muestraHorAsisa(mostHoraVtAsisa($codeAsisa, $rwSesion['fecha_asistencia'])); ?>"></td>
          <td><?php if($calI_V == 2) { echo "<i class=\"fa fa-check\"></i>"; } ?></td>
          <td><?php if($calI_V == 3) { echo "<i class=\"fa fa-check\"></i>"; } ?></td>
          <td><?php if($calI_V == 1) { echo "<i class=\"fa fa-check\"></i>"; } ?></td>
          <td>
            <input type="hidden" name="desigual" value="<?php echo $desigual; ?>">
            <input type="hidden" name="idasistencia<?php echo $dam++; ?>" value="<?php echo $rwSesion['idasistencia']; ?>">
            <input type="hidden" name="coDemanda" value="<?php echo $codeAsisa; ?>">
            <input type="hidden" name="textAgregar<?php echo $txA++; ?>" value=" <?php echo $menObs; ?>">
            <input type="hidden" name="estServ<?php echo $sta++; ?>" value="<?php echo $estRes; ?>"> <!-- $estRes -->
            <input type="hidden" name="menGuarda<?php echo $msG++; ?>" value="1">
            <input type="hidden" name="mensalog<?php echo $msL++; ?>" value="<?php echo $mensaLog; ?>">
            <input type="hidden" name="idvtaRes<?php echo $ivC++; ?>" value="<?php echo $calI_V; ?>">
            <input type="hidden" name="identi<?php echo $idt++ ?>" value="<?php echo $rwCompTab['idSv']; ?>"> <!-- Evaluar para eliminarlo o no -->
            <?php
              echo "<span><strong>".$mensaLog."</strong></span>";
            
            
            
            ?>
            <?php //echo "<span><strong>".$textDesp.$textFecha.$textHora.$textHvta."</strong></span>"; ?>
          </td>
        </tr>
      <?php } else { ?>
        <tr class="<?php echo $filaColor; ?>">
          <td>Sesión <?php echo $i++; ?></td>
          <td><input type="date" <?php echo $modEstado; ?> class="form-control <?php cModi(muestraFechAsisa($rwSesion['fecha_asistencia']), $rwCompTab['fecha']); ?>" name="fecha<?php echo $fch++; ?>" value="<?php echo muestraFechAsisa($rwSesion['fecha_asistencia']); ?>"></td>
          <td><input type="time" class="form-control" name="hconsulta<?php echo $cst++; ?>" value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>"></td>
          <td>
            <input type="time" <?php echo $modEstado; ?> class="form-control <?php cModi(muestraHorAsisa($rwSesion['hora_asistencia']).":00", $rwCompTab['hora']); ?>" name="hora<?php echo $hr++; ?>"
            value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>" <?php if($desigual==1) {echo "required"; } else { echo "readonly title=\"Se guarda la hora del campo hora. No es necesario especicarlas por separado.\""; } ?>>
          </td>
          <td>
            <input type="hidden" name="desigual" value="<?php echo $desigual; ?>">
            <input type="hidden" name="idasistencia<?php echo $dam++; ?>" value="<?php echo $rwSesion['idasistencia']; ?>">
            <input type="hidden" name="coDemanda" value="<?php echo $codeAsisa; ?>">
            <input type="hidden" name="textAgregar<?php echo $txA++; ?>" value=" <?php if(isset($menObs) && !empty($menObs)) {echo $menObs;} ?>">
            <input type="hidden" name="estServ<?php echo $sta++; ?>" value="<?php echo $estRes; ?>">     <!-- $estRes -->
            <input type="hidden" name="menGuarda<?php echo $msG++; ?>" value="1">
            <input type="hidden" name="mensalog<?php echo $msL++; ?>" value="<?php echo $mensaLog; ?>">
            <input type="hidden" name="idvtaRes<?php echo $ivC++; ?>" value="<?php echo $calI_V; ?>">
            <input type="hidden" name="hvuelta<?php echo $hrV++; ?>" value="<?php echo muestraHorAsisa(mostHoraVtAsisa($codeAsisa, $rwSesion['fecha_asistencia'])); ?>">
            <input type="hidden" name="hconsulta<?php echo $cst++; ?>" value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>"> <!-- Evaluar para eliminar o no -->
            <input type="hidden" name="identi<?php echo $idt++ ?>" value="<?php echo $rwCompTab['idSv']; ?>"> <!-- Evaluar para eliminar o no -->
            <?php
              echo "<span><strong>".$mensaDescrip."</strong></span>";
             /*
              if(empty($textAgregar)) {
                echo "<span><strong>".$textDesp.$textFecha.$textHora.$textHvta."</strong></span>";
              } else {
                echo "<span><strong>".$textAgregar."</strong></span>";
              }
            */
            ?>
          </td>
        </tr>
      <?php } } ?>
      </tbody>
    </table>
  </div>
</div>
