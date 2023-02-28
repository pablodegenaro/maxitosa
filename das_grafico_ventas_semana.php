<?php
require ("conexion.php");
require ("funciones.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

switch($_GET['s']) {
    case '00000': 

    $ventas_semana = mssql_query("
        SET LANGUAGE Spanish; 
        select tbl.Dia, isnull(ft.MtoTotal,0) MtoTotal
        from TablaDias tbl left join 
        (
        select 
        sum(MtoTotal/factorp) MtoTotal, 
        datename(DW,Fechae) as dia,
        DATEPART(dw,Fechae) as Nro from SAFACT 

        where datepart(wk,Fechae) = DATEPART(wk,GETDATE()) and CodSucu='00000'
        group by datename(DW,Fechae), datename(dy,Fechae),  DATEPART(dw,Fechae) 
        ) ft on tbl.NroDia = ft.Nro
        order by NroDia");
    break;
    case '00001': 

    $ventas_semana = mssql_query("
        SET LANGUAGE Spanish; 
        select tbl.Dia, isnull(ft.MtoTotal,0) MtoTotal
        from TablaDias tbl left join 
        (
        select 
        sum(MtoTotal/factorp) MtoTotal, 
        datename(DW,Fechae) as dia,
        DATEPART(dw,Fechae) as Nro from SAFACT 

        where datepart(wk,Fechae) = DATEPART(wk,GETDATE()) and CodSucu='00001'
        group by datename(DW,Fechae), datename(dy,Fechae),  DATEPART(dw,Fechae) 
        ) ft on tbl.NroDia = ft.Nro
        order by NroDia");
    break;
    case '00002': 

    $ventas_semana = mssql_query("
        SET LANGUAGE Spanish; 
        select tbl.Dia, isnull(ft.MtoTotal,0) MtoTotal
        from TablaDias tbl left join 
        (
        select 
        sum(MtoTotal/factorp) MtoTotal, 
        datename(DW,Fechae) as dia,
        DATEPART(dw,Fechae) as Nro from SAFACT 

        where datepart(wk,Fechae) = DATEPART(wk,GETDATE()) and CodSucu='00002'
        group by datename(DW,Fechae), datename(dy,Fechae),  DATEPART(dw,Fechae) 
        ) ft on tbl.NroDia = ft.Nro
        order by NroDia");
    break;

    default:

    $ventas_semana = mssql_query("
        SET LANGUAGE Spanish; 
        select tbl.Dia, isnull(ft.MtoTotal,0) MtoTotal
        from TablaDias tbl left join 
        (
        select 
        sum(MtoTotal/factorp) MtoTotal, 
        datename(DW,Fechae) as dia,
        DATEPART(dw,Fechae) as Nro from SAFACT 

        where datepart(wk,Fechae) = DATEPART(wk,GETDATE()) 
        group by datename(DW,Fechae), datename(dy,Fechae),  DATEPART(dw,Fechae) 
        ) ft on tbl.NroDia = ft.Nro
        order by NroDia");
    break;
}








$nombres_dia = array();
$data_dia = array();
$j=0;
for($i=0; $i<mssql_num_rows($ventas_semana); $i++) {
    /*switch (mssql_result($ventas_semana,$i,"dia")) {
        case 'Lunes': break;
        case 'Martes': break;
        case 'Miercoles': break;
        case 'Jueves': break;
        case 'Viernes': break;
        case 'Sabado': break;
        case 'Domingo': break;
        default:

    }*/
    $nombres_dia[] = mssql_result($ventas_semana,$i,"dia");
    $data_dia[] = mssql_result($ventas_semana,$i,"MtoTotal");
}

echo json_encode(array(
    'nombres_dia' => $nombres_dia,
    'data_dia' => $data_dia,
));
