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

  switch (true) {
    # =================================================
    # === UN TIPO DE SUCURSAL,  UN PROVEEDOR ==== 
    # =================================================
    case ($sucursal!="-" && $proveedor!="-"):
    $query = mssql_query("SELECT a.coditem, a.Descrip1, b.proveedor,

      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) 
      as botellas
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      where b.proveedor='$proveedor' and DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'  and CodSucu='$sucursal' group by a.CodItem, a.Descrip1, b.proveedor  order by botellas desc");
    break;

    # =============================================================
    # === UN TIPO DE SUCURSAL , TODOS LOS PROVEEDORES ==== 
    # =============================================================
    case ($sucursal!="-" && $proveedor=="-"):
    $query = mssql_query("SELECT a.coditem, a.Descrip1, b.proveedor,

      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) 
      as botellas
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      where  DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'  and CodSucu='$sucursal' group by a.CodItem, a.Descrip1, b.proveedor  order by botellas desc");
    break;
    

    # =============================================================
    # ===  UN PROVEEDOR, TODAS LAS SUCURSALES ==== 
    # =============================================================
    case ($sucursal=="-" && $proveedor!="-"):
    $query = mssql_query("SELECT a.coditem, a.Descrip1, b.proveedor,

      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) 
      as botellas
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      where b.proveedor='$proveedor' and DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'   group by a.CodItem, a.Descrip1, b.proveedor  order by botellas desc");
    break;

    # ===============================================================
    # === UN TIPO DE VENDEDOR, TODAS LAS SUCURSALES ==== 
    # ===============================================================
    case ($sucursal=="-"  && $proveedor="-"):
    $query = mssql_query("SELECT a.coditem, a.Descrip1, b.proveedor,

      ISNULL(SUM((A.Cantidad*CASE WHEN A.EsUnid = 1 THEN 1 ELSE CantEmpaq END)*CASE WHEN a.Tipofac in ('A','C') then 1 else -1 end),0) 
      as botellas
      from SAITEMFAC as a inner join SAPROD_99 as b on a.CodItem=b.CodProd 
      inner join SAPROD as c on a.CodItem=c.CodProd and a.TipoFac in ('A','B','C','D')
      where DATEADD(dd, 0, DATEDIFF(dd, 0, a.FechaE)) between '$fechai' and '$fechaf'  group by a.CodItem, a.Descrip1, b.proveedor  order by botellas desc");
    break;

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
              window.location.href = "principal1.php?page=rotacion_inv&mod=1";
            }
          </script>
          <h3 class="card-title">Rotacion de Inventario</h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">
          <table id="example1" class="table table-sm table-bordered table-striped">
            <thead style="background-color: #00137f;color: white;">
              <tr>
                <th>Codigo Producto</th>
                <th>Descripcion</th>
                <th>Proveedor</th>
                <th title="Cuando es negativo es por que existe una devolucion sin documento en el rango de fecha">Botellas</th>                    
              </tr>
            </thead>
            <tbody>
              <?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
                ?>
                <tr>
                  <td><?php echo mssql_result($query, $i, "coditem"); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $i, "Descrip1")); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $i, "proveedor")); ?></td>
                  <td title="Cuando es negativo es por que existe una devolucion sin documento en el rango de fecha"><?php echo rdecimal2(mssql_result($query, $i, "botellas")); ?></td>                      
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