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
                            <li class="breadcrumb-item active">Aumento de Precios por Marca</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-saint">
                    <script type="text/javascript">
                        function guarda(){
                            limpiarBuscar();
                                /* document.forms["registro_usuarios"].submit();*/
                        }
                        function regresa(){
                            window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Aumento de Precios por Marca</h3>&nbsp;&nbsp;&nbsp;
                    </div>
                    <form name="formulario" method="post" action="aumento_precios_marca_procesa.php">
                        <div class="card-body">
                            <?php
                            if (isset($_SESSION['mensaje'])) {
                                ?>
                                <div class="alert alert-<?= $_SESSION['bg_mensaje'];?> alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas <?= $_SESSION['icono'];?>"></i> Atencion!</h5>
                                    <?= $_SESSION['mensaje'];?>
                                </div>
                                <?php
                                unset($_SESSION['bg_mensaje']);
                                unset($_SESSION['icono']);
                                unset($_SESSION['mensaje']);
                            }
                            ?>
                            <?php
                            $marca = mssql_query("SELECT distinct Marca FROM SAPROD WHERE marca != '' ORDER BY marca");

                            ?>
                            <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                <table id="example4" class="table table-sm table-bordered table-striped">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th style="width: 40%;">Marca</th>
                                            <th style="width: 20%;">%Precio 1</th>
                                            <th style="width: 20%;">%Precio 2</th>
                                            <th style="width: 20%;">%Precio 3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($marca); $i++) {
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo utf8_encode(mssql_result($marca, $i, "Marca")); ?>
                                                    <input type="hidden" name="marca[]" value="<?php echo trim(mssql_result($marca, $i, 'Marca')); ?>">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="profit1[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)" value="0">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="profit2[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)" value="0">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="profit3[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)" value="0">
                                                </td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="button" onclick="regresa()" class="btn btn-outline-saint ">Regresar</button>
                                <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint float-right">Procesar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <?php include "footer.php"; ?>
        <script src="Icons.js" type="text/javascript"></script>
        <script type="text/javascript">
            function isNumberKey(txt, evt) {
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode == 46) {
                    if (txt.value.indexOf('.') === -1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (charCode > 31 &&
                        (charCode < 48 || charCode > 57))
                        return false;
                }
                return true;
            }
        </script>
        <?php
    } else {
        header('Location: index.php');
    }
?>