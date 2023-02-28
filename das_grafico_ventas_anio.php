<?php
require ("conexion.php");
require ("funciones.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

switch($_GET['s']) {
    case '00000': 

    $ano = date('Y');
    $fechai = $ano.'-01-01';
    $fechaf = $ano.'-12-31';

    $ventas_anio = mssql_query("WITH Meses AS
        (
            SELECT 1 AS Mes UNION ALL SELECT Mes + 1 FROM Meses WHERE Mes < 12
            ),
        Operaciones AS
        (
            SELECT
            datepart(MONTH, DATEADD(MONTH, m.Mes, -1)) AS mes, 
            COALESCE(o.Cuenta, 0) AS total
            FROM
            Meses m
            LEFT JOIN 
            (
                SELECT
                MONTH(c.FechaE) AS [Mes], SUM(c.MtoTotal/factorp) AS [Cuenta]
                FROM
                SAFACT c where c.FechaE BETWEEN '$fechai' AND '$fechaf' and c.TipoFac  in ('A','C') and c.CodSucu='00000'
                GROUP BY MONTH(c.FechaE)
                ) o ON m.Mes = o.Mes       
            )
        SELECT * FROM Operaciones");

    break;
    case '00001': 

    $ano = date('Y');
    $fechai = $ano.'-01-01';
    $fechaf = $ano.'-12-31';

    $ventas_anio = mssql_query("WITH Meses AS
        (
            SELECT 1 AS Mes UNION ALL SELECT Mes + 1 FROM Meses WHERE Mes < 12
            ),
        Operaciones AS
        (
            SELECT
            datepart(MONTH, DATEADD(MONTH, m.Mes, -1)) AS mes, 
            COALESCE(o.Cuenta, 0) AS total
            FROM
            Meses m
            LEFT JOIN 
            (
                SELECT
                MONTH(c.FechaE) AS [Mes], SUM(c.MtoTotal/factorp) AS [Cuenta]
                FROM
                SAFACT c where c.FechaE BETWEEN '$fechai' AND '$fechaf' and c.TipoFac  in ('A','C') and c.CodSucu='00001'
                GROUP BY MONTH(c.FechaE)
                ) o ON m.Mes = o.Mes       
            )
        SELECT * FROM Operaciones");

    break;
    case '00002': 

    $ano = date('Y');
    $fechai = $ano.'-01-01';
    $fechaf = $ano.'-12-31';

    $ventas_anio = mssql_query("WITH Meses AS
        (
            SELECT 1 AS Mes UNION ALL SELECT Mes + 1 FROM Meses WHERE Mes < 12
            ),
        Operaciones AS
        (
            SELECT
            datepart(MONTH, DATEADD(MONTH, m.Mes, -1)) AS mes, 
            COALESCE(o.Cuenta, 0) AS total
            FROM
            Meses m
            LEFT JOIN 
            (
                SELECT
                MONTH(c.FechaE) AS [Mes], SUM(c.MtoTotal/factorp) AS [Cuenta]
                FROM
                SAFACT c where c.FechaE BETWEEN '$fechai' AND '$fechaf' and c.TipoFac  in ('A','C') and c.CodSucu='00002'
                GROUP BY MONTH(c.FechaE)
                ) o ON m.Mes = o.Mes       
            )
        SELECT * FROM Operaciones");


    break;

    default:
    $ano = date('Y');
    $fechai = $ano.'-01-01';
    $fechaf = $ano.'-12-31';

    $ventas_anio = mssql_query("WITH Meses AS
        (
            SELECT 1 AS Mes UNION ALL SELECT Mes + 1 FROM Meses WHERE Mes < 12
            ),
        Operaciones AS
        (
            SELECT
            datepart(MONTH, DATEADD(MONTH, m.Mes, -1)) AS mes, 
            COALESCE(o.Cuenta, 0) AS total
            FROM
            Meses m
            LEFT JOIN 
            (
                SELECT
                MONTH(c.FechaE) AS [Mes], SUM(c.MtoTotal/factorp) AS [Cuenta]
                FROM
                SAFACT c where c.FechaE BETWEEN '$fechai' AND '$fechaf' and c.TipoFac  in ('A','C')
                GROUP BY MONTH(c.FechaE)
                ) o ON m.Mes = o.Mes       
            )
        SELECT * FROM Operaciones");

    break;
}



$nombres_meses = array();
$data_meses = array();
for($i=0; $i<mssql_num_rows($ventas_anio); $i++) {
    $nombres_meses[] = valida_Mes(mssql_result($ventas_anio,$i,"mes"));
    $data_meses[] = mssql_result($ventas_anio,$i,"total");
}


echo json_encode(array(
    'nombres_meses' => $nombres_meses,
    'data_meses' => $data_meses,
));
