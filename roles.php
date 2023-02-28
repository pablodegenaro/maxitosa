
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <button class="btn btn-outline-saint" id="add_button" onclick="limpiar()" data-toggle="modal" data-target="#rolModal"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Rol</button>
                    <hr>
                    <div class="card card-saint">
                        <div class="card-header">
                            <h3 class="card-title">Roles Registrados</h3><!-- overflow:scroll; -->
                        </div>
                        <div class="card-body" style="width:auto;">
                            <table class="table table-sm table-hover table-condensed table-bordered table-striped text-center" style="width:100%;" id="roles_data">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <th class="text-center" title="ID">ID</th>
                                        <th class="text-center" title="Rol de Usuario">Rol de Usuario</th>
                                        <th class="text-center" title="Permisos">Permisos</th>
                                        <th class="text-center" title="Opciones">Opciones</th>
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
    <div class="modal fade"  id="rolModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #084f8a;color: white;">
                    <h5 class="modal-title">Agregar Choferes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="rol_form">
                        <label>Descripción</label>
                        <input type="text" class="form-control input-sm" maxlength="30" id="rol" name="rol" placeholder="Ingrese la descripción del ROL" required >
                        <br />

                        <div class="modal-footer">
                            <input type="hidden" name="id_rol" id="id_rol"/>
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
<script type="text/javascript" src="roles.js"></script>
