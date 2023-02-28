<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=FacturasPenientesCobrar.xls");
header("Pragma: no-cache");
header("Expires: 0");

require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');
$rango = $_GET['rango'];
$codsucu = $_GET['sucu'];
$suma = 0;
$fechas = "TODO";
switch ($rango) {
    case 2:
    $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
        CONVERT( date , saacxc.fechae ) as FechaEmi, 
        (case when saacxc.tipocxc = 10 then (select CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
        DATEDIFF(DD, saacxc.fechae, (case when saacxc.tipocxc = 10 then (select CONVERT( date ,GETDATE()) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else saacxc.fechae end))as DiasTrans,
        DATEDIFF(DD, saacxc.fechae, CONVERT( date ,GETDATE()))as DiasTransHoy,
        UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPend, 
        (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
        from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
        where (DATEADD(dd, 0, DATEDIFF(dd, 0, SAACXC.FechaE)) between DATEADD(day, -7, CONVERT( date ,GETDATE())) and GETDATE()) 
        and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
        order by saacxc.FechaE asc");
    $fechas = "DE 0 A 7 DIAS";
    break;
    case 3:
    $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
        CONVERT( date , saacxc.fechae ) as FechaEmi, 
        (case when saacxc.tipocxc = 10 then (select CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
        DATEDIFF(DD, saacxc.fechae, (case when saacxc.tipocxc = 10 then (select CONVERT( date ,GETDATE()) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else saacxc.fechae end))as DiasTrans,
        DATEDIFF(DD, saacxc.fechae, CONVERT( date ,GETDATE()))as DiasTransHoy,
        UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPend, 
        (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
        from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
        where (DATEADD(dd, 0, DATEDIFF(dd, 0, SAACXC.FechaE)) between DATEADD(day, -15, CONVERT( date ,GETDATE())) and DATEADD(day, -8, CONVERT( date ,GETDATE()))) 
        and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
        order by saacxc.FechaE asc");
    $fechas = "DE 8 A 15 DIAS";
    break;
    case 4:
    $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
        CONVERT( date , saacxc.fechae ) as FechaEmi, 
        (case when saacxc.tipocxc = 10 then (select CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
        DATEDIFF(DD, saacxc.fechae, (case when saacxc.tipocxc = 10 then (select CONVERT( date ,GETDATE()) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else saacxc.fechae end))as DiasTrans,
        DATEDIFF(DD, saacxc.fechae, CONVERT( date ,GETDATE()))as DiasTransHoy,
        UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPend, 
        (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
        from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
        where (DATEADD(dd, 0, DATEDIFF(dd, 0, SAACXC.FechaE)) between DATEADD(day, -40, CONVERT( date ,GETDATE())) and DATEADD(day, -16, CONVERT( date ,GETDATE()))) 
        and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
        order by saacxc.FechaE asc");
    $fechas = "DE 16 A 40 DIAS";
    break;
    case 5:
    $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
        CONVERT( date , saacxc.fechae ) as FechaEmi, 
        (case when saacxc.tipocxc = 10 then (select CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
        DATEDIFF(DD, saacxc.fechae, (case when saacxc.tipocxc = 10 then (select CONVERT( date ,GETDATE()) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else saacxc.fechae end))as DiasTrans,
        DATEDIFF(DD, saacxc.fechae, CONVERT( date ,GETDATE()))as DiasTransHoy,
        UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPend, 
        (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
        from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
        where (SAACXC.FechaE < DATEADD(day, -40, CONVERT( date ,GETDATE()))) 
        and sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
        order by saacxc.FechaE asc");
    $fechas = "MAYOR A 40 DIAS";
    break;
    case 6:
    $query = mssql_query("SELECT (case when saacxc.tipocxc = 10 then 'FACT' else 'N/D' end) as TipoOpe, saacxc.numerod as NroDoc, saclie.CodClie as CodClie, saclie.Descrip as Cliente, 
        CONVERT( date , saacxc.fechae ) as FechaEmi, 
        (case when saacxc.tipocxc = 10 then (select CONVERT( VARCHAR ,fechad,103) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else 'N/A' end) as FechaDesp,
        DATEDIFF(DD, saacxc.fechae, (case when saacxc.tipocxc = 10 then (select CONVERT( date ,GETDATE()) from appfacturas inner join appfacturas_det on appfacturas.correl = appfacturas_det.correl where 
            appfacturas_det.numeros = saacxc.numerod) else saacxc.fechae end))as DiasTrans,
        DATEDIFF(DD, saacxc.fechae, CONVERT( date ,GETDATE()))as DiasTransHoy,
        UPPER(saacxc.codvend) as Ruta, saacxc.saldo as SaldoPend, 
        (select supervisor from SAVEND_99 where CodVend = saacxc.CodVend) as Supervisor
        from saacxc inner join saclie on saacxc.codclie = saclie.codclie inner join SASUCURSAL sucu on sucu.CodSucu=saacxc.CodSucu
        where sucu.CodSucu='$codsucu' and saacxc.saldo>0 AND (saacxc.tipocxc='10' OR saacxc.tipocxc='20') 
        order by saacxc.FechaE asc");
    $fechas = "TODAS LAS CUENTAS";
    break;
}

?>
<h2>PENDIENTE POR COBRAR: <?php echo $fechas; ?></h2>
<table width="auto" border="0">
    <tr class="ui-widget-header">
        <?php for ($i = 0; $i < mssql_num_fields($query); ++$i){ ?>
            <td><strong><?php echo mssql_field_name($query, $i); ?></strong></td>
        <?php } ?>
    </tr>
    <tr></tr>
    <?php for($j=0;$j<mssql_num_rows($query);$j++){
        $suma = $suma + mssql_result($query,$j,"SaldoPend");
        ?>
        <tr <?php if ($j%2 != 0){ ?> bgcolor="#CCCCCC" <?php } ?> >
            <?php for($i=0;$i<mssql_num_fields($query);$i++){ ?>
                <td>
                    <?php
                    if(is_numeric(mssql_result($query,$j,mssql_field_name($query, $i))) and strstr(mssql_result($query,$j,mssql_field_name($query, $i)),'.')) {
                        echo rdecimal(mssql_result($query,$j,mssql_field_name($query, $i)));
                    }else{
                        echo utf8_encode(mssql_result($query,$j,mssql_field_name($query, $i)));
                    }
                    ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>
<br>
<br>
<br>
<?php echo "TOTAL ITEMS:".mssql_num_rows($query)." TOTAL MONTO: ".rdecimal($suma); ?>


