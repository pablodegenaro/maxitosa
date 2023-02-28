<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
$aux = $_GET['cli'];

$descrip = "";
$codclie = "";
$nomina = "";
$ficha = "";

if ($aux != ""){
    $query = mssql_query("SELECT Descrip, Activo, cli99.* from SACLIE clie inner join SACLIE_99 cli99 on clie.CodClie = cli99.CodClie where clie.CodClie = '$aux'");
    $descrip = mssql_result($query,0,"descrip");
    $codclie = mssql_result($query,0,"codclie");
    $nomina = mssql_result($query,0,"nomina");
    $ficha = mssql_result($query,0,"ficha");

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
                        <li class="breadcrumb-item active">Editar Perfil de Empleado</li>
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
                            window.location.href = "principal1.php?page=maestro_empleados&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Editar Empleado: <?php echo utf8_encode($descrip) ; ?></h3>
                    </div>
                    <form class="form-horizontal" action="principal1.php?page=maestro_empleados_edita_procesa&mod=1&id=<?php echo $aux; ?>" method="post" id="registro_cliente" name="registro_cliente">
                     <div class="card-body">
                        <!-- Date -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="codclie" class="col-sm-2 col-form-label">Codigo Empleado</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo $codclie; ?>" id="codclie" name="codclie" placeholder="Codigo Cliente" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descrip" class="col-sm-2 col-form-label">Razon Social</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo utf8_encode($descrip); ?>" id="descrip" name="descrip" placeholder="Descripcion" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="clasificacion" class="col-sm-2 col-form-label">Ficha</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo $ficha; ?>" id="ficha" name="ficha" placeholder="Ficha del empleado de Gestion Pago">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nomina" class="col-sm-2 col-form-label">Nomina</label>
                                <div class="col-sm-10">
                                 <select class="form-control custom-select" name="nomina" id="nomina" style="width: 100%;" >
                                    <option value="<?php echo $nomina; ?>"><?php echo Nomina_nombre($nomina); ?></option>
                                    <option value="SWNOMMSSQL000001">Almacen</option>
                                    <option value="SWNOMMSSQL000002">Administracion</option>
                                    <option value="SWNOMMSSQL000003">Ventas</option>
                                    <option value="SWNOMMSSQL000004">Gerencia</option>
                                    <option value="SWNOMMSSQL000005">Semanal Campo</option>
                                    <option value="SWNOMMSSQL000006">Nomina Empleados Eventuales</option>
                                    <option value="SWNOMMSSQL000008">Nomina Vigilancia</option>
                                    <option value="SWNOMMSSQL000009">Idietca Diaria</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                    <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint float-right">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
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
</script>