<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
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
            <li class="breadcrumb-item active">Convenios</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
   <div class="row">
    <div class="col-12">
     <div class="card card-saint">
      <div class="card-header">
       <h3 class="card-title">Relacion de Convenios</h3>&nbsp;&nbsp;&nbsp;
     </div>
     <div class="card-body">
      <?php 
      $consulta_convenio = mssql_query("SELECT descripcion, nivel_precio, porcentaje, precio_base, tipo_aplicacion from convenio_configuracion order by nivel_precio asc");
      ?>
      <table id="example8" class="table table-sm table-bordered table-hover">
        <thead style="background-color: #00137f;color: white;">
          <tr>
            <th>Descripcion</th>
            <th>N° Convenio</th>
            <th>Precio Base</th>
            <th>Porcentaje</th>
            <th>Aplicacion</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 0; $i < mssql_num_rows($consulta_convenio); $i++) {
            ?>
            <tr>
              <td><div align="center"><?php echo mssql_result($consulta_convenio,$i,"descripcion"); ?></div></td>
              <td><div align="center"><?php echo mssql_result($consulta_convenio,$i,"nivel_precio"); ?></div></td>
              <td><div align="center"><?php 
              $preciobase=mssql_result($consulta_convenio,$i,"precio_base");
                      //0 = manual
                      //1= sur
                      //2=casco
                      //3=mayorista
              switch ($preciobase) {
                case 0:
                $precio = "Manual";
                break;
                case 1:
                $precio = "Sur";
                break;
                case 2:
                $precio = "Casco";
                break;
                case 3:
                $precio = "Mayorista";
                break;
              }
              echo  $precio; ?></div>
            </td>
            <td><div align="center"><?php echo mssql_result($consulta_convenio,$i,"porcentaje"); ?> %</div></td>
            <td><div align="center"><?php 
            $aplicacion=mssql_result($consulta_convenio,$i,"tipo_aplicacion");
                    //0=no aplica
                    //1= incremento multiplicar por el porcentaje.
                    //2= decremento dividir por el porcentaje.
            switch ($aplicacion) {
              case 0:
              $apli = "N/A";
              break;
              case 1:
              $apli = "Incremento";
              break;
              case 2:
              $apli = "Decremento";
              break;
            }
            echo  $apli; ?></div>
          </td>                    
          <td>
            <div align="center">
              <a href="principal1.php?page=convenio_configuracion_ver&mod=1&id=<?php echo mssql_result($consulta_convenio,$i,"descripcion"); ?>"><img src="images/edt.png" border="0" width="20" height="18" /></a>
            </div>
          </td>
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