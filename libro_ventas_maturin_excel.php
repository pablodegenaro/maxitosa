<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Libro_Ventas_Maturin".date('d-m-Y h:i a',time() - 3600*date('I')).".xls");
header("Pragma: no-cache");
header("Expires: 0");
require("conexion.php");
require("funciones.php");
session_start();
set_time_limit(0);
ini_set('memory_limit', '512M');

$fechai = $_GET['fechai'];
$fechaf = $_GET['fechaf'];
$sucursal = $_GET['sucu'];
$fechai = normalize_date($fechai) . ' 00:00:00';
$fechaf = normalize_date($fechaf) . ' 23:59:59';


$resumen1 = mssql_query("SET DATEFORMAT YMD; SELECT * FROM DBO.VW_ADM_LIBROIVAVENTAS_MATURIN WHERE CODSUCU = '$sucursal' and  ('$fechai'<=FECHAEMISION) AND (FECHAEMISION<='$fechaf') AND ((FECHARETENCION IS NULL) OR (('$fechai'<=FECHARETENCION) AND (FECHARETENCION<='$fechaf'))) and TIPODOC != 'RET' ORDER BY (YEAR(FECHAFACTURA)*10000)+(MONTH(FECHAFACTURA)*100)+DAY(FECHAFACTURA),FECHAT");
$resumen2 = mssql_query("SET DATEFORMAT YMD; SELECT * FROM DBO.VW_ADM_LIBROIVAVENTAS_MATURIN WHERE CODSUCU = '$sucursal' and  ('$fechai'<=FECHAEMISION) AND (FECHAEMISION<='$fechaf') AND (NOT(FECHARETENCION IS NULL) AND NOT(('$fechai'<=FECHARETENCION) AND (FECHARETENCION<='$fechaf'))) AND TIPO='81' ORDER BY FECHAT");
$num = mssql_num_rows($resumen1); ?>
<table id="example1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:100%;">
    <thead  style="background-color: #00137f;color: white;">
        <tr id="cells">
            <td align="center">Nro. Ope</td>
            <th align="center" >Fecha</th>
            <th align="center" >Rif</th>
            <th align="center" >Nombre o Raz&oacute;n Social</th>
            <th align="center" >Numero de Comprobante</th>
            <th align="center" >Numero de Factura</th>
            <th align="center" >Numero de Nota Debito</th>
            <th align="center" >Numero de Nota Credito</th>
            <th align="center" >Tipo de Transacc.</th>
            <th align="center" >Numero Factura Afectada</th>
            <th align="center" >Ventas Gravadas Incluyendo IVA</th>
            <th align="center" >Impuesto al PVP</th>
            <th align="center" >IVA Percibido</th>  
            <th align="center" >Ventas No Gravadas</th> 
            <th align="center" >Ventas Exentas</th>
            <th align="center" >Base Imponible 16 %</th>                        
            <th align="center" >% Alic</th>
            <th align="center" >Impuesto IVA</th>
            <th align="center" >IVA Retenido (por el comprador)</th>
        </tr>
    </thead>
    <tbody style="background-color: aliceblue">
        <?php
        $ventasnogravadas = $totalventasconiva = $impuestoalpvp = $impuestopercibido = $ventasexentas = $ivaretenido = $impuestoiva= 0;
        for($i=0;$i<$num;$i++){
            $k = $i+1;
            $numerodoc = mssql_result($resumen1, $i, 'numerodoc');
            $retencion_dato = mssql_query("SELECT * FROM DBO.VW_ADM_LIBROIVAVENTAS WHERE CODSUCU = '$sucursal' and  ('$fechai'<=FECHAEMISION) AND (FECHAEMISION<='$fechaf') AND ((FECHARETENCION IS NULL) OR (('$fechai'<=FECHARETENCION) AND (FECHARETENCION<='$fechaf'))) and factafectada = '$numerodoc' and tipodoc = 'RET'");
            ?>
            <tr >
                <!-- <td align="center">Nro. Ope</td> -->
                <td><?php echo $k; ?></td>
                <!-- <th align="center" width="100">Fecha</th> -->
                <td align="center"><?php echo date('d/m/Y', strtotime(mssql_result($resumen1, $i, 'fechaemision'))); ?></td>
                <!-- <th align="center" width="100">Rif</th> -->
                <td><?php echo mssql_result($resumen1, $i, 'rifcliente'); ?></td>
                <!-- <th align="center" width="200">Nombre o Raz&oacute;n Social</th> -->
                <td><?php echo utf8_encode(mssql_result($resumen1, $i, 'nombre')); ?></td>
                <!-- <th align="center" width="50">Numero de Comprobante</th> -->
                <td align="right"><?php echo mssql_result($resumen1, $i, 'nroretencion'); ?></td>
                <!-- <th align="center" width="100">Numero de Factura</th> -->
                <td><?php
                            //$numerod=mssql_result($resumen1, $i, 'numerodoc');
                $tipodoc=mssql_result($resumen1, $i, 'tipo');

                if ($tipodoc =='A') {
                    echo $numerodfact=mssql_result($resumen1, $i, 'numerodoc');
                }else{
                    echo $numerodfact='';
                }
            ?></td>
            <!-- <th align="center" width="100">Numero de Nota Debito</th> -->
            <td></td>
            <!-- <th align="center" width="100">Numero de Nota Credito</th> -->
            <td>
                <?php
                            //$numerodd=mssql_result($resumen1, $i, 'numerodoc');
                $tipodoc1=mssql_result($resumen1, $i, 'tipo');

                if ($tipodoc1 =='B') {
                    echo $numerodev=mssql_result($resumen1, $i, 'numerodoc');
                }else{
                    echo $numerodev='';
                }
                ?>
            </td>
            <!-- <th align="center" width="100">Tipo de Transacc.</th> -->
            <td align="center"><?php echo mssql_result($resumen1, $i, 'tiporeg'); ?></td>
            <!-- <th align="center" width="100">Numero Factura Afectada</th> -->

            <?php
                            //$numerodd=mssql_result($resumen1, $i, 'numerodoc');
            $tipodoc2=mssql_result($resumen1, $i, 'tipo');

            if ($tipodoc2 =='A') {

                $numeroafec='';
            }else{
                $numeroafec=mssql_result($resumen1, $i, 'factafectada');
            }
            ?>
            <td align="right"><?php echo $numeroafec; ?></td>
            <!-- <th align="center" width="200">Ventas Gravadas Incluyendo IVA</th> -->
            <td align="right"><?php echo number_format(mssql_result($resumen1, $i, 'totalventasconiva'), 2, ',', '.'); $totalventasconiva += mssql_result($resumen1, $i, 'totalventasconiva'); ?></td>
            <!-- <th align="center" width="200">Impuesto al PVP</th> -->
            <td align="right"><?php echo number_format(mssql_result($resumen1, $i, 'montoiva_contribuyeial'), 2, ',', '.'); $impuestoalpvp += mssql_result($resumen1, $i, 'montoiva_contribuyeial');?></td>
            <!-- <th align="center" width="200">IVA Percibido</th>   -->
            <td align="right"><?php echo number_format(mssql_result($resumen1, $i, 'montoiva_contribuyeart18'), 2, ',', '.'); $impuestopercibido += mssql_result($resumen1, $i, 'montoiva_contribuyeart18'); ?></td>
            <!-- <th align="center" width="150">Ventas No Gravadas</th>  -->
            <td align="right">
                <?php 
                $bi=mssql_result($resumen1, $i, 'montoiva_contribuyeart18');
                $tipo=mssql_result($resumen1, $i, 'tipo');
                if ($bi != 0 ) {
                    echo number_format(mssql_result($resumen1, $i, 'totalventas'), 2, ',', '.'); $ventasnogravadas += mssql_result($resumen1, $i, 'totalventas');                           
                }elseif($bi == 0 ) {
                    echo $base_imponible = '0,00';
                }
                ?>
            </td>
            <!-- <th align="center" width="100">Ventas Exentas</th> -->
            <td align="right"><?php echo number_format(mssql_result($resumen1, $i, 'mtoexento'), 2, ',', '.'); $ventasexentas += mssql_result($resumen1, $i, 'mtoexento'); ?></td>
            <!-- <th align="center" width="150">Base Imponible 16 %</th>     -->    
            <?php
            $bi=mssql_result($resumen1, $i, 'montoiva_contribuyeart18');
            $tipo=mssql_result($resumen1, $i, 'tipo');
            if ($bi == 0 ) {
                            $base_imponible = mssql_result($resumen1, $i, 'totalgravable_contribuye') ;//- mssql_result($resumen1, $i, 'mtoexento');
                        }elseif($bi != 0 ) {
                            $base_imponible = '0,00';

                        //$totalventasconiva =  mssql_result($resumen1, $i, 'totalventasconiva'); 
                        }
                        ?>                      
                        <td align="right"><?php echo number_format($base_imponible, 2, ',', '.'); ?></td>
                        <!-- <th align="center" width="50">% Alic</th> -->
                        <td align="right"><?php echo number_format(mssql_result($resumen1, $i, 'Alicuota_ContribuyeIVA'), 0, ',', '.'); ?>%</td>
                        <!-- <th align="center" width="150">Impuesto IVA</th> -->
                        <td align="right"><?php echo number_format(mssql_result($resumen1, $i, 'montoiva_contribuyeiva'), 2, ',', '.'); $impuestoiva += mssql_result($resumen1, $i, 'montoiva_contribuyeiva'); ?></td>
                        <!-- <th align="center" width="100">IVA Retenido (por el comprador)</th> -->
                        <td align="right"><?php echo number_format(mssql_result($resumen1,$i, 'retencioniva'), 2, ',', '.'); $ivaretenido += mssql_result($resumen1, $i, 'retencioniva'); ?></td>       
                    </tr>
                    <?php
                }
                ?>
                <!--    <tr>
                        <td colspan="10" align="right"><strong>Totales</strong></td>
                        <td align="right"><?php echo number_format($ial, 2, ',', '.'); ?></td>
                        <td align="right"><?php echo number_format($art18, 2, ',', '.'); ?></td>
                        <td align="right"><?php echo number_format($tvii, 2, ',', '.'); ?></td>
                        <td align="right"><?php echo number_format($ve, 2, ',', '.'); ?></td>
                        <td align="right"><?php echo number_format($magbi16c, 2, ',', '.'); ?></td>
                        <td></td>
                        <td align="right"><?php echo number_format($mag16c, 2, ',', '.'); ?></td>
                        <td align="right"><?php echo number_format($ivare, 2, ',', '.'); ?></td>
                    </tr> -->
                </tbody>
            </table>
            <hr>


            <h1>RELACION DE COMPROBANTES DE MESES ANTERIORES</h1>
            <div id="Layer2" style="width:1400px; height:auto;">
                <table id="tabla1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:40%;">
                    <thead  style="background-color: #00137f;color: white;">
                        <tr id="cells">
                            <th align="center">Nro. Ope</th>
                            <th align="center" width="100">Fecha de Documento</th>
                            <th align="center" width="100">RIF</th>
                            <th align="center" width="200">Nombre o Raz&oacute;n Social</th>
                            <th align="center" width="100">Nro. de Comp. de Retenci&oacute;n</th>
                            <th align="center" width="100">Tipo Tran.</th>
                            <th align="center" width="100">Nro. Factura Afectada</th>
                            <th align="center" width="100">Fecha Factura Afectada</th>
                            <th align="center" width="100">Base de Retenci&oacute;n</th>
                            <th align="center" width="150">I.V.A.</th>
                            <th align="center" width="100">I.V.A. Retenido</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: aliceblue">
                        <?php
                        $num = mssql_num_rows($resumen2);
                        $ivare2 = $ivape2 = 0;
                        for($i=0;$i<$num;$i++){
                            $k = $i+1;
                            ?>
                            <tr >
                                <td><?php echo $k; ?></td>
                                <td align="center"><?php echo date('d/m/Y', strtotime(mssql_result($resumen2, $i, 'fechaemision'))); ?></td>
                                <td><?php echo mssql_result($resumen2, $i, 'rifcliente'); ?></td>
                                <td><?php echo utf8_encode(mssql_result($resumen2, $i, 'nombre')); ?></td>
                                <td align="center"><?php echo mssql_result($resumen2, $i, 'tipodoc'); ?></td>
                                <td><?php echo mssql_result($resumen2, $i, 'numerodoc'); ?></td>
                                <td align="center"><?php echo mssql_result($resumen2, $i, 'factafectada'); ?></td>
                                <?php if (mssql_result($resumen2, $i, 'fecharetencion') == null){ ?>
                                    <td align="center"></td>
                                <?php }else{ ?>
                                    <td align="center"><?php echo date('d/m/Y', strtotime(mssql_result($resumen2, $i, 'fecharetencion'))); ?></td>
                                <?php } ?>
                                <td align="right"><?php echo number_format(mssql_result($resumen2, $i, 'totalgravable_contribuye'), 2, ',', '.'); ?></td>
                                <td align="right"><?php echo number_format(mssql_result($resumen2, $i, 'totalivacontribuye'), 2, ',', '.'); ?></td>
                                <td align="right"><?php echo number_format(mssql_result($resumen2, $i, 'retencioniva'), 2, ',', '.'); $ivare2 += mssql_result($resumen2, $i, 'retencioniva'); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="10" align="right"><strong>Totales</strong></td>
                            <td align="right"><?php echo number_format($ivare2, 2, ',', '.'); ?></td>
                        </tr></tbody>
                    </table>
                </div>
                <br><br><br>




                <!-- seguir  -->

                <table id="tabla1" class="table table-sm text-center table-condensed table-bordered table-striped" style="width:40%;">
                    <thead  style="background-color: #00137f;color: white;">
                        <tr >
                            <td width="500"><strong>RESUMEN DEL LIBRO DE VENTAS</strong></td>
                            <td width="100"><strong>BASE IMPONIBLE</strong></td>
                            <td width="100"><strong>DEBITO FISCAL</strong></td>
                        </tr>
                    </thead>
                    <tbody style="background-color: aliceblue">
                        <tr>
                            <td>Total Ventas No Gravadas</td>
                            <td align="right"><?php echo number_format($ventasnogravadas, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Total Impuesto al PVP</td>
                            <td align="right"><?php echo number_format($impuestoalpvp, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Total IVA Percibido</td>
                            <td align="right"><?php echo number_format($impuestopercibido, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Total Ventas Exentas de IVA</td>
                            <td align="right"><?php echo number_format($ventasexentas, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Total Ventas Gravadas por Alicuota General (16%)</td>
                            <td align="right"><?php echo number_format($totalventasconiva, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format($impuestoiva, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Total ventas Gravadas por Alicuota reducida</td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                        </tr>
                        <tr class="ui-widget-header">
                            <td width="500"><strong>Total Ventas y Débitos Fiscales para efectos de determinación</strong></td>
                            <td width="100" align="right"><strong><?php echo number_format(0, 2, ',', '.'); ?></strong></td>
                            <td width="100" align="right"><strong><?php echo number_format(0, 2, ',', '.'); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Iva Retenidos periodos anteriores</td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format($ivare2, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td>Iva Retenidos en este periodo</td>
                            <td align="right"><?php echo number_format(0, 2, ',', '.'); ?></td>
                            <td align="right"><?php echo number_format($ivaretenido, 2, ',', '.'); ?></td>
                        </tr>
                        <tr class="ui-widget-header">
                            <td width="500"><strong>Total IVA Retenido</strong></td>
                            <td width="100" align="right"><strong><?php echo number_format(0, 2, ',', '.'); ?></strong></td>
                            <td width="100" align="right"><strong><?php echo number_format(($ivare2+$ivare), 2, ',', '.'); ?></strong></td>
                        </tr>
                    </tbody>
                </table>