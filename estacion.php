<?php 
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require_once 'conexion.php';
require_once 'funciones.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logistica y Despacho</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
        
        <div class="lockscreen-logo">
            <div class="col-sm-12">
                <img src="dist/img/AdminLTELogo.png" alt="Rsistems Logo" style="opacity: .8" width="180" height="150">
            </div>
        </div>

        <form action="estacion_procesa.php" method="post">
            <!-- User name -->
            <div class="lockscreen-name">Seleccione Sucursal</div>

            <div class="lockscreen-credentials">
                <div class="row pt-2">
                    <div class="col-10">
                        <div class="input-group">
                            <select id="sucu" name="sucu" class="form-control select2 text-center" style="width: 100%;">
                                <option value="">-- Seleccione Sucursal --</option>
                                <?php
                                $query = mssql_query("SELECT CodSucu, Descrip FROM SASUCURSAL");
                                for ($j = 0; $j < mssql_num_rows($query); $j++) {
                                    $codsucu =  mssql_result($query, $j, "CodSucu");
                                    ?>
                                    <option value="<?= $codsucu; ?>" <?php if($_COOKIE['codsucu'] == $codsucu) { echo 'selected'; } ?>>
                                        <?= mssql_result($query, $j, "Descrip"); ?>
                                    </option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User name -->
            <div class="lockscreen-name mt-4">Seleccione Estación</div>

            <div class="lockscreen-credentials">
                <div class="row pt-2">
                    <div class="col-10">
                        <div class="input-group">
                            <select id="esta" name="esta" class="form-control select2 text-center" style="width: 100%;">
                                <option value="">-- Seleccione Estación --</option>
                                <?php
                                $query = mssql_query("SELECT CodEsta FROM SAESTA");
                                for ($j = 0; $j < mssql_num_rows($query); $j++) {
                                    ?>
                                    <option value="<?= mssql_result($query, $j, "CodEsta"); ?>">
                                        <?= mssql_result($query, $j, "CodEsta"); ?>
                                    </option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-4"></div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Aceptar</button>
                </div>
                <div class="col-4"></div>
            </div>
        </form>
    </div>
    <!-- /.center -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>

    <script type="text/javascript">
        $(function () {

            $('#sucu').one('select2:open', function(e) {
                $('input.select2-search__field').prop('placeholder', 'Buscar...');
            });

            $('#esta').one('select2:open', function(e) {
                $('input.select2-search__field').prop('placeholder', 'Buscar...');
            });

            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });
        })  
    </script>
</body>

</html>