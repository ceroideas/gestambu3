<div class="box-body no-padding">
  <table class="table table-condensed">
    <tbody>
      <?php if($serIdvta == 1 ) { ?>
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
          <th width="30%">Descripción de modificación</th>
        </tr>
      <?php } ?>
      <?php while($rwSesion = mysqli_fetch_array($colSesion)) {
        /* Filtro para comparar */
        $codigoFiltro = $rwSesion['cod_demanda'];
        $ideAsist     = $rwSesion['idasistencia'];
        $fechaAsist   = $rwSesion['fecha_asistencia'];
        $fechAst      = $rwAst['fecha_asistencia'];
        $tabDemanda   = mysqli_query($gestambu, "SELECT cod_demanda, cod_servicio FROM asisademanda WHERE cod_demanda = '$codigoFiltro' ");
        $rwDemanda    = mysqli_fetch_assoc($tabDemanda);

        $asisAsisa = mysqli_query($gestambu, "SELECT idasistencia, cod_demanda, fecha_asistencia, hora_asistencia, vuelta, estado,  COUNT(cod_demanda) AS numSesion FROM asisaasistencia WHERE cod_demanda ='$codigoFiltro'");
        $rwAst = mysqli_fetch_assoc($asisAsisa);

        include 'ref/filtroncont.php';
        if($serIdvta == 1 ) {
      ?>
      <tr>
        <td>Sesión <?php echo $i++; ?></td>
        <td><input type="date" class="form-control" name="fecha<?php echo $fch++; ?>" value="<?php echo muestraFechAsisa($rwSesion['fecha_asistencia']); ?>"></td>
        <td><input type="time" class="form-control" name="hconsulta<?php echo $cst++; ?>" value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>"></td>
        <td><input type="time" class="form-control" name="hora<?php echo $hr++; ?>" value="" <?php if($desigual==1) {echo "required"; } else { echo "readonly title=\"Se guarda la hora del campo hora. No es necesario especicarlas por separado.\""; } ?>></td>
        <td><input type="time" class="form-control" name="hvuelta<?php echo $hrV++; ?>" value="<?php echo muestraHorAsisa($rwSqlVta['hora_asistencia']); ?>"></td>
        <td><?php if($calI_V == 2) { echo "<i class=\"fa fa-check\"></i>"; } ?></td>
        <td><?php if($calI_V == 3) { echo "<i class=\"fa fa-check\"></i>"; } ?></td>
        <td><?php if($calI_V == 1) { echo "<i class=\"fa fa-check\"></i>"; } ?></td>
        <td>
          <input type="hidden" name="desigual" value="<?php echo $desigual; ?>">
          <input type="hidden" name="idasistencia<?php echo $dam++; ?>" value="<?php echo $rwSesion['idasistencia']; ?>">
          <input type="hidden" name="coDemanda" value="<?php echo $codeAsisa; ?>">
          <input type="hidden" name="textAgregar<?php echo $txA++; ?>" value=" <?php echo $menObs; ?>">
          <input type="hidden" name="estServ<?php echo $sta++; ?>" value="<?php echo $estRes; ?>">
          <input type="hidden" name="menGuarda<?php echo $msG++; ?>" value="1">
          <input type="hidden" name="mensalog<?php echo $msL++; ?>" value="<?php echo $mensaLog; ?>">
          <input type="hidden" name="idvtaRes<?php echo $ivC++; ?>" value="<?php echo $calI_V; ?>">
          <?php
            echo "<span><strong>".$mensaLog."</strong></span>";
          ?>
        </td>
        </td>
      </tr>
    <?php } else { ?>
      <tr>
        <td>Sesión <?php echo $i++; ?></td>
        <td><input type="date" class="form-control" name="fecha<?php echo $fch++; ?>" value="<?php echo muestraFechAsisa($rwSesion['fecha_asistencia']); ?>"></td>
        <td><input type="time" class="form-control" name="hconsulta<?php echo $cst++; ?>" value="<?php echo muestraHorAsisa($rwSesion['hora_asistencia']); ?>"></td>
        <td><input type="time" class="form-control" name="hora<?php echo $hr++; ?>" value="<?php if($tipAsisa > 60 && $tipAsisa < 68) { echo muestraHorAsisa($rwSesion['hora_asistencia']); } ?>" <?php if($desigual==1) {echo "required"; } else { echo "readonly title=\"Se guarda la hora del campo hora. No es necesario especicarlas por separado.\""; } ?>></td>
        <td>
          <input type="hidden" name="desigual" value="<?php echo $desigual; ?>">
          <input type="hidden" name="idasistencia<?php echo $dam++; ?>" value="<?php echo $rwSesion['idasistencia']; ?>">
          <input type="hidden" name="coDemanda" value="<?php echo $codeAsisa; ?>">
          <input type="hidden" name="textAgregar<?php echo $txA++; ?>" value=" <?php echo $menObs; ?>">
          <input type="hidden" name="estServ<?php echo $sta++; ?>" value="<?php echo $estRes; ?>">
          <input type="hidden" name="menGuarda<?php echo $msG++; ?>" value="1">
          <input type="hidden" name="mensalog<?php echo $msL++; ?>" value="<?php echo $mensaLog; ?>">
          <input type="hidden" name="idvtaRes<?php echo $ivC++; ?>" value="<?php echo $calI_V; ?>">
          <input type="hidden" name="hvuelta<?php echo $hrV++; ?>" value="00:00">
          <?php
            echo "<span><strong>".$mensaLog."</strong></span>";
          ?>
        </td>
        </td>
      </tr>
    <?php } } ?>
    </tbody>
  </table>
</div>
