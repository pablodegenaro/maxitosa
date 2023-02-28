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
                        <li class="breadcrumb-item active">Costos e Inventario</li>
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
                        if ( document.getElementById("marca").value != "" && document.getElementById("depo").value != "" ){
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
                    <h3 class="card-title">Costo e Inventario</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=costo_inv_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Seleccion</label>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <select class="form-control custom-select" name="marca" id="marca" style="width: 100%;" required>
                                        <option value="">Seleccione una Marca</option>
                                        <option value="-">TODAS</option>
                                        <?php
                                        $marca= mssql_query("SELECT distinct(marca) from saprod where activo = '1' and marca is not null order by marca asc");
                                        for($i=0;$i<mssql_num_rows($marca);$i++){
                                            ?>
                                            <option value="<?php echo mssql_result($marca,$i,"marca"); ?>"><?php echo utf8_encode(mssql_result($marca,$i,"marca")); ?></option>
                                            <?php
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <select class="select2" name="depo[]" id="depo[]" multiple="multiple" data-placeholder="Seleccione Deposito" data-dropdown-css-class="select2-blue" style="width: 100%;" required>
                                        <?php
                                        $depo= mssql_query(" select CodUbic, Descrip from SADEPO order by codubic");
                                        for($i=0;$i<mssql_num_rows($depo);$i++){
                                            ?>
                                            <option value="<?php echo mssql_result($depo,$i,"CodUbic"); ?>"><?php echo mssql_result($depo,$i,"Descrip"); ?></option>
                                            <?php
                                        } ?>
                                    </select>
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
