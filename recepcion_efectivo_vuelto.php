<?php 

session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require("conexion.php");
$fecha = $_SESSION['fecha'];
$date = date('d-m-Y');

$data = array();
$query = "  SELECT codVendedor,Descrip,CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END AS moneda,
saldo = sum(debe)-sum(haber)
FROM SAREC_VUELTOS  WHERE moneda = '".$_SESSION['moneda']."'  AND codsucu = '".$_SESSION['codsucu1']."'
GROUP BY codVendedor,Descrip,moneda";
$stmt = mssql_query($query);
if(mssql_num_rows($stmt) != 0){
    while($row = mssql_fetch_array($stmt)){
        $data[] = $row;
    }
}

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
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
                <script type="text/javascript">
                    function regresa(){
                        window.location.href = "principal1.php?page=recepcion_efectivo_principal&mod=1";
                    }
                </script>
                <div class="card-header">
                    <h3 class="card-title">Vueltos del Dia <stron><?php echo date('d-m-Y',strtotime($fecha))." "?></strong></h3>
                    <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                </div>       
                <div class="col-md-12">
                    <?php if(!$data):?>
                        <span></span>
                    <?php else:?>              
                        <div class="card-body">     
                            <h6>TABLA BOLIVARES</h6>   
                            <table  id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <td><strong>Codigo</strong></td>
                                        <td><strong>Nombre</strong></td>
                                        <td><strong>Moneda</strong></td>
                                        <td><strong>Saldo Acreditado</strong></td>
                                        <td><strong>Opcion</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data as $vendedor):?>
                                        <tr>
                                            <td><?php echo $vendedor["codVendedor"]?></td>
                                            <td><?php echo $vendedor["Descrip"]?></td>
                                            <td><?php echo $vendedor["moneda"]?></td>
                                            <?php if($vendedor['moneda'] == 'Bolivares'): ?>
                                                <td><?php echo $vendedor["saldo"]?> Bs</td>              
                                            <?php endif; ?>
                                            <?php if($vendedor['moneda'] == 'Dolares'): ?>
                                                <td><?php echo $vendedor["saldo"]?> $</td>              
                                            <?php endif; ?>
                                            <?php if($vendedor['moneda'] == 'Euros'): ?>
                                                <td><?php echo $vendedor["saldo"]?> €</td>              
                                            <?php endif; ?>
                                            <td>
                                                <?php if($vendedor['saldo'] != 0):?>
                                                    <button type="button" class="btn btn-sm btn-outline-saint" <?php echo "onclick=editarForm('$vendedor[codVendedor]','$vendedor[moneda]')"?> data-toggle="modal" data-target="#exampleModal">Retiro</button>
                                                <?php endif;?>   
                                            </td>                                    
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div> 
                    <?php endif;?> 
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form class="form-horizontal" id="edit_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title" id="titulo"></div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0">

                            <input type="text" hidden name="correlativo" id="correlativo">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="">Vendedor</label>
                                                <input type="text" readonly name="nombre1" id="nombre1" class="form-control font-weight-bold">                                              
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Moneda</label>                                
                                                <input type="text" readonly name="moneda" id="moneda" class="form-control font-weight-bold">                                                   
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Numero de Documento</label>
                                                <input type="text" name="factura" id="factura" class="form-control" placeholder="Numero de Documento"> 
                                                <span id="errorFactura"></span>                                                    
                                            </div>
                                            <input type="text" hidden name="codVendedor" id="codVendedor" class="form-control font-weight-bold">
                                            <input type="text" hidden name="nombre" id="nombre" class="form-control font-weight-bold">                                              
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row mt-2">
                                            <div class="col-sm-3">
                                                <label for="">Tipo de Transaccion</label>
                                                <input type="text" readonly name="tipo_doc" id="tipo_doc" class="form-control font-weight-bold">                                                     
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Saldo Disponible</label>
                                                <input type="text" readonly name="monto_acreditado" id="monto_acreditado" class="form-control font-weight-bold">                                                     
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Ingrese el monto a retirar</label>
                                                <input type="text"  class="form-control" name="monto" id="monto">
                                            </div>                       
                                        </div>
                                        <div class="row mt-1">
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
                                                <input type="text" name="denom[]" id="denom_1" class="form-control">
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
                                                <input type="text" name="denom[]" id="denom_20" class="form-control" >
                                            </div>
                                            <div class="col-sm-2"> 
                                                <label for="">Cant. 5 €</label>                                      
                                                <input type="text" name="denom[]" id="denom_21" class="form-control" placeholder="5 €">
                                            </div>
                                        </div>
                                    </div>                    
                                </div>
                                <label for="" class="col-sm-12 mt-3 text-center">Disponibilidad de Billetes</label>  
                                <table id="tablaVuelto" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                    <thead style="background-color: #00137f;color: white;"></thead>
                                </table> 
                            </div> 
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-saint float-left">Procesar</button>
                            <button type="button" onclick="ocultar()" class="btn btn-outline-saint float-right" data-dismiss="modal">Cancelar</button>                
                        </div>
                    </div>
                </form>    
            </div>
        </div>    
    </section> 
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script src="recepcion_efectivo_vuelto.js"></script>
