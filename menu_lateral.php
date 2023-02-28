<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- LOGO SUPERIOR MENU -->
    <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1" class="brand-link">
        <img src="dist/img/AdminLTELogo.png " alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">RSISTEMS APP</span>
    </a>
    <!-- PERFIL DE USUARIO -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/icon_human.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION["nombre_p"]; ?></a>
                <input id="id" type="hidden" value="<?php echo $_SESSION['login']; ?>"/>
            </div>
        </div>
        <!-- MENU LATERAL -->
        <nav id="content_menu" class="mt-2"> 
            <ul class="nav nav-pills nav-sidebar  flex-column nav-flat nav-legacy nav-compact text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <!-- INICIO -->
                <li class="nav-item">
                    <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']);?>&mod=1&s=00000" class="nav-link">
                        <i class="fas fa-home nav-icon"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <!-- CERRAR SESION -->
                <li class="nav-item">
                    <a href="destruir.php" class="nav-link">
                        <i class="fas fa-power-off"></i>
                        <p> Cerrar sesión</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>