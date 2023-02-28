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
                    function regresa(){
                        window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                    }
                </script>
                <div class="card-header">
                    <h3 class="card-title">Lista de Precios Divisas</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=lista_precios_divisas_ver&mod=1" method="post" id="formulario" name="">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group row">
                                <label for="depo" class="col-sm-2 col-form-label">Depósito</label>
                                <div class="col-sm-10">
                                    <select id="depo" name="depo" class="custom-select" style="width: 100%;" required>
                                        <option value="">--Seleccione--</option>
                                        <?php
                                        $depo= mssql_query("SELECT CodUbic, Descrip FROM sadepo ORDER BY codubic");
                                        for($i=0;$i<mssql_num_rows($depo);$i++){
                                            ?>
                                            <option value="<?php echo mssql_result($depo,$i,"CodUbic"); ?>">
                                                <?php echo mssql_result($depo,$i,"CodUbic") .": ". mssql_result($depo,$i,"Descrip"); ?>
                                            </option>
                                            <?php
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="marca" class="col-sm-2 col-form-label">Marca</label>
                                <div class="col-sm-9">
                                    <select id="marca" name="marca[]" class="select2" multiple="multiple" data-placeholder="--Seleccione--" style="width: 100%;" required>
                                        <?php
                                        $marca= mssql_query("SELECT distinct(marca) from saprod where activo = '1' and marca!='' order by marca asc");
                                        for($i=0;$i<mssql_num_rows($marca);$i++) {
                                            ?>
                                            <option value="<?php echo mssql_result($marca,$i,"marca"); ?>">
                                                <?php echo utf8_encode(mssql_result($marca,$i,"marca")); ?>
                                            </option>
                                            <?php
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-sm-1 mt-2">
                                    <div class="custom-control custom-checkbox mr-4">
                                        <input id="check_marca" name="check_marca" value="1" class="custom-control-input" type="checkbox">
                                        <label for="check_marca" class="custom-control-label">Todos</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="instap" class="col-sm-2 col-form-label">Instancias Padre</label>
                                <div class="col-sm-10">
                                    <select id="instap" name="instap[]" class="select2" multiple="multiple" data-placeholder="--Seleccione--" style="width: 100%;" required>
                                        <?php
                                        $insta_p = mssql_query("SELECT CODINST, DESCRIP, INSPADRE FROM VW_ADM_INSTANCIAS WHERE INSPADRE=0 AND CODINST IN (1,14,24)");
                                        for ($i=0; $i<mssql_num_rows($insta_p); $i++) {
                                            ?>
                                            <option value="<?php echo mssql_result($insta_p, $i, "CODINST"); ?>">
                                                <?php echo strtoupper(utf8_encode(mssql_result($insta_p, $i, "DESCRIP"))); ?>
                                            </option>
                                            <?php
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="insta" class="col-sm-2 col-form-label">Instancias</label>
                                <div class="col-sm-10">
                                    <select id="insta" name="insta[]" class="select2" multiple="multiple" data-placeholder="--Seleccione--" readonly="" style="width: 100%;" required>
                                        <!-- ajax -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="prove" class="col-sm-2 col-form-label">Proveedor</label>
                                <div class="col-sm-9">
                                    <select id="prove" name="prove[]" class="select2" multiple="multiple" data-placeholder="--Seleccione--" style="width: 100%;" required>
                                        <!-- <option value="-">TODOS LOS PROVEEDORES</option> -->
                                        <?php
                                        $prov= mssql_query("SELECT DISTINCT proveedor FROM SAPROD_99 WHERE proveedor!='' ORDER BY proveedor ASC");
                                        for($i=0;$i<mssql_num_rows($prov);$i++) {
                                            ?>
                                            <option value="<?php echo mssql_result($prov,$i,"proveedor"); ?>">
                                                <?php echo utf8_encode(mssql_result($prov,$i,"proveedor")); ?>
                                            </option>
                                            <?php
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-sm-1 mt-2">
                                    <div class="custom-control custom-checkbox mr-4">
                                        <input id="check_prove" name="check_prove" value="1" class="custom-control-input" type="checkbox">
                                        <label for="check_prove" class="custom-control-label">Todos</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orden" class="col-sm-2 col-form-label">Ordenar por:</label>
                                <div class="col-sm-10">
                                    <select id="orden" name="orden" class="form-control custom-select" required>
                                        <option value="codprod">Código</option>
                                        <option value="descrip">Descripción</option>
                                        <!-- <option value="marca">Marca</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orden" class="col-sm-2 col-form-label">Precios</label>
                                <div class="col-sm-10">
                                    <div class="form-group row ml-2 mt-1">
                                        <div class="custom-control custom-checkbox mr-4">
                                            <input class="custom-control-input" type="checkbox" id="p1" value="1" name="p1" checked="">
                                            <label for="p1" class="custom-control-label">Sur</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-4">
                                            <input class="custom-control-input" type="checkbox" id="p2" value="1" name="p2" checked="">
                                            <label for="p2" class="custom-control-label">Casco</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-4">
                                            <input class="custom-control-input" type="checkbox" id="p3" value="1" name="p3" checked="">
                                            <label for="p3" class="custom-control-label">Mayorista</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orden" class="col-sm-2 col-form-label">Existencia</label>
                                <div class="col-sm-10">
                                    <div class="form-group row ml-2 mt-1">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="exis" value="1" name="exis" checked="">
                                            <label for="exis" class="custom-control-label">Con Existencia</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-4">
                                <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                            </div>
                            <div class="col-8">
                                <div class="float-right">
                                    <button type="button" onclick="generarPdf()" class="btn btn-sm btn-danger">
                                        <i class="fa fa-file-pdf"></i> 
                                        Generar PDF
                                    </button>
                                    <button type="button" onclick="generarExcel()" class="btn btn-sm btn-success">
                                        <i class="fa fa-file-excel"></i>
                                        Generar Excel
                                    </button>
                                </div>                                    
                            </div>
                        </div>                            
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include "footer.php"; ?>
<script type="text/javascript" src="lista_precios_divisas3.js"></script>
