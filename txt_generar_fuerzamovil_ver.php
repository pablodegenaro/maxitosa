<?php
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require_once ("permisos/Mssql.php");

$fmovil =$_POST['fmovil'];
$newperiodo = date("Ym", strtotime($fechai));
$fecha = date('Y-m-d');

switch (true) {
    # =============================================================
    # === Empresa ==== 
    # =============================================================
    case ($fmovil=="1"):
    $file='';
    $file = "Empresa.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT top 1 CodSucu as cod_empresa, Descrip as nom_empresa, RIF as rif_empresa, Direc1 as dir_empresa, Telef as tel_empresa, 1 as est_empresa, TokenEmpresa as img_empresa from SACONF");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $nom_empresa = mssql_result($query, $i, "nom_empresa");
        $rif_empresa = mssql_result($query, $i, "rif_empresa");
        $dir_empresa = mssql_result($query, $i, "dir_empresa");
        $tel_empresa = mssql_result($query, $i, "tel_empresa");
        $est_empresa = mssql_result($query, $i, "est_empresa");
        $img_empresa = mssql_result($query, $i, "img_empresa");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($nom_empresa, 1)."|";
        $output .= str_pad($rif_empresa, 1)."|";
        $output .= str_pad($dir_empresa, 1)."|";
        $output .= str_pad($tel_empresa, 1)."|";
        $output .= str_pad($est_empresa, 1)."|";
        $output .= str_pad($img_empresa, 1);

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
    # === Sucursales ==== 
    # ====================================================================
    case ($fmovil=="2"):
    $file='';
    $file = "Sucursal.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT codsucu as cod_sucursal,  '00000' as cod_empresa, Descrip as nom_sucursal, direc1 as dir_sucursal, Telef as tel_sucursal, email as correo_sucursal,1 as est_empresa, TokenEmpresa as img_sucursal   from SACONF");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $nom_sucursal = mssql_result($query, $i, "nom_sucursal");
        $dir_sucursal = mssql_result($query, $i, "dir_sucursal");
        $tel_sucursal = mssql_result($query, $i, "tel_sucursal");
        $correo_sucursal = mssql_result($query, $i, "correo_sucursal");
        $est_empresa = mssql_result($query, $i, "est_empresa");
        $img_sucursal = mssql_result($query, $i, "img_sucursal");

        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($nom_sucursal, 1)."|";
        $output .= str_pad($dir_sucursal, 1)."|";
        $output .= str_pad($tel_sucursal, 1)."|";
        $output .= str_pad($correo_sucursal, 1)."|";
        $output .= str_pad($est_empresa, 1)."|";
        $output .= str_pad($img_sucursal, 1);

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
    # === Clientes ==== 
    # ====================================================================
    case ($fmovil=="3"):
    $file='';
    $file = "clientes.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT CodClie as codigo, Descrip as descripcion, id3 as rif, Direc1 as direccion, Telef as tel, Email, CodZona as cod_sector from SACLIE where activo = '1'");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $codigo = mssql_result($query, $i, "codigo");
        $descripcion = mssql_result($query, $i, "descripcion");
        $rif = mssql_result($query, $i, "rif");
        $direccion = mssql_result($query, $i, "direccion");
        $tel = mssql_result($query, $i, "tel");
        $Email = mssql_result($query, $i, "Email");
        $cod_sector = mssql_result($query, $i, "cod_sector");

        $output .= str_pad($codigo, 1)."|";
        $output .= str_pad($descripcion, 1)."|";
        $output .= str_pad($rif, 1)."|";
        $output .= str_pad($direccion, 1)."|";
        $output .= str_pad($tel, 1)."|";
        $output .= str_pad($Email, 1)."|";
        $output .= str_pad($cod_sector, 1);

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
    # === Clientes Empresas==== 
    # ====================================================================
    case ($fmovil=="4"):
    $file='';
    $file = "cliente_empresa.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN CodVend like '1%' THEN '00000' WHEN CodVend like '2%' THEN '00001' WHEN CodVend like '3%' THEN '00002' ELSE '' end as cod_sucursal, codclie as cod_cliente, CASE WHEN Activo = '1' THEN '0' WHEN Activo = '0' THEN '1' ELSE '0' end as estatus  from SACLIE where activo = '1'");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_cliente = mssql_result($query, $i, "cod_cliente");
        $estatus = mssql_result($query, $i, "estatus");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_cliente, 1)."|";
        $output .= str_pad($estatus, 1);

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
    # === Clientes Con vendedor==== 
    # ====================================================================
    case ($fmovil=="5"):
    $file='';
    $file = "cliente_vendedor.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT codclie as cod_clie, codvend as cod_vendedor from saclie where CodVend is not null and CodVend !=''
        union
        SELECT codclie as cod_clie, ruta_alternativa as cod_vendedor from SACLIE_99 where ruta_alternativa is not null and ruta_alternativa !='' 
        union
        SELECT codclie as cod_clie, ruta_alternativa_2 as cod_vendedor from SACLIE_99 where ruta_alternativa_2 is not null and ruta_alternativa_2 !='' ");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_clie = mssql_result($query, $i, "cod_clie");
        $cod_vendedor = mssql_result($query, $i, "cod_vendedor");

        $output .= str_pad($cod_clie, 1)."|";
        $output .= str_pad($cod_vendedor, 1);

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
    # === Listas de precios==== 
    # ====================================================================
    case ($fmovil=="6"):
    $file='';
    $file = "tlista_precio.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, '1' as cod_lprecio, 'Precio 1 Sur' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, '2' as cod_lprecio, 'Precio 2 Casco' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, '3' as cod_lprecio, 'Precio 3 Mayorista' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, '1' as cod_lprecio, 'Precio 1 Sur' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, '2' as cod_lprecio, 'Precio 2 Casco' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, '3' as cod_lprecio, 'Precio 3 Mayorista' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, '1' as cod_lprecio, 'Precio 1 Sur' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, '2' as cod_lprecio, 'Precio 2 Casco' as desc_lprecio
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, '3' as cod_lprecio, 'Precio 3 Mayorista' as desc_lprecio");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_lprecio = mssql_result($query, $i, "cod_lprecio");
        $desc_lprecio = mssql_result($query, $i, "desc_lprecio");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_lprecio, 1)."|";
        $output .= str_pad($desc_lprecio, 1);

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
    # === Listas de precios Clientes ==== 
    # ====================================================================
    case ($fmovil=="7"):
    $file='';
    $file = "cliente_lprecio.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN CodVend like '1%' THEN '00000' WHEN CodVend like '2%' THEN '00001' WHEN CodVend like '3%' THEN '00002' ELSE '' end as cod_sucursal, codclie as cod_cliente, TipoPVP as cod_lprecio, '1' as predet  from SACLIE");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_cliente = mssql_result($query, $i, "cod_cliente");
        $cod_lprecio = mssql_result($query, $i, "cod_lprecio");
        $predet = mssql_result($query, $i, "predet");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_cliente, 1)."|";
        $output .= str_pad($cod_lprecio, 1)."|";
        $output .= str_pad($predet, 1);

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
    # === Listas de precios Clientes ==== 
    # ====================================================================
    case ($fmovil=="8"):
    $file='';
    $file = "articulos.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT codprod as codigo, descrip as descripcion, unidad as cod_unidad, '1' as cod_unidad_venta, Peso  from saprod");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $codigo = mssql_result($query, $i, "codigo");
        $descripcion = mssql_result($query, $i, "descripcion");
        $cod_unidad = mssql_result($query, $i, "cod_unidad");
        $cod_unidad_venta = mssql_result($query, $i, "cod_unidad_venta");
        $Peso = mssql_result($query, $i, "Peso");

        $output .= str_pad($codigo, 1)."|";
        $output .= str_pad($descripcion, 1)."|";
        $output .= str_pad($cod_unidad, 1)."|";
        $output .= str_pad($cod_unidad_venta, 1)."|";
        $output .= str_pad($Peso, 1);

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
    # === articulos empresa ==== 
    # ====================================================================
    case ($fmovil=="9"):
    $file='';
    $file = "articulo_empresa.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN b.codubic like '1%' THEN '00000' WHEN b.codubic like '2%' THEN '00001'  WHEN b.codubic like '3%' THEN '00002' ELSE '' end as cod_sucursal, a.CodProd as cod_articulo, b.Existen as stock_a, '0' as stock_p, CASE WHEN a.EsExento = '0' THEN '16' else 0 end as alicuota from saprod as a inner join saexis as b on a.codprod=b.codprod where b.CodUbic in ('1000','2000','3000')");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_articulo = mssql_result($query, $i, "cod_articulo");
        $stock_a = mssql_result($query, $i, "stock_a");
        $stock_p = mssql_result($query, $i, "stock_p");
        $alicuota = mssql_result($query, $i, "alicuota");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_articulo, 1)."|";
        $output .= str_pad($stock_a, 1)."|";
        $output .= str_pad($stock_p, 1)."|";
        $output .= str_pad($alicuota, 1);

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
    # === unidad ==== 
    # ====================================================================
    case ($fmovil=="10"):
    $file='';
    $file = "unidad.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query(" 
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, 'CAJ' as codigo, 'CAJA' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, 'PAQ' as codigo, 'PAQUETE' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, 'UND' as codigo, 'UNIDAD' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, 'PCK' as codigo, 'PACK' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, 'CAJ' as codigo, 'CAJA' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, 'PAQ' as codigo, 'PAQUETE' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, 'UND' as codigo, 'UNIDAD' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, 'PCK' as codigo, 'PACK' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, 'CAJ' as codigo, 'CAJA' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, 'PAQ' as codigo, 'PAQUETE' as descripcion
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, 'UND' as codigo, 'CAJA' as descripcion
        UNION
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, 'PCK' as codigo, 'PACK' as descripcion
        ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $codigo = mssql_result($query, $i, "codigo");
        $descripcion = mssql_result($query, $i, "descripcion");


        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($codigo, 1)."|";
        $output .= str_pad($descripcion, 1);


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
    # === sector ==== 
    # ====================================================================
    case ($fmovil=="11"):
    $file='';
    $file = "sector.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT '00000' as cod_empresa, '00000' as cod_sucursal,  CodZona as cod_sector, Descrip as desc_sector from SAZONA
        union
        SELECT '00000' as cod_empresa, '00001' as cod_sucursal,  CodZona as cod_sector, Descrip as desc_sector from SAZONA
        union
        SELECT '00000' as cod_empresa, '00002' as cod_sucursal,  CodZona as cod_sector, Descrip as desc_sector from SAZONA");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_sector = mssql_result($query, $i, "cod_sector");
        $desc_sector = mssql_result($query, $i, "desc_sector");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_sector, 1)."|";
        $output .= str_pad($desc_sector, 1);


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
    # === Plan de visita ==== 
    # ====================================================================
    case ($fmovil=="12"):
    $file='';
    $file = "plan_visita.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN a.CodVend like '1%' THEN '00000' WHEN a.CodVend like '2%' THEN '00001' WHEN a.CodVend like '3%' THEN '00002' ELSE '' end as cod_sucursal, a.codclie as cod_cliente, a.CodVend as cod_vendedor, b.dia_visita as dia_semana from SACLIE as a inner join saclie_99 as b on a.CodClie=b.CodClie");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_cliente = mssql_result($query, $i, "cod_cliente");
        $cod_vendedor = mssql_result($query, $i, "cod_vendedor");
        $dia_semana = mssql_result($query, $i, "dia_semana");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_cliente, 1)."|";
        $output .= str_pad($cod_vendedor, 1)."|";
        $output .= str_pad($dia_semana, 1);


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
    # === Tasa ==== 
    # ====================================================================
    case ($fmovil=="13"):
    $file='';
    $file = "tasa_hd.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '1' as cod_tasa, factor as  valor_tasa, REPLACE(CONVERT( VARCHAR ,getdate(),111),'/','-') as fecha_act, convert(varchar,getdate(),108) as hora_act, REPLACE(CONVERT( VARCHAR ,getdate()+1,111),'/','-') as fecha_exp from saconf where codsucu='00000'");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_tasa = mssql_result($query, $i, "cod_tasa");
        $valor_tasa = mssql_result($query, $i, "valor_tasa");
        $fecha_act = mssql_result($query, $i, "fecha_act");
        $hora_act = mssql_result($query, $i, "hora_act");
        $fecha_exp = mssql_result($query, $i, "fecha_exp");

        $output .= str_pad($cod_tasa, 1)."|";
        $output .= str_pad($valor_tasa, 1)."|";
        $output .= str_pad($fecha_act, 1)."|";
        $output .= str_pad($hora_act, 1)."|";
        $output .= str_pad($fecha_exp, 1);


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
    # === Limite de Credito ==== 
    # ====================================================================
    case ($fmovil=="14"):
    $file='';
    $file = "limite_credito.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN CodVend like '1%' THEN '00000' WHEN CodVend like '2%' THEN '00001' WHEN CodVend like '3%' THEN '00002' ELSE '' end as cod_sucursal, codclie as cod_cliente, LimiteCred as limite_credito, saldo as saldo_cliente  from SACLIE");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_cliente = mssql_result($query, $i, "cod_cliente");
        $limite_credito = mssql_result($query, $i, "limite_credito");
        $saldo_cliente = mssql_result($query, $i, "saldo_cliente");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_cliente, 1)."|";
        $output .= str_pad($limite_credito, 1)."|";
        $output .= str_pad($saldo_cliente, 1);


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
    # === monedas ==== 
    # ====================================================================
    case ($fmovil=="15"):
    $file='';
    $file = "moneda.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, 'VES' as cod_moneda, 'Bolivares' as nom_moneda, 'Bs' as s_moneda, '1' as est_moneda
        union
        SELECT  DISTINCT '00000' as cod_empresa, '00000' as cod_sucursal, 'USD' as cod_moneda, 'Dolares' as nom_moneda, '$' as s_moneda, '1' as est_moneda
        union
        SELECT DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, 'VES' as cod_moneda, 'Bolivares' as nom_moneda, 'Bs' as s_moneda, '1' as est_moneda
        union
        SELECT  DISTINCT '00000' as cod_empresa, '00001' as cod_sucursal, 'USD' as cod_moneda, 'Dolares' as nom_moneda, '$' as s_moneda, '1' as est_moneda
        union
        SELECT DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, 'VES' as cod_moneda, 'Bolivares' as nom_moneda, 'Bs' as s_moneda, '1' as est_moneda
        union
        SELECT  DISTINCT '00000' as cod_empresa, '00002' as cod_sucursal, 'USD' as cod_moneda, 'Dolares' as nom_moneda, '$' as s_moneda, '1' as est_moneda
        ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_moneda = mssql_result($query, $i, "cod_moneda");
        $nom_moneda = mssql_result($query, $i, "nom_moneda");
        $s_moneda = mssql_result($query, $i, "s_moneda");
        $est_moneda = mssql_result($query, $i, "est_moneda");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_moneda, 1)."|";
        $output .= str_pad($nom_moneda, 1)."|";
        $output .= str_pad($s_moneda, 1)."|";
        $output .= str_pad($est_moneda, 1);


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
    # === Tipos de Documentos ==== 
    # ====================================================================
    case ($fmovil=="16"):
    $file='';
    $file = "tipo_documento.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
     SELECT  '00000' as cod_empresa, '00000' as cod_sucursal, '10' as cod_tipodoc, 'Factura o Nota de Entrega' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00000' as cod_sucursal, '31' as cod_tipodoc, 'Dev Factura o Dev Nota de Entrega' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00000' as cod_sucursal, '41' as cod_tipodoc, 'Pagos' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00000' as cod_sucursal, '81' as cod_tipodoc, 'Retenciones' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00000' as cod_sucursal, '50' as cod_tipodoc, 'Anticipo' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00000' as cod_sucursal, '20' as cod_tipodoc, 'Nota de Credito Administrativa' as desc_tipodoc
     union
     SELECT  '00000' as cod_empresa, '00001' as cod_sucursal, '10' as cod_tipodoc, 'Factura o Nota de Entrega' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00001' as cod_sucursal, '31' as cod_tipodoc, 'Dev Factura o Dev Nota de Entrega' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00001' as cod_sucursal, '41' as cod_tipodoc, 'Pagos' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00001' as cod_sucursal, '81' as cod_tipodoc, 'Retenciones' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00001' as cod_sucursal, '50' as cod_tipodoc, 'Anticipo' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00001' as cod_sucursal, '20' as cod_tipodoc, 'Nota de Credito Administrativa' as desc_tipodoc
     union
     SELECT  '00000' as cod_empresa, '00002' as cod_sucursal, '10' as cod_tipodoc, 'Factura o Nota de Entrega' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00002' as cod_sucursal, '31' as cod_tipodoc, 'Dev Factura o Dev Nota de Entrega' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00002' as cod_sucursal, '41' as cod_tipodoc, 'Pagos' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00002' as cod_sucursal, '81' as cod_tipodoc, 'Retenciones' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00002' as cod_sucursal, '50' as cod_tipodoc, 'Anticipo' as desc_tipodoc
     union
     SELECT   '00000' as cod_empresa, '00002' as cod_sucursal, '20' as cod_tipodoc, 'Nota de Credito Administrativa' as desc_tipodoc
     ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_tipodoc = mssql_result($query, $i, "cod_tipodoc");
        $desc_tipodoc = mssql_result($query, $i, "desc_tipodoc");

        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_tipodoc, 1)."|";
        $output .= str_pad($desc_tipodoc, 1);
        


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
    # === Articulos por unidad cantidad de empaque ==== 
    # ====================================================================
    case ($fmovil=="17"):
    $file='';
    $file = "articulos_unidad.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
     SELECT '00000' as cod_empresa, '00000' as cod_sucursal, codprod as codigo_articulo, '1' as cod_unidad_venta, CAST(CantEmpaq as int) as num_unidad  from saprod
     union
     SELECT '00000' as cod_empresa, '00000' as cod_sucursal, codprod as codigo_articulo, '2' as cod_unidad_venta, CAST(CantEmpaq as int) as num_unidad  from saprod
     union
     SELECT '00000' as cod_empresa, '00001' as cod_sucursal, codprod as codigo_articulo, '1' as cod_unidad_venta, CAST(CantEmpaq as int) as num_unidad  from saprod
     union
     SELECT '00000' as cod_empresa, '00001' as cod_sucursal, codprod as codigo_articulo, '2' as cod_unidad_venta, CAST(CantEmpaq as int) as num_unidad  from saprod
     union
     SELECT '00000' as cod_empresa, '00002' as cod_sucursal, codprod as codigo_articulo, '1' as cod_unidad_venta, CAST(CantEmpaq as int) as num_unidad  from saprod
     union
     SELECT '00000' as cod_empresa, '00002' as cod_sucursal, codprod as codigo_articulo, '2' as cod_unidad_venta, CAST(CantEmpaq as int) as num_unidad  from saprod
     ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $codigo_articulo = mssql_result($query, $i, "codigo_articulo");
        $cod_unidad_venta = mssql_result($query, $i, "cod_unidad_venta");
        $num_unidad = mssql_result($query, $i, "num_unidad");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($codigo_articulo, 1)."|";
        $output .= str_pad($cod_unidad_venta, 1)."|";
        $output .= str_pad($num_unidad, 1); 

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
    # === Unidad de Venta ==== 
    # ====================================================================
    case ($fmovil=="18"):
    $file='';
    $file = "unidad_venta.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '1' as cod_unidad_venta, 'Caja' as des_unidad_venta
        union
        SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '2' as cod_unidad_venta, 'Detallado' as des_unidad_venta
        union
        SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '1' as cod_unidad_venta, 'Caja' as des_unidad_venta
        union
        SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '2' as cod_unidad_venta, 'Detallado' as des_unidad_venta
        union
        SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '1' as cod_unidad_venta, 'Caja' as des_unidad_venta
        union
        SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '2' as cod_unidad_venta, 'Detallado' as des_unidad_venta
        ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_unidad_venta = mssql_result($query, $i, "cod_unidad_venta");
        $des_unidad_venta = mssql_result($query, $i, "des_unidad_venta");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_unidad_venta, 1)."|";
        $output .= str_pad($des_unidad_venta, 1);

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
    # === Precio Articulos ==== 
    # ====================================================================
    case ($fmovil=="19"):
    $file='';
    $file = "precio_articulo.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("    
     SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '1' as cod_lprecio, codprod as cod_producto, precio1 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '2' as cod_lprecio, codprod as cod_producto, precio2 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '3' as cod_lprecio, codprod as cod_producto, precio3 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '1' as cod_lprecio, codprod as cod_producto, precio1 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '2' as cod_lprecio, codprod as cod_producto, precio2 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '3' as cod_lprecio, codprod as cod_producto, precio3 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '1' as cod_lprecio, codprod as cod_producto, precio1 as precio from SAPROD
     union
     SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '2' as cod_lprecio, codprod as cod_producto, precio2 as precio  from SAPROD
     union
     SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '3' as cod_lprecio, codprod as cod_producto, precio3 as precio from SAPROD
     ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_lprecio = mssql_result($query, $i, "cod_lprecio");
        $cod_producto = mssql_result($query, $i, "cod_producto");
        $precio = mssql_result($query, $i, "precio");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_lprecio, 1)."|";
        $output .= str_pad($cod_producto, 1)."|";
        $output .= str_pad($precio, 1);

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
    # === Facturas pendientes ==== 
    # ====================================================================
    case ($fmovil=="20"):
    $file='';
    $file = "factpendientes.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN CodVend like '1%' THEN '00000' WHEN CodVend like '2%' THEN '00001' WHEN CodVend like '3%' THEN '00002' ELSE '' end as cod_sucursal, CodClie as cod_cliente, numerod as num_factura, FechaV as fecha_vencimiento, montoneto as monto_siniva, MtoTax as iva, Monto as total, TipoCxc as cod_tipodoc, CodVend as cod_vendedor, 'VES' as cod_moneda, EsReten as pend_reten  from saacxc where TipoCxc = 10 and saldo > 0
     ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_cliente = mssql_result($query, $i, "cod_cliente");
        $num_factura = mssql_result($query, $i, "num_factura");
        $fecha_vencimiento = mssql_result($query, $i, "fecha_vencimiento");
        $monto_siniva = mssql_result($query, $i, "monto_siniva");
        $iva = mssql_result($query, $i, "iva");
        $total = mssql_result($query, $i, "total");
        $cod_tipodoc = mssql_result($query, $i, "cod_tipodoc");
        $cod_vendedor = mssql_result($query, $i, "cod_vendedor");
        $cod_moneda = mssql_result($query, $i, "cod_moneda");
        $pend_reten = mssql_result($query, $i, "pend_reten");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_cliente, 1)."|";
        $output .= str_pad($num_factura, 1)."|";
        $output .= str_pad($fecha_vencimiento, 1)."|";
        $output .= str_pad($monto_siniva, 1)."|";
        $output .= str_pad($iva, 1)."|";
        $output .= str_pad($total, 1)."|";
        $output .= str_pad($cod_tipodoc, 1)."|";
        $output .= str_pad($cod_vendedor, 1)."|";
        $output .= str_pad($cod_moneda, 1)."|";
        $output .= str_pad($pend_reten, 1);

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
    # === tipos de pago  ==== 
    # ====================================================================
    case ($fmovil=="21"):
    $file='';
    $file = "tipo_pago.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '0' as cod_tipopago, 'Contado' as descripcion, '0' as descuento, '1' as estado 
        union
        SELECT '00000' as cod_empresa, '00000' as cod_sucursal, '1' as cod_tipopago, 'Credito' as descripcion, '0' as descuento, '1' as estado 
        union
        SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '0' as cod_tipopago, 'Contado' as descripcion, '0' as descuento, '1' as estado 
        union
        SELECT '00000' as cod_empresa, '00001' as cod_sucursal, '1' as cod_tipopago, 'Credito' as descripcion, '0' as descuento, '1' as estado 
        union
        SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '0' as cod_tipopago, 'Contado' as descripcion, '0' as descuento, '1' as estado 
        union
        SELECT '00000' as cod_empresa, '00002' as cod_sucursal, '1' as cod_tipopago, 'Credito' as descripcion, '0' as descuento, '1' as estado 
        ");


    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_tipopago = mssql_result($query, $i, "cod_tipopago");
        $descripcion = mssql_result($query, $i, "descripcion");
        $descuento = mssql_result($query, $i, "descuento");
        $estado = mssql_result($query, $i, "estado");
        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_tipopago, 1)."|";
        $output .= str_pad($descripcion, 1)."|";
        $output .= str_pad($descuento, 1)."|";
        $output .= str_pad($estado, 1);


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
    # === tipos de pagos por cliente ==== 
    # ====================================================================
    case ($fmovil=="22"):
    $file='';
    $file = "tpclient.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("SELECT '00000' as cod_empresa, CASE WHEN CodVend like '1%' THEN '00000' WHEN CodVend like '2%' THEN '00001' WHEN CodVend like '3%' THEN '00002' ELSE '' end as cod_sucursal, CodClie as cod_cliente, EsCredito as cod_tipo_pago from SACLIE
     ");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_cliente = mssql_result($query, $i, "cod_cliente");
        $cod_tipo_pago = mssql_result($query, $i, "cod_tipo_pago");

        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_cliente, 1)."|";
        $output .= str_pad($cod_tipo_pago, 1);

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
    # === bancos ==== 
    # ====================================================================
    case ($fmovil=="23"):
    $file='';
    $file = "bancos.csv";
    $fh = fopen($file, 'w');

    $query = mssql_query("
        SELECT '00000' as cod_empresa, '00000' as cod_sucursal, codbanc as cod_banco, descripcion as des_banco, moneda as cod_moneda, '1' as estatus from SBBANC where descripcion like '%Banco%'
        union
        SELECT '00000' as cod_empresa, '00001' as cod_sucursal, codbanc as cod_banco, descripcion as des_banco, moneda as cod_moneda, '1' as estatus from SBBANC where descripcion like '%Banco%'
        union
        SELECT '00000' as cod_empresa, '00002' as cod_sucursal, codbanc as cod_banco, descripcion as des_banco, moneda as cod_moneda, '1' as estatus from SBBANC where descripcion like '%Banco%'
        ");

    for($i=0; $i < mssql_num_rows($query); $i++) {

        $cod_empresa = mssql_result($query, $i, "cod_empresa");
        $cod_sucursal = mssql_result($query, $i, "cod_sucursal");
        $cod_banco = mssql_result($query, $i, "cod_banco");
        $des_banco = mssql_result($query, $i, "des_banco");
        $cod_moneda = mssql_result($query, $i, "cod_moneda");
        $estatus = mssql_result($query, $i, "estatus");

        
        $output .= str_pad($cod_empresa, 1)."|";
        $output .= str_pad($cod_sucursal, 1)."|";
        $output .= str_pad($cod_banco, 1)."|";
        $output .= str_pad($des_banco, 1)."|";
        $output .= str_pad($cod_moneda, 1)."|";
        $output .= str_pad($estatus, 1);

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

}


?>