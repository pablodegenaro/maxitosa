<?php
set_time_limit(0);

$codsucu = $_SESSION['codsucu'];
$codesta = $_SESSION['codesta'];
?>
<div class="content-wrapper">
    <section class="content mt-2">
        <div class="card card-saint-soft">
            <div class="card-header">
                <form id="clie_form" method="post">
                    <input type="hidden" name="ant" id="ant_input" value="0"/>
                    <input type="hidden" name="cred" id="cred_input" value="0"/>
                    <input type="hidden" name="desc" id="desc_input" value="0"/>
                    <div class="row">
                        <!-- INPUTS DE SELECCION -->
                        <div class="col-4">
                            <div class="row"> 
                                <div class="col-12">
                                    <h3 class="m-1"><strong>Facturación</strong></h3>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="form-group">
                                        <select id="clie" name="clie" class="form-control select2" style="width: 100%;">
                                            <option value="">-- Seleccione cliente --</option>
                                            <?php
                                            $saclie = mssql_query("SELECT CodClie, Descrip FROM SACLIE WHERE Activo=1");
                                            for ($j = 0; $j < mssql_num_rows($saclie); $j++) { ?>
                                                <option value="<?php echo mssql_result($saclie, $j, "CodClie");?>">
                                                    <?php echo mssql_result($saclie, $j, "CodClie")." - ".utf8_encode(mssql_result($saclie, $j, "Descrip"));?>
                                                    </option><?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <select id="vend" name="vend" class="form-control select2" style="width: 100%;">
                                                <option value="">-- Seleccione vendedor --</option>
                                                <?php
                                                $savend = mssql_query("SELECT CodVend, Descrip FROM SAVEND ORDER BY CodVend ASC");
                                                for ($j = 0; $j < mssql_num_rows($savend); $j++) { ?>
                                                    <option value="<?php echo mssql_result($savend, $j, "CodVend");?>">
                                                        <?php echo mssql_result($savend, $j, "CodVend")." - ".utf8_encode(mssql_result($savend, $j, "Descrip"));?>
                                                        </option><?php
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <select id="depo" name="depo" class="form-control custom-select" style="width: 100%;">
                                                    <option value="">-- Seleccione depósito --</option>
                                                    <?php
                                                    $saclie = mssql_query("SELECT CodUbic, Descrip FROM SADEPO WHERE Clase='$codsucu' AND CodUbic IN ('1000','2000','3000') ORDER BY CodUbic ASC");
                                                    for ($j = 0; $j < mssql_num_rows($saclie); $j++) { ?>
                                                        <option value="<?php echo mssql_result($saclie, $j, "CodUbic");?>">
                                                            <?php echo mssql_result($saclie, $j, "CodUbic")." - ".utf8_encode(mssql_result($saclie, $j, "Descrip"));?>
                                                            </option><?php
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6" id="div_convenio">
                                                <div class="form-group">
                                                    <input type="hidden" id="convenio" name="convenio" value="0"/>
                                                    <input name="input_convenio" id="input_convenio" value="Sin Convenio" type="text" class="form-control text-left" readonly>
                                                </div>
                                            </div>
                                            <div class="col-6" id="div_precio">
                                                <div class="form-group">
                                                    <select id="tipo_precio" name="tipo_precio" class="form-control custom-select" style="width: 100%;">
                                                        <!-- <option value="0">Precio 0</option> -->
                                                        <option value="1" selected>Precio 1</option>
                                                        <option value="2">Precio 2</option>
                                                        <option value="3">Precio 3</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- SEPARACION -->
                                    <div class="col-4">
                                        <dl class="row">
                                            <?php
                                            $sasucursal = mssql_query("SELECT CodSucu, Descrip FROM SASUCURSAL WHERE CodSucu='$codsucu'");
                                            ?>
                                            <dt class="col-sm-8 text-right">Sucursal: </dt>
                                            <dd class="col-sm-4"><?php echo mssql_result($sasucursal, 0, "Descrip"); ?></dd>
                                            <dt class="col-sm-8 text-right">Estación: </dt>
                                            <dd class="col-sm-4"><?php echo $codesta; ?></dd>
                                            <dt class="col-sm-8 text-right">Tasa: </dt>
                                            <dd id="tasa" class="col-sm-4">0.00 Bs</dd>
                                            <dd class="col-sm-8 offset-sm-4 text-dark">.</dd>
                                            <dt class="col-sm-8 text-right">renglones: </dt>
                                            <dd id="itemscargado" class="col-sm-4">0</dd>
                                        </dl>
                                    </div>

                                    <!-- TOTALIZACION -->
                                    <div class="col-4 pr-3">
                                        <dl class="row">
                                            <dt class="col-sm-8 text-right">Factura Nro. </dt> 
                                            <dd id="correl" class="col-sm-4 text-right">00000000</dd>
                                            <dd class="col-sm-8 offset-sm-4 text-dark">.</dd>
                                            <dt class="col-sm-8 text-right">Subtotal</dt>
                                            <dd id="subttl" class="col-sm-4 text-right">0.00</dd>
                                            <dt class="col-sm-8 text-right">IVA 16%</dt>
                                            <dd id="imp_16" class="col-sm-4 text-right">0.00</dd>
                                    <!-- <dt class="col-sm-8 text-right">IVA Percibido</dt>
                                        <dd id="imp_per" class="col-sm-4 text-right">0.00</dd> -->
                                        <dt class="col-sm-8 text-right">Impuesto Art.18 PVP</dt>
                                        <dd id="imp_18" class="col-sm-4 text-right">0.00</dd>
                                        <dt class="col-sm-8 text-right">Total Bs</dt>
                                        <dd id="ttlbs" class="col-sm-4 text-right">0.00</dd>
                                        <dt class="col-sm-8 text-right">Total $</dt>
                                    <dd id="ttld" class="col-sm-4 text-right">0.00</dd><!-- 
                                        <dd class="col-sm-8 offset-sm-4 text-dark">.</dd> -->

                                        <input type="hidden" id="subttl_input" value="0"/>
                                        <input type="hidden" id="tgrabable_input" value="0"/>
                                        <input type="hidden" id="texento_input" value="0"/>
                                        <input type="hidden" id="imp_16_input" value="0"/>
                                        <input type="hidden" id="imp_per_input" value="0"/>
                                        <input type="hidden" id="imp_18_input" value="0"/>
                                        <input type="hidden" id="ttlbs_input" value="0"/>
                                    </dl>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-8">
                                <div id="div_btn_add" class="form-check form-check-inline">
                                    <button type="button" class="btn btn-block btn-secondary add-new">
                                        Agregar renglon
                                    </button>
                                </div>
                                <div class="form-check form-check-inline">
                                    <button id="btn_prod" type="button" class="btn btn-block btn-secondary">
                                        Productos
                                    </button>
                                </div>
                                <div class="form-check form-check-inline">
                                    <button id="btn_cargar" type="button" class="btn btn-block btn-secondary">
                                        Cargar
                                    </button>
                                </div>
                                <div class="form-check form-check-inline">
                                    <button id="btn_limpiar_tabla" type="button" class="btn btn-sm btn-block btn-secondary">
                                        Limpiar tabla
                                    </button>
                                </div>
                            </div>
                            <div class="col-4 pr-3">
                                <dl class="row">
                                    <dt id="correl_c_text" class="col-sm-8 text-right">Doc. Cargado: </dt> 
                                    <dd id="correl_c" class="col-sm-4 text-right">00000000</dd>
                                    <dd id="tipofac_c" class="col-sm-4 text-right" style="display: none;"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <form id="form_table" name="form_table" class="form-horizontal" method="post">
                        <div class="card-body p-0">
                            <table id="table_data" class="table table-sm table-bordered table-hover">
                                <thead >
                                    <tr>
                                        <th class="pl-1 text-center" style="width: 2%">#</th>
                                        <th style="width: 4%">Opc</th>
                                        <th style="width: 10%">Código</th>
                                        <th style="width: 22%">Descripción</th>
                                        <th style="width: 6%">Cant</th>
                                        <th style="width: 8%">Und</th>
                                        <th style="width: 11%">Precio Bs</th>
                                        <th style="width: 11%">Precio $</th>
                                        <th style="width: 13%">Total Bs</th>
                                        <th style="width: 13%">Total $</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <button id="btn_limpiar" type="button" class="btn btn-sm btn-outline-saint">Limpiar</button>
                            <button id="btn_ttl" type="button" class="btn btn-saint float-right">Totalizar</button>

                        </div>
                    </form>
                </div>
                <!-- </div> -->
                <div class="row text-center pb-4" style="width: 10%;">
                    <span>Leyenda:</span>

                    <div class="col-sm-12">
                        <div class="bg-navy color-palette mt-1"><span>Precio 1</span></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="bg-primary color-palette mt-1"><span>Precio 2</span></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="bg-info color-palette mt-1"><span>Precio 3</span></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="bg-purple color-palette mt-1"><span>Convenio</span></div>
                    </div>
                </div>
            </section>
            <!-- /.content -->

        </div>

        <!-- MODAL PRODUCTOS -->
        <div class="modal fade" id="productosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <input type="hidden" id="idxprd" value="-1"/>
                    <div class="modal-header">
                        <h4 class="modal-title">PRODUCTOS </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row pl-2 mb-2">
                            <div class="col-8">
                                <p class="pt-1">
                                    <strong>DEPOSITO:</strong>&nbsp;&nbsp; <span id="span_depo"></span>
                                </p>
                            </div>
                            <div class="col-4 float-right">
                                <input id="buscaPrd" type="text" class="form-control text-left" placeholder="Buscar">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table id="productos_data" class="table table-sm table-bordered table-striped text-center">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr>
                                            <th class="small align-middle">Opciones</th>
                                            <th class="small align-middle">Código</th>
                                            <th class="small align-middle">Descripción</th>
                                            <th class="small align-middle">Bultos</th>
                                            <th class="small align-middle">Paquetes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- TD de la tabla que se pasa por ajax -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL CARGAR DOCUMENTO -->
        <div class="modal fade" id="documentosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">DOCUMENTOS </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row pl-2 mb-2">
                            <div class="col-8"></div>
                            <div class="col-4 float-right">
                                <input id="buscaDoc" type="text" class="form-control text-left" placeholder="Buscar">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table id="documentos_data" class="table table-sm table-bordered table-striped text-center">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr>
                                            <th class="small align-middle">Nro Doc.</th>
                                            <th class="small align-middle">Razón Social</th>
                                            <th class="small align-middle">Emisión</th>
                                            <th class="small align-middle">Tipo Doc.</th>
                                            <th class="small align-middle">Total Bs</th>
                                            <th class="small align-middle">Total $</th>
                                            <th class="small align-middle">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- TD de la tabla que se pasa por ajax -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL TOTALIZAR -->
        <div class="modal fade" id="totalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #084f8a;color: white;">
                        <h4 class="modal-title"><strong>(FACTURACIÓN)</strong> TOTAL OPERACIÓN</h4>
                        <button type="button" onclick="cerrarTotales()" class="btn btn-sm bg-light float-right">
                            Cerrar
                        </button>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <form id="total_modal_form" method="post">
                <div class="modal-body">
                    <div class="row form-group">
                        <label for="total_ope_bs" class="col-sm-4 col-form-label">Total Operación</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="total_ope_bs" name="total_ope_bs" type="text" class="form-control text-right" placeholder="0" value="0" readonly="">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="total_ope_d" name="total_ope_d" type="text" class="form-control text-right" placeholder="0" value="0" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="row form-group" id="div_primerdes">
                        <label for="primer_des" class="col-sm-4 col-form-label">Primer Desc.</label>
                        <div class="col-sm-8 input-group">
                            <input id="primer_des" name="primer_des" type="text" class="form-control" placeholder="0" onkeypress="return isNumberKey(this, event)" onkeydown="return setValueOnPressEnter(this,event)" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group" id="div_montodes">
                        <label for="monto_des" class="col-sm-4 col-form-label">Monto Desc.</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="monto_des" name="monto_des" type="text" class="form-control text-right" value="0" readonly="" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="monto_des_d" name="monto_des_d" type="text" class="form-control text-right" value="0" readonly="" required>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label for="ttl_neto_bs" class="col-sm-4 col-form-label">Total Neto</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="ttl_neto_bs" name="ttl_neto_bs" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="ttl_neto_d" name="ttl_neto_d" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="ttl_imp_16_bs" class="col-sm-4 col-form-label">IVA 16%</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="ttl_imp_16_bs" name="ttl_imp_16_bs" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="ttl_imp_16_d" name="ttl_imp_16_d" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group"  hidden="">
                        <label for="ttl_imp_per_bs" class="col-sm-4 col-form-label">IVA Percibido</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="ttl_imp_per_bs" name="ttl_imp_per_bs" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="ttl_imp_per_d" name="ttl_imp_per_d" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="ttl_imp_18_bs" class="col-sm-4 col-form-label">Imp. Art.18 PVP</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="ttl_imp_18_bs" name="ttl_imp_18_bs" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="ttl_imp_18_d" name="ttl_imp_18_d" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="ttl_gral_bs" class="col-sm-4 col-form-label">Total General</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Bs</span></div>
                                <input id="ttl_gral_bs" name="ttl_gral_bs" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input id="ttl_gral_d" name="ttl_gral_d" type="text" class="form-control text-right" placeholder="0" readonly="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="tipo_ope" class="col-sm-4 col-form-label">Tipo operación</label>
                        <div class="col-sm-8">
                            <select id="tipo_ope" name="tipo_ope" class="form-control custom-select" style="width: 100%;" required>
                                <option value="">-- Seleccione --</option>
                                <?php
                                $saoper = mssql_query("SELECT CodOper, Descrip FROM SAOPER WHERE CodOper='$codsucu'");
                                for ($j = 0; $j < mssql_num_rows($saoper); $j++) { 
                                    $codoper = mssql_result($saoper, $j, "CodOper"); ?>
                                    <option value="<?php echo $codoper;?>" <?php if ($codoper==$codsucu) { echo 'selected'; } ?>>
                                        <?php echo utf8_encode(mssql_result($saoper, $j, "Descrip"));?>
                                        </option><?php
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="fechaemi" class="col-sm-4 col-form-label">Fecha Emisión</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="date" class="form-control" id="fechaemi" name="fechaemi" onchange="setFechaV('num')" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" readonly="" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="diasven" class="col-sm-4 col-form-label">Dias Vencimiento</label>
                            <div class="col-sm-1">
                                <input id="diasven" name="diasven" type="text" class="form-control" value="0" min="0" onkeyup="setFechaV('num')" onkeypress="return isNumberKey(this, event)" onkeydown="return setValueOnPressEnter(this,event)" required>
                            </div>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="date" class="form-control" id="fechaven" name="fechaven" onchange="setFechaV('date')" min="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" required="">
                                </div>
                            </div>
                        </div>
                        <div class="row form-group" id="div_anticipo">
                            <label for="anticipo" class="col-sm-4 col-form-label pb-0">Anticipo</label>
                            <div class="col-sm-8">
                                <input id="anticipo" name="anticipo" type="text" class="form-control pb-0" placeholder="(máx. 0.00)" min="0" onkeypress="return isNumberKey(this, event)" onkeydown="return setValueOnPressEnter(this,event)" required>
                            </div>
                            <span id="span_anticipo" class="col-sm-4 col-form-label pt-0">(disponible 0.00 Bs)</span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btn_modal_comentario" type="button" class="btn btn-sm btn-outline-saint">
                            Comentarios
                        </button>
                        <button id="btn_modal_facturar" type="Submit" name="action" class="btn btn-saint float-right">
                            Facturar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL COMENTARIO -->
    <div class="modal fade" id="comentarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #084f8a;color: white;">
                    <h4 class="modal-title">COMENTARIOS</h4>
                    <button type="button" onclick="cerrarComentario()" class="btn btn-sm bg-light float-right">
                        Cerrar
                    </button>
                </div>
                <form id="comentario_form" method="post">
                    <div class="modal-body">
                        <div class="row form-group">
                            <label for="coment1" class="col-sm-2 col-form-label">Comentario 1</label>
                            <div class="col-sm-10">
                                <input id="coment1" name="coment1" type="text" maxlength="60" class="form-control">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="coment2" class="col-sm-2 col-form-label">Comentario 2</label>
                            <div class="col-sm-10">
                                <input id="coment2" name="coment2" type="text" maxlength="60" class="form-control">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="coment3" class="col-sm-2 col-form-label">Comentario 3</label>
                            <div class="col-sm-10">
                                <input id="coment3" name="coment3" type="text" maxlength="60" class="form-control">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="coment4" class="col-sm-2 col-form-label">Comentario 4</label>
                            <div class="col-sm-10">
                                <input id="coment4" name="coment4" type="text" maxlength="60" class="form-control">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="coment5" class="col-sm-2 col-form-label">Comentario 5</label>
                            <div class="col-sm-10">
                                <input id="coment5" name="coment5" type="text" maxlength="60" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn_modal_comentario_aceptar" type="button" name="action" class="btn btn-saint pull-left">
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("footer3.php"); ?>
    <script type="text/javascript" src="ventas3_fac.js"></script>
    <script type="text/javascript" src="ventas3_fac_gestion_tabla.js"></script>