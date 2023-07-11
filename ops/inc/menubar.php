<!-- =============================================== -->
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
<!-- barra izquierda -->
<aside class="main-sidebar">
  <!-- barra lateral: estilo en: sidebar.less -->
  <section class="sidebar">
    <!-- Panel de usuario -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="/ico/users/<?php echo mostrarIcoUser($idUseriden); ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $rwIdUser['usNom']." ".$rwIdUser['usApe']; ?></p>
        <a href="#"><i class="fa fa-circle text-<?php echo $rwIdUser['btColor']; ?>"></i> <?php echo $rwIdUser['cate']; ?></a>
      </div>
    </div>
    <!-- menu lateral: : estilo en: sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">-ENLACES * NAVEGADOR -</li>
      <li><a href="/ops/index.php?prov=29"><i class="fa fa-home"></i> <span>Inicio(MA)</span></a></li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-text-o"></i> <span>Nuevo</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/nuevo/nserv.php"><i class="fa fa-file"></i> Servicio</a></li>
          <li><a href="/ops/nuevo/contSelec.php"><i class="fa fa-list-ol"></i> Continuado</a></li>
          <li><a href="/ops/nuevo/cronico.php"><i class="fa fa-user-md"></i> Paciente crónico</a></li>
        </ul>
      </li>
      <li>
        <a href="/ops/docs/documentos.php">
          <i class="fa fa-folder"></i> <span>Documentos</span>
        </a>		
	  </li>
	  <li class="treeview">
          <a href="#">
            <i class="fa fa fa-bar-chart-o"></i> <span>Estadísticas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <!-- Ejemplo: Colocar número en el menú
          <span class="pull-right-container">
            <span class="label label-primary pull-right">4</span>
          </span>
          -->
        <ul class="treeview-menu">
          <li><a href="/ops/estadisticas/diario.php"><i class="fa fa-bar-chart"></i> Diario</a></li>
		  <?php if($idUseriden == '6' || $idUseriden == '1') { ?>
          <li><a href="/ops/estadisticas/contarfechas.php"><i class="fa fa-calendar"></i> Entre fechas</a></li>
		  <li><a href="/ops/estadisticas/contarfechasidvta.php"><i class="fa fa-clone"></i> Con id/vta</a></li>
		  <?php } ?>		  
          <li><a href="/ops/estadisticas/computo.php"><i class="fa fa-calculator"></i> Cómputo</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-th"></i> <span>Continuados</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
          <!-- Ejemplo agregar etiqueta con texto
          <span class="pull-right-container">
            <small class="label pull-right bg-green">Hot</small>
          </span>
          -->
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/continuados/continuados.php?prov=11"><i class="fa fa-circle-o"></i> Cádiz</a></li>
		  <li><a href="/ops/continuados/continuados.php?prov=14"><i class="fa fa-circle-o"></i> Córdoba</a></li>
          <li><a href="/ops/continuados/continuados.php?prov=29"><i class="fa fa-circle-o"></i> Málaga</a></li>
		  <li><a href="/ops/continuados/continuados.php?prov=52"><i class="fa fa-circle-o"></i> Melilla</a></li>
          <li><a href="/ops/continuados/continuados.php?prov=41"><i class="fa fa-circle-o"></i> Sevilla</a></li>
		  <li><a href="/ops/continuados/continuados.php?prov=21"><i class="fa fa-circle-o"></i> Huelva</a></li>
          <li><a href="/ops/continuados/rutascont.php"><i class="fa fa-taxi"></i> Rutas</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-plane"></i><span>Vuelos</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/nuevo/vuelo.php"><i class="fa fa-circle-o"></i> Nuevo Vuelo</a></li>
          <li><a href="/ops/listados/vuelos.php"><i class="fa fa-circle-o"></i> Listado de vuelos</a></li>
          <li><a href="/ops/referencia/supIndex/tablaVuelos.php"><i class="fa fa-circle-o"></i> Buscador de vuelos</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-medkit"></i>
          <span>Paciente Crónico</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/nuevo/cronico.php"><i class="fa fa-circle-o"></i> Nuevo paciente</a></li>
          <li><a href="/ops/mostrar/segmedico.php"><i class="fa fa-circle-o"></i> Listado pacientes</a></li>
          <li><a href="/ops/buscar/seguimiento/tablSeg.php"><i class="fa fa-circle-o"></i> Tabla de seguimiento</a></li>
		  <li><a href="/ops/referencia/cambiomedico.php"><i class="fa fa-refresh"></i> Cambio de médico</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-list"></i> <span>Listados</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Servicios
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="#"><i class="fa fa-circle-o"></i> Libreta
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=11&recuSel=1"><i class="fa fa-circle-o"></i> Cádiz</a></li>
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=14&recuSel=1"><i class="fa fa-circle-o"></i> Córdoba</a></li>				  
                  <li><a href="/ops/servicios/libreta/libreta.php?prov=29&recuSel=1"><i class="fa fa-circle-o"></i> Málaga</a></li>
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=52&recuSel=1"><i class="fa fa-circle-o"></i> Melilla</a></li>                  
                  <li><a href="/ops/servicios/libreta/libreta.php?prov=41&recuSel=1"><i class="fa fa-circle-o"></i> Sevilla</a></li>
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=21&recuSel=1"><i class="fa fa-circle-o"></i> Huelva</a></li>
                </ul>
              </li>
              <li>
                <a href="#"><i class="fa fa-circle-o"></i> Enfermería
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=11&recuSel=2"><i class="fa fa-circle-o"></i> Cádiz</a></li>
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=14&recuSel=2"><i class="fa fa-circle-o"></i> Córdoba</a></li>
                  <li><a href="/ops/servicios/libreta/libreta.php?prov=29&recuSel=2"><i class="fa fa-circle-o"></i> Málaga</a></li>
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=52&recuSel=2"><i class="fa fa-circle-o"></i> Melilla</a></li>                  
                  <li><a href="/ops/servicios/libreta/libreta.php?prov=41&recuSel=2"><i class="fa fa-circle-o"></i> Sevilla</a></li>
				  <li><a href="/ops/servicios/libreta/libreta.php?prov=21&recuSel=2"><i class="fa fa-circle-o"></i> Huelva</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Actividad
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-circle-o"></i> Cádiz</a></li>
			  <li><a href="#"><i class="fa fa-circle-o"></i> Córdoba</a></li>
			  <li><a href="#"><i class="fa fa-circle-o"></i> Málaga</a></li>
              <li><a href="#"><i class="fa fa-circle-o"></i> Melilla</a></li>
              <li><a href="#"><i class="fa fa-circle-o"></i> Sevilla</a></li>
			  <li><a href="#"><i class="fa fa-circle-o"></i> Huelva</a></li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Selección
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="/ops/listados/general.php"><i class="fa fa-circle-o"></i> General</a></li>
              <li><a href="/ops/listados/continuados.php""><i class="fa fa-circle-o"></i> Continuados</a></li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Específico
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="/ops/listados/asisaMa.php"><i class="fa fa-circle-o"></i> Asisa Málaga</a></li>
              <li><a href="/ops/listados/resactividad.php""><i class="fa fa-circle-o"></i> Res. Actividad</a></li>
            </ul>
          </li>		  
        </ul>
      </li>
      <!-- menu restringido -->
      <?php if($_SESSION['usCate'] < 3 ) { ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-laptop"></i> <span>Gestión</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Gestión interna
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="#"><i class="fa fa-circle-o"></i> Trabajador
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Registrar</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Consultar</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Inactivos</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Seguros</a></li>
                </ul>
              </li>
              <li>
                <a href="#"><i class="fa fa-circle-o"></i> Vehículos
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Registrar</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Ingresar gasóil</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Ingresar peaje</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Aseguradora
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-circle-o"></i> Registrar</a></li>
              <li><a href="#"><i class="fa fa-circle-o"></i> Consultar</a></li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="fa fa-circle-o"></i> Resgistrar
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-circle-o"></i> Tipo de servicio</a></li>
              <li><a href="#"><i class="fa fa-circle-o"></i> Tipo de recurso</a></li>
            </ul>
          </li>
          <li><a href="#"><i class="fa fa-circle-o"></i> Presupuesto</a></li>
        </ul>
      </li>
      <?php } ?>
      <!--/. menu restringido -->
      <li><a href="/ops/relevo/parteInci.php"><i class="fa fa-calendar-minus-o"></i> <span>Relevo</span></a></li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-edit"></i> <span>Buscar</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/referencia/supIndex/tablaServ.php"><i class="fa fa-circle-o"></i> Tabla de servicios</a></li>
          <li><a href="/ops/buscar/pacientes/buscarpac.php"><i class="fa fa-circle-o"></i> Pacientes</a></li>
          <li><a href="/ops/buscar/servicios/buscaserv.php"><i class="fa fa-circle-o"></i> Servicios</a></li>
          <li><a href="/ops/buscar/croinicos/buscarcro.php"><i class="fa fa-circle-o"></i> Crónicos/paliativos</a></li>
        </ul>
      </li>
      <!-- menu restringido -->
      <li class="treeview">
        <a href="#">
          <i class="fa fa-table"></i> <span>Coordinación</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/coordinacion/ncia.php"><i class="fa fa-circle-o"></i> Nueva Aseguradora</a></li>
          <li><a href="/ops/soporte/nuser.php"><i class="fa fa-circle-o"></i> Nuevo usuario</a></li>
        </ul>
      </li>
      <?php if($_SESSION['usCate'] < 3 ) { ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-table"></i> <span>Soporte</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/ops/soporte/nuser.php"><i class="fa fa-circle-o"></i> Nuevo usuario</a></li>
          <li>
            <a href="#"><i class="fa fa-circle-o text-yellow"></i> Vehículos
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="/ops/soporte/nwVehiculo.php"><i class="fa fa-circle-o "></i> Nuevo Vehículo</a></li>
            </ul>
          </li>
          <li>
            <a href="#"><i class="fa fa-circle-o text-yellow"></i> Funcionalidad
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="/ops/soporte/categorias.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
              <li><a href="/ops/soporte/tipo.php"><i class="fa fa-circle-o"></i> Servicio</a></li>
              <li><a href="/ops/soporte/recurso.php"><i class="fa fa-circle-o"></i> Recurso</a></li>
              <li><a href="/ops/soporte/estados.php"><i class="fa fa-circle-o"></i> Estados</a></li>
              <li><a href="/ops/soporte/msjLog.php"><i class="fa fa-circle-o"></i> Mensajes Log</a></li>
              <li><a href="/ops/soporte/codasisa.php"><i class="fa fa-circle-o"></i> Código Asisa</a></li>
			  <li><a href="/ops/soporte/nuevaruta.php"><i class="fa fa-taxi"></i> Nueva Ruta</a></li>
            </ul>
          </li>
        </ul>
      </li>
      <?php } ?>
      <!--/. menu restringido -->
      <!-- <li><a href="../../documentation/index.html"><i class="fa fa-book"></i> <span>Documentación</span></a></li> -->
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
