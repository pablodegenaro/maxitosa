<?php 
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require_once 'conexion.php';
require_once 'funciones.php';

if ($_SESSION['login']) {
  ?>
  <!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>APP Rsistems</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/saint_adminlte.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  </head>
  <body class="hold-transition layout-top-nav">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
          <a href="principal.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1" class="navbar-brand">
            <img src="dist/img/AdminLTELogo.png" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Rsistems</span>
          </a>

          <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1" class="nav-link">Inicio</a>
              </li>
              <li class="nav-item">
                <a href="principal1.php?page=despacho_crea&mod=1" class="nav-link">Despachos</a>
              </li>
              <li class="nav-item">
                <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1" class="nav-link">Estadisticas</a>
              </li>
            </ul>
          </div>
          <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">         
            <li class="nav-item">
              <a href="destruir.php" class="nav-link">Cerrar <?php echo $_SESSION['nombre']; ?></a> 
            </li>
          </ul>
        </div>
      </nav>
      <div class="content-wrapper">

        <?php if (isset($_GET['page']) && isset($_GET['mod'])){ 
          if ($_GET['page'] && $_GET['mod']){
            switch ($_GET['mod']) {
              case '1': 
              include("".$_GET['page'].".php");
              break;
            }
          }
          ?>
        <?php }else{ echo 'esta entrando en una redireccion errada'; }?>
      </div>
    </body>
    </html>
    <?php
  } else {
    header('Location: index.php');
  }
?>