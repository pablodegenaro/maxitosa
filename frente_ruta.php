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
                        <li class="breadcrumb-item active">Frente de Ruta</li>
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
                    <h3 class="card-title">Frente de Ruta</h3>
                </div>
                <form class="form-horizontal"  method="post" id="formulario" name="">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group"><div class="row">
                            <label for="sucursal" class="col-sm-2 col-form-label">Sucursal</label>
                            <div class="col-10 mt-1">
                                <div class="form-group">
                                    <select id="sucursal" name="sucursal" class="form-control" style="width: 100%;">
                                        <?php
                                        $savend = mssql_query("SELECT SUBSTRING(CodSucu, LEN(CodSucu), LEN(CodSucu))+1 CodSucu, Descrip FROM SASUCURSAL");
                                        for ($j = 0; $j < mssql_num_rows($savend); $j++) { ?>
                                            <option value="<?php echo mssql_result($savend, $j, "CodSucu");?>">
                                                <?php echo utf8_encode(mssql_result($savend, $j, "Descrip"));?>
                                                </option><?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label for="codvend" class="col-sm-2 col-form-label">Vendedor</label>
                                <div class="col-10 mt-1">
                                    <div class="form-group">
                                        <select id="codvend" name="codvend" class="form-control select2" style="width: 100%;">
                                            <option value="">-- Seleccione --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label for="dia" class="col-sm-2 col-form-label">Día de la semana</label>
                                <div class="col-9 mt-1">
                                    <select id="dia" name="dia[]" class="select2" multiple="multiple" data-placeholder="--Seleccione--" style="width: 100%;" required>
                                        <option value="LUNES">LUNES</option>
                                        <option value="MARTES">MARTES</option>
                                        <option value="MIERCOLES">MIERCOLES</option>
                                        <option value="JUEVES">JUEVES</option>
                                        <option value="VIERNES">VIERNES</option>
                                        <option value="SABADO">SABADO</option>
                                    </select>
                                </div>
                                <div class="col-sm-1 mt-3">
                                    <div class="custom-control custom-checkbox mr-4">
                                        <input id="check_dia" name="check_dia" value="1" class="custom-control-input" type="checkbox">
                                        <label for="check_dia" class="custom-control-label">Todos</label>
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
<script type="text/javascript" src="frente_ruta.js"></script>
