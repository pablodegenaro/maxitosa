<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $fechai = $_POST['fechai'];
  $fechaii = normalize_date2($fechai);
  $fechaf = $_POST['fechaf'];
  $fechaff = normalize_date2($fechaf);
  $sucursal = $_POST['sucursal'];
  $clase = $_POST['clase'];

  switch (true) {
    # =================================================
    # === UN TIPO DE SUCURSAL,  UNA CLASE ==== 
    # =================================================
    case ($sucursal!="-" && $clase!="-"):
    $query = mssql_query("SELECT a.fechae, a.CodClie, a.Descrip, a.numerod, c.clase, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '1') as unidades,
      (a.MtoTotal/a.FactorP) as monto 
      from SAFACT as a inner join SAITEMFAC as b on a.numerod=b.NumeroD and a.TipoFac=b.TipoFac left join saclie as c on a.CodClie=c.CodClie  inner join saprod as d on b.CodItem=d.CodProd
      where a.TipoFac='E' and c.clase='$clase' and a.CodSucu='$sucursal' and DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' group by a.codclie, a.Descrip, a.numerod, c.clase, a.MtoTotal, a.FactorP, a.FechaE");
    break;

    # =============================================================
    # === UN TIPO DE SUCURSAL , TODOS LOS VENDEDORES ==== 
    # =============================================================
    case ($sucursal!="-" && $clase="-"):
    $query = mssql_query("SELECT a.fechae, a.CodClie, a.Descrip, a.numerod, c.clase, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '1') as unidades,
      (a.MtoTotal/a.FactorP) as monto 
      from SAFACT as a inner join SAITEMFAC as b on a.numerod=b.NumeroD and a.TipoFac=b.TipoFac left join saclie as c on a.CodClie=c.CodClie  inner join saprod as d on b.CodItem=d.CodProd
      where a.TipoFac='E' and a.CodSucu='$sucursal' and DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' group by a.codclie, a.Descrip, a.numerod, c.clase, a.MtoTotal, a.FactorP, a.FechaE");
    break;
    
    # ============================================================
    # === UN TIPO DE SUCURSAL Y VENDEDOR 
    # ============================================================
    // case ($sucursal!="-" && $inst=="-" && $convend!="-"):
    // $productos = mssql_query("SELECT distinct coditem, saprod.Descrip FROM saitemfac 
    //   INNER JOIN saprod ON saitemfac.coditem = saprod.codprod 
    //   INNER JOIN sainsta ON saprod.codinst = sainsta.codinst 
    //   INNER JOIN safact ON saitemfac.numerod = safact.numerod 
    //   WHERE DATEADD(dd, 0, DATEDIFF(dd, 0, saitemfac.FechaE)) BETWEEN '$fechai' and '$fechaf' AND (saitemfac.tipofac = 'A' OR saitemfac.tipofac = 'B')
    //   AND saitemfac.codvend = '$convend' AND SAITEMFAC.CodSucu = '$sucursal' ORDER BY saitemfac.coditem");
    // break;

    # =============================================================
    # ===  VENDEDOR, TODAS LAS SUCURSALES ==== 
    # =============================================================
    case ($sucursal="-" && $clase!="-"):
    $query = mssql_query("SELECT a.fechae, a.CodClie, a.Descrip, a.numerod, c.clase, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '1') as unidades,
      (a.MtoTotal/a.FactorP) as monto 
      from SAFACT as a inner join SAITEMFAC as b on a.numerod=b.NumeroD and a.TipoFac=b.TipoFac left join saclie as c on a.CodClie=c.CodClie  inner join saprod as d on b.CodItem=d.CodProd
      where a.TipoFac='E' and c.clase='$clase'  and DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' group by a.codclie, a.Descrip, a.numerod, c.clase, a.MtoTotal, a.FactorP, a.FechaE");
    break;

    # =====================================================
    # === TODAS LAS SUCURSALES,  VENDEDORES ==== 
    # =====================================================
    default:
    $query = mssql_query("SELECT a.fechae, a.CodClie, a.Descrip, a.numerod, c.clase, 
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '0') as cajas,
      (select ISNULL(sum(cantidad),0) from saitemfac where saitemfac.numerod = A.numerod and saitemfac.tipofac ='e' and EsUnid = '1') as unidades,
      (a.MtoTotal/a.FactorP) as monto 
      from SAFACT as a inner join SAITEMFAC as b on a.numerod=b.NumeroD and a.TipoFac=b.TipoFac left join saclie as c on a.CodClie=c.CodClie  inner join saprod as d on b.CodItem=d.CodProd
      where a.TipoFac='E' and DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf' group by a.codclie, a.Descrip, a.numerod, c.clase, a.MtoTotal, a.FactorP, a.FechaE");
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
              window.location.href = "principal1.php?page=relacion_pedidos&mod=1";
            }
          </script>
          <h3 class="card-title">Relacion de Pedidos</h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">
          <?php 
          $num = mssql_num_rows($query); 
          ?>
          <!--  <table id="example2" class="table table-bordered table-hover"> -->
            <table id="example1" class="table table-sm table-bordered table-striped">
              <thead style="background-color: #00137f;color: white;">
                <tr>
                  <th>Fecha</th>
                  <th>Codigo Cliente</th>
                  <th>Razon Social</th>
                  <th>Documento</th>
                  <th>Clase</th>
                  <th>Cajas</th>
                  <th>Unidades</th>
                  <th>Total $</th>
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
                  ?>
                  <tr>
                    <td><?php echo date("d-m-Y", strtotime(mssql_result($query,$i,"fechae"))); ?></td>
                    <td><?php echo mssql_result($query, $i, "CodClie"); ?></td>
                    <td><?php echo utf8_encode(mssql_result($query, $i, "Descrip")); ?></td>
                    <td><?php echo mssql_result($query, $i, "numerod"); ?></td>
                    <td><?php echo mssql_result($query, $i, "clase"); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "cajas")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "unidades")); ?></td>
                    <td><?php echo rdecimal2(mssql_result($query, $i, "monto")); ?>
                  </td>
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