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
                        <li class="breadcrumb-item active">Eliminar Compra</li>
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
                        if (document.getElementById("fechae").value != "" && document.getElementById("numerod").value != "" && document.getElementById("proveedor").value != "" ){
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
                    <h3 class="card-title">Eliminar Compra</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=borrar_compra_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                     <?php
                     if (isset($_SESSION['mensaje'])) {
                        ?>
                        <div class="alert alert-default-<?= $_SESSION['bg_mensaje'];?> alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas <?= $_SESSION['icono'];?>"></i> Atención!</h5>
                            <?= $_SESSION['mensaje'];?>
                        </div>
                        <?php
                        unset($_SESSION['bg_mensaje']);
                        unset($_SESSION['icono']);
                        unset($_SESSION['mensaje']);
                    }
                    ?>
                    <div class="form-group">
                        <label>Ingrese el Numero de Compra</label>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="form-check form-check-inline">
                                    <label for="numerod" class="col-form-label col-sm-4"></label>
                                    <input type="text" class="form-control col-sm-10"  id="numerod" name="numerod" placeholder="Ingrese numero de documento" required>
                                </div>
                                <!-- <div class="form-check form-check-inline">
                                    <label for="vutil" class="col-form-label col-sm-4"></label>
                                    <input type="text" class="form-control col-sm-10"  id="fechat" name="fechat" required>
                                </div> -->
                                <div class="form-check form-check-inline">
                                    <label for="proveedor" class="col-form-label col-sm-4"></label>
                                    <select id="proveedor" name="proveedor" class="select2"  class="form-control col-sm-10" required>
                                        <option value="">Seleccione un Proveedor</option>
                                        <option value="-">Todos</option>
                                        <?php
                                        $proveedores= mssql_query("SELECT * from SAPROV where activo = '1' order by descrip asc");
                                        if (mssql_num_rows($proveedores) != 0){ 
                                          for($i=0;$i<mssql_num_rows($proveedores);$i++){
                                            ?>                         
                                            <option value="<?php echo mssql_result($proveedores,$i,"codprov"); ?>"><?php echo mssql_result($proveedores,$i,"codprov"); ?>: <?php echo utf8_encode(substr(mssql_result($proveedores,$i,"descrip"), 0, 35)); ?></option>
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
</div>
</section>
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
