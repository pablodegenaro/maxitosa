<?php
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require ("conexion.php");
require ("funciones.php");
require_once ("permisos/Mssql.php");

$id = $_POST['id'];
$codbanc = $_POST['codbanc'];
$items = $_POST['proc'];
$idDoc = $_POST['idDoc'];

if (count($items)>0) {
    foreach ($items as $item) {
        $arr = explode('-', $item);
        $trans = $arr[0];
        $doc = $arr[1];

        if (!empty($arr[0])){
            $query = mssql_query("UPDATE SBTRAN SET Estado=1 WHERE CodBanc='$codbanc' AND Estado=0 AND Documento='$trans'");
            if ($query) {
                $query1 = mssql_query("UPDATE Docitem_app SET Procesado=1 WHERE id='$doc' AND Procesado=0");
            }
        }
    }
}
$query2 = mssql_query("UPDATE Doc_app SET Procesado=1 WHERE idDoc='$idDoc'");

echo "<script language=Javascript> location.href=\"principal1.php?page=carga_extrato_bancara_ver&mod=1&i=$id\";</script>";