<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Ventas </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="row">
                <?php 
                $arr_modulos_ventas = array('ventas_fac.php','ventas_devolfac.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-info pb-4">
                            <div class="inner">
                                <h3>Facturación</h3>
                                <?php 
                                if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_fac.php')) > 0) {
                                    ?>
                                    <a class="text-light" href="principal2.php?page=ventas_fac&mod=1">Emisión Facturas</a>
                                    <br>
                                    <?php 
                                }
                                if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_devolfac.php')) > 0) {
                                    ?>
                                    <a class="text-light" href="principal2.php?page=ventas_devolfac&mod=1">Devolución Facturas</a>
                                    <?php 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>
                

                <?php 
                $arr_modulos_ventas = array('ventas_ne.php','ventas_devolne.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-success pb-4">
                            <div class="inner">
                                <h3>Nota de Entrega</h3>
                                <?php 
                                if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_ne.php')) > 0) {
                                    ?>
                                    <a href="principal2.php?page=ventas_ne&mod=1" class="text-light">Emisión NE</a>
                                    <br>
                                    <?php 
                                }
                                if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_devolne.php')) > 0) {
                                    ?>
                                    <a href="principal2.php?page=ventas_devolne&mod=1" class="text-light">Devolución NE</a>
                                    <?php 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>

                <?php 
                $arr_modulos_ventas = array('ventas_ped.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-danger pb-4">
                            <div class="inner">
                                <h3>Pedidos</h3>
                                <?php 
                                if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_ped.php')) > 0) {
                                    ?>
                                    <a href="principal2.php?page=ventas_ped&mod=1" class="text-light">Emisión Pedido</a>
                                    <?php 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>

                <?php 
                $arr_modulos_ventas = array('ventas_presu.php');
                if (count(Permisos::verficarArrayPermisoPorSessionUsuario($arr_modulos_ventas)) > 0) {
                    ?>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <div class="small-box bg-gray pb-4">
                            <div class="inner">
                                <h3>Presupuesto</h3>
                                <?php 
                                if (count(Permisos::verficarPermisoPorSessionUsuario('ventas_presu.php')) > 0) {
                                    ?>
                                    <a href="principal2.php?page=ventas_presu&mod=1" class="text-light">Emisión Presupuesto</a>
                                    <?php 
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
                ?>
                
            </div>
        </div>
    </div>

</div>
<!-- /.content-wrapper -->
<?php include("footer2.php"); ?>