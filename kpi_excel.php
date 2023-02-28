<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reporte_kpi_".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
require("funciones.php");
include_once 'kpimarcas.php';
session_start();
set_time_limit(0);
$fechai = $_GET['fechai']; $fechai2 = str_replace('/','-',$fechai); $fechai2 = date('Y-m-d', strtotime($fechai2));
$fechaf = $_GET['fechaf']; $fechaf2 = str_replace('/','-',$fechaf); $fechaf2 = date('Y-m-d', strtotime($fechaf2));
$dias_habiles = $_GET['habiles'];
$dias_trans = $_GET['trans'];
$sku = $_GET['skus'];
function color($valor){
    $valor = floatval(str_replace(",", ".", str_replace(".", "", $valor)));
    if ($valor > 80){
        return "bgcolor='#009966'";
    }
    if ($valor > 50 and $valor <= 80){
        return "bgcolor='#FFFF66'";
    }
    if ($valor <= 50){
        return "bgcolor='#FF4040'";
    }
}
?>
<style type="text/css">
    <!--
    .Estilo2 {
       color: #FFFFFF;
       font-weight: bold;
   }
   .Estilo3 {
    font-size: 14px;
    font-weight: bold;
    font-family: "ARIAL", Courier, monospace}
    .Estilo6 {color: #006600}
    .Estilo8 {color: #FF0000}
    .Estilo9 {color: #FFFF33}
    -->
</style>

<h1>kpi <span class="ui-state-default">Aj </span>C.A.</h1>
DESDE: <?php echo $fechai; ?> HASTA: <?php echo $fechaf; ?> DIAS HABILES: <?php echo $dias_habiles; ?> DIAS TRANSC. <?php echo $dias_trans; ?>


<table width="1160" border="0"  class="Estilo3"  id="table" align="center">
    <tr class="ui-widget-header" align="center">
        <td width="34"><span class="Estilo2">Rutas</span></td>
        <td colspan="8"><span class="Estilo2">Activaci&oacute;n</span></td>
        <td colspan="7"><span class="Estilo2">Efectividad</span></td>
        <td  colspan="5" ><span class="Estilo2">Ventas</span></td>
    </tr>
    <tr class="ui-state-default">
        <td><div align="center"><a href="javascript:;" onclick="cerrar()">Rutas</a></div></td>
        <td width="52"><div align="center">Maestro</div></td>
        <td width="65"><div align="center">Clie Activados </div></td>

        <td width="65" align="center"  ><div style="width: 10px; word-wrap: break-word; text-align: left"> <a href="#">PARMALAT</a></div></td>
        <td width="65" align="center"  ><div style="width: 10px; word-wrap: break-word; text-align: left"> <a href="#">LA LUCHA</a></div></td>
        <td width="65" align="center"  ><div style="width: 10px; word-wrap: break-word; text-align: left"> <a href="#">PEPSICO</a></div></td>
        <td width="65" align="center"  ><div style="width: 10px; word-wrap: break-word; text-align: left"> <a href="#">ST MORITZ</a></div></td>

        <td width="67"><div align="center">%Act. Alcanzada </div></td>
        <td width="69"><div align="center">Pendientes </div></td>
        <td width="70"><div align="center"> Visita </div></td>
        <td width="57"><div align="center">Obj  Facturas mas notas Mensual</div></td>
        <td width="56"><div align="center">Total Facturas Realizadas</div></td>
        <!--      nuevas-->
        <td width="56"><div align="center">Total Notas Realizadas</div></td>
        <td width="56"><div align="center">Devoluciones Realizadas (nt + fac)</div></td>
        <td width="56"><div align="center">Total Devoluciones Realizadas ($) </div></td>
        <!--      fin nuevas-->
        <td width="63"><div align="center">% Efect. Alcanzada a la Fecha</div></td>
        <td width="46"><div align="center">Objetivo (Bulto) </div></td>
        <td width="46"><div align="center">Logro (Bulto) </div></td>
        <td width="46"><div align="center">%Alcanzado (Bulto) </div></td>
        <!--      nueva-->
        <td width="140"><div align="center">Real Drop Size ($)</div></td>
        <td width="140"><div align="center">Total Logro Ventas en ($)</div></td>
        <!--      fin nueva-->
    </tr>
    <?php
    $coordinadores = mssql_query("select distinct coordinador from savend_02 d inner join savend S on S.codvend= d.CodVend where (d.coordinador = '' or d.coordinador is not null) and d.coordinador != ' ' and S.Activo = 1 and s.codvend != '00' and s.codvend != '16'  order by coordinador Asc");

    $total_clientes_full = 0;
    $total_clientes_act_full = 0;
    $total_clientes_x_act_full = 0;
    $total_cant_fact_mens_full = 0;
    $total_ventas_cant_fact_full = 0;

    $total_marca1_full = 0;
    $total_marca2_full = 0;
    $total_marca3_full = 0;
    $total_marca4_full = 0;

    /** nuevas **/
    $totalgeneral_notas_realizadas = 0;
    $totalgeneral_devoluciones_realizadas = 0;
    $totalgeneral_montoendivisa_devoluciones = 0;
    $totalgeneral_real_dz_dolares = 0;
    $totalgeneral_logro_ventas_divisas = 0;
    /** fin nuevas **/

    for($j=0;$j<mssql_num_rows($coordinadores);$j++){
        $coordina = mssql_result($coordinadores,$j,"coordinador");?>
        <tr>
            <td colspan="27"><?php echo $coordina; ?></td>
            </tr><?php
            $vendedores= mssql_query("select * from savend inner join savend_02 on savend.codvend = savend_02.codvend where activo = '1' and coordinador != '' and savend_02.coordinador like '$coordina' order by savend.codvend");
            $total_clientes = 0;
            $total_clientes_act = 0;
            $total_clientes_x_act = 0;
            $total_cant_fact_mens = 0;
            $total_ventas_cant_fact = 0;
            $total_objetivo_bul = 0;
            $total_logrado_bul = 0;

            $total_marca1 = 0;
            $total_marca2 = 0;
            $total_marca3 = 0;
            $total_marca4 = 0;

            /** nuevas **/
            $subttl_notas_realizadas = 0;
            $subttl_devoluciones_realizadas = 0;
            $subttl_montoendivisa_devoluciones = 0;
            $subttl_real_dz_dolares = 0;
            $subttl_logro_ventas_divisas = 0;
            /** fin nuevas **/

            for($i=0;$i<mssql_num_rows($vendedores);$i++){
                $codvend = mssql_result($vendedores,$i,"codvend");

                /*$clientes = mssql_query("SELECT codclie  from saclie where codvend = '$codvend' and activo = '1'");*/
                $clientes = mssql_query("SELECT SACLIE.codclie FROM saclie INNER JOIN saclie_01 ON saclie.codclie = saclie_01.codclie
                    WHERE activo = 1 AND (saclie.CodVend = '$codvend' or Ruta_Alternativa = '$codvend' OR Ruta_Alternativa_2 = '$codvend')");

                $ventas_fact = mssql_query("SELECT numerod FROM safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and safact.codvend = '$codvend' and tipofac in ('A') AND NumeroD NOT IN 
                 (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND
                     cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT))");

                $ventas_nt = mssql_query("SELECT numerod FROM sanota where DATEADD(dd, 0, DATEDIFF(dd, 0, sanota.FechaE)) between '$fechai2' and '$fechaf2' and sanota.codvend = '$codvend' and tipofac in ('C') AND SANOTA.numerof = '0' AND NumeroD NOT IN 
                 (SELECT X.NumeroD FROM sanota AS X WHERE X.TipoFac in ('C') AND x.Numerof is not NULL AND
                     cast(X.subtotal as BIGINT) = cast((select Z.subtotal from SAnota AS Z where Z.NumeroD = x.Numerof and Z.TipoFac in ('D'))as BIGINT))");

                $devoluciones_fact = mssql_query("SELECT numerod FROM safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and safact.codvend = '$codvend' and tipofac in ('B')");
                $devoluciones_nt = mssql_query("SELECT numerod FROM SANOTA where DATEADD(dd, 0, DATEDIFF(dd, 0, SANOTA.FechaE)) between '$fechai2' and '$fechaf2' and SANOTA.codvend = '$codvend' and tipofac in ('D') ");

                $montoendivisa_devoluciones_fact = mssql_query("SELECT COALESCE(SUM(TGravable/NULLIF(Tasa,0)), 0) as MontoD FROM safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and safact.codvend = '$codvend' and tipofac in ('B')");
                $montoendivisa_devoluciones_nt = mssql_query("SELECT COALESCE(SUM(subtotal), 0) as MontoD FROM sanota where DATEADD(dd, 0, DATEDIFF(dd, 0, sanota.FechaE)) between '$fechai2' and '$fechaf2' and sanota.codvend = '$codvend' and tipofac in ('D') ");

                $clientes_activos = mssql_query("SELECT distinct(SAFACT.CodClie) AS CODCLIE FROM SAFACT WHERE SAFACT.CodVend = '$codvend' AND TipoFac in ('A') AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_01 ON SACLIE.CodClie = SACLIE_01.CodClie 
                 WHERE ACTIVO = 1 AND (SACLIE.CodVend = '$codvend' or Ruta_Alternativa = '$codvend' OR Ruta_Alternativa_2 = '$codvend')) AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai2' and '$fechaf2' AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT))
                
                UNION
                
                SELECT distinct(SANOTA.CodClie) AS CODCLIE FROM SANOTA WHERE SANOTA.CodVend = '$codvend' AND TipoFac in ('C') AND SANOTA.numerof = '0' AND SANOTA.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_01 ON SACLIE.CodClie = SACLIE_01.CodClie 
                 WHERE ACTIVO = 1 AND (SACLIE.CodVend = '$codvend' or Ruta_Alternativa = '$codvend' OR Ruta_Alternativa_2 = '$codvend')) AND DATEADD(dd, 0, DATEDIFF(dd, 0, SANOTA.FechaE)) between '$fechai2' and '$fechaf2' AND NumeroD NOT IN (SELECT X.NumeroD FROM SANOTA AS X WHERE X.TipoFac in ('C') AND x.numerof is not NULL AND cast(X.subtotal as BIGINT) = cast((select Z.subtotal from SANOTA AS Z where Z.NumeroD = x.numerof and Z.TipoFac in ('D'))as BIGINT))");

            $clientes_x_rutas = mssql_query("SELECT saclie.codclie as codclie from saclie inner join saclie_01 on saclie.codclie = saclie_01.codclie where DATEADD(dd, 0, DATEDIFF(dd, 0, saclie.FechaE)) between '$fechai2' and '$fechaf2' and (codvend = '$codvend' or ruta_alternativa = '$codvend' or SACLIE_01.Ruta_Alternativa_2 = '$codvend')"); ///CLIENTES CAPTADOS

            $ventas_divisas_fact = mssql_query("SELECT COALESCE(SUM(TGravable/NULLIF(Tasa,0)), 0) as MontoD FROM safact where DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) between '$fechai2' and '$fechaf2' and safact.codvend = '$codvend' and tipofac in ('A')");
            $ventas_divisas_nt = mssql_query("SELECT COALESCE(SUM(CASE WHEN descuento IS NOT NULL THEN subtotal-descuento ELSE subtotal END)/1.16, 0) as MontoD FROM SANOTA where DATEADD(dd, 0, DATEDIFF(dd, 0, SANOTA.FechaE)) between '$fechai2' and '$fechaf2' and SANOTA.codvend = '$codvend' and tipofac in ('C') AND SANOTA.numerof = '0'");

            $facturas_realizadas = mssql_num_rows($ventas_fact);
            $notas_realizadas = mssql_num_rows($ventas_nt);
            $devoluciones_realizadas = mssql_num_rows($devoluciones_fact) + mssql_num_rows($devoluciones_nt);
            $montoendivisa_devoluciones = floatval(mssql_result($montoendivisa_devoluciones_fact,0,"MontoD")) + floatval(mssql_result($montoendivisa_devoluciones_nt,0,"MontoD"));
            $logro_ventas_divisas = floatval(mssql_result($ventas_divisas_fact,0,"MontoD")) + floatval(mssql_result($ventas_divisas_nt,0,"MontoD"));
            $real_dz_dolares = (($facturas_realizadas + $notas_realizadas) > 0) ? $logro_ventas_divisas/($facturas_realizadas+$notas_realizadas) : 0;
            $clientes_activos = mssql_num_rows($clientes_activos);


            $marca1_bultos = bultos_activados($fechai2,$fechaf2,'PARMALAT',$codvend);
            $marca2_bultos = bultos_activados($fechai2,$fechaf2,'LA LUCHA',$codvend);
            $marca3_bultos = bultos_activados($fechai2,$fechaf2,'PEPSICO',$codvend);
            $marca4_bultos = bultos_activados($fechai2,$fechaf2,'ST MORITZ',$codvend)
            + bultos_activados($fechai2,$fechaf2,'ST.MORITZ',$codvend);

            $total_marca1 += $marca1_bultos;
            $total_marca2 += $marca2_bultos;
            $total_marca3 += $marca3_bultos;
            $total_marca4 += $marca4_bultos;

            $porcent_act = (mssql_num_rows($clientes) != 0)
            ? rdecimal((($clientes_activos)/mssql_num_rows($clientes))*100) : 0;
            $por_activar = mssql_num_rows($clientes) - ($clientes_activos);
            $frecuencias = mssql_query("SELECT * FROM savend_02 WHERE CodVend = '$codvend'");

            $frecu = $dias_habiles / 5;

            if (mssql_result($frecuencias,0,"frecuencia")){
                $frecu_ot = mssql_result($frecuencias,0,"frecuencia");
            }else{
                $frecu_ot = 2;
            }

            switch ($frecu_ot) {
                case 1:
                $frecu = $frecu * 0.25;
                break;
                case 2:
                $frecu = $frecu * 0.5;
                break;
                case 4:
                $frecu = $frecu * 1;
                break;
            }

            $objetivo_cant_mens = $frecu * mssql_num_rows($clientes);

            $objetivo_bul = (mssql_result($frecuencias,0,"ObjVentasBu"))
            ? mssql_result($frecuencias,0,"ObjVentasBu") : 0;

            $total_clientes += mssql_num_rows($clientes);
            $total_clientes_act += ($clientes_activos);
            $total_clientes_x_act += $por_activar;
            $total_cant_fact_mens += $objetivo_cant_mens;
            $total_ventas_cant_fact += $facturas_realizadas;
            $total_objetivo_bul += $objetivo_bul;
            ?>
            <tr <?php if (($i % 2) != 0){ ?>bgcolor="#CCCCCC"<?php }?>>
                <td><div align="center"><?php echo mssql_result($vendedores,$i,"codvend"); ?></div></td>
                <td><div align="center"><?php echo mssql_num_rows($clientes); ?></div></td>
                <td><div align="center"><?php echo ($clientes_activos); ?></div></td>

                <td width="65"><div align="center"><?php if ($marca1_bultos != 0){   echo $marca1_bultos; } ?></div></td>
                <td width="65"><div align="center"><?php if ($marca2_bultos != 0){    echo $marca2_bultos; } ?></div></td>
                <td width="65"><div align="center"><?php if ($marca3_bultos != 0){     echo $marca3_bultos; } ?></div></td>
                <td width="65"><div align="center"><?php if ($marca4_bultos != 0){   echo $marca4_bultos; } ?></div></td>

                <td <?php echo color(rdecimal($porcent_act)); ?> ><div align="center"><?php echo rdecimal($porcent_act); ?>%</div></td>
                <td><div align="center"><?php echo $por_activar; ?></div></td>
                <td><div align="center"><?php
                switch ($frecu_ot) {
                    case 1:
                    echo "Mensual";
                    break;
                    case 2:
                    echo "Quincenal";
                    break;
                    case 4:
                    echo "Semanal";
                    break;
                    default:
                    echo "Semanal";
                }?></div>
            </td>
            <td><div align="center"><?php echo rdecimal2($objetivo_cant_mens); ?></div></td>
            <td><div align="center"><?php echo $facturas_realizadas; ?></div></td>
            <!--      nuevas-->
            <td><div align="center"><?php echo $notas_realizadas;                      $subttl_notas_realizadas += $notas_realizadas; ?></div></td>
            <td><div align="center"><?php echo $devoluciones_realizadas;               $subttl_devoluciones_realizadas +=  $devoluciones_realizadas;?></div></td>
            <td><div align="center"><?php echo rdecimal2($montoendivisa_devoluciones); $subttl_montoendivisa_devoluciones += $montoendivisa_devoluciones; ?></div></td>
            <!--      fin nuevas-->
            <?php
            $efec_x_dia = 0;
            if ((($dias_trans/$dias_habiles)*$objetivo_cant_mens) != 0){
                $efec_x_dia = (($facturas_realizadas+$notas_realizadas)/(($dias_trans/$dias_habiles)*$objetivo_cant_mens))*100;
                $total_por_efec_dia = $total_por_efec_dia + $efec_x_dia;
            }else{
                $efec_x_dia = 0;
            }
            ?>
            <td <?php echo color(rdecimal($efec_x_dia)); ?>><div align="center"><?php echo rdecimal($efec_x_dia)."%"; ?></div></td>
            <td><div align="center"><?php echo rdecimal($objetivo_bul); ?></div></td>
            <td>
                <div align="center"><?php
                $bultos = calcula_Requerido_Bult_Und_kg($fechai,$fechaf,$codvend,'BUL');
                $total_logrado_bul = $total_logrado_bul + $bultos;
                echo rdecimal($bultos); ?>
            </div>
        </td>
        <td
        <?php
        if ($objetivo_bul != 0){
            $porcet_bul = ($bultos/$objetivo_bul)*100;
        }else{
            $porcet_bul = 0;
        }
        $porcet_bul = (is_null($porcet_bul)) ? 0 : $porcet_bul;
        echo color(rdecimal( $porcet_bul));
        ?>
        >
        <div align="center">
            <?php
            if ($objetivo_bul != ""){
                $total_porcent_bul = $total_porcent_bul + $porcet_bul;

                echo rdecimal($porcet_bul);
            }else{
                echo "0";
            }
        ?>%
    </div>
</td>
<!--      nuevas-->
<td><div align="center"><?php echo rdecimal2($real_dz_dolares);       $subttl_real_dz_dolares += $real_dz_dolares;?></div></td>
<td><div align="center"><?php echo rdecimal2($logro_ventas_divisas);  $subttl_logro_ventas_divisas += $logro_ventas_divisas;?></div></td>
<!--      fin nuevas-->
</tr>
<?php } ?>
<tr class="ui-state-default">
    <td>SubTotal</td>
    <td><div align="center"><?php echo $total_clientes;      $total_clientes_full += $total_clientes; ?></div></td>
    <td><div align="center"><?php echo $total_clientes_act;  $total_clientes_act_full += $total_clientes_act;  ?></div></td>
    <td width="65"><div align="center"><?php echo $total_marca1;   $total_marca1_full += $total_marca1; ?> </div></td>
    <td width="65"><div align="center"><?php echo $total_marca2;   $total_marca2_full += $total_marca2; ?> </div></td>
    <td width="65"><div align="center"><?php echo $total_marca3;   $total_marca3_full += $total_marca3; ?> </div></td>
    <td width="65"><div align="center"><?php echo $total_marca4;   $total_marca4_full += $total_marca4; ?> </div></td>
    <td <?php
    if ($total_clientes!=0) {
        $total_prom_porcent = ($total_clientes_act/$total_clientes)*100;
    }else{
        $total_prom_porcent = 0;
    }
    echo color(rdecimal($total_prom_porcent)); ?>
    >
    <div align="center"><?php echo rdecimal($total_prom_porcent); ?>%</div>
</td>
<td><div align="center"><div align="center"><?php echo $total_clientes_x_act;    $total_clientes_x_act_full += $total_clientes_x_act; ?></div></div></td>
<td><div align="center"></div></td>
<td><div align="center"><?php echo rdecimal2($total_cant_fact_mens);              $total_cant_fact_mens_full += $total_cant_fact_mens; ?></div></td>
<td><div align="center"><?php echo $total_ventas_cant_fact;                       $total_ventas_cant_fact_full += $total_ventas_cant_fact; ?></div></td>
<!--      nuevas-->
<td><div align="center"><?php echo rdecimal2($subttl_notas_realizadas);           $totalgeneral_notas_realizadas += $subttl_notas_realizadas; ?></div></td>
<td><div align="center"><?php echo rdecimal2($subttl_devoluciones_realizadas);    $totalgeneral_devoluciones_realizadas += $subttl_devoluciones_realizadas; ?></div></td>
<td><div align="center"><?php echo rdecimal2($subttl_montoendivisa_devoluciones); $totalgeneral_montoendivisa_devoluciones += $subttl_montoendivisa_devoluciones; ?></div></td>
<!--      fin nuevas-->
<?php
$t_efec_x_dia = 0;
if ((($dias_trans/$dias_habiles)*$total_cant_fact_mens) != 0){
    $t_efec_x_dia = (($total_ventas_cant_fact+$subttl_notas_realizadas)/(($dias_trans/$dias_habiles)*$total_cant_fact_mens))*100;
}else{
    $t_efec_x_dia = 0;
}
?>
<td <?php echo color(rdecimal( $t_efec_x_dia)); ?>><div align="center"><?php echo rdecimal($t_efec_x_dia)."%"; ?></div></td>
<td><div align="center"><?php echo rdecimal2($total_objetivo_bul); $total_objetivo_bul_full += $total_objetivo_bul; ?></div></td>
<td><div align="center"><?php echo rdecimal2($total_logrado_bul);  $total_logrado_bul_full += $total_logrado_bul; ?></div></td>
<td <?php
if ($total_objetivo_bul != 0){
    $tota_bul_prom_porcent = ($total_logrado_bul/$total_objetivo_bul)*100;

    $tota_bul_prom_porcent = (is_null($tota_bul_prom_porcent)) ? 0 : $tota_bul_prom_porcent;
}else{
    $tota_bul_prom_porcent = 0;
}
echo color(rdecimal($tota_bul_prom_porcent)); ?>
>
<div align="center"><?php echo rdecimal($tota_bul_prom_porcent); ?>%</div>
</td>
<!--      nuevas-->
<td><div align="center"><?php echo rdecimal2($subttl_real_dz_dolares);      $totalgeneral_real_dz_dolares += $subttl_real_dz_dolares; ?></div></td>
<td><div align="center"><?php echo rdecimal2($subttl_logro_ventas_divisas); $totalgeneral_logro_ventas_divisas += $subttl_logro_ventas_divisas; ?></div></td>
<!--      fin nuevas-->
</tr>
<tr><td style="height: 25px;"></td></tr>
<?php } //coordinadores ?>
<tr><td style="height: 25px;"></td></tr>
<tr class="ui-state-default">
    <td>TotalGeneral</td>
    <td><div align="center"><?php echo $total_clientes_full; ?></div></td>
    <td><div align="center"><?php echo $total_clientes_act_full; ?></div></td>
    <td width="65"><div align="center"><?php echo $total_marca1_full; ?> </div></td>
    <td width="65"><div align="center"><?php echo $total_marca2_full; ?> </div></td>
    <td width="65"><div align="center"><?php echo $total_marca3_full; ?> </div></td>
    <td width="65"><div align="center"><?php echo $total_marca4_full; ?> </div></td>
    <td <?php
    if ($total_clientes_full!=0) {
        $total_prom_porcent_full = ($total_clientes_act_full/$total_clientes_full)*100;
    }else{
        $total_prom_porcent_full = 0;
    }
    echo color(rdecimal($total_prom_porcent_full)); ?>
    >
    <div align="center"><?php echo rdecimal($total_prom_porcent_full); ?>%</div>
</td>
<td><div align="center"><div align="center"><?php echo $total_clientes_x_act_full;?></div></div></td>
<td><div align="center"></div></td>
<td><div align="center"><?php echo rdecimal2($total_cant_fact_mens_full); ?></div></td>
<td><div align="center"><?php echo $total_ventas_cant_fact_full; ?></div></td>
<!--     nuevas-->
<td><div align="center"><?php echo $totalgeneral_notas_realizadas; ?></div></td>
<td><div align="center"><?php echo $totalgeneral_devoluciones_realizadas; ?></div></td>
<td><div align="center"><?php echo rdecimal2($totalgeneral_montoendivisa_devoluciones); ?></div></td>
<!--      fin nuevas-->
<?php
$t_efec_x_dia = 0;
if ((($dias_trans/$dias_habiles)*$total_cant_fact_mens_full) != 0){
    $t_efec_x_dia = (($total_ventas_cant_fact_full+$totalgeneral_notas_realizadas)/(($dias_trans/$dias_habiles)*$total_cant_fact_mens_full))*100;
}else{
    $t_efec_x_dia = 0;
}
?>
<td <?php echo color(rdecimal( $t_efec_x_dia)); ?>><div align="center"><?php echo rdecimal($t_efec_x_dia)."%"; ?></div></td>
<td><div align="center"><?php echo rdecimal2($total_objetivo_bul_full); ?></div></td>
<td><div align="center"><?php echo rdecimal2($total_logrado_bul_full); ?></div></td>
<td <?php
if ($total_objetivo_bul_full != 0){
    $tota_bul_prom_porcent = ($total_logrado_bul_full/$total_objetivo_bul_full)*100;
}else{
    $tota_bul_prom_porcent = 0;
}
echo color(rdecimal($tota_bul_prom_porcent));
?> ><div align="center"><?php echo rdecimal($tota_bul_prom_porcent); ?>%</div></td>
<!--      nuevas-->
<td><div align="center"><?php echo rdecimal2($totalgeneral_real_dz_dolares); ?></div></td>
<td><div align="center"><?php echo rdecimal2($totalgeneral_logro_ventas_divisas); ?></div></td>
<!--      fin nuevas-->
</tr>
<tr><td style="height: 25px;"></td></tr>
<tr>
    <td colspan="20" bgcolor="#CCCCCC"><span class="Estilo6">VERDE: 81 - 100% <span class="Estilo9">AMARILLO: 51 - 80%</span> <span class="Estilo8">ROJO: 0 - 50% </span></span></td>
</tr>
<tr><td style="height: 25px;"></td></tr>
</table>
