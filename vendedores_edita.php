<?php

$aux = $_GET['ven'];

$supervisor = "";
$codvend = "";
$descrip = "";
$cedula = "";
$clave = "";
$obj_ventas_kg = "";
$obj_ventas_bul = "";
$obj_ventas_und = "";
$obj_captar_clie = "";
$obj_especial = "";
$obj_bs = "";
$frecuencia = "";

if ($aux != ""){
    $query = mssql_query("SELECT S.Descrip, S.clase, S.Telef, S.activo, U.* FROM savend_99 AS U INNER JOIN savend AS S ON S.CodVend = U.CodVend WHERE U.CodVend = '$aux'");
    $supervisor = mssql_result($query,0,"supervisor");
    $codvend = mssql_result($query,0,"codvend");
    $descrip = mssql_result($query,0,"descrip");
    $cedula = mssql_result($query,0,"cedula");
    $clave = mssql_result($query,0,"clave");
    $obj_bul_und = mssql_result($query,0,"obj_bul_und");
    $obj_bul_und = (is_null($obj_bul_und) || empty($obj_bul_und)) ? 0 : $obj_bul_und;
    $obj_ventas_kg = mssql_result($query,0,"obj_ventas_kg");
    $obj_ventas_bul = mssql_result($query,0,"obj_ventas_bul");
    $obj_ventas_und = mssql_result($query,0,"obj_ventas_und");
    $obj_captar_clie = mssql_result($query,0,"obj_captar");
    $obj_especial = mssql_result($query,0,"obj_especial");
    $obj_bs = mssql_result($query,0,"obj_bs");
    $frecuencia = mssql_result($query,0,"frecuencia");
}?>
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
                        <li class="breadcrumb-item active">Editar Vendedor</li>
                    </ol>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-saint">
                    <script type="text/javascript">
                        function guarda(){
                            /*document.forms["registro_cliente"].submit();*/
                        }
                        function regresa(){
                            window.location.href = "principal1.php?page=vendedores&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Editar Vendedor: <?php echo utf8_encode($descrip) ; ?></h3>
                    </div>
                    <form class="form-horizontal" action="principal1.php?page=vendedores_edita_procesa&mod=1&id=<?php echo $aux; ?>" method="post" id="registro_vendedor" name="registro_vendedor">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="supervisor">SUPERVISOR</label>
                                            <input type="text" class="form-control input-sm text-bold" maxlength="100" value="<?= trim($supervisor); ?>" id="supervisor" name="supervisor" placeholder="Ingrese Supervisor" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="ruta">RUTA</label>
                                            <input type="text" class="form-control input-sm bg-gray-light" maxlength="20" value="<?= $codvend; ?>" id="ruta" name="ruta" placeholder="Ruta" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <?php
                                            $objetivos_kpi = mssql_query("select id, descripcion from kpi_objetivos");
                                            ?>
                                            <label for="objetivo_kpi">TIPO DE OBJETIVO EN KPI</label>
                                            <select class="form-control custom-select" name="objetivo_kpi" id="objetivo_kpi" onchange="tipo_objetivo_kpi(this.value)" style="width: 100%;" required>
                                                <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                <?php
                                                for($i=0; $i < mssql_num_rows($objetivos_kpi); $i++) {
                                                ?><option name="" value="<?= mssql_result($objetivos_kpi,$i,"id");?>" <?= ($obj_bul_und==mssql_result($objetivos_kpi,$i,"id")) ? 'selected' : '';?>>
                                                    <?= mssql_result($objetivos_kpi,$i,"descripcion");?>
                                                    </option><?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="ubicacion">CLAVE</label>
                                            <input type="password" class="form-control input-sm" maxlength="100" value="<?= $clave; ?>" id="clave" name="clave" placeholder="Ingrese clave">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="obj_ventas_bul">OBJ. VENTAS (BULTOS)</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= number_format($obj_ventas_bul,2); ?>" id="obj_ventas_bul" name="obj_ventas_bul" placeholder="Ingrese objetivo ventas (bulto)" onkeypress="return isNumberKey(this, event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="nomper">NOMBRE Y APELLIDO</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= $descrip; ?>" id="nomper" name="nomper" placeholder="Ingrese nombre y apellido" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="obj_ventas_und">OBJ. VENTAS (UNIDAD)</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= number_format($obj_ventas_und,2,".", ""); ?>" id="obj_ventas_und" name="obj_ventas_und" placeholder="Ingrese objetivo ventas (unidad)"  onkeypress="return isNumberKey(this, event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="cedula">CEDULA</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= $cedula; ?>" id="cedula" name="cedula" placeholder="Ingrese cedula" onkeypress="return isNumberKey(this, event)" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="obj_ventas_kg">OBJ. VENTAS (KG)</label>
                                            <input type="text" class="form-control input-sm" maxlength="30" value="<?= number_format($obj_ventas_kg,2,".", ""); ?>" id="obj_ventas_kg" name="obj_ventas_kg" placeholder="Ingrese objetivo ventas (kg)" onkeypress="return isNumberKey(this, event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <?php
                                            $depositos = mssql_query("SELECT CodUbic, Descrip from SADEPO where Activo=1");
                                            $seleccionado = mssql_result($query,0,"ubicacion");
                                            ?>
                                            <label for="objetivo_kpi">ALMACEN</label>
                                            <select class="form-control custom-select" name="deposito" id="deposito" style="width: 100%;" required>
                                                <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                <?php
                                                for($i=0; $i < mssql_num_rows($depositos); $i++) {
                                                ?><option name="" value="<?= mssql_result($depositos,$i,"CodUbic");?>" <?= ($seleccionado==mssql_result($depositos,$i,"CodUbic")) ? 'selected' : '';?>>
                                                    <?= mssql_result($depositos,$i,"CodUbic");?> - <?= mssql_result($depositos,$i,"Descrip");?>
                                                    </option><?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="obj_clientes_captar">OBJ. CLIENTES A CAPTAR</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= $obj_captar_clie; ?>" id="obj_clientes_captar" name="obj_clientes_captar" placeholder="Ingrese objetivo clientes a captar" onkeypress="return isNumberKey(this, event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="obj_bs">OBJ. BS</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= number_format($obj_bs,2,".", ""); ?>" id="obj_bs" name="obj_bs" placeholder="Ingrese objetivo bs" onkeypress="return isNumberKey(this, event)">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="obj_especial">OBJ. ESPECIAL</label>
                                            <input type="text" class="form-control input-sm" maxlength="100" value="<?= number_format($obj_especial,2,".", ""); ?>" id="obj_especial" name="obj_especial" placeholder="Ingrese objetivo especial" onkeypress="return isNumberKey(this, event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="frecuencia">Frecuencia</label>
                                            <select class="form-control custom-select" name="frecuencia" id="frecuencia" style="width: 100%;" required>
                                                <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                <option name="" value="1" <?= ($frecuencia=='1') ? 'selected' : '';?>>Mensual</option>
                                                <option name="" value="2" <?= ($frecuencia=='2') ? 'selected' : '';?>>Quincenal</option>
                                                <option name="" value="4" <?= ($frecuencia=='4') ? 'selected' : '';?>>Semanal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="codvend" value="<?= $aux; ?>">
                            <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                            <button type="submit" name="Submit"  class="btn btn-saint float-right">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
<?php include "footer.php"; ?>
<script type="text/javascript">

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

    function tipo_objetivo_kpi(valor) {
        if (valor!=='1') $('#obj_ventas_kg').val('0');
        if (valor!=='2') $('#obj_ventas_bul').val('0');
        if (valor!=='3') $('#obj_ventas_und').val('0');

        $('#obj_ventas_kg').prop("readonly", valor!=='1');
        $('#obj_ventas_bul').prop("readonly", valor!=='2');
        $('#obj_ventas_und').prop("readonly", valor!=='3');

        if (valor!=='1' && !$("#obj_ventas_kg").hasClass('bg-gray-light')) {
            $("#obj_ventas_kg").addClass('bg-gray-light');
        } else {
            $('#obj_ventas_kg').removeClass('bg-gray-light');
        }

        if (valor!=='2' && !$("#obj_ventas_bul").hasClass('bg-gray-light')) {
            $("#obj_ventas_bul").addClass('bg-gray-light');
        } else {
            $('#obj_ventas_bul').removeClass('bg-gray-light');
        }

        if (valor!=='3' && !$("#obj_ventas_und").hasClass('bg-gray-light')) {
            $("#obj_ventas_und").addClass('bg-gray-light');
        } else {
            $('#obj_ventas_und').removeClass('bg-gray-light');
        }
    }

    $(document).ready(function () {
        tipo_objetivo_kpi(<?php echo "'".intval($obj_bul_und)."'";?>);
    });
</script>

