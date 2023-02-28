<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if (isset($_POST['sucu1']) and isset($_POST['esta1'])) {
    $_SESSION['codsucu1'] = $_POST['sucu1'];
    $_SESSION['codesta1'] = $_POST['esta1'];
    setcookie ("codesta1", $_POST['esta1']); 
    header("Location: principal1.php?page=recepcion_efectivo_principal&mod=1");
} else {
    header('Location: recepcion_efectivo_estacion.php');
}
