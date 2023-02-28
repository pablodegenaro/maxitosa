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
                        <li class="breadcrumb-item active">KPI</li>
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
                        if (document.getElementById("fechai").value != "" && document.getElementById("fechaf").value != "" && document.getElementById("edv").value != "" ){
                                /* document.forms["registro_usuarios"].submit();*/
                        }else{
                            alert("Debe Rellenar Todos Los Campos");
                        }
                    }
                    function regresa(){
                        window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                    }
                    function isNumberKey(txt, evt) {
                        var charCode = (evt.which) ? evt.which : evt.keyCode;
                        if (charCode == 46) {
                                //Check if the text already contains the . character
                            if (txt.value.indexOf('.') === -1) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            if (charCode > 31 &&
                                (charCode < 48 || charCode > 57))
                                return false;
                        }
                        return true;
                    }
                </script>
                <div class="card-header">
                    <h3 class="card-title">KPI (Key Performance Indicator)</h3>
                </div>
                <form class="form-horizontal" action="principal.php?page=kpi_ver&mod=1" method="post" id="" name="">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <label>Seleccion</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-2"></label>
                                        <input type="date" class="form-control col-sm-10"  id="fechai" name="fechai" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-2"></label>
                                        <input type="date" class="form-control col-sm-10"  id="fechaf" name="fechaf" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4">d.habiles</label>
                                        <input type="number" class="form-control col-sm-8"  id="d_habiles" name="d_habiles" value="1" min="1" max="31"
                                        onkeypress="return isNumberKey(this, event)" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4">d.trans</label>
                                        <input type="number" class="form-control col-sm-8"  id="d_trans" name="d_trans" value="0" min="0" max="31"
                                        onkeypress="return isNumberKey(this, event)" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="Submit" onclick="guarda()" class="btn btn-saint">Procesar</button>
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
