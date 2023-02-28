<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require ("conexion.php");
require ("funciones.php");
require ('phpExcel/Excel/reader.php');

$cod = array();
$maneja_factor = 1; 
$precio_manual = 1; 
$flete_me = 0; 
$pvp = array();
$iva = array();
$sugerido = array();
$Costo_Total = array();
$profit1 = array();
$profit2 = array();
$profit3 = array();
$Proveedor = array();

# evalua si es carga de un archivo excel o no
if(!empty($_FILES["file"]["name"])) {

    $archivo = $_FILES['file'];
    $nomb_archi = explode('.', $archivo['name']);

    # renombrar
    $file_ext = substr($archivo['name'], strripos($archivo['name'], '.')); // Verifica el nombre del archivo
    $nuevoNombre = sprintf("%s.%s", uniqid(), str_replace('.', '', $file_ext));

    $sDirGuardar = "./files_temp/".$nuevoNombre;
    if (!file_exists("./files_temp/")) {
        // verifica que la carpeta exista
        mkdir("./files_temp/", 0777, true);
    }
    $resp = move_uploaded_file($archivo["tmp_name"], $sDirGuardar);
    
    if ($resp==true) {
        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('CP1251');
        $data->read("./files_temp/".$nuevoNombre);
        error_reporting(E_ALL ^ E_NOTICE);

        //var_dump($data->sheets[0]['cells']);

        if (
            ($data->sheets[0]['cells'][1][1] == 'Codigo') and 
            ($data->sheets[0]['cells'][1][2] == 'Descripcion') and 
            ($data->sheets[0]['cells'][1][3] == 'Proveedor') and 
            ($data->sheets[0]['cells'][1][4] == 'Marca')
        ) {
            for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                $c1 = $c2 = $c3 = $c4 = '';
                $c5 = $c6 = $c7 = $c8 = $c9 = $c10 = $c11 = 0;
                for ($col=1;$col<=11;$col++) {
                    switch ($col) {
                        case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 5: $c5 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 6: $c6 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 7: $c7 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 8: $c8 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 9: $c9 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 10: $c10 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 11: $c11 = $data->sheets[0]['cells'][$row][$col]; break;
                    }
                }
                $cod[] = $c1;
                $prove[] = $c3;
                $pvp[] = $c6;
                $iva[] = $c7;
                $sugerido[] = $c8;
                $Costo_Total[] = $c5;
                $profit1[] = $c9;
                $profit2[] = $c10;
                $profit3[] = $c11;
            }
        }
    }
}

if (count($cod) > 0) 
{
    for ($i=0; $i<count($cod); $i++) 
    {
        $query = mssql_query("EXEC [Add_Factor] @CodProd ='$cod[$i]' ,
            @Proveedor = '$prove[$i]', 
            @Maneja_Factor =$maneja_factor, 
            @Precio_Manual =$precio_manual,
            @Pvp ='$pvp[$i]', 
            @Iva ='$iva[$i]', 
            @Sugerido ='$sugerido[$i]', 
            @Costo_Total ='$Costo_Total[$i]', 
            @Flete_ME ='$flete_me', 
            @Profit1 ='$profit1[$i]', 
            @Profit2 ='$profit2[$i]', 
            @Profit3 ='$profit3[$i]'");
    }
}

echo "<script language=Javascript> location.href=\"principal1.php?page=productos_precios&mod=1\";</script>";