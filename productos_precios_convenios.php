<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
set_time_limit(0);
if ($_SESSION['login']) {
    ?>
    <div class="content-wrapper" >
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                            <li class="breadcrumb-item active">Productos Precios Convenios</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content" >
            <div class="col-md-12">
                <div class="card card-saint">
                </div>
                <div class="card card-saint">
                    <script type="text/javascript">
                        function guarda(){
                            limpiarBuscar();
                                /* document.forms["registro_usuarios"].submit();*/
                        }
                        function regresa(){
                            window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Productos Precios Convenios</h3>&nbsp;&nbsp;&nbsp;
                    </div>
                    <form name="formulario" method="post" action="productos_precios_procesa_convenios.php">
                        <div class="card-body">
                            <?php
                            $productos = mssql_query("SELECT prod.CodProd, Descrip, Marca, prod99.proveedor, Profit1, Profit2,Profit3,  Profit4, Profit5, Profit6, Profit7, Profit8 from saprod prod  left join SAPROD_99 prod99 on prod.CodProd = prod99.CodProd");
                            ?> 

                            <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                <table id="example5" class="table table-sm table-bordered table-striped table-responsive p-0">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Proveedor</th>
                                            <th>Marca</th>
                                            <th>Precio 4 $ Convenio Diageo</th>
                                            <th>Precio 5 $ Convenio EURO</th>
                                            <th>Precio 6 $ Convenio Call Center</th>
                                            <th>Precio 7 $ Convenio Empleados</th>
                                            <th>Precio 8 $ Convenio Mayorista</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($productos); $i++) { 
                                            $precio4= 4;
                                            $precio5= 5;
                                            $precio6= 6;
                                            $precio7= 7;
                                            $precio8= 8;
                                            $Profit1 = mssql_result($productos, $i, "Profit1");
                                            $Profit2 = mssql_result($productos, $i, "Profit2");
                                            $Profit3 = mssql_result($productos, $i, "Profit3");
                                            $Profit4 = mssql_result($productos, $i, "Profit4");  
                                            $Profit5 = mssql_result($productos, $i, "Profit5");    
                                            $Profit6 = mssql_result($productos, $i, "Profit6");    
                                            $Profit7 = mssql_result($productos, $i, "Profit7");    
                                            $Profit8 = mssql_result($productos, $i, "Profit8");                                           
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo mssql_result($productos, $i, "CodProd"); ?>
                                                    <input type="hidden" name="cod[]" value="<?php echo trim(mssql_result($productos, $i, 'CodProd')); ?>">
                                                    <input type="hidden" name="proveedor[]" value="<?php echo trim(mssql_result($productos, $i, 'proveedor')); ?>">
                                                </td>
                                                <td class="text-left"><?php echo utf8_encode(mssql_result($productos, $i, "Descrip")); ?></td>
                                                <td class="text-center"><?php echo utf8_encode(mssql_result($productos, $i, "proveedor")); ?></td>
                                                <td class="text-center"><?php echo utf8_encode(mssql_result($productos, $i, "Marca")); ?></td>
                                                <td class="text-center">
                                                    <?php    
                                                    $query = mssql_query("SELECT * from convenio_configuracion where nivel_precio=$precio4");

                                                    $porcentaje=mssql_result($query, 0, "porcentaje");
                                                    $porcentajefinal= ($porcentaje/100)+1;
                                                    $preciobase=mssql_result($query, 0, "precio_base");
                                                    $aplicacion=mssql_result($query, 0, "tipo_aplicacion");

                                                    switch ($preciobase) {
                                                        case 0:
                                                        $Profit44=$Profit4;
                                                        break;
                                                        case 1:
                                                        switch ($aplicacion) {
                                                            case 1:
                                                            $Profit44= $Profit1*$porcentajefinal;
                                                            break;
                                                            case 2:
                                                            $Profit44= $Profit1*((100-$porcentaje)/100);
                                                            break;                                                             
                                                        }
                                                        break;
                                                        case 2:
                                                        switch ($aplicacion) {
                                                            case 1:
                                                            $Profit44= $Profit2*$porcentajefinal;
                                                            break;
                                                            case 2:
                                                            $Profit44= $Profit2*((100-$porcentaje)/100);
                                                            break;                                                             
                                                        }
                                                        break;
                                                        case 3:
                                                        switch ($aplicacion) {
                                                            case 1:
                                                            $Profit44= $Profit3*$porcentajefinal;
                                                            break;
                                                            case 2:
                                                            $Profit44= $Profit3*((100-$porcentaje)/100);
                                                            break;                                                             
                                                        }
                                                        break;
                                                    }
                                                    ?>
                                                    <input type="text" name="profit4[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= ($Profit44>0) ? number_format($Profit44, 2, '.', '') : 0; ?>">
                                                </td>
                                                <td class="text-center">
                                                   <?php    
                                                   $query = mssql_query("SELECT * from convenio_configuracion where nivel_precio=$precio5");

                                                   $porcentaje=mssql_result($query, 0, "porcentaje");
                                                   $porcentajefinal= ($porcentaje/100)+1;
                                                   $preciobase=mssql_result($query, 0, "precio_base");
                                                   $aplicacion=mssql_result($query, 0, "tipo_aplicacion");

                                                   switch ($preciobase) {
                                                    case 0:
                                                    $Profit55=$Profit5;
                                                    break;
                                                    case 1:
                                                    switch ($aplicacion) {
                                                        case 1:
                                                        $Profit55= $Profit1*$porcentajefinal;
                                                        break;
                                                        case 2:
                                                        $Profit55= $Profit1*((100-$porcentaje)/100);
                                                        break;                                                             
                                                    }
                                                    break;
                                                    case 2:
                                                    switch ($aplicacion) {
                                                        case 1:
                                                        $Profit55= $Profit2*$porcentajefinal;
                                                        break;
                                                        case 2:
                                                        $Profit55= $Profit2*((100-$porcentaje)/100);
                                                        break;                                                             
                                                    }
                                                    break;
                                                    case 3:
                                                    switch ($aplicacion) {
                                                        case 1:
                                                        $Profit55= $Profit3*$porcentajefinal;
                                                        break;
                                                        case 2:
                                                        $Profit55= $Profit3*((100-$porcentaje)/100);
                                                        break;                                                             
                                                    }
                                                    break;
                                                }
                                                ?>
                                                <input  type="text" name="profit5[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                value="<?= ($Profit55>0) ? number_format($Profit55, 2, '.', '') : 0; ?>">
                                            </td>
                                            <td class="text-center">
                                             <?php    
                                             $query = mssql_query("SELECT * from convenio_configuracion where nivel_precio=$precio6");

                                             $porcentaje=mssql_result($query, 0, "porcentaje");
                                             $porcentajefinal= ($porcentaje/100)+1;
                                             $preciobase=mssql_result($query, 0, "precio_base");
                                             $aplicacion=mssql_result($query, 0, "tipo_aplicacion");

                                             switch ($preciobase) {
                                                case 0:
                                                $Profit66=$Profit6;
                                                break;
                                                case 1:
                                                switch ($aplicacion) {
                                                    case 1:
                                                    $Profit66= $Profit1*$porcentajefinal;
                                                    break;
                                                    case 2:
                                                    $Profit66= $Profit1*((100-$porcentaje)/100);
                                                    break;                                                             
                                                }
                                                break;
                                                case 2:
                                                switch ($aplicacion) {
                                                    case 1:
                                                    $Profit66= $Profit2*$porcentajefinal;
                                                    break;
                                                    case 2:
                                                    $Profit66= $Profit2*((100-$porcentaje)/100);
                                                    break;                                                             
                                                }
                                                break;
                                                case 3:
                                                switch ($aplicacion) {
                                                    case 1:
                                                    $Profit66= $Profit3*$porcentajefinal;
                                                    break;
                                                    case 2:
                                                    $Profit66= $Profit3*((100-$porcentaje)/100);
                                                    break;                                                             
                                                }
                                                break;
                                            }
                                            ?>
                                            <input  type="text" name="profit6[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                            value="<?= ($Profit66>0) ? number_format($Profit66, 2, '.', '') : 0; ?>">
                                        </td>
                                        <td class="text-center">
                                           <?php    
                                           $query = mssql_query("SELECT * from convenio_configuracion where nivel_precio=$precio7");

                                           $porcentaje=mssql_result($query, 0, "porcentaje");
                                           $porcentajefinal= ($porcentaje/100)+1;
                                           $preciobase=mssql_result($query, 0, "precio_base");
                                           $aplicacion=mssql_result($query, 0, "tipo_aplicacion");

                                           switch ($preciobase) {
                                            case 0:
                                            $Profit77=$Profit7;
                                            break;
                                            case 1:
                                            switch ($aplicacion) {
                                                case 1:
                                                $Profit77= $Profit1*$porcentajefinal;
                                                break;
                                                case 2:
                                                $Profit77= $Profit1*((100-$porcentaje)/100);
                                                break;                                                             
                                            }
                                            break;
                                            case 2:
                                            switch ($aplicacion) {
                                                case 1:
                                                $Profit77= $Profit2*$porcentajefinal;
                                                break;
                                                case 2:
                                                $Profit77= $Profit2*((100-$porcentaje)/100);
                                                break;                                                             
                                            }
                                            break;
                                            case 3:
                                            switch ($aplicacion) {
                                                case 1:
                                                $Profit77= $Profit3*$porcentajefinal;
                                                break;
                                                case 2:
                                                $Profit77= $Profit3*((100-$porcentaje)/100);
                                                break;                                                             
                                            }
                                            break;
                                        }
                                        ?>
                                        <input  type="text" name="profit7[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                        value="<?= ($Profit77>0) ? number_format($Profit77, 2, '.', '') : 0; ?>">
                                    </td>
                                    <td class="text-center">
                                       <?php    
                                       $query = mssql_query("SELECT * from convenio_configuracion where nivel_precio=$precio8");

                                       $porcentaje=mssql_result($query, 0, "porcentaje");
                                       $porcentajefinal= ($porcentaje/100)+1;
                                       $preciobase=mssql_result($query, 0, "precio_base");
                                       $aplicacion=mssql_result($query, 0, "tipo_aplicacion");

                                       switch ($preciobase) {
                                        case 0:
                                        $Profit88=$Profit8;
                                        break;
                                        case 1:
                                        switch ($aplicacion) {
                                            case 1:
                                            $Profit88= $Profit1*$porcentajefinal;
                                            break;
                                            case 2:
                                            $Profit88= $Profit1*((100-$porcentaje)/100);
                                            break;                                                             
                                        }
                                        break;
                                        case 2:
                                        switch ($aplicacion) {
                                            case 1:
                                            $Profit88= $Profit2*$porcentajefinal;
                                            break;
                                            case 2:
                                            $Profit88= $Profit2*((100-$porcentaje)/100);
                                            break;                                                             
                                        }
                                        break;
                                        case 3:
                                        switch ($aplicacion) {
                                            case 1:
                                            $Profit88= $Profit3*$porcentajefinal;
                                            break;
                                            case 2:
                                            $Profit88= $Profit3*((100-$porcentaje)/100);
                                            break;                                                             
                                        }
                                        break;
                                    }
                                    ?>
                                    <input  type="text" name="profit8[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                    value="<?= ($Profit88>0) ? number_format($Profit88, 2, '.', '') : 0; ?>">
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                <button type="submit" name="Submit"   onclick="limpiarBuscar()"  class="btn btn-saint float-right">Procesar</button>
            </div>
        </form>
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

    function limpiarBuscar() {
        $('[type="search"]').val("");
    }
</script>
<script>
    $(document).ready(function () {
                //$('td[style*="display: none"] input').prop("disabled", true);
    });

    $(document).on("click", "#exportar_excel", function () {
        var value = $('.dataTables_filter input').val();
        window.open('productos_precios_convenios_excel.php?&s=' + value, '_blank');
    });
</script>
<?php
} else {
    header('Location: index.php');
}
?>