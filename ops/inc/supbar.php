<?php
// Datos para identifiación de usuario
$idUseriden = $_SESSION['userId'];


if(isset( $_SESSION['dato_usuario_logado'])){
    $rwIdUser =  $_SESSION['dato_usuario_logado'];
}else{
    $identUser = mysqli_query($gestambu, "SELECT user.userId, user.usNom, user.usApe, user.usImg, cate.idCate, cate.cate, cate.btColor
  FROM user
    LEFT JOIN cate ON user.usCate = cate.idCate
  WHERE userId = '$idUseriden'");
    $rwIdUser = mysqli_fetch_assoc($identUser);
    $_SESSION['dato_usuario_logado'] = $rwIdUser;
}





?>
<header class="main-header">
  <!-- Logo -->
  <a href="#" class="logo">
    <!-- mini logo para barra lateral 50x50 pixels -->
    <span class="logo-mini"><b>G</b>A</span>
    <!-- logo para estado normal y dispositivos móviles-->
    <span class="logo-lg"><b>Gest</b>Ambu</span>
  </a>
  <!-- Navegador de cabecera: estilo en:  header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Botones para barra lateral (ocultar)-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Ocultar navegador</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
    <!-- Notificación Asisa
    <div class="pull-left" style="width:25%; margin-left:25%; padding:15px; color: white;">
      <ul class="nav navbar-nav">
        <li><i class="fa fa-exclamation-triangle"></i> Notificación: Asisa ha enviado nuevo servicio.</li>
      </ul>
    </div>
    -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Mensajes: el estilo se encuentra en dropdown.less-->
        <li>
          <a href="/ops/nuevo/nserv.php" data-toggle="tooltip" data-placement="bottom" title="Nuevo servicio">
            <i class="fa fa-plus" ></i>
          </a>
        </li>
        <li>
          <a href="/ops/servicios/inciops/inciOps.php" data-toggle="tooltip" data-placement="bottom" title="Incidencias en expedientes">
            <i class="fa fa-info" ></i>
          </a>
        </li>		
        <li>
          <a href="/ops/personal/lstpersonal.php" data-toggle="tooltip" data-placement="bottom" title="Personal">
            <i class="fa fa-user" ></i>
          </a>
        </li>
        <li class="dropdown messages-menu" data-toggle="tooltip" data-placement="bottom" title="Buscar">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-search"></i>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Buscador...</li>
            <li>
              <!-- menu interno: contiene los datos: -->
              <ul class="menu">
                <li><!-- comienzo de opcion -->
                  <a href="/ops/buscar/pacientes/buscarpac.php">
                    <div class="pull-left">
                      <i class="fa fa-user"></i>
                    </div>
                    <h4>
                      Paciente
                      <small><i class="fa fa-mail-forward"></i> IR </small>
                    </h4>
                  </a>
                </li>
                <!-- Fin de opcion -->
                <li><!-- comienzo de opcion -->
                  <a href="/ops/buscar/servicios/buscaserv.php">
                    <div class="pull-left">
                      <i class="fa fa-group"></i>
                    </div>
                    <h4>
                      Servicios
                      <small><i class="fa fa-mail-forward"></i> IR </small>
                    </h4>
                  </a>
                </li>
                <!-- Fin de opcion -->
                <li><!-- comienzo de opcion -->
                  <a href="/ops/referencia/supIndex/tablaServ.php">
                    <div class="pull-left">
                      <i class="fa fa-table"></i>
                    </div>
                    <h4>
                      Tabla de servicios
                      <small><i class="fa fa-mail-forward"></i> IR </small>
                    </h4>
                  </a>
                </li>
                <!-- Fin de opcion -->
                <li><!-- comienzo de opcion -->
                  <a href="/ops/buscar/cronicos/buscarcro.php" target="_blank">
                    <div class="pull-left">
                      <i class="fa fa-user-md"></i>
                    </div>
                    <h4>
                      Crónicos / Paliativos
                      <small><i class="fa fa-mail-forward"></i> IR </small>
                    </h4>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <!-- Incidencias -->
        <li data-toggle="tooltip" data-placement="bottom" title="Parte incidencias">
          <a href="#" onclick="abrirVentana()"><i class="fa fa-exclamation-triangle"></i></a>
        </li>
        <!-- listados ambulancia -->
        <li class="dropdown messages-menu" data-toggle="tooltip" data-placement="bottom" title="Libretas">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" >
            <i class="fa fa-ambulance"></i>
          </a>
          <ul class="dropdown-menu">
            <li>
              <!-- menu interno: contiene los datos: -->
              <ul class="menu">
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=29&recuSel=1">
                    <div class="pull-left">
                      <i class="fa">MA</i>
                    </div>
                    <h4>
                      Málaga
                    </h4>
                    <p>Haz click para ir a Libreta de Málaga.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=41&recuSel=1">
                    <div class="pull-left">
                      <i class="fa">SE</i>
                    </div>
                    <h4>
                      Sevilla
                    </h4>
                    <p>Haz click para ir a Libreta de Sevilla.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->				
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=11&recuSel=1">
                    <div class="pull-left">
                      <i class="fa">CA</i>
                    </div>
                    <h4>
                      Cádiz
                    </h4>
                    <p>Haz click para ir a Libreta de Algeciras.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=21&recuSel=1">
                    <div class="pull-left">
                      <i class="fa">HU</i>
                    </div>
                    <h4>
                      Huelva
                    </h4>
                    <p>Haz click para ir a Libreta de Huelva.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=14&recuSel=1">
                    <div class="pull-left">
                      <i class="fa">CO</i>
                    </div>
                    <h4>
                      Córdoba
                    </h4>
                    <p>Haz click para ir a Libreta de Córdoba.</p>
                  </a>
                </li>				
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=41&recuSel=7">
                    <div class="pull-left">
                      <i class="fa">RTSE</i>
                    </div>
                    <h4>
                      Ruta Asepeyo de Sevilla
                    </h4>
                    <p>Haz click para ir a Ruta de Sevilla.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->				
              </ul>
            </li>
          </ul>
        </li>
        <!-- Enfermeria -->
        <li class="dropdown messages-menu" data-toggle="tooltip" data-placement="bottom" title="Enfermería">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-eyedropper"></i>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Enfermería para: </li>
            <li>
              <!-- menu interno: contiene los datos: -->
              <ul class="menu">
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=29&recuSel=2">
                    <div class="pull-left">
                      <i class="fa">MA</i>
                    </div>
                    <h4>
                      Málaga
                    </h4>
                    <p>Haz click para ir a Enfermería de Málaga.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=41&recuSel=2">
                    <div class="pull-left">
                      <i class="fa">SE</i>
                    </div>
                    <h4>
                      Sevilla
                    </h4>
                    <p>Haz click para ir a Enfermería de Sevilla.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=11&recuSel=2">
                    <div class="pull-left">
                      <i class="fa">CA</i>
                    </div>
                    <h4>
                      Cádiz
                    </h4>
                    <p>Haz click para ir a Enfermería de Algeciras.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=21&recuSel=2">
                    <div class="pull-left">
                      <i class="fa">HU</i>
                    </div>
                    <h4>
                      Huelva
                    </h4>
                    <p>Haz click para ir a Enfermería de Huelva.</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
              </ul>
            </li>
            <li class="footer"><a href="#"> </a></li>
          </ul>
        </li>
		<!-- Extracciones -->
	
		<!-- Fin extracciones -->
        <!-- Servicios para guardar -->
        <li class="dropdown notifications-menu" data-toggle="tooltip" data-placement="bottom" title="Asisa - Servicios" >
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-flag-o"></i>
            <span id="muestranum" class="label label-danger"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">ASISA - Opciones para notificación </li>
            <li>
              <!-- menu interno: contiene los datos: -->
              <ul class="menu">
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/referencia/demanda/asisademanda.php">
                    <h6>
                      <i class="fa fa-sticky-note"></i> Lista de servicios para guardar
                      <spam class="pull-right text-aqua"><?php echo @$numGuarda; ?></spam>
                    </h6>
                    <p style="font-size: 0.71em;" class="text-light-blue">Selecciona para ver la lista de servicios</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
                <li><!-- comienzo del mensaje -->
                  <a href="/ops/servicios/libreta/libreta.php?prov=29&recuSel=2">
                    <h6>
                      <i class="fa fa-reply"></i> Enviar notificaciones de estado
                      <spam class="pull-right text-aqua">E</spam>
                    </h6>
                    <p style="font-size: 0.71em;" class="text-light-blue">Notifica a Asisa el estado de los servicioss</p>
                  </a>
                </li>
                <!-- Fin del mensaje -->
              </ul>
            </li>
          </ul>
        </li>
        
        
        
        <li class="dropdown notifications-menu" data-toggle="tooltip" data-placement="bottom" title="Sanitas - Servicios" >
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-fire"></i>
            <span id="muestranum2" class="label label-danger"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Sanitas - Opciones para notificación </li>
            <li>
              <ul class="menu">
                <li>
                  <a href="/ops/referencia/demanda/sanitasdemanda.php">
                    <h6>
                      <i class="fa fa-sticky-note"></i> Lista de servicios para guardar
                      <spam class="pull-right text-aqua"><?php echo @$numGuardaSanita; ?></spam>
                    </h6>
                    <p style="font-size: 0.71em;" class="text-light-blue">Selecciona para ver la lista de servicios</p>
                  </a>
                </li>
                <li>
                  <a href="/ops/servicios/libreta/libreta.php?prov=29&recuSel=2">
                    <h6>
                      <i class="fa fa-reply"></i> Enviar notificaciones de estado
                      <spam class="pull-right text-aqua">E</spam>
                    </h6>
                    <p style="font-size: 0.71em;" class="text-light-blue">Notifica a Sanitas el estado de los servicioss</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </li> 
        
        
        <!-- Cuenta de usuario: estilo en: dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="/ico/users/<?php echo mostrarIcoUser($idUseriden); ?>" class="user-image" alt="User Image">
            <span class="hidden-xs"><?php echo $rwIdUser['usNom']." ".$rwIdUser['usApe']; ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- Foto de usuario -->
            <li class="user-header">
              <img src="/ico/users/<?php echo mostrarIcoUser($idUseriden); ?>" class="img-circle" alt="User Image">

              <p>
                <?php echo $rwIdUser['usNom']." ".$rwIdUser['usApe']." - ".$rwIdUser['cate']; ?>
                <small>Información adicional de usuario</small>
              </p>
            </li>
            <!-- Menu -->
            <li class="user-body">
              <div class="row">
                <div class="col-xs-4 text-center">
                  <a href="#">Enlace 1</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Enlace 2</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Enlace 3</a>
                </div>
              </div>
              <!-- /.row -->
            </li>
            <!-- Menu pie-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat">Perfil</a>
              </div>
              <div class="pull-right">
                <a href="/ops/salir.php" class="btn btn-default btn-flat">Salir</a>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control de sucesos-->
        <!--
		<li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
		-->
      </ul>
    </div>
  </nav>
</header>
<!-- Fin de barra superior -->
<script>
function abrirVentana() {
  open('/ops/referencia/insRelevo.php','','top=50,left=500,width=500,height=400') ;
}
var refreshId = setInterval(function() {
 $.get('/ops/inc/asisanumero.php', function(htmlexterno){
   $("#muestranum").html(htmlexterno);
 });
},20000);
var refreshIdSanitas = setInterval(function() {
 $.get('/ops/inc/sanitasnumero.php', function(htmlexterno){
   $("#muestranum2").html(htmlexterno);
 });
},20000); // Cambiar este valor para incrementar el tiempo 1000 = 1seg
</script>


<script type="text/javascript">
        // Script para limitar el numero de paginas visibles a 4
        var storages = [1,2,3,4]
        var ready= 0;
        for(x=0;x<storages.length;x++){
        console.log('array' + storages[x]);
        console.log(localStorage.getItem('gestambu'+storages[x]));
            if(localStorage.getItem('gestambu'+storages[x]) == null && ready == 0){
               console.log('entre' + storages[x]);
            	localStorage.setItem('gestambu'+storages[x], Date.now());
            	ready =1;
            	break;
            } 
        }
        if(ready == 0){
           alert('demasiadas');
			window.location.href = 'https://www.google.es/';
        }
        window.addEventListener('beforeunload', (event) => {
          	console.log('cerrando');
            var storages = [1,2,3,4]
            var ready= 0;
            for(x=0;x<storages.length;x++){
                if(localStorage.getItem('gestambu'+storages[x]) != null && ready == 0){
                	localStorage.removeItem('gestambu'+storages[x]);
                	ready =1;
                	break;
                } 
            }
                
        });
</script>
