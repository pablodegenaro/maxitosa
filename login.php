<?php
require "conexion.php";
if (@session_start() == false) {
    session_destroy();
    session_name('S1sTem@RsIsT3m@#$%$@pP');
    session_start();
}
$user= $_POST['user'];
$clave= $_POST['clave'];
$consulta_user = mssql_query("SELECT * from (
    SELECT codusUa, descrip, RTRIM(LTRIM(SUBSTRING(SDATA2,193,1)+SUBSTRING(SDATA1,77,1))) AS nivel,
    RTRIM(LTRIM(SUBSTRING(SDATA3,175,1)+SUBSTRING(SDATA1,33,1)+SUBSTRING(SDATA2,90,1)+SUBSTRING(SDATA3,14,1)+SUBSTRING(SDATA1,207,1)+
        SUBSTRING(SDATA3,111,1)+SUBSTRING(SDATA3,145,1)+SUBSTRING(SDATA2,180,1)+SUBSTRING(SDATA2,9,1)+SUBSTRING(SDATA3,53,1))) as clave 
    from ssusrs) as innertable where codusUa=  '$user' and  clave = '$clave'");

if (mssql_num_rows($consulta_user) != 0) {
    $dashboard = mssql_query("SELECT modulos.id, nombre, ruta FROM Modulos 
        INNER JOIN Roles_app rol ON rol.dashboard = modulos.ruta
        INNER JOIN SSUSRS usr ON usr.rol_id = rol.id
        WHERE ruta LIKE 'dashboard_%' AND usr.CodUsua = '$user'");

    $_SESSION['login']    = mssql_result($consulta_user, 0, "codusUa");
    $_SESSION['tipo_usu'] = mssql_result($consulta_user, 0, "nivel");
    $_SESSION['nombre_p']   = mssql_result($consulta_user, 0, "descrip");
    $_SESSION['dashboard']   = (mssql_num_rows($dashboard) > 0)
    ? mssql_result($dashboard, 0, "ruta")
    : "dashboard_default.php";
    header("Location: principal1.php?page=". str_replace(".php", "", $_SESSION['dashboard'])."&mod=1&s=00000");
} else {
    header('Location: index.php');
}
