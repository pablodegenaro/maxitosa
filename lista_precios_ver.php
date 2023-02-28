<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');

if ($_SESSION['login']) {
    $depo = $_POST['depo'];
    $exist = isset($_POST['exist']) ? "1" : "0";
    $marca = $_POST['marca'];
    $iva = isset($_POST['iva']) ? 1.16 : 0;
    $cubi = isset($_POST['cubi']) ? 1 : 0;
    $orden = $_POST['orden'];
    $p1 = str_replace("1","1",$_POST['p1']);
    $p2 = str_replace("1","2",$_POST['p2']);
    $p3 = str_replace("1","3",$_POST['p3']);
    $sumap = $_POST['p1'] + $_POST['p2'] + $_POST['p3'];
    $sumap2 = $p1 + $p2 + $p3;

    if ($marca == '-'){

        if ($depo != "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT *, preciou as preciou1 from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where (saexis.codubic = '$depo') order by saprod.$orden");
            }else{
                $productos = mssql_query("SELECT *, preciou as preciou1 from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where (saexis.codubic = '$depo') and (saexis.existen > 0 or saexis.exunidad > 0) order by saprod.$orden");
            }
        }else if ($depo == "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT saprod.CodProd, marca, saprod.Descrip,  EsExento, sum(saexis.Existen) as existen, Precio1, Precio2, Precio3, sum(saexis.ExUnidad) as exunidad, PrecioU as PrecioU1, PrecioU2, PrecioU3 from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where saexis.codubic IN (SELECT codubic from Sadepo) group by SAPROD.CodProd, Precio1, Precio2, Precio3, PrecioU, PrecioU2, PrecioU3, saprod.Descrip, EsExento,  marca order by saprod.$orden");
            }else{
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip,  EsExento, marca, sum(saexis.Existen) as existen, Precio1, Precio2, Precio3, sum(saexis.ExUnidad) as exunidad, PrecioU as PrecioU1, PrecioU2, PrecioU3 from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where saexis.codubic IN (SELECT codubic from Sadepo) and (saexis.existen > 0 or saexis.exunidad > 0) group by SAPROD.CodProd, Precio1, Precio2, Precio3, PrecioU, PrecioU2, PrecioU3, saprod.Descrip, EsExento,  marca order by saprod.$orden");
            }
        }
    }else{

        if ($depo != "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT *, preciou as preciou1 from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where (saexis.codubic = '$depo') and marca = '$marca' order by saprod.$orden");
            }else{
                $productos = mssql_query("SELECT *, preciou as preciou1 from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where (saexis.codubic = '$depo') and marca = '$marca' and (saexis.existen > 0 or saexis.exunidad > 0) order by saprod.$orden");
            }
        }else if ($depo == "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip,  EsExento, sum(saexis.Existen) as existen, Precio1, Precio2, Precio3, sum(saexis.ExUnidad) as exunidad, PrecioU as PrecioU1, PrecioU2, PrecioU3, marca from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where marca = '$marca' and saexis.codubic IN (SELECT codubic from Sadepo) group by SAPROD.CodProd, Precio1, Precio2, Precio3, PrecioU, PrecioU2, PrecioU3, saprod.Descrip, EsExento,  marca order by saprod.$orden");
            }else{
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip,  EsExento, sum(saexis.Existen) as existen, Precio1, Precio2, Precio3, sum(saexis.ExUnidad) as exunidad, PrecioU as PrecioU1, PrecioU2, PrecioU3, marca from saexis inner join saprod on saexis.codprod = saprod.codprod left join saprod_99 on saprod.codprod = saprod_99.codprod where saexis.codubic IN (SELECT codubic from Sadepo) and (saexis.existen > 0 or saexis.exunidad > 0)  and marca = '$marca' group by SAPROD.CodProd, Precio1, Precio2, Precio3, PrecioU, PrecioU2, PrecioU3, EsExento,  marca, saprod.Descrip order by saprod.$orden");
            }
        }

    }


    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card card-saint">
                        <div class="card-header">
                            <script type="text/javascript">
                                function regresa(){
                                    window.location.href = "principal1.php?page=lista_precios&mod=1";
                                }
                            </script>
                            <h3 class="card-title">Lista de Precios</h3>&nbsp;&nbsp;&nbsp;
                            <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                        </div>
                        <div class="card-body">
                            <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                <table id="example1" class="table table-sm table-bordered table-striped">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr>
                                            <th>C&oacute;digo</th>
                                            <th>Descrip</th>
                                            <th>Marca</th>
                                            <!--BULTOS-->
                                            <th>Bultos</th>
                                            <?php if ($sumap == 0 or $sumap == 3){ ?>
                                                <th>Pre 1 Bul</th>
                                                <th>Pre 2 Bul</th>
                                                <th>Pre 3 Bul</th>
                                            <?php }if ($sumap == 2){  ?>
                                                <th>Pre <?php if ($p1 == 1){ echo $p1; }else{ echo $p2;} ?> Bul</th>
                                                <th>Pre <?php if ($p3 == 3){ echo $p3; }else{ echo $p2;} ?> Bul</th>
                                            <?php }if ($sumap == 1){ ?>
                                                <th>Pre <?php echo $sumap2; ?> Bul</th>
                                            <?php } ?>
                                            <!--PAQUETES-->
                                            <th>Paq</th>
                                            <?php if ($sumap == 0 or $sumap == 3){ ?>
                                                <th>Pre 1 Paq</th>
                                                <th>Pre 2 Paq</th>
                                                <th>Pre 3 Paq</th>
                                            <?php }if ($sumap == 2){  ?>
                                                <th>Pre <?php if ($p1 == 1){ echo $p1; }else{ echo $p2;} ?> Paq</th>
                                                <th>Pre <?php if ($p3 == 3){ echo $p3; }else{ echo $p2;} ?> Paq</th>
                                            <?php }if ($sumap == 1){ ?>
                                                <th>Pre <?php echo $sumap2; ?> Paq</th>
                                            <?php } ?>
                                            <?php if ($cubi == 1){ ?>
                                                <th>Cubicaje</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($productos); $i++) {

                                            if(mssql_result($productos,$i,"esexento")==0) {
                                                $precio1 = mssql_result($productos,$i,"precio1")*$iva;
                                                $precio2 = mssql_result($productos,$i,"precio2")*$iva;
                                                $precio3 = mssql_result($productos,$i,"precio3")*$iva;
                                                $preciou1 = mssql_result($productos,$i,"preciou1")*$iva;
                                                $preciou2 = mssql_result($productos,$i,"preciou2")*$iva;
                                                $preciou3 = mssql_result($productos,$i,"preciou3")*$iva;
                                            }else{
                                                $precio1 = mssql_result($productos,$i,"precio1");
                                                $precio2 = mssql_result($productos,$i,"precio2");
                                                $precio3 = mssql_result($productos,$i,"precio3");
                                                $preciou1 = mssql_result($productos,$i,"preciou1");
                                                $preciou2 = mssql_result($productos,$i,"preciou2");
                                                $preciou3 = mssql_result($productos,$i,"preciou3");
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo mssql_result($productos,$i,"codprod"); ?></td>
                                                <td><?php  echo utf8_encode(mssql_result($productos,$i,"descrip")); ?></td>
                                                <td><?php echo utf8_encode(mssql_result($productos,$i,"marca")); ?></td>
                                                <!--BULTOS-->
                                                <td><?php echo round(mssql_result($productos,$i,"existen")); ?></td>
                                                <?php
                                                if ($sumap == 0 or $sumap == 3){ ?>
                                                    <td><?php echo rdecimal2($precio1); ?></td>
                                                    <td><?php echo rdecimal2($precio2); ?></td>
                                                    <td><?php echo rdecimal2($precio3); ?></td>
                                                    <?php
                                                } if ($sumap == 2){  ?>
                                                    <td><?php if ($p1 == 1){ echo rdecimal2($precio1); }else{ echo rdecimal2($precio2);} ?> </td>
                                                    <td><?php if ($p3 == 3){echo rdecimal2($precio3); }else{ echo rdecimal2($precio2);} ?></td>
                                                    <?php
                                                }if ($sumap == 1){ ?>
                                                    <td><?php if(mssql_result($productos,$i,"esexento")==0) {
                                                        echo rdecimal2(mssql_result($productos,$i,"precio".$sumap2)*$iva);
                                                    }else{
                                                        echo rdecimal2(mssql_result($productos,$i,"precio".$sumap2)); } ?>
                                                        </td><?php
                                                    } ?>
                                                    <!--PAQUETES-->
                                                    <td><?php echo round(mssql_result($productos,$i,"exunidad")); ?></td>
                                                    <?php
                                                    if ($sumap == 0 or $sumap == 3){ ?>
                                                        <td><?php echo rdecimal2($preciou1); ?></td>
                                                        <td><?php echo rdecimal2($preciou2); ?></td>
                                                        <td><?php echo rdecimal2($preciou3); ?></td>
                                                        <?php
                                                    }if ($sumap == 2){  ?>
                                                        <td><?php if ($p1 == 1){ echo rdecimal2($preciou1); }else{ echo rdecimal2($preciou2);} ?></td>
                                                        <td><?php if ($p3 == 3){ echo rdecimal2($preciou3); }else{ echo rdecimal2($preciou2); } ?></td>
                                                        <?php
                                                    }if ($sumap == 1){ ?>
                                                        <td><?php if(mssql_result($productos,$i,"esexento")==0) {
                                                            echo rdecimal2(mssql_result($productos,$i,"preciou".$sumap2)*$iva);
                                                        }else{
                                                            echo rdecimal2(mssql_result($productos,$i,"preciou".$sumap2)); } ?>
                                                            </td><?php
                                                        }
                                                        if ($cubi == 1){ ?>
                                                            <td><?php echo mssql_result($productos,$i,"cubicaje"); ?></td><?php
                                                        } ?>
                                                    </tr>
                                                    <?php
                                                } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
                <?php include "footer.php"; ?>
                <script src="Icons.js" type="text/javascript"></script>
                <?php
            } else {
                header('Location: index.php');
            }
        ?>