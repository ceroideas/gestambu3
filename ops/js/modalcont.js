// JavaScript Document
function accionSuspTrat() {
  var textMotivo = $("input#motivoSup").val();
  var selector = $("input#valorRh").val();
  var userSup = $("input#userSusp").val();
  var marcador = 1;
  var dataString = 'textMotivo='+ textMotivo + '&selector=' + selector + '&usuarioSup=' + userSup + '&marcador=' + marcador ;

  $.ajax({
        type: "POST",
        url: "/ops/referencia/modals/marcarsuspendertrat.php",
        data: dataString,
        success: function() {
          $('#marcarSusp').html("<div id='message'></div>");
          $('#message').html("<div class='box-body'><p class='text-green'>¡El servicio se ha marcado como EN SUSPENSO! </p></div>")
          .hide()
          .fadeIn(1500, function() {
            $('#message').append("<spam><i class='fa fa-check'></i></spam>");
          });
        }
       });
      return false;
  }
  function reanudarTrat() {
    var selector = $("input#valorRhRea").val();
    var userSup = $("input#userRea").val();
    var marcador = 2;
    var dataString = '&selector=' + selector + '&usuarioSup=' + userSup + '&marcador=' + marcador ;

    $.ajax({
          type: "POST",
          url: "/ops/referencia/modals/marcarsuspendertrat.php",
          data: dataString,
          success: function() {
            $('#reanudar').html("<div id='messageRea'></div>");
            $('#messageRea').html("<div class='box-body'><p class='text-green'>¡Se ha reanudado el servicio! </p></div>")
            .hide()
            .fadeIn(1500, function() {
              $('#message').append("<spam><i class='fa fa-check'></i></spam>");
            });
          }
         });
        return false;
    }
