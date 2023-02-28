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
            <li class="breadcrumb-item active">Maestro de Empleados</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content" >

    <div class="col-md-12">
      <div class="card card-saint">
        <script type="text/javascript">
          function guarda(){
            if (document.getElementById("edv").value != ""){
              /* document.forms["registro_usuarios"].submit();*/
            }else{
              alert("Debe Rellenar Todos Los Campos");
            }
          }
          function regresa(){
            window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
          }
        </script>
        <div class="card-header">
          <h3 class="card-title">Maestro de Empleados</h3>
        </div>
        <form class="form-horizontal" action="principal1.php?page=maestro_empleados_ver&mod=1" method="post" id="" name="">
          <div class="card-body">
            <div class="form-group">
              <label>Seleccion</label>
              <div class="form-group row">
                <div class="col-sm-12">
                  <div class="form-check form-check-inline">
                    <select class="form-control custom-select" name="edv" id="edv" style="width: 100%;" required>
                     <option value="">Seleccione un Vendedor</option>
                     <option value="-">TODOS</option>
                     <?php 
                     $vendedores= mssql_query("SELECT CodVend, Descrip from savend where codvend in ('1992','2992') and  activo = '1'");
                     if (mssql_num_rows($vendedores) != 0){ 
                      for($i=0;$i<mssql_num_rows($vendedores);$i++){
                        ?>                         
                        <option value="<?php echo mssql_result($vendedores,$i,"codvend"); ?>"><?php echo mssql_result($vendedores,$i,"codvend"); ?>: <?php echo utf8_encode(substr(mssql_result($vendedores,$i,"descrip"), 0, 35)); ?></option>
                        <?php 
                      }
                    } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Procesar</button>
          <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
        </div>
      </form>
    </div>
  </div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
