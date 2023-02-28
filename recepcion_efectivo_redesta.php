<?php 
session_start();
if($_SESSION['login']){
    if(!$_SESSION["codsucu1"] && !$_SESSION["codesta1"]){
        echo "<script>window.location.href = 'recepcion_efectivo_estacion.php';</script>";
    }else{
        unset($_SESSION["codsucu1"]);
        unset($_SESSION["codesta1"]);
        echo "<script>window.location.href = 'recepcion_efectivo_estacion.php';</script>";
    }
}
?>
