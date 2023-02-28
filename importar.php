<div class="content-wrapper">
    <!-- BOX DE LA MIGA DE PAN -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Ultima Activacion</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- BOX DEL CONTENIDO DE LA VISTA FORMULARIO Y TABLA -->
    <section class="content">
        <div class="col-md-12">
            <div class="card card-saint">
                <script type="text/javascript">
                    function guarda(){
                        if ( $("#archivo")[0].files.length > 0 ){
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
                    <h3 class="card-title">Importar Excel</h3>
                </div>
                <form role="form" class="form-horizontal" action="importar_procesa.php" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <label>Elija un archivo (xls)</label>
                            <div class="input-group">
                                <input type="file" id="archivo" name="archivo" accept=".xls,.xlsx" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="submit" name="Submit" onclick="guarda()" class="btn btn-saint">Procesar</button>
                        <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
