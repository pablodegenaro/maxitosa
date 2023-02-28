<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2 mt-4 text-center">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-4">
                    <h1 class="m-0 text-dark">Gestión de Proyectos</h1>
                </div>
                <div class="col-sm-4">
                    <script type="text/javascript">
                        function regresa(){
                            window.location.href = "principalrs.php?page=rspermisos_index&mod=1";
                        }
                    </script>
                    <button type="button" onclick="regresa()" class="btn btn-outline-gray bg-gray">Volver atrás</button>
                </div>
                <div class="col-sm-2">
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-10 text-white text-sm">
                    se gestiona todos los módulos del sistema dependiendo de un menu
                </div>
                <div class="col-2 text-right">
                    <button class="btn btn-xs btn-info" id="add_proyecto_button" onclick="mostrar_proyecto()" data-toggle="modal" data-target="#proyectoModal"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Módulo</button>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <table class="table table-sm table-hover text-center" style="width:100%;" id="proyecto_data">
                        <thead style="background-color: #00137f;color: white;">
                            <tr>
                                <td class="text-center" title="#">#</td>
                                <td class="text-center" title="Nombre">Nombre</td>
                                <td class="text-center" title="Es Principal">Es Principal</td>
                                <td class="text-center" title="Acciónes">Acciónes</td>
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
<?php include("footerrs.php"); ?>
<script src="Icons.js" type="text/javascript"></script>
<script type="text/javascript" src="rspermisos_pro.js"></script>