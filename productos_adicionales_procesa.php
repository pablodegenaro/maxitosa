<?php
require ("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

$cod = $_POST['cod'];
$capacidad_botella = $_POST['capacidad_botella'];
$proveedor = $_POST['proveedor'];
$casa_representacion = $_POST['casa_representacion'];
$clasificacion_categoria = $_POST['clasificacion_categoria'];
$sub_clasificacion_categoria = $_POST['sub_clasificacion_categoria'];
$grado_alcoholico = $_POST['grado_alcoholico'];

for ($i=0; $i<count($cod); $i++) {

    $query = mssql_query("select * from SAPROD_99 where CodProd='$cod[$i]'");
    if (mssql_num_rows($query) > 0) {
        $query = mssql_query("UPDATE SAPROD_99 SET 
            capacidad_botella='$capacidad_botella[$i]',
            proveedor='$proveedor[$i]',
            casa_representacion='$casa_representacion[$i]',
            clasificacion_categoria='$clasificacion_categoria[$i]',
            sub_clasificacion_categoria='$sub_clasificacion_categoria[$i]',
            grado_alcoholico='$grado_alcoholico[$i]'
            WHERE CodProd='$cod[$i]'");
    } else {
        $query = mssql_query("INSERT INTO SAPROD_99 (capacidad_botella, proveedor, casa_representacion,
            clasificacion_categoria,sub_clasificacion_categoria,grado_alcoholico, CodProd)
        VALUES ('$capacidad_botella[$i]','$proveedor[$i]','$casa_representacion[$i]',
            '$clasificacion_categoria[$i]','$sub_clasificacion_categoria[$i]','$grado_alcoholico[$i]','$cod[$i]')");
    }
}

echo "<script language=Javascript> location.href=\"principal1.php?page=productos_adicionales&mod=1\";</script>";