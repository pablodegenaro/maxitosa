<?php 
set_time_limit(0);
include_once 'kpimarcas.php';
$fechai = $_POST['fechai'].' 00:00:00';
/*$fechai = normalize_date2($fechai).' 00:00:00';*/

$fechaf = $_POST['fechaf'].' 23:59:59';
/*$fechaf = normalize_date2($fechaf).' 23:59:59';*/
$fechaii = normalize_date($_POST['fechai']);
$fechaff = normalize_date($_POST['fechaf']);

$dias_trans = $_POST['d_trans'];
$dias_habiles = $_POST['d_habiles'];
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
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  ?>
  <div class="content-header">
    <div class="container">
    </div>
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">KPI (Key Performance Indicator)</h1>
            </div>
            <div class="col-sm-6 text-right">
                <script type="text/javascript">
                    function regresa(){
                        window.location.href = "principal1.php?page=kpi&mod=1";
                    }
                </script>
                <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-3 mt-4 form-check-inline">
                <dt class="col-sm-3 text-gray">Desde:</dt>
                <input type="text" class="form-control-sm col-8 text-center" id="fechai" value="<?php echo $_POST['fechai']; ?>" readonly>
            </div><!-- /.col -->
            <div class="col-sm-3 mt-4 form-check-inline">
                <dt class="col-sm-4 text-gray">Hasta:</dt>
                <input type="text" class="form-control-sm col-sm-8 text-center" id="fechaf" value="<?php echo $_POST['fechaf']; ?>" readonly>
            </div><!-- /.col -->
            <div class="col-sm-2 mt-4 form-check-inline">
                <dt class="col-sm-7 text-gray">Días Habiles:</dt>
                <input type="text" class="form-control-sm col-sm-5  text-center" id="d_habiles" value="<?php echo $_POST['d_habiles']; ?>" readonly>
            </div><!-- /.col -->
            <div class="col-sm-2 mt-4 form-check-inline">
                <dt class="col-sm-7 text-gray">Días Transc:</dt>
                <input type="text" class="form-control-sm col-sm-5  text-center" id="d_trans" value="<?php echo $_POST['d_trans']; ?>" readonly>
            </div><!-- /.col -->
        </div>
    </div>
</div>
<div class="content">
    <table id="tabla" class="table table-sm text-center table-condensed table-bordered table-striped table-responsive" style="width:100%;">
        <?php
        $colSpan_rutas = 1;
        $colSpan_acti = 4;
        $colSpan_efec = 7;
        $colSpan_vent = 13;

        $query_kpi_marcas = mssql_query("SELECT id, descripcion, fechae FROM Kpi_marcas ORDER BY id DESC");
        $colSpan_total = $colSpan_rutas + $colSpan_acti + $colSpan_efec + $colSpan_vent + mssql_num_rows($query_kpi_marcas);
        ?>
        <thead>
            <tr style="background-color: #00137f ;color: white;">
                <th class="small align-middle" colspan="<?= $colSpan_rutas; ?>"  id="cabecera_rutas">Rutas</th>
                <th class="small align-middle" colspan="<?= $colSpan_acti + mssql_num_rows($query_kpi_marcas)?>">Activaci&oacute;n</th>
                <th class="small align-middle" colspan="<?= $colSpan_efec; ?>">Efectividad</th>
                <th class="small align-middle" colspan="<?= $colSpan_vent; ?>">Ventas</th>
            </tr>
            <tr id="cells" style="background-color: #0c70c8 ;color: white; font-weight: bold">
                <th class="small align-middle">Rutas</th>
                <th class="small align-middle">Maestro de Clientes</th>
                <th class="small align-middle">Clientes Activados</th>
                <?php
                for($i=0;$i<mssql_num_rows($query_kpi_marcas);$i++) {?>
                    <th class="small align-middle"><?php echo utf8_decode(mssql_result($query_kpi_marcas,$i,"descripcion")); ?></th><?php
                }
                ?>
                <th class="small align-middle">% Activación Clientes Alcanzado</th>
                <th class="small align-middle">Clientes Pendientes</th>
                <th class="small align-middle">Frecuencia de Visita</th>
                <th class="small align-middle">Objetivo Facturas más Notas Mensual</th>
                <th class="small align-middle">Total Facturas Realizadas</th>
                <th class="small align-middle">Total Notas Realizadas</th>
                <th class="small align-middle">Devoluciones Realizadas (nt + fac)</th>
                <th class="small align-middle">Total Devoluciones Realizadas ($)</th>
                <th class="small align-middle">% Efectividad Alcanzada a la Fecha</th>
                <th class="small align-middle">Objetivo (Bulto)</th>
                <th class="small align-middle">Logro (Bulto)</th>
                <th class="small align-middle">%Alcanzado (Bulto)</th>
                <th class="small align-middle">Objetivo (Kg)</th>
                <th class="small align-middle">Logro (Kg)</th>
                <th class="small align-middle">%Alcanzado (Kg)</th>
                <th class="small align-middle">Objetivo Total Ventas ($)</th>
                <th class="small align-middle">Total Ventas ($)</th>
                <th class="small align-middle">%Alcanzado ($)</th>
                <th class="small align-middle">Cobranza Rebajadas (Bs)</th>
            </tr>
        </thead>
        <tbody style="background-color: aliceblue">
            <?php
            $fechai2 = str_replace('/','-',$_POST['fechai']); $fechai2 = date('Y-m-d', strtotime($fechai2));
            $fechaf2 = str_replace('/','-',$_POST['fechaf']); $fechaf2 = date('Y-m-d', strtotime($fechaf2));

            $coordinadores = mssql_query("SELECT DISTINCT supervisor
                FROM savend_99 d INNER JOIN savend S ON S.codvend = d.CodVend
                WHERE (d.supervisor = '' OR d.supervisor IS NOT NULL) AND d.supervisor != ' ' AND S.Activo = 1
                ORDER BY supervisor ASC");
            if (mssql_num_rows($coordinadores) > 0)
            {
                $marcasKpi = array();
                $query_kpi_marcas = mssql_query("SELECT id, descripcion, fechae FROM Kpi_marcas ORDER BY id DESC");
                for($i=0;$i<mssql_num_rows($query_kpi_marcas);$i++) {
                    $marcasKpi[] = mssql_result($query_kpi_marcas,$i,"descripcion");
                }

                $ttl_marcas = new Kpimarca($marcasKpi);

                # inicializacion variables ttl
                $ttl_clientes           = 0;
                $ttl_clientes_activos   = 0;
                $ttl_clientes_noactivos = 0;
                $ttl_activacionBultos   = array();
                $ttl_porc_activacion    = 0;
                $ttl_obj_documentos_mensual  = 0;
                $ttl_facturas_realizadas     = 0;
                $ttl_notas_realizadas        = 0;
                $ttl_devoluciones_realizadas = 0;
                $ttl_montoendivisa_devoluciones = 0;
                $ttl_efec_alcanzada_fecha       = 0;
                $ttl_objetivo_bulto             = 0;
                $ttl_logro_bulto                = 0;
                $ttl_porc_alcanzado_bulto       = 0;
                $ttl_objetivo_kg                = 0;
                $ttl_logro_kg                   = 0;
                $ttl_porc_alcanzado_kg          = 0;
                $ttl_objetivo_ventas_divisas    = 0;
                $ttl_logro_ventas_divisas       = 0;
                $ttl_porc_ventas_divisas        = 0;
                $ttl_cobranzasRebajadas = 0;

                //DECLARAMOS UN ARRAY PARA EL RESULTADO DEL MODELO.
                $data = Array();
                for($i=0;$i<mssql_num_rows($coordinadores);$i++)
                {
                    //DECLARAMOS UN SUB ARRAY Y LO LLENAMOS POR CADA REGISTRO EXISTENTE.
                    $sub_array = array();
                    $coordinador = mssql_result($coordinadores,$i,"supervisor");
                    ?>
                    <tr>
                        <td class="text-left" colspan="<?= $colSpan_total; ?>">Coordinador:   <strong><?= strtoupper($coordinador); ?></strong></td>
                    </tr>
                    <?php
                    # $sub_array['coordinador'] = mssql_result($coordinadores,$i,"coordinador");
                    $subttl_marcas = new Kpimarca($marcasKpi);

                    # inicializacion variables subttl
                    $subttl_clientes           = 0;
                    $subttl_clientes_activos   = 0;
                    $subttl_clientes_noactivos = 0;
                    $subttl_activacionBultos   = array();
                    $subttl_porc_activacion    = 0;
                    $subttl_obj_documentos_mensual  = 0;
                    $subttl_facturas_realizadas     = 0;
                    $subttl_notas_realizadas        = 0;
                    $subttl_devoluciones_realizadas = 0;
                    $subttl_montoendivisa_devoluciones = 0;
                    $subttl_efec_alcanzada_fecha       = 0;
                    $subttl_objetivo_bulto             = 0;
                    $subttl_logro_bulto                = 0;
                    $subttl_porc_alcanzado_bulto       = 0;
                    $subttl_objetivo_kg                = 0;
                    $subttl_logro_kg                   = 0;
                    $subttl_porc_alcanzado_kg          = 0;
                    $subttl_objetivo_ventas_divisas    = 0;
                    $subttl_logro_ventas_divisas       = 0;
                    $subttl_porc_ventas_divisas        = 0;
                    $subttl_cobranzasRebajadas = 0;

                    $vendedores = mssql_query("SELECT * FROM savend INNER JOIN savend_99 ON savend.codvend = savend_99.codvend
                        WHERE activo = '1' AND supervisor != '' AND savend_99.supervisor = '$coordinador'
                        ORDER BY savend.codvend");
                    if (mssql_num_rows($vendedores) > 0) {
                        for($v=0;$v<mssql_num_rows($vendedores);$v++)
                        {
                            $ruta = mssql_result($vendedores,$v,"ID3");
                            $clientes = mssql_query("SELECT descrip, codclie, direc1 AS direc, (SELECT dia_visita FROM SACLIE_99 WHERE SACLIE_99.CodClie=SACLIE.CodClie) as dia_visita FROM saclie WHERE codvend = '$ruta' AND activo = '1'");
                            $clientes_activos = mssql_query("SELECT distinct(SAFACT.CodClie) AS codclie, Descrip as descrip, Direc2 AS direc, (SELECT dia_visita FROM SACLIE_99 WHERE SACLIE_99.CodClie=SAFACT.CodClie) as dia_visita FROM SAFACT WHERE SAFACT.CodVend = '$ruta' AND TipoFac in ('A','C') AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_99 ON SACLIE.CodClie = SACLIE_99.CodClie
                                WHERE ACTIVO = 1 AND (SACLIE.CodVend = '$ruta' or Ruta_Alternativa = '$ruta' OR Ruta_Alternativa_2 = '$ruta')) AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai2' and '$fechaf2' AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A','C') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B','D'))as BIGINT))");
                            $clientes_noactivos = mssql_num_rows($clientes) - mssql_num_rows($clientes_activos);
                            $activacionBultos = array();
                            foreach ($marcasKpi as $key => $marca) {
                                $query = mssql_query("SELECT DISTINCT(CodClie) FROM saitemfac INNER JOIN saprod ON saitemfac.coditem = saprod.codprod INNER JOIN SAFACT
                                    ON SAITEMFAC.NumeroD = SAFACT.NumeroD WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai2' AND '$fechaf2' AND saprod.marca LIKE '$marca' AND
                                    SAITEMFAC.codvend = '$ruta' AND saitemfac.tipofac IN ('A','C') AND SAFACT.tipofac IN ('A','C') AND SAFACT.NumeroD NOT IN
                                    (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac IN ('A','C') AND x.NumeroR IS NOT NULL AND
                                        CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac IN ('B','D')) AS BIGINT))");
                                $activacionBultos[] = array(
                                    'marca' => $marca,
                                    'valor' => mssql_num_rows($query)
                                );
                            }
                            $porc_activacion = (mssql_num_rows($clientes)!=0) ? (mssql_num_rows($clientes_activos)/mssql_num_rows($clientes)) * 100 : 0;
                            $frecuenciaVisita = '';
                            $frecu_ot = (mssql_result($vendedores, $v, "Frecuencia") !== null and mssql_result($vendedores, $v, "Frecuencia") !== "")
                            ? mssql_result($vendedores, $v, "Frecuencia") : 2;
                            switch ($frecu_ot) {
                                case 1: $frecuenciaVisita = "Mensual";   break;
                                case 2: $frecuenciaVisita = "Quincenal"; break;
                                case 4: $frecuenciaVisita = "Semanal";   break;
                                default: $frecuenciaVisita = "Semanal";
                            }

                            $frecu = intval($dias_habiles) / 5;
                            switch ($frecu_ot) {
                                case 1: $frecu = $frecu * 0.25; break;
                                case 2: $frecu = $frecu * 0.5; break;
                                case 4: $frecu = $frecu * 1; break;
                            }
                            $obj_documentos_mensual = ($frecu * mssql_num_rows($clientes));
                            $query_safact = mssql_query("SELECT numerod, descrip, fechae, tipofac, COALESCE(TGravable/NULLIF(Factorp,0), 0) as montod FROM safact WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) BETWEEN '$fechai2' AND '$fechaf2' AND safact.codvend = '$ruta' AND tipofac IN ('A','C') AND NumeroD NOT IN 
                                (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac IN ('A','C') AND x.NumeroR IS NOT NULL AND
                                    CAST(X.Monto AS BIGINT) = CAST((SELECT Z.Monto FROM SAFACT AS Z WHERE Z.NumeroD = x.NumeroR AND Z.TipoFac IN ('B','D')) AS BIGINT))");
                            $facturas_realizadas = 0;
                            $notas_realizadas = 0;
                            for($s=0;$s<mssql_num_rows($query_safact);$s++) {
                                switch (mssql_result($query_safact, $s, "tipofac")) {
                                    case 'A': $facturas_realizadas+=1; break;
                                    case 'C': $notas_realizadas+=1; break;
                                }
                            }
                            $query_devoluciones = mssql_query("SELECT numerod, descrip, fechae, COALESCE(TGravable/NULLIF(Factorp,0), 0) as montod, tipofac FROM safact WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) BETWEEN '$fechai2' AND '$fechaf2' AND safact.codvend = '$ruta' AND tipofac IN ('B','D')");
                            $devolucionesFact = 0;
                            $devolucionesNota = 0;
                            $montoendivisa_devoluciones_fact = 0;
                            $montoendivisa_devoluciones_nt = 0;
                            for($s=0;$s<mssql_num_rows($query_devoluciones);$s++) {
                                switch (mssql_result($query_devoluciones, $s, "tipofac")) {
                                    case 'B':
                                    $devolucionesFact+=1;
                                    $montoendivisa_devoluciones_fact += floatval(mssql_result($query_devoluciones, $s, "montod"));
                                    break;
                                    case 'D':
                                    $devolucionesNota+=1;
                                    $montoendivisa_devoluciones_nt += floatval(mssql_result($query_devoluciones, $s, "montod"));
                                    break;
                                }
                            }
                            $devoluciones_realizadas = $devolucionesFact + $devolucionesNota;
                            $montoendivisa_devoluciones = $montoendivisa_devoluciones_fact + $montoendivisa_devoluciones_nt;

                            $tmp = ( ($dias_trans/$dias_habiles) * $obj_documentos_mensual );
                            $efec_alcanzada_fecha = ($tmp!=0) ? ( ($facturas_realizadas+$notas_realizadas) / $tmp )*100 : 0;
                            $query_objetivo = mssql_query("SELECT * FROM savend_99 WHERE CodVend = '$ruta'");
                            $objetivo_bulto       = (mssql_result($query_objetivo, 0, "obj_ventas_bul") !== "") ? mssql_result($query_objetivo, 0, "obj_ventas_bul") : 0;
                            $logro_bulto          = calcula_Requerido_Bult_Und_kg( $fechai2, $fechaf2, $ruta, 'BUL');
                            $porc_alcanzado_bulto = ($objetivo_bulto!=0) ? ($logro_bulto/$objetivo_bulto)*100 : 0;
                            $objetivo_kg          = (mssql_result($query_objetivo, 0, "obj_ventas_bul") !== "") ? mssql_result($query_objetivo, 0, "obj_ventas_kg") : 0;
                            $logro_kg             = calcula_Requerido_Bult_Und_kg( $fechai2, $fechaf2, $ruta, 'KG');
                            $porc_alcanzado_kg    = ($objetivo_kg!=0) ? ($logro_kg/$objetivo_kg)*100 : 0;
                            $objetivo_ventas_divisas = (mssql_result($query_objetivo, 0, "obj_bs") !== "") ? mssql_result($query_objetivo, 0, "obj_bs") : 0; # realmente objetivo $$

                            $query_ventas_dvisas = mssql_query("SELECT COALESCE(SUM(TGravable/NULLIF(Factorp,0)), 0) AS MontoD, tipofac FROM safact 
                              WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, safact.FechaE)) BETWEEN '$fechai2' AND '$fechaf2' AND safact.codvend = '$ruta' AND tipofac IN ('A','C')
                              GROUP BY SAFACT.TipoFac");
                            $ventas_divisas_fact = 0;
                            $ventas_divisas_nt = 0;
                            for($s=0;$s<mssql_num_rows($query_ventas_dvisas);$s++) {
                                switch (mssql_result($query_ventas_dvisas, $s, "tipofac")) {
                                    case 'A': $ventas_divisas_fact += floatval(mssql_result($query_ventas_dvisas, $s, "montod")); break;
                                    case 'C': $ventas_divisas_nt += floatval(mssql_result($query_ventas_dvisas, $s, "montod")); break;
                                }
                            }

                            $logro_ventas_divisas = floatval($ventas_divisas_fact) + floatval($ventas_divisas_nt);
                            $porc_ventas_divisas  = (($objetivo_ventas_divisas!=0) ? ($logro_ventas_divisas/$objetivo_ventas_divisas)*100 : 0);
                            $query_cobranzasRebajadas = mssql_query("SELECT V.CODVEND, V.fechae, V.numerod, V.NumeroFac, V.MONTO, V.Descrip
                                FROM (
                                    SELECT FA.codvend, FA.fechae, FA.NumeroD, FA.NumeroD AS NumeroFac, FA.MONTO AS MONTO, FA.Descrip
                                    FROM VW_ADM_FACTURAS FA
                                    WHERE (FA.Tipofac In ('A','B')) AND (FA.Contado<>0)
                                    AND (SUBSTRING(FA.CODSUCU,1,LEN('00000'+''))='00000'+'')
                                    UNION ALL
                                    SELECT CC.codvend, CC.FechaE, CC.NUMEROD, ISNULL(PG.NUMEROD,CC.NumeroD) AS NUMEROFAC,
                                    (CASE WHEN CC.TIPOCXC LIKE '3%' THEN -1
                                        WHEN CC.TIPOCXC LIKE '2%' THEN -1 ELSE 1 END)*ISNULL(PG.MONTO-PG.MTOTAX+PG.RetenIVA+CancelI,CC.MONTO-CC.MTOTAX) AS MONTO, CL.Descrip
                                    FROM SAACXC CC
                                    INNER JOIN SACLIE CL ON (CL.CodClie=CC.CodClie)
                                    LEFT JOIN SAPAGCXC PG ON (PG.NROPPAL=CC.NROUNICO)
                                    WHERE ((CC.TipoCxc LIKE '4%') OR (CC.Tipocxc LIKE '3%') OR (CC.Tipocxc LIKE '2%'))
                                    AND (CC.EsReten=0) AND (FromTran=1) AND (SUBSTRING(CC.CODSUCU,1,LEN('00000'+''))='00000'+'')
                                    ) V
                                WHERE
                                (CONVERT(DATETIME,CONVERT(DATETIME, '$fechai2',120)+' 00:00:00',120)<=V.FECHAE) AND
                                (V.FECHAE<=CONVERT(DATETIME,CONVERT(DATETIME, '$fechaf2',120)+' 23:59:59',120))
                                AND (codvend = '$ruta')");
                            $cobranzasRebajadas = 0;
                            for($s=0;$s<mssql_num_rows($query_cobranzasRebajadas);$s++) {
                                $cobranzasRebajadas += floatval(mssql_result($query_cobranzasRebajadas, $s, "monto"));
                            }

                            #llenado de los subtotals
                            $subttl_marcas->set_acumKpiMarcas($activacionBultos);
                            $subttl_clientes                                 += mssql_num_rows($clientes);
                            $subttl_clientes_activos                         += mssql_num_rows($clientes_activos);
                            $subttl_activacionBultos                         = $subttl_marcas->get_totalKpiMarcas();
                            $subttl_clientes_noactivos                       += $clientes_noactivos;
                            $subttl_porc_activacion                          = ($subttl_clientes!=0) ? ($subttl_clientes_activos/$subttl_clientes) * 100 : 0;
                            $subttl_obj_documentos_mensual                   += $obj_documentos_mensual;
                            $subttl_facturas_realizadas                      += $facturas_realizadas;
                            $subttl_notas_realizadas                         += $notas_realizadas;
                            $subttl_devoluciones_realizadas                  += $devoluciones_realizadas;
                            $subttl_montoendivisa_devoluciones               += $montoendivisa_devoluciones;
                            $tmp = ( ($dias_trans/$dias_habiles) * $subttl_obj_documentos_mensual );
                            $subttl_efec_alcanzada_fecha                     = ($tmp!=0) ? ( ($subttl_facturas_realizadas+$subttl_notas_realizadas) / $tmp )*100 : 0;
                            $subttl_objetivo_bulto                           += $objetivo_bulto;
                            $subttl_logro_bulto                              += $logro_bulto;
                            $subttl_porc_alcanzado_bulto                     = (($subttl_objetivo_bulto!=0) ? ($subttl_logro_bulto/$subttl_objetivo_bulto)*100 : 0);
                            $subttl_objetivo_kg                              += $objetivo_kg;
                            $subttl_logro_kg                                 += $logro_kg;
                            $subttl_porc_alcanzado_kg                        = (($subttl_objetivo_kg!=0) ? ($subttl_logro_kg/$subttl_objetivo_kg)*100 : 0);
                            $subttl_objetivo_ventas_divisas                  += $objetivo_ventas_divisas;
                            $subttl_logro_ventas_divisas                     += $logro_ventas_divisas;
                            $subttl_porc_ventas_divisas                      = (($subttl_objetivo_ventas_divisas!=0) ? ($subttl_logro_ventas_divisas/$subttl_objetivo_ventas_divisas)*100 : 0);
                            $subttl_cobranzasRebajadas                       += $cobranzasRebajadas;

                            ?>
                            <tr>
                                <td align="center" class="small align-middle"><?= $ruta; ?></td>
                                <td align="center" class="small align-middle"><?= mssql_num_rows($clientes); ?></td>
                                <td align="center" class="small align-middle"><?= mssql_num_rows($clientes_activos); ?></td>
                                <?php
                                foreach ($activacionBultos as $activacion) { ?>
                                    <td align="center" class="small align-middle"><?= $activacion['valor']; ?></td><?php
                                } ?>
                                <td align="center" class="small align-middle" <?php echo color(rdecimal($porc_activacion)); ?>>
                                    <?= number_format($porc_activacion, 2, ',', '.'); ?> %
                                </td>
                                <td align="center" class="small align-middle"><?= $clientes_noactivos; ?></td>
                                <td align="center" class="small align-middle"><?= $frecuenciaVisita; ?></td>
                                <td align="center" class="small align-middle"><?= number_format($obj_documentos_mensual, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle"><?= $facturas_realizadas; ?></td>
                                <td align="center" class="small align-middle"><?= $notas_realizadas; ?></td>
                                <td align="center" class="small align-middle"><?= $devoluciones_realizadas; ?></td>
                                <td align="center" class="small align-middle"><?= number_format($montoendivisa_devoluciones, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle" <?php echo color(rdecimal($efec_alcanzada_fecha)); ?>>
                                    <?= number_format($efec_alcanzada_fecha, 2, ',', '.'); ?> %
                                </td>
                                <td align="center" class="small align-middle"><?= number_format($objetivo_bulto, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle"><?= number_format($logro_bulto, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle" <?php echo color(rdecimal($porc_alcanzado_bulto)); ?>>
                                    <?= number_format($porc_alcanzado_bulto, 2, ',', '.'); ?> %
                                </td>
                                <td align="center" class="small align-middle"><?= number_format($objetivo_kg, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle"><?= number_format($logro_kg, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle" <?php echo color(rdecimal($porc_alcanzado_kg)); ?>>
                                    <?= number_format($porc_alcanzado_kg, 2, ',', '.'); ?> %
                                </td>
                                <td align="center" class="small align-middle"><?= number_format($objetivo_ventas_divisas, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle"><?= number_format($logro_ventas_divisas, 2, ',', '.'); ?></td>
                                <td align="center" class="small align-middle" <?php echo color(rdecimal($porc_ventas_divisas)); ?>>
                                    <?= number_format($porc_ventas_divisas, 2, ',', '.'); ?> %
                                </td>
                                <td align="center" class="small align-middle"><?= number_format($cobranzasRebajadas, 2, ',', '.'); ?></td>
                            </tr>
                            <?php
                        }

                        #llenado del total general
                        $ttl_marcas->set_acumKpiMarcas($subttl_marcas->get_totalKpiMarcas());
                        $ttl_clientes                                 += $subttl_clientes;
                        $ttl_clientes_activos                         += $subttl_clientes_activos;
                        $ttl_activacionBultos                         = $ttl_marcas->get_totalKpiMarcas();
                        $ttl_clientes_noactivos                       += $subttl_clientes_noactivos;
                        $ttl_porc_activacion                          = ($ttl_clientes!=0) ? ($ttl_clientes_activos/$ttl_clientes) * 100 : 0;
                        $ttl_obj_documentos_mensual                   += $subttl_obj_documentos_mensual;
                        $ttl_facturas_realizadas                      += $subttl_facturas_realizadas;
                        $ttl_notas_realizadas                         += $subttl_notas_realizadas;
                        $ttl_devoluciones_realizadas                  += $subttl_devoluciones_realizadas;
                        $ttl_montoendivisa_devoluciones               += $subttl_montoendivisa_devoluciones;
                        $tmp = ( ($dias_trans/$dias_habiles) * $ttl_obj_documentos_mensual );
                        $ttl_efec_alcanzada_fecha                     = ($tmp!=0) ? ( ($ttl_facturas_realizadas+$ttl_notas_realizadas) / $tmp )*100 : 0;
                        $ttl_objetivo_bulto                           += $subttl_objetivo_bulto;
                        $ttl_logro_bulto                              += $subttl_logro_bulto;
                        $ttl_porc_alcanzado_bulto                     = (($ttl_objetivo_bulto!=0) ? ($ttl_logro_bulto/$ttl_objetivo_bulto)*100 : 0);
                        $ttl_objetivo_kg                              += $subttl_objetivo_kg;
                        $ttl_logro_kg                                 += $subttl_logro_kg;
                        $ttl_porc_alcanzado_kg                        = (($ttl_objetivo_kg!=0) ? ($ttl_logro_kg/$ttl_objetivo_kg)*100 : 0);
                        $ttl_objetivo_ventas_divisas                  += $subttl_objetivo_ventas_divisas;
                        $ttl_logro_ventas_divisas                     += $subttl_logro_ventas_divisas;
                        $ttl_porc_ventas_divisas                      = (($ttl_objetivo_ventas_divisas!=0) ? ($ttl_logro_ventas_divisas/$ttl_objetivo_ventas_divisas)*100 : 0);
                        $ttl_cobranzasRebajadas                       += $subttl_cobranzasRebajadas;

                        ?>
                        <tr>
                            <td align="center" class="small align-middle" style="font-weight: bold">SUBTOTAL</td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= $subttl_clientes; ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= $subttl_clientes_activos; ?></td>
                            <?php
                            foreach ($subttl_activacionBultos as $activacion) { ?>
                                <td align="center" class="small align-middle" style="font-weight: bold"><?= $activacion['valor']; ?></td><?php
                            } ?>
                            <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($subttl_porc_activacion)); ?>>
                                <?= number_format($subttl_porc_activacion, 2, ',', '.'); ?> %
                            </td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= $subttl_clientes_noactivos; ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= ""; ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_obj_documentos_mensual, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= $subttl_facturas_realizadas; ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= $subttl_notas_realizadas; ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= $subttl_devoluciones_realizadas; ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_montoendivisa_devoluciones, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($subttl_efec_alcanzada_fecha)); ?>>
                                <?= number_format($subttl_efec_alcanzada_fecha, 2, ',', '.'); ?> %
                            </td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_objetivo_bulto, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_logro_bulto, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($subttl_porc_alcanzado_bulto)); ?>>
                                <?= number_format($subttl_porc_alcanzado_bulto, 2, ',', '.'); ?> %
                            </td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_objetivo_kg, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_logro_kg, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($subttl_porc_alcanzado_kg)); ?>>
                                <?= number_format($subttl_porc_alcanzado_kg, 2, ',', '.'); ?> %
                            </td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_objetivo_ventas_divisas, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_logro_ventas_divisas, 2, ',', '.'); ?></td>
                            <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($subttl_porc_alcanzado_kg)); ?>>
                                <?= number_format($subttl_porc_alcanzado_kg, 2, ',', '.'); ?> %
                            </td>
                            <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($subttl_cobranzasRebajadas, 2, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                }

                ?>
                <tr>
                    <td class="text-left" colspan="<?= $colSpan_total; ?>"></strong></td>
                </tr>
                <tr>
                    <td align="center" class="small align-middle" style="font-weight: bold">TOTAL GENERAL</td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= $ttl_clientes; ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= $ttl_clientes_activos; ?></td>
                    <?php
                    foreach ($ttl_activacionBultos as $activacion) { ?>
                        <td align="center" class="small align-middle" style="font-weight: bold"><?= $activacion['valor']; ?></td><?php
                    } ?>
                    <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($ttl_porc_activacion)); ?>>
                        <?= number_format($ttl_porc_activacion, 2, ',', '.'); ?> % 
                    </td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= $ttl_clientes_noactivos; ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= ""; ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_obj_documentos_mensual, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= $ttl_facturas_realizadas; ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= $ttl_notas_realizadas; ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= $ttl_devoluciones_realizadas; ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_montoendivisa_devoluciones, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($ttl_efec_alcanzada_fecha)); ?>>
                        <?= number_format($ttl_efec_alcanzada_fecha, 2, ',', '.'); ?> % 
                    </td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_objetivo_bulto, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_logro_bulto, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($ttl_porc_alcanzado_bulto)); ?>>
                        <?= number_format($ttl_porc_alcanzado_bulto, 2, ',', '.'); ?> % 
                    </td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_objetivo_kg, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_logro_kg, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($ttl_porc_alcanzado_kg)); ?>>
                        <?= number_format($ttl_porc_alcanzado_kg, 2, ',', '.'); ?> % 
                    </td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_objetivo_ventas_divisas, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_logro_ventas_divisas, 2, ',', '.'); ?></td>
                    <td align="center" class="small align-middle" style="font-weight: bold" <?php echo color(rdecimal($ttl_porc_alcanzado_kg)); ?>>
                        <?= number_format($ttl_porc_alcanzado_kg, 2, ',', '.'); ?>
                    </td>
                    <td align="center" class="small align-middle" style="font-weight: bold"><?= number_format($ttl_cobranzasRebajadas, 2, ',', '.'); ?></td>
                </tr>
                <?php
            } ?>
        </tbody>
    </table>

    <div class="row text-center">
        <div class="col-sm-1">
            <div class="bg-danger color-palette"><span>ROJO: 0 - 50% </span></div>
        </div>
        <div class="col-sm-1">
            <div class="bg-warning color-palette"><span>AMARILLO: 51 - 80%</span></div>
        </div>
        <div class="col-sm-1">
            <div class="bg-success color-palette"><span>VERDE: 81 - 100% </span></div>
        </div>
    </div>
</div>
 <!--    <div align="center">
      <a href="kpi_excel.php?&fechai=<?php echo $fechai2; ?>&fechaf=<?php echo $fechaf2; ?>&habiles=<?php echo $dias_habiles; ?>&trans=<?php echo $dias_trans; ?>&skus=<?php echo $sku; ?>"><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>
  </div> -->
  <br>

  <!-- /.content -->
</div>

</div>
<?php
} else {
  header('Location: index.php');
}
?>