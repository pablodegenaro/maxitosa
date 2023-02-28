<?php 
require("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
$aux = $_GET['correl'];
$usuario = $_GET['usuario'];
$fechad = "";
$destino = "";
$placa = "";
$chofer = "";
if ($aux != ""){
  $consulta_correl = mssql_query("SELECT * from appfacturasft where correl = '$aux'");
  $fechad = mssql_result($consulta_correl,0,"fechad");
  $destino = mssql_result($consulta_correl,0,"nota");
  $chofer = mssql_result($consulta_correl,0,"cedula_chofer"); 
  $placa = mssql_result($consulta_correl,0,"placa");
  $fechai = normalize_date($fechad);
//$fechaf = normalize_date($fechaf);
}
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
          <li class="breadcrumb-item active">Editar Despacho</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">

  <div class="container">
    <div class="card card-info">
      <div class="card-header">
        <script type="text/javascript">
          function guarda(){
            if (document.getElementById("fechad").value != "" && document.getElementById("destino").value != "" && document.getElementById("placa").value != ""){
              document.forms["edit_despacho"].submit();
            }else{
              alert("Debe Rellenar Todos Los Campos");
            }
          }
          function regresa(){
            window.location.href = "principal1.php?page=despacho_visualft&mod=1&correl2=<?php echo $correl; ?>&usuario=<?php echo $usuario; ?>";
          }
        </script>
        <h3 class="card-title">Editar Despacho # <?php echo $aux; ?></h3>
      </div>
      <form class="form-horizontal" action="principal1.php?page=despacho_edita_verft&mod=1&correl=<?php echo $aux; ?>" method="post" id="edit_despacho" name="edit_despacho">
        <div class="card-body">
          <div class="form-group row">
            <label for="fechad" class="col-sm-2 col-form-label">Fecha</label>
            <div class="input-group date col-sm-10" id="fechadesp" data-target-input="nearest">
              <input type="text" name="fechadespacho" id="fechadespacho" class="form-control datetimepicker-input" data-target="#fechadesp" value="<?php echo $fechai; ?>"/>
              <div class="input-group-append" data-target="#fechadesp" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="destino" class="col-sm-2 col-form-label">Destino</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" value="<?php echo $destino; ?>" id="destino" name="destino" placeholder="Destino del Despacho" requiere>
            </div>
          </div>
          <div class="form-group row">
            <label for="placa" class="col-sm-2 col-form-label">placa</label>
            <div class="col-sm-10">
              <select class="form-control custom-select" name="placa" id="placa" style="width: 100%;" required>
                <option value="<?php echo $placa; ?>"><?php echo $placa; ?></option>
                <?php 
                $vehiculo= mssql_query("SELECT * from appVehiculo");
                if (mssql_num_rows($vehiculo) != 0){ 
                  for($i=0;$i<mssql_num_rows($vehiculo);$i++){
                    ?>                         
                    <option value="<?php echo mssql_result($vehiculo,$i,"placa"); ?>"><?php echo mssql_result($vehiculo,$i,"placa"); ?>: <?php echo substr(mssql_result($vehiculo,$i,"modelo"), 0, 35); ?></option>
                    <?php 
                  }
                } ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="chofer" class="col-sm-2 col-form-label">Chofer</label>
            <div class="col-sm-10">
              <select class="form-control custom-select" name="chofer" id="chofer" style="width: 100%;" required>
                <option value="<?php echo $chofer; ?>"><?php echo $chofer; ?></option>
                <?php 
                $choferes= mssql_query("SELECT * from appChofer where estatus = '1'");
                if (mssql_num_rows($choferes) != 0){ 
                  for($i=0;$i<mssql_num_rows($choferes);$i++){
                    ?>                         
                    <option value="<?php echo mssql_result($choferes,$i,"cedula"); ?>"><?php echo mssql_result($choferes,$i,"cedula"); ?>: <?php echo substr(mssql_result($choferes,$i,"descripcion"), 0, 35); ?></option>
                    <?php 
                  }
                } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <input type="hidden" id="usuario" name="usuario" value="<?php echo  $_SESSION['login']; ?>">
          <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-info">Guardar</button>
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
      </form>
    </div>
  </div>
</div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
