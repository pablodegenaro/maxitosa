<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');

require("conexion.php");

$moneda = $_SESSION['moneda']; 
$fecha = $_SESSION['fecha'];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];

/** **************************************************************************** */
/** MUESTRA LA INFORMACION DETALLADA DEL EFECTIVO INGRESADO EN DISTINTAS MONEDAS */
/** **************************************************************************** */

$dataBS = array();
$dataDL = array();
$dataEU = array();
$sum = 0;
$tipo_documento = array(
    "I" => "Ingreso",
    "E" => "Retiro"
);

$saldoAnterior = "SELECT total_saldo = sum(debe) - sum(haber) FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) < CAST('$fecha' AS DATE) AND moneda = '$moneda' AND codsucu = '$codsucu'";
$stmtSaldoAnterior = mssql_query($saldoAnterior);
if(mssql_num_rows($stmtSaldoAnterior) != 0){
    while($row = mssql_fetch_array($stmtSaldoAnterior)){
        $sum = $row['total_saldo'];
    }
}

$queryBS = "SELECT otros, fecha, codVendedor,codCliente,nombCliente,factura,correlativo,id,
CASE moneda WHEN 'BS' THEN 'Bolivares' END as moneda, monto,debe,haber,saldo,observacion,tipo_pago,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo
FROM SAREC_EFECTIVO WHERE moneda = 'BS' AND CAST(fecha AS DATE) = '$fecha' AND codsucu = '$codsucu' order by correlativo";
$stmt = mssql_query($queryBS);
if(mssql_num_rows($stmt) != 0){
    while($row = mssql_fetch_array($stmt)){
        $row['factura'] = strval($row['factura']);
        $dataBS[] = $row;
    }
}
$queryDL = "SELECT otros, fecha,codVendedor,codCliente,nombCliente,factura,correlativo,id,
CASE moneda WHEN 'DL' THEN 'Dolares' END as moneda, monto,debe,haber,saldo,observacion,tipo_pago,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo
FROM SAREC_EFECTIVO WHERE moneda = 'DL' AND CAST(fecha AS DATE) = '$fecha' AND codsucu = '$codsucu' order by correlativo";
$stmt = mssql_query($queryDL);
if(mssql_num_rows($stmt) != 0){
    while($row = mssql_fetch_array($stmt)){
        $dataDL[] = $row;
    }
}

$queryEU = "SELECT otros, fecha,codVendedor,codCliente,nombCliente,factura,correlativo,id,
CASE moneda WHEN 'EU' THEN 'Euros' END as moneda, monto,debe,haber,saldo,observacion,tipo_pago,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo
FROM SAREC_EFECTIVO WHERE moneda = 'EU' AND CAST(fecha AS DATE) = '$fecha' AND codsucu = '$codsucu' order by correlativo";
$stmt = mssql_query($queryEU); 
if(mssql_num_rows($stmt) != 0){
    while($row = mssql_fetch_array($stmt)){
        $dataEU[] = $row;
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
            <?php if($moneda == 'BS'):?>  
                <div class="card card-saint">
                    <div class="card-header">
                        <div class="card-title">Bolivares</div>
                        <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                    </div>  


                    <div class="card-body">
                        <table  id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td><strong>Correl</strong></td>
                                    <td><strong>Fecha</strong></td>
                                    <td><strong>Vendedor</strong></td>
                                    <td><strong>Cliente</strong></td>
                                    <td><strong>Foraneo</strong></td>
                                    <td><strong>Factura</strong></td>
                                    <td><strong>Tipo</strong></td>
                                    <td><strong>Moneda</strong></td>
                                    <td><strong>Debe</strong></td>
                                    <td><strong>Haber</strong></td>
                                    <td><strong>Saldo</strong></td>
                                    <td><strong>Observacion</strong></td>
                                    <td><strong>Opcion</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataBS as $item):?>
                                    <tr>
                                        <td><?php echo $item["correlativo"]?></td>
                                        <td><?php echo date('d/m/Y',strtotime($item["fecha"]))?></td>
                                        <td><?php echo $item["codVendedor"]?></td>
                                        <td><?php echo $item["codCliente"]?></td>
                                        <td><?php echo $item["otros"]?></td>
                                        <td><?php echo $item["factura"]?></td>
                                        <td><?php echo $item["tipo"];?></td>
                                        <td><?php echo $item["moneda"]?></td>
                                        <td><?php echo $item["debe"]?></td>  
                                        <td><?php echo $item["haber"]?></td>   
                                        <td>
                                            <?php if($item['tipo'] == 'ANULADO'){ echo 0;}
                                            if($item['tipo'] == 'INGRESO'){$sum = $sum + $item['debe']; echo number_format($sum,2);}
                                            if($item['tipo'] == 'EGRESO'){$sum = $sum - $item['haber']; echo number_format($sum,2);}
                                            ?>
                                        </td>       
                                        <td><?php echo $item["observacion"]?></td> 
                                        <td>
                                            <?php if($item['tipo'] == 'ANULADO'):?>
                                                <span></span>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=editar('$item[factura]','$item[correlativo]')"?> data-toggle="modal" data-target="#exampleModal">Editar</button>
                                                <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=anular('$item[factura]','$item[correlativo]')"?>>Anular</button>
                                            <?php endif;?>
                                        </td>                                              
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>  
                    </div>   
                    
                </div> 
            <?php endif;?>
            <?php if($moneda == 'DL'):?>
                <div class="card card-saint">
                    <div class="card-header">
                        <div class="card-title">Dolares</div>
                        <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                    </div>       
                    <div class="card-body">
                        <table  id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td><strong>Correl</strong></td>
                                    <td><strong>Fecha</strong></td>
                                    <td><strong>Vendedor</strong></td>
                                    <td><strong>Cliente</strong></td>
                                    <td><strong>Foraneo</strong></td>
                                    <td><strong>Factura</strong></td>
                                    <td><strong>Tipo</strong></td>
                                    <td><strong>Moneda</strong></td>
                                    <td><strong>Debe</strong></td>
                                    <td><strong>Haber</strong></td>
                                    <td><strong>Saldo</strong></td>
                                    <td><strong>Observacion</strong></td>
                                    <td><strong>Opcion</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataDL as $item):?>
                                    <tr>
                                        <td><?php echo $item["correlativo"]?></td>
                                        <td><?php echo date('d/m/Y',strtotime($item["fecha"]))?></td>
                                        <td><?php echo $item["codVendedor"]?></td>
                                        <td><?php echo $item["codCliente"]?></td>
                                        <td><?php echo $item["otros"]?></td>
                                        <td><?php echo $item["factura"]?></td>
                                        <td><?php echo $item["tipo"];?></td>
                                        <td><?php echo $item["moneda"]?></td>
                                        <td><?php echo $item["debe"]?></td>  
                                        <td><?php echo $item["haber"]?></td>   
                                        <td>
                                            <?php if($item['tipo'] == 'ANULADO'){ echo 0;}
                                            if($item['tipo'] == 'INGRESO'){$sum = $sum + $item['debe']; echo number_format($sum,2);}
                                            if($item['tipo'] == 'EGRESO'){$sum = $sum - $item['haber']; echo number_format($sum,2);}
                                            ?>
                                        </td>    
                                        <td><?php echo $item["observacion"]?></td> 
                                        <td>
                                            <?php if($item['tipo'] == 'ANULADO'):?>
                                                <span></span>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=editar('$item[factura]')"?> data-toggle="modal" data-target="#exampleModal">Editar</button>
                                                <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=anular('$item[factura]')"?>>Anular</button>
                                            <?php endif;?>
                                        </td>                                                                
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>   
                </div> 
            <?php endif;?>
            <?php if($moneda == 'EU'):?>
                <div class="card card-saint">
                    <div class="card-header">
                        <h3 class="card-title">Euros</h3>
                        <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>

                    </div>       
                    <div class="card-body">
                        <table  id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td><strong>Correl</strong></td>
                                    <td><strong>Fecha</strong></td>
                                    <td><strong>Vendedor</strong></td>
                                    <td><strong>Cliente</strong></td>
                                    <td><strong>Foraneo</strong></td>
                                    <td><strong>Factura</strong></td>
                                    <td><strong>Tipo</strong></td>
                                    <td><strong>Moneda</strong></td>
                                    <td><strong>Debe</strong></td>
                                    <td><strong>Haber</strong></td>
                                    <td><strong>Saldo</strong></td>
                                    <td><strong>Observacion</strong></td>
                                    <td><strong>Opcion</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataEU as $item):?>
                                    <tr>
                                        <td><?php echo $item["correlativo"]?></td>
                                        <td><?php echo date('d/m/Y',strtotime($item["fecha"]))?></td>
                                        <td><?php echo $item["codVendedor"]?></td>
                                        <td><?php echo $item["codCliente"]?></td>
                                        <td><?php echo $item["otros"]?></td>
                                        <td><?php echo $item["factura"]?></td>
                                        <td><?php echo $item["tipo"];?></td>
                                        <td><?php echo $item["moneda"]?></td>
                                        <td><?php echo $item["debe"]?></td>  
                                        <td><?php echo $item["haber"]?></td>   
                                        <td>
                                            <?php if($item['tipo'] == 'ANULADO'){ echo 0;}
                                            if($item['tipo'] == 'INGRESO'){$sum = $sum + $item['debe']; echo number_format($sum,2);}
                                            if($item['tipo'] == 'EGRESO'){$sum = $sum - $item['haber']; echo number_format($sum,2);}
                                            ?>
                                        </td>       
                                        <td><?php echo $item["observacion"]?></td> 
                                        <td>
                                            <?php if($item['tipo'] == 'ANULADO'):?>
                                                <span></span>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=editar('$item[factura]')"?> data-toggle="modal" data-target="#exampleModal">Editar</button>
                                                <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=anular('$item[factura]')"?>>Anular</button>
                                            <?php endif;?>
                                        </td>                                                                
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>   
                </div> 
            <?php endif?>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form class="form-horizontal" id="edit_form">
                    <div class="modal-content p-0">
                        <div class="modal-header">
                            <div class="modal-title" id="titulo"></div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <input type="text" hidden name="correlativo" id="correlativo1">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="">Tipo de Transaccion</label>
                                                <select name="tipo_doc" id="tipo_doc"  class="form-control custom-select">
                                                    <?php foreach($tipo_documento as $key=>$value):?>
                                                        <option <? echo "value=".$key.""?>><?php echo $value; ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <div id="vendedor1">
                                                    <label for="">Vendedor</label>
                                                    <select id="vendedor" name="vendedor" class="form-control select2 text-center" style="width: 100%;">
                                                        <option value="">-- Seleccione Vendedor --</option>
                                                        <?php
                                                        $query = mssql_query("SELECT CodVend, Descrip FROM SAVEND order by CodVend ASC");
                                                        for ($j = 0; $j < mssql_num_rows($query); $j++) {
                                                            $codVendedor =  mssql_result($query, $j, "CodVend");
                                                            ?>
                                                            <option value="<?= $codVendedor; ?>" <?php if($_COOKIE['CodVend'] == $codVendedor) { echo 'selected'; } ?>>
                                                                <?= mssql_result($query, $j, "CodVend")?> : <?= utf8_encode(mssql_result($query, $j, "Descrip")); ?>
                                                            </option>
                                                            <?php
                                                        } ?>
                                                    </select>
                                                    <span id="errorVendedor"></span>
                                                </div>
                                                <div id="foraneo">
                                                    <label for="">Foraneo</label>
                                                    <input type="text" name="otros" id="otros" class="form-control" placeholder="Foraneo..">       
                                                    <span id="errorOtros"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="">Total a Pagar</label>
                                                <input type="text" class="form-control font-weight-bold" name="monto" id="monto">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-sm-4" id="cliente1">
                                                <label for="">Cliente</label>
                                                <select id="cliente" name="cliente" class="form-control select2 text-center" style="width: 100%;">
                                                    <option value="">-- Seleccione Cliente --</option>
                                                    <?php
                                                    $query = mssql_query("SELECT CodClie, Descrip FROM SACLIE");
                                                    for ($j = 0; $j < mssql_num_rows($query); $j++) {
                                                        $codcliente =  mssql_result($query, $j, "CodClie");
                                                        ?>
                                                        <option value="<?= $codcliente; ?>" <?php if($_COOKIE['CodClie'] == $codcliente) { echo 'selected'; } ?>>
                                                            <?= mssql_result($query, $j, "CodClie")?> : <?= utf8_encode(mssql_result($query, $j, "Descrip")); ?>
                                                        </option>
                                                        <?php
                                                    } ?>
                                                </select>
                                                <span id="errorCliente"></span>
                                            </div>
                                            <div class="col-sm-4">
                                                <label for="">Documento</label>
                                                <input type="text"  name="factura1" id="factura1" class="form-control font-weight-bold">       
                                            </div> 
                                            <div class="row mt-2">

                                                <input type="text" hidden name="moneda" id="moneda" class="form-control font-weight-bold">                                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mt-2">
                                            <div class="col-sm-12">
                                                <label for="">Observacion</label>
                                                <textarea name="observacion" id="observacion" class="form-control" cols="30" rows="2" placeholder="Observacion..." style="resize:none;"></textarea>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <div class="col-md-12">          
                                    <label for="" class="mt-2">Ingrese la cantidad de Billetes</label>                
                                    <div id="form_bs"> 
                                        <div class="row">
                                            <div class="col-sm-2">    
                                                <label for="">Cant. 100 Bs</label>                                  
                                                <input type="text" name="denom[]" id="denom_1" class="form-control" >
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 50 Bs</label>                                     
                                                <input type="text" name="denom[]" id="denom_2" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="">Cant. 20 Bs</label>                                      
                                                <input type="text" name="denom[]" id="denom_3" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="">Cant. 10 Bs</label>                                      
                                                <input type="text" name="denom[]" id="denom_4" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="">Cant. 5 Bs</label>                                      
                                                <input type="text" name="denom[]" id="denom_5" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 1 Bs</label>                                     
                                                <input type="text" name="denom[]" id="denom_6" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="">Cant. 0.5 Bs</label>                                      
                                                <input type="text" name="denom[]" id="denom_7" class="form-control">
                                            </div>
                                        </div>    
                                    </div>
                                    <div id="form_dl">
                                        <div class="row mt-1 mb-1">
                                            <div class="col-sm-2">
                                                <label for="">Cant. 100 $</label>                                      
                                                <input type="text" name="denom[]" id="denom_8" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="">Cant. 50 $</label>                                       
                                                <input type="text" name="denom[]" id="denom_9" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 20 $</label>                                      
                                                <input type="text" name="denom[]" id="denom_10" class="form-control">
                                            </div>
                                            <div class="col-sm-2">     
                                                <label for="">Cant. 10 $</label>                                  
                                                <input type="text" name="denom[]" id="denom_11" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 5 $</label>                                      
                                                <input type="text" name="denom[]" id="denom_12" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 2 $</label>                                      
                                                <input type="text" name="denom[]" id="denom_13" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="">Cant. 1 $</label>                                       
                                                <input type="text" name="denom[]" id="denom_14" class="form-control">
                                            </div>
                                        </div>
                                    </div>                            
                                    <div id="form_eu">
                                        <div class="row mt-1">
                                            <div class="col-sm-2">  
                                                <label for="">Cant. 500 €</label>                                     
                                                <input type="text" name="denom[]" id="denom_15" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 200 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_16" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 100 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_17" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 50 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_18" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 20 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_19" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 10 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_20" class="form-control">
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 5 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_21" class="form-control">
                                            </div>
                                        </div>
                                    </div>                    
                                    <div class="mt-2 col-md-12">
                                        <table id="editTablaBS" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                            <thead style="background-color: #00137f;color: white;">
                                                <tr>
                                                    <td width="10%"><strong>Denominacion</strong></td>
                                                    <td width="13%">100 Bs</td>
                                                    <td>50 Bs</td>
                                                    <td>20 Bs</td>
                                                    <td>10 Bs</td>
                                                    <td>5 Bs</td>
                                                    <td>1 Bs</td>
                                                    <td width="13%">0.5 Bs</td>
                                                    <td>Total</td>                       
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <tr><td><strong>Bolivares</strong></td>
                                                </tbody>     
                                            </table>      
                                            <table id="editTablaDL" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                                <thead style="background-color: #00137f;color: white;">
                                                    <tr>
                                                        <td width="10%"><strong>Denominacion</strong></td>
                                                        <td>100 $</td>
                                                        <td>50 $</td>
                                                        <td>20 $</td>
                                                        <td>10 $</td>
                                                        <td>5 $</td>
                                                        <td>2 $</td>
                                                        <td>1 $</td>
                                                        <td>Total</td> 
                                                    </tr>
                                                </thead> 
                                                <tbody ><tr><td><strong>Dolares</strong></td></tbody>     
                                                </table>
                                                <table id="editTablaEU" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                                    <thead style="background-color: #00137f;color: white;">
                                                        <tr>
                                                            <td width="10%"><strong>Denominacion</strong></td>
                                                            <td>500 €</td>
                                                            <td>200 €</td>
                                                            <td>100 €</td>
                                                            <td>50 €</td>
                                                            <td>20 €</td>
                                                            <td>10 €</td>
                                                            <td>5 €</td>
                                                            <td>Total</td> 
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <tr><td><strong>Euros</strong></td>
                                                        </tbody>     
                                                    </table>               
                                                </div>
                                            </div>         

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-saint float-left">Procesar</button>
                                        <button type="button" onclick="ocultar()" class="btn btn-sm btn-outline-saint float-right" data-dismiss="modal">Cancelar</button>                
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section> 
                </div>
                <?php include "footer.php"; ?>
                <script src="Icons.js" type="text/javascript"></script>
                <script src="recepcion_efectivo_detalle.js"></script>
