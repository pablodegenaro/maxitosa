<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
if ($_SESSION['login']) {
    ?>
    <div class="content-wrapper" >
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                            <li class="breadcrumb-item active">Productos Precios</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content" >


            <div class="col-md-12">
                <div class="card card-saint">
                </div>
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
                        <h3 class="card-title">Productos Precios</h3>&nbsp;&nbsp;&nbsp;
                    </div>
                    <form name="formulario" method="post" action="productos_precios_procesa.php">
                        <div class="card-body">
                            <?php
                            $productos = mssql_query("SELECT prod.CodProd, Descrip, Marca, prod99.proveedor, Refere, Maneja_Factor, Precio_Manual, Costo_Total, Flete_ME, pvp, sugerido, iva, Profit1, Profit2, Profit3 from saprod prod  left join SAPROD_99 prod99 on prod.CodProd = prod99.CodProd");
                            ?> 
                            <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                <table id="example5" class="table table-sm table-bordered table-striped table-responsive p-0">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Proveedor</th>
                                            <th>Marca</th>
                                            <th>Costo $</th>
                                            <th>Art. 18 PVP Bs</th>
                                            <th>IVA Percibido Bs</th>
                                            <th>PVP Sugerido</th>
                                            <th>Precio 1 $ Sur</th>
                                            <th>Precio 2 $ Casco</th>
                                            <th>Precio 3 $ Mayorista</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($productos); $i++) {
                                            $Costo_Total = mssql_result($productos, $i, "Costo_Total");
                                            $pvp = mssql_result($productos, $i, "pvp");
                                            $iva = mssql_result($productos, $i, "iva");
                                            $sugerido = mssql_result($productos, $i, "sugerido");
                                            $Profit1 = mssql_result($productos, $i, "Profit1");
                                            $Profit2 = mssql_result($productos, $i, "Profit2");
                                            $Profit3 = mssql_result($productos, $i, "Profit3");

                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo mssql_result($productos, $i, "CodProd"); ?>
                                                    <input type="hidden" name="cod[]" value="<?php echo trim(mssql_result($productos, $i, 'CodProd')); ?>">
                                                    <input type="hidden" name="proveedor[]" value="<?php echo trim(mssql_result($productos, $i, 'proveedor')); ?>">
                                                </td>
                                                <td class="text-left"><?php echo utf8_encode(mssql_result($productos, $i, "Descrip")); ?></td>
                                                <td class="text-center"><?php echo utf8_encode(mssql_result($productos, $i, "proveedor")); ?></td>
                                                <td class="text-center"><?php echo utf8_encode(mssql_result($productos, $i, "Marca")); ?></td>
                                                <td class="text-center">
                                                    <input type="text" name="costo_total[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($Costo_Total>0) ? number_format($Costo_Total, 2, '.', '') : 0; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="pvp[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($pvp>0) ? number_format($pvp, 2, '.', '') : 0; ?>">
                                                </td> 
                                                <td class="text-center">
                                                    <input type="text" name="iva[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($iva>0) ? number_format($iva, 2, '.', '') : 0; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="sugerido[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($sugerido>0) ? number_format($sugerido, 2, '.', '') : 0; ?>">
                                                </td> 
                                                <td class="text-center">
                                                    <input type="text" name="profit1[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($Profit1>0) ? number_format($Profit1, 2, '.', '') : 0; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="profit2[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($Profit2>0) ? number_format($Profit2, 2, '.', '') : 0; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="profit3[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($Profit3>0) ? number_format($Profit3, 2, '.', '') : 0; ?>">
                                                </td>                                                    
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                                <button type="submit" name="Submit"   onclick="limpiarBuscar()"  class="btn btn-saint float-right">Procesar</button>
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
                    //Check if the text already contains the . character
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

            function limpiarBuscar() {
                $('[type="search"]').val("");
            }
        </script>
        <script>
            $(document).ready(function () {
                //$('td[style*="display: none"] input').prop("disabled", true);
            });

            $(document).on("click", "#exportar_excel", function () {
                var value = $('.dataTables_filter input').val();
                window.open('productos_precios_excel.php?&s=' + value, '_blank');
            });
        </script>
        <?php
    } else {
        header('Location: index.php');
    }
?>