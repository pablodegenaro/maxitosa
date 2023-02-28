<?php 
require("conexion.php");
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    unset($_SESSION['fecha']);
    unset($_SESSION['moneda']);
    $_SESSION['fecha'] = $_POST['fecha'];
    $_SESSION['moneda'] = $_POST['moneda'];     
}else{
    unset($_SESSION['fecha']);
    unset($_SESSION['moneda']);
}
$moneda = array(
    "BS" => "Bolivares",
    "DL" => "Dolares",
    "EU" => "Euros",
    "TD" => "Reporte de todas las Monedas"
);

?>
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
                        <li class="breadcrumb-item active">Caja</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="col-md-12">
            <div class="card card-saint">
                <div class="card-header">
                    <h3 class="card-title">Caja</h3>
                </div>
                <form id="form_recepcion">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="form-check form-check-inline">
                                        <input type="date" name="fecha" id="fecha" value="<?php echo $_SESSION['fecha']?>" class="form-control">                                          
                                    </div>
                                    <div class="form-check form-check-inline">                     
                                        <select name="moneda" id="moneda" class="form-control custom-select">
                                            <option value=""> -- Seleccione Moneda -- </option>
                                            <?php foreach($moneda as $key=>$value):?>
                                                <option <? echo "value=".$key."" ?> ><?php echo $value; ?></option>
                                            <?php endforeach;?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit"  class="btn btn-saint">Procesar</button>
                        <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
                    </div>
                </form>                                                      
            </div>
            <div id="principal">
                <div class="row">
                    <div class="col-sm-4">
                        <a href="principal1.php?page=recepcion_efectivo&mod=1">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="text-center">Caja del Dia</h5>
                                    </div>
                                </div>
                            </div>   
                        </a>                           
                    </div>
                    <div class="col-sm-4">
                        <a href="principal1.php?page=recepcion_efectivo_crear&mod=1">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="text-center">Ingreso o Retiro</h5>
                                    </div>
                                </div>
                            </div> 
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="principal1.php?page=recepcion_efectivo_detalle&mod=1">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="text-center">Movimientos</h5>
                                    </div>
                                </div>
                            </div>
                        </a>     
                    </div>                   
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <a href="principal1.php?page=recepcion_efectivo_bdenom&mod=1">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="text-center">Cantidad de Billetes</h5>
                                    </div>
                                </div>
                            </div>       
                        </a>                       
                    </div>
                    <div class="col-sm-4">
                        <a href="principal1.php?page=recepcion_efectivo_vuelto&mod=1">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="text-center">Vueltos Pendientes</h5>
                                    </div>
                                </div>
                            </div>
                        </a> 
                    </div> 
                    <div class="col-sm-4">
                        <a href="recepcion_efectivo_report.php">
                            <div class="card card-saint">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="text-center">Reporte del Dia</h5>
                                    </div>
                                </div>
                            </div>
                        </a> 
                    </div>                   
                </div>
            </div>
            <div id="reporte">
                <div class="col-sm-4">
                    <a href="recepcion_efectivo_reportTD.php">
                        <div class="card card-saint">
                            <div class="card-header">
                                <div class="card-body">
                                    <h5 class="text-center">Reporte de todas las Monedas</h5>
                                </div>
                            </div>
                        </div>
                    </a> 
                </div>
            </div>                                    
        </div>
    </section>
</div>

<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script src="recepcion_efectivo_principal.js"></script>
