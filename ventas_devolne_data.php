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
    
    $querylengh = mssql_query("SELECT ValueInt FROM SACORRELSIS WHERE FieldName='LenCorrel' AND CodSucu='$codsucu'");
    $lengh = (mssql_num_rows($querylengh)>0) ? mssql_result($querylengh, 0,"ValueInt") : 8;
    $sacorrel =  mssql_query("SELECT Prefijo, ValueInt FROM SACORRELSIS WHERE FieldName='PrxDevNEV' AND CodSucu='$codsucu'");
    $saconf =  mssql_query("SELECT FactorM FROM SACONF WHERE CodSucu='$codsucu'");

    $data = array (
        "correl" => mssql_result($sacorrel, 0, "Prefijo").str_pad(mssql_result($sacorrel, 0, "ValueInt"), $lengh, 0, STR_PAD_LEFT),
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
                FROM SAPROD p INNER JOIN SAEXIS e ON e.CodProd=p.CodProd AND (e.Existen>0 OR e.ExUnidad>0)
                INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
                WHERE d.Clase='$sucursal' AND (p.CodProd LIKE '%$search%' OR p.Descrip LIKE '%$search%')")
        );
    } else {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT p.CodProd, p.Descrip, CONVERT(INT, e.Existen) Bul, CONVERT(INT, e.ExUnidad) Paq 
                FROM SAPROD p INNER JOIN SAEXIS e ON e.CodProd=p.CodProd AND (e.Existen>0 OR e.ExUnidad>0)
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
            "sEcho" => 1, //Informaci??n para el datatables
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
    $convenio = $_POST["convenio"];
    $sucursal = $_SESSION["codsucu"];

    if ($cant=="") {
        $cant = 1;
    }
    if ($convenio != "0") {
        switch ($convenio) {
            case 1: $precio=4; break;
            case 2: $precio=5; break;
            case 3: $precio=6; break;
            case 4: $precio=7; break;
        }
    }

    $datos =  Mssql::fetch_assoc(
        mssql_query("SELECT p.CodProd, p.Descrip, CONVERT(INT, p.CantEmpaq) CantEmpaq, p.Unidad, p.UndEmpaq, 
            p.Precio$precio/(CASE WHEN  '$unid' = '1' THEN p.CantEmpaq ELSE 1 END) AS Precio, 
            ISNULL((SELECT CASE WHEN EsPorct=1 THEN (Monto/100) * p.Precio$precio ELSE Monto END FROM SATAXPRD tax WHERE tax.CodProd=p.CodProd AND CodTaxs='IVA'), 0)/(CASE WHEN '$unid' = '1' THEN p.CantEmpaq ELSE 1 END) AS iva
            FROM SAPROD p 
            INNER JOIN SAPROD_99 p9 ON p9.CodProd=p.CodProd
            INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
            INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$depo'
            WHERE d.Clase='$sucursal' AND (p.CodProd = '$codprod' OR p.Descrip LIKE '%$codprod%')")
    );
    $datos = $datos[0];

    $total = ($datos['Precio'] + $datos['iva']);

    $data = array (
        "tipopvp" => $precio,
        "codprod" => $datos['CodProd'],
        "descrip" => utf8_encode($datos["Descrip"]),
        "und" => substr($datos['UndEmpaq'], 0, 3),
        "cantempaq" => $datos['CantEmpaq'],
        "iva" => $datos['iva'],
        "precio"  => $datos["Precio"],
        "preciod" => $datos["Precio"]/$tasa,
        "subtotal"  => $datos["Precio"] * $cant,
        "subtotald" => ($datos["Precio"]/$tasa) * $cant,
        "total"   => $total * $cant,
        "totald"  => ($total/$tasa) * $cant
    );

    echo json_encode($data);
    break;

    case "listar_docs":
    $search = $_POST["search"];
    $codclie = $_POST["codclie"];
    $sucursal = $_SESSION["codsucu"];
    
    switch (true) {
        case ($search!=='' && $codclie==''):
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac IN ('C') AND NumeroR IS NULL AND CodSucu='$sucursal' AND (NumeroD LIKE '%$search%' OR Descrip LIKE '%$search%')")
        );
        break;
        case ($search=='' && $codclie==''):
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac IN ('C') AND NumeroR IS NULL AND CodSucu='$sucursal'")
        );
        break;
        case ($search!=='' && $codclie!==''):
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac IN ('C') AND NumeroR IS NULL AND CodSucu='$sucursal' 
                AND (NumeroD LIKE '%$search%' OR Descrip LIKE '%$search%') AND CodClie='$codclie'")
        );
        break;
        case ($search=='' && $codclie!==''):
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac IN ('C') AND NumeroR IS NULL AND CodSucu='$sucursal' AND CodClie='$codclie'")
        );
        break;
    }
    

        //declaramos el array
    $data = array();
    foreach ($datos as $key => $row) {
        $sub_array = array();

        $sub_array[] = $row["NumeroD"];
        $sub_array[] = utf8_encode($row["Descrip"]);
        $sub_array[] = date('d/m/Y', strtotime($row["FechaE"]));
        switch ($row["TipoFac"]) {
            case "A": $sub_array[] = '<small class="badge badge-secondary">Factura</small>'; break;
            case "B": $sub_array[] = '<small class="badge badge-secondary">Devoluci??n Factura</small>'; break;
            case "C": $sub_array[] = '<small class="badge badge-secondary">Nota de Entrega</small>'; break;
            case "D": $sub_array[] = '<small class="badge badge-secondary">Devoluci??n N/E</small>'; break;
            case "E": $sub_array[] = '<small class="badge badge-secondary">Pedido</small>'; break;
            case "F": $sub_array[] = '<small class="badge badge-secondary">Presupuesto</small>'; break;
            case "G": $sub_array[] = '<small class="badge badge-secondary">Fact. en Espera</small>'; break;
        }
            //$sub_array[] = Functions::rdecimal($row["MtoTotal"], 2);
            //$sub_array[] = Functions::rdecimal($row["Mtotald"], 2);
        $sub_array[] = number_format($row["MtoTotal"], 2, ',', '.');
        $sub_array[] = number_format($row["Mtotald"], 2, ',', '.');
        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="seleccionarDoc(\'' . $row['NumeroD'] . '\',\'' . $row['TipoFac'] . '\');"  
        id="' . $row['NumeroD'] . '" 
        class="btn btn-outline-saint btn-xs">
        Seleccionar
        </button>
        </div>';

        $data[] = $sub_array;
    }

    $results = array(
            "sEcho" => 1, //Informaci??n para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );

    echo json_encode($results);
    break;

    case 'datos_doc':
    $iva_ttl = 0;
    $numerod = $_POST['numerod'];
    $tipofac = $_POST["tipofac"];
    $sucursal = $_SESSION["codsucu"];

    $arr = array();
    $safact =  Mssql::fetch_assoc(
        mssql_query("SELECT NumeroD, f.Descrip, TipoFac, f.CodClie, v.CodVend, v.Descrip NomperVend, c.TipoPVP, 
            Monto SubTotal, MtoTax, MtoTotal, FactorP, ISNULL(MtoTotal/FactorP, 0) Mtotald,
            convert(decimal(18,2), COALESCE(((Descto1*100)/NULLIF(Monto,0)), 0)) PorcDesc, Descto1
            FROM SAFACT f INNER JOIN SAVEND v ON v.CodVend=f.CodVend INNER JOIN SACLIE c ON c.CodClie=f.CodClie
            WHERE CodSucu='$sucursal' AND NumeroD='$numerod' AND TipoFac='$tipofac'")
    );
    if (count($safact)>0) {

        $precio = ($safact[0]['TipoPVP']==0) ? 1 : $safact[0]['TipoPVP'];

        $arr = array(
            "codclie" => $safact[0]['CodClie'],
            "codvend" => $safact[0]['CodVend'],
            "subtotal" => $safact[0]['SubTotal'],
            "impuesto" => $safact[0]['MtoTax'],
            "total" => $safact[0]['MtoTotal'],
            "totald" => $safact[0]['Mtotald'],
            "tasa" => $safact[0]['FactorP'],
            "precio" => $precio,
            "descuento" => $safact[0]['PorcDesc'],
            "mdescuento" => $safact[0]['Descto1'],
        );
    }

    $arr1 = array();
    
    $saitemfac =  Mssql::fetch_assoc(
        mssql_query("SELECT CodItem, Descrip1, item.CodUbic, Cantidad, item.EsUnid, p.UndEmpaq, CONVERT(INT, p.CantEmpaq) CantEmpaq, Precio, Precio/item.FACTORP AS Preciod, item.FactorP, TipoPVP, Descto, Descto/item.FactorP AS Desctod,
            ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IVA' GROUP BY CodTaxs, CodItem, Monto) / item.Cantidad, 0) AS iva,
            (ISNULL((SELECT Monto FROM SATAXITF WHERE NumeroD=item.NumeroD AND TipoFac=item.TipoFac AND CodItem=item.CodItem AND NroLinea=item.NroLinea AND CodSucu=item.CodSucu AND CodTaxs='IVA' GROUP BY CodTaxs, CodItem, Monto) / item.Cantidad, 0)/item.Factorp) AS iva_d
            FROM SAITEMFAC item INNER JOIN SAFACT f ON f.NumeroD=item.NumeroD AND item.TipoFac='$tipofac' INNER JOIN SAPROD p ON p.CodProd=item.CodItem INNER JOIN SAEXIS e ON e.CodProd=p.CodProd AND e.CodUbic=item.CodUbic
            WHERE item.CodSucu='$sucursal' AND item.NumeroD='$numerod'")
    );
    if (count($saitemfac)>0) {
        foreach ($saitemfac as $item) {

            $total = ($item['Precio'] - $item['Descto']) + $item['iva'];
            $totald = ($item['Preciod'] - $item['Desctod']) + $item['iva_d'];

            $arr1[] = array(
                "iva" => $item['iva'],
                "codprod" => $item['CodItem'],
                "descrip" => utf8_encode($item['Descrip1']),
                "tipopvp" => $item['TipoPVP'],
                "und" => substr($item['UndEmpaq'], 0, 3),
                "cantidad" => Functions::rdecimal($item['Cantidad'], 0),
                "unidad" => $item['EsUnid'],
                "cantempaq" => $item['CantEmpaq'],
                "precio" => $item['Precio'],
                "preciod" => $item['Preciod'],
                "subtotal" => $item['Precio'] * $item['Cantidad'],
                "subtotald" => $item['Preciod'] * $item['Cantidad'],
                "total" => $total * $item['Cantidad'],
                "totald" => $totald * $item['Cantidad'],
            );
            $iva_ttl += ($item['iva'] * Functions::rdecimal($item['Cantidad'], 0));
        }
        $arr['codubic'] =  $saitemfac[0]['CodUbic'];
    }
    $arr['iva'] = $iva_ttl;

    $data = array (
        "head" => $arr,
        "body" => $arr1,
    );

    echo json_encode($data);
    break;

    case 'devolne':
    $tipofac_facturar = "D";
    $procesar = false;
    $mensaje_err = '';
    $items_text = '';
    $ant  = $_POST['ant'];
    $codclie  = $_POST['clie'];
    $codvend  = $_POST['vend'];
    $codubic  = $_POST['depo'];
    $tipo_precio  = $_POST['tipo_precio'];
    $tasa  = $_POST['tasa'];
    $numerod_c  = $_POST['numerod_c'];
    $tipofac_c  = $_POST['tipofac_c'];
    
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
    $fechaemi = $_POST['fechaemi'];
    $diasven = $_POST['diasven'];
    $fechaven = $_POST['fechaven'];
    $anticipo = $_POST['anticipo'];
    $comentario_delvol = $_POST['comentario_delvol'];
    
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
                $text_error = '';
                    //validacion de existencia valida de productos a facturar
                foreach ($arr_prod as $i => $codprod) {
                    if ($codprod!='') {
                        $query = mssql_query("SELECT p.CodProd, p.Descrip, p.CantEmpaq,  p.UndEmpaq FROM SAPROD p
                            INNER JOIN SAEXIS e ON e.CodProd=p.CodProd INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic AND e.CodUbic='$codubic'
                            WHERE d.Clase='$codsucu' AND p.CodProd = '$codprod'");
                        if (mssql_num_rows($query) == 0) {
                                // no encontro el producto solicitado
                            $flag_exis = false;
                            $text_error = "Existe un C??digo de Producto Inv??lido!";
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
                    $query = mssql_query("SELECT FieldName, Prefijo, ValueInt FROM SACORRELSIS WHERE FieldName='PrxDevNEV' AND CodSucu='$codsucu'");
                    $correl_nuevo = mssql_result($query, 0, "Prefijo").str_pad(mssql_result($query, 0,"ValueInt"), $lengh, 0, STR_PAD_LEFT);

                    if ($correl_nuevo != "") {
                            //actualiza el correlativo +1
                        $query = mssql_query("UPDATE SACORRELSIS SET ValueInt=ValueInt+1 WHERE FieldName='PrxDevNEV' AND CodSucu='$codsucu'");
                        
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
                                $proc_fact = mssql_query("EXEC [App_Fac_Ne_Items] @NumeroD ='$correl_nuevo', @TipoFac ='$tipofac_facturar', @CodSucu ='$codsucu', @NroOrg ='$numerod_c', @TipoOrg ='$tipofac_c', @Deposito = '$codubic', @Vendedor='$codvend', @NroLinea ='$nroLinea', @CodItem ='$codItem', @Cantidad ='$cantidad', @EsUnida ='$esunid', @TipoPvp = '$tipopvp', @Factor ='$tasa'")
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
                            $procesar = mssql_query("EXEC [App_Fac_Ne_Header] @NumeroD ='$correl_nuevo', @TipoFac ='$tipofac_facturar', @CodClie ='$codclie', @CodSucu ='$codsucu', @CodEsta ='$codesta', @CodUsua ='$user', @TipoOrg ='$tipofac_c', @NroOrg ='$numerod_c', @Deposito = '$codubic', @Vendedor='$codvend', @Factor ='$tasa', @Anticipo ='$monto_anticipo', @TipoOpe ='$tipo_ope', @FechaEmi ='$fechaemi', @DiasVen ='$diasven', @FechaVen ='$fechaven', @DescuentoUno = '$porcentaje_primer_des', @crearAnticipo='0', @MontoAnticipo='0', @Notas1 = '$coment1', @Notas2 = '$coment2', @Notas3 = '$coment3', @Notas4 = '$coment4', @Notas5 = '$coment5', @MotivoDevol='$comentario_delvol' ")
                            or ($mensaje_err.=('MSSQL Error: ' . mssql_get_last_message()."<br/>"));
                        }
                    }

                        //mensaje
                    if($procesar){
                        $query = mssql_query("SELECT NroUnico FROM SAFACT WHERE CodSucu='$codsucu' AND NumeroD='$correl_nuevo' AND TipoFac='$tipofac_facturar'");

                        $output = array(
                            "id"   => mssql_result($query, 0, "NroUnico"),
                            "title" => 'Completado',
                            "mensaje" => "Devoluci??n N.E. generada exitosamente!",
                            "icono"   => "success",
                        );
                    } else {
                        $output = array(
                            "id"   => "",
                            "title" => 'Atenci??n!',
                            "mensaje" => "Ocurri?? un error al Facturar! ".$mensaje_err,
                            "icono"   => "error",
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
                        $mail->Subject = "ERROR APP EN DEV. N/E";
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
                        "title" => 'Atenci??n!',
                        "mensaje" => $text_error,
                        "icono"   => "error"
                    );
                }
            } 
                # Envia mensaje error si no detecta la estacion en Sesion
            else {
                $output = array(
                    "title" => 'ERROR',
                    "mensaje" => "No Se detecta la Estaci??n ! <br><br> vuelva a iniciar sesi??n",
                    "icono"   => "error"
                );
            }
        } 
            # Envia mensaje error si no detecta la sucursal en Sesion
        else {
            $output = array(
                "title" => 'ERROR',
                "mensaje" => "No Se detecta la Sucursal ! <br><br> vuelva a iniciar sesi??n",
                "icono"   => "error"
            );
        }
    } 
        # Envia mensaje error si no detecta el usuario en Sesion
    else {
        $output = array(
            "title" => 'ERROR',
            "mensaje" => "No se detecta el Usuario ! <br><br> vuelva a iniciar sesi??n",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;
    
}