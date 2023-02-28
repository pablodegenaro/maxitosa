<?php
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require_once ("permisos/Mssql.php");

$cubo =$_POST['cubo'];
$fechai = $_POST['fechai'];//.' 00:00:00';
$fechaf = $_POST['fechaf'];//.' 23:59:59';

$newperiodo = date("Ym", strtotime($fechai));

$fecha = date('Y-m-d');

switch (true) {
    # =============================================================
    # === ALMACENES ==== 
    # =============================================================
    case ($cubo=="1"):
    $file='';
    $file = "Almacen.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT CodUbic,Descrip from sadepo where CodUbic in ('1000','2000','3000')");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodUbic = mssql_result($query, $i, "CodUbic");
        $Descrip = mssql_result($query, $i, "Descrip");


        $output .= str_pad($CodUbic, 1).";";
        $output .= str_pad($Descrip, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

    break;

    # ====================================================================
    # === CLASIFICACION CLIENTES ==== 
    # ====================================================================
    case ($cubo=="2"):
    $file='';
    $file = "ClasCli.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT b.CodClie,d.segmentacion as segment_code,'0' as segment, d.canal, d.tipo from SAITEMFAC as a inner join SAFACT as b on a.TipoFac=b.TipoFac and a.NumeroD=b.NumeroD inner join SAPROD_99 as c on a.CodItem=c.CodProd inner join SACLIE_99 as d on b.CodClie=d.CodClie where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai'  and '$fechaf' and c.proveedor='DIAGEO' and a.tipofac IN ('A','C') order by b.CodClie");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodClie = mssql_result($query, $i, "CodClie");
        $segment_code = mssql_result($query, $i, "segment_code");
        $segment = mssql_result($query, $i, "segment");
        $canal = mssql_result($query, $i, "canal");
        $tipo = mssql_result($query, $i, "tipo");


        $output .= str_pad($CodClie, 1).";";
        $output .= str_pad($segment_code, 1).";";
        $output .= str_pad($segment, 1).";";
        $output .= str_pad($canal, 1).";";
        $output .= str_pad($tipo, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);
  # ====================================================================
    # === CLASIFICACION PRODUCTOS ==== 
    # ====================================================================
    case ($cubo=="3"):
    $file='';
    $file = "Clasprod.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT a.CodItem, a.NroLinea,b.clasificacion_categoria,b.sub_clasificacion_categoria,'' as codigo_grupo ,b.sub_clasificacion_categoria,'' as codigo_marca,c.Marca  from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd inner join SAPROD as c on a.CodItem=c.CodProd where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai'  and '$fechaf' and b.proveedor='DIAGEO' and a.tipofac IN ('A','C') order by a.CodItem");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $Coditem = mssql_result($query, $i, "Coditem");
        $NroLinea = mssql_result($query, $i, "NroLinea");
        $clasificacion_categoria = mssql_result($query, $i, "clasificacion_categoria");
        $codigo_grupo = mssql_result($query, $i, "codigo_grupo");
        $sub_clasificacion_categoria = mssql_result($query, $i, "sub_clasificacion_categoria");
        $codigo_marca = mssql_result($query, $i, "codigo_marca");
        $Marca = mssql_result($query, $i, "Marca");

        $output .= str_pad($Coditem, 1).";";
        $output .= str_pad($NroLinea, 1).";";
        $output .= str_pad($clasificacion_categoria, 1).";";
        $output .= str_pad($codigo_grupo, 1).";";
        $output .= str_pad($sub_clasificacion_categoria, 1).";";        
        $output .= str_pad($codigo_marca, 1).";";
        $output .= str_pad($Marca, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

   # ====================================================================
    # === CLASIFICACION TERRITORIOS ==== 
    # ====================================================================
    case ($cubo=="4"):
    $file='';
    $file = "Clasterr.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT b.CodClie, d.Descrip,'0' AS A,'0' AS B,'0' AS C,'0' AS D,'0' AS E from SAITEMFAC as a inner join SAFACT as b on a.NumeroD=b.NumeroD and a.TipoFac=b.TipoFac inner join SACLIE as c on b.CodClie=c.CodClie inner join SAZONA as d on c.CodVend=d.CodZona inner join SAPROD_99 as e on a.CodItem=e.CodProd where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai'  and '$fechaf' and e.proveedor='DIAGEO' and a.tipofac IN ('A','C') group by b.CodClie, d.Descrip");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodClie = mssql_result($query, $i, "CodClie");
        $Descrip = mssql_result($query, $i, "Descrip");
        $A = mssql_result($query, $i, "A");
        $B = mssql_result($query, $i, "B");
        $C = mssql_result($query, $i, "C");
        $D = mssql_result($query, $i, "D");
        $E = mssql_result($query, $i, "E");

        $output .= str_pad($CodClie, 1).";";
        $output .= str_pad($Descrip, 1).";";
        $output .= str_pad($A, 1).";";
        $output .= str_pad($B, 1).";";
        $output .= str_pad($C, 1).";";
        $output .= str_pad($D, 1).";";
        $output .= str_pad($E, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

 # ====================================================================
    # === CLIENTES ==== 
    # ====================================================================
    case ($cubo=="5"):
    $file='';
    $file = "Clientes.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT b.CodClie, b.Descrip, b.CodVend,'0' AS A from SAITEMFAC as a inner join SAFACT as b on a.NumeroD=b.Numerod and a.TipoFac=b.TipoFac inner join SAPROD_99 as c on a.CodItem=c.CodProd where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai'  and '$fechaf' and c.proveedor='DIAGEO' and a.tipofac IN ('A','C') group by b.CodClie, b.Descrip, b.CodVend order by b.codvend");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodClie = mssql_result($query, $i, "CodClie");
        $Descrip = mssql_result($query, $i, "Descrip");
        $CodVend = mssql_result($query, $i, "CodVend");
        $A = mssql_result($query, $i, "A");

        $output .= str_pad($CodClie, 1).";";
        $output .= str_pad($Descrip, 1).";";
        $output .= str_pad($CodVend, 1).";";
        $output .= str_pad($A, 1).";";
        $output .= "\n";

        $serial += 1;
    }


    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);
# ====================================================================
    # === DIAS DE VISITAS ==== 
    # ====================================================================
    case ($cubo=="6"):
    $file='';
    $file = "Diasvisita.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT b.CodClie, b.CodVend, d.dia_visita, CONVERT(varchar,GETDATE(),112) as  Fecha  from SAITEMFAC as a inner join SAFACT as b on a.NumeroD=b.Numerod and a.TipoFac=b.TipoFac inner join SAPROD_99 as c on a.CodItem=c.CodProd  inner join saclie_99 as d on b.CodClie=d.CodClie where DATEADD
        (dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai'  and '$fechaf' and c.proveedor='DIAGEO' and a.tipofac IN ('A','C') group by b.CodClie, d.dia_visita, b.CodVend order by b.CodVend");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodClie = mssql_result($query, $i, "CodClie");
        $CodVend = mssql_result($query, $i, "CodVend");
        $dia_visita = mssql_result($query, $i, "dia_visita");
        $fecha = mssql_result($query, $i, "fecha");

        $output .= str_pad($CodClie, 1).";";
        $output .= str_pad($CodVend, 1).";";
        $output .= str_pad($dia_visita, 1).";";
        $output .= str_pad($fecha, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

# ====================================================================
    # === EXISTENCIAS ==== 
    # ====================================================================
    case ($cubo=="7"):
    $file='';
    $file = "Existencias.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT a.codubic, b.CodProd Material,CAST((a.Existen*b.CantEmpaq)+a.ExUnidad as int) as Botellas, '0' as Valor_Total, '0' as Monto_IVA, CONVERT(varchar,GETDATE(),112) as  Fecha  from SAEXIS as a left join saprod as b on b.codprod=a.codprod where a.CodUbic in ('1000','2000','3000') and a.codprod in (select CodProd from saprod_99 where proveedor ='DIAGEO') order by a.CodUbic");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $codubic = mssql_result($query, $i, "codubic");
        $Material = mssql_result($query, $i, "Material");
        $Botellas = mssql_result($query, $i, "Botellas");
        $Valor_Total = mssql_result($query, $i, "Valor_Total");
        $Monto_IVA = mssql_result($query, $i, "Monto_IVA");
        $Fecha = mssql_result($query, $i, "Fecha");

        $output .= str_pad($codubic, 1).";";
        $output .= str_pad($Material, 1).";";
        $output .= str_pad($Botellas, 1).";";
        $output .= str_pad($Valor_Total, 1).";";
        $output .= str_pad($Monto_IVA, 1).";";
        $output .= str_pad($Fecha, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);
# ====================================================================
    # === INVENTARIOS ==== 
    # ====================================================================
    case ($cubo=="8"):
    $file='';
    $file = "Inventarios.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT CodUbic from sadepo where CodUbic in ('1000','2000','3000')");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodUbic = mssql_result($query, $i, "CodUbic");

        $output .= str_pad($CodUbic, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);
# ====================================================================
    # === PRODUCTOS ==== 
    # ====================================================================
    case ($cubo=="9"):
    $file='';
    $file = "Productos.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT a.CodItem, a.Descrip1, c.CantEmpaq, sum((case a.EsUnid when 0 then a.cantidad*c.CantEmpaq else cantidad end )) as cantidad, d.capacidad_botella from SAITEMFAC as a inner join SAFACT as b on a.NumeroD=b.NumeroD and a.TipoFac=b.TipoFac inner join SAPROD as c on a.CodItem=c.CodProd  inner join SAPROD_99 as d on c.CodProd=d.CodProd where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai'  and '$fechaf' and d.proveedor='DIAGEO' and a.tipofac IN ('A','C') group by a.CodItem, a.Descrip1, c.CantEmpaq, d.capacidad_botella");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodItem = mssql_result($query, $i, "CodItem");
        $Descrip1 = mssql_result($query, $i, "Descrip1");
        $CantEmpaq = mssql_result($query, $i, "CantEmpaq");
        $Cantidad = mssql_result($query, $i, "Cantidad");
        $capacidad_botella = mssql_result($query, $i, "capacidad_botella");

        $output .= str_pad($CodItem, 1).";";
        $output .= str_pad($Descrip1, 1).";";
        $output .= str_pad($CantEmpaq, 1).";";
        $output .= str_pad($Cantidad, 1).";";
        $output .= str_pad($capacidad_botella, 1).";";
        $output .= "\n";

        $serial += 1;
    }

    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

# ====================================================================
    # === SUPERVISORES ==== 
    # ====================================================================
    case ($cubo=="10"):
    $file='';
    $file = "Supervisores.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT DISTINCT(supervisor),'0' AS A,'0' AS B from SAVEND_99");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $supervisor = mssql_result($query, $i, "supervisor");
        $A = mssql_result($query, $i, "A");
        $B = mssql_result($query, $i, "B");

        $output .= str_pad($supervisor, 1).";";
        $output .= str_pad($A, 1).";";
        $output .= str_pad($B, 1).";";
        $output .= "\n";

        $serial += 1;
    }


    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);
# ====================================================================
    # === VENDEDORES ==== 
    # ====================================================================
    case ($cubo=="11"):
    $file='';
    $file = "Vendedores.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT a.CodVend, a.Descrip, b.supervisor from SAVEND as a inner join SAVEND_99 as b on a.CodVend=b.CodVend order by a.CodVend asc");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $CodVend = mssql_result($query, $i, "CodVend");
        $Descrip = mssql_result($query, $i, "Descrip");
        $supervisor = mssql_result($query, $i, "supervisor");

        $output .= str_pad($CodVend, 1).";";
        $output .= str_pad($Descrip, 1).";";
        $output .= str_pad($supervisor, 1).";";
        $output .= "\n";

        $serial += 1;
    }


    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

# ====================================================================
    # === VENTAS ==== 
    # ====================================================================
    case ($cubo=="12"):
    $file='';
    $file = "Ventas.TXT";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT  saitemfac.CodUbic+'FACTURA'+saitemfac.CodItem+''+convert(varchar(3),SAITEMFAC.NroLinea) as ID,
        a.ID3,
        saitemfac.NumeroD,
        CONVERT(varchar,saitemfac.fechae,112) as  Fecha,
        (case when SAITEMFAC.TipoFac = 'A' then 'FACTURA' when SAITEMFAC.TipoFac = 'C' then 'FACTURA' when SAITEMFAC.TipoFac in ('B','D') then 'DEVOLUCION' end) as clase_factura, 
        a.CodClie,
        SAITEMFAC.CodItem,
        saprod.CantEmpaq,
        saitemfac.CodUbic,
        saitemfac.CodVend,
        saitemfac.Precio,
        0 as monto_iva,
        a.MtoTax        
        from SAITEMFAC inner join saprod on SAITEMFAC.coditem = saprod.codprod 
        inner join SAFACT as a on SAITEMFAC.NumeroD=a.NumeroD and SAITEMFAC.TipoFac=a.TipoFac
        left join SACLIE_99 as c on a.CodClie=c.CodClie
        left join SAPROD_99 as d on SAITEMFAC.CodItem=d.CodProd      
        where 
        DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai'  and '$fechaf' and d.proveedor='DIAGEO' and SAITEMFAC.tipofac IN ('A','B','C','D') ORDER BY SAITEMFAC.FechaE");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $ID = mssql_result($query, $i, "ID");
        $ID3 = mssql_result($query, $i, "ID3");
        $NumeroD = mssql_result($query, $i, "NumeroD");
        $Fecha = mssql_result($query, $i, "Fecha");
        $clase_factura = mssql_result($query, $i, "clase_factura");
        $CodClie = mssql_result($query, $i, "CodClie");
        $CodItem = mssql_result($query, $i, "CodItem");
        $CantEmpaq = mssql_result($query, $i, "CantEmpaq");
        $CodUbic = mssql_result($query, $i, "CodUbic");
        $Codvend = mssql_result($query, $i, "Codvend");
        $Precio = mssql_result($query, $i, "Precio");
        $Monto_iva = mssql_result($query, $i, "Monto_iva");
        $MtoTax = mssql_result($query, $i, "MtoTax");

        $output .= str_pad($ID, 1).";";
        $output .= str_pad($ID3, 1).";";
        $output .= str_pad($NumeroD, 1).";";
        $output .= str_pad($Fecha, 1).";";
        $output .= str_pad($clase_factura, 1).";";
        $output .= str_pad($CodClie, 1).";";
        $output .= str_pad($CodItem, 1).";";
        $output .= str_pad($CantEmpaq, 1).";";
        $output .= str_pad($CodUbic, 1).";";
        $output .= str_pad($Codvend, 1).";";
        $output .= str_pad($Precio, 1).";";
        $output .= str_pad($Monto_iva, 1).";";
        $output .= str_pad($MtoTax, 1).";";
        $output .= "\n";

        $serial += 1;
    }


    fwrite($fh, $output);
    fclose($fh);

    $enlace = $file;
    header ("Content-Disposition: attachment; filename=".$enlace);
    header ("Content-Type: application/octet-stream");
    header ("Content-Length: ".filesize($enlace));
    readfile($enlace);
    unlink($file);

}

?>