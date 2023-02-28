<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $rete = $_POST['rete'];
  $usuario=$_SESSION['nombre_p'];

  $elimina = false;
  $mensaje = '';


  switch (true) {

    case ( $rete!="1" ):

    $query = mssql_query("UPDATE SAACXP set FromTran = 0 where TipoCXP in ('81','31','21','82') and FromTran = 1");

    if ($query) {

      $elimina = true;

    } 

    break;


    case ($rete!="0" ):

    $query = mssql_query("UPDATE SAACXP set FromTran = 1 where TipoCXP in ('81','31','21','82') and FromTran = 0");

    if ($query) {

      $elimina = true;

    }

    break;
    default:

  }

  if ($elimina) {
    $_SESSION['icono'] = "fa-check";
    $_SESSION['mensaje'] = "Procesado Exitosamente!";
    $_SESSION['bg_mensaje'] = "info";
  } else {
    $_SESSION['icono'] = "fa-exclamation-triangle";
    $_SESSION['mensaje'] = "Error al Procesar";
    $_SESSION['bg_mensaje'] = "danger";
  }

  echo "<script language=Javascript> location.href=\"principal1.php?page=paseretenciones&mod=1\";</script>";
}

?>