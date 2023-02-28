<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require ("Functions.php");
require_once ("permisos/Mssql.php");

switch ($_GET["op"]) {

    case 'index':
    $sucursal = $_SESSION["codsucu"];
    
    $sacorrel =  mssql_query("SELECT Prefijo, ValueInt FROM SACORRELSIS WHERE FieldName='PrxProf' AND CodSucu='$codsucu'");
    $saconf =  mssql_query("SELECT FactorM FROM SACONF WHERE CodSucu='$codsucu'");

    $data = array (
        "correl" => mssql_result($sacorrel, 0, "Prefijo").str_pad(mssql_result($sacorrel, 0, "ValueInt"), 8, 0, STR_PAD_LEFT),
        "factor" => Functions::rdecimal(mssql_result($saconf, 0, 'FactorM'), 2),
    );
    
    echo json_encode($data);
    break;

    case 'datos_clie':
    $codclie = $_POST['codclie'];

    $datos =  mssql_query("SELECT c.CodVend, v.Descrip, c9.convenio, ISNULL(con.descripcion,'') nomperConvenio, c.TipoPVP, c.PagosA, c.DiasCred 
        FROM SACLIE c INNER JOIN SACLIE_99 c9 ON c9.CodClie=c.CodClie 
        LEFT JOIN SAVEND v ON v.CodVend=c.CodVend
        LEFT JOIN convenio_configuracion con ON con.nivel_precio=(c9.convenio+3)
        WHERE c.CodClie='$codclie'");

    if (mssql_num_rows($datos)>0) {
        $data = array (
            "codvend"  => mssql_result($datos, 0, 'CodVend'),
            "vendedor" => mssql_result($datos, 0, 'Descrip'),
            "precio"   => mssql_result($datos, 0, 'TipoPVP'),
            "pagosa"   => floatval(mssql_result($datos, 0, 'PagosA')),
            "cred"     => intval(mssql_result($datos, 0, 'DiasCred')),
            "convenio" => intval(mssql_result($datos, 0, 'convenio')),
            "nomperConvenio" => mssql_result($datos, 0, 'nomperConvenio'),
        );
    } else {
        $data = array (
            "codvend"  => "N/D",
            "vendedor" => "N/D",
            "precio"   => 1,
            "pagosa"   => 0,
            "convenio" => 0,
            "nomperConvenio" => "",
        );
    }

    echo json_encode($data);
    break;

    case "buscar_cant_prd":
    $search = $_POST["search"];
    $depo = $_POST["depo"];
    $sucursal = $_SESSION["codsucu"];
    if ($search!=='') {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT p.CodProd, p.Descrip, e.Existen Bul, e.ExUnidad Paq FROM SAPROD p 
                INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
                INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
                WHERE d.Clase='$sucursal' AND (p.CodProd LIKE '%$search%' OR p.Descrip LIKE '%$search%')")
        );
    } else {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT p.CodProd, p.Descrip, e.Existen Bul, e.ExUnidad Paq FROM SAPROD p 
                INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
                INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
                WHERE d.Clase='$sucursal'")
        );
    }

    
    echo json_encode(
        array(
            "c" => count($datos)
        )
    );
    break;

    case "listar_prd":
    $search = $_POST["search"];
    $depo = $_POST["depo"];
    $sucursal = $_SESSION["codsucu"];
    if ($search!=='') {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT p.CodProd, p.Descrip, CONVERT(INT, e.Existen) Bul, CONVERT(INT, e.ExUnidad) Paq 
                FROM SAPROD p INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
                INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
                WHERE d.Clase='$sucursal' AND (p.CodProd LIKE '%$search%' OR p.Descrip LIKE '%$search%')")
        );
    } else {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT p.CodProd, p.Descrip, CONVERT(INT, e.Existen) Bul, CONVERT(INT, e.ExUnidad) Paq 
                FROM SAPROD p INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
                INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
                WHERE d.Clase='$sucursal'")
        );
    }
    

        //declaramos el array
    $data = array();
    foreach ($datos as $key => $row) {
        $sub_array = array();

        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="seleccionarPrd(\'' . $row['CodProd'] . '\');"  
        id="' . $row['CodProd'] . '" 
        class="btn btn-outline-saint btn-xs">
        Seleccionar
        </button>
        </div>';
        $sub_array[] = $row["CodProd"];
        $sub_array[] = utf8_encode($row["Descrip"]);
        $sub_array[] = Functions::rdecimal($row["Bul"], 0);
        $sub_array[] = Functions::rdecimal($row["Paq"], 0);

        $data[] = $sub_array;
    }

    $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );

    echo json_encode($results);
    break;

    case 'datos_prod':
    $codprod = $_POST['codprod'];
    $depo = $_POST["depo"];
    $unid = $_POST['unid'];
    $cant = $_POST['cant'];
    $precio = $_POST['precio'];
    $tasa = $_POST["tasa"];
    $sucursal = $_SESSION["codsucu"];

    if ($cant=="") {
        $cant = 1;
    }

    $datos =  Mssql::fetch_assoc(
        mssql_query("SELECT p.CodProd, p.Descrip, CONVERT(INT, p.CantEmpaq) CantEmpaq, CONVERT(INT, e.Existen) Cajas, CONVERT(INT, e.ExUnidad) Botellas, CONVERT(INT, e.CantCom) CajasCompr, CONVERT(INT, e.UnidCom) BotellasCompr,
            p.Unidad, p.UndEmpaq, ROUND(p.Precio$precio/(CASE WHEN  '$unid' = '1' THEN p.CantEmpaq ELSE 1 END),2,1) AS Precio, 
            ROUND(ISNULL((SELECT CASE WHEN EsPorct=1 THEN (Monto/100) * ROUND(p.Precio$precio,(CASE WHEN '$unid' = '1' THEN 1 ELSE 2 END),1) ELSE Monto END FROM SATAXPRD tax WHERE tax.CodProd=p.CodProd AND CodTaxs='IVA'), 0)/(CASE WHEN '$unid' = '1' THEN p.CantEmpaq ELSE 1 END),4) AS iva
            FROM SAPROD p 
            INNER JOIN SAPROD_99 p9 ON p9.CodProd=p.CodProd
            INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
            INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
            WHERE d.Clase='$sucursal' AND (p.CodProd = '$codprod' OR p.Descrip LIKE '%$codprod%')")
    );
    $datos = $datos[0];

    $total = ($datos['Precio'] + $datos['ial'] + $datos['pvp'] + $datos['iva']);

    $data = array (
        "codprod" => $datos['CodProd'],
        "descrip" => utf8_encode($datos["Descrip"]),
        "und" => substr($datos['UndEmpaq'], 0, 3),
        "umb" => $datos['Cajas'],
        "ump" => $datos['Botellas'],
        "ucb" => $datos['CajasCompr'],
        "ucp" => $datos['BotellasCompr'],
        "cantempaq" => $datos['CantEmpaq'],
        "iva" => $datos['iva'],
        "precio"  => rdecimal($datos["Precio"], 2),
        "preciod" => rdecimal($datos["Precio"]/$tasa, 2),
        "subtotal"  => rdecimal($datos["Precio"] * $cant, 2),
        "subtotald" => rdecimal(($datos["Precio"]/$tasa) * $cant, 2),
        "total"   => rdecimal($total * $cant, 2),
        "totald"  => rdecimal(($total/$tasa) * $cant, 2)
    );

    echo json_encode($data);
    break;

    case "existencias":
    $arr_idx = $_POST['idx'];
    $arr_prod = $_POST['prod'];
    $depo = $_POST["depo"];
    $sucursal = $_SESSION["codsucu"];

    $data = array();
    foreach ($arr_prod as $i => $codprod) {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT p.CodProd, p.Descrip, CONVERT(INT, e.Existen) Bul, CONVERT(INT, e.ExUnidad) Paq 
                FROM SAPROD p INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
                INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
                WHERE d.Clase='$sucursal' AND p.CodProd = '$codprod'")
        );
        if (count($datos)>0) {
            $data[] = array(
                'idx' => $arr_idx[$i],
                'codprod' => $codprod,
                'umb' => $datos[0]['Bul'],
                'ump' => $datos[0]['Paq'],
            );
        }
    } 
    echo json_encode($data);
    break;

    case 'presupuestar':
    $tipofac_facturar = "F";
    $procesar = false;
    $mensaje_err = '';
    $items_text = '';
    $ant  = $_POST['ant'];
    $codclie  = $_POST['clie'];
    $codvend  = $_POST['vend'];
    $codubic  = $_POST['depo'];
    $tipo_precio  = $_POST['tipo_precio'];
    $tasa  = $_POST['tasa'];
    
    $arr_prod = $_POST['prod'];
    $arr_cant = $_POST['cant'];
    $arr_unid = $_POST['unid'];
    $arr_iva16    = $_POST['iva16'];
    $arr_tipopvp  = $_POST['tipopvp'];
    
    $total_ope_bs   = $_POST['total_ope_bs'];
    $porcentaje_primer_des = $_POST['primer_des'];
    $ttl_neto_bs    = $_POST['ttl_neto_bs'];
    $ttl_imp_16_bs  = $_POST['ttl_imp_16_bs'];
    $ttl_gral_bs    = $_POST['ttl_gral_bs'];
    $tipo_ope = $_POST['tipo_ope'];
    
    $coment1 = $_POST['coment1'];
    $coment2 = $_POST['coment2'];
    $coment3 = $_POST['coment3'];
    $coment4 = $_POST['coment4'];
    $coment5 = $_POST['coment5'];

    $fechae = date('Y-m-d');
    
    $monto_anticipo = 0;
    $monto_cambio = 0;
    $monto_pagoa = 0;
    $monto_acum = 0;

        # Evalua que exista en sesion el Usuario
    if ($_SESSION['login']) 
    {
            # Evalua que exista en sesion la Sucursal
        if ($_SESSION['codsucu']) 
        {
                # Evalua que exista en sesion la Estacion
            if ($_SESSION['codesta']) 
            {
                $flag_exis = true;
                $flag_no_prod = false;
                    //validacion de existencia valida de productos a facturar
                foreach ($arr_prod as $i => $codprod) {
                    if ($codprod!='') {
                        $query = mssql_query("SELECT CodProd, Descrip FROM SAPROD WHERE CodProd = '$codprod'");
                        if (mssql_num_rows($query) == 0) {
                            $flag_exis = false;
                            $flag_no_prod = true;
                            break;
                        }
                    }
                    
                    
                }

                if ($flag_exis) {
                    $user = $_SESSION['login'];
                    $codsucu = $_SESSION['codsucu'];
                    $codesta = $_SESSION['codesta'];

                        //obtenemos el nuevo correlativo para la Factura
                    $querylengh = mssql_query("SELECT ValueInt FROM SACORRELSIS WHERE FieldName='LenCorrel' AND CodSucu='$codsucu'");
                    $lengh = (mssql_num_rows($querylengh)>0) ? mssql_result($querylengh, 0,"ValueInt") : 8;
                    $query = mssql_query("SELECT FieldName, Prefijo, ValueInt FROM SACORRELSIS WHERE FieldName='PrxProf' AND CodSucu='$codsucu'");
                    $correl_nuevo = mssql_result($query, 0, "Prefijo").str_pad(mssql_result($query, 0,"ValueInt"), $lengh, 0, STR_PAD_LEFT);

                    if ($correl_nuevo != "") {
                            //actualiza el correlativo +1
                        $query = mssql_query("UPDATE SACORRELSIS SET ValueInt=ValueInt+1 WHERE FieldName='PrxProf' AND CodSucu='$codsucu'");
                        
                        $acum = 1;
                        $flag_items = true;
                        foreach ($arr_prod as $i => $codprod) {
                            if (!empty($codprod)) {
                                $nroLinea = $acum;
                                $codItem = $codprod;
                                $cantidad = $arr_cant[$i];
                                $esunid = $arr_unid[$i];
                                $tipopvp = $arr_tipopvp[$i];
                                $items_text.="NROLINEA: $nroLinea, CODITEM: $codItem, CANTIDAD: $cantidad, ESUNID: $esunid, PRECIO: $tipopvp <br/>";

                                    //se procede a guardar los documentos en la nueva factura.
                                $proc_fact = mssql_query("EXEC [App_Ped_Presu_Items] @NumeroD ='$correl_nuevo', @TipoFac ='$tipofac_facturar', @CodSucu ='$codsucu', @NroOrg ='', @TipoOrg ='', @Deposito = '$codubic', @Vendedor='$codvend', @NroLinea ='$nroLinea', @CodItem ='$codItem', @Cantidad ='$cantidad', @EsUnida ='$esunid', @TipoPvp = '$tipopvp', @Factor ='$tasa'")
                                or ($mensaje_err.=('MSSQL Error: ' . mssql_get_last_message()."<br/>"));
                                if (!$proc_fact) {
                                    $flag_items = false;
                                }
                                $acum+=1;
                            }
                        }

                            # cabecera factura
                        if ($flag_items) {
                                //se procede a guardar la cabecera de la nueva factura.
                            $procesar = mssql_query("EXEC [App_Ped_Presu_Header] @NumeroD ='$correl_nuevo', @TipoFac ='$tipofac_facturar', @CodClie ='$codclie', @CodSucu ='$codsucu', @CodEsta ='$codesta', @CodUsua ='$user', @TipoOrg ='', @NroOrg ='', @Deposito = '$codubic', @Vendedor='$codvend', @Factor ='$tasa', @Anticipo ='$monto_anticipo', @TipoOpe ='$tipo_ope', @DescuentoUno = '$porcentaje_primer_des', @Notas1 = '$coment1', @Notas2 = '$coment2', @Notas3 = '$coment3', @Notas4 = '$coment4', @Notas5 = '$coment5' ")
                            or ($mensaje_err.=('MSSQL Error: ' . mssql_get_last_message()."<br/>"));
                        }
                    }

                        //mensaje
                    if($procesar){
                        $query = mssql_query("SELECT NroUnico FROM SAFACT WHERE CodSucu='$codsucu' AND NumeroD='$correl_nuevo' AND TipoFac='$tipofac_facturar'");

                        $output = array(
                            "id"   => mssql_result($query, 0, "NroUnico"),
                            "title" => 'Completado',
                            "mensaje" => "Se Presupuestó exitosamente!",
                            "icono"   => "success"
                        );
                    } else {
                        $output = array(
                            "id"   => "",
                            "title" => 'Atención!',
                            "mensaje" => "Ocurrió un error al Presupuestar! ".$mensaje_err,
                            "icono"   => "error"
                        );
                    }

                        // en caso de fallo de cualquiera de las bandera, envia un correo y almacena en la bd
                    if (!$procesar || !$flag_items) {
                        require 'PHPMailer/class.phpmailer.php';
                        $mail = new PHPMailer(true);
                            // Server settings
                        $mail->isSMTP();
                        $mail->SMTPDebug = 0;
                        $mail->Debugoutput = 'html';
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure= 'ssl';
                        $mail->Port = 465;
                        $mail->Username = 'no.responder.eltriunfo@gmail.com';
                        $mail->Password = 'oweagrbckonxufal';
                            // Sender &amp; Recipient
                        $mail->From = 'no.responder.eltriunfo@gmail.com';
                        $mail->FromName = "Sistema de Logistica y Despacho El Triunfo C.A";
                        $mail->isHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->Encoding = 'base64';
                        $mail->Timeout=15;
                        $mail->AddAddress("soporte.rsistems@gmail.com");
                            //$mail->AddCC("");
                            //$mail->AddBCC("");
                        $mail->AddBCC("llopez@rsistems.tech");
                        $mail->Subject = "ERROR APP EN PRESUPUESTO";
                        $body="<strong>MENSAJE $mensaje_err</strong>
                        <p>
                        <strong>DATOS:</strong>  <br>
                        NUMEROD: $correl_nuevo <br>
                        TIPOFAC: $tipofac_facturar <br>
                        CODCLIE: $codclie <br>
                        CODSUCU: $codsucu <br>
                        CODESTA: $codesta <br>
                        CODUSUA: $user <br>
                        TipoOrg: $tipofac_c <br>
                        NroOrg: $numerod_c <br>
                        CODDEPO: $codubic <br>
                        CODVEND: $codvend <br>
                        FACTOR: $tasa <br>
                        FECHAE: $fechaemi <br>
                        DIASVENC: $diasven <br>
                        PORC.DESC: $porcentaje_primer_des <br>
                        NOTAS1: $coment1 <br>
                        NOTAS2: $coment2 <br>
                        NOTAS3: $coment3 <br>
                        NOTAS4: $coment4 <br>
                        NOTAS5: $coment5 <br>
                        NOTAS9: $comentario_delvol
                        </p>
                        <p>
                        <strong>ITEMS:</strong>  <br>
                        $items_text
                        <p>";
                        $mail->Body=$body;
                        $mail->Send();

                            //inserta la incidencia
                        $items_text1 = str_replace('<br/>',', ', $items_text);
                        mssql_query("INSERT INTO [dbo].[IncidenciasModVentas]
                            ([NumeroD]
                                ,[TipoFac]
                                ,[CodClie]
                                ,[CodSucu]
                                ,[CodEsta]
                                ,[CodUsua]
                                ,[TipoOrg]
                                ,[NroOrg]
                                ,[CodUbic]
                                ,[CodVend]
                                ,[Factor]
                                ,[FechaE]
                                ,[DiasVenc]
                                ,[PorcDesc]
                                ,[Notas1]
                                ,[Notas2]
                                ,[Notas3]
                                ,[Notas4]
                                ,[Notas5]
                                ,[Notas8]
                                ,[Notas9]
                                ,[Items])
                            VALUES
                                        ('$correl_nuevo' --<NumeroD, varchar(20),>
                                        ,'$tipofac_facturar' --<TipoFac, varchar(1),>
                                        ,'$codclie' --<CodClie, varchar(15),>
                                        ,'$codsucu' --<CodSucu, varchar(5),>
                                        ,'$codesta' --<CodEsta, varchar(10),>
                                        ,'$user' --<CodUsua, varchar(10),>
                                        ,'$tipofac_c' --<TipoOrg, varchar(1),>
                                        ,'$numerod_c' --<NroOrg, varchar(20),>
                                        ,'$codubic' --<CodUbic, varchar(10),>
                                        ,'$codvend' --<CodVend, varchar(10),>
                                        ,'$tasa' --<Factor, decimal(28,4),>
                                        ,GETDATE() --<FechaE, datetime,>
                                        ,'$diasven' --<DiasVenc, int,>
                                        ,'$porcentaje_primer_des' --<PorcDesc, decimal(28,2),>
                                        ,'$coment1' --<Notas1, varchar(60),>
                                        ,'$coment2' --<Notas2, varchar(60),>
                                        ,'$coment3' --<Notas3, varchar(60),>
                                        ,'$coment4' --<Notas4, varchar(60),>
                                        ,'$coment5' --<Notas5, varchar(60),>
                                        ,'APP' --<Notas8, varchar(60),>
                                        ,'$comentario_delvol' --<Notas9, varchar(60),>
                                        ,'$items_text1') --<Items, varchar(max),> ");
                    }
                } else {
                    $output = array(
                        "title" => 'Atención!',
                        "mensaje" => "Existe un Código de Producto Inválido!",
                        "icono"   => "error"
                    );
                }
            } 
                # Envia mensaje error si no detecta la estacion en Sesion
            else {
                $output = array(
                    "title" => 'ERROR',
                    "mensaje" => "No Se detecta la Estación ! <br><br> vuelva a iniciar sesión",
                    "icono"   => "error"
                );
            }
        } 
            # Envia mensaje error si no detecta la sucursal en Sesion
        else {
            $output = array(
                "title" => 'ERROR',
                "mensaje" => "No Se detecta la Sucursal ! <br><br> vuelva a iniciar sesión",
                "icono"   => "error"
            );
        }
    } 
        # Envia mensaje error si no detecta el usuario en Sesion
    else {
        $output = array(
            "title" => 'ERROR',
            "mensaje" => "No se detecta el Usuario ! <br><br> vuelva a iniciar sesión",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;
    
}