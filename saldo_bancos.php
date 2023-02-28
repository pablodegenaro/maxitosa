<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
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
                            <li class="breadcrumb-item active">Bancos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <script type="text/javascript">
            function elimina(id){
                if(confirm("Esta Seguro de Eliminar a Este Banco?")){
                    location.href="principal.php?page=saldo_bancos_elimina&mod=1&id="+id;
                }
            }
        </script>
        <section class="content">

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-saint">
                            <div class="card-header">
                                <h3 class="card-title">Relacion De Bancos</h3>&nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="card-body">
                                <?php
                                $consulta = mssql_query("SELECT id, Descrip, NroCta, Saldo from Bancos_App");
                                ?>
                                <div class="row mb-2">
                                    <div class="col-12 text-right">
                                        <a href="principal1.php?page=saldo_bancos_edita&mod=1">Agregar Nuevo Banco </a>
                                    </div>
                                </div>

                                <table id="example2" class="table table-sm table-bordered table-hover">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>Descripcion</th>
                                            <th>Numero de Cuenta</th>
                                            <th>Saldo</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($consulta); $i++) {
                                            ?>
                                            <tr>
                                                <td><div align="center"><?php echo mssql_result($consulta,$i,"Descrip"); ?></div></td>
                                                <td><div align="center"><?php echo mssql_result($consulta,$i,"NroCta"); ?></div></td>
                                                <td><div align="right"><?php echo rdecimal2(mssql_result($consulta,$i,"Saldo")); ?></div></td>
                                                <td><div align="center"><a href="principal1.php?page=saldo_bancos_edita&mod=1&id=<?php echo mssql_result($consulta,$i,"id"); ?>"><img src="images/edt.png" border="0" width="20" height="18" /></a></div></td>
                                                <td><div align="center"><a href="javascript:;" onClick="elimina('<?php echo mssql_result($consulta,$i,"id"); ?>')"><img src="images/cancel.png" border="0" width="20" height="18" /></a></div></td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Descripcion</th>
                                            <th>Numero de Cuenta</th>
                                            <th>Saldo</th>
                                            <th>Editar</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </tfoot>
                                </table>
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