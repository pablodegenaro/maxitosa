<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
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
                            <li class="breadcrumb-item active">Clientes</li>
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
                        <h3 class="card-title">Clientes</h3>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="card-body">
                        <?php
                        $clientes = mssql_query("SELECT Descrip, Activo, cli99.*, clie.Represent, clie.EsCredito, clie.LimiteCred, clie.Telef, cli99.canal, cli99.formato_cliente, cli99.pdv_ocasion, cli99.formato_cliente_2, cli99.alcance, cli99.nivel_ejecucion, cli99.lcredito , cli99.segmentacion, cli99.convenio,clie.codvend from SACLIE clie inner join SACLIE_99 cli99 on clie.CodClie = cli99.CodClie");
                        ?>
                        <table id="example5" class="table table-sm table-bordered table-striped table-responsive p-0">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <th>Codigo Cliente</th>
                                    <th>Razon Social</th>
                                    <th>Condicion de Pago</th>
                                    <th>Frecuencia</th>
                                    <th>Limite de Credito</th>
                                    <th>Persona de Contacto</th>
                                    <th>Telefono</th>
                                    <th>Red Social</th>
                                    <th>Clasificacion / Formato</th>
                                    <th>Formato PDV / Ocasion</th>
                                    <th>Formato Cliente / OC Secundaria</th>
                                    <th>Alcance</th>
                                    <th>Nivel Ejecucion</th>
                                    <th>Estado</th>
                                    <th>Ruta Principal</th>         
                                    <th>Rutas Alternativas</th>                                            
                                    <th>Dia Visita</th>
                                    <th>Segmentacion</th>
                                    <th>Convenio</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < mssql_num_rows($clientes); $i++) {
                                    $activo = (mssql_result($clientes, $i, "Activo")=='1')
                                    ? '<span class="badge badge-success mt-1">Activo</span>'
                                    : '<span class="badge badge-secondary mt-1">Inactivo</span>' ;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo mssql_result($clientes, $i, "CodClie"); ?></td>
                                        <td class="text-left"><?php echo utf8_encode(mssql_result($clientes, $i, "descrip")) ; ?></td>
                                        <?php 
                                        if (mssql_result($clientes, $i, "EsCredito")=='1') {
                                         $condpago= 'Credito';
                                     } else {
                                         $condpago= 'Contado';
                                     }
                                     ?>
                                     <td class="text-center"><?php echo $condpago; ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "frecuencia_visita"); ?></td>
                                     <td class="text-center"><?php echo rdecimal2(mssql_result($clientes, $i, "lcredito")); ?></td>
                                     <td class="text-center"><?php echo utf8_encode(mssql_result($clientes, $i, "Represent")); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "Telef"); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "canal"); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "formato_cliente"); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "pdv_ocasion"); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "formato_cliente_2"); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "alcance"); ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "nivel_ejecucion"); ?></td>
                                     <td class="text-center"><?php echo $activo; ?></td>
                                     <td class="text-center"><?php echo mssql_result($clientes, $i, "codvend"); ?></td>
                                     <td class="text-center">
                                        <?php
                                        echo mssql_result($clientes, $i, "ruta_alternativa");
                                        if (strlen(mssql_result($clientes, $i, "ruta_alternativa_2"))>1) {
                                            echo ", " . mssql_result($clientes, $i, "ruta_alternativa_2");
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo mssql_result($clientes, $i, "dia_visita"); ?></td>
                                    <td class="text-center"><?php echo mssql_result($clientes, $i, "segmentacion"); ?></td>
                                    <td class="text-center"><?php 
                                    $convenio= mssql_result($clientes, $i, "convenio");
                                    switch ($convenio) {
                                        case 0:
                                        $escon = "Sin Convenio";
                                        break;
                                        case 1:
                                        $escon = "DIAGEO";
                                        break;
                                        case 2:
                                        $escon = "EURO";
                                        break;
                                        case 3:
                                        $escon = "CALL CENTER";
                                        break;
                                        case 4:
                                        $escon = "EMPLEADOS";
                                        break;
                                        case 4:
                                        $escon = "MAYORISTA";
                                        break;
                                    }
                                    echo $escon;
                                ?></td>  
                                <td  class="text-center">
                                    <a href="principal1.php?page=clientes_edita&mod=1&cli=<?php echo mssql_result($clientes, $i, "CodClie"); ?>"
                                     class="btn btn-outline-saint btn-sm">
                                     Editar
                                 </a>
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