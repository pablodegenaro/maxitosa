<?php
header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
header("Content-Disposition: attachment; filename=productos_precios_".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");

require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);

$search = $_GET['s'];

if ($search !== ''){
    $productos = mssql_query("SELECT prod.CodProd, Descrip, Marca, proveedor, Refere, Maneja_Factor, Precio_Manual, Costo_Total, Flete_ME, pvp, sugerido, iva, Profit1, Profit2, Profit3 , Profit4, Profit5, Profit6, Profit7
        FROM saprod prod  left join SAPROD_99 prod99 on prod.CodProd = prod99.CodProd
        WHERE prod.CodProd LIKE '%$search%' OR Descrip LIKE '%$search%' OR Marca LIKE '%$search%'");
} else {
    $productos = mssql_query("SELECT prod.CodProd, Descrip, Marca, proveedor,Refere, Maneja_Factor, Precio_Manual, Costo_Total, Flete_ME, pvp, sugerido, iva, Profit1, Profit2, Profit3 , Profit4, Profit5, Profit6, Profit7
        FROM saprod prod  left join SAPROD_99 prod99 on prod.CodProd = prod99.CodProd");
}

?>

<style type="text/css">
    .Estilo2 {
        font-size: 16px;
        color: #FFFFFF;
        font-weight: bold;
    }
    .Estilo3 {
        font-size: 14px;
        font-weight: bold;
        font-family: "ARIAL", Courier, monospace
    }
    .Estilo4 {
        font-size: 14px;
        font-family: "ARIAL", Courier, monospace
    }
    .Estilo6 {color: #006600}
    .Estilo8 {color: #FF0000}
    .Estilo9 {color: #FFFF33}
</style>

<table width="1160" border="0"  class="Estilo4"  id="table" align="center">
    <tr align="center">
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Codigo</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Descripcion</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Proveedor</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Marca</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Costo $</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Art. 18 PVP Bs</span></td>
        <!-- <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">IVA Percibido Bs</span></td> -->
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">PVP Sugerido</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 1 $ Sur</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 2 $ Casco</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 3 $ Mayorista</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 4 $ Convenio CDL</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 5 $ Convenio EURO</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 6 $ Call Center</span></td>
        <td colspan="1" bgcolor="#5e77bb"><span class="Estilo2">Precio 7 $ Disponible</span></td>
    </tr>
    <?php 
    for ($i = 0; $i < mssql_num_rows($productos); $i++) {
        $Flete_ME = mssql_result($productos, $i, "Flete_ME");
        $Costo_Total = mssql_result($productos, $i, "Costo_Total");
        $pvp = mssql_result($productos, $i, "pvp");
        // $iva = mssql_result($productos, $i, "iva");
        $sugerido = mssql_result($productos, $i, "sugerido");
        $Profit1 = mssql_result($productos, $i, "Profit1");
        $Profit2 = mssql_result($productos, $i, "Profit2");
        $Profit3 = mssql_result($productos, $i, "Profit3");
        $Profit4 = mssql_result($productos, $i, "Profit4");
        $Profit5 = mssql_result($productos, $i, "Profit5");
        $Profit6 = mssql_result($productos, $i, "Profit6");
        $Profit7 = mssql_result($productos, $i, "Profit7");

        $bg = (($i % 2) != 0) ? 'bgcolor="#CCCCCC"' : '';
        ?>
        <tr>
            <td <?php echo $bg ?>> <div align="center"><?php echo mssql_result($productos, $i, "CodProd"); ?></div></td>
            <td <?php echo $bg ?>> <div align="left"><?php echo utf8_encode(mssql_result($productos, $i, "Descrip")); ?></div></td>
            <td <?php echo $bg ?>> <div align="center"><?php echo utf8_encode(mssql_result($productos, $i, "Proveedor")); ?></div></td>
            <td <?php echo $bg ?>> <div align="center"><?php echo utf8_encode(mssql_result($productos, $i, "Marca")); ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Costo_Total>0) ? number_format($Costo_Total, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($pvp>0) ? number_format($pvp, 2, '.', '') : 0; ?></div></td>
            <!-- <td <?php echo $bg ?>> <div align="right"><?php echo ($iva>0) ? number_format($iva, 2, '.', '') : 0; ?></div></td> -->
            <td <?php echo $bg ?>> <div align="right"><?php echo ($sugerido>0) ? number_format($sugerido, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit1>0) ? number_format($Profit1, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit2>0) ? number_format($Profit2, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit3>0) ? number_format($Profit3, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit4>0) ? number_format($Profit4, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit5>0) ? number_format($Profit5, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit6>0) ? number_format($Profit6, 2, '.', '') : 0; ?></div></td>
            <td <?php echo $bg ?>> <div align="right"><?php echo ($Profit7>0) ? number_format($Profit7, 2, '.', '') : 0; ?></div></td>
        </tr>
        <?php 
    } ?>
</table>