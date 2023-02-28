<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');

require("conexion.php");

$timeZone = new DateTimeZone('America/La_Paz');
$fecha = $_SESSION['fecha'];
$moneda = $_SESSION['moneda'];
$dataDenom = array();
$tipo_documento = array(
    "I"=>"Ingreso",
    "E"=>"Retiro"
);
$seleccion = array(
    "V" => "Vendedor",
    "O" => "Foraneo"
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
                    <div class="card-title">
                        <h5> Caja <?php echo date('d-m-Y',strtotime($fecha));?></h5>
                    </div>
                    <div class="float-right" id="correl"></div>
                </div>

                <form class="form-horizontal" id="form_recepcion" >
                    <input type="date" name="fecha" hidden id="fecha" value="<?php echo date('Y-m-d',strtotime($fecha));?>">
                    <input type="text" name="correlativo" hidden id="correlativo">
                    <input type="text" name="moneda" hidden id="moneda" value="<?php echo $_SESSION['moneda']?>">
                    <div class="card-body">  
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="">Tipo de Transaccion</label>
                                    <select name="tipo_doc" id="tipo_doc"  class="form-control custom-select" onchange="tipoTransaccion()">
                                        <option value="">--- Seleccione ---</option>
                                        <?php foreach($tipo_documento as $key=>$value):?>
                                            <option <? echo "value=".$key.""?>><?php echo $value; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div> 
                                <div class="col-sm-4" id="tipoEntidad">
                                    <label for="">Seleccione el Tipo de Entidad</label>
                                    <select name="seleccion" id="seleccion" class="form-control" onchange="tipoCaja()">
                                        <option value="">-- Seleccione --</option>
                                        <?php
                                        foreach($seleccion as $key => $value) {
                                            ?>
                                            <option value="<? echo $key; ?>">
                                                <?php echo $value?>
                                            </option>
                                            <?php
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-sm-4" id="tpagar">
                                    <label for="" id="texto"></label>
                                    <input type="text" name="monto" id="monto" class="form-control">       
                                </div>
                            </div>
                        </div>  
                        <div id="vistaFormulario">  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="row">
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
                                            <input type="text" name="factura" id="factura" class="form-control" placeholder="Numero de Documento">       
                                            <span id="errorFactura"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row mt-2">      
                                        <label for="">Observacion</label>                              
                                        <textarea name="observacion" id="observacion" class="form-control" cols="30" rows="2" placeholder="Observacion..." style="resize:none;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2 mb-2">
                                    <div id="denominacion">
                                        <label for="" class="col-sm-12 mt-3 text-center">Disponibilidad de Billetes</label>  
                                        <?php if($moneda == 'BS'):?>
                                            <table id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                                <thead style="background-color: #00137f;color: white;">
                                                    <tr>
                                                        <td width="10%">Denominacion</td>
                                                        <td width="13%">100 Bs</td>
                                                        <td>50 Bs</td>
                                                        <td>20 Bs</td>
                                                        <td>10 Bs</td>
                                                        <td>5 Bs</td>
                                                        <td>1 Bs</td>
                                                        <td width="13%">0.5 Bs</td>              
                                                    </tr>
                                                </thead>     
                                            </table>   
                                        <?php endif;?>
                                        <?php if($moneda == 'DL'):?>
                                            <table id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
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
                                                    </tr>
                                                </thead>     
                                            </table>
                                        <?php endif;?>
                                        <?php if($moneda == 'EU'):?>
                                            <table id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
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
                                                    </tr>
                                                </thead>    
                                            </table>  
                                        <?php endif;?>         
                                    </div>
                                </div>
                                
                                <div class="col-md-12">          
                                    <label for="" class="mt-4 mb-3">Ingrese la cantidad de Billetes</label>               
                                    <div id="form_bs"> 
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">100 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_1" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">50 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_2" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">20 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_3" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">10 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_4" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">5 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_5" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">1 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_6" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">0.5 Bs <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_7" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>  
                                    </div>
                                    <div id="form_dl">
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">100 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_8" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">50 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_9" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">20 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_10" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">10 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_11" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">5 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_12" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">2 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_13" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="descrip" class="col-sm-2 col-form-label text-right">1 $ <strong>x</strong></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="denom[]" id="denom_14" class="form-control form-control-sm" style="width:60%;">
                                            </div>
                                        </div>
                                    </div>  
                                </div>                          
                                <div id="form_eu">
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">500 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_15" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">200 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_16" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">100 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_17" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">50 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_18" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">20 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_19" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">10 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_20" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="descrip" class="col-sm-2 col-form-label text-right">5 € <strong>x</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="denom[]" id="denom_21" class="form-control form-control-sm" style="width:60%;">
                                        </div>
                                    </div>
                                </div>   
                            </div>  
                            <div class="form-group row" id="vueltos1">
                                <label for="descrip" class="col-sm-2 col-form-label text-right">Vuelto Pendiente</label>
                                <div class="col-sm-10">
                                    <label for="descrip" id="vueltos" class="col-sm-2 col-form-label"></label>
                                </div>
                            </div>               
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="procesar" class="btn btn-saint">Procesar</button>
                        <button type="button" class="btn btn-outline-saint float-right" onclick="regresa()">Regresar</button>                
                    </div>
                </form>    
            </div>
            <div class="card card-saint">
                <div class="card-body" id="mostrarTabla">
                    <table id="tablaRecepcion" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                        <thead style="background-color: #00137f;color: white;"></thead>
                    </table> 
                </div>
            </div>
        </div>
    </section>
</div>
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script src="recepcion_efectivo_crear.js"></script>