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
                        <li class="breadcrumb-item active">Form Retenciones</li>
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
                        if (document.getElementById("rete").value != "" ){
                                /* document.forms["registro_usuarios"].submit();*/
                        }else{
                            alert("Debe Rellenar Todos Los Campos");
                        }
                    }
                    function regresa(){
                        window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                    }
                </script>
                <div class="card-header">
                    <h3 class="card-title">Form Retenciones</h3>
                </div>
                <form class="form-horizontal" action="principal1.php?page=paseretenciones_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                     <?php
                     if (isset($_SESSION['mensaje'])) {
                        ?>
                        <div class="alert alert-default-<?= $_SESSION['bg_mensaje'];?> alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas <?= $_SESSION['icono'];?>"></i> Atención!</h5>
                            <?= $_SESSION['mensaje'];?>
                        </div>
                        <?php
                        unset($_SESSION['bg_mensaje']);
                        unset($_SESSION['icono']);
                        unset($_SESSION['mensaje']);
                    }
                    ?>                                   
                    <div class="form-check form-check-inline">
                        <label for="edv" class="col-form-label col-sm-6"></label>
                        <select class="form-control custom-select" name="rete" id="rete" style="width: 100%;" required>
                            <option value="">Seleccione una Opcion</option>
                            <option value="1">Activar para Imprimir (Desactiva para Consolidacion)</option>
                            <option value="0">Desactiva para Imprimir (Activa para Consolidacion)</option>
                        </select>
                    </div>                
                </div>
                <div class="card-footer">
                    <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Procesar</button>
                    <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
