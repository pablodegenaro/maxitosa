<?php
require ("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

$cod = $_POST['cod'];
$profit4 = $_POST['profit4'];
$profit5 = $_POST['profit5'];
$profit6 = $_POST['profit6'];
$profit7 = $_POST['profit7'];
$profit8 = $_POST['profit8'];

for ($i=0; $i<count($cod); $i++) {

  $query = mssql_query("EXEC [Add_Factor_convenios] 
    @CodProd ='$cod[$i]' ,
    @Profit4 ='$profit4[$i]',
    @Profit5 ='$profit5[$i]',
    @Profit6 ='$profit6[$i]',
    @Profit7 ='$profit7[$i]',
    @Profit8 ='$profit8[$i]'");
}

echo "<script language=Javascript> location.href=\"principal1.php?page=productos_precios_convenios&mod=1\";</script>";