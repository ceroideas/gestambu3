/*  Validar Nuevo Servicio */
/* La validación comienza cuando se pulsa el botón enviar */
/* Valores de tabla servicio
  1	URGENCIA
  2	V_MEDICA
  3	CONSULTA
  4	INYECTABLE
  5	DIALISIS
  6	RADIOTERAPIA
  7	REHABILITACION
  8	LOGOPEDA
  9	SEG_MEDICO
  10	SECUNDARIO
  11	ALTA
  12	AEROPUERTO
  13	TAXI
  14	INGRESO
  15	FESTEJOS
  16	ESCORT
  17	C_TLF
  18	EVENTOS
  19	FIT TO FLY
  20	CURA
  21	ENFERMERIA
  22	EXTRACCION
  23  PREVENTIVO
*/

$(document).ready(function() {
  $(".validar").click(function () {

    $(".error").remove();

    if($("#idCia").val() === "") {
      $("#valCia").focus().after("<span class='error'>No se ha definido una aseguradora.</span>");
      return false;
    } else if($("#prov").val() === "") {
        $("#valProv").focus().after("<span class='error'>No se ha definido una provincia.</span>");
        return false;
    } else if($("#fecha").val() === "") {
        $('#valFecha').focus().after("<span class='error'>Se ha de especificar una fecha.</span>");
        return false;
    } else if($("#hora").val() === "") {
        $('#valHora').focus().after("<span class='error'>Se ha de especificar horario.</span>");
        return false;
    } else if($("#tipo").val() === "") {
        $('#valTipo').focus().after("<span class='error'>Se ha de especificar servicio.</span>");
        return false;
    } else if($("#tipo").val() === "1") { //Validacion para urgencia
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
          if(document.getElementById("idvta").checked) {
            $("#idvta").focus().after("<span class='error'>Una urgencia no es ida/vta.</span>");
            return false;
          }
          if(document.getElementById("ida").checked) {
            $("#ida").focus().after("<span class='error'>Una urgencia no es ida.</span>");
            return false;
          }
          if(document.getElementById("vta").checked) {
            $("#vta").focus().after("<span class='error'>Una urgencia no es vta.</span>");
            return false;
          }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una urgencia no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una urgencia no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una urgencia no puede ser recurso taxi.</span>");
          return false;
        } // fin validación urgencia
    } else if($("#tipo").val() === "2") { //Validacion para v_médica
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una v_médica no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Una v_médica no es ida.</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Una v_médica no es vuelta.</span>");
              return false;
            } else if(document.getElementById("ox").checked) {
              $("#ox").focus().after("<span class='error'>Una v_médica no es oxígeno.</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Una v_médica no es rampa.</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Una v_médica no es 2 técnicos.</span>");
              return false;
            } else if(document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Una v_médica no es enfermero.</span>");
              return false;
            } else if(!document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Una v_médica ha de ser médico.</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Una v_médica no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una v_médica no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Una v_médica no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una v_médica no puede ser recurso taxi.</span>");
          return false;
        } // fin validación v_médica
    } else if($("#tipo").val() === "3") { //Validacion para consulta
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Una consulta ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una consulta no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
            if(!document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una consulta ha de ser ida/vta.</span>");
              return false;
            }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una consulta no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una consulta no puede ser recurso taxi.</span>");
          return false;
        } // fin validación consulta
    } else if($("#tipo").val() === "4") { //Validacion para inyectable
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un iny. no puede ser ida/vta</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un iny. no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un iny. no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("ox").checked) {
              $("#ox").focus().after("<span class='error'>Un iny. no puede ser oxígeno</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Un iny. no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Un iny. no puede ser 2 técnicos</span>");
              return false;
            } else if(document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Un iny. no puede ser medico</span>");
              return false;
            } else if(!document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Ha de estar marcardo</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Un iny. no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Un iny. no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un iny. no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un iny. no puede ser recurso taxi.</span>");
          return false;
        } // fin validación inyectable
    } else if($("#tipo").val() === "5") { //Validacion para diálisis
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Una diálisis ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una diálisis no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Una diálisis ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una diálisis no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una diálisis no puede ser recurso taxi.</span>");
          return false;
        } // fin validación diálisis
    } else if($("#tipo").val() === "6") { //Validacion para radioterápia
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Una radio ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una radio no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
            if(!document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una radio ha de ser ida/vta.</span>");
              return false;
            }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una radio no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una radio no puede ser recurso taxi.</span>");
          return false;
        } // fin validación radioterápia
    } else if($("#tipo").val() === "7") { //Validacion para rehabilitación
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
          if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
            $("#idvta").focus().after("<span class='error'>Una Rhb. ha de ser tener marcado id, vta o ida/vta.</span>");
            return false;
          }
          if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
            $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
            return false;
          }
          if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
            $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
            return false;
          }
          if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
            $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
            return false;
          }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una rhb. no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Una rhb. ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una rhb. no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una rhb. no puede ser recurso taxi.</span>");
          return false;
        } else if($("#recurso").val() === "7") { // ruta
          if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
            $("#idvta").focus().after("<span class='error'>Una Rhb. ha de ser tener marcado id, vta o ida/vta.</span>");
            return false;
          }
          if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
            $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
            return false;
          }
          if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
            $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
            return false;
          }
          if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
            $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
            return false;
          }
        } // fin validación rehabilitación
    } else if($("#tipo").val() === "8") { //Validacion para logopeda
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Un logopeda ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un logopeda no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
            if(!document.getElementById("idvta").checked && !document.getElementById("ida").checked && !document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Un logopeda ha de ser tener marcado id, vta o ida/vta.</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("vta").checked) {
              $("#idvta").focus().after("<span class='error'>Para ida y vuelta hay que marcar solo este campo</span>");
              return false;
            }
            if(document.getElementById("ida").checked && document.getElementById("idvta").checked) {
              $("#ida").focus().after("<span class='error'>Ida no puede estar marcado junto Ida/vta</span>");
              return false;
            }
            if(document.getElementById("vta").checked && document.getElementById("idvta").checked) {
              $("#vta").focus().after("<span class='error'>Vuelta no puede estar marcado junto Ida/vta</span>");
              return false;
            }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un logopeda no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un logopeda no puede ser recurso taxi.</span>");
          return false;
        } // fin validación logopeda
    } else if($("#tipo").val() === "9") { //Validacion para seg_médico
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un seg_médico no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un seg_médico no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un seg_médico no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("ox").checked) {
              $("#ox").focus().after("<span class='error'>Un seg_médico no puede ser oxígeno</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Un seg_médico no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Un seg_médico no puede ser 2 técnicos</span>");
              return false;
            } else if(document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Un seg_médico no es enfermero.</span>");
              return false;
            } else if(!document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Un seg_médico ha de ser médico.</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Un seg_médico no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un seg_médico no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Un seg_médico no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un seg_médico no puede ser recurso taxi.</span>");
          return false;
        } // fin validación seg_médico
    } else if($("#tipo").val() === "10") { //Validacion para secundario
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una consulta no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una consulta no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una consulta no puede ser recurso taxi.</span>");
          return false;
        } // fin validación secundario
    } else if($("#tipo").val() === "11") { //Validacion para alta
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
          } else if($("#recurso").val() === "1") { // ambulancia
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un alta no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un alta no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un alta no puede ser vuelta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un alta no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un alta no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un alta no puede ser recurso taxi.</span>");
          return false;
        } // fin validación alta
    } else if($("#tipo").val() === "12") { //Validacion para aeropuerto
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un aeorpuerto no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un aeorpuerto no puede ser recurso V_médica.</span>");
          return false;
        } // fin validación aeropuerto
    } else if($("#tipo").val() === "13") { //Validacion para taxi
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Un taxi no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un taxi no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Un taxi no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un taxi no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // v_médica
            if(document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Un taxi no es con enfermero.</span>");
              return false;
            } else if(document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Un taxi no es con médico.</span>");
              return false;
            }
        } // fin validación taxi
    } else if($("#tipo").val() === "14") { //Validacion para ingreso
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
          } else if($("#recurso").val() === "1") { // ambulancia
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un ingreso no es ida/vta.</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un ingreso no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          if(document.getElementById("idvta").checked) {
            $("#idvta").focus().after("<span class='error'>Un ingreso no es ida/vta.</span>");
            return false;
          }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un ingreso no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un ingreso no puede ser recurso taxi.</span>");
          return false;
        } // fin validación ingreso
    } else if($("#tipo").val() === "15") { //Validacion para festejo
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
          } else if($("#recurso").val() === "1") { // ambulancia
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un festejo no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un festejo no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un festejo no puede ser vuelta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una festejo no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          if(document.getElementById("idvta").checked) {
            $("#idvta").focus().after("<span class='error'>Un festejo no es ida/vta.</span>");
            return false;
          } else if(document.getElementById("ida").checked) {
            $("#ida").focus().after("<span class='error'>Un festejo no puede ser ida</span>");
            return false;
          } else if(document.getElementById("vta").checked) {
            $("#vta").focus().after("<span class='error'>Un festejo no puede ser vuelta</span>");
            return false;
          }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un festejo no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un festejo no puede ser recurso taxi.</span>");
          return false;
        } // fin validación festejo
    } else if($("#tipo").val() === "16") { //Validacion para escort
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un escort no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un escort no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un escort no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Un escort no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Un escort no puede ser dos técnicos</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Un escort no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un escort no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un escort no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un escort no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Un escort no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Un escort no puede ser dos técnicos</span>");
              return false;
            }
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Un escort no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un escort no puede ser recurso taxi.</span>");
          return false;
        } // fin validación escort
    } else if($("#tipo").val() === "17") { //Validacion para c_tlf
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una c_tlf no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Una c_tlf no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Una c_tlf no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Una c_tlf no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Una c_tlf no puede ser dos técnicos</span>");
              return false;
            } else if(document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Una c_tlf no es enfermero.</span>");
              return false;
            } else if(!document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Una c_tlf ha de ser médico.</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Una c_tlf no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Una c_tlf no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Una c_tlf no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una c_tlf no puede ser recurso taxi.</span>");
          return false;
        } // fin validación c_tlf
    } else if($("#tipo").val() === "18") { //Validacion para eventos
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
          } else if($("#recurso").val() === "1") { // ambulancia
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un evento no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un evento no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un evento no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Un evento no puede ser rampa</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un evento no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          if(document.getElementById("idvta").checked) {
            $("#idvta").focus().after("<span class='error'>Un festejo no es ida/vta.</span>");
            return false;
          } else if(document.getElementById("ida").checked) {
            $("#ida").focus().after("<span class='error'>Un festejo no puede ser ida</span>");
            return false;
          } else if(document.getElementById("vta").checked) {
            $("#vta").focus().after("<span class='error'>Un festejo no puede ser vuelta</span>");
            return false;
          }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un festejo no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un festejo no puede ser recurso taxi.</span>");
          return false;
        } // fin validación eventos
    } else if($("#tipo").val() === "19") { //Validacion para fit to fly
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un fit to fly no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un fit to fly no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un fit to fly no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Un fit to fly no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Un fit to fly no puede ser dos técnicos</span>");
              return false;
            } else if(document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Un fit to fly no es enfermero.</span>");
              return false;
            } else if(!document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Un fit to fly ha de ser médico.</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Un fit to fly no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un fit to fly no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Un fit to fly no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un fit to fly no puede ser recurso taxi.</span>");
          return false;
        } // fin validación fit to fly
    } else if($("#tipo").val() === "20") { //Validacion para cura
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una cura no puede ser ida/vta</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Una cura no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Una cura no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Una cura no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Una cura no puede ser dos técnicos</span>");
              return false;
            } else if(document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Una cura no puede ser medico</span>");
              return false;
            } else if(!document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Ha de estar marcardo</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Una cura no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Una cura no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una cura no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una cura no puede ser recurso taxi.</span>");
          return false;
        } // fin validación cura
    } else if($("#tipo").val() === "21") { //Validacion para enfermería
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una enfermería no puede ser ida/vta</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Una enfermería no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Una enfermería no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Una enfermería no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Una enfermería no puede ser dos técnicos</span>");
              return false;
            } else if(document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Una enfermería no puede ser medico</span>");
              return false;
            } else if(!document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Ha de estar marcardo</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Una enfermería no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Una enfermería no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una enfermería no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una enfermería no puede ser recurso taxi.</span>");
          return false;
        } // fin validación enfermería
    } else if($("#tipo").val() === "22") { //Validacion para extracción
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
        } else if($("#recurso").val() === "2") { // enfermero
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Una extracción no puede ser ida/vta</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Una extracción no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Una extracción no puede ser vuelta</span>");
              return false;
            } else if(document.getElementById("rampa").checked) {
              $("#rampa").focus().after("<span class='error'>Una extracción no puede ser rampa</span>");
              return false;
            } else if(document.getElementById("dostec").checked) {
              $("#dostec").focus().after("<span class='error'>Una extracción no puede ser dos técnicos</span>");
              return false;
            } else if(document.getElementById("medico").checked) {
              $("#medico").focus().after("<span class='error'>Una extracción no puede ser medico</span>");
              return false;
            } else if(!document.getElementById("due").checked) {
              $("#due").focus().after("<span class='error'>Ha de estar marcardo</span>");
              return false;
            }
        } else if($("#recurso").val() === "1") { // ambulancia
          $("#valRecurso").focus().after("<span class='error'>Una extracción no puede ser recurso ambulancia.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          $("#valRecurso").focus().after("<span class='error'>Una extracción no puede ser recurso UVI.</span>");
          return false;
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Una extracción no puede ser recurso v_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Una extracción no puede ser recurso taxi.</span>");
          return false;
        } // fin validación extracción
    } else if($("#tipo").val() === "23") { //Validacion para preventivo
        if($("#recurso").val() === "") { //valor vacio
          $("#valRecurso").focus().after("<span class='error'>Se ha de especificar recurso.</span>");
          return false;
          } else if($("#recurso").val() === "1") { // ambulancia
            if(document.getElementById("idvta").checked) {
              $("#idvta").focus().after("<span class='error'>Un preventivo no es ida/vta.</span>");
              return false;
            } else if(document.getElementById("ida").checked) {
              $("#ida").focus().after("<span class='error'>Un preventivo no puede ser ida</span>");
              return false;
            } else if(document.getElementById("vta").checked) {
              $("#vta").focus().after("<span class='error'>Un preventivo no puede ser vuelta</span>");
              return false;
            }
        } else if($("#recurso").val() === "2") { // enfermero
          $("#valRecurso").focus().after("<span class='error'>Un preventivo no puede ser recurso enfermero.</span>");
          return false;
        } else if($("#recurso").val() === "3") { // UVI
          if(document.getElementById("idvta").checked) {
            $("#idvta").focus().after("<span class='error'>Un preventivo no es ida/vta.</span>");
            return false;
          } else if(document.getElementById("ida").checked) {
            $("#ida").focus().after("<span class='error'>Un preventivo no puede ser ida</span>");
            return false;
          } else if(document.getElementById("vta").checked) {
            $("#vta").focus().after("<span class='error'>Un preventivo no puede ser vuelta</span>");
            return false;
          }
        } else if($("#recurso").val() === "4") { // v_médica
          $("#valRecurso").focus().after("<span class='error'>Un preventivo no puede ser recurso V_médica.</span>");
          return false;
        } else if($("#recurso").val() === "5") { // taxi
          $("#valRecurso").focus().after("<span class='error'>Un preventivo no puede ser recurso taxi.</span>");
          return false;
        } // fin validación preventivo
    } else if($("#nombre").val() === "") {
        $("#valNombre").focus().after("<span class='error'>Se ha de definir al menos el nombre del paciente.</span>");
        return false;
    } else if($("#recoger").val() === "") {
        $("#valRecoger").focus().after("<span class='error'>Especifica el lugar de recogida.</span>");
        return false;
    }
  });
  $("#nombre").keyup(function() {
    if( $(this).val() !== "" ){
        $(".error").fadeOut();
        return false;
      }
  });
  $("#recurso").click(function() {
    if( $(this).val() !== "" ){
        $(".error").fadeOut();
        return false;
      }
  });
  $("#apellidos").keyup(function() {
    if( $(this).val() !== "" ){
        $(".error").fadeOut();
        return false;
      }
  });
});
