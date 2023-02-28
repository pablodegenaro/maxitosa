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
                        <li class="breadcrumb-item active">Lista de Precios Divisas 2</li>
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
                        if (document.getElementById("depo").value != "" && document.getElementById("marca").value != "" && document.getElementById("orden").value != ""){
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
                    <h3 class="card-title">Lista de Precios Divisas</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=lista_precios_divisas2_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <label>Seleccion</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <select class="custom-select" name="depo" id="depo" required>
                                            <option value="">Seleccione un Deposito</option>
                                            <option value="-">TODOS</option>
                                            <?php
                                            $depo= mssql_query("SELECT CodUbic, Descrip FROM sadepo where Descrip like 'Almacen%' ORDER BY codubic");
                                            for($i=0;$i<mssql_num_rows($depo);$i++){
                                                ?>
                                                <option value="<?php echo mssql_result($depo,$i,"CodUbic"); ?>"><?php echo mssql_result($depo,$i,"CodUbic") .": ". mssql_result($depo,$i,"Descrip"); ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <select class="form-control custom-select" name="proveedor" id="proveedor" style="width: 100%;" required>
                                            <option value="">Seleccione Proveedor</option>
                                            <option value="-">TODOS</option>
                                            <?php 
                                            $proveedor= mssql_query("SELECT distinct(proveedor) from SAPROD_99 where proveedor is not null order by proveedor asc");
                                            for($i=0;$i<mssql_num_rows($proveedor);$i++){
                                                ?>
                                                <option value="<?php echo mssql_result($proveedor,$i,"proveedor"); ?>"><?php echo utf8_encode(mssql_result($proveedor,$i,"proveedor")); ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </select>
                                </div>
                                <div class="form-check form-check-inline">
                                    <select class="custom-select" name="orden" id="orden" style="width: 100%;" required>
                                        <option value="">Ordenar por:</option>
                                        <option value="codprod">Código</option>
                                        <option value="descrip">Descripción</option>
                                        <option value="marca">Marca</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row pl-4">
                            <div class="form-check form-check-inline">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" value="1" name="precio" checked="">
                                    <label for="customRadio1" class="custom-control-label">F. Sur</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input class="custom-control-input" type="radio" id="customRadio2" value="2" name="precio">
                                    <label for="customRadio2" class="custom-control-label">Casco M.</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input class="custom-control-input" type="radio" id="customRadio3" value="3" name="precio">
                                    <label for="customRadio3" class="custom-control-label">Mayorista</label>
                                </div>
                            </div>

                            <div class="custom-control custom-checkbox col-sm-2 ml-4">
                                <input class="custom-control-input" type="checkbox" id="exis" value="1" name="exis" checked="checked">
                                <label for="exis" class="custom-control-label">Con Existencia</label>
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
