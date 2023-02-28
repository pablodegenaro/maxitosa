<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');

if ($_SESSION['login']) {

    $depo = $_POST['depo'];
    $exist = isset($_POST['exist']) ? 1 : 0;
    $proveedor = $_POST['proveedor'];
    $orden = $_POST['orden'];
    $precio = $_POST['precio'];

    switch ($precio) {
        case 1: 
        echo $prenom = 'F. Sur';
        break;
        case 2: 
        echo $prenom = 'Casco M.';
        break;
        case 3: 
        echo $prenom = 'Mayo';
        break;
    }

    if ($proveedor == '-'){

        if ($depo != "-"){
            if ($exist == "0"){ /*listo*/
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod
                    left join saprod_99 on saprod.codprod = saprod_99.codprod
                    left join SAINSTA on saprod.CodInst = SAINSTA.CodInst
                    where (saexis.codubic = '$depo') order by saprod.$orden");
            }else{ 
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod
                    left join saprod_99 on saprod.codprod = saprod_99.codprod
                    left join SAINSTA on saprod.CodInst = SAINSTA.CodInst
                    where (saexis.codubic = '$depo') and (saexis.existen > 0 or saexis.exunidad > 0) order by saprod.$orden");
            }
        }else if ($depo == "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod
                    left join saprod_99 on saprod.codprod = saprod_99.codprod
                    left join SAINSTA on saprod.CodInst = SAINSTA.CodInst
                    where saexis.codubic IN (SELECT CodUbic FROM sadepo where Descrip like 'Almacen%') group by SAPROD.CodProd,  saprod.Refere, Profit1, Profit2, Profit3, SAEXIS.Existen, SAEXIS.ExUnidad, CantEmpaq, EsExento, saprod.Descrip, saprod_99.proveedor, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria order by saprod.$orden");
            }else{
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod
                    left join saprod_99 on saprod.codprod = saprod_99.codprod

                    where
                    saexis.codubic IN (Select codubic from Sadepo)
                    and
                    (saexis.existen > 0 or saexis.exunidad > 0)
                    group by SAPROD.CodProd, saprod.Refere, Profit1, Profit2, Profit3, SAEXIS.Existen, SAEXIS.ExUnidad, CantEmpaq, EsExento, saprod.Descrip, saprod_99.proveedor, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria order by saprod.$orden");
            }
        }
    }else{

        if ($depo != "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod

                    left join saprod_99 on saprod.codprod = saprod_99.codprod where (saexis.codubic = '$depo') and saprod_99.proveedor = '$proveedor' order by saprod.$orden");
            }else{ 
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod

                    left join saprod_99 on saprod.codprod = saprod_99.codprod where (saexis.codubic = '$depo') and (saexis.existen > 0 or saexis.exunidad > 0) and saprod_99.proveedor = '$proveedor' group by SAPROD.CodProd, Profit1, Profit2, Profit3, SAEXIS.Existen, SAEXIS.ExUnidad, CantEmpaq, EsExento, saprod.Descrip, saprod_99.proveedor, saprod.esexento, saexis.Existen, saexis.ExUnidad order by saprod.$orden");
            }
        }else if ($depo == "-"){
            if ($exist == "0"){
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod

                    left join saprod_99 on saprod.codprod = saprod_99.codprod where saprod_99.proveedor = '$proveedor' and saexis.codubic IN (Select codubic from Sadepo)  group by SAPROD.CodProd, Profit1, Profit2, Profit3, SAEXIS.Existen, SAEXIS.ExUnidad, CantEmpaq, EsExento, saprod.Descrip, saprod_99.proveedor order by saprod.$orden");
            }else{
                $productos = mssql_query("SELECT saprod.CodProd, saprod.Descrip, saprod_99.proveedor, saprod.Refere, saexis.Existen as existen, COALESCE(Profit1,0) as precio1, COALESCE(Profit2,0)  as precio2, COALESCE(Profit3,0)  as precio3, saexis.ExUnidad as exunidad, COALESCE(Profit1/NULLIF(CantEmpaq,0), 0) as preciou1, COALESCE(Profit2/NULLIF(CantEmpaq,0), 0) as preciou2 ,  COALESCE(Profit3/NULLIF(CantEmpaq,0), 0) as PrecioU3, saprod.esexento, saprod_99.capacidad_botella, saprod_99.clasificacion_categoria, saprod.cantempaq, saprod.codinst, saprod_99.sub_clasificacion_categoria
                    from
                    saexis inner join saprod on saexis.codprod = saprod.codprod
                    left join saprod_99 on saprod.codprod = saprod_99.codprod

                    where
                    saexis.codubic IN (Select codubic from Sadepo)
                    and
                    (saexis.existen > 0 or saexis.exunidad > 0)  and saprod_99.proveedor = '$proveedor'  group by SAPROD.CodProd, saprod.Refere, Profit1, Profit2, Profit3, SAEXIS.Existen, SAEXIS.ExUnidad, CantEmpaq, EsExento, saprod.Descrip, saprod_99.proveedor order by saprod.$orden");
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
                                    window.location.href = "principal1.php?page=lista_precios_divisas2&mod=1";
                                }
                            </script>
                            <h3 class="card-title">Lista de Precios 2</h3>&nbsp;&nbsp;&nbsp;
                            <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                        </div>
                        <div class="card-body">
                            <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                <table id="example3" class="table table-sm table-bordered table-striped">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr>
                                            <th>C&oacute;digo</th>
                                            <th>Proveedor</th>
                                            <th>Descrip</th>
                                            <th>Clasificacion</th>
                                            <th> SUB Clasificacion</th>
                                            <th>Capacidad</th>
                                            <th>Cantidad Empaque</th>
                                            <th>Pre <?= $prenom; ?> Bul</th>
                                            <th>Pre <?= $prenom; ?> Paq</th>
                                            <th>Referencia</th>
                                            <th>Instancia Padre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($productos); $i++) {

                                            $ins = mssql_result($productos, $i, 'codinst');
                                            $query = mssql_query("SELECT inspadre from SAINSTA where CodInst='$ins'");
                                            $instpadre= mssql_result($query,$m,"inspadre");
                                            
                                            if ($ins != 1 & $instpadre == 1)  {
                                                $inspadredesc = 'LICORES';
                                            }elseif ($ins != 14 & $instpadre == 14) {
                                                $inspadredesc = 'MISCELANEOS';
                                            }elseif ($ins == 24 & $instpadre == 0)  {
                                               $inspadredesc = 'BEBIDAS NO-ALCOHOLICAS';

                                           }

                                           $pre = $preu = 0;
                                           switch ($precio) {
                                            case 1: 
                                            $pre = mssql_result($productos,$i,"precio1"); 
                                            $preu = mssql_result($productos,$i,"preciou1"); 
                                            break;
                                            case 2: 
                                            $pre = mssql_result($productos,$i,"precio2"); 
                                            $preu = mssql_result($productos,$i,"preciou2"); 
                                            break;
                                            case 3: 
                                            $pre = mssql_result($productos,$i,"precio3"); 
                                            $preu = mssql_result($productos,$i,"preciou3"); 
                                            break;
                                        }

                                        ?>
                                        <tr>
                                            <td><?php echo mssql_result($productos,$i,"codprod"); ?></td>
                                            <td><?php echo utf8_encode(mssql_result($productos,$i,"proveedor")); ?></td>
                                            <td><?php  echo utf8_encode(mssql_result($productos,$i,"descrip")); ?></td>
                                            <td><?php  echo utf8_encode(mssql_result($productos,$i,"clasificacion_categoria")); ?></td>
                                            <td><?php  echo utf8_encode(mssql_result($productos,$i,"sub_clasificacion_categoria")); ?></td>
                                            <td><?php  echo utf8_encode(mssql_result($productos,$i,"capacidad_botella")); ?></td>
                                            <td><?php echo rdecimal2(mssql_result($productos,$i,"cantempaq")); ?></td>
                                            <!--BULTOS-->
                                            <td><?php echo rdecimal2($pre); ?></td>
                                            <!--PAQUETES-->
                                            <td><?php echo rdecimal2($preu); ?></td>
                                            <td><?php echo utf8_encode(mssql_result($productos,$i,"Refere")); ?></td>
                                            <td><?php echo $inspadredesc; ?></td>
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