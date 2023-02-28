<?php
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require ("conexion.php");
require ("funciones.php");
require_once ("permisos/Mssql.php");
require ('phpExcel/Excel/reader.php');


$guarda = false;
$mensaje = '';


# CODIGOS DE BANCOS
#nota: si hay bancos que no se utilizaran colocarle guion " - "
define(BANCO_ACTIVO, '1110052600');
define(BANCO_BANCAMIGA, '1110053000');
define(BANCO_BANCARIBE, '1110051000');
define(BANCO_BANESCO, '1110050600');
define(BANCO_BANPLUS, '1110052100');
define(BANCO_BICENTENARIO, '1110052000');
define(BANCO_BNC, '1110051600');
define(BANCO_BOD, '');
define(BANCO_CARONI, '1110050700');
define(BANCO_DELSUR, '1110053200');
define(BANCO_EXTERIOR, '1110050400');
define(BANCO_MERCANTIL, '1110050300');
define(BANCO_PLAZA, '1110054100');
define(BANCO_PROVINCIAL, '1110050200');
define(BANCO_VENEZOLANO_DE_CREDITO, '1110055100');
define(BANCO_DEL_TESORO, '1110052800');
define(BANCO_DE_VENEZUELA, '1110050100');



# evalua si es carga de un archivo excel o no
if(!empty($_FILES["file"]["name"])) {
    $allowed_file_types = array('.xls', '.XLS'); // tipos de archivos aceptados

    if (!file_exists("files_temp/")) {
        // verifica que la carpeta exista
        mkdir("files_temp/", 0777, true);
    }

    $archivo = $_FILES['file'];
    $nomb_archi = $archivo['name'];
    $file_ext = substr($archivo['name'], strripos($archivo['name'], '.')); // Verifica el nombre del archivo
    
    if (in_array($file_ext, $allowed_file_types)) {
        # renombrar
        $codbanc = $_POST['codbanc'];
        $banc = mssql_query("SELECT CodBanc, Descripcion, NoCuenta FROM SBBANC WHERE Activo=1 AND CodBanc = '$codbanc'");
        $DescripBanc = mssql_result($banc, 0, "Descripcion");
        $NroCtaBanc = mssql_result($banc, 0, "NoCuenta");
        $fecha1 = date('Y_m_d_H_m_s');
        $nuevoNombre = sprintf("%s.%s", $DescripBanc."_".$fecha1, str_replace('.', '', $file_ext));

        $sDirGuardar = "./files_temp/".$nuevoNombre;
        $resp = move_uploaded_file($archivo["tmp_name"], $sDirGuardar);
        
        if ($resp) {
            $data = new Spreadsheet_Excel_Reader();
            $data->setOutputEncoding('CP1251');
            $data->read("./files_temp/".$nuevoNombre);
            error_reporting(E_ALL ^ E_NOTICE);

            //echo json_encode($data->sheets[0]['cells']);
            switch($codbanc) {
                #====================
                # ==BANCO VENEZUELA==
                #====================
                case BANCO_DE_VENEZUELA: 
                if (
                    (trim($data->sheets[0]['cells'][1][1]) == 'fecha') and 
                    (trim($data->sheets[0]['cells'][1][2]) == 'referencia') and 
                    (trim($data->sheets[0]['cells'][1][3]) == 'concepto') and 
                    (trim($data->sheets[0]['cells'][1][4]) == 'saldo') and 
                    (trim($data->sheets[0]['cells'][1][5]) == 'monto') 
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                    
                        # items
                    for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                        $c2 = $c4 = $c5 = $c6 = $c7 = $c8 = '';
                        for ($col=1;$col<=8;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: 
                                $c4 = $data->sheets[0]['cells'][$row][$col]; 
                                $c4 = (!empty($c4)) ? floatval(str_replace(",",'.',str_replace(".",'',$c4))) : 0; break;
                                case 5: 
                                $c5 = $data->sheets[0]['cells'][$row][$col]; 
                                $c5 = (!empty($c5)) ? floatval(str_replace(",",'.',str_replace(".",'',$c5))) : 0; break;
                                case 6: $c6 = $data->sheets[0]['cells'][$row][$col]; break;
                            }
                        }
                            # parseo de fecha
                        $fecha_arr = explode('/', $c1);
                        $dia = $fecha_arr[0];
                        $mes = $fecha_arr[1];
                        $anio = $fecha_arr[2];
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            #seleccion de monto
                        $debito = $credito = 0;
                        switch(true) {
                            case $c5>0: $credito = $c5; break;
                            case $c5<0:  $debito = $c5; break;
                        }
                            # paseo de monto (evita negativo)
                        $debito = floatval(str_replace('-','', $debito));
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Concepto, Debito, Credito, Saldo, TipoTrans, Refere)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c3',$debito,$credito,$c4,'$c6','$c2')");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                    
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #====================
                # ==BANCO BANCARIBE==
                #====================
                case BANCO_BANCARIBE: 
                $nrocta =  str_replace("'","", $data->sheets[0]['cells'][1][1]);
                    # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$nrocta','$fecha','$Usua',0)");
                    # items
                for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                    $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = $c7 = $c8 = '';
                    for ($col=1;$col<=8;$col++) {
                        switch ($col) {
                            case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 5: 
                            $c5 = $data->sheets[0]['cells'][$row][$col]; 
                            $c5 = (!empty($c5)) ? $c5 : 0; break;
                            case 6: 
                            $c6 = $data->sheets[0]['cells'][$row][$col]; 
                            $c6 = (!empty($c6)) ? $c6 : 0; break;
                            case 7: 
                            $c7 = $data->sheets[0]['cells'][$row][$col]; 
                            $c7 = (!empty($c7)) ? $c7 : 0; break;
                            case 8: $c8 = $data->sheets[0]['cells'][$row][$col]; break;
                        }
                    }
                        # parseo de fecha
                    $dia = substr($c1,0,2);
                    $mes = substr($c1,3,2);
                    $anio = substr($c1,6,4);
                    $c1 = $anio.'-'.$mes.'-'.$dia;
                        # insersion de item
                    $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, CodTrans, Debito, Credito, Saldo, TipoTrans)
                        VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2','$c3','$c8',$c5,$c6,$c7,'$c4')");
                    if (!$docitemApp) {
                        $insertItems = false;
                    }
                }
                

                if ($docApp && $docitemApp) {
                    $guarda = true;
                    $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                } else {
                    $mensaje = "Ocurrio un error al agregar $nomb_archi";
                }
                break;
                #==================
                # ==BANCO ACTIVO===
                #==================
                case BANCO_ACTIVO:
                if (
                    (trim($data->sheets[0]['cells'][2][4]) == 'Referencia') and 
                    (trim($data->sheets[0]['cells'][2][7]) == 'Monto') and 
                    (trim($data->sheets[0]['cells'][2][8]) == 'Saldo')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                        # items
                    for ($row=3;$row<=count($data->sheets[0]['cells']);$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = $c7 = $c8 = '';
                        for ($col=1;$col<=8;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 5: $c5 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 6: $c6 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 7: 
                                $c7 = $data->sheets[0]['cells'][$row][$col]; 
                                $c7 = (!empty($c7)) ? floatval(str_replace(',','.', $c7)) : 0; break;
                                case 8: 
                                $c8 = $data->sheets[0]['cells'][$row][$col]; 
                                $c8 = (!empty($c8)) ? floatval(str_replace(',','.', $c8)) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c1,0,2);
                        $mes = substr($c1,3,2);
                        $anio = substr($c1,6,4);
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            #seleccion de monto
                        $debito = $credito = 0;
                        switch($c6) {
                            case 'CREDITO': $credito = $c7; break;
                            case 'DEBITO':  $debito = $c7; break;
                        }
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, CodTrans, Debito, Credito, Saldo, TipoTrans)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c4','$c3','$c5',$debito,$credito,$c8,'$c6')");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #==================
                # ==BANCO BANESCO===
                #==================
                case BANCO_BANESCO:
                if (
                    (trim($data->sheets[0]['cells'][1][1]) == 'Fecha') and 
                    (trim($data->sheets[0]['cells'][1][2]) == 'Referencia') and 
                    (trim($data->sheets[0]['cells'][1][4]) == 'Monto') and 
                    (trim($data->sheets[0]['cells'][1][5]) == 'Balance')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                        # items
                    for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = '';
                        for ($col=1;$col<=5;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: 
                                $c4 = $data->sheets[0]['cells'][$row][$col]; 
                                $c4 = (!empty($c4)) ? floatval($c4) : 0; break;
                                case 5: 
                                $c5 = $data->sheets[0]['cells'][$row][$col]; 
                                $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c1,0,2);
                        $mes = substr($c1,3,2);
                        $anio = substr($c1,6,4);
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            #seleccion de monto
                        $debito = $credito = 0;
                        switch(true) {
                            case $c4>0: $credito = $c4; break;
                            case $c4<0:  $debito = $c4; break;
                        }
                            # paseo de monto (evita negativo)
                        $debito = floatval(str_replace('-','', $debito));
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, Debito, Credito, Saldo)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2','$c3',$debito,$credito,$c5)");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #=====================
                # ==BANCO BANCAMIGA===
                #=====================
                case BANCO_BANCAMIGA:
                if (
                    (trim($data->sheets[0]['cells'][3][2]) == 'Fecha') and 
                    (trim($data->sheets[0]['cells'][3][3]) == 'Referencia') and 
                    (trim($data->sheets[0]['cells'][3][4]) == 'Concepto') and 
                    (trim($data->sheets[0]['cells'][3][7]) == 'Saldo')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                        # items
                    for ($row=4;$row<=count($data->sheets[0]['cells']);$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = $c7 = '';
                        for ($col=1;$col<=7;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 3: 
                                $c3 = $data->sheets[0]['cells'][$row][$col];
                                $c3 =  str_replace("'","", $c3);     
                                break;
                                case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 5: 
                                $c5 = $data->sheets[0]['cells'][$row][$col]; 
                                $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                                case 6: 
                                $c6 = $data->sheets[0]['cells'][$row][$col]; 
                                $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                                case 7: 
                                $c7 = $data->sheets[0]['cells'][$row][$col]; 
                                $c7 = (!empty($c7)) ? floatval($c7) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c2,0,2);
                        $mes = substr($c2,3,2);
                        $anio = substr($c2,6,4);
                        $c2 = $anio.'-'.$mes.'-'.$dia;
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, Debito, Credito, Saldo)
                            VALUES ('$nuevoNombre','$DescripBanc','$c2','$c3','$c4',$c5,$c6,$c7)");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #===================
                # ==BANCO BANPLUS===
                #===================
                case BANCO_BANPLUS:
                if (
                    (trim($data->sheets[0]['cells'][1][1]) == 'Fecha') and 
                    (trim($data->sheets[0]['cells'][1][2]) == 'Referencia') and 
                    (trim($data->sheets[0]['cells'][1][6]) == 'Saldo')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                        # items
                    for ($row=3;$row<=count($data->sheets[0]['cells'])-1;$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = '';
                        for ($col=1;$col<=6;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: 
                                $c2 = $data->sheets[0]['cells'][$row][$col];
                                $c2 =  str_replace("'","", $c2);     
                                break;
                                case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: 
                                $c4 = $data->sheets[0]['cells'][$row][$col]; 
                                $c4 = (!empty($c4)) ? floatval($c4) : 0; break;
                                case 5: 
                                $c5 = $data->sheets[0]['cells'][$row][$col]; 
                                $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                                case 6: 
                                $c6 = $data->sheets[0]['cells'][$row][$col]; 
                                $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c1,0,2);
                        $mes = substr($c1,3,2);
                        $anio = substr($c1,6,4);
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, Debito, Credito, Saldo)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2','$c3',$c4,$c5,$c6)");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #========================
                # ==BANCO BICENTENARIO===
                #========================
                case BANCO_BICENTENARIO:
                if (
                    (trim($data->sheets[0]['cells'][11][3]) == 'FECHA') and 
                    (trim($data->sheets[0]['cells'][11][4]) == 'REFERENCIA') and
                    (trim($data->sheets[0]['cells'][11][5]) == 'CONCEPTO') and
                    (trim($data->sheets[0]['cells'][11][6]) == 'CARGO') and 
                    (trim($data->sheets[0]['cells'][11][7]) == 'ABONO')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                    
                        # items
                    for ($row=12;$row<=count($data->sheets[0]['cells'])+2;$row++) {
                        $c3 = $c4 = $c5 = $c6 = $c7 = '';
                        for ($col=3;$col<=7;$col++) {
                            switch ($col) {
                                case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 5: $c5 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 6: 
                                $c6 = $data->sheets[0]['cells'][$row][$col]; 
                                $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                                case 7: 
                                $c7 = $data->sheets[0]['cells'][$row][$col]; 
                                $c7 = (!empty($c7)) ? floatval($c7) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c3,0,2);
                        $mes = substr($c3,3,2);
                        $anio = substr($c3,6,4);
                        $c3 = $anio.'-'.$mes.'-'.$dia;
                            # saldo
                        $saldo = 0;
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, Debito, Credito, Saldo)
                            VALUES ('$nuevoNombre','$DescripBanc','$c3','$c4','$c5',$c6,$c7,$saldo)");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #===============
                # ==BANCO BNC===
                #===============
                case BANCO_BNC:
                if (
                    (trim($data->sheets[0]['cells'][6][1]) == 'Fecha') and 
                    (trim($data->sheets[0]['cells'][6][2]) == 'Referencia') and
                    (trim($data->sheets[0]['cells'][6][5]) == 'Debe') and 
                    (trim($data->sheets[0]['cells'][6][6]) == 'Haber') and 
                    (trim($data->sheets[0]['cells'][6][7]) == 'Saldo')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                    
                        # items
                    for ($row=7;$row<=count($data->sheets[0]['cells'])+1;$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = $c7 = '';
                        for ($col=1;$col<=7;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 5: 
                                $c5 = $data->sheets[0]['cells'][$row][$col]; 
                                $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                                case 6: 
                                $c6 = $data->sheets[0]['cells'][$row][$col]; 
                                $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                                case 7: 
                                $c7 = $data->sheets[0]['cells'][$row][$col]; 
                                $c7 = (!empty($c7)) ? floatval($c7) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c1,0,2);
                        $mes = substr($c1,3,2);
                        $anio = substr($c1,6,4);
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            # paseo de monto (evita negativo)
                        $c5 = floatval(str_replace('+','', str_replace('-','', $c5)));
                        $c6 = floatval(str_replace('+','', str_replace('-','', $c6)));
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, TipoTrans, Concepto, Debito, Credito, Saldo)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2','$c3','$c4',$c5,$c6,$c7)");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #===============
                # ==BANCO BOD===
                #===============
                case BANCO_BOD:
                    # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                
                    # items
                for ($row=2;$row<=count($data->sheets[0]['cells'])+1;$row++) {
                    $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = $c7 = '';
                    for ($col=1;$col<=7;$col++) {
                        switch ($col) {
                            case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 5: 
                            $c5 = $data->sheets[0]['cells'][$row][$col]; 
                            $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                            case 6: 
                            $c6 = $data->sheets[0]['cells'][$row][$col]; 
                            $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                            case 7: $c7 = $data->sheets[0]['cells'][$row][$col]; break;
                        }
                    }
                        # parseo de fecha
                    $dia = substr($c3,0,2);
                    $mes = substr($c3,3,2);
                    $anio = substr($c3,6,4);
                    $c3 = $anio.'-'.$mes.'-'.$dia;
                        # paseo de monto (evita negativo)
                    $c5 = floatval(str_replace('-','', $c5));
                    $saldo = 0;
                        # insersion de item
                    $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, Debito, Credito, Saldo)
                        VALUES ('$nuevoNombre','$DescripBanc','$c3','$c4','$c7',$c5,$c6,$saldo)");
                    if (!$docitemApp) {
                        $insertItems = false;
                    }
                }

                if ($docApp && $docitemApp) {
                    $guarda = true;
                    $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                } else {
                    $mensaje = "Ocurrio un error al agregar $nomb_archi";
                }
                break;
                #==================
                # ==BANCO CARONI===
                #==================
                case BANCO_CARONI:
                if (
                    (trim($data->sheets[0]['cells'][1][2]) == 'Fecha') and 
                    (trim($data->sheets[0]['cells'][1][3]) == 'No. de Cheque') and 
                    (trim($data->sheets[0]['cells'][1][7]) == 'Saldo')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                        # items
                    for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = '';
                        for ($col=1;$col<=7;$col++) {
                            switch ($col) {
                                case 1: $c1 = trim($data->sheets[0]['cells'][$row][$col]); break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 5: 
                                $c5 = $data->sheets[0]['cells'][$row][$col]; 
                                $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                                case 6: 
                                $c6 = $data->sheets[0]['cells'][$row][$col]; 
                                $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                                case 7: 
                                $c7 = $data->sheets[0]['cells'][$row][$col]; 
                                $c7 = (!empty($c7)) ? floatval($c7) : 0; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c2,0,2);
                        $mes = substr($c2,3,2);
                        $anio = substr($c2,6,4);
                        $c2 = $anio.'-'.$mes.'-'.$dia;
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Concepto, Debito, Credito, Saldo, CodTrans)
                            VALUES ('$nuevoNombre','$DescripBanc','$c2','$c1',$c5,$c6,$c7,'$c4')");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #==================
                # ==BANCO DELSUR===
                #==================
                case BANCO_DELSUR:
                if (
                    (trim($data->sheets[0]['cells'][1][1]) == 'Fecha') and 
                    (trim($data->sheets[0]['cells'][1][2]) == 'Concepto') and 
                    (trim($data->sheets[0]['cells'][1][3]) == 'Asignacion') and 
                    (trim($data->sheets[0]['cells'][1][4]) == 'Importe') and 
                    (trim($data->sheets[0]['cells'][1][5]) == 'Oficina')
                ) {
                        # cabecera
                    $fecha = date('Y-m-d H:i:s');
                    $Usua = $_SESSION['login'];
                    $insertItems = true;
                    $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                        VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");

                    $saldo = floatval($data->sheets[0]['cells'][2][4]);
                        # items
                    for ($row=3;$row<=count($data->sheets[0]['cells'])-1;$row++) {
                        $c1 = $c2 = $c3 = $c4 = $c5 = '';
                        for ($col=1;$col<=5;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: 
                                $c4 = $data->sheets[0]['cells'][$row][$col]; 
                                $c4 = (!empty($c4)) ? floatval($c4) : 0; break;
                                case 5: $c5 = $data->sheets[0]['cells'][$row][$col]; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c1,0,2);
                        $mes = substr($c1,3,2);
                        $anio = substr($c1,6,4);
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            #seleccion de monto
                        $debito = $credito = 0;
                        switch(true) {
                            case $c4>0: $credito = $c4; break;
                            case $c4<0:  $debito = $c4; break;
                        }
                        $saldo+=$c4;
                            # paseo de monto (evita negativo)
                        $debito = floatval(str_replace('-','', $debito));
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Concepto, Debito, Credito, Saldo, Oficina)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2',$debito,$credito,$saldo,'$c5')");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }

                    if ($docApp && $docitemApp) {
                        $guarda = true;
                        $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                    } else {
                        $mensaje = "Ocurrio un error al agregar $nomb_archi";
                    }
                } else {
                    $guarda = false;
                    $mensaje = "El archivo no coincide con el Banco seleccionado.";
                }
                break;
                #====================
                # ==BANCO EXTERIOR===
                #====================
                case BANCO_EXTERIOR:
                    # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                    # items
                for ($row=1;$row<=count($data->sheets[0]['cells']);$row++) {
                    $c2 = $c4 = $c6 = $c8 = $c12 = $c14 = '';
                    for ($col=1;$col<=14;$col++) {
                        switch ($col) {
                            case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 6: $c6 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 8: $c8 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 12: 
                            $c12 = $data->sheets[0]['cells'][$row][$col]; 
                            $c12 = (!empty($c12)) ? floatval($c12) : 0; break;
                            case 14: 
                            $c14 = $data->sheets[0]['cells'][$row][$col]; 
                            $c14 = (!empty($c14)) ? floatval($c14) : 0; break;
                        }
                    }
                        # parseo de fecha
                    $dia = substr($c4,0,2);
                    $mes = substr($c4,3,2);
                    $anio = substr($c4,6,4);
                    $c4 = $anio.'-'.$mes.'-'.$dia;
                        #seleccion de monto
                    $debito = $credito = 0;
                    switch(true) {
                        case $c12>0: $credito = $c12; break;
                        case $c12<0:  $debito = $c12; break;
                    }
                        # paseo de monto (evita negativo)
                    $debito = floatval(str_replace('-','', $debito));
                        # insersion de item
                    $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, CodTrans, Concepto, Debito, Credito, Saldo)
                        VALUES ('$nuevoNombre','$DescripBanc','$c4','$c6','$c8',$debito,$credito,$c14)");
                    if (!$docitemApp) {
                        $insertItems = false;
                    }
                }

                if ($docApp && $docitemApp) {
                    $guarda = true;
                    $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                } else {
                    $mensaje = "Ocurrio un error al agregar $nomb_archi";
                }
                break;
                #=====================
                # ==BANCO MERCANTIL===
                #=====================
                case BANCO_MERCANTIL:
                    # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                    # items
                for ($row=1;$row<=count($data->sheets[0]['cells']);$row++) {
                    if ($data->sheets[0]['cells'][$row][9]!='0') 
                    {
                        $c1 = $c2 = $c4 = $c6 = $c7 = $c8 = $c9 = '';
                        for ($col=1;$col<=9;$col++) {
                            switch ($col) {
                                case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 4: $c4 = $data->sheets[0]['cells'][$row][$col]; break;
                                case 6: 
                                $c6 = $data->sheets[0]['cells'][$row][$col]; 
                                $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                                case 7: 
                                $c7 = $data->sheets[0]['cells'][$row][$col]; 
                                $c7 = (!empty($c7)) ? floatval($c7) : 0; break;
                                case 8: 
                                $c8 = $data->sheets[0]['cells'][$row][$col]; 
                                $c8 = (!empty($c8)) ? floatval($c8) : 0; break;
                                case 9: $c9 = $data->sheets[0]['cells'][$row][$col]; break;
                            }
                        }
                            # parseo de fecha
                        $dia = substr($c1,0,2);
                        $mes = substr($c1,3,2);
                        $anio = substr($c1,6,4);
                        $c1 = $anio.'-'.$mes.'-'.$dia;
                            # insersion de item
                        $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Concepto, Debito, Credito, Saldo, CodTrans)
                            VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2',$c6,$c7,$c8,'$c9')");
                        if (!$docitemApp) {
                            $insertItems = false;
                        }
                    }
                }

                if ($docApp && $docitemApp) {
                    $guarda = true;
                    $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                } else {
                    $mensaje = "Ocurrio un error al agregar $nomb_archi";
                }
                break;
                #=================
                # ==BANCO PLAZA===
                #=================
                case BANCO_PLAZA:
                    # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                    # items
                for ($row=3;$row<=count($data->sheets[0]['cells']);$row++) {
                    $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = '';
                    for ($col=1;$col<=6;$col++) {
                        switch ($col) {
                            case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 4: 
                            $c4 = $data->sheets[0]['cells'][$row][$col]; 
                            $c4 = (!empty($c4)) ? floatval( str_replace(',','.',str_replace('.','', $c4))) : 0; break;
                            case 5: 
                            $c5 = $data->sheets[0]['cells'][$row][$col]; 
                            $c5 = (!empty($c5)) ? floatval(str_replace(',','.',str_replace('.','', $c5))) : 0; break;
                            case 6: 
                            $c6 = $data->sheets[0]['cells'][$row][$col]; 
                            $c6 = (!empty($c6)) ? floatval(str_replace(',','.',str_replace('.','', $c6))) : 0; break;
                        }
                    }
                        # parseo de fecha
                    $dia = substr($c1,0,2);
                    $mes = substr($c1,3,2);
                    $anio = substr($c1,6,4);
                    $c1 = $anio.'-'.$mes.'-'.$dia;
                        # paseo de monto (evita negativo)
                    $c4 = floatval(str_replace('-','', $c4));
                        # insersion de item
                    $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Concepto, Debito, Credito, Saldo, Refere)
                        VALUES ('$nuevoNombre','$DescripBanc','$c1','$c3',$c4,$c5,$c6,'$c2')");
                    if (!$docitemApp) {
                        $insertItems = false;
                    }
                }

                if ($docApp && $docitemApp) {
                    $guarda = true;
                    $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                } else {
                    $mensaje = "Ocurrio un error al agregar $nomb_archi";
                }
                break;
                #======================
                # ==BANCO PROVINCIAL===
                #======================
                case BANCO_PROVINCIAL:
                    # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                $saldo = 0;
                    # items
                    //echo json_encode(array('CANTIDAD CELDAS ' => end(array_keys($data->sheets[0]['cells']))));
                for ($row=6;$row<=end(array_keys($data->sheets[0]['cells']));$row++) {
                    if(preg_match('/Saldo Inicial/i', $data->sheets[0]['cells'][$row][5])) {
                        $saldo = floatval($data->sheets[0]['cells'][$row][6]);
                    } 
                    elseif(!empty($data->sheets[0]['cells'][$row][5]) 
                        && !preg_match('/ Concepto/i', $data->sheets[0]['cells'][$row][5]) 
                        && !preg_match('/Saldo Inicial/i', $data->sheets[0]['cells'][$row][5]) 
                        && !preg_match('/Saldo Final/i', $data->sheets[0]['cells'][$row][5])
                    ) {
                        $c1 = $c3 = $c4 = $c5 = $c6 = $c7 = '';
                    for ($col=1;$col<=7;$col++) {
                        switch ($col) {
                            case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 3: $c3 = trim($data->sheets[0]['cells'][$row][$col]); break;
                            case 4: 
                            $c4 = trim($data->sheets[0]['cells'][$row][$col]);
                            $c4 = str_replace("'","", $c4);      
                            break;
                            case 5: $c5 = trim($data->sheets[0]['cells'][$row][$col]); break;
                            case 6: 
                            $c6 = $data->sheets[0]['cells'][$row][$col]; 
                            $c6 = (!empty($c6)) ? floatval(str_replace(",",'',$c6)) : 0; break;
                            case 7: 
                            $c7 = $data->sheets[0]['cells'][$row][$col]; 
                            $c7 = str_replace("'",'', $c7); break;
                        }
                    }
                            # parseo de fecha
                    $fecha_arr = explode('/', $c1);
                    $dia = $fecha_arr[0];
                    $mes = $fecha_arr[1];
                    $anio = $fecha_arr[2];
                    $c1 = $anio.'-'.$mes.'-'.$dia;
                            #seleccion de monto
                    $debito = $credito = 0;
                    switch(true) {
                        case $c6>0: $credito = $c6; break;
                        case $c6<0:  $debito = $c6; break;
                    }
                    $saldo+=$c6;
                            # paseo de monto (evita negativo)
                    $debito = floatval(str_replace('-','', $debito));
                            # insersion de item
                    $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, CodTrans, Concepto, Debito, Credito, Saldo, Refere, Oficina)
                        VALUES ('$nuevoNombre','$DescripBanc','$c1','$c3','$c5',$debito,$credito,$saldo,'$c4','$c7')");
                    if (!$docitemApp) {
                        $insertItems = false;
                    }
                }
            }

            if ($docApp && $docitemApp) {
                $guarda = true;
                $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
            } else {
                $mensaje = "Ocurrio un error al agregar $nomb_archi";
            }
            break;
                #=================================
                # ==BANCO VENEZOLANO DE CREDITO===
                #=================================
            case BANCO_VENEZOLANO_DE_CREDITO:
                    # cabecera
            $fecha = date('Y-m-d H:i:s');
            $Usua = $_SESSION['login'];
            $insertItems = true;
            $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
            
            $saldo = 0;
                    # items
            for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                $c1 = $c2 = $c3 = $c4 = $c5 = '';
                for ($col=1;$col<=5;$col++) {
                    switch ($col) {
                        case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 2: $c2 = trim($data->sheets[0]['cells'][$row][$col]); break;
                        case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                        case 4: 
                        $c4 = $data->sheets[0]['cells'][$row][$col]; 
                        $c4 = (!empty($c4)) ? floatval($c4) : 0; break;
                        case 5: 
                        $c5 = $data->sheets[0]['cells'][$row][$col]; 
                        $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                    }
                }
                        # parseo de fecha
                $fecha_arr = explode('/', $c1);
                $dia = $fecha_arr[0];
                $mes = $fecha_arr[1];
                $anio = $fecha_arr[2];
                $c1 = $anio.'-'.$mes.'-'.$dia;
                        #seleccion de monto
                if ($c4 > 0) {
                    $saldo-=$c4;
                }
                if ($c5 > 0) {
                    $saldo+=$c5;
                }
                        # insersion de item
                $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Concepto, Refere, Debito, Credito, Saldo)
                    VALUES ('$nuevoNombre','$DescripBanc','$c1','$c2','$c3',$c4,$c5,$saldo)");
                if (!$docitemApp) {
                    $insertItems = false;
                }
            }

            if ($docApp && $docitemApp) {
                $guarda = true;
                $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
            } else {
                $mensaje = "Ocurrio un error al agregar $nomb_archi";
            }
            break;
                #======================
                # ==BANCO DEL TESORO===
                #======================
            case BANCO_DEL_TESORO:
            if (
                (trim($data->sheets[0]['cells'][1][1]) == 'Fecha') and 
                (trim($data->sheets[0]['cells'][1][3]) == 'Referencia') and 
                (trim($data->sheets[0]['cells'][1][4]) == 'Cargo') and 
                (trim($data->sheets[0]['cells'][1][5]) == 'Abono')
            ) {
                        # cabecera
                $fecha = date('Y-m-d H:i:s');
                $Usua = $_SESSION['login'];
                $insertItems = true;
                $docApp = mssql_query("INSERT INTO Doc_App (idDoc, NombreDoc, CodBanc, NroCta, FechaE, Usua, Procesado) 
                    VALUES ('$nuevoNombre','$nomb_archi','$codbanc','$NroCtaBanc','$fecha','$Usua',0)");
                        # items
                for ($row=2;$row<=count($data->sheets[0]['cells']);$row++) {
                    $c1 = $c2 = $c3 = $c4 = $c5 = $c6 = $c7 = '';
                    for ($col=1;$col<=7;$col++) {
                        switch ($col) {
                            case 1: $c1 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 2: $c2 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 3: $c3 = $data->sheets[0]['cells'][$row][$col]; break;
                            case 4: 
                            $c4 = $data->sheets[0]['cells'][$row][$col]; 
                            $c4 = (!empty($c4)) ? floatval($c4) : 0; break;
                            case 5: 
                            $c5 = $data->sheets[0]['cells'][$row][$col]; 
                            $c5 = (!empty($c5)) ? floatval($c5) : 0; break;
                            case 6: 
                            $c6 = $data->sheets[0]['cells'][$row][$col]; 
                            $c6 = (!empty($c6)) ? floatval($c6) : 0; break;
                        }
                    }
                            # parseo de fecha
                    $dia = substr($c1,0,2);
                    $mes = substr($c1,3,2);
                    $anio = substr($c1,6,4);
                    $c1 = $anio.'-'.$mes.'-'.$dia;
                            # insersion de item
                    $docitemApp = mssql_query("INSERT INTO Docitem_App (idDoc, NomperBanc, FechaE, Refere, Concepto, Debito, Credito, Saldo)
                        VALUES ('$nuevoNombre','$DescripBanc','$c1','$c3','$c2',$c4,$c5,$c6)");
                    if (!$docitemApp) {
                        $insertItems = false;
                    }
                }

                if ($docApp && $docitemApp) {
                    $guarda = true;
                    $mensaje = "Archivo $nomb_archi Agregado exitosamente!";
                } else {
                    $mensaje = "Ocurrio un error al agregar $nomb_archi";
                }
            } else {
                $guarda = false;
                $mensaje = "El archivo no coincide con el Banco seleccionado.";
            }
            break;
        }
    }
}
}

if ($guarda) {
    $_SESSION['icono'] = "fa-check";
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['bg_mensaje'] = "success";
} else {
    $_SESSION['icono'] = "fa-exclamation-triangle";
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['bg_mensaje'] = "warning";
}

echo "<script language=Javascript> location.href=\"principal1.php?page=carga_extrato_bancara&mod=1\";</script>";