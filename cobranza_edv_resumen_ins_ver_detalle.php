<?php 
ini_set('memory_limit', '512M');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
    $rango = $_GET['rango'];
    $edv = $_GET['edv'];
    $fechai = $_GET['fechai'].' 00:00:00';
    $fechaf = $_GET['fechaf'].' 23:59:59';
    $suma = 0;
    
    switch ($rango) {
        case 2:
        // LICORES CASCO + NORTE -60
        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then 1 else 0 end = 1 ");
        break;

        case 3:
        // LICORES CASCO + NORTE +60
        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then 1 else 0 end = 1 ");
        break;

        case 4:
        
        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte', 'Sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte','Sur') and itf.Instancia in ('MISCELANEOS')) then 1 else 0 end = 1 ");
        break;

        case 5:
        
        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte','Sur' ) and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte','Sur') and itf.Instancia in ('MISCELANEOS')) then 1 else 0 end = 1 ");
        break;

        case 6:

        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat))>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES') then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat))>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES') then 1 else 0 end = 1 ");
        break;

        case 7:
        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then 1 else 0 end = 1 ");
        break;


        case 8:
        $query = mssql_query("
            SELECT cl.Descrip, cxc.codvend, cxc.fechae,cxc.fechat,cxc.numerod, pag.numerod as numfac, cxc.factorp,cxc.TipoCxc,
            case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0  and cl.Clase IN ('Casco','Norte','Sur') and itf.Instancia in ('LICORES','MISCELANEOS')) then pag.Monto/fc.factorP else 0 end as 'Monto'
            from SAACXC cxc 
            inner join SAPAGCXC pag on cxc.NroUnico = pag.NroPpal and cxc.TipoCxc = '41'
            left join SAVEND vnd on cxc.CodVend = vnd.CodVend
            left join SACLIE cl on cxc.CodClie = cl.CodClie
            left join  SAACXC fac on pag.NroRegi = fac.NroUnico
            left join SAFACT fc on fac.NumeroD = (case when fc.TipoFac = 'A' then fc.NumeroD when fc.TipoFac = 'C' then 'NE'+fc.NumeroD end) and fc.TipoFac in ('A','C')
            left join (select substring(ins.ORDERBYFIELD,0,6) Insta,inn.Descrip Instancia , NumeroD, TipoFac from SAITEMFAC it 
             inner join SAPROD prd on it.CodItem = prd.CodProd
             inner join VW_ADM_INSTANCIAS ins on prd.CodInst = ins.CODINST
             inner join SAINSTA inn on substring(ins.ORDERBYFIELD,0,6) = inn.CodInst group by substring(ins.ORDERBYFIELD,0,6), inn.Descrip ,NumeroD, TipoFac) itf on fc.NumeroD = itf.NumeroD and fc.TipoFac = itf.TipoFac
            where cxc.fechat between '$fechai' and '$fechaf' and cxc.CodVend='$edv'  and case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0  and cl.Clase IN ('Casco','Norte','Sur') and itf.Instancia in ('LICORES','MISCELANEOS')) then 1 else 0 end = 1 ");
        break;

    }

    switch ($rango) {
        case 2:
        $rango_f = "Liq Casco + Norte - 60 Dias";
        break;
        case 3:
        $rango_f = "Liq Casco + Norte + 60 Dias";
        break;
        case 4:
        $rango_f = "Misc Casco + Norte + Sur - 60";
        break;
        case 5:
        $rango_f = "Misc Casco + Norte + Sur + 60";
        break;
        case 6:
        $rango_f = "Licores Sur - 60 Dias";
        break;
        case 7:
        $rango_f = "Licores Sur + 60 Dias";
        break;
        case 8:
        $rango_f = "Total General + 60";
        break;
    }

    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
<!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion Clientes</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
</ol>
</div>
</div> -->
</div>
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-saint">
                <script type="text/javascript">
                    function regresa(){
                        window.location.href = "principal1.php?page=cobranza_edv_resumen_ins&mod=1";
                    }
                </script>
                <div class="card-header">
                    <script type="text/javascript">
                      function regresa(){
                        window.location.href = "principal1.php?page=cobranza_edv_resumen_ins&mod=1";
                    }
                </script>
                <h3 class="card-title">Cobros EDV <?php echo $edv; ?> de <?php echo $rango_f; ?></h3>
                <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
            </div>
            <div class="card-body">
                <table id="example3" class="table table-lg table-bordered table-hover">
                    <thead class="ui-widget-header" style="background-color: #00137f;color: white;">
                        <th width="120" align="center"><strong>Fecha Cobranza</strong></th>
                        <th width="80" align="center"><strong>Recibo</strong></th>
                        <th width="80" align="center"><strong>Documento</strong></th>
                        <th width="250" align="center"><strong>Razon Social</strong></th>
                        <th width="80" align="center"><strong>Factor</strong></th>
                        <th width="80" align="center"><strong>Monto $</strong></th>
                    </thead>
                    <tbody>
                        <?php for ($i=0; $i < mssql_num_rows($query); $i++) { 
                            ?>
                            <tr <?php if ($j%2 != 0){ ?> bgcolor="#CCCCCC" <?php } ?> >
                                <td align="center"><?php echo date('d-m-Y', strtotime(mssql_result($query, $i, 'fechat'))); ?></td>
                                <td><?php echo mssql_result($query, $i, 'NumeroD'); ?></td>
                                <td><?php echo mssql_result($query, $i, 'numfac'); ?></td>
                                <td><?php echo utf8_encode(mssql_result($query, $i, 'descrip')); ?></td>
                                <td align="right"><?php echo rdecimal2(mssql_result($query, $i, 'FactorP')); ?></td>
                                <td align="right"><?php echo rdecimal2(mssql_result($query, $i, 'monto')); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table> 
                <br>
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