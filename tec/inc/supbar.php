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
  <a href="/tec/index.php" class="logo">
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

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
<!--
        <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-ambulance"></i>
            <span class="label label-success">4</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Tienes 4 mensajes</li>
            <li>
              <ul class="menu">
                <li>
                  <a href="#">
                    <div class="pull-left">
                      <img src="../docs/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    </div>
                    <h4>
                      Soporte
                      <small><i class="fa fa-clock-o"></i> 5 mins</small>
                    </h4>
                    <p>Ha excedido su periodo de registro.</p>
                  </a>
                </li>

              </ul>
            </li>
            <li class="footer"><a href="#">Ver todos los mensajes.</a></li>
          </ul>
        </li>

        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa  fa-stethoscope"></i>
            <span class="label label-warning">5</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Tiene 5 notificaciones</li>
            <li>

              <ul class="menu">
                <li>
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> 5 incidencias de tiempo
                  </a>
                </li>
              </ul>
            </li>
            <li class="footer"><a href="#">Ver todas</a></li>
          </ul>
        </li>
-->
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

<!-- =============================================== -->
