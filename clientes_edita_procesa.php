<?php
require ("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

$codclie = $_POST['codclie'];
$clasificacion = $_POST['clasificacion'];
$limitecredito = $_POST['limitecredito'];
$ruta_alternativa = $_POST['ruta_alternativa'];
$ruta_alternativa_2 = $_POST['ruta_alternativa_2'];
$frecuencia_visita = $_POST['frecuencia_visita'];
$dia_visita = $_POST['dia_visita'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$ruc = $_POST['ruc'];
$portafolio = $_POST['portafolio'];
$licencia_licor = $_POST['licencia_licor'];
$canal = $_POST['canal'];
$pdv_ocasion = $_POST['pdv_ocasion'];
$formato_cliente = $_POST['formato_cliente'];
$formato_cliente_2 = $_POST['formato_cliente_2'];
$alcance = $_POST['alcance'];
$nivel_ejecucion = $_POST['nivel_ejecucion'];
$tipo = $_POST['tipo'];
$segmentacion = $_POST['segmentacion'];
$convenio = $_POST['convenio'];

$query = mssql_query("UPDATE saclie_99 SET 
                    clasificacion='$clasificacion', 
                    lcredito='$limitecredito',
                    ruta_alternativa='$ruta_alternativa', 
                    ruta_alternativa_2='$ruta_alternativa_2', 
                    dia_visita='$dia_visita', 
                    frecuencia_visita='$frecuencia_visita', 
                    latitud='$latitud', 
                    longitud='$longitud', 
                    ruc='$ruc', 
                    portafolio='$portafolio', 
                    licencia_licor='$licencia_licor', 
                    canal='$canal', 
                    pdv_ocasion='$pdv_ocasion', 
                    formato_cliente='$formato_cliente', 
                    formato_cliente_2='$formato_cliente_2', 
                    alcance='$alcance', 
                    nivel_ejecucion='$nivel_ejecucion', 
                    tipo='$tipo' ,
                    segmentacion='$segmentacion',
                    convenio='$convenio'
                WHERE CodClie='$codclie'");

echo "<script language=Javascript> location.href=\"principal1.php?page=clientes&mod=1\";</script>";

