
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Lista de Usuarios</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Lista de Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="card card-saint">
                        <div class="card-header">
                            <h3 class="card-title">Usuarios Registrados</h3>
                        </div>
                        <div class="card-body" style="width:auto;">
                            <table class="table table-sm table-hover table-condensed table-bordered table-striped text-center" style="width:100%;" id="usuario_data">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <td class="text-center" title="Acceso" style="width: 10%">Acceso</td>
                                        <td class="text-center" title="Usuario">Usuario</td>
                                        <td class="text-center" title="Nivel SAINT">Nivel SAINT</td>
                                        <td class="text-center" title="Permisos">Permisos</td>
                                        <td class="text-center" title="Rol">Rol</td>
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
    </section>
    <div class="modal fade"  id="usuarioModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="usuario_form">
                        <label for="login">Nombre de Usuario</label>
                        <input type="text" class="form-control input-sm" maxlength="30" id="login" name="login" placeholder="Ingrese nombre de usuario" >
                        <br />
                        <label for="rol">Rol de Usuario</label>
                        <select class="form-control custom-select" name="rol" id="rol" style="width: 100%;" required>
                            <!-- la lista de roles se carga por ajax -->
                        </select>
                        <br />
                        <div class="modal-footer">
                            <input type="hidden" name="id_usuario" id="id_usuario"/>
                            <button type="submit" name="action" id="btnGuardar" class="btn btn-success pull-left" value="Add">Guardar</button>
                            <button type="button" onclick="limpiar()" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once("footer.php");?>
<script type="text/javascript" src="usuario.js"></script>
