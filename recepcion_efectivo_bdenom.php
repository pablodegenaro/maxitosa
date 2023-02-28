<?php 
require("conexion.php");
session_start();
/** ************************************************************************************* */
/** **************MUESTRA LA INFORMACION DE LOS BILLETES POR DENOMINACION**************** */
/** ************************************************************************************* */
$fecha = $_SESSION["fecha"];
$moneda = $_SESSION["moneda"];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];

$data = array();


$queryTabla = "SELECT 
total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
sum(debe) - sum(haber) as saldo
FROM SAREC_DENOM WHERE moneda ='$moneda' AND codsucu = '$codsucu'group by moneda";
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
                    <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                </div> 
                <div class="card-body">
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
                                    <td>Total</td>                       
                                </tr>
                            </thead> 
                            <tbody>
                                <?php foreach($data as $item): ?>
                                    <tr>
                                        <td>Bolivares</td>
                                        <td><?php echo $item['total_100'] ?></td>
                                        <td><?php echo $item['total_50'] ?></td>
                                        <td><?php echo $item['total_20'] ?></td>
                                        <td><?php echo $item['total_10'] ?></td>
                                        <td><?php echo $item['total_5'] ?></td>
                                        <td><?php echo $item['total_2'] ?></td>
                                        <td><?php echo $item['total_1'] ?></td>
                                        <td><?php echo $item['saldo'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>     
                        </table>   
                    <?php endif;?>  
                    <?php if($moneda == 'DL'):?>
                        <table id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td width="10%">Denominacion</td>
                                    <td width="13%">100 $</td>
                                    <td>50 $</td>
                                    <td>20 $</td>
                                    <td>10 $</td>
                                    <td>5 $</td>
                                    <td>2 $</td>
                                    <td width="13%">1 $</td>
                                    <td>Total</td>                       
                                </tr>
                            </thead> 
                            <tbody>
                                <?php foreach($data as $item): ?>
                                    <tr>
                                        <td>Dolares</td>
                                        <td><?php echo $item['total_100'] ?></td>
                                        <td><?php echo $item['total_50'] ?></td>
                                        <td><?php echo $item['total_20'] ?></td>
                                        <td><?php echo $item['total_10'] ?></td>
                                        <td><?php echo $item['total_5'] ?></td>
                                        <td><?php echo $item['total_2'] ?></td>
                                        <td><?php echo $item['total_1'] ?></td>
                                        <td><?php echo $item['saldo'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>     
                        </table>   
                    <?php endif;?>  
                    <?php if($moneda == 'EU'):?>
                        <table id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td width="10%">Denominacion</td>
                                    <td width="13%">500 €</td>
                                    <td>200 €</td>
                                    <td>100 €</td>
                                    <td>50 €</td>
                                    <td>20 €</td>
                                    <td>10 €</td>
                                    <td width="13%">5 €</td>
                                    <td>Total</td>                       
                                </tr>
                            </thead> 
                            <tbody>
                                <?php foreach($data as $item): ?>
                                    <tr>
                                        <td>Euros</td>
                                        <td><?php echo $item['total_100'] ?></td>
                                        <td><?php echo $item['total_50'] ?></td>
                                        <td><?php echo $item['total_20'] ?></td>
                                        <td><?php echo $item['total_10'] ?></td>
                                        <td><?php echo $item['total_5'] ?></td>
                                        <td><?php echo $item['total_2'] ?></td>
                                        <td><?php echo $item['total_1'] ?></td>
                                        <td><?php echo $item['saldo'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>     
                        </table>   
                    <?php endif;?>   
                </div>                                                      
            </div>                               
        </div>
    </section>
</div>



<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script>

    $(function(){
        $("#tablaBS").DataTable({
            "responsive": true, 
            "lengthChange": false,
            "autoWidth": false,
            "language": texto_español_datatables,
            "destroy":true,
            "searching": false,
        }).buttons().container().appendTo('#tablaBS_wrapper .col-md-6:eq(0)');

        $("#tablaDL").DataTable({
            "responsive": true, 
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "language": texto_español_datatables
        }).buttons().container().appendTo('#tablaDL_wrapper .col-md-6:eq(0)');

        $("#tablaEU").DataTable({
            "responsive": true, 
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "language": texto_español_datatables
        }).buttons().container().appendTo('#tablaEU_wrapper .col-md-6:eq(0)');
    })
    function regresa(){
        window.location.href = `principal1.php?page=recepcion_efectivo_principal&mod=1`;
    }
</script>