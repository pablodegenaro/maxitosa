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
                        <li class="breadcrumb-item active">Resumen de Facturas</li>
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
                        if (document.getElementById("fechai").value != "" && document.getElementById("fechaf").value != "" && document.getElementById("sucursal").value != "" ){
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
                    <h3 class="card-title">Resumen de Facturas</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=resumen_fac_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <label>Seleccion</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4"></label>
                                        <input type="date" class="form-control col-sm-10"  id="fechai" name="fechai" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4"></label>
                                        <input type="date" class="form-control col-sm-10"  id="fechaf" name="fechaf" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;

                                    <div class="form-check form-check-inline">
                                        <select class="form-control custom-select" name="sucursal" id="sucursal" style="width: 100%;" required>
                                            <option value="">Seleccione una Sucursal</option>
                                            <option value="-">TODAS</option>
                                            <?php
                                            $sucur= mssql_query("SELECT CodSucu, Descrip from SASUCURSAL");
                                            for($i=0;$i<mssql_num_rows($sucur);$i++){
                                                ?>
                                                <option value="<?php echo mssql_result($sucur,$i,"CodSucu"); ?>"><?php echo mssql_result($sucur,$i,"Descrip"); ?></option>
                                                <?php
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
