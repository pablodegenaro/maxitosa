<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');

if ($_SESSION['login']) {
  $instancia = $_POST['instancia'];
  $almacen = $_POST['almacen'];
  $existencia = $_POST['existencia'];

  if ($existencia == 1) {
   switch (true) {
    # =============================================================
    # === UN PRODUCTO, UN PROVEEDOR, UNA MARCA   ==== 
    # =============================================================
    case ($instancia != "-" &  $almacen != "-" ):

    $query = mssql_query("SELECT P.CodProd
     , P.Descrip
     ,f.proveedor     
     , Coalesce((Case When P.DEsLote = 0 Then E.Existen Else L.Cantidad End), 0)  Existen
     , Coalesce((Case When P.DEsLote = 0 Then E.ExUnidad Else L.CantidadU End), 0)  ExUnidad
     , D.Descrip  DescripD
     , P.Marca
     , c.Descrip as insta
     From dbo.SAPROD P WITH (NOLOCK)
     left join SAINSTA inst on p.CodInst=inst.CodInst
     Inner Join VW_ADM_INSTANCIAS C 
     on P.CodInst=C.CodInst 
     Left  Join dbo.SAEXIS E 
     On (P.CodProd = E.CodProd)
     Left  Join SADEPO D 
     On (E.CodUbic = D.CodUbic)
     Left  Join dbo.SALOTE L 
     On (P.CodProd = L.CodProd)
     And (L.CodUbic = D.CodUbic)
     left join SAPROD_99 f
     on (p.CodProd=f.CodProd)
     Where (E.Existen+E.ExUnidad) > 0
     and  e.codubic = '$almacen'  and  c.CodInst ='$instancia'

     ");

    break;

    # ====================================================================
    # === TODAS LAS MARCAS, TODOS LOS PROVEEDORES, TODOS LOS SKU ==== 
    # ====================================================================
    case ($instancia == "-" &  $almacen != "-" ):

    $query = mssql_query("SELECT P.CodProd
     , P.Descrip
     ,f.proveedor     
     , Coalesce((Case When P.DEsLote = 0 Then E.Existen Else L.Cantidad End), 0)  Existen
     , Coalesce((Case When P.DEsLote = 0 Then E.ExUnidad Else L.CantidadU End), 0)  ExUnidad
     , D.Descrip  DescripD
     , P.Marca
     , c.Descrip as insta
     From dbo.SAPROD P WITH (NOLOCK)
     left join SAINSTA inst 
     on p.CodInst=inst.CodInst
     Inner Join VW_ADM_INSTANCIAS C 
     on P.CodInst=C.CodInst 
     Left  Join dbo.SAEXIS E 
     On (P.CodProd = E.CodProd)
     Left  Join SADEPO D 
     On (E.CodUbic = D.CodUbic)
     Left  Join dbo.SALOTE L 
     On (P.CodProd = L.CodProd)
     And (L.CodUbic = D.CodUbic)
     left join SAPROD_99 f
     on (p.CodProd=f.CodProd)
     Where (E.Existen+E.ExUnidad) > 0
     and  e.codubic = '$almacen' 

     ");

    break;
    default:
    break;
  }
}else {

  switch (true) {
    # =============================================================
    # === UN PRODUCTO, UN PROVEEDOR, UNA MARCA   ==== 
    # =============================================================
    case ($instancia == "-" &  $almacen != "-"):

    $query = mssql_query("SELECT P.CodProd
     , P.Descrip
     ,f.proveedor     
     , Coalesce((Case When P.DEsLote = 0 Then E.Existen Else L.Cantidad End), 0)  Existen
     , Coalesce((Case When P.DEsLote = 0 Then E.ExUnidad Else L.CantidadU End), 0)  ExUnidad
     , D.Descrip  DescripD
     , P.Marca
     , c.Descrip as insta
     From dbo.SAPROD P WITH (NOLOCK)
     left join SAINSTA inst on p.CodInst=inst.CodInst
     Inner Join VW_ADM_INSTANCIAS C 
     on P.CodInst=C.CodInst 
     Left  Join dbo.SAEXIS E 
     On (P.CodProd = E.CodProd)
     Left  Join SADEPO D 
     On (E.CodUbic = D.CodUbic)
     Left  Join dbo.SALOTE L 
     On (P.CodProd = L.CodProd)
     And (L.CodUbic = D.CodUbic)
     left join SAPROD_99 f
     on (p.CodProd=f.CodProd)
     Where e.codubic = '$almacen' 

     ");

    break;

    # ====================================================================
    # === TODAS LAS MARCAS, TODOS LOS PROVEEDORES, TODOS LOS SKU ==== 
    # ====================================================================
    case ($instancia != "-" &  $almacen != "-"):

    $query = mssql_query("SELECT P.CodProd
     , P.Descrip
     ,f.proveedor     
     , Coalesce((Case When P.DEsLote = 0 Then E.Existen Else L.Cantidad End), 0)  Existen
     , Coalesce((Case When P.DEsLote = 0 Then E.ExUnidad Else L.CantidadU End), 0)  ExUnidad
     , D.Descrip  DescripD
     , P.Marca
     , c.Descrip as insta
     From dbo.SAPROD P WITH (NOLOCK)
     left join SAINSTA inst 
     on p.CodInst=inst.CodInst
     Inner Join VW_ADM_INSTANCIAS C 
     on P.CodInst=C.CodInst 
     Left  Join dbo.SAEXIS E 
     On (P.CodProd = E.CodProd)
     Left  Join SADEPO D 
     On (E.CodUbic = D.CodUbic)
     Left  Join dbo.SALOTE L 
     On (P.CodProd = L.CodProd)
     And (L.CodUbic = D.CodUbic)
     left join SAPROD_99 f
     on (p.CodProd=f.CodProd) 
     Where e.codubic = '$almacen' and  c.CodInst ='$instancia'

     ");

    break;
    default:
    break;
  }
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
                window.location.href = "principal1.php?page=inventario_fisico_ins_hijo&mod=1";
              }
            </script>
            <h3 class="card-title">Inventario Fisico por Instancia Hijo</h3>&nbsp;&nbsp;&nbsp;
            <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
          </div>
          
          <div class="card-body">    

            <table id="example1" class="table table-sm table-bordered table-striped">
              <thead style="background-color: #00137f;color: white;">
                <tr class="ui-widget-header">
                  <td align="center">Codigo</td>
                  <td align="center">Descripción</td>
                  <td align="center">Proveedor</td>
                  <td align="center">Marca</td>
                  <td align="center">Instancia</td>
                  <td align="center">Deposito</td>
                  <td align="center">Cajas</td>
                  <td align="center">Unidades</td>
                  <td align="center">Existencia Fisica Real</td>
                </tr>
              </thead>
              <?php for ($j = 0; $j < mssql_num_rows($query); $j++) {
                ?>
                <tr <?php if ($j % 2 != 0) {?> bgcolor="#CCCCCC" <?php }?> >
                  <td><?php echo mssql_result($query, $j, 'Codprod'); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'Descrip')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'proveedor')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'Marca')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'Insta')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'DescripD')); ?></td>                        
                  <td><?php echo rdecimal2((mssql_result($query, $j, 'existen'))); ?></td>
                  <td><?php echo rdecimal2((mssql_result($query, $j, 'exunidad'))); ?></td>
                  <td align="center">______________</td>
                </tr>
              <?php } ?>
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