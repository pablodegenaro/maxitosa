<?php
require("conexion.php");
session_start();
$latitud = 8.298087;
$longitud = -62.727934;
$cond1 = $cond2 = "";
if ($_POST['edv'] != 'TODOS') {
	$codvend = $_POST['edv'];
	$cond2 = " and codvend = '".$codvend."'";
}
if ($_POST['opc'] == 1) {
	$opc = 'lunes';
	$cond1 = " and dia_visita = '".$opc."'";
}elseif ($_POST['opc'] == 2) {
	$opc = 'martes';
	$cond1 = " and dia_visita = '".$opc."'";
}elseif ($_POST['opc'] == 3) {
	$opc = 'miercoles';
	$cond1 = " and dia_visita = '".$opc."'";
}elseif ($_POST['opc'] == 4) {
	$opc = 'jueves';
	$cond1 = " and dia_visita = '".$opc."'";
}elseif ($_POST['opc'] == 5) {
	$opc = 'viernes';
	$cond1 = " and dia_visita = '".$opc."'";
}
if ($_POST['opc'] == 0 and $_POST['edv'] == 'Todos') {
	$query = mssql_query("SELECT s.codclie, latitud, longitud, dia_visita, s.descrip, codvend from saclie_99 u inner join saclie s on s.codclie = u.codclie where (latitud is not null) and (longitud is not null)");
}else{
	$query = mssql_query("SELECT s.codclie, latitud, longitud, dia_visita, s.descrip, codvend from saclie_99 u inner join saclie s on s.codclie = u.codclie where (latitud is not null) and (longitud is not null) ".$cond1.$cond2);
}
if ($_POST['edv'] == 'Todos') {
	?>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<title>Geolocalización</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="leaflet/leaflet.css" />
		<script src="leaflet/leaflet.js"></script>
		<style>
			#mapidt {
				height: 100%;
				width: 100%;
			}
		</style>
	</head>
	<body>
		<h1>Geolocalización</h1>
		<div id="mapidt"></div>

		<?php
		$array = array();
		for ($i=0; $i < mssql_num_rows($query); $i++) {
			if (trim(mssql_result($query, $i, 'latitud'))) {
				$array[$i] = array(
					'codvend' => trim(mssql_result($query, $i, 'codvend')),
					'descrip' => trim(mssql_result($query, $i, 'descrip')),
					'dia_visita' => strtolower(trim(mssql_result($query, $i, 'dia_visita'))),
					'latitud' => trim(mssql_result($query, $i, 'latitud')),
					'longitud' => trim(mssql_result($query, $i, 'longitud'))
				);
			}
		}
		?>
		<script>
			var latitud = "<?php echo $latitud; ?>";
			var longitud = "<?php echo $longitud; ?>";
			let map = L.map('mapidt').setView([latitud,longitud], 12);
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
			}).addTo(map);
			var i01 = L.icon({
				iconUrl: 'leaflet/images/01.png',
			});
			var i02 = L.icon({
				iconUrl: 'leaflet/images/02.png',
			});
			var i04 = L.icon({
				iconUrl: 'leaflet/images/04.png',
			});
			var i05 = L.icon({
				iconUrl: 'leaflet/images/05.png',
			});
			var i08 = L.icon({
				iconUrl: 'leaflet/images/08.png',
			});
			var i100 = L.icon({
				iconUrl: 'leaflet/images/100.png',
			});
			var i12 = L.icon({
				iconUrl: 'leaflet/images/12.png',
			});
			var i15 = L.icon({
				iconUrl: 'leaflet/images/15.png',
			});
			var i16 = L.icon({
				iconUrl: 'leaflet/images/16.png',
			});
			var i22 = L.icon({
				iconUrl: 'leaflet/images/22.png',
			});
			var i31 = L.icon({
				iconUrl: 'leaflet/images/31.png',
			});
			var i32 = L.icon({
				iconUrl: 'leaflet/images/32.png',
			});
			var i35 = L.icon({
				iconUrl: 'leaflet/images/35.png',
			});
			var marcadores = <?php echo json_encode($array) ?>;
			for (var i = 0; i < marcadores.length; i++) {
				if (marcadores[i].codvend=='01') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: i01}).addTo(map);
				}else if (marcadores[i].codvend=='04') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: i02}).addTo(map);
				}else if (marcadores[i].codvend=='05') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: i04}).addTo(map);
				}else if (marcadores[i].codvend=='12') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: i05}).addTo(map);
				}else if (marcadores[i].codvend=='14') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: i08}).addTo(map);
				}else if (marcadores[i].codvend=='15') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: i100}).addTo(map);
				}
				marcador.bindPopup(marcadores[i].descrip+'<br>Visita: '+marcadores[i].diasvisita+'<br>Ruta: '+marcadores[i].codvend);
			}
		</script>
	</body>
	</html>
	<?php
}else{
	?>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<title>Geolocalización</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="leaflet/leaflet.css" />
		<script src="leaflet/leaflet.js"></script>
		<style>
			#mapid {
				height: 100%;
				width: 100%;
			}
		</style>
	</head>
	<body>
		<h1>Geolocalización</h1>
		<div id="mapid"></div>

		<?php
		$array = array();
		for ($i=0; $i < mssql_num_rows($query); $i++) {
			if (trim(mssql_result($query, $i, 'latitud'))) {
				$array[$i] = array(
					'codvend' => trim(mssql_result($query, $i, 'codvend')),
					'descrip' => trim(mssql_result($query, $i, 'descrip')),
					'dia_visita' => strtolower(trim(mssql_result($query, $i, 'dia_visita'))),
					'latitud' => trim(mssql_result($query, $i, 'latitud')),
					'longitud' => trim(mssql_result($query, $i, 'longitud'))
				);
			}
		}
		?>
		<script>
			var latitud = "<?php echo $latitud; ?>";
			var longitud = "<?php echo $longitud; ?>";
			let map = L.map('mapid').setView([latitud,longitud], 12);
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
			}).addTo(map);
			var martes = L.icon({
				iconUrl: 'leaflet/images/martes.png',
			});
			var miercoles = L.icon({
				iconUrl: 'leaflet/images/miercoles.png',
			});
			var jueves = L.icon({
				iconUrl: 'leaflet/images/jueves.png',
			});
			var lunes = L.icon({
				iconUrl: 'leaflet/images/lunes.png',
			});
			var viernes = L.icon({
				iconUrl: 'leaflet/images/viernes.png',
			});
			var marcadores = <?php echo json_encode($array) ?>;
			for (var i = 0; i < marcadores.length; i++) {
				if (marcadores[i].diasvisita=='martes') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: martes}).addTo(map);
				}else if (marcadores[i].diasvisita=='miercoles') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: miercoles}).addTo(map);
				}else if (marcadores[i].diasvisita=='jueves') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: jueves}).addTo(map);
				}else if (marcadores[i].diasvisita=='lunes') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: lunes}).addTo(map);
				}else if (marcadores[i].diasvisita=='viernes') {
					var marcador = L.marker([marcadores[i].latitud,marcadores[i].longitud], {icon: viernes}).addTo(map);
				}
				marcador.bindPopup(marcadores[i].descrip+'<br>Visita: '+marcadores[i].diasvisita+'<br>Ruta: '+marcadores[i].codvend);
			}
		</script>
	</body>
	</html>
	<?php
}
?>