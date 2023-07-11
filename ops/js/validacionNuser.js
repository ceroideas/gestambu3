// Validación campos para Nuevo Usuario
//Cuando el documento esta listo ejecuta la función "inicio"
$(document).on("ready",inicio);

function inicio() {
  //Oculta la etiqueta span con la clase help-block
  $("span.help-block").hide();
  //Al hacer click en el elemento #enviar ejecuta la funcion validar
  $("#enviar").click(validar);
}
function validar() {
  //Se crea variable que reciba el valor del elemento a validar
  var valor = document.getElementById("usNom").value;
  //Si el valor del campo es nulo, o si palabra es 0 o si se ha introducido un espacio con el teclado; devuelve falso
  if( valor === null || valor.length === 0 || /^\s+$/.test(valor) ) {
    //Elimina los duplicados de X que hubiera, para solo mostrar 1 x
    $("#icotex").remove();
    //selecciona el div del elemento (2 anteriores) y cambia la clase por otra dada
    $("#usNom").parent().parent().attr("class","form-group has-warning has-feedback");
    //Ingresa texto en la etiqueta span
    $("#usNom").parent().children("span").text("Debe ingresar algún carácter").show();
    //Agrega un elemento a div
    $("#usNom").parent().append("<span id='icotex' class='form-control-feedback glyphicon glyphicon-remove'></span>");
    return false;
  }
  //Sólamente acepta campos numéricos
  /*
  else if( isNaN(valor) ) {
    return false;
  }
  */
  else{
    //Elimina la clase X
    $("#icotex").remove();
    //Si todo está correcto cambia la clase y oculta la etiqueta span
    $("#usNom").parent().parent().attr("class","form-group has-feedback has-success");
    $("#usNom").parent().children("span").text("").hide();
    $("#usNom").parent().append("<span id='icotex' class='form-control-feedback glyphicon glyphicon-ok'></span>");
    return true;
  }
}
