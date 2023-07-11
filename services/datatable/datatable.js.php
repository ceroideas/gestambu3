<script>
  const keys = JSON.parse('<?php echo $keys ?>');
  $(function() {

    import('/docs/plugins/datatables/jquery.dataTables.min.js').then(_=>{

      import('/docs/plugins/datatables/dataTables.bootstrap.min.js').then(__=>{

        let table = '#table-' + idTable; 
        url = url.replace('/gestambu/www','');

        $(table).DataTable({
          //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
          "pageLength": 10,
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          'ajax': {
            'url': url+'?prov=<?= isset($_GET['prov']) ? $_GET['prov'] : 29 ?>&final=<?= isset($esp) && $esp != 0 ? $esp : "" ?>&filFecha=<?= isset($filFecha) ? $filFecha : "" ?>',
            'data': {
              fIni: $('[name=fIni]').val(),
              fFin: $('[name=fFin]').val()
            }
          },
          'columns': keys,
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
               $('.estadoVuelo').editable('/ops/referencia/jeditable/saveVueloEstados.php', {
                 loadurl:'/ops/referencia/jeditable/guardarEstadoSV.php',
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