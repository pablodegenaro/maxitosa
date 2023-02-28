<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $fechai = $_POST['fechai'];
  $fechaf = $_POST['fechaf'];
  $sucursal = $_POST['sucursal'];
  $proveedor = $_POST['proveedor'];
  $sucursal = $_POST['sucursal'];

  if ($sucursal == '00000') {
    $almacen1= '1000';
  }
  elseif ($sucursal == '00001') {
    $almacen1= '2000';
  }
  elseif ($sucursal == '00002') {
    $almacen1= '3000';

  }


  switch (true) {
    # =================================================
    # === UN TIPO DE SUCURSAL,  UN PROVEEDOR ==== 
    # =================================================
    case ($sucursal!="-" && $proveedor!="-"):

    $query = mssql_query("SELECT a.coditem, a.Descrip1, e.clasificacion_categoria, b.proveedor, clas.Descrip InsPadre, clas.Descrip InsPadre,g.ExUnidad/c.CantEmpaq + g.Existen  as cajas_existen,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0) as CAJ_FACT,    
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0)  as BOT_FACT,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0) as CAJNE,
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0)  as BOTNE,
      sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end)  as montod
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      inner join SAPROD_99 as e on a.CodItem=e.CodProd
      inner join SAFACT as d on a.NumeroD= d.NumeroD and a.TipoFac=d.TipoFac
      inner join VW_ADM_INSTANCIAS inst on c.CodInst = inst.CODINST
      inner join SAINSTA clas on CONVERT(int,substring(inst.Orderbyfield,0,6)) = clas.CodInst    
      inner join (select sum(Existen) Existen, sum(ExUnidad) ExUnidad, CodProd,CodUbic from saexis where CodUbic not like '%1' group by CodProd,CodUbic) as g on c.CodProd=g.CodProd
      where b.proveedor='$proveedor' AND   a.CodSucu='$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' and g.CodUbic='$almacen1'
      group by a.CodItem, a.Descrip1, b.proveedor, e.clasificacion_categoria, clas.Descrip, g.Existen, g.ExUnidad, c.CantEmpaq
      having 
      ISNULL(SUM((A.Cantidad/CASE WHEN A.EsUnid = 0 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) != 0 OR sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end) != 0
      order by CAJ_FACT desc");

    break;

    # =============================================================
    # === UN TIPO DE SUCURSAL , TODOS LOS PROVEEDORES ==== 
    # =============================================================
    case ($sucursal!="-" && $proveedor=="-"):

    $query = mssql_query("SELECT a.coditem, a.Descrip1, e.clasificacion_categoria, b.proveedor, clas.Descrip InsPadre, clas.Descrip InsPadre,g.ExUnidad/c.CantEmpaq + g.Existen  as cajas_existen,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0) as CAJ_FACT,    
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0)  as BOT_FACT,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0) as CAJNE,
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0)  as BOTNE,
      sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end)  as montod
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      inner join SAPROD_99 as e on a.CodItem=e.CodProd
      inner join SAFACT as d on a.NumeroD= d.NumeroD and a.TipoFac=d.TipoFac
      inner join VW_ADM_INSTANCIAS inst on c.CodInst = inst.CODINST
      inner join SAINSTA clas on CONVERT(int,substring(inst.Orderbyfield,0,6)) = clas.CodInst    
      inner join (select sum(Existen) Existen, sum(ExUnidad) ExUnidad, CodProd,CodUbic from saexis where CodUbic not like '%1' group by CodProd,CodUbic) as g on c.CodProd=g.CodProd
      where  a.CodSucu='$sucursal' AND DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' and g.CodUbic='$almacen1'
      group by a.CodItem, a.Descrip1, b.proveedor, e.clasificacion_categoria, clas.Descrip, g.Existen, g.ExUnidad, c.CantEmpaq
      having 
      ISNULL(SUM((A.Cantidad/CASE WHEN A.EsUnid = 0 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) != 0 OR sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end) != 0
      order by CAJ_FACT desc");

    break;
    
    case ($sucursal=="-" && $proveedor!="-"):

    $query = mssql_query("SELECT a.coditem, a.Descrip1, e.clasificacion_categoria, b.proveedor, clas.Descrip InsPadre, clas.Descrip InsPadre,g.ExUnidad/c.CantEmpaq + g.Existen  as cajas_existen,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0) as CAJ_FACT,    
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0)  as BOT_FACT,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0) as CAJNE,
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0)  as BOTNE,
      sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end)  as montod
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      inner join SAPROD_99 as e on a.CodItem=e.CodProd
      inner join SAFACT as d on a.NumeroD= d.NumeroD and a.TipoFac=d.TipoFac
      inner join VW_ADM_INSTANCIAS inst on c.CodInst = inst.CODINST
      inner join SAINSTA clas on CONVERT(int,substring(inst.Orderbyfield,0,6)) = clas.CodInst    
      inner join (select sum(Existen) Existen, sum(ExUnidad) ExUnidad, CodProd from saexis where CodUbic not like '%1' group by CodProd) as g on c.CodProd=g.CodProd
      where  b.proveedor='$proveedor' AND DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'
      group by a.CodItem, a.Descrip1, b.proveedor, e.clasificacion_categoria, clas.Descrip, g.Existen, g.ExUnidad, c.CantEmpaq
      having 
      ISNULL(SUM((A.Cantidad/CASE WHEN A.EsUnid = 0 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) != 0 OR sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end) != 0
      order by CAJ_FACT desc");

    break;

    case ($sucursal=="-"  && $proveedor=="-"):
    $query = mssql_query("SELECT  a.coditem, a.Descrip1, e.clasificacion_categoria, b.proveedor, clas.Descrip InsPadre, clas.Descrip InsPadre, g.ExUnidad/c.CantEmpaq + g.Existen  as cajas_existen,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0) as CAJ_FACT,    
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A') then 1 WHEN a.Tipofac in ('B') then -1 end),0)  as BOT_FACT,
      ISNULL(SUM(A.Cantidad*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0) as CAJNE,
      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('C') then 1 WHEN a.Tipofac in ('D') then -1 end),0)  as BOTNE,
      sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end)  as montod
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      inner join SAPROD_99 as e on a.CodItem=e.CodProd
      inner join SAFACT as d on a.NumeroD= d.NumeroD and a.TipoFac=d.TipoFac
      inner join VW_ADM_INSTANCIAS inst on c.CodInst = inst.CODINST
      inner join SAINSTA clas on CONVERT(int,substring(inst.Orderbyfield,0,6)) = clas.CodInst 
      inner join (select sum(Existen) Existen, sum(ExUnidad) ExUnidad, CodProd from saexis where CodUbic not like '%1' group by CodProd) as g on c.CodProd=g.CodProd
      where   DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'
      group by a.CodItem, a.Descrip1, b.proveedor, e.clasificacion_categoria, clas.Descrip, g.Existen, g.ExUnidad, c.CantEmpaq
      having 
      ISNULL(SUM((A.Cantidad/CASE WHEN A.EsUnid = 0 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) != 0 OR sum(case when a.TipoFac in ('A','c') then (a.MtoTax+a.totalitem)/a.FactorP when a.TipoFac in ('B','d') then (a.MtoTax+a.totalitem)/a.FactorP * -1 end) != 0
      order by CAJ_FACT desc");

    break;

    # =====================================================
    # === TODAS LAS SUCURSALES,  VENDEDORES ==== 
    # =====================================================
    default:
    $query = mssql_query("");
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
        <div class="card-header">
          <script type="text/javascript">
            function regresa(){
              window.location.href = "principal1.php?page=rotacion_inv_full_v2&mod=1";
            }
          </script>
          <h3 class="card-title">Rotacion de Inventario</h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">
          <!--  <table id="example2" class="table table-bordered table-hover"> -->
            <table id="example1" class="table table-sm table-bordered table-striped">
              <thead style="background-color: #00137f;color: white;">
                <tr>
                  <th>Codigo Producto</th>
                  <th>Descripcion</th>
                  <th>Categoria</th>
                  <th>Inst. Padre</th>
                  <th>Proveedor</th>
                  <th>Existencia</th>
                  <th>Caj Factura</th>
                  <th>Bot Factura</th>
                  <th>Caj NE</th>
                  <th>Bot NE</th>
                  <th>Monto $</th>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
                  ?>
                  <tr>
                    <td><?php echo mssql_result($query, $i, "coditem"); ?></td>
                    <td><?php echo utf8_encode(mssql_result($query, $i, "Descrip1")); ?></td>
                    <td><?php echo utf8_encode(mssql_result($query, $i, "clasificacion_categoria")); ?></td>
                    <td><?php echo utf8_encode(mssql_result($query, $i, "InsPadre")); ?></td>
                    <td><?php echo utf8_encode(mssql_result($query, $i, "proveedor")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "cajas_existen")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "caj_fact")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "bot_fact")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "cajne")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "botne")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "montod")); ?></td>    
                  </tr>
                <?php }?>
              </tbody>
            </table>
          </div>
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