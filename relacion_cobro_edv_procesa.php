<?php 
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
    $lista = $_POST['check_lista'];
    $usuario = $_POST['usuario'];
    $sucursal = $_POST['sucu'];
    $convend = $_POST['vend'];
    $fechahoy = date('Y-m-d H:i:s');

    if (count($lista) > 0) {

        $correl1 = mssql_query("SELECT max(NroRelacion) as NroRelacion from app_relacion_cobros ");
        if ( mssql_result($correl1, 0, 'NroRelacion') >= 1) {
            $correl =  mssql_result($correl1, 0, 'NroRelacion') + 1; 
        } else {
            $correl = 1 ; 
        }

        $header = mssql_query("INSERT INTO app_relacion_cobros (NroRelacion, CodUsua, CodVend, CodSucu, FechaE) 
            VALUES ('$correl','$usuario','$convend','$sucursal','$fechahoy')");
        //obtenemos el nuevo id
        $query = mssql_query("SELECT SCOPE_IDENTITY() as id");
        $id = mssql_result($query, 0, "id");

        foreach ($lista as $key => $item) {
            $arr = explode(",", $item);
            $numerod = $arr[0];
            $tipofac = $arr[1];

            $query = mssql_query("SELECT numerod, TipoFac, CodClie, Descrip, fechae, FechaV, MtoTotal/FactorP AS total
                FROM SAFACT WHERE NumeroD='$numerod' AND TipoFac='$tipofac'");

            if (mssql_num_rows($query) > 0) {
                $codclie = mssql_result($query, 0, "CodClie");
                $rsocial = mssql_result($query, 0, "Descrip");
                if (strpos($rsocial, "'") !== false) {
                    $rsocial = str_replace("'","''",$rsocial);
                }
                $emision = mssql_result($query, 0, "fechae");
                $vencimiento = mssql_result($query, 0, "FechaV");
                $monto = mssql_result($query, 0, "total");
                $fechae = date("Y-m-d", strtotime($emision));
                $fechav = date("Y-m-d", strtotime($vencimiento));

                $header = mssql_query("INSERT INTO app_relacion_cobros_items (id_relacion, numerod, codclie, rsocial, emision, vencimiento, monto, vendedor, codsucu, tipofac) 
                    VALUES ('$correl','$numerod','$codclie','$rsocial','$fechae','$fechav','$monto','$convend','$sucursal','$tipofac')");
            }
        }
    }


    echo "<script language=Javascript> location.href=\"principal1.php?page=relacion_cobro_edv&mod=1\";</script>";
    
} else {
    header('Location: index.php');
}
?>