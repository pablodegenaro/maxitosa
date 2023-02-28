<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 id="title_permisos">Gestión de IT</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Gestión de IT</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-saint card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="pt-2 px-3"><h3 class="card-title">Gestión</h3></li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tab-modulos" data-toggle="pill" href="#tab-modulos" role="tab" aria-controls="tab-modulos" aria-selected="true">
                                    Módulos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="false">
                                    Menús
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tab-dashboard" data-toggle="pill" href="#tab-dashboard" role="tab" aria-controls="tab-dashboard" aria-selected="false">
                                    Dashboards
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tab-permisos" data-toggle="pill" href="#tab-permisos" role="tab" aria-controls="tab-permisos" aria-selected="false">
                                    Permisos Masivo
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-modulos" role="tabpanel" aria-labelledby="custom-tab-modulos">
                                <div class="row">
                                    <div class="col-10 text-gray">
                                        se gestiona todos los módulos del sistema dependiendo de un menu
                                    </div>
                                    <div class="col-2 text-right">
                                        <button class="btn btn-outline-saint" id="add_modulo_button" onclick="mostrar_modulo()" data-toggle="modal" data-target="#moduloModal"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Módulo</button>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <table class="table table-sm table-hover table-condensed table-bordered table-striped text-center" style="width:100%;" id="modulo_data">
                                            <thead style="background-color: #00137f;color: white;">
                                                <tr>
                                                    <td class="text-center" title="Ruta">Ruta</td>
                                                    <td class="text-center" title="Menú">Menú</td>
                                                    <td class="text-center" title="Nombre">Nombre</td>
                                                    <td class="text-center" title="Icono">Icono</td>
                                                    <td class="text-center" title="Orden">Orden</td>
                                                    <td class="text-center" title="Acciónes">Acciónes</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-menu" role="tabpanel" aria-labelledby="custom-tab-menu">
                                <div class="row">
                                    <div class="col-10 text-gray">
                                        se gestiona todos los Menús del sistema
                                    </div>
                                    <div class="col-2 text-right">
                                        <button class="btn btn-outline-saint" id="add_modulo_button" onclick="mostrar_menu()" data-toggle="modal" data-target="#menuModal"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Menú</button>
                                    </div>
                                </div>
                                <div class="row mt-5 mb-5">
                                    <div class="col-12">
                                        <table class="table table-sm table-hover table-condensed table-bordered table-striped text-center" style="width:100%;" id="menu_data">
                                            <thead style="background-color: #00137f;color: white;">
                                                <tr>
                                                    <td class="text-center" title="Nombre menú">Nombre menú</td>
                                                    <td class="text-center" title="Icono">Icono</td>
                                                    <td class="text-center" title="Menú padre">Menú padre</td>
                                                    <td class="text-center" title="Proyecto">Proyecto</td>
                                                    <td class="text-center" title="Orden">Orden</td>
                                                    <td class="text-center" title="Opciones">Opciones</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-dashboard" role="tabpanel" aria-labelledby="custom-tab-dashboard">
                                <div class="row">
                                    <div class="col-10 text-gray">
                                        <p>
                                            se gestiona los Dashboards del sistema dependiendo del Rol
                                        </p>
                                        <p>
                                            se lista la seleccion de dashboard dependiendo del nombre del archivo dashboard_*.php en Módulos
                                        </p>

                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <table class="table table-sm table-hover table-condensed table-bordered table-striped text-center" style="width:100%;" id="dashboard_data">
                                            <thead style="background-color: #00137f;color: white;">
                                                <tr>
                                                    <td style="width: 10%" class="text-center" title="#">#</td>
                                                    <td style="width: 50%" class="text-center" title="Roles">Roles</td>
                                                    <td style="width: 40%" class="text-center" title="Dashboard">Dashboard</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-permisos" role="tabpanel" aria-labelledby="custom-tab-permisos">
                                <div class="row">
                                    <div class="col-2">
                                        <form action="gestionit_txt.php" method="POST" target="_blank">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-plus" aria-hidden="true"></i> Generar Archivo
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-10 text-gray">
                                        se gestiona todos los permisos como archivo de texto
                                    </div>
                                    <div class="col-2 text-right">
                                    </div>
                                </div>
                                <form id="permisos_form" method="post" enctype="multipart/form-data">
                                    <div class="form-group row mt-5">
                                        <label for="file" class="col-sm-2 col-form-label">Arhivo (.txt)</label>
                                        <div  class="col-sm-10">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="file" name="file" accept=".txt" required>
                                                <label id="label_file" class="custom-file-label" for="file">seleccione archivo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1">
                                        <label for="file" class="col-sm-2 col-form-label">Tipo Operación</label>
                                        <div  class="col-sm-10">
                                            <div class="form-check form-check-inline mt-1">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="customRadio1" value="1" name="opera" checked="">
                                                    <label for="customRadio1" class="custom-control-label">Actualización</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-5">
                                                    <input class="custom-control-input" type="radio" id="customRadio2" value="2" name="opera">
                                                    <label for="customRadio2" class="custom-control-label">Reemplazar</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <button id="cargar_button" type="submit" name="Submit" class="btn btn-primary">Ingresar Permisos</button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div class="modal fade"  id="moduloModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #084f8a;color: white;">
                    <h5 class="modal-title">Agregar Módulo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="modulo_form">
                        <div class="row">
                            <div class="col-9">
                                <label for="ruta">Archivo (.php)</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control input-sm" id="ruta" name="ruta" placeholder="Ingrese nombre del archivo" required >
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="orden_modulo">Orden</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control input-sm" id="orden_modulo" name="orden_modulo" placeholder="Orden" value="0" min="0" max="99" required >
                                </div>
                            </div>
                        </div>
                        <label for="nombre">Nombre</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control input-sm" maxlength="24" id="nombre" name="nombre" placeholder="Ingrese nombre del modulo" required >
                        </div>
                        <label for="icono">Icono del Módulo</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control input-sm" maxlength="50" id="icono" name="icono" placeholder="Si deja este campo vacío, se asignará un icono por default">
                            <div class="input-group-append">
                                <span class="input-group-text"><i id="icon" class=""></i></span>
                            </div>
                        </div>
                        <label for="menu_id">Menu (opcional)</label>
                        <div class="input-group mb-3">
                            <select class="form-control custom-select" id="menu_id" name="menu_id">
                                <option value="-1">--Seleccione--</option>
                            </select>
                        </div>
                        <label for="estado">Estado</label>
                        <div class="input-group mb-3">
                            <select class="form-control custom-select" id="estado" name="estado">
                                <option value="">Seleccione un Estado</option>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <br />
                        <div class="modal-footer">
                            <input type="hidden" name="id_modulo" id="id_modulo"/>
                            <button type="submit" name="action" id="btnGuardarModulo" class="btn btn-success pull-left" value="Add">Guardar</button>
                            <button type="button" onclick="limpiar_modulo()" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cerrar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade"  id="menuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #084f8a;color: white;">
                    <h5 class="modal-title">Agregar Menú</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="menu_form">
                        <div class="row">
                            <div class="col-9">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control input-sm" maxlength="30" id="nombre" name="nombre" placeholder="Ingrese nombre del menú" required >
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="orden">Orden menú</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control input-sm" id="orden" name="orden" placeholder="Orden" value="0" min="0" max="99" required >
                                </div>
                            </div>
                        </div>
                        <label for="menu_padre">Menú padre</label>
                        <div class="input-group mb-3">
                            <select class="form-control custom-select" id="menu_padre" name="menu_padre">
                                <!--Se carga por AJAX-->
                            </select>
                        </div>
                        <label for="icono">Icono del Menú</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control input-sm" maxlength="50" id="icono" name="icono" placeholder="Si deja este campo vacío, se asignará un icono por default">
                            <div class="input-group-append">
                                <span class="input-group-text"><i id="icon" class=""></i></span>
                            </div>
                        </div>
                        <label for="menu_padre">Menú Proyecto</label>
                        <div class="input-group mb-3">
                            <select class="form-control custom-select" id="menu_proyecto" name="menu_proyecto">
                                <option value="1" selected>Proyecto Principal</option>
                                <option value="2">Proyecto Taller</option>
                            </select>
                        </div>
                        <label for="estado">Estado</label>
                        <div class="input-group mb-3">
                            <select class="form-control custom-select" id="estado" name="estado">
                                <option value="">Seleccione un Estado</option>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <br />
                        <div class="modal-footer">
                            <input type="hidden" name="id_menu" id="id_menu"/>
                            <button type="submit" name="action" id="btnGuardarMenu" class="btn btn-success pull-left" value="Add">Guardar</button>
                            <button type="button" onclick="limpiar_menu()" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cerrar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script type="text/javascript" src="gestionit.js"></script>
<script type="text/javascript" src="gestionit_txt.js"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>
