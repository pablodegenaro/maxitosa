<?php
header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
header("Content-Disposition: attachment; filename=lista_precios4_".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");

require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);

$marca = $_GET['marca'];
$instap = $_GET['instap'];
$insta = $_GET['insta'];
$prove = $_GET['prove'];
$orden = $_GET['orden'];
$p1 = $_GET['p1'];
$p2 = $_GET['p2'];
$p3 = $_GET['p3'];
$p4 = $_GET['p4'];
$p5 = $_GET['p5'];
$p6 = $_GET['p6'];
$p7 = $_GET['p7'];
$p8 = $_GET['p8'];
$divisa = $_GET['divisa'];
$exis =  $_GET['exis'];

$sumap = ($p1+$p2+$p3+$p4+$p5+$p6+$p7+$p8)*2;


$marcas = '()';
$aux2 = "";
foreach ($marca as $num) { $aux2 .= "'$num',"; }
$marcas = "(" . substr($aux2, 0, strlen($aux2)-1) . ")";

$instaciaspadre = '()';
if (count($instap) > 0) {
    $aux3 = "";
    foreach ($instap as $num) { $aux3 .= "$num,"; }
    $instaciaspadre = "(" . substr($aux3, 0, strlen($aux3)-1) . ")";
}

$instaciashijo = '()';
if (count($insta) > 0) {
    $aux4 = "";
    foreach ($insta as $num) { $aux4 .= "$num,"; }
    $instaciashijo = "(" . substr($aux4, 0, strlen($aux4)-1) . ")";
}

$proveedores = '()';
$aux5 = "";
foreach ($prove as $num) { $aux5 .= "'$num',"; }
$proveedores = "(" . substr($aux5, 0, strlen($aux5)-1) . ")";

$saconf = mssql_query("SELECT top 1 FactorM from SACONF");
$factor =  mssql_result($saconf, 0, 'FactorM'); 
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
$consul_empresa = mssql_query("SELECT top 1 Descrip from SACONF");
$consul_empresa1 =  mssql_result($consul_empresa, 0, 'DESCRIP'); 
?>
<p style="font-size: 25px; font-weight: bold; text-align: center; "> LISTA DE PRECIOS</p>

<p style="font-size: 18px; text-align: center; "><?= $consul_empresa1; ?></p>


<table width="1160" border="0"  class="Estilo4"  id="table" align="center">
    <?php
    $inst_padre = mssql_query("SELECT CODINST, DESCRIP, INSPADRE FROM VW_ADM_INSTANCIAS WHERE INSPADRE=0 AND CODINST IN $instaciaspadre");
    for ($i = 0; $i < mssql_num_rows($inst_padre); $i++) {
        $insp_id = mssql_result($inst_padre, $i, "CODINST");
        ?>
        <tr align="left">
            <td colspan="<?= 6 + $sumap; ?>" bgcolor="#5e77bb"><span class="Estilo1"><?= mssql_result($inst_padre, $i, "DESCRIP"); ?></span></td>
        </tr>
        

        <?php 
        $inst_hijo = mssql_query("SELECT CODINST, DESCRIP, INSPADRE FROM VW_ADM_INSTANCIAS WHERE INSPADRE=$insp_id AND CODINST IN $instaciashijo");
        for ($j = 0; $j < mssql_num_rows($inst_hijo); $j++) {
            $ins_id = mssql_result($inst_hijo, $j, "CODINST");
            ?>
            <tr align="left">
                <td colspan="<?= 6 + $sumap; ?>" bgcolor="#B0C4DE"><span class="Estilo2"><?= mssql_result($inst_hijo, $j, "DESCRIP"); ?></span></td>
            </tr>
            <tr align="center">
                <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CODIGO</span></td>
                <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">DESCRIPCION</span></td>
                <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">SUB CATEGORIA</span></td>
                <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CAP.</span></td>
                <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">EMP.</span></td>
                <?php 
                if ($p1 == 1) { 
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">SUR CAJA <?php echo $divisa ? "$" : "Bs";  ?> </span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">SUR BOT. <?php echo $divisa ? "$" : "Bs";  ?> </span></td>
                    <?php 
                } 
                if ($p2 == 1) {  
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CASCO CAJA <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CASCO BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <?php 
                }
                if ($p3 == 1) {
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">MAYORISTA CAJA <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">MAYORISTA BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <?php 
                } 
                if ($p4 == 1) {
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. DIAGEO CAJA <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. DIAGEO BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <?php 
                } 
                if ($p5 == 1) {
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. EURO CAJA <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. EURO BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <?php 
                } 
                if ($p6 == 1) {
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. CALL CENTER CAJA <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. CALL CENTER BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <?php 
                } 
                if ($p7 == 1) {
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. EMPLEADOS CAJA <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. EMPLEADOS BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                    <?php 
                } 
                if ($p8 == 1) {
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. MAYORISTA CAJA <?php echo $divisa ? "$" : "Bs";  ?>span></td>
                        <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CONV. MAYORISTA BOT. <?php echo $divisa ? "$" : "Bs";  ?></span></td>
                        <?php 
                    } 
                    ?>
                    <td colspan="1" bgcolor="#B0C4DE"><span class="Estilo4-bold">CODIGO BARRA</span></td>
                </tr>
                
                <?php 
                if ($exis != 1 ) {
                    if ($divisa != 0) {
                        $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE(Profit1,0) as precio1, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE(Profit3,0)  as precio3,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                            COALESCE(Profit4,0) as precio4, COALESCE(Profit4/NULLIF(CantEmpaq,0), 0) as preciou4,
                            COALESCE(Profit5,0) as precio5, COALESCE(Profit5/NULLIF(CantEmpaq,0), 0) as preciou5,
                            COALESCE(Profit6,0) as precio6, COALESCE(Profit6/NULLIF(CantEmpaq,0), 0) as preciou6,
                            COALESCE(Profit7,0) as precio7, COALESCE(Profit7/NULLIF(CantEmpaq,0), 0) as preciou7,
                            COALESCE(Profit8,0) as precio8, COALESCE(Profit8/NULLIF(CantEmpaq,0), 0) as preciou8
                            FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                            LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                            LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                            WHERE proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3, saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8,  saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
                    }else{
                        $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE((Profit1*ISNULL($factor,0)),0) as precio1, COALESCE((Profit1*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE((Profit2*ISNULL($factor,0)),0)  as precio2, COALESCE((Profit2*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE((Profit3*ISNULL($factor,0)),0)  as precio3,  COALESCE((Profit3*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                            COALESCE((Profit4*ISNULL($factor,0)),0) as precio4, COALESCE((Profit4*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou4,
                            COALESCE((Profit5*ISNULL($factor,0)),0) as precio5, COALESCE((Profit5*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou5,
                            COALESCE((Profit6*ISNULL($factor,0)),0) as precio6, COALESCE((Profit6*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou6,
                            COALESCE((Profit7*ISNULL($factor,0)),0) as precio7, COALESCE((Profit7*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou7,
                            COALESCE((Profit8*ISNULL($factor,0)),0) as precio8, COALESCE((Profit8*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou8
                            FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                            LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                            LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                            WHERE proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3, saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8,  saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
                    }
                } else {
                    if ($divisa != 0) {
                       $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE(Profit1,0) as precio1, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE(Profit3,0)  as precio3,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                         COALESCE(Profit4,0) as precio4, COALESCE(Profit4/NULLIF(CantEmpaq,0), 0) as preciou4,
                         COALESCE(Profit5,0) as precio5, COALESCE(Profit5/NULLIF(CantEmpaq,0), 0) as preciou5,
                         COALESCE(Profit6,0) as precio6, COALESCE(Profit6/NULLIF(CantEmpaq,0), 0) as preciou6,
                         COALESCE(Profit7,0) as precio7, COALESCE(Profit7/NULLIF(CantEmpaq,0), 0) as preciou7,
                         COALESCE(Profit7,0) as precio8, COALESCE(Profit7/NULLIF(CantEmpaq,0), 0) as preciou8
                         FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                         LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                         LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                         WHERE (saexis.existen > 0 OR saexis.exunidad > 0) AND proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3,  saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
                   }else{
                    $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  COALESCE((Profit1*ISNULL($factor,0)),0) as precio1, COALESCE((Profit1*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE((Profit2*ISNULL($factor,0)),0)  as precio2, COALESCE((Profit2*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou2, COALESCE((Profit3*ISNULL($factor,0)),0)  as precio3,  COALESCE((Profit3*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria,
                        COALESCE((Profit4*ISNULL($factor,0)),0) as precio4, COALESCE((Profit4*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou4,
                        COALESCE((Profit5*ISNULL($factor,0)),0) as precio5, COALESCE((Profit5*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou5,
                        COALESCE((Profit6*ISNULL($factor,0)),0) as precio6, COALESCE((Profit6*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou6,
                        COALESCE((Profit7*ISNULL($factor,0)),0) as precio7, COALESCE((Profit7*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou7,
                        COALESCE((Profit8*ISNULL($factor,0)),0) as precio8, COALESCE((Profit8*ISNULL($factor,0))/NULLIF(CantEmpaq,0), 0) as preciou8
                        FROM saexis INNER JOIN saprod ON saexis.codprod = saprod.codprod
                        LEFT JOIN saprod_99 ON saprod.codprod = saprod_99.codprod
                        LEFT JOIN SAINSTA ON saprod.CodInst = SAINSTA.CodInst
                        WHERE (saexis.existen > 0 OR saexis.exunidad > 0) AND  proveedor IN $proveedores AND saprod.CodInst='$ins_id' AND saprod.Marca IN $marcas  GROUP  by saprod.CodProd, saprod.Descrip, saprod.marca, saprod.Refere,  saprod_99.Profit1, saprod_99.Profit2, saprod_99.Profit3, saprod_99.Profit4, saprod_99.Profit5, saprod_99.Profit6, saprod_99.Profit7,saprod_99.Profit8,  saprod.CantEmpaq, saprod_99.capacidad_botella, saprod_99.sub_clasificacion_categoria ORDER BY saprod.$orden ");
                }
            }

            for ($x = 0; $x < mssql_num_rows($productos); $x++) {
                $bg = (($x % 2) != 0) ? 'bgcolor="#CCCCCC"' : 'bgcolor="#FFFFFF"';

                $precio1 = mssql_result($productos, $x, "precio1");
                $precio2 = mssql_result($productos, $x, "precio2");
                $precio3 = mssql_result($productos, $x, "precio3");
                $precio4 = mssql_result($productos, $x, "precio4");
                $precio5 = mssql_result($productos, $x, "precio5");
                $precio6 = mssql_result($productos, $x, "precio6");
                $precio7 = mssql_result($productos, $x, "precio7");
                $precio8 = mssql_result($productos, $x, "precio8");
                $preciou1 = mssql_result($productos, $x, "preciou1");
                $preciou2 = mssql_result($productos, $x, "preciou2");
                $preciou3 = mssql_result($productos, $x, "preciou3");
                $preciou4 = mssql_result($productos, $x, "preciou4");
                $preciou5 = mssql_result($productos, $x, "preciou5");
                $preciou6 = mssql_result($productos, $x, "preciou6");
                $preciou7 = mssql_result($productos, $x, "preciou7");
                $preciou8 = mssql_result($productos, $x, "preciou8");
                ?>
                <tr align="center">
                    <td <?= $bg ?>> <div align="center"><?= mssql_result($productos, $x, "CodProd"); ?></div></td>
                    <td <?= $bg ?>> <div align="left">  <?= mssql_result($productos, $x, "Descrip"); ?></div></td>
                    <td <?= $bg ?>> <div align="center">  <?= mssql_result($productos, $x, "sub_clasificacion_categoria"); ?></div></td>
                    <td <?= $bg ?>> <div align="center"><?= rdecimal2(mssql_result($productos, $x, "capacidad_botella")); ?></div></td>
                    <td <?= $bg ?>> <div align="center"><?= rdecimal2(mssql_result($productos, $x, "CantEmpaq")); ?></div></td>
                    <?php 
                    if ($p1 == 1) { 
                        ?>
                        <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio1); ?></div></td>
                        <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou1); ?></div></td>
                        <?php 
                    } 
                    if ($p2 == 1) {  
                        ?>
                        <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio2); ?></div></td>
                        <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou2); ?></div></td>
                        <?php 
                    }
                    if ($p3 == 1) {
                       ?>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio3); ?></div></td>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou3); ?></div></td>
                       <?php 
                   } 
                   if ($p4 == 1) {
                       ?>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio4); ?></div></td>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou4); ?></div></td>
                       <?php 
                   } 
                   if ($p5 == 1) {
                       ?>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio5); ?></div></td>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou5); ?></div></td>
                       <?php 
                   } 
                   if ($p6 == 1) {
                       ?>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio6); ?></div></td>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou6); ?></div></td>
                       <?php 
                   } 
                   if ($p7 == 1) {
                       ?>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio7); ?></div></td>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou7); ?></div></td>
                       <?php 
                   } 
                   if ($p8 == 1) {
                       ?>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($precio8); ?></div></td>
                       <td <?= $bg ?>> <div align="right"><?= rdecimal2($preciou8); ?></div></td>
                       <?php 
                   } 
                   ?>
                   <td <?= $bg ?>> <div align="center"><?= ". ". mssql_result($productos, $x, "Refere"); ?></div></td>
               </tr>
               <?php
           }
       }
   }
   ?>
</table>