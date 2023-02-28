<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Lista de Operadores</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=taller_index&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Lista de Operadores</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right pr-3">
                    <button class="btn btn-outline-saint" id="add_button" onclick="mostrar()" data-toggle="modal" data-target="#moduloModal"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Operador</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="card card-saint">
                        <div class="card-header">
                            <h3 class="card-title">Usuarios Operadores</h3>
                        </div>
                        <div class="card-body" style="width:auto;">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-sm table-hover table-condensed table-bordered table-striped text-center" style="width:100%;" id="operador_data">
                                        <thead style="background-color: #00137f;color: white;">
                                            <tr>
                                                <td class="text-center" title="Activo" style="width: 10%">Activo</td>
                                                <td class="text-center" title="Usuario">Usuario</td>
                                                <td class="text-center" title="Cédula">Cédula</td>
                                                <td class="text-center" title="Nombre">Nombre</td>
                                                <td class="text-center" title="Opciones">Opciones</td>
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
        </div>
    </section>

    <!-- MODAL EDITAR OPERADOR -->
    <div class="modal fade"  id="operadorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Operador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="operador_form">
                        <br />
                        <label for="rol">Usuario</label>
                        <select class="form-control custom-select" name="user" id="user" style="width: 100%;" required>
                            <option value="">-- Seleccione cliente --</option>
                            <?php
                            $ssusrs = mssql_query("SELECT codusua, descrip FROM SSUSRS WHERE CodUsua NOT IN (SELECT usuario FROM TLOPER) ORDER BY codusua");
                            for ($j = 0; $j < mssql_num_rows($ssusrs); $j++) { ?>
                                <option value="<?php echo mssql_result($ssusrs, $j, "codusua");?>">
                                    <?php echo mssql_result($ssusrs, $j, "codusua")." : ".utf8_encode(mssql_result($ssusrs, $j, "descrip"));?>
                                    </option><?php
                                } ?>
                            </select>

                            <label for="login">Nombre de Usuario</label>
                            <input type="text" class="form-control input-sm" maxlength="30" id="login" name="login" placeholder="Ingrese nombre de usuario" >
                            <br />
                            <label for="rol">Rol de Usuario</label>
                            <select class="form-control custom-select" name="rol" id="rol" style="width: 100%;" required>
                                <!-- la lista de roles se carga por ajax -->
                            </select>
                            <br />
                            <div class="modal-footer">
                                <input type="hidden" name="id_ope" id="id_ope"/>
                                <button type="submit" name="action" id="btnGuardar" class="btn btn-success pull-left" value="Add">Guardar</button>
                                <button type="button" onclick="limpiar()" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("footer_taller.php");?>
    <script type="text/javascript" src="taller_operador.js"></script>
