<script>
  const keysNurse = JSON.parse('<?php echo $keysNurse ?>');
  $(function() {


    import('/docs/plugins/datatables/jquery.dataTables.min.js').then(_=>{

      import('/docs/plugins/datatables/dataTables.bootstrap.min.js').then(__=>{
 

        let table = '#table-' + idTableNurse; 
        urlNurse = urlNurse.replace('/gestambu/www','');

        $(table).DataTable({
          //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
          "pageLength": 10,
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
            'url': urlNurse+'?prov=<?= isset($_GET['prov']) ? $_GET['prov'] : 29 ?>&final=<?= isset($esp) && $esp != 0 ? $esp : "" ?>&filFecha=<?= isset($filFecha) ? $filFecha : "" ?>',
            'data': {
              fIni: $('[name=fIni]').val(),
              fFin: $('[name=fFin]').val()
            }
          },
          'columns': keysNurse,
          "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
          },
           "drawCallback": function( settings ) {
              $.each($('[data-bg][data-bg!=""]'),function(a,b){
                let bg = $(b).data('bg');
                $(b).parents('tr').addClass(bg);
              });
               $('.tieneVuelta').editable('/ops/referencia/jeditable/saveIndexEstados.php', {
                 loadurl:'/ops/referencia/jeditable/guardarEstadoCV.php',
                 type   : 'select',
                 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
                 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
               });
               // Edita el cuadro de estado - cuando no hay ida y vuelta
               $('.sinVuelta').editable('/ops/referencia/jeditable/saveIndexEstados.php', {
                 loadurl:'/ops/referencia/jeditable/guardarEstadoSV.php',
                 type   : 'select',
                 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
                 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>',
               });
               //Edita el campo de horas
               $('.hora').editable('/ops/referencia/jeditable/saveIndexHoras.php', {
                 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
                 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'     
               });
               //Listado para vehículos
               $('.vhlist').editable('/ops/referencia/jeditable/savelistadoVh.php', {
                 loadurl:'/ops/referencia/jeditable/listadoVh.php',
                 type   : 'select',
                 cancel: '<button class="btn btn-xs" type="cancel" >Cancelar</button>',
                 submit: '<button class="btn btn-xs btn-primary" type="submit" >Ok</button>'
               });
               //Edita campo técnicos
               $('.editTec').editable('/ops/referencia/jeditable/savePersonal.php', {
                 loadurl:'/ops/referencia/jeditable/listadoTecnicos.php',
                 type   : 'select',
                 submit : 'OK'
               });
               //Edita campo due
               $('.editDue').editable('/ops/referencia/jeditable/savePersonal.php', {
                 loadurl:'/ops/referencia/jeditable/listadoDue.php',
                 type   : 'select',
                 submit : 'OK'
               });
               //Edita campo médico
               $('.editMed').editable('/ops/referencia/jeditable/savePersonal.php', {
                 loadurl:'/ops/referencia/jeditable/listadoMed.php',
                 type   : 'select',
                 submit : 'OK'
               });
            }
        });
      /**/
      });
    });

import('/docs/plugins/jeditable/jquery.jeditable.js').then(__=>{


});

  });



  function copiarAlPortapapeles(id_elemento, copyAnswer) {
   var aux = document.createElement("input");

   aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
   document.body.appendChild(aux);
   aux.select();
   document.execCommand("copy");
   document.body.removeChild(aux);

   var answer = document.getElementById(copyAnswer);
   var successful = document.execCommand('copy');

   if(successful) answer.innerHTML = '¡ Copiado !';
 }
 function limpiar(elementSeleccionado) {
    document.getElementById(elementSeleccionado).innerHTML="";
 }
</script>