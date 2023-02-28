<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();

$aux = $_GET['id'];
$id = "";
$descrip = "";
$nrocta = "";
$saldo = "";
if ($aux != ""){
    $consulta_vehiculo = mssql_query("SELECT id, Descrip, NroCta, Saldo from Bancos_App where id = '$aux'");
    $id = mssql_result($consulta_vehiculo,0,"id");
    $descrip = mssql_result($consulta_vehiculo,0,"Descrip");
    $nrocta = mssql_result($consulta_vehiculo,0,"NroCta");
    $saldo = mssql_result($consulta_vehiculo,0,"Saldo");
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
                        <li class="breadcrumb-item active">Crear Bancos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">

        <div class="container">
            <div class="col-md-12">
                <div class="card card-saint">
                    <script type="text/javascript">
                        function guarda(){
                            if (document.getElementById("descrip").value != "" && document.getElementById("nrocta").value != "" && document.getElementById("saldo").value != ""){
                                document.forms["registro_banco"].submit();
                            }else{
                                alert("Debe Rellenar Todos Los Campos");
                            }
                        }
                        function regresa(){
                            window.location.href = "principal1.php?page=saldo_bancos&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Creacion de Bancos</h3>
                    </div>
                    <form class="form-horizontal" action="principal1.php?page=saldo_bancos_inserta&mod=1&id=<?php echo $aux; ?>" method="post" id="registro_banco" name="registro_banco">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="descrip" class="col-sm-2 col-form-label">Nombre del Banco</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="<?php echo $descrip; ?>" id="descrip" name="descrip" placeholder="Descripcion" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nrocta" class="col-sm-2 col-form-label">Numero de Cuenta</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="<?php echo $nrocta; ?>" id="nrocta" name="nrocta" placeholder="Numero de Cuenta" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="saldo" value="<?php echo number_format($saldo,2,'.',''); ?>" name="saldo" placeholder="Saldo"  onkeypress="return isNumberKey(this, event)" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Guardar</button>
                            <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
                        </div>
                    </form>
                </div>
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