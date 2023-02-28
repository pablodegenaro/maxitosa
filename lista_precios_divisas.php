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
                        <li class="breadcrumb-item active">Lista de Precios Divisas</li>
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
                <form class="form-horizontal" action="principal1.php?page=lista_precios_divisas_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Seleccion</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <select class="custom-select" name="depo" id="depo" required>
                                            <option value="">Seleccione un Deposito</option>
                                            <option value="-">TODOS</option>
                                            <?php
                                            $depo= mssql_query("SELECT CodUbic, Descrip FROM sadepo ORDER BY codubic");
                                            for($i=0;$i<mssql_num_rows($depo);$i++){
                                                ?>
                                                <option value="<?php echo mssql_result($depo,$i,"CodUbic"); ?>"><?php echo mssql_result($depo,$i,"CodUbic") .": ". mssql_result($depo,$i,"Descrip"); ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <select class="form-control custom-select" name="marca" id="marca" style="width: 100%;" required>
                                            <option value="">Seleccione una Marca</option>
                                            <option value="-">TODAS</option>
                                            <?php
                                            $marca= mssql_query("SELECT distinct(marca) from saprod where activo = '1' order by marca asc");
                                            for($i=0;$i<mssql_num_rows($marca);$i++){
                                                ?>
                                                <option value="<?php echo mssql_result($marca,$i,"marca"); ?>"><?php echo utf8_encode(mssql_result($marca,$i,"marca")); ?></option>
                                                <?php
                                            } ?>
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
                                <div class="custom-control custom-checkbox col-sm-1">
                                    <input class="custom-control-input" type="checkbox" id="p1" value="1" name="p1">
                                    <label for="p1" class="custom-control-label">Precio 1</label>
                                </div>
                                <div class="custom-control custom-checkbox col-sm-1">
                                    <input class="custom-control-input" type="checkbox" id="p2" value="1" name="p2">
                                    <label for="p2" class="custom-control-label">Precio 2</label>
                                </div>
                                <div class="custom-control custom-checkbox col-sm-2">
                                    <input class="custom-control-input" type="checkbox" id="p3" value="1" name="p3">
                                    <label for="p3" class="custom-control-label">Precio 3</label>
                                </div>
                                <div class="custom-control custom-checkbox col-sm-2">
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
