<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");

error_reporting(0);
set_time_limit(0);

$codvend = $_GET['id'];
$supervisor = $_POST['supervisor'];
$ruta = $_POST['ruta'];
$objetivo_kpi = $_POST['objetivo_kpi'];
$clave = $_POST['clave'];
$obj_ventas_bul = $_POST['obj_ventas_bul'];
$obj_ventas_und = $_POST['obj_ventas_und'];
$cedula = $_POST['cedula'];
$obj_ventas_kg = $_POST['obj_ventas_kg'];
$deposito = $_POST['deposito'];
$obj_clientes_captar = $_POST['obj_clientes_captar'];
$obj_bs = $_POST['obj_bs'];
$obj_especial = $_POST['obj_especial'];
$frecuencia = $_POST['frecuencia'];

$output = array();

$query = mssql_query("UPDATE SAVEND_99 SET 
   supervisor='$supervisor', 
   cedula='$cedula', 
   clave='$clave', 
   ubicacion='$deposito', 
   obj_captar='$obj_clientes_captar', 
   obj_especial='$obj_especial', 
   obj_bul_und='$objetivo_kpi', 
   obj_bs='$obj_bs', 
   obj_ventas_kg='$obj_ventas_kg', 
   obj_ventas_bul='$obj_ventas_bul', 
   obj_ventas_und='$obj_ventas_und',
   frecuencia='$frecuencia' 
   where CodVend='$codvend'");

echo "<script language=Javascript> location.href=\"principal1.php?page=vendedores&mod=1\";</script>";