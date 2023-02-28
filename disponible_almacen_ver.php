<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $marca = $_POST['marca'];
  $instancia = $_POST['instancia'];
  $sucursal = $_POST['sucursal'];
  $proveedor = $_POST['proveedor'];

  if ($sucursal == '00000') {
    $ubicacion= 'Puerto Ordaz' ;
  }
  elseif ($sucursal == '00001') {
    $ubicacion= 'Maturin';
  }
  elseif ($sucursal == '00002') {
    $ubicacion= 'Carupano';
  }

  if ($sucursal == '00000') {
    $almacen1= '1000' ;
    $almacen2= '1001';
  }
  elseif ($sucursal == '00001') {
    $almacen1= '2000' ;
    $almacen2= '2001';
  }
  elseif ($sucursal == '00002') {
    $almacen1= '3000' ;
    $almacen2= '3001';
  }

  switch (true) {
    # =============================================================
    # === UNA SUCURSAL, UNA INSTANCIA, UN PROVEEDOR, UNA MARCA ==== 
    # =============================================================
    case ($sucursal!="-" && $instancia!="-" && $proveedor!="-" &&  $marca!="-"):

    $query = mssql_query("SELECT exis.codprod Codprod, saprod.Descrip, Marca, inst.CodInst, sap99.proveedor, inst.Descrip as inst
      from saprod 
      inner join saexis exis on saprod.codprod = exis.codprod 
      INNER JOIN SADEPO depo ON depo.CodUbic = exis.CodUbic
      left join SAINSTA inst on saprod.CodInst=inst.CodInst
      inner join SAPROD_99 sap99 on saprod.CodProd=sap99.CodProd
      where (exis.existen > 0 or exis.exunidad > 0) and len(marca) > 0 and inst.CodInst ='$instancia' and Marca='$marca' and sap99.proveedor='$proveedor' and depo.Clase ='$sucursal'
      group by  exis.codprod, saprod.descrip, Marca, inst.CodInst, inst.Descrip,sap99.proveedor");

    break;

    # ====================================================================
    # === UNA SUCURSAL, UNA INSTANCIA, UN PROVEEDOR, TODAS LAS MARCAS ==== 
    # ====================================================================
    case ($sucursal!="-" && $instancia!="-" && $proveedor!="-" &&  $marca=="-"):

    $query = mssql_query("SELECT exis.codprod Codprod, saprod.Descrip, Marca, inst.CodInst, sap99.proveedor, inst.Descrip as inst
      from saprod 
      inner join saexis exis on saprod.codprod = exis.codprod 
      INNER JOIN SADEPO depo ON depo.CodUbic = exis.CodUbic
      left join SAINSTA inst on saprod.CodInst=inst.CodInst
      inner join SAPROD_99 sap99 on saprod.CodProd=sap99.CodProd
      where (exis.existen > 0 or exis.exunidad > 0) and len(marca) > 0 and inst.CodInst ='$instancia' and  sap99.proveedor='$proveedor'  and depo.Clase ='$sucursal'
      group by  exis.codprod, saprod.descrip, Marca, inst.CodInst, inst.Descrip,sap99.proveedor");

    break;

    # =============================================================================
    # === UNA SUCURSAL, UNA INSTANCIA, TODOS LOS PROVEEDORES, TODAS LAS MARCAS ==== 
    # =============================================================================
    case ($sucursal!="-" && $instancia!="-" && $proveedor=="-" &&  $marca=="-"):


    if ($sucursal!="-") {

     # =============================================================================
    # === UNA SUCURSAL, TODAS LAS INSTANCIA, TODOS LOS PROVEEDORES, TODAS LAS MARCAS ==== 
    # =============================================================================
     $query = mssql_query("SELECT exis.codprod Codprod, saprod.Descrip, Marca, inst.CodInst, sap99.proveedor, inst.Descrip as inst
      from saprod 
      inner join saexis exis on saprod.codprod = exis.codprod 
      INNER JOIN SADEPO depo ON depo.CodUbic = exis.CodUbic
      left join SAINSTA inst on saprod.CodInst=inst.CodInst
      inner join SAPROD_99 sap99 on saprod.CodProd=sap99.CodProd
      where (exis.existen > 0 or exis.exunidad > 0) and len(marca) > 0 and depo.Clase ='$sucursal'
      group by  exis.codprod, saprod.descrip, Marca, inst.CodInst, inst.Descrip,sap99.proveedor");
   }else{

    # =============================================================================
    # === UNA SUCURSAL, UNA INSTANCIA, TODOS LOS PROVEEDORES, TODAS LAS MARCAS ==== 
    # =============================================================================
    $query = mssql_query("SELECT exis.codprod Codprod, saprod.Descrip, Marca, inst.CodInst, sap99.proveedor, inst.Descrip as inst
      from saprod 
      inner join saexis exis on saprod.codprod = exis.codprod 
      INNER JOIN SADEPO depo ON depo.CodUbic = exis.CodUbic
      left join SAINSTA inst on saprod.CodInst=inst.CodInst
      inner join SAPROD_99 sap99 on saprod.CodProd=sap99.CodProd
      where (exis.existen > 0 or exis.exunidad > 0) and len(marca) > 0 and inst.CodInst ='$instancia'   and depo.Clase ='$sucursal'
      group by  exis.codprod, saprod.descrip, Marca, inst.CodInst, inst.Descrip,sap99.proveedor");

  }


  break;

    # =====================================================
    # === TODAS LAS SUCURSALES, INSTACIAS y VENDEDORES ==== 
    # =====================================================
  default:
  $query = mssql_query("SELECT exis.codprod Codprod, saprod.Descrip, Marca, inst.CodInst, sap99.proveedor, inst.Descrip as inst
    from saprod 
    inner join saexis exis on saprod.codprod = exis.codprod 
    INNER JOIN SADEPO depo ON depo.CodUbic = exis.CodUbic
    left join SAINSTA inst on saprod.CodInst=inst.CodInst
    inner join SAPROD_99 sap99 on saprod.CodProd=sap99.CodProd
    where (exis.existen > 0 or exis.exunidad > 0) and len(marca) > 0 
    group by  exis.codprod, saprod.descrip, Marca, inst.CodInst, inst.Descrip,sap99.proveedor");
}

$bultos = 0;
$paquetes = 0;

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
              window.location.href = "principal1.php?page=disponible_almacen&mod=1";
            }
          </script>
          <h3 class="card-title">Disponible Almacen en <?= $ubicacion; ?></h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">
          <table id="example1" class="table table-sm table-bordered table-striped">
            <thead style="background-color: #00137f;color: white;">
              <tr class="ui-widget-header">
                <td rowspan="2" align="center">Codigo</td>
                <td rowspan="2" align="center">Descripción</td>
                <td rowspan="2" align="center">Instancia</td>
                <td rowspan="2" align="center">Proveedor</td>
                <td rowspan="2" align="center">Marca</td>
                <td colspan="2" align="center">Almacen <?= $almacen1; ?></td>
                <td colspan="2" align="center">Almacen <?= $almacen2; ?></td>
              </tr>
              <tr class="ui-widget-header">
                <td  align="center">Bultos</td>
                <td  align="center">Paquetes</td>
                <td  align="center">Bultos</td>
                <td  align="center">Paquetes</td>
              </tr>

            </thead>
            <?php for ($j = 0; $j < mssql_num_rows($query); $j++) {
              $codprod = mssql_result($query, $j, 'Codprod');

              $query1 = mssql_query("SELECT  saprod.codprod, saexis.existen  Bultos,  saexis.exunidad Paquetes
                from saprod 
                inner join saexis on saprod.codprod = saexis.codprod 
                INNER JOIN SADEPO depo ON depo.CodUbic = saexis.CodUbic
                where depo.Clase='$sucursal' AND (saexis.existen > 0 or saexis.exunidad > 0) and saexis.CodUbic ='$almacen1' and saexis.codprod ='$codprod'");

              $query2 = mssql_query("SELECT  saprod.codprod, saexis.existen  Bultos,  saexis.exunidad Paquetes
                from saprod 
                inner join saexis on saprod.codprod = saexis.codprod 
                INNER JOIN SADEPO depo ON depo.CodUbic = saexis.CodUbic
                where depo.Clase='$sucursal' AND (saexis.existen > 0 or saexis.exunidad > 0) and saexis.CodUbic ='$almacen2'  and saexis.codprod ='$codprod'");

                ?>
                <tr <?php if ($j % 2 != 0) {?> bgcolor="#CCCCCC" <?php }?> >
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'Codprod')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'Descrip')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'inst')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'proveedor')); ?></td>
                  <td><?php echo utf8_encode(mssql_result($query, $j, 'Marca')); ?></td>
                  <!--************* TD 1*************** -->

                  <?php if (mssql_num_rows($query1) > 0) {
                    ?>
                    <td style="text-align: center;"><?php echo rdecimal2(mssql_result($query1, 0, 'Bultos')); ?></td><?php
                  } else {
                  ?><td style="text-align: center;"> 0 </td>
                  <?php
                }
                ?>
                <?php if (mssql_num_rows($query1) > 0) {
                  ?>
                  <td style="text-align: center;"><?php echo rdecimal2(mssql_result($query1, 0, 'Paquetes')); ?></td><?php
                } else {
                ?><td style="text-align: center;"> 0 </td>
                <?php
              }
              ?>
              <!--************* TD 2*************** -->
              <?php if (mssql_num_rows($query2) > 0) {
                ?>
                <td style="text-align: center;"><?php echo rdecimal2(mssql_result($query2, 0, 'Bultos')); ?></td><?php
              } else {
              ?><td style="text-align: center;"> 0 </td>
              <?php
            }
            ?>
            <?php if (mssql_num_rows($query2) > 0) {
              ?>
              <td style="text-align: center;"><?php echo rdecimal2(mssql_result($query2, 0, 'Paquetes')); ?></td><?php
            } else {
            ?><td style="text-align: center;"> 0 </td>
            <?php
          }
          ?>
        </tr>
      <?php }?>
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