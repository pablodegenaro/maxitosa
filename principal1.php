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

if ($_SESSION['login']) {
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
		<title>Logistica y Despacho</title>
		<!--		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
		<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
		<!--		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
		<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
		<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
		<link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
		<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
		<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
		<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
		<!--		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
		<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
		<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
		<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
		<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
		<!-- Select2 -->
		<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
		<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
		<!-- Bootstrap4 Duallistbox -->
		<link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
		<link rel="stylesheet" href="dist/css/adminlte.min.css">
		<link rel="stylesheet" href="dist/css/saint_adminlte.min.css">		
	</head>
	<body class="hold-transition sidebar-mini layout-fixed">
		<div class="wrapper">
			<div class="preloader flex-column justify-content-center align-items-center">
				<img class="animation__wobble" src="dist/img/AdminLTELogo.png" alt="Rsistems" height="250" width="250">
			</div>
			<nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
					</li>
					<li class="nav-item d-none d-sm-inline-block">
						<a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']);?>&mod=1&s=00000" class="nav-link">
							Principal
						</a>
					</li>
					<?php 
					if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_index.php')) > 0) {
						?>
						<li class="nav-item">
							<a href="principal2.php?page=ventas_index&mod=1" class="nav-link">
								Área de Ventas
							</a>
						</li>
						<?php 
					}
					?>
					<?php 
					if (count(Permisos::verficarPermisoPorSessionUsuario('ventas2_index.php')) > 0) {
						?>
						<li class="nav-item">
							<a href="principal3.php?page=ventas2_index&mod=1" class="nav-link">
								Área de Ventas
							</a>
						</li>
						<?php 
					}
					?>
					<?php 
					if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_index.php')) > 0) {
						?>
						<li class="nav-item">
							<a href="principal4.php?page=ventas3_index&mod=1" class="nav-link">
								Área de Ventas (nuevo Esquema)
							</a>
						</li>
						<?php 
					}
					?>
					<?php 
					if (count(Permisos::verficarPermisoPorSessionUsuario('taller_index.php')) > 0) {
						?>
						<li class="nav-item">
							<a href="principal1.php?page=taller_index&mod=1" class="nav-link">
								Área de Taller
							</a>
						</li>
						<?php 
					}
					?>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" data-widget="fullscreen" href="#" role="button">
							<i class="fas fa-expand-arrows-alt"></i>
						</a>
					</li>
				</ul>
			</nav>
			<?php include "menu_lateral.php"; ?>
			<?php
			$vistas_excepcion_permisos = array('das.php', 'usuarios.php', 'dashboard_default.php');

			if (isset($_GET['page']) && isset($_GET['mod'])) {
				if (!PermisosHelpers::verficarAcceso( $_GET['page'].".php", $vistas_excepcion_permisos)) {
					include ('errorNoTienePermisos.php');
				} else {
					if ($_GET['page'] && $_GET['mod']) {
						switch ($_GET['mod']) {
							case '1':
							include("".$_GET['page'].".php");
							break;
						}
					}
				}
			} else { echo 'esta entrando en una redireccion errada'; }?>
		</div>
	</body>
	</html>
	<?php
} else {
	header('Location: index.php');
}
?>