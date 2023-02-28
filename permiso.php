
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 id="title_permisos">Permisos</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!--                                <input id="btnGestion" type="button" class="btn btn-outline-primary mr-3" value="Gestión permisos" />-->
                        <input id="btnVolver"  type="button" class="btn btn-outline-saint" value="Volver" />
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card card-saint" id="tabla">
            <div class="card-header">
                <h3 class="card-title">Permisos</h3>
            </div>
            <div class="card-body" style="width:auto;">
                <form id="permisos_form">
                    <div class="row">
                        <div class="col">
                            <div class="form-group text-center">
                                <h3 class="">Seleccione los permisos a habilitar</h3>
                            </div>
                        </div>
                    </div>


                    <div id="permisos" class="mt-4">
                        <!--se cargan por ajax-->
                    </div>

                    <div class="text-left m-t-10">
                        <!--tipo 0 es roles, tipo 1 es usuarios-->
                        <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET['t'] ?>"/>
                        <input type="hidden" name="tipoid" id="tipoid" value="<?php echo $_GET['i'] ?>"/>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?php include "footer.php"; ?>
<script type="text/javascript" src="permiso.js"></script>