<?php
header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
header("Content-Disposition: attachment; filename=FRENTE_DE_RUTA_".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");

require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);

$sucursal = $_GET['sucursal'];
$codvend = $_GET['codvend'];
$dias = $_GET['dia'];

$vendedor = mssql_query("SELECT CodVend, Descrip FROM savend WHERE CodVend='$codvend'");
$nomperVend = mssql_result($vendedor, 0, "Descrip");
?>

<style type="text/css">
    .Estilo1 {
        font-size: 24px;
        color: #000000;
        font-weight: bold;
    }
    .Estilo2 {
        font-size: 20px;
        color: #000;
        font-weight: bold;
    }
    .Estilo3 {
        font-size: 14px;
        font-weight: bold;
        font-family: "ARIAL", Courier, monospace;
    }
    .Estilo4 {
        font-size: 14px;
        font-family: "ARIAL", Courier, monospace;
    }
    .Estilo4-bold {
        font-size: 14px;
        font-family: "ARIAL", Courier, monospace;
        font-weight: bold;
    }
    .Estilo4-white {
        font-size: 14px;
        color: #FFFFFF;
        font-family: "ARIAL", Courier, monospace;
    }
    .Estilo6 {color: #006600}
    .Estilo8 {color: #FF0000}
    .Estilo9 {color: #FFFF33}
</style>

<?php 
foreach($dias as $i => $dia) {
    $clases = mssql_query("SELECT Clase FROM SACLIE s INNER JOIN SACLIE_99 s99 ON s.CodClie=s99.CodClie WHERE (CodVend='$codvend' OR ruta_alternativa='$codvend' OR ruta_alternativa_2='$codvend') 
        AND dia_visita='$dia' AND Activo=1 GROUP BY Clase");
    
    for ($y = 0; $y < mssql_num_rows($clases); $y++) {
        $clase = mssql_result($clases, $y, "Clase");

        $clientes = mssql_query("SELECT s.CodClie, Descrip, DiasCred, frecuencia_visita, EsCredito, Represent, Telef, canal, formato_cliente, pdv_ocasion, formato_cliente_2, alcance, nivel_ejecucion, CodVend,
            ruta_alternativa, dia_visita, LimiteCred
            FROM SACLIE s INNER JOIN SACLIE_99 s99 ON s.CodClie=s99.CodClie
            WHERE (CodVend='$codvend' OR ruta_alternativa='$codvend' OR ruta_alternativa_2='$codvend') AND dia_visita='$dia' AND Clase='$clase' AND Activo=1
            ORDER BY Descrip ASC");
            ?>
            <table width="1160" border="0" class="Estilo4"  id="table" align="center">
                <tr align="center"></tr>
                <?php
                if ($i > 0) {
                    ?>
                    <tr align="center"></tr>
                    <tr align="center"></tr>
                    <tr align="center"></tr>
                    <tr align="center"></tr>
                    <?php
                }
                ?>
                <tr align="center">
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>     
                    <td colspan="1"></td>  
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold"><?php echo $codvend; ?></span></td>
                </tr>
                <tr align="center">
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>  
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">-</span></td>
                    <td colspan="1"></td>    
                    <td colspan="1" bgcolor="#B0C4DE"></td>
                </tr>
                <tr align="center">
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>    
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold"><?= $clase; ?></span></td>    
                    <td colspan="1"></td>    
                    <td colspan="1"></td>   
                    <td colspan="1"></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold"><?php echo $nomperVend; ?></span></td>
                    <td colspan="1"></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold"><?php echo $dia; ?></span></td>
                </tr>
                <tr align="center"></tr>
                <tr align="center"></tr>
                <tr align="center">
                    <td colspan="<?= 17; ?>" bgcolor="#5e77bb"><span class="Estilo1">HOJA DE RUTA</span></td>
                </tr>
                <tr align="center"></tr>
                <tr align="center">
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">#</span></td>  
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">COD.CLIENTE</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CLIENTE</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">DIAS CREDITO</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">FRECUENCIA</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONDICION DE PAGO</span></td>  
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">LIMITE CREDITO</span></td>  
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">PERSONA DE CONTACTO</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">TELEFONO</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CANAL</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CLASIFICACION</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">Formato PDV / Ocacion</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">Formato Cliente/OC.Secundaria</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">Alcance</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">Nivel Ejecucion</span></td>   
                    <td colspan="1" bgcolor="#B0C4DE" width="15px"><span class="Estilo4-bold">Ruta Principal</span></td>    
                    <td colspan="1" bgcolor="#B0C4DE" width="15px"><span class="Estilo4-bold">Ruta Alternativa</span></td>    
                </tr>
                <?php 
                for ($x = 0; $x < mssql_num_rows($clientes); $x++) {
                    $bg = (($x % 2) != 0) ? 'bgcolor="#CCCCCC"' : 'bgcolor="#FFFFFF"';
                    ?>
                    <tr align="center">
                        <td <?= $bg ?>> <div align="center"><?= $x+1; ?></div></td>
                        <td <?= $bg ?>> 
                            <div align="center" class="Estilo4-bold"> <?= mssql_result($clientes, $x, "CodClie"); ?></div>
                        </td>
                        <td <?= $bg ?>> <div align="left">  <?= mssql_result($clientes, $x, "Descrip"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "DiasCred"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "frecuencia_visita"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= (mssql_result($clientes, $x, "EsCredito")==1) ? 'CREDITO' : 'CONTADO'; ?></div></td>
                        <td <?= $bg ?>> <div align="right"> <?= number_format(mssql_result($clientes, $x, "LimiteCred"),2,',','.'); ?></div></td>
                        <td <?= $bg ?>> <div align="left">  <?= mssql_result($clientes, $x, "Represent"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "Telef"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "canal"); ?></div></td>
                        <td <?= $bg ?>> <div align="left">  <?= mssql_result($clientes, $x, "formato_cliente"); ?></div></td>
                        <td <?= $bg ?>> <div align="left">  <?= mssql_result($clientes, $x, "pdv_ocasion"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "formato_cliente_2"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "alcance"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "nivel_ejecucion"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "CodVend"); ?></div></td>
                        <td <?= $bg ?>> <div align="center"><?= mssql_result($clientes, $x, "ruta_alternativa"); ?></div></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
    }
    ?>
