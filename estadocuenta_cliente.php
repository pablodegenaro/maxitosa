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
                        <li class="breadcrumb-item active">Estado de Cuenta Cliente</li>
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
                        if (document.getElementById("fechai").value != "" && document.getElementById("fechaf").value != "" && document.getElementById("cliente").value != ""  ){
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
                    <h3 class="card-title">Estado de Cuenta Clientes</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=estadocuenta_cliente_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4">Hasta</label>
                                        <input type="date" class="form-control col-sm-10"  id="fechaf" name="fechaf" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                        <select id="cliente" name="cliente" class="select2"  data-placeholder="--Seleccione--"  required>
                                            <option value="">Seleccione un Cliente</option>
                                            <?php 
                                            $clientes= mssql_query("SELECT * from saclie where activo = '1'");
                                            if (mssql_num_rows($clientes) != 0){                                                     
                                                for($i=0;$i<mssql_num_rows($clientes);$i++){
                                                    ?>                         
                                                    <option value="<?php echo mssql_result($clientes,$i,"codclie"); ?>"><?php echo mssql_result($clientes,$i,"codclie"); ?>: <?php echo substr(utf8_encode(mssql_result($clientes,$i,"descrip")), 0, 35); ?></option>
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
