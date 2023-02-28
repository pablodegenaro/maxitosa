<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
	$marca = $_POST['marca'];
  $proveedor = $_POST['proveedor'];

  switch (true) {
    # =============================================================
    # ===  UNA INSTANCIA, UN PROVEEDOR, UNA MARCA ==== a
    # =============================================================
    case ( $proveedor!="-" ):

    $query = mssql_query("SELECT a.CodProd, a.Descrip, c.proveedor, a.Existen, a.ExUnidad  from 
      saprod as a left join SAITEMFAC as b on a.CodProd=b.CodItem 
      inner join SAPROD_99 as c on a.CodProd=c.CodProd 
      where c.proveedor='$proveedor' and  a.CodProd not in (select CodItem from SAITEMFAC where TipoFac in ('A','C')) group by a.CodProd, a.Descrip, c.proveedor, a.Existen, a.ExUnidad order by c.proveedor asc");

    break;

    # ====================================================================
    # === UNA UNA INSTANCIA, UN PROVEEDOR, TODAS LAS MARCAS ==== a
    # ====================================================================
    case ($proveedor="-" ):

    $query = mssql_query("SELECT a.CodProd, a.Descrip, c.proveedor, a.Existen, a.ExUnidad  from 
      saprod as a left join SAITEMFAC as b on a.CodProd=b.CodItem 
      inner join SAPROD_99 as c on a.CodProd=c.CodProd 
      where   a.CodProd not in (select CodItem from SAITEMFAC where  TipoFac in ('A','C')) group by a.CodProd, a.Descrip, c.proveedor, a.Existen, a.ExUnidad order by c.proveedor asc");

    break;

    # =====================================================
    # === TODAS LAS SUCURSALES, INSTACIAS y VENDEDORES ==== 
    # =====================================================
    default:

  }

  $bultos = 0;
  $paquetes = 0;

  ?>


  <div class="content-wrapper">
    <!-- BOX DE LA MIGA DE PAN -->
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
              window.location.href = "principal1.php?page=sku_stop&mod=1";
            }
          </script>
          <h3 class="card-title">SKU STOP</h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">            
          <table id="example1" class="table table-sm table-bordered table-striped">
            <thead style="background-color: #00137f;color: white;">
              <tr>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Proveedor</th>
                <th>Bultos</th>
                <th>BOT / UND</th>
              </tr>
            </thead>
            <tbody>
              <?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
                ?>
                <tr>
                  <td><?php echo mssql_result($query, $i, "codprod"); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $i, "descrip")); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $i, "proveedor")); ?></td>
                  <td><?php echo rdecimal2(mssql_result($query, $i, "existen")); ?></td>
                  <td><?php echo rdecimal2(mssql_result($query, $i, "exunidad")); ?></td>
                </tr>
              <?php }?>
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