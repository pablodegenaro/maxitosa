<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
session_start();
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
                            <li class="breadcrumb-item active">Vendedores</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-saint">
                    <div class="card-header">
                        <script type="text/javascript">
                            function regresa(){
                                window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                            }
                        </script>
                        <h3 class="card-title">Vendedores</h3>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="card-body">
                        <?php
                        $vendedores = mssql_query("SELECT S.Descrip, S.clase, S.Telef, S.activo, depo.Descrip Depo, U.* FROM savend_99 AS U INNER JOIN savend AS S ON S.CodVend = U.CodVend LEFT JOIN sadepo depo ON depo.CodUbic=U.ubicacion");
                        ?>
                        <table id="example1" class="table table-sm table-bordered table-striped text-center">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <th>Ruta</th>
                                    <th>Nombre</th>
                                    <th>Clase</th>
                                    <th>Deposito</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < mssql_num_rows($vendedores); $i++) {

                                    $coordinador = (mssql_result($vendedores, $i, "activo")==1 and strlen(mssql_result($vendedores, $i, "supervisor"))==0)
                                    ? '<br><span class="right badge badge-primary">Coordinador no asignado</span>'
                                    : '';
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo mssql_result($vendedores, $i, "CodVend"); ?></td>
                                        <td class="text-center"><?php echo utf8_encode(mssql_result($vendedores, $i, "Descrip")) . $coordinador ; ?></td>
                                        <td class="text-center"><?php echo mssql_result($vendedores, $i, "clase"); ?></td>
                                        <td class="text-center">
                                            <?php if (strlen(mssql_result($vendedores, $i, "ubicacion")) > 0) {
                                                echo mssql_result($vendedores, $i, "ubicacion") ." : " . mssql_result($vendedores, $i, "depo");
                                            } else {
                                                echo '<span class="right badge badge-secondary">no asignado</span>';
                                            }?>
                                        </td>
                                        <td  class="text-center">
                                            <a href="principal1.php?page=vendedores_edita&mod=1&ven=<?php echo mssql_result($vendedores, $i, "CodVend"); ?>"
                                               class="btn btn-outline-saint btn-sm">
                                               Editar
                                           </a>
                                       </td>
                                   </tr>
                               <?php }?>
                           </tbody>
                       </table>
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