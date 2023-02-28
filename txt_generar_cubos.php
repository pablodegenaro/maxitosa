<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-6">
                    <h2 class="ml-3">Generador de Cubos Diageo</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="card card-saint"  id="tabla">
                        <div class="card-header">
                            <h3 class="card-title">Generar Cubos Diageo</h3>
                        </div>
                        <div class="card-body" style="width:auto;">
                            <div class="form-check-inline">
                                <form action="txt_generar_cubos_ver.php" method="POST" target="_blank">
                                    <div class="form-check form-check-inline">
                                        <input type="date" class="form-control col-sm-10"  id="fechai" name="fechai" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                       <input type="date" class="form-control col-sm-10"  id="fechaf" name="fechaf" required>
                                   </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   <div class="form-check form-check-inline">
                                    <select class="form-control custom-select" name="cubo" id="cubo" style="width: 100%;" required>
                                        <option value="">Seleccione un Cubo</option>
                                        <option value="1">Almacenes</option>
                                        <option value="2">Clasificacion Clientes</option>
                                        <option value="3">Clasificacion Productos</option>
                                        <option value="4">Clasificacion de Territorio</option>
                                        <option value="5">Clientes</option>                                       
                                        <option value="7">Existencias</option>                                        
                                        <option value="9">Productos</option>
                                        <option value="10">Supervisores</option>
                                        <option value="11">Vendedores</option>
                                        <option value="12">Ventas</option>
                                    </select>
                                </div> <br><br>
                                <button type="submit" class="btn btn-primary btn-sm">Generar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php require_once("footer.php");?>
<script type="text/javascript" src="txt_generar.js"></script>
