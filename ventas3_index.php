<?php
$codsucu = $_SESSION['codsucu'];
$sasucursal = mssql_query("SELECT CodSucu, Descrip FROM SASUCURSAL WHERE CodSucu='$codsucu'");
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Ventas </h1> 
                    <input type="hidden" id="sucursal_hidden" value="<?= mssql_result($sasucursal, 0, "Descrip"); ?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                <?php 
                $arr_modulos_ventas = array('ventas3_fac.php','ventas3_devolfac.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-info pb-4">
                            <div class="inner">
                                <h3>Facturación</h3>
                                <div class="row">
                                    <div class="col-12">
                                        <?php
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_fac.php')) > 0) {
                                            ?>
                                            <a class="text-light" href="principal4.php?page=ventas3_fac&mod=1">Emisión Facturas</a>
                                            <br>
                                            <?php 
                                        }
                                        ?>
                                    </div>
                                    <div class="col-12">
                                        <?php
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_devolfac.php')) > 0) {
                                            ?>
                                            <a class="text-light" href="principal4.php?page=ventas3_devolfac&mod=1">Devolución Facturas</a>
                                            <?php 
                                        }
                                        ?>
                                    </div>
                                    <div class="col-12">
                                        <?php
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_devolfac_par.php')) > 0) {
                                            ?>
                                            <a class="text-light" href="principal4.php?page=ventas3_devolfac_par&mod=1">Devolución Facturas Parcial</a>
                                            <?php 
                                        }
                                        ?>
                                    </div>
                                </div>

                                <?php 
                                $arr_modulos_ventas_imp = array('ventas3_fac_pdf.php','ventas3_devolfac_pdf.php');
                                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas_imp)) > 0) {
                                    ?>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <?php
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_fac_pdf.php')) > 0) {
                                                ?>
                                                <a onclick="mostrar('A')" class="text-light">Imprimir Facturas</a>
                                                <br>
                                                <?php
                                            } ?>
                                        </div>
                                        <div class="col-12">
                                            <?php 
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_devolfac_pdf.php')) > 0) {
                                                ?>
                                                <a onclick="mostrar('B')" class="text-light">Imprimir Devolución Facturas</a>
                                                <?php 
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>
                

                <?php 
                $arr_modulos_ventas = array('ventas3_ne.php','ventas3_devolne.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-success pb-4">
                            <div class="inner">
                                <h3>Nota de Entrega</h3>
                                <div class="row">
                                    <div class="col-12">
                                        <?php 
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_ne.php')) > 0) {
                                            ?>
                                            <a href="principal4.php?page=ventas3_ne&mod=1" class="text-light">Emisión NE</a>
                                            <br>
                                            <?php 
                                        } 
                                        ?>
                                    </div>
                                    <div class="col-12">
                                        <?php
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_devolne.php')) > 0) {
                                            ?>
                                            <a href="principal4.php?page=ventas3_devolne&mod=1" class="text-light">Devolución NE</a>
                                            <br>
                                            <?php 
                                        } ?>
                                    </div>
                                    <div class="col-12">
                                        <?php
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_devolne_par.php')) > 0) {
                                            ?>
                                            <a href="principal4.php?page=ventas3_devolne_par&mod=1" class="text-light">Devolución NE Parcial</a>
                                            <br>
                                            <?php 
                                        } ?>
                                    </div>
                                </div>

                                <?php 
                                $arr_modulos_ventas_imp = array('ventas3_ne_pdf.php','ventas3_devolne_pdf.php');
                                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas_imp)) > 0) {
                                    ?>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <?php
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_ne_pdf.php')) > 0) {
                                                ?>
                                                <a onclick="mostrar('C')" class="text-light">Imprimir NE</a>
                                                <br>
                                                <?php 
                                            } ?>
                                        </div>
                                        <div class="col-12">
                                            <?php 
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_devolne_pdf.php')) > 0) {
                                                ?>
                                                <a onclick="mostrar('D')" class="text-light">Imprimir Devolución NE</a>
                                                <?php 
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>

                <?php 
                $arr_modulos_ventas = array('ventas3_ped.php','ventas3_ped_pdf.php','ventas3_ped_eliminar.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-danger pb-4">
                            <div class="inner">
                                <h3>Pedidos</h3>
                                <div class="row">
                                    <div class="col-12">
                                        <?php 
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_ped.php')) > 0) {
                                            ?>
                                            <a href="principal4.php?page=ventas3_ped&mod=1" class="text-light">Emisión Pedido</a>
                                            <?php 
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <?php 
                                $arr_modulos_ventas_imp = array('ventas3_ped_pdf.php','ventas3_ped_eliminar.php');
                                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas_imp)) > 0) {
                                    ?>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <?php
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_ped_pdf.php')) > 0) {
                                                ?>
                                                <a onclick="mostrar('E')" class="text-light">Imprimir Pedido</a>
                                                <br>
                                                <?php 
                                            } ?>
                                        </div>
                                        <div class="col-12">
                                            <?php 
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_ped_eliminar.php')) > 0) {
                                                ?>
                                                <a onclick="eliminar('E')" class="text-light">Borrar Pedido</a>
                                                <?php 
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>

                <?php 
                $arr_modulos_ventas = array('ventas3_presu.php','ventas3_presu_pdf.php','ventas3_presu_eliminar.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-gray pb-4">
                            <div class="inner">
                                <h3>Presupuesto</h3>
                                <div class="row">
                                    <dis class="col-12">
                                        <?php 
                                        if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_presu.php')) > 0) {
                                            ?>
                                            <a href="principal4.php?page=ventas3_presu&mod=1" class="text-light">Emisión Presupuesto</a>
                                            <?php 
                                        }
                                        ?>
                                    </dis>
                                </div>

                                <?php 
                                $arr_modulos_ventas_imp = array('ventas3_presu_pdf.php','ventas3_presu_eliminar.php');
                                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas_imp)) > 0) {
                                    ?>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <?php
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_presu_pdf.php')) > 0) {
                                                ?>
                                                <a onclick="mostrar('F')" class="text-light">Imprimir Presupuesto</a>
                                                <br>
                                                <?php 
                                            } ?>
                                        </div>
                                        <div class="col-12">
                                            <?php 
                                            if (count(Permisos::verficarPermisoPorSessionUsuario('ventas3_presu_eliminar.php')) > 0) {
                                                ?>
                                                <a onclick="eliminar('F')" class="text-light">Borrar Presupuesto</a>
                                                <?php 
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                } 
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>
                
            </div>
        </div>
    </div>
</div>


<!-- MODAL IMPRIMIR -->
<div class="modal fade" id="imprimirModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #084f8a;color: white;">
                <h4 id="imp_title" class="modal-title">IMPRIMIR</h4>
                <button type="button" onclick="cerrarModalImp()" class="btn btn-sm bg-light float-right">
                    Cerrar
                </button>
            </div>
            <form id="imp_form" method="post">
                <div class="modal-body">
                    <diw class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="tipo_doc">Tipo Documento</label>
                                <input type="text" class="form-control form-control-sm" id="tipo_doc" name="tipo_doc" placeholder="Tipo Documento" disabled="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sucursal_input">Sucursal</label>
                                <input type="text" class="form-control form-control-sm" id="sucursal_input" name="sucursal_input" placeholder="Sucursal" value="" disabled="">
                            </div>
                        </div>
                    </diw>
                    <div class="form-group">
                        <label for="numerod_input">Documento</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="numerod_input" name="numerod_input" placeholder="Ingrese Número de documento" onkeypress="return enterKeyPressed(this,event)">
                            <span class="input-group-append">
                                <button id="btn_buscar_doc" type="button" class="btn btn-saint btn-flat">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div id="alert_error" class="alert alert-default-danger alert-dismissible">
                        Documento no encontrado
                    </div>
                    <div class="form-group">
                        <label for="rs_input">Razón Social</label>
                        <input type="text" class="form-control form-control-sm" id="rs_input" name="rs_input" placeholder="" disabled="">
                    </div>
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="tipo" name="tipo" value=""/>
                    <input type="hidden" id="nrounico" name="nrounico" value=""/>
                    <button id="btn_modal_imp_aceptar" type="button" name="action" class="btn btn-outline-saint float-left">
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CARGAR DOCUMENTO IMPRIMIR -->
<div class="modal fade" id="documentosModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #084f8a;color: white;">
                <h4 id="title_doc" class="modal-title">DOCUMENTOS </h4>
                <button type="button" onclick="cerrarModalBuscarDoc()" class="btn btn-sm bg-light float-right">
                    Cerrar
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
                                    <th class="small align-middle">Opciones</th>
                                    <th class="small align-middle">Nro Doc.</th>
                                    <th class="small align-middle">Razón Social</th>
                                    <th class="small align-middle">Emisión</th>
                                    <th class="small align-middle">Total Bs</th>
                                    <th class="small align-middle">Total $</th>
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

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #084f8a;color: white;">
                <h4 id="eliminar_title" class="modal-title">BORRAR</h4>
                <button type="button" onclick="cerrarModalBorrar()" class="btn btn-sm bg-light float-right">
                    Cerrar
                </button>
            </div>
            <form id="imp_form" method="post">
                <div class="modal-body">
                    <diw class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="sucursal1_input">Sucursal</label>
                                <input type="text" class="form-control form-control-sm" id="sucursal1_input" name="sucursal1_input" placeholder="Sucursal" value="" disabled="">
                            </div>
                        </div>
                    </diw>
                    <div class="form-group">
                        <label for="numerod1_input">Documento</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="numerod1_input" name="numerod1_input" placeholder="Ingrese Número de documento" onkeypress="return enterKeyPressed1(this,event)">
                            <span class="input-group-append">
                                <button id="btn_buscar_doc1" type="button" class="btn btn-saint btn-flat">
                                    <i class="fas fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div id="alert_error1" class="alert alert-default-danger alert-dismissible">
                        Documento no encontrado
                    </div>
                    <div class="form-group">
                        <label for="rs1_input">Razón Social</label>
                        <input type="text" class="form-control form-control-sm" id="rs1_input" name="rs1_input" placeholder="" disabled="">
                    </div>
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="tipo1" name="tipo" value=""/>
                    <input type="hidden" id="nrounico1" name="nrounico" value=""/>
                    <button id="btn_modal_borrar_aceptar" type="button" name="action" class="btn btn-outline-saint float-left">
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CARGAR DOCUMENTO ELIMINAR -->
<div class="modal fade" id="documentos1Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #084f8a;color: white;">
                <h4 id="title_doc1" class="modal-title">DOCUMENTOS </h4>
                <button type="button" onclick="cerrarModalBuscarDocBorrar()" class="btn btn-sm bg-light float-right">
                    Cerrar
                </button>
            </div>
            <div class="modal-body">
                <div class="row pl-2 mb-2">
                    <div class="col-8"></div>
                    <div class="col-4 float-right">
                        <input id="buscaDoc1" type="text" class="form-control text-left" placeholder="Buscar">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="documentos1_data" class="table table-sm table-bordered table-striped text-center">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <th class="small align-middle">Opciones</th>
                                    <th class="small align-middle">Nro Doc.</th>
                                    <th class="small align-middle">Razón Social</th>
                                    <th class="small align-middle">Emisión</th>
                                    <th class="small align-middle">Total Bs</th>
                                    <th class="small align-middle">Total $</th>
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
<?php include("footer3.php"); ?>
<script type="text/javascript" src="ventas3_index.js"></script>