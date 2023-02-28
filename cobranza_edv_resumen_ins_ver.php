<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$fechai = $_POST['fechai'].' 00:00:00';
$fechaf = $_POST['fechaf'].' 23:59:59';
$convend = $_POST['edv'];
$fechaii = normalize_date($fechai);
$fechaff = normalize_date($fechaf);

$fechaa=$_POST['fechai'];
$fechab=$_POST['fechaf'];

$suma = 0;

if ($convend == '-') {
    $query = mssql_query("
      SELECT cxc.CodVend as Codigo, vnd.descrip as Vendedor, 
    --COBRANZA CASCO + NORTE (MISCELANEOS Y LICORES) -60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'LIQ Casco + Norte - 60',
    --COBRANZA CASCO + NORTE (MISCELANEOS Y LICORES) +60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'LIQ Casco + Norte + 60',
    --COBRANZA SUR (LICORES) -60 DIAS

    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte','sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) as 'MISC Casco + Norte + Sur - 60',
    --COBRANZA CASCO + NORTE (MISCELANEOS Y LICORES) +60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte','sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) as 'Misc Casco + Norte + Sur + 60',

    COALESCE(SUM(case when ((DATEDIFF(DD, fc.Fechat, cxc.Fechat))>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'Licores Sur - 60',
    --COBRANZA SUR (LICORES) +60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'Licores Sur + 60',
    --TOTAL
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0)+
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0)+
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte','Sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) +
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte','Sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) +
    COALESCE(SUM(case when ((DATEDIFF(DD, fc.Fechat, cxc.Fechat))>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0)+
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'Total General'

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
    where cxc.fechat between '$fechai' and '$fechaf'  group by cxc.CodVend , vnd.descrip ");

}else{

    $query = mssql_query("
      SELECT cxc.CodVend as Codigo, vnd.descrip as Vendedor, 
   --COBRANZA CASCO + NORTE (MISCELANEOS Y LICORES) -60 DIAS
   COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'LIQ Casco + Norte -60',
    --COBRANZA CASCO + NORTE (MISCELANEOS Y LICORES) +60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'LIQ Casco + Norte +60',
    --COBRANZA SUR (LICORES) -60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte','sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) as 'MISC Casco + Norte -60',
    --COBRANZA CASCO + NORTE (MISCELANEOS Y LICORES) +60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase IN ('Casco','Norte','sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) as 'Misc Casco + Norte +60',

    COALESCE(SUM(case when ((DATEDIFF(DD, fc.Fechat, cxc.Fechat))>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'Licores Sur -60',
    --COBRANZA SUR (LICORES) +60 DIAS
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0) as 'Licores Sur +60',
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte') and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0)+
    COALESCE(SUM(case when (DATEDIFF(DD, fc.Fechat, cxc.Fechat)>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase IN ('Casco','Norte','sur') and itf.Instancia in ('MISCELANEOS')) then pag.Monto/fc.factorP else 0 end),0) +

    COALESCE(SUM(case when ((DATEDIFF(DD, fc.Fechat, cxc.Fechat))>=0 and (DATEDIFF(DD, fc.Fechat, cxc.Fechat))<=60 and cl.Clase = 'Sur' and itf.Instancia in ('LICORES')) then pag.Monto/fc.factorP else 0 end),0)

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
    where cxc.fechat between '$fechai' and '$fechaf ' and cxc.CodVend='$convend' group by cxc.CodVend , vnd.descrip , cxc.CodVend");
};


?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Cobranza por Instancias</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="col-md-12">
            <div class="card card-saint">
                <script type="text/javascript">
                    function regresa(){
                        window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                    }
                </script>
                <div class="card-header">
                    <script type="text/javascript">
                      function regresa(){
                        window.location.href = "principal1.php?page=cobranza_edv_resumen_ins&mod=1";
                    }
                </script>
                <h3 class="card-title">Cobranza por Instancias</h3>
                <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
            </div>
            <div class="card-body" style="width:auto;">
                <table id="example3" class="table table-lg table-bordered table-hover">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <?php
                            for ($i = 0; $i < mssql_num_fields($query); ++$i){ ?>
                                <th  align="center"><?php echo mssql_field_name($query, $i); ?></th><?php
                            } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for($j=0;$j<mssql_num_rows($query);$j++) {
                         ?>
                         <tr> <?php
                         for ($i=0; $i<mssql_num_fields($query); $i++) {
                            if (is_numeric(mssql_result($query,$j,mssql_field_name($query, $i))) and strstr(mssql_result($query,$j,mssql_field_name($query, $i)),'.')) {?>
                                <td align="right">
                                   <a target="_blank" href="principal1.php?page=cobranza_edv_resumen_ins_ver_detalle&mod=1&rango=<?php echo $i; ?>&edv=<?php echo mssql_result($query,$j,mssql_field_name($query, 0)); ?>&fechai=<?php echo $fechaa; ?>&fechaf=<?php echo $fechab; ?>"><?php echo rdecimal2(mssql_result($query,$j,mssql_field_name($query, $i))); ?></a>
                               </td>
                               <?php
                           } else {?>
                            <td  align="center"><?php echo utf8_encode(mssql_result($query,$j,mssql_field_name($query, $i)));?></td><?php
                        }
                    } ?>
                    </tr> <?php
                }
                ?>
            </tbody>
        </table>
        <br>
        <br>
    </div>
</div>
</div>
</section>      
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>