<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if (isset($_POST['sucu']) and isset($_POST['esta'])) {
    $_SESSION['codsucu'] = $_POST['sucu'];
    $_SESSION['codesta'] = $_POST['esta'];
    setcookie ("codesta", $_POST['esta']); 
    header("Location: principal2.php?page=ventas_index&mod=1");
} else {
    header('Location: estacion.php');
}
