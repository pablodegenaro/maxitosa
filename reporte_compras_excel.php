<?
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=REPORTE_COMPRAS".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php 
require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');

$fechaie 	= $_POST['fechai'];
$fechafe 	= $_POST['fechaf'];
$marcae 	= $_POST['marca'];
$sucursal = $_POST['sucursal'];
$arreglo 	= $_POST['n'];
$arreglo2 	= $_POST['v'];
$fechai 	= normalize_date($fechaie);
$fechaf 	= normalize_date($fechafe);


if ($sucursal == '00000') {
 $alm_principal = '1000';
} elseif ($sucursal == '00001') {
 $alm_principal = '2000';
}elseif ($sucursal == '00002') {
 $alm_principal = '3000';
}



?>
<style type="text/css">
	<!--
	.Estilo1 {
		font-size: 14px;
	}
	.Estilo2 {
		font-size: 20px;
		font-weight: bold;

	}
  -->
</style>
<style type="text/css">
  .formato
  {mso-style-parent:style;
   mso-number-format:"\@";}
 </style>
 <style type="text/css">
   table, th, td {
    border: 1px solid black;
    border-width: thin;
  }
</style>

<p style="font-size: 25px; font-weight: bold; "> Reporte de Compras</p>

<p style="font-size: 18px; ">Proveedor: <?php echo ($marca=='-') ? "TODOS" : $marca; ?></p>

<p style="font-size: 18px; ">Desde: <?php echo $fechai; ?> &nbsp;&nbsp; Hasta: <?php echo $fechaf; ?></p>

<?php 
if ($marca != "%%"){
}

?>

<table width="900" border="0" class="Estilo1">

  <thead>
    <tr class="ui-widget-header">
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">C&oacute;digo</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Descripci&oacute;n</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Display x Bulto</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" colspan="2">&Uacute;ltimo precio de compra</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">% RENT</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" colspan="2">Fecha Pen&uacute;ltima compra</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" colspan="2">Fecha &uacute;ltima compra</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" colspan="4">Ventas mes anterior</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Venta total &uacute;ltimo mes</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Existencia Actual Bultos</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Productos no Vendidos</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">D&iacute;as de Inventarios</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Sugerido</th>  
     <th style="background-color: #cc0000; color: white; font-size: 15px;" rowspan="2">Pedido</th>
   </tr>
   <tr>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">Display</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">Bulto</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">Fecha</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">Bultos</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">Fecha</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">Bultos</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">1</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">2</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">3</th>
     <th style="background-color: #cc0000; color: white; font-size: 15px;">4</th>
   </tr>
 </thead>

 <tbody>


  <?php 

/*for ($i=0; $i < count($arreglo); $i++) {
	
	if ($arreglo[$i]>0) {*/

   if ($marca == "-"){

	/*$consulta = mssql_query("SELECT prod.Codprod
	  FROM SAPROD AS prod 
	    INNER JOIN saexis AS exis
	    ON prod.CodProd = exis.CodProd
	    where (activo = '1' AND exis.codubic = '01' AND prod.Codprod LIKE '$arreglo2[$i]') OR (activo = '1' AND exis.codubic = '03' AND prod.Codprod LIKE '$arreglo2[$i]')  group by prod.CodProd ");*/

      $consulta = mssql_query("SELECT prod.Codprod
       FROM SAPROD AS prod 
       INNER JOIN saexis AS exis
       ON prod.CodProd = exis.CodProd
       where (activo = '1' AND exis.codubic = '".$alm_principal."') group by prod.CodProd ");

     }else{

	 /*$consulta = mssql_query("SELECT prod.Codprod
	  FROM SAPROD AS prod 
	    INNER JOIN saexis AS exis
	    ON prod.CodProd = exis.CodProd
	    where (activo = '1' and exis.codubic = '01' AND prod.marca LIKE '%".$marca."%' AND prod.Codprod LIKE '$arreglo2[$i]') 
	    OR (activo = '1' AND exis.codubic = '03' AND prod.marca LIKE '%".$marca."%' AND prod.Codprod LIKE '$arreglo2[$i]') group by prod.CodProd ");*/

      $consulta = mssql_query("SELECT prod.Codprod
       FROM SAPROD AS prod 
       INNER JOIN saexis AS exis
       ON prod.CodProd = exis.CodProd
       where (activo = '1' and exis.codubic = '".$alm_principal."' AND prod.marca LIKE '%".$marca."%') group by prod.CodProd ");

     }

     for($i=0;$i<mssql_num_rows($consulta);$i++){
      ?>

      <tr <?php if (($i % 2) != 0){ ?> class="ui-state-default" <?php } ?>>

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
            echo trim(mssql_result($codproducto,0,"codprod"));

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
            echo mssql_result($descripcion,0,"Descrip");
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
          WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J') ");


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

      /*$costoDisplay = mssql_query("SELECT (costact/CantEmpaq) CostoDisplay, (costo*existen) CostoBultos
                      FROM SAPROD AS prod
                      INNER JOIN SAITEMCOM AS item
                      ON prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."'
                      WHERE item.TipoCom = 'H' ");*/

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


      /*$CostoBultos = mssql_query("SELECT item.costo, item.CodItem, prod.existen, (costo*existen) CostoBultos
                          FROM SAPROD AS prod
                          INNER JOIN SAITEMCOM AS item
                          ON prod.CodProd = item.CodItem
                          where item.coditem = '".trim(mssql_result($consulta, $i, 'CodProd'))."' and item.TipoCom = 'H'");*/

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
                          	WHERE item.CodSucu = '$sucursal' AND item.TipoCom IN ('H','J')  ");

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
                              echo "%";
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

      /*$FechaCompra = mssql_query("SELECT  FechaUC
                           FROM SAPROD AS prod
                           INNER JOIN SAITEMCOM AS item
                           ON prod.CodProd = '".trim(mssql_result($consulta, $i, 'CodProd'))."'
                           WHERE item.TipoCom = 'H' ");*/

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

      /*  print_r($fechai);
       print_r("<br>");
      print_r($fechaf);
      print_r("<br>");
      */
      $separa = explode("-",$fechai);
      $dia = $separa[0];
      $mes = $separa[1];
      $ano = $separa[2];

      $fechaiA = date('Y-m-d', mktime(0,0,0,($mes)-1,1, date('Y')));
      $fechafA = date('Y-m-d', mktime(0,0,0,$mes,1, date('Y'))-1);


      /*print_r($fechaiA);
      print_r("<br>");
      print_r($fechafA);
      print_r("<br>");*/

      /*$ventas = mssql_query("SELECT cantidad, fechaE
                             FROM SAITEMFAC
                             WHERE CodItem = '".(mssql_result($consulta, $i, 'CodProd'))."' AND DATEADD(dd, 0, DATEDIFF(dd, 0, fechaE)) between '$fechaiA' and '$fechafA'");*/

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

          /*$prodNoVendidos = mssql_query("SELECT COALESCE(SUM((CASE WHEN EsUnid=1 THEN Cantidad/CantEmpaq ELSE Cantidad END)), 0) AS cantidadBult
                                  FROM SAITEMFAC item INNER JOIN SAPROD prod ON prod.CodProd = item.CodItem
                                  WHERE CodItem = '".(mssql_result($consulta, $i, 'CodProd'))."' AND item.FechaE BETWEEN '$fechaiA' AND '$fechafA' AND TipoFac = 'F' AND OTipo IS NULL");*/

                                  $prodNoVendidos = mssql_query("SELECT COALESCE(SUM((CASE WHEN EsUnid=1 THEN item.Cantidad/CantEmpaq ELSE Cantidad END)), 0) AS cantidadBult
                                    FROM SAFACT as fact
                                    INNER JOIN SAITEMFAC item ON fact.NumeroD = item.NumeroD
                                    INNER JOIN SAPROD prod ON prod.CodProd = item.CodItem
                                    WHERE item.CodSucu = '$sucursal' AND (fact.NumeroD = item.NumeroD AND fact.TipoFac = item.TipoFac)
                                    AND (SUBSTRING(CONVERT(VARCHAR,fact.FechaE,120),1,10) >= '$fechaie' AND SUBSTRING(CONVERT(VARCHAR,fact.FechaE,120),1,10) <= '$fechafe')
                                    AND CodItem = '".mssql_result($consulta, $i, 'CodProd')."' AND item.NroLineaC = 0 AND item.TipoFac = 'p' AND fact.Monto <> 0");


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
                                  <?php

                                  echo !is_null($arreglo[$i])
                                  ? $arreglo[$i]
                                  : '';
                                  ?>
                                </td>

                                <!-- ******************************************************************************************* -->


                                <?php
                                $cont_hay++;
                                ?>

                              </tr>

                              <?php


                            }
                            ?>

                          </tbody>
                        </table>