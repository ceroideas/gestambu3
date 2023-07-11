<?php

if($completado == 1 ) {
  //Completado manual
  for ($i=1; $i <=$sesiones; $i++) {
    echo "
    <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
      <label>$i Sesion: </label>
      <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"\" required >
    </div>";
    }
} else {
  //Completado Automático
  if($pauta == 1 ) {
    //Para cálculo de 24h sumar $i a la fecha dada
    for ($i=1; $i <=$sesiones; $i++) {
      $contSum = $i - 1;
      $mostResul= date('Y-m-d', strtotime($inicio . "+".$contSum." days"));
      echo "
      <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";
      }
  } elseif($pauta == 2) {
    //Para cálculo de 48h multiplicar $i * 2 a la fecha dada
    for ($i=1; $i <=$sesiones; $i++) {
      $contSum = $i - 1;
      $iDoble = $contSum * 2;
      $mostResul= date('Y-m-d', strtotime($inicio . "+".$iDoble." days"));
      echo "
      <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";
      }
  } elseif($pauta == 3) {
    //Para cálculo de 72h multiplicar $i * 3 a la fecha dada
    for ($i=1; $i <=$sesiones; $i++) {
      $contSum = $i - 1;
      $iTriple = $contSum * 3;
      $mostResul= date('Y-m-d', strtotime($inicio . "+".$iTriple." days"));
      echo "
      <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";
      }
  } elseif($pauta == 4) {
    //Para pauta de lunes a viernes
    for ($i=1; $i <=$sesiones; $i++) {
      //Comprobamos que día de la semana es el que nos dan
      $selFecha = $inicio;
      $nfecha = date("w", strtotime($selFecha));

      if($nfecha == 0) {
        //Domingo
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 1) {
        //Lunes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 2) {
        //Martes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 3) {
        //Miércoles
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 4) {
        //Jueves
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 5) {
        //Viernes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 6) {
        //Sábado
        $mostResul= date('Y-m-d', strtotime($selFecha . "+2 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      }

      echo "
      <div class=\"form-group col-md-2 col-sm-2 col-xs-2\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";

      }
  } elseif($pauta == 5) {
    //Para cálculo de pauta lunes - miércoles - viernes
    for ($i=1; $i <=$sesiones; $i++) {
      //Comprobamos que día de la semana es el que nos dan
      $selFecha = $inicio;
      $nfecha = date("w", strtotime($selFecha));

      if($nfecha == 0) {
        //Domingo
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 1) {
        //Lunes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 2) {
        //Martes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 3) {
        //Miércoles
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 4) {
        //Jueves
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 5) {
        //Viernes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 6) {
        //Sábado
        $mostResul= date('Y-m-d', strtotime($selFecha . "+2 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      }

      echo "
      <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";

      }
  } elseif($pauta ==  6) {
    //Para cálculo de pauta martes - jueves - sábados
    for ($i=1; $i <=$sesiones; $i++) {
      //Comprobamos que día de la semana es el que nos dan
      $selFecha = $inicio;
      $nfecha = date("w", strtotime($selFecha));

      if($nfecha == 0) {
        //Domingo
        $mostResul= date('Y-m-d', strtotime($selFecha . "+2 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 1) {
        //Lunes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 2) {
        //Martes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 3) {
        //Miércoles
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 4) {
        //Jueves
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 5) {
        //Viernes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 6) {
        //Sábado
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      }

      echo "
      <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";

      }
  } elseif($pauta == 7) {
    //Para pauta de sábados y domingos
    for ($i=1; $i <=$sesiones; $i++) {
      //Comprobamos que día de la semana es el que nos dan
      $selFecha = $inicio;
      $nfecha = date("w", strtotime($selFecha));

      if($nfecha == 0) {
        //Domingo
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 1) {
        //Lunes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+5 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 2) {
        //Martes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+4 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 3) {
        //Miércoles
        $mostResul= date('Y-m-d', strtotime($selFecha . "+3 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 4) {
        //Jueves
        $mostResul= date('Y-m-d', strtotime($selFecha . "+2 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 5) {
        //Viernes
        $mostResul= date('Y-m-d', strtotime($selFecha . "+1 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      } elseif($nfecha == 6) {
        //Sábado
        $mostResul= date('Y-m-d', strtotime($selFecha . "+0 days"));
        $inicio = date('Y-m-d', strtotime($mostResul . "+1 days"));;
      }

      echo "
      <div class=\"form-group col-md-3 col-sm-3 col-xs-3\">
        <label>$i Sesion: </label>
        <input type=\"date\" class=\"form-control\" name=\"fecha$i\" value=\"".$mostResul."\" required >
      </div>";

      }
  }
}



 ?>
