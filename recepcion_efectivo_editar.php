<?php

require("conexion.php");

$codVendedor = $_POST["codVendedor"];
$factura = $_POST["factura"];
$moneda = $_POST["moneda"];
$id = $_POST["id"];
$vuelto = $_POST["vuelto"];
if($moneda == 'Bolivares'){
    $moneda = 'BS';
}
if($moneda == 'Dolares'){
    $moneda = 'DL';
}
if($moneda == 'Euros'){
    $moneda = 'EU';
}
$tipo_documento = array(
    "A"=>"Anular",
    "R"=>"Revertir"
);
$data = array();
$query = "SELECT 
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,id,
fecha,codVendedor,codCliente,factura,correlativo,observacion,otros,monto
FROM SAREC_EFECTIVO WHERE factura = '$factura' AND codVendedor = '$codVendedor' OR id ='$id' AND moneda = '$moneda'";
$stmt = mssql_query($query);
if(mssql_num_rows($stmt) != 0){
    while($row = mssql_fetch_array($stmt)){
        $id = $row["id"];
        $fecha = $row["fecha"];
        $codVendedor = $row["codVendedor"];
        $codCliente = $row["codCliente"];
        $factura = $row["factura"];
        $moneda = $row["moneda"];
        $correlativo = $row["correlativo"];
        $observacion = $row["observacion"];
        $otros = $row["otros"];
        $monto = $row["monto"];
    }
}

$queryTabla = "SELECT CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber <> 0) ,
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND haber <> 0),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND haber <> 0),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND haber <> 0),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND haber <> 0),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber <> 0),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe <> 0) - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND haber <> 0),
sum(debe) - sum(haber) as saldo
FROM SAREC_DENOM WHERE moneda ='$moneda' AND CAST(fecha AS DATE) = '$fecha' group by moneda";
$stmtTabla = mssql_query($queryTabla);
if(mssql_num_rows($stmtTabla)!=0){
    while($row = mssql_fetch_array($stmtTabla)){
        if( $row["total_100"] == NULL ||  $row["total_100"] == ''){
            $row["total_100"] = 0;
        }
        if( $row["total_50"] == NULL ||  $row["total_50"] == ''){
            $row["total_50"] = 0;
        }
        if( $row["total_20"] == NULL ||  $row["total_20"] == ''){
            $row["total_20"] = 0;
        }
        if( $row["total_10"] == NULL ||  $row["total_10"] == ''){
            $row["total_10"] = 0;
        }
        if( $row["total_5"] == NULL ||  $row["total_5"] == ''){
            $row["total_5"] = 0;
        }
        if( $row["total_2"] == NULL || $row["total_2"] == ''){
            $row["total_2"] = 0;
        }
        if( $row["total_1"] == NULL ||  $row["total_1"] == ''){
            $row["total_1"] = 0;
        }
        if( $row["saldo"] == NULL ||  $row["saldo"] == ''){
            $row["saldo"] = 0;
        }
        $data[] = $row;
    }
}

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
                    <h3 class="card-title">Caja <strong><?php echo date('d-m-Y',strtotime($fecha));?></strong></h3>
                    <div class="d-flex justify-content-end">
                        <form action="principal1.php?page=recepcion_efectivo_ver&mod=1" method="post">
                            <input type="date" value="<?php echo date('Y-m-d',strtotime($fecha));?>" hidden name="fecha">                          
                            <button type="submit" class="btn btn-saint mr-2">Ver Movimientos</button>
                            <input type="text" value="<?php echo $id; ?>" hidden name="id">
                            <input type="text" id="vuelto" value="<?php echo $vuelto; ?>" hidden name="vuelto">
                        </form>
                    </div>
                </div>
                <form class="form-horizontal" id="form_recepcion">
                    <div class="card-body">
                        <!-- Date -->
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <input type="date" readonly name="fecha" id="fecha" class="form-control font-weight-bold" value="<?php echo date('Y-m-d',strtotime($fecha))?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" readonly name="codVendedor" id="codVendedor" class="form-control font-weight-bold" value="<?php echo $codVendedor;?>">                                              
                                        <input type="text" readonly name="codClientev" id="codClientev" class="form-control font-weight-bold" value="<?php echo $codCliente;?>">                                             
                                        <input type="text" readonly name="otros" id="otros" class="form-control font-weight-bold"  value="<?php echo $otros?>">                                                   
                                    </div>

                                    <div class="col-sm-3">
                                        <input type="text" readonly name="codCliente" id="codCliente" class="form-control font-weight-bold" value="<?php echo $codCliente;?>">                                              
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <input type="text" readonly name="factura" id="factura" class="form-control font-weight-bold" value="<?php echo $factura;?>">       
                                        <span id="errorFactura"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" readonly name="correlativo" id="correlativo" class="form-control" value="<?php echo $correlativo; ?>">
                                        <span id="errorCorrelativo"></span>
                                    </div>
                                    <div class="col-sm-2">                                
                                        <input type="text" readonly name="moneda" id="moneda" class="form-control font-weight-bold" value="<?php echo $moneda; ?>">                                                   
                                    </div>
                                    <div class="col-sm-2">
                                        <select name="tipo_doc" id="tipo_doc"  class="form-control custom-select" onchange="ocultar()">
                                            <option value="">Tipo Documento</option>
                                            <?php foreach($tipo_documento as $key=>$value):?>
                                                <option <? echo "value=".$key.""?>><?php echo $value; ?></option>
                                            <?php endforeach;?>
                                        </select>

                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" readonly class="form-control" name="monto" id="monto" value="<?php echo $monto;?>">
                                    </div>                       
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row mt-2">
                                    <div class="col-sm-12">
                                        <textarea style="resize:none;"class="form-control" name="observacion"id="observacion" rows="3"><?php echo $observacion;?></textarea>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-saint">Procesar</button>
                        <button type="button" class="btn btn-outline-saint float-right" onclick="regresa()">Regresar</button>                
                    </div>
                </form>  
                <div class="card-body" id="tablaUpdate">
                    <table id="tablaRecepcion" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                        <thead style="background-color: #00137f;color: white;"></thead>
                    </table>
                </div>                             
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="col-md-12">
        <table id="tablaRecepcion" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
            <thead style="background-color: #00137f;color: white;"></thead>
        </table>
    </div>
</section>  
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script src="recepcion_efectivo_editar.js"></script>