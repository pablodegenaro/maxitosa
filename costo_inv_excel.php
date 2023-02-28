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
$marca = $_GET['marca'];
$depo = $_GET['depo'];

$codubic = "";
if ($depo != "()") {
    $depo = str_replace("-", "'", $depo);
    $codubic = "and saexis.codubic in " . $depo;
}

$q_marca = "";
if ($marca != "-") {
    $q_marca = "and marca like '$marca'";
}

$query = mssql_query("SELECT  saexis.codprod Codprod, Descrip, tara, Marca, CostAct Costo, precio1 Precio, sum(saexis.existen) Bultos,
                sum(saexis.exunidad) Paquetes  from saprod inner join saexis on
                saprod.codprod = saexis.codprod where (saexis.existen > 0 or saexis.exunidad > 0) and len(marca) > 0 $codubic $q_marca
                group by saexis.codprod, descrip, CostAct, precio1, Marca, tara
                ");

$query_1          = mssql_query("SELECT factor from SACONF where CodSucu = 00000");
$factor           = mssql_result($query_1, 0, 'factor');
$costos           = 0;
$costos_p         = 0;
$precios          = 0;
$bultos           = 0;
$paquetes         = 0;
$tot_cos_bultos   = 0;
$tot_cos_paquetes = 0;

?>
<p>Factor Utilizado para el calculo : <?php echo rdecimal($factor); ?> </p>
<table width="auto" border="0">
    <tr class="ui-widget-header">
        <td><strong>Codprod</strong></td>
        <td><strong>Descrip</strong></td>
        <td><strong>Marca</strong></td>
        <td><strong>Costo Bultos Bs</strong></td>
        <td><strong>Costo Unid Bs</strong></td>
        <td><strong>Precio</strong></td>
        <td><strong>Costo Bultos $.</strong></td>
        <td><strong>Costo Unid $</strong></td>
        <td><strong>Bultos</strong></td>
        <td><strong>Paquetes</strong></td>
        <td><strong>Total Bs Costo Bultos</strong></td>
        <td><strong>Total Bs Costo Unidades</strong></td>
        <td><strong>Total $ Costo Bultos</strong></td>
        <td><strong>Total $ Costo Unid</strong></td>
    </tr>
    <tr></tr>
    <?php
    for ($j = 0; $j < mssql_num_rows($query); $j++) {
        $codprod = mssql_result($query, $j, 'Codprod');
        $query_s = mssql_query("select CantEmpaq Display from saprod where codprod = '" . $codprod . "'");
        if (mssql_result($query_s, 0, 'Display') == 0) {
            $cdisplay = 0;
        } else {
            $cdisplay = mssql_result($query, $j, 'Costo') / mssql_result($query_s, 0, 'Display');
        }

        $tdisplayd = $cdisplay / $factor;

        ?>
    <tr>
        <td><?php echo utf8_encode($codprod); ?></td>
        <td><?php echo utf8_encode(mssql_result($query, $j, 'Descrip')); ?></td>
        <td><?php echo utf8_encode(mssql_result($query, $j, 'Marca')); ?></td>
        <td><?php echo rdecimal(mssql_result($query, $j, 'Costo'),2); ?></td>
        <td><?php echo rdecimal($cdisplay,2); ?></td>
        <td><?php echo rdecimal(mssql_result($query, $j, 'Precio'),2); ?></td>
        <td><?php echo rdecimal(mssql_result($query, $j, 'Costo') / $factor,2); ?></td>
        <td><?php echo rdecimal($tdisplayd,2); ?></td>
        <td><?php echo rdecimal(mssql_result($query, $j, 'Bultos'),2); ?></td>
        <td><?php echo rdecimal(mssql_result($query, $j, 'Paquetes'),2); ?></td>
        <td><?php echo rdecimal(mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos'),2); ?></td>
        <td><?php echo rdecimal($cdisplay * mssql_result($query, $j, 'Paquetes'),2); ?></td>
        <td><?php echo rdecimal((mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos')) / $factor,2); ?></td>
        <td><?php echo rdecimal(($cdisplay * mssql_result($query, $j, 'Paquetes')) / $factor,2); ?></td>
    </tr> <?php

    $costos   = $costos + mssql_result($query, $j, "Costo");
    $costos_p = $costos_p + $cdisplay;

    $tdisplayd  = $cdisplay / $factor;
    $costosd    = $costos + mssql_result($query, $j, 'Costo') / $factor;
    $costos_p_d = $costos_p + $tdisplayd;
    $precios          = $precios + mssql_result($query, $j, "Precio");

    $costos_pd = $costos_pd + (mssql_result($query, $j, 'Costo') / $factor);
    $precios_d = $precios_d + $tdisplayd;

    $bultos           = $bultos + mssql_result($query, $j, "Bultos");
    $paquetes         = $paquetes + mssql_result($query, $j, "Paquetes");
    $tot_cos_bultos   = $tot_cos_bultos + (mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos'));
    $tot_cos_paquetes = $tot_cos_paquetes + ($cdisplay * mssql_result($query, $j, 'Paquetes'));

    $tot_cos_bultosd   = $tot_cos_bultosd + (mssql_result($query, $j, 'Costo') * mssql_result($query, $j, 'Bultos')) / $factor;
    $tot_cos_paquetesd = $tot_cos_paquetesd + ($cdisplay * mssql_result($query, $j, 'Paquetes')) / $factor;
    } ?>
    <tr>
        <td colspan="3" align="right">Totales: </td>
        <td ><?php echo rdecimal($costos,2); ?></td>
        <td ><?php echo rdecimal($costos_p,2); ?></td>
        <td ><?php echo rdecimal($precios,2); ?></td>

        <td ><?php echo rdecimal($costos_pd,2); ?></td>
        <td ><?php echo rdecimal($precios_d,2); ?></td>

        <td ><?php echo rdecimal($bultos,2); ?></td>
        <td ><?php echo rdecimal($paquetes,2); ?></td>
        <td ><?php echo rdecimal($tot_cos_bultos,2); ?></td>
        <td ><?php echo rdecimal($tot_cos_paquetes,2); ?></td>

        <td ><?php echo rdecimal($tot_cos_bultosd,2); ?></td>
        <td ><?php echo rdecimal($tot_cos_paquetesd,2); ?></td>
    </tr>
</table>


