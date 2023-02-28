<?php  
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	$fechai = $_POST['fechai'];
	$fechaf = $_POST['fechaf'];
	$marca = $_POST['marca'];
	$sucursal = $_POST['sucursal'];
/*$fechai = normalize_date($fechai);
$fechaf = normalize_date($fechaf);*/
$cont_hay = 0;

$fechaii = normalize_date($_POST['fechai']);
$fechaff = normalize_date($_POST['fechaf']);

if ($sucursal == '00000') {
   $alm_principal = '1000';
} elseif ($sucursal == '00001') {
 $alm_principal = '2000';
}elseif ($sucursal == '00002') {
   $alm_principal = '3000';
}



?>

<div class="content-wrapper">
	<!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
	<section class="content">
		<div class="container">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Reporte de Compras</h1>
				</div>
			</div>
			<div class="row mb-2">
				<div class="col-sm-3 mt-4 form-check-inline">
				</div>
				<div class="col-sm-3 mt-4 form-check-inline">
					<dt class="col-sm-3 text-gray">Desde:</dt>
					<input type="text" class="form-control-sm col-8 text-center" id="fechai" value="<?php echo $fechaii; ?>" readonly>
				</div>
				<div class="col-sm-3 mt-4 form-check-inline">
					<dt class="col-sm-4 text-gray">Hasta:</dt>
					<input type="text" class="form-control-sm col-sm-8 text-center" id="fechaf" value="<?php echo $fechaff; ?>" readonly>&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<script type="text/javascript">
						function regresa(){
							window.location.href = "principal1.php?page=reporte_compras&mod=1";
						}
					</script>
					<button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
				</div>
			</div>

		</div>
        <form name="formulario" method="post" action="reporte_compras_excel.php">
            <input type="hidden" id="sucursal" name="sucursal" value="<?= $sucursal ?>"/>
            <?php

            if ($marca == "-"){

                $consulta = mssql_query("SELECT prod.Codprod
                 FROM SAPROD AS prod 
                 INNER JOIN saexis AS exis
                 ON prod.CodProd = exis.CodProd
                 WHERE (activo = '1' and exis.codubic = '".$alm_principal."') group by prod.CodProd");


             }else{

                $consulta = mssql_query("SELECT prod.Codprod
                  FROM SAPROD AS prod 
                  INNER JOIN saexis AS exis
                  ON prod.CodProd = exis.CodProd
                  where (activo = '1' and exis.codubic = '".$alm_principal."' AND prod.marca LIKE '%".$marca."%') group by prod.CodProd ");


                  if ($marca != "%%"){
                    ?>
                    <div align="center">
                        <p>Proveedor: <?php echo $marca; ?></p>

                        <br><br>
                    </div>
                    <?php
                }
            }
            ?>

            <input type="hidden" name="marca" value="<?php echo $marca; ?>">
            <input type="hidden" name="fechai" value="<?php echo $fechai; ?>">
            <input type="hidden" name="fechaf" value="<?php echo $fechaf; ?>">

            <table id="example1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:100%;">

                <thead style="background-color: #00137f;color: white;">
                    <tr class="ui-widget-header">
                        <th class="ui-widget-header" rowspan="2">#</th>
                        <th class="ui-widget-header" rowspan="2">Código</th>
                        <th class="ui-widget-header" rowspan="2">Descripción</th>
                        <th class="ui-widget-header" rowspan="2">Display x Bulto</th>
                        <th class="ui-widget-header" colspan="2">Último precio de compra</th>
                        <th class="ui-widget-header" rowspan="2">% RENT</th>
                        <th class="ui-widget-header" colspan="2">Fecha penúltima compra</th>
                        <th class="ui-widget-header" colspan="2">Fecha última compra</th>
                        <th class="ui-widget-header" colspan="4">Ventas mes anterior</th>
                        <th class="ui-widget-header" rowspan="2">Venta total último mes</th>
                        <th class="ui-widget-header" rowspan="2">Existencia Actual Bultos</th>
                        <th class="ui-widget-header" rowspan="2">Productos no Vendidos</th>
                        <th class="ui-widget-header" rowspan="2">Días de Inventarios</th>
                        <th class="ui-widget-header" rowspan="2">Sugerido</th>
                        <th class="ui-widget-header" rowspan="2">Pedido</th>
                    </tr>
                    <tr>
                        <th class="ui-widget-header">Display</th>
                        <th class="ui-widget-header">Bulto</th>
                        <th class="ui-widget-header">Fecha</th>
                        <th class="ui-widget-header">Bultos</th>
                        <th class="ui-widget-header">Fecha</th>
                        <th class="ui-widget-header">Bultos</th>
                        <th class="ui-widget-header">1</th>
                        <th class="ui-widget-header">2</th>
                        <th class="ui-widget-header">3</th>
                        <th class="ui-widget-header">4</th>
                    </tr>
                </thead>

                <tbody style="background-color: aliceblue">
                    <?php

                    for($i=0;$i<mssql_num_rows($consulta);$i++){

                        ?>
                        <tr <?php if (($i % 2) != 0){ ?> class="ui-state-default" <?php } ?>>

                            <td style="text-align: center;"> <?php  echo $cont_hay; ?>

                        </td>

                        <!-- ******************************************************************************************* -->

                        <?php

                        $codproducto =mssql_query("SELECT prod.CodProd FROM SAPROD AS prod 
                         INNER JOIN SAITEMCOM AS item 
                         ON prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."'
                         WHERE item.CodSucu = '$sucursal' AND tipocom IN ('H','J')  ");


                        if (mssql_num_rows($codproducto)>0){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo mssql_result($codproducto,0,"codprod");

                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->



                        <?php

                        $descripcion = mssql_query("SELECT prod.Descrip FROM SAPROD AS prod 
                         INNER JOIN SAITEMCOM AS item 
                         ON prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."'
                         WHERE item.CodSucu = '$sucursal' AND tipocom IN ('H','J')  ");


                        if (mssql_num_rows($descripcion)>0){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo utf8_encode(mssql_result($descripcion,0,"Descrip"));
                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>

                        <!-- ******************************************************************************************* -->


                        <?php

                        $displayxbultos = mssql_query("SELECT prod.CantEmpaq DisplayBultos FROM SAPROD AS prod 
                         INNER JOIN SAITEMCOM AS item 
                         ON prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."'
                         WHERE item.CodSucu = '$sucursal' AND  item.TipoCom IN ('H','J') ");


                        if (mssql_num_rows($displayxbultos)>0){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo number_format(mssql_result($displayxbultos, 0, "DisplayBultos"), 0, ",", ".");
                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->


                        <?php

                        $costoDisplay = mssql_query("SELECT TOP(1) item.FechaE, prod2.Costo_total, CantEmpaq, (prod2.Costo_total/CantEmpaq) CostoDisplay
                            FROM SAPROD AS prod
                            INNER JOIN SAPROD_99 AS prod2 ON prod2.CodProd = prod.CodProd
                            INNER JOIN SAITEMCOM AS item ON prod.CodProd = item.CodItem
                            WHERE item.coditem = '".trim(mssql_result($consulta, $i, 'CodProd'))."' 
                            AND item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') ORDER BY FechaE DESC");


                        if (mssql_num_rows($costoDisplay)>0){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo rdecimal2(mssql_result($costoDisplay,0,"CostoDisplay"));
                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->

                        <?php

                        $CostoBultos = mssql_query("SELECT TOP(1) item.FechaE, prod2.Costo_total, CantEmpaq, (prod2.Costo_total) CostoBultos
                           FROM SAPROD AS prod
                           INNER JOIN SAPROD_99 AS prod2 ON prod2.CodProd = prod.CodProd
                           INNER JOIN SAITEMCOM AS item ON prod.CodProd = item.CodItem
                           WHERE item.coditem = '".trim(mssql_result($consulta, $i, 'CodProd'))."' 
                           AND item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') ORDER BY FechaE DESC");


                        if (mssql_num_rows($CostoBultos)>0){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo rdecimal2(mssql_result($CostoBultos,0,"CostoBultos"));
                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->

                        <?php

                        $rentabilidad = mssql_query("SELECT  prod.Precio1 Precio1, prod.CostAct CostoActual
                           FROM SAPROD AS prod 
                           INNER JOIN SAITEMCOM AS item 
                           ON prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."'
                           WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') ");

                        $precio1p = 0;
                        $costoActual1p = 0;

                        if (mssql_num_rows($rentabilidad) > 0) {
                            $precio1p = mssql_result($rentabilidad,0,"Precio1");
                            $costoActual1p = mssql_result($rentabilidad,0,"CostoActual");
                        }

                        $r1=0;
                        $p1=0;
                        $r=0;
                        $p=0;
                        $r1= $precio1p - $costoActual1p;

                        if ($r1 == 0){
                            $r = 0;
                        }else{
                            $r = $r1;
                        }


                        $p1 = $r * 100;

                        if ($p1 == 0){
                            $p = 0;
                        }else{
                            $p = $p1;
                        }


                        if ($p == 0 OR $precio1p == 0){
                            $porcentaje = 0;
                        } else{
                            $porcentaje = $p / $precio1p;
                        }

                        if (mssql_num_rows($rentabilidad)>0){
                            ?>



                            <td  <?php if ($porcentaje > 30) { echo 'BGCOLOR="#ff3939"';} ?> style="text-align: center; width: 40px;">
                                <?php
                                echo rdecimal2($porcentaje, 1);
                                echo "  %";
                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->

                        <?php



                        $FechaPenultimaCompra = mssql_query(" SELECT  top 2 item.FechaE PenultimaCompra, item.NumeroD
                           FROM SACOMP AS comp
                           INNER JOIN SAITEMCOM AS item
                           ON comp.NumeroD = item.NumeroD
                           INNER JOIN SAPROD AS prod
                           ON item.CodItem = prod.CodProd
                           WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') AND prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."' AND comp.numeroN is null group by item.NumeroD ,item.FechaE order by item.FechaE desc 
                           ");


                        if (mssql_num_rows($FechaPenultimaCompra)>1){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo date('d-m-Y', strtotime(mssql_result($FechaPenultimaCompra,1,"PenultimaCompra")));
                                ?>
                            </td>
                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->

                        <?php

                        $BultosPenultimaCompra = mssql_query("SELECT  top 2 item.FechaE, item.NumeroD, sum(item.Cantidad) cant
                           FROM SACOMP AS comp
                           INNER JOIN SAITEMCOM AS item
                           ON comp.NumeroD = item.NumeroD
                           INNER JOIN SAPROD AS prod
                           ON item.CodItem = prod.CodProd
                           WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') AND prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."' AND comp.numeroN is null group by item.NumeroD ,item.FechaE order by item.FechaE desc ");

                        if (mssql_num_rows($BultosPenultimaCompra)>1){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo number_format(mssql_result($BultosPenultimaCompra, 1, "cant"), 0, ",", ".");
                                ?>
                            </td>
                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->

                        <?php

                        $FechaCompra = mssql_query(" SELECT  top 2 item.FechaE UltimaCompra, item.NumeroD
                            FROM SACOMP AS comp
                            INNER JOIN SAITEMCOM AS item
                            ON comp.NumeroD = item.NumeroD
                            INNER JOIN SAPROD AS prod
                            ON item.CodItem = prod.CodProd
                            WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') AND prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."' AND comp.numeroN is null group by item.NumeroD ,item.FechaE order by item.FechaE desc 
                            ");



                        if (mssql_num_rows($FechaCompra)>0){
                            ?>

                            <?php
                            if(!mssql_result($FechaCompra,0,"UltimaCompra")){
                                ?>

                                <td style="text-align: center;"> 0 </td>

                                <?php
                            }else{

                                ?>

                                <td style="text-align: center;">
                                    <?php
                                    echo date('d-m-Y', strtotime(mssql_result($FechaCompra,0,"UltimaCompra")))

                                    ?>
                                </td>

                                <?php
                            }

                        } else {
                            ?>

                            <td style="text-align: center;"> 0 </td>

                            <?php
                        }
                        ?>

                        <!-- ******************************************************************************************* -->

                        <?php

                        $CantUltimaCompra = mssql_query("SELECT  top 2 item.FechaE, item.NumeroD, sum(item.Cantidad) CantUltimaCompra
                            FROM SACOMP AS comp
                            INNER JOIN SAITEMCOM AS item
                            ON comp.NumeroD = item.NumeroD
                            INNER JOIN SAPROD AS prod
                            ON item.CodItem = prod.CodProd
                            WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') AND prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."' AND comp.numeroN is null group by item.NumeroD ,item.FechaE order by item.FechaE desc ");


                        if (mssql_num_rows($CantUltimaCompra)>0){
                            ?>

                            <td style="text-align: center;">
                                <?php
                                echo number_format(mssql_result($CantUltimaCompra, 0, "CantUltimaCompra"), 0, ",", ".");

                                ?>
                            </td>

                            <?php
                        }else{
                            ?>

                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>



                        <!-- ******************************************************************************************* -->

                        <?php

                        $separa = explode("-",$fechai);
                        $dia = $separa[0];
                        $mes = $separa[1];
                        $ano = $separa[2];

                        $fechaiA = date('Y-m-d', mktime(0,0,0,($mes)-1,1, date('Y')));
                        $fechafA = date('Y-m-d', mktime(0,0,0,$mes,1, date('Y'))-1);


                        $ventas = mssql_query("SELECT fechaE, COALESCE((CASE WHEN EsUnid=1 THEN cantidad/CantEmpaq ELSE Cantidad END), 0) AS cantidadBult
                          FROM SAITEMFAC INNER JOIN SAPROD prod ON prod.CodProd = saitemfac.CodItem
                          WHERE saitemfac.CodSucu = '$sucursal' AND CodItem = '".(trim(mssql_result($consulta, $i, 'CodProd')))."' AND DATEADD(dd, 0, DATEDIFF(dd, 0, fechaE)) between '$fechaiA' and '$fechafA' AND TipoFac in ('A','C')
                          ");


                        $M10 = $N10 = $O10 = $P10 = $Q10 = 0;

                        for ($j=0; $j<mssql_num_rows($ventas); ++$j){

                            if((date('Y-m-d', strtotime(trim(mssql_result($ventas, $j, 'fechaE')))) >= date('Y-m-d', mktime(0,0,0,($mes)-1,1, date('Y')))) and (date('Y-m-d', strtotime(trim(mssql_result($ventas, $j, 'fechaE')))) <= date('Y-m-d',mktime(0,0,0,($mes)-1,7, date('Y'))))){

                                $M10+=(1*mssql_result($ventas, $j, 'cantidadBult'));

                            }elseif ((date('Y-m-d', strtotime(trim(mssql_result($ventas, $j, 'fechaE')))) >= date('Y-m-d', mktime(0,0,0,($mes)-1,8, date('Y')))) and (date('Y-m-d', strtotime(trim(mssql_result($ventas, $j, 'fechaE')))) <= date('Y-m-d',mktime(0,0,0,($mes)-1,14, date('Y'))))){

                                $N10+=(1*mssql_result($ventas, $j, 'cantidadBult'));

                            }elseif((date('Y-m-d', strtotime(trim(mssql_result($ventas, $j, 'fechaE')))) >= date('Y-m-d', mktime(0,0,0,($mes)-1,15, date('Y')))) and (date('Y-m-d', strtotime(trim(mssql_result($ventas, $j, 'fechaE')))) <= date('Y-m-d', mktime(0,0,0,($mes)-1,21, date('Y'))))){

                                $O10+=(1*mssql_result($ventas, $j, 'cantidadBult'));

                            }else{

                                $P10+=(1*mssql_result($ventas, $j, 'cantidadBult'));
                            }
                        }

                        if (mssql_num_rows($ventas)>0) {
                            ?>

                            <td style="text-align: center;"> <?php echo rdecimal2($M10, 2); ?> </td>
                            <td style="text-align: center;"> <?php echo rdecimal2($N10, 2); ?> </td>
                            <td style="text-align: center;"> <?php echo rdecimal2($O10, 2); ?> </td>
                            <td style="text-align: center;"> <?php echo rdecimal2($P10, 2); ?> </td>

                            <?php
                        }else{
                            ?>
                            <td style="text-align: center;"> 0 </td>
                            <td style="text-align: center;"> 0 </td>
                            <td style="text-align: center;"> 0 </td>
                            <td style="text-align: center;"> 0 </td>
                            <?php
                        }
                        ?>


                        <!-- ******************************************************************************************* -->


                        <td style="text-align: center;">
                            <?php

                            $ventaTotalUltimoMes = $M10+$N10+$O10+$P10;

                            echo rdecimal2($ventaTotalUltimoMes, 2);

                            ?>
                        </td>


                        <!-- ******************************************************************************************* -->

                        <td style="text-align: center;">
                            <?php

                            $BultosExistente  = mssql_query("SELECT exis.Existen + COALESCE(exis.ExUnidad / NULLIF(prod.cantempaq, 0), 0) as  bultosexis
                               FROM SAEXIS exis 
                               INNER JOIN SAPROD prod ON prod.CodProd = exis.CodProd
                               INNER JOIN SADEPO depo ON depo.CodUbic = exis.CodUbic
                               WHERE exis.CodUbic='$alm_principal' AND depo.Clase='$sucursal' AND exis.CodProd='".trim(mssql_result($consulta, $i, 'CodProd'))."' ");

                            $ExistenciaActualBultos = mssql_result($BultosExistente,0,"bultosexis");
                            echo rdecimal(floatval($ExistenciaActualBultos), 2);
                            ?>
                        </td>

                        <!-- ******************************************************************************************* -->

                        <td style="text-align: center;">
                            <?php

                            $prodNoVendidos = mssql_query("SELECT COALESCE(SUM((CASE WHEN EsUnid=1 THEN item.Cantidad/CantEmpaq ELSE Cantidad END)), 0) AS cantidadBult
                               FROM SAFACT as fact
                               INNER JOIN SAITEMFAC item ON fact.NumeroD = item.NumeroD
                               INNER JOIN SAPROD prod ON prod.CodProd = item.CodItem
                               WHERE item.CodSucu = '$sucursal' AND (fact.NumeroD = item.NumeroD AND fact.TipoFac = item.TipoFac)
                               AND (SUBSTRING(CONVERT(VARCHAR,fact.FechaE,120),1,10) >= '$fechai' AND SUBSTRING(CONVERT(VARCHAR,fact.FechaE,120),1,10) <= '$fechaf')
                               AND CodItem = '".trim(mssql_result($consulta, $i, 'CodProd'))."' AND item.NroLineaC = 0 AND item.TipoFac = 'P' AND fact.Monto <> 0");


                            echo number_format(mssql_result($prodNoVendidos, 0, "cantidadBult"), 1, ",", ".");

                            ?>
                        </td>

                        <!-- ******************************************************************************************* -->


                        <td style="text-align: center;">
                            <?php

                            if($ventaTotalUltimoMes == 0){

                                $DiasdeInventario = 0;
                                echo $DiasdeInventario;

                            }else{

                                $DiasdeInventario = ($ExistenciaActualBultos/$ventaTotalUltimoMes)*30;
                                echo rdecimal2($DiasdeInventario, 2);

                            }
                            ?>
                        </td>


                        <!-- ******************************************************************************************* -->

                        <td style="text-align: center;">
                            <?php

                            $sugeridoAnt = $ventaTotalUltimoMes*1.2;
                            $sugerido = ($ventaTotalUltimoMes*1.2) - $ExistenciaActualBultos;
                            $sugerido = ($sugerido > 0) ? $sugerido : 0;

                            echo rdecimal2($sugerido, 1);

                            ?>

                        </td>

                        <!-- ******************************************************************************************* -->
                        <td style="text-align: center;">
                            <input type="text" name="n[]" style="text-align: right; width: 90%;">
                            <input type="hidden" name="v[]" value="<?php echo trim(mssql_result($consulta, $i, 'CodProd')); ?>">
                        </td>


                        <!-- ******************************************************************************************* -->


                        <?php
                        $cont_hay++;
                    }

                    ?>
                </tr>

                <tr>
                    <td colspan="21">
                        <div align="center">
                            <div align="center">
                                <br>
                                <button type="submit" name="Submit"    class="btn btn-info"><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
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