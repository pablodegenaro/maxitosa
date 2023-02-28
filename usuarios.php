<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Relacion De Usuarios</h3>&nbsp;&nbsp;&nbsp;
                            <a href="principal.php?page=usuarios&mod=1">Usuarios</a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="principal.php?page=vehiculos&mod=1">Vehiculos</a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="principal.php?page=choferes&mod=1">Choferes</a>
                        </div>
                        <div class="card-body">
                            <?php
                            $consulta_user = mssql_query("SELECT * from (
                                select codusUa, descrip, RTRIM(LTRIM(SUBSTRING(SDATA2,193,1)+
                                  SUBSTRING(SDATA1,77,1))) AS nivel,
                                RTRIM(LTRIM(SUBSTRING(SDATA3,175,1)+SUBSTRING(SDATA1,33,1)+SUBSTRING(SDATA2,90,1)+SUBSTRING(SDATA3,14,1)+SUBSTRING(SDATA1,207,1)+
                                  SUBSTRING(SDATA3,111,1)+SUBSTRING(SDATA3,145,1)+SUBSTRING(SDATA2,180,1)+SUBSTRING(SDATA2,9,1)+SUBSTRING(SDATA3,53,1))) as clave 
                                from ssusrs) as innertable where codusUa != '001' order by descrip asc " ) ;
                                ?>
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Nombre y Apellido</th>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($consulta_user); $i++) {
                                            ?>
                                            <tr>
                                                <td><?php echo $i + 1; ?></td>
                                                <td><?php echo mssql_result($consulta_user, $i, "codusUa"); ?></td>
                                                <td><?php echo mssql_result($consulta_user, $i, "descrip"); ?></td>
                                                <td> <?php
                                                $nivel= mssql_result($consulta_user, $i, "nivel");
                                                switch ($nivel) {
                                                    case 1:
                                                    echo "Directiva";
                                                    break;
                                                    case 2:
                                                    echo "Administracion";
                                                    break;
                                                    case 3:
                                                    echo "Compras";
                                                    break;
                                                    case 4:
                                                    echo "Ventas";
                                                    break;
                                                    case 5:
                                                    echo "Logistica";
                                                    break;
                                                    case 6:
                                                    echo "Finanzas";
                                                    break;
                                                    case 7:
                                                    echo "Contabilidad";
                                                    break;
                                                    case 8:
                                                    echo "IT";
                                                    break;
                                                    case 9:
                                                    echo "Supervisor";
                                                    break;
                                                    case 10:
                                                    echo "Comercial";
                                                    break;
                                                    default:
                                                    echo "usuario sin rol";
                                                }
                                            ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Nombre y Apellido</th>
                                        <th>Usuario</th>
                                        <th>Rol</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>