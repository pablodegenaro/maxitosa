<?php
require ("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);


$archivo = $_FILES['archivo'];
$nomb_archi = explode('.', $archivo['name']);
if ($nomb_archi[1] == 'xls' || $nomb_archi[1] == 'xlsx') {
    $sDirGuardar = $_SERVER["DOCUMENT_ROOT"]."/".$archivo['name'];
    move_uploaded_file($archivo["tmp_name"], $sDirGuardar);
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($_SERVER["DOCUMENT_ROOT"]."/".$archivo['name']);
    error_reporting(E_ALL ^ E_NOTICE);
    if (($data->sheets[0]['cells'][3][1] == 'FICHA')
        and ($data->sheets[0]['cells'][3][2] == 'CI')
        and ($data->sheets[0]['cells'][3][3] == 'APELLIDOS Y NOMBRES')
        and ($data->sheets[0]['cells'][3][4] == 'EMPRESA')
        and ($data->sheets[0]['cells'][3][5] == 'SALARIO')
    ) {
        for ($row=4;$row<=(count($data->sheets[0]['cells'])+1);$row++) {
            $c1 = $c2 = $c3 = $c4 = '';
            for ($col=1;$col<=4;$col++) {
                switch ($col) {
                    case 1:
                    $c1 = $data->sheets[0]['cells'][$row][$col];
                    break;
                    case 2:
                    $c2 = $data->sheets[0]['cells'][$row][$col];
                    break;
                    case 3:
                    $c3 = $data->sheets[0]['cells'][$row][$col];
                    break;
                    case 4:
                    $c4 = $data->sheets[0]['cells'][$row][$col];
                    break;
                }
            }
            $query = mssql_query("INSERT INTO SAPRUEBA (colum1,colum2,colum3,colum4) VALUES('$c1','$C2','$c3','$c4')");
        }
        $_SESSION["mensaje"] = 'actualizada exitosamente';
        $_SESSION["info_msj"] = 'success';
    }else{
        $_SESSION["mensaje"] = 'Formato en el archivo invalido';
        $_SESSION["info_msj"] = 'danger';
    }
}else{
    $_SESSION["mensaje"] = 'El archivo no tiene extensión XLS';
    $_SESSION["info_msj"] = 'danger';
}
header ('Location: principal1.php?page=importar&mod=1');