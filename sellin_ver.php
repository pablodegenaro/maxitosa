<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $fechai = $_POST['fechai'];
  $fechaf = $_POST['fechaf'];
  $marca = $_POST['marca'];
  $sucursal = $_POST['sucursal'];
  if ($marca == "-"){
    $consulta = mssql_query("SELECT distinct(SAITEMCOM.CodItem) as coditem,

      SUM(CASE WHEN TipoCom = 'H' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
      SUM(CASE WHEN TipoCom = 'H' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END) AS COMPRAS,
      SUM(CASE WHEN TipoCom = 'I' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
      SUM(CASE WHEN TipoCom = 'I' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END) AS DEVOL,

      (SUM(CASE WHEN TipoCom = 'H' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
        SUM(CASE WHEN TipoCom = 'H' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END)) -

      (SUM(CASE WHEN TipoCom = 'I' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
        SUM(CASE WHEN TipoCom = 'I' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END)) AS TOTAL,

      (SELECT SAPROD.Descrip FROM SAPROD WHERE SAPROD.CodProd = SAITEMCOM.CodItem) AS PRODUCTO,
      (SELECT SAPROD.Marca FROM SAPROD WHERE SAPROD.CodProd = SAITEMCOM.CodItem) AS MARCA


      FROM SAITEMCOM INNER JOIN SAPROD ON SAITEMCOM.CodItem = SAPROD.CodProd where  SAITEMCOM.CodSucu = '$sucursal' AND
      DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMCOM.FechaE)) between '$fechai' and '$fechaf' and (TipoCom = 'H' OR TipoCom = 'I')  GROUP BY (CodItem)");

  }else{
    $consulta = mssql_query("SELECT distinct(SAITEMCOM.CodItem) as coditem,

      SUM(CASE WHEN TipoCom = 'H' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
      SUM(CASE WHEN TipoCom = 'H' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END) AS COMPRAS,
      SUM(CASE WHEN TipoCom = 'I' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
      SUM(CASE WHEN TipoCom = 'I' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END) AS DEVOL,

      (SUM(CASE WHEN TipoCom = 'H' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
        SUM(CASE WHEN TipoCom = 'H' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END)) -

      (SUM(CASE WHEN TipoCom = 'I' and Esunid = '0' THEN SAITEMCOM.Cantidad ELSE 0 END) + 
        SUM(CASE WHEN TipoCom = 'I' and Esunid = '1' THEN SAITEMCOM.Cantidad/SAPROD.CantEmpaq ELSE 0 END)) AS TOTAL,

      (SELECT SAPROD.Descrip FROM SAPROD WHERE SAPROD.CodProd = SAITEMCOM.CodItem) AS PRODUCTO,
      (SELECT SAPROD.Marca FROM SAPROD WHERE SAPROD.CodProd = SAITEMCOM.CodItem) AS MARCA


      FROM SAITEMCOM INNER JOIN SAPROD ON SAITEMCOM.CodItem = SAPROD.CodProd where  SAITEMCOM.CodSucu = '$sucursal' AND
      DATEADD(dd, 0, DATEDIFF(dd, 0, SAITEMCOM.FechaE)) between '$fechai' and '$fechaf' and saprod.marca = '$marca' and (TipoCom = 'H' OR TipoCom = 'I')  GROUP BY (CodItem)");
  }
  ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
      </div>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card card-saint">
            <div class="card-header">
              <script type="text/javascript">
                function regresa(){
                  window.location.href = "principal1.php?page=sellin&mod=1";
                }
              </script>
              <h3 class="card-title">Sell In Compras</h3>&nbsp;&nbsp;&nbsp;
              <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-sm table-bordered table-striped">
                <thead style="background-color: #00137f;color: white;">
                  <tr>
                    <th>Codigo Producto</th>
                    <th>Producto</th>
                    <th>Compra</th>
                    <th>Devol Compra</th>
                    <th>Total</th>
                    <th>Marca</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < mssql_num_rows($consulta); $i++) {
                    ?>
                    <tr>
                      <td><?php echo mssql_result($consulta, $i, "coditem"); ?></td>
                      <td><?php echo utf8_encode(mssql_result($consulta, $i, "PRODUCTO")); ?></td>
                      <td><?php  echo number_format(rdecimal(mssql_result($consulta,0,"COMPRAS")),2, ".", ","); ?></td>
                      <td><?php  echo number_format(rdecimal(mssql_result($consulta,0,"DEVOL")),2, ".", ","); ?></td>
                      <td><?php  echo number_format(rdecimal(mssql_result($consulta,0,"TOTAL")),2, ".", ","); ?></td>
                      <td><?php echo mssql_result($consulta, $i, "MARCA"); ?></td>
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