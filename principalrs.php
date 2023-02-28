<?php 
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");

date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require_once 'conexion.php';
require_once 'permisos/PermisosHelpers.php';
require_once 'permisos/Mssql.php';
require_once 'funciones.php';

if ($_SESSION['login']) 
{
	?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">

        <title>RSISTEMS C.A.</title>
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- sweetalert2 -->
        <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.css">

        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <link rel="stylesheet" href="dist/css/saint_adminlte.min.css">
    </head>
    <body class="hold-transition layout-top-nav dark-mode">
        <div class="wrapper">
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="Rsistems" height="250" width="250">
            </div>

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand-md navbar-light navbar-white text-sm">
                <div class="container">
                    <!-- LOGO SUPERIOR MENU -->
                    <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']);?>&mod=1&s=00000" class="navbar-brand">
                        <img src="dist/img/AdminLTELogo.png " alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                        <span class="brand-text font-weight-light">RSISTEMS APP</span>
                    </a>

                    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                        <!-- Left navbar links -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']);?>&mod=1&s=00000" class="nav-link">
                                    Volver a Principal
                                </a>
                            </li>
                        </ul>
                    </div>
                    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <?php
            $vistas_excepcion_permisos = array();

            if (isset($_GET['page']) && isset($_GET['mod'])) {
                if ($_GET['page'] && $_GET['mod']) {
                    switch ($_GET['mod']) {
                        case '1':
                        include("".$_GET['page'].".php");
                        break;
                    }
                }

            } else { echo 'esta entrando en una redireccion errada'; }
            ?> 
        </body>
        </html>
        <?php
    } else {
       header('Location: index.php');
   }
?>