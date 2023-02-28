<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $correl = $_GET['correl2'];
  $usuario = $_GET['usuario'];
  $consulta_correl = mssql_query("SELECT * from appfacturasft where correl = '$correl'");
  $fechad = mssql_result($consulta_correl,0,"fechad");
  $destino = mssql_result($consulta_correl,0,"nota");
  $chofer = mssql_result($consulta_correl,0,"cedula_chofer"); 
  $placa = mssql_result($consulta_correl,0,"placa");
  $fechai = normalize_date($fechad);
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
<script type="text/javascript">
  function elimina(id){
    if(confirm("Esta Seguro que desea eliminar el Documento FT del Despacho?")){
      location.href="principal1.php?page=despacho_eliminaft&mod=1&factura="+id;
    }
  }
</script>
<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Despacho #<?php echo $correl; ?></h3>
          &nbsp;&nbsp;&nbsp;
          <a href="principal1.php?page=despacho_relacionft&mod=1"> Relacion de Despachos</a>
          &nbsp;&nbsp;&nbsp;
          <a href="principal1.php?page=vehiculos&mod=1"></a>
          &nbsp;&nbsp;&nbsp;
          <a href="principal1.php?page=choferes&mod=1"></a>
          <br>
          <p><strong>Fecha Des</strong>:  <?php echo $fechai; ?>&nbsp;&nbsp;
            <strong>Destino</strong>: <?php echo $destino; ?>&nbsp;&nbsp;
            <strong>Chofer</strong>: &nbsp;&nbsp;<?php echo $chofer; ?>&nbsp;&nbsp;
            <strong>Vehiculo</strong>: &nbsp;&nbsp;<?php echo $placa; ?>
            <a href="principal1.php?page=despacho_editaft&mod=1&correl=<?php echo $correl; ?>&usuario=<?php echo $usuario; ?>">&nbsp;&nbsp;&nbsp;<img src="images/edt.png" border="0" width="20" height="18" alt="Editar Despacho" title="Editar Despacho" /></a>
          </p>
        </div>
        <div class="card-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>                  
              <tr>
                <th>Documento</th>
                <th>Fecha</th>
                <th>Razon Social</th>
                <th>Zona</th>
                <th>Edv</th>
                <th>Monto</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <?php  
              $numeros =  mssql_query("SELECT numeros, tipofac from appfacturas_detft where correl = '$correl'");
              $output = array();
              for($i=0;$i<mssql_num_rows($numeros);$i++) {
                $output[] = array(
                  $facturas= mssql_result($numeros,$i,"numeros"),
                  $tipofac= mssql_result($numeros,$i,"tipofac"),
                );
                $facturas = mssql_query("SELECT numerod, descrip, fechae, direc1, codvend, mtototal, tipofac from safact where numerod = '$facturas' and tipofac ='$tipofac' order by fechae desc");
                ?>
                <tr>
                  <td>
                    <div align="center"><?php echo mssql_result($facturas,$j,"numerod"); ?> <br>
                      <label for="tipo_fact" class="col-form-label-sm">
                        <?php 
                        if ($tipofac == 'C') {
                          echo "Nota de Entrega";
                        } else { echo "Factura FT";} ?></label>
                      </div>                      
                    </td>
                    <td>
                      <div align="center"><?php echo date("d/m/Y", strtotime(mssql_result($facturas,$j,"fechae"))); ?></div>
                    </td>
                    <td>
                      <div align="center"><?php echo utf8_encode(mssql_result($facturas,$j,"descrip")); ?></div>
                    </td>
                    <td>
                      <div align="center"><?php echo utf8_encode(mssql_result($facturas,$j,"Direc1")); ?></div>
                    </td>
                    <td>
                      <div align="center"><?php echo mssql_result($facturas,$j,"codvend"); ?></div>
                    </td>
                    <td>
                      <div align="center"><?php echo rdecimal2(mssql_result($facturas,$j,"mtototal")); ?></div>
                    </td>
                    <td><div align="center"><a href="javascript:;" onClick="elimina('<?php echo mssql_result($facturas,$j,"numerod"); ?>,<?php echo mssql_result($facturas,$j,"tipofac"); ?>,<?php echo $correl; ?>,<?php echo $usuario; ?>')"><img src="images/cancel.png" border="0" width="20" height="18" /></a></div></td> 
                  </tr>
                <?php  }?>
              </tbody>
            </table>
            <div align="center"><a href="pdf_despachoft.php?&correl=<?php echo $correl; ?>" target="_blank"><img border="0" src="images/imp.gif" width="29" height="24" /> Generar Pdf</a>
            </div>
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