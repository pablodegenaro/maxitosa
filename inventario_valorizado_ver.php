<?php
set_time_limit(0);
$numero = $_POST['depo'];
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

    $edv   = "";
    $count  = count($numero);
    for ($i = 0; $i < $count; $i++) {
        $edv = $edv . "'" . $numero[$i] . "',";
    }

    $depo = "(" . substr($edv, 0, strlen($edv) - 1) . ")";
    if ($depo != "()") {
        $codubic = "and c.codubic in " . $depo;
    } else {
        $codubic = "";
    }

    $query = mssql_query("SELECT a.codprod, a.descrip, b.proveedor, b.clasificacion_categoria, clas.descrip as instancia, sum(isnull((c.ExUnidad/nullif(a.CantEmpaq,0)),0) + c.Existen) as cajas, 
        sum((isnull((c.ExUnidad/nullif(a.CantEmpaq,0)),0) + c.Existen) * b.Profit2) as valor
        from SAPROD as a inner join SAPROD_99 as b on a.CodProd=b.CodProd inner join SAEXIS as c on a.CodProd=c.CodProd   inner join VW_ADM_INSTANCIAS inst on a.CodInst = inst.CODINST
        inner join SAINSTA clas on CONVERT(int,substring(inst.Orderbyfield,0,6)) = clas.CodInst     where (c.Existen >0 or c.ExUnidad >0)   $codubic  group by a.CodProd, a.Descrip, b.proveedor, b.clasificacion_categoria, clas.Descrip
        ");

        ?>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                <!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion Clientes</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
</ol>
</div>
</div> -->
</div>
</section>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-saint">
                    <div class="card-header">
                        <script type="text/javascript">
                            function regresa(){
                                window.location.href = "principal1.php?page=inventario_valorizado&mod=1";
                            }
                        </script>
                        <h3 class="card-title">Inventario Valorizado</h3>&nbsp;&nbsp;&nbsp;
                        <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                    </div>
                    <div class="card-body">
                        <!-- <table id="example2" class="table table-bordered table-hover"> -->
                            <table id="example1" class="table table-sm table-bordered table-striped">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripcion</th>
                                        <th>Proveedor</th>
                                        <th>Categoria</th>
                                        <th>Ins. Padre</th>
                                        <th>Bultos</th>
                                        <th>Costo P2 $</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($j = 0; $j < mssql_num_rows($query); $j++) {
                                        ?>
                                        <tr>
                                            <td><?php echo mssql_result($query, $j, 'codprod'); ?></td>
                                            <td><?php echo utf8_encode(mssql_result($query, $j, 'descrip')); ?></td>
                                            <td><?php echo utf8_encode(mssql_result($query, $j, 'proveedor')); ?></td>
                                            <td><?php echo utf8_encode(mssql_result($query, $j, 'clasificacion_categoria')); ?></td>
                                            <td><?php echo utf8_encode(mssql_result($query, $j, 'instancia')); ?></td>
                                            <td><?php echo rdecimal2(mssql_result($query, $j, 'cajas'), 2); ?></td>
                                            <td><?php echo rdecimal2(mssql_result($query, $j, 'valor'), 2); ?></td>
                                        </tr> 
                                    <?php } ?>
                                </tbody>
                            </table>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<?php
} else {
    header('Location: index.php');
}
?>