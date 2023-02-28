<?php
require ("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

$cod = $_POST['cod'];
$proveedor = $_POST['proveedor'];
$maneja_factor = 1;
$precio_manual = 1;
$flete_me = 0;
$pvp = $_POST['pvp'];
$sugerido = $_POST['sugerido'];
$costo_total = $_POST['costo_total'];
$profit1 = $_POST['profit1'];
$profit2 = $_POST['profit2'];
$profit3 = $_POST['profit3'];
for ($i=0; $i<count($cod); $i++) {

  $query = mssql_query("EXEC [Add_Factor] 
    @CodProd ='$cod[$i]' ,
    @proveedor ='$proveedor[$i]' ,
    @Maneja_Factor =$maneja_factor, 
    @Precio_Manual =$precio_manual,
    @Pvp ='$pvp[$i]', 
    @Sugerido ='$sugerido[$i]', 
    @Costo_Total ='$costo_total[$i]', 
    @Flete_ME ='$flete_me', 
    @Profit1 ='$profit1[$i]', 
    @Profit2 ='$profit2[$i]', 
    @Profit3 ='$profit3[$i]'");
}

echo "<script language=Javascript> location.href=\"principal1.php?page=productos_precios_nuevo_esquema&mod=1\";</script>";