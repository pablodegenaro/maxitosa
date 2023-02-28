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
                        <li class="breadcrumb-item active">Kpi Marcas</li>
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
                        let form = $('#frm_kpimarcas').serialize();

                        $.ajax({
                            async: true,
                            url: "kpi_marcas_guardar.php",
                            method: "POST",
                            dataType: "json",
                            data: form,
                            error: function (e) {
                                console.log(e.responseText);
                            },
                            success: function (data) {
                                if(!jQuery.isEmptyObject(data)) {
                                    let { icono, mensaje } = data

                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                    })
                                    Toast.fire({
                                        icon: icono,
                                        title: mensaje
                                    })
                                }
                            }
                        });
                    }
                    function regresa(){
                        window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                    }
                </script>
                <div class="card-header">
                    <h3 class="card-title">Seleccione las Marcas</h3>
                </div>
                <div class="card-body">
                    <form id="frm_kpimarcas" class="form-horizontal">
                        <div class="row">
                            <dt class="col-6">TODAS LAS MARCAS</dt>
                            <dt class="col-6">MARCAS VISIBLES PARA EL KPI</dt>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <select name="marcas[]" id="marcas[]" class="duallistbox" multiple="multiple">
                                    <!-- la lista de marcas se carga por ajax -->
                                    <?php
                                    $kpi_marcas = array();
                                    $query_kpi_marcas = mssql_query("SELECT id, descripcion, fechae FROM Kpi_marcas ORDER BY id ");
                                    for($i=0;$i<mssql_num_rows($query_kpi_marcas);$i++) {
                                        $kpi_marcas[] = mssql_result($query_kpi_marcas,$i,"descripcion");
                                    }

                                    $marcas = mssql_query("SELECT DISTINCT(marca) FROM saprod WHERE activo = '1' AND marca IS NOT NULL ORDER BY marca ASC");
                                    for($i=0;$i<mssql_num_rows($marcas);$i++) {
                                        $seleccionado = in_array(mssql_result($marcas,$i,"marca"), $kpi_marcas);
                                        ?>
                                        <option value="<?php echo mssql_result($marcas,$i,"marca"); ?>" <?= ($seleccionado) ? 'selected' : '';?>>
                                            <?php echo utf8_encode(mssql_result($marcas,$i,"marca")); ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Guardar</button>
                    <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
                </div>
            </div>
        </div>
    </section>

</div>
<?php include "footer.php"; ?>
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="Icons.js" type="text/javascript"></script>
