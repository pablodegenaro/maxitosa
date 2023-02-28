<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

    $edv = $_POST['edv'];

    switch (true) {

        case ( $edv!="-" ):

        $query = mssql_query("SELECT Descrip, Activo, cli99.*,  clie.EsCredito,  cli99.lcredito , cli99.segmentacion, cli99.convenio,clie.codvend, clie.telef, cli99.ficha, cli99.nomina from SACLIE clie inner join SACLIE_99 cli99 on clie.CodClie = cli99.CodClie where clie.codvend ='$edv'");

/*    if ($query) {

      $elimina = true;

    } 
*/
    break;


    case ($edv=="-" ):

    $query = mssql_query("SELECT Descrip, Activo, cli99.*,  clie.EsCredito,  cli99.lcredito , cli99.segmentacion, cli99.convenio,clie.codvend, clie.telef , cli99.ficha, cli99.nomina from SACLIE clie inner join SACLIE_99 cli99 on clie.CodClie = cli99.CodClie where clie.codvend in ('1992','2992')");

 /*   if ($query) {

      $elimina = true;

    }
*/
    break;
    default:

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
                        <li class="breadcrumb-item active">Lista de Empleados para CXC Nomina</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content" style=" margin-bottom: 70%";>
        <div class="col-md-12">
            <div class="card card-saint">
                <div class="card-header">
                    <script type="text/javascript">
                        function regresa(){
                            window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                        }
                    </script>
                    <h3 class="card-title">Lista de Empleados para CXC Nomina</h3>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="card-body row justify-content-center" >
                    <table id="example5" class="table table-sm table-bordered table-striped table-responsive p-0">
                        <thead style="background-color: #00137f;color: white;">
                            <tr>
                                <th>Codigo Saint</th>
                                <th>Codigo Nomina</th>
                                <th>Razon Social</th>                               
                                <th>Nomina</th>
                                <th>Telefono</th>                                
                                <th>Estado</th>                                
                                <th>Vendedor</th>                                
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < mssql_num_rows($query); $i++) {
                                $activo = (mssql_result($query, $i, "Activo")=='1')
                                ? '<span class="badge badge-success mt-1">Activo</span>'
                                : '<span class="badge badge-secondary mt-1">Inactivo</span>' ;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo mssql_result($query, $i, "CodClie"); ?></td>
                                    <td class="text-center"><?php $ficha = mssql_result($query, $i, "ficha");
                                    if (is_null($ficha)) {
                                         echo "Debe Asignar Ficha";
                                     } else { echo $ficha;}?></td>
                                    <td class="text-left"><?php echo utf8_encode(mssql_result($query, $i, "descrip"));?></td>
                                    <td class="text-center"><?php 
                                    $nomina=mssql_result($query, $i, "nomina");
                                    switch ($nomina) {
                                        case SWNOMMSSQL000001: $nomina = "Almacen";break;
                                        case SWNOMMSSQL000002: $nomina = "Administracion";break;
                                        case SWNOMMSSQL000003: $nomina = "Ventas";break;
                                        case SWNOMMSSQL000004: $nomina = "Gerencia";break;
                                        case SWNOMMSSQL000005: $nomina = "Semanal Campo";break;
                                        case SWNOMMSSQL000006: $nomina = "Nomina Empleados Eventuales";break;
                                        case SWNOMMSSQL000008: $nomina = "Nomina Vigilancia";break;
                                        case SWNOMMSSQL000009: $nomina = "Idietca Diaria";break;
                                        default :$nomina = "Debe Asignar Nomina";
                                    }
                                    echo $nomina;
                                    ?>
                                </td>
                                <td class="text-center"><?php echo mssql_result($query, $i, "Telef"); ?></td>
                                <td class="text-center"><?php echo $activo; ?></td>
                                <td class="text-center"><?php echo mssql_result($query, $i, "codvend"); ?></td>
                                <td  class="text-center">
                                  <a href="principal1.php?page=maestro_empleados_edita&mod=1&cli=<?php echo mssql_result($query, $i, "CodClie"); ?>"
                                    class="btn btn-outline-saint btn-sm">Editar</a>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
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