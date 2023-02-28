<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $fechai = $_POST['fecha'];
  $fechai = normalize_date($fechai);
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
                        window.location.href = "principal1.php?page=ultima_activacion_clientes&mod=1";
                      }
                    </script>
                    <h3 class="card-title">Ultima Activacion de Clientes</h3>&nbsp;&nbsp;&nbsp;
                    <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                  </div>
                  <div class="card-body">
                    <?php 
                    $busca_no_activado = mssql_query("SELECT cast(FechaUV as date) as fechauv, CodClie, Descrip, ID3, CodVend from saclie where fechauv < CAST('$fechai' as datetime) and activo > 0 order by fechauv");
                    ?>
                    <table id="example1" class="table table-sm table-bordered table-striped">
                      <thead style="background-color: #00137f;color: white;">
                        <tr>
                          <th>Ultima Venta</th>
                          <th>Codigo Cliente</th>
                          <th>Razon Social</th>
                          <th>Rif</th>
                          <th>Codigo Vendedor</th>
                          <th>Saldo Pendiente $</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php for ($i = 0; $i < mssql_num_rows($busca_no_activado); $i++) {
                          ?>
                          <tr>
                            <td><?php
                            $fechauv = mssql_result($busca_no_activado, $i, "FechaUV");
                            echo normalize_date($fechauv) ?></td>
                            <td><?php echo mssql_result($busca_no_activado, $i, "CodClie"); ?></td>
                            <td><?php echo utf8_encode(mssql_result($busca_no_activado, $i, "Descrip")); ?></td>
                            <td><?php echo mssql_result($busca_no_activado, $i, "id3"); ?></td>
                            <td><?php echo mssql_result($busca_no_activado, $i, "CodVend"); ?></td>
                            <td>
                              <?php 
                              $codigo = mssql_result($busca_no_activado,$i,"codclie");
                              $consulta = mssql_query("SELECT SUM(saldo/factor) as total from saacxc where codclie='$codigo' and tipocxc='10' and saldo>0"); 
                              echo rdecimal2(mssql_result($consulta,0,"total"));
                              ?>
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