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
        <a href="#"><i class="fa fa-circle text-success"></i> Conectado</a>
      </div>
    </div>
    <ul class="sidebar-menu">
      <li class="header">-ENLACES * NAVEGADOR -</li>
      <li><a href="/tec/index.php"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-text-o"></i> <span>Registrar</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/tec/registrar/iniGuardia.php"><i class="fa fa-circle-o"></i> Inicio de guardia</a></li>
          <li><a href="#"><i class="fa fa-circle-o"></i> Modificar guardia </a></li>
          <li><a href="/tec/registrar/cambioVh.php"><i class="fa fa-circle-o"></i> Cambio de vehículo</a></li>
          <li><a href="/tec/registrar/finGuardia.php"><i class="fa fa-circle-o"></i> Finalizar guardia</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-th"></i> <span>Servicios</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="/tec/servicios/general.php">
            <i class="fa fa-circle-o"></i>
            General
            </a>
          </li>
          <li><a href="/tec/referencia/partrabajo.php"><i class="fa fa-circle-o"></i> Parte de trabajo</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-ambulance"></i> <span>Vehículo</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="/tec/registrar/repostaje.php"><i class="fa fa-battery-quarter"></i> <span>Repostaje</span></a></li>
          <li><a href="/tec/registrar/peaje.php"><i class="fa fa-money"></i> <span>Peaje</span></a></li>
          <li><a href="#"><i class="fa fa-exclamation-triangle"></i> <span>Indicencia</span></a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
