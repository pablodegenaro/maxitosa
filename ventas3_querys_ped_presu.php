<?php
function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

$queryTRAN = "
DECLARE
@STATUS INT,
@MENSAJE nvarchar(2048) = ''

BEGIN TRY  
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE
BEGIN TRANSACTION VENTA

DECLARE
@DescuentoUno decimal(24,4),
@Periodo varchar(6),
@Variable varchar(max),
@Iva16 decimal(24,4),
@IvaPer decimal(24,4),
@Ial decimal(24,4),
@TGravable_Iva16 decimal(24,4),
@TGravable_IvaPer decimal(24,4),
@TGravable_Ial decimal(24,4),
@Mtotax_IvaPer decimal(24,4),
@Mtotax_Ial decimal(24,4),
@SaldoAct decimal(28,4),
@MtoVentas decimal(28,4),
@FechaA datetime,
@NROUNICOCXC  INT,
@MontoTotal decimal(28,4),
@CantRegistNroOrg INT,
@NUMERRORS    INT;
SET @NUMERRORS=0;
set @FechaA = getdate()"."\n\n";

try {
    //obtenemos el nuevo correlativo para la Factura
    $querylengh = mssql_query("SELECT ValueInt FROM SACORRELSIS WHERE FieldName='LenCorrel' AND CodSucu='$codsucu'");
    $lengh = (mssql_num_rows($querylengh)>0) ? mssql_result($querylengh, 0,"ValueInt") : 8;
    $query = mssql_query("SELECT FieldName, Prefijo, ValueInt FROM SACORRELSIS WHERE FieldName='$FIELD_CORREL' AND CodSucu='$codsucu'");
    $correl_nuevo = mssql_result($query, 0, "Prefijo").str_pad(mssql_result($query, 0,"ValueInt"), $lengh, 0, STR_PAD_LEFT);

    if ($correl_nuevo != "") {
        //actualiza el correlativo +1
        $queryTRAN .= ("UPDATE SACORRELSIS SET ValueInt=ValueInt+1 WHERE FieldName='$FIELD_CORREL' AND CodSucu='$codsucu'") ."\n";
        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";

        # OBTENCION DEL PERIODO
        $queryTRAN .= ("SELECT @Periodo=MesCurso FROM SACONF WHERE CodSucu='$codsucu' ") ."\n";

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

                
                # OBTENCION DE VALORES DE IMPUESTOS Y PRECIO DE PRODUCTO
                $queryValues = mssql_query("SELECT 
                    ISNULL(e.Existen, 0) ExistenPrd,
                    ISNULL(e.ExUnidad, 0) ExUnidadPrd, 
                    p.CantEmpaq, 
                    p.CostPro CostProPrd,
                    (CASE WHEN '$esunid' = 0 THEN /*valor por caja*/ (CASE WHEN $tipopvp=1 THEN Precio1 WHEN $tipopvp=2 THEN Precio2 WHEN $tipopvp=3 THEN Precio3 WHEN $tipopvp=4 THEN Precio4 WHEN $tipopvp=5 THEN Precio5 WHEN $tipopvp=6 THEN Precio6 WHEN $tipopvp=7 THEN Precio7  WHEN $tipopvp=8 THEN Precio8 ELSE 0 END)  ELSE /*Valor por unidad*/ (CASE WHEN $tipopvp=1 THEN PrecioU WHEN $tipopvp=2 THEN PrecioU2 WHEN $tipopvp=3 THEN PrecioU3 WHEN $tipopvp=4 THEN PrecioU4 WHEN $tipopvp=5 THEN PrecioU5 WHEN $tipopvp=6 THEN PrecioU6 WHEN $tipopvp=7 THEN PrecioU7 WHEN $tipopvp=8 THEN PrecioU8 ELSE 0 END) END) PrecioValor,
                    ISNULL((SELECT Monto FROM SATAXPRD WHERE CodProd='$codItem' AND CodTaxs='IAL'), 0)/(CASE WHEN $esunid = 1 THEN p.CantEmpaq ELSE 1 END) Ial,
                    ISNULL((SELECT Monto FROM SATAXPRD WHERE CodProd='$codItem' AND CodTaxs='PVP'), 0)/(CASE WHEN $esunid = 1 THEN p.CantEmpaq ELSE 1 END) IvaPer,
                    ISNULL((SELECT (Monto/100) * (CASE WHEN $esunid = 0 THEN /*valor por caja*/ (CASE WHEN $tipopvp=1 THEN Precio1 WHEN $tipopvp=2 THEN Precio2 WHEN $tipopvp=3 THEN Precio3 WHEN $tipopvp=4 THEN Precio4 WHEN $tipopvp=5 THEN Precio5 WHEN $tipopvp=6 THEN Precio6 WHEN $tipopvp=7 THEN Precio7  WHEN $tipopvp=8 THEN Precio8 ELSE 0 END)  ELSE /*Valor por unidad*/ (CASE WHEN $tipopvp=1 THEN PrecioU WHEN $tipopvp=2 THEN PrecioU2 WHEN $tipopvp=3 THEN PrecioU3 WHEN $tipopvp=4 THEN PrecioU4 WHEN $tipopvp=5 THEN PrecioU5 WHEN $tipopvp=6 THEN PrecioU6 WHEN $tipopvp=7 THEN PrecioU7 WHEN $tipopvp=8 THEN PrecioU8 ELSE 0 END) END) FROM SATAXPRD tax WHERE tax.CodProd=p.CodProd AND CodTaxs='IVA'), 0) Iva16
                    FROM SAPROD p 
                    INNER JOIN SAPROD_99 p9 ON p9.CodProd=p.CodProd
                    INNER JOIN SAEXIS e ON e.CodProd=p.CodProd
                    INNER JOIN SADEPO d ON d.CodUbic=e.CodUbic
                    WHERE d.Clase='$codsucu' AND p.CodProd='$codItem' AND e.CodUbic='$codubic'");
                if (mssql_num_rows($queryValues) > 0) {
                    $ExistenPrd  = mssql_result($queryValues, 0, "ExistenPrd");
                    $ExUnidadPrd = mssql_result($queryValues, 0, "ExUnidadPrd");
                    $CantEmpaq   = mssql_result($queryValues, 0, "CantEmpaq");
                    $CostProPrd  = mssql_result($queryValues, 0, "CostProPrd");
                    $PrecioValor = mssql_result($queryValues, 0, "PrecioValor");
                    $Ial    = mssql_result($queryValues, 0, "Ial");
                    $IvaPer = mssql_result($queryValues, 0, "IvaPer");
                    $Iva16  = mssql_result($queryValues, 0, "Iva16");

                    if ($Iva16 > 0) {
                        # IMPUESTOS ITEMS
                        $queryTRAN .= ("INSERT INTO SATAXITF
                            (CodItem, NroLinea, CodTaxs,  MtoTax, Monto, NumeroD, TipoFac, CodSucu, TGravable, NroLineaC)
                            VALUES
                            ('$codItem', '$nroLinea', 'IVA', 16, $Iva16*$cantidad, '$correl_nuevo', '$tipofac_facturar', '$codsucu', $PrecioValor*$cantidad, 0)") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
                    }
                    if ($IvaPer > 0) {
                        # IMPUESTOS ITEMS
                        $queryTRAN .= ("INSERT INTO SATAXITF
                            (CodItem, NroLinea, CodTaxs,  MtoTax, Monto, NumeroD, TipoFac, CodSucu, TGravable, NroLineaC)
                            SELECT
                            '$codItem', '$nroLinea', 'PVP',  (CASE WHEN $esunid=1 THEN Monto/$CantEmpaq ELSE Monto END), $IvaPer*$cantidad, '$correl_nuevo', '$tipofac_facturar', '$codsucu', $PrecioValor*$cantidad, 0
                            FROM SATAXPRD WHERE CodProd = '$codItem' and CodTaxs = 'PVP'") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
                    }
                    if ($Ial > 0) {
                        # IMPUESTOS ITEMS
                        $queryTRAN .= ("INSERT INTO SATAXITF
                            (CodItem, NroLinea, CodTaxs,  MtoTax, Monto, NumeroD, TipoFac, CodSucu, TGravable, NroLineaC)
                            SELECT
                            '$codItem', '$nroLinea', 'IAL', (CASE WHEN $esunid=1 THEN Monto/$CantEmpaq ELSE Monto END), $Ial*$cantidad, '$correl_nuevo', '$tipofac_facturar', '$codsucu', $PrecioValor*$cantidad, 0
                            FROM SATAXPRD WHERE CodProd = '$codItem' and CodTaxs = 'IAL'") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
                    }

                    # ITEMS FACTURA
                    $queryTRAN .= ("INSERT INTO SAITEMFAC ([CodSucu],[TipoFac],[NumeroD],[NroLinea],[Signo],[FechaE],[CodItem],[CodUbic],
                        [OTipo],[ONumero],[Descrip1],[Descrip2],[Cantidad],[CantMayor],[Costo],[TotalItem],
                        [Precio],[TipoPVP],[PriceO],[MtoTax],[MtoTaxO],[CodVend],[NroUnicoL],[ExistAntU],
                        [ExistAnt],[Factor],[FechaL],[FechaV],[Refere],[EsUnid],[EsExento],[FactorP])
                    SELECT '$codsucu', '$tipofac_facturar', '$correl_nuevo', '$nroLinea', 1, '$fechaemi', '$codItem', '$codubic',
                    (CASE WHEN '$tipofac_c'!='' THEN '$tipofac_c' ELSE NULL END), (CASE WHEN '$numerod_c'!='' THEN '$numerod_c' ELSE NULL END), Descrip, Descrip2, '$cantidad', 1, (CASE WHEN $esunid=1 THEN CostAct/CantEmpaq ELSE CostAct END), $cantidad*$PrecioValor,
                    $PrecioValor,$tipopvp,$PrecioValor, (isnull($Ial,0)+isnull($Iva16,0)+isnull($IvaPer,0))*$cantidad, isnull($Ial,0)+isnull($Iva16,0)+isnull($IvaPer,0), '$codvend',0,ISNULL($ExUnidadPrd,0),
                    ISNULL($ExistenPrd, 0),1,'$fechaemi', '$fechaemi', Refere, $esunid, EsExento, $tasa
                    FROM SAPROD WHERE Codprod = '$codItem'") ."\n";
                    $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
                    
                    # ELIMINAR ITEM DE DOCUMENTO SI ESTA CARGADO
                    if ( in_array($tipofac_facturar, array('E')) && ($tipofac_c != "") ) {
                        $queryTRAN .= ("DELETE FROM SAITEMFAC WHERE CodSucu='$codsucu' AND NumeroD='$numerod_c' AND CodItem='$codItem' ") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
                    }

                    # COMPROMETER LAS EXISTENCIAS
                    if ($tipofac_facturar == 'E') {
                        $queryTRAN .= ("UPDATE SAEXIS SET 
                            [UnidCom]=[UnidCom] + IIF($esunid=1,$cantidad,0),
                            [CantCom]=[CantCom] + IIF($esunid=0,$cantidad,0)
                            WHERE (CodProd='$codItem' AND CodUbic='$codubic') ") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";

                        $queryTRAN .= ("UPDATE SAEXIS SET 
                            [UnidCom]=[UnidCom] - FLOOR([UnidCom]/$CantEmpaq)*$CantEmpaq,
                            [CantCom]=[CantCom] + FLOOR([UnidCom]/$CantEmpaq) 
                            WHERE (CodProd='$codItem' AND CodUbic='$codubic') ") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";

                        $queryTRAN .= ("UPDATE SAPROD SET 
                            [Compro] = [Compro] + IIF($esunid=0,$cantidad,0)
                            WHERE (CodProd='$codItem' )") ."\n";
                        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
                    }
                }
                $acum+=1;
            }
        }

        # cabecera factura
        $MotivoDevol = '';
        $crearAnticipo = 0;

        # CALCULO DEL DESCUENTO
        $queryTRAN .= ("SET @DescuentoUno = isnull(CASE WHEN ($porcentaje_primer_des>0) THEN $porcentaje_primer_des/100 ELSE 0 END, 0)")."\n";

        # OBTENCION DEL PERIODO
        $queryTRAN .= ("SELECT @Periodo=MesCurso FROM SACONF WHERE CodSucu='$codsucu' ")."\n";

        # Impuesto General IVA
        $queryTRAN .= ("SELECT @Iva16 = ISNULL(SUM(Monto),0), @TGravable_Iva16 = ISNULL(SUM(TGravable),0)
            FROM SATAXITF WHERE CodTaxs='IVA' AND NumeroD='$correl_nuevo' AND TipoFac='$tipofac_facturar' AND CodSucu='$codsucu'")."\n";
        $queryTRAN .= "IF @Iva16 > 0
        BEGIN"."\n";
        $queryTRAN .= ("INSERT INTO SATAXVTA 
            ([CodSucu],[TipoFac],[NumeroD],[CodTaxs],[MtoTax],[TGravable],[Monto])
            VALUES 
            ('$codsucu', '$tipofac_facturar', '$correl_nuevo', 'IVA', 16 ,(@TGravable_Iva16 - (@TGravable_Iva16*@DescuentoUno)), (@Iva16 - (@Iva16*@DescuentoUno)))")."\n";
        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
        $queryTRAN .= "END"."\n";

        # Impuesto General PVP
        $queryTRAN .= ("SELECT @IvaPer = ISNULL(SUM(Monto),0), @TGravable_IvaPer = ISNULL(SUM(TGravable),0), @Mtotax_IvaPer = ISNULL(SUM([MtoTax]),0)
            FROM SATAXITF WHERE CodTaxs='PVP' AND NumeroD='$correl_nuevo' AND TipoFac='$tipofac_facturar' AND CodSucu='$codsucu'")."\n";
        $queryTRAN .= "IF @IvaPer > 0
        BEGIN"."\n";
        $queryTRAN .= ("INSERT INTO SATAXVTA 
            ([CodSucu],[TipoFac],[NumeroD],[CodTaxs],[MtoTax],[TGravable],[Monto])
            VALUES
            ('$codsucu', '$tipofac_facturar', '$correl_nuevo', 'PVP', @Mtotax_IvaPer, @TGravable_IvaPer - (@TGravable_IvaPer*@DescuentoUno), @IvaPer)")."\n";
        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
        $queryTRAN .= "END"."\n";
        
        # Impuesto General IAL
        $queryTRAN .= ("SELECT @Ial = ISNULL(SUM(Monto),0), @TGravable_Ial = ISNULL(SUM(TGravable),0), @Mtotax_Ial = ISNULL(SUM([MtoTax]),0)
            FROM SATAXITF WHERE CodTaxs='IAL' AND NumeroD='$correl_nuevo' AND TipoFac='$tipofac_facturar' AND CodSucu='$codsucu'")."\n";
        $queryTRAN .= "IF @Ial > 0
        BEGIN"."\n";
        $queryTRAN .= ("INSERT INTO SATAXVTA 
            ([CodSucu],[TipoFac],[NumeroD],[CodTaxs],[MtoTax],[TGravable],[Monto])
            VALUES 
            ('$codsucu', '$tipofac_facturar', '$correl_nuevo', 'IAL', @Mtotax_Ial ,@TGravable_Ial - (@TGravable_Ial*@DescuentoUno), @Ial)")."\n";
        $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
        $queryTRAN .= "END"."\n";

        # Ultimo Saldo de cxc 
        $queryTRAN .= ("SELECT top 1  @SaldoAct = isnull(Saldo,0) FROM SAACXC WHERE CodClie='$codclie' ORDER BY FechaE DESC ");

        # Encabezado Factura General
        $queryTRAN .= ("INSERT INTO [dbo].[SAFACT]
            ([TipoFac]
                ,[NumeroD]
                ,[NroCtrol]
                ,[Status]
                ,[CodSucu]
                ,[CodEsta]
                ,[CodUsua]
                ,[EsCorrel]
                ,[CodConv]
                ,[Signo]
                ,[FechaT]
                ,[OTipo]
                ,[ONumero]
                ,[NumeroC]
                ,[NumeroT]
                ,[NumeroR]
                ,[FechaD1]
                ,[NumeroD1]
                ,[AutSRI]
                ,[NroEstable]
                ,[PtoEmision]
                ,[NumeroF]
                ,[NumeroP]
                ,[NumeroE]
                ,[NumeroZ]
                ,[Moneda]
                ,[Factor]
                ,[MontoMEx]
                ,[CodClie]
                ,[CodVend]
                ,[CodUbic]
                ,[Descrip]
                ,[Direc1]
                ,[Direc2]
                ,[Direc3]
                ,[ZipCode]
                ,[Telef]
                ,[ID3]
                ,[Monto]
                ,[MtoTax]
                ,[Fletes]
                ,[TGravable]
                ,[TExento]
                ,[CostoPrd]
                ,[CostoSrv]
                ,[DesctoP]
                ,[RetenIVA]
                ,[FechaR]
                ,[FechaI]
                ,[FechaE]
                ,[FechaV]
                ,[MtoTotal]
                ,[Contado]
                ,[Credito]
                ,[CancelI]
                ,[CancelA]
                ,[CancelE]
                ,[CancelC]
                ,[CancelT]
                ,[CancelG]
                ,[CancelP]
                ,[Cambio]
                ,[MtoExtra]
                ,[ValorPtos]
                ,[Descto1]
                ,[PctAnual]
                ,[MtoInt1]
                ,[Descto2]
                ,[PctManejo]
                ,[MtoInt2]
                ,[SaldoAct]
                ,[MtoPagos]
                ,[MtoNCredito]
                ,[MtoNDebito]
                ,[MtoFinanc]
                ,[DetalChq]
                ,[TotalPrd]
                ,[TotalSrv]
                ,[OrdenC]
                ,[CodOper]
                ,[NGiros]
                ,[NMeses]
                ,[MtoComiVta]
                ,[MtoComiCob]
                ,[MtoComiVtaD]
                ,[MtoComiCobD]
                ,[Notas1]
                ,[Notas2]
                ,[Notas3]
                ,[Notas4]
                ,[Notas5]
                ,[Notas6]
                ,[Notas7]
                ,[Notas8]
                ,[Notas9]
                ,[Notas10]
                ,[TipoTraE]
                ,[NumeroNCF]
                ,[TGravable0]
                ,[NIT]
                --  ,[AutSRIReten]  tuve que comentar
                --  ,[FecCadReten]  tuve que comentar
                --  ,[EstablReten]  tuve que comentar
                --  ,[PtoEmiReten]  tuve que comentar
                ,[FechaSRI]
                -- ,[EstadoFE]
                --  ,[CodTran]
                --  ,[FromTran]
                --  ,[TipoDev]
                --  ,[CodTarj]
                -- ,[NroTurno]
                --  ,[CancelTips]
                --  ,[NroUnicoL]
                --  ,[CodAlte]
                --  ,[Parcial]
                --   ,[NumeroU]
                --   ,[ImpuestoD]
                )
            
            select
                '$tipofac_facturar' --<TipoFac, varchar(1),>
                ,'$correl_nuevo' --<NumeroD, varchar(20),>
                ,Null --<NroCtrol, varchar(20),>
                ,NULL --<Status, varchar(2),>
                ,'$codsucu' --<CodSucu, varchar(5),
                ,'$codesta' --<CodEsta, varchar(10),>
                ,'$user' --<CodUsua, varchar(10),>
                ,1 --<EsCorrel, smallint,>
                ,NULL --<CodConv, varchar(10),>
                ,1 --<Signo, smallint,>
                ,getdate() --<FechaT, datetime,>
                ,CASE WHEN '$tipofac_c'!='' THEN '$tipofac_c' ELSE NULL END --<OTipo, varchar(1),>
                ,CASE WHEN '$numerod_c'!='' THEN '$numerod_c' ELSE NULL END --<ONumero, varchar(20),>
                ,NULL --<NumeroC, varchar(10),>
                ,NULL --<NumeroT, varchar(20),>
                ,CASE WHEN '$numerod_c'!='' THEN '$numerod_c' ELSE NULL END --<NumeroR, varchar(20),>
                ,'$fechaemi' --<FechaD1, datetime,>
                ,Null --<NumeroD1, varchar(15),>
                ,NULL --<AutSRI, varchar(40),>
                ,NULL --<NroEstable, varchar(10),>
                ,NULL --<PtoEmision, varchar(10),>
                ,NULL --<NumeroF, varchar(40),>
                ,NULL --<NumeroP, varchar(15),>
                ,NULL --<NumeroE, varchar(20),>
                ,NULL --<NumeroZ, varchar(10),>
                ,NULL --<Moneda, varchar(5),>
                ,$tasa --<Factor, decimal(28,4),>
                ,((TExento+Tgravable)/$tasa) - (((TExento+Tgravable)/$tasa)*@DescuentoUno) --<MontoMEx, decimal(28,4),>
                ,'$codclie' --<CodClie, varchar(15),>
                ,'$codvend' /*Vendedor*/ --<CodVend, varchar(10),>
                ,'$codubic' /*Deposito*/ --<CodUbic, varchar(10),>
                ,Descrip --<Descrip, varchar(60),>
                ,Direc1 --<Direc1, varchar(60),>
                ,CASE WHEN Direc2!='' THEN Direc2 ELSE NULL END --<Direc2, varchar(60),>
                ,NULL --<Direc3, varchar(60),>
                ,CASE WHEN ZipCode!='' THEN ZipCode ELSE NULL END --<ZipCode, varchar(20),>
                ,Telef --<Telef, varchar(30),>
                ,ID3 --<ID3, varchar(25),>
                ,(TExento+Tgravable) --<Monto, decimal(28,4),>
                ,((isnull(@Iva16,0) - (isnull(@Iva16,0)*@DescuentoUno))+isnull(@IvaPer,0)+isnull(@Ial,0)) --<MtoTax, decimal(28,4),>
                ,0 --,<Fletes, decimal(28,4),>
                ,Tgravable-(Tgravable*@DescuentoUno) --<TGravable, decimal(28,4),>
                ,TExento-(TExento*@DescuentoUno) --<TExento, decimal(28,4),>
                ,CostoSer --<CostoPrd, decimal(28,4),>
                ,0 --<CostoSrv, decimal(28,4),>
                ,0 --<DesctoP, decimal(28,4),>
                ,0 --<RetenIVA, decimal(28,4),>
                ,NULL --<FechaR, datetime,>
                ,'$fechaemi' --<FechaI, datetime,>
                ,'$fechaemi' --<FechaE, datetime,>
                ,'$fechaemi' --<FechaV, datetime,>
                , ((TExento+Tgravable+isnull(@Iva16,0)) - ((TExento+Tgravable+isnull(@Iva16,0))*@DescuentoUno))+isnull(@IvaPer,0)+isnull(@Ial,0)  --<MtoTotal, decimal(28,4),>
                ,0 --(TExento+Tgravable+MtoTax) --<Contado, decimal(28,4),>
                , ((TExento+Tgravable+isnull(@Iva16,0)) - ((TExento+Tgravable+isnull(@Iva16,0))*@DescuentoUno))+isnull(@IvaPer,0)+isnull(@Ial,0) - $monto_anticipo --<Credito, decimal(28,4),>
                ,0 --<CancelI, decimal(28,4),>
                ,0 --$monto_anticipo /*Pago Anticipo*/ --<CancelA, decimal(28,4),>
                ,0 --<CancelE, decimal(28,4),>
                ,0 --<CancelC, decimal(28,4),>
                ,0 --(TExento+Tgravable+MtoTax)-@Anticipo --<CancelT, decimal(28,4),>
                ,0 --<CancelG, decimal(28,4),>
                ,0 --<CancelP, decimal(28,4),>
                ,0 --@Cambio /*Vuelto Modulo*/ --<Cambio, decimal(28,4),>
                ,0 --<MtoExtra, decimal(28,4),>
                ,0 --<ValorPtos, decimal(28,4),>
                ,(Tgravable+TExento) * @DescuentoUno --<Descto1, decimal(28,4),>
                ,0 --<PctAnual, decimal(28,4),>
                ,0 --<MtoInt1, decimal(28,4),>
                ,0 --<Descto2, decimal(28,4),>
                ,0 --<PctManejo, decimal(28,4),>
                ,0 --MtoInt2, decimal(28,4),>
                ,isnull(@SaldoAct, 0) --<SaldoAct, decimal(28,4),>
                ,0 --<MtoPagos, decimal(28,4),>
                ,0 --<MtoNCredito, decimal(28,4),>
                ,0 --<MtoNDebito, decimal(28,4),>
                ,0 --<MtoFinanc, decimal(28,4),>
                ,NULL --<DetalChq, varchar(40),>
                ,Texento+Tgravable --<TotalPrd, decimal(28,4),>
                ,0 --<TotalSrv, decimal(28,4),>
                ,NULL --<OrdenC, varchar(30),>
                ,'$tipo_ope' --<CodOper, varchar(10),>
                ,0 --<NGiros, int,>
                ,0 --<NMeses, int,>
                ,0 --<MtoComiVta, decimal(28,4),>
                ,0 --<MtoComiCob, decimal(28,4),>
                ,0 --<MtoComiVtaD, decimal(28,4),>
                ,0 --<MtoComiCobD, decimal(28,4),>
                ,CASE WHEN '$coment1'!='' THEN '$coment1' ELSE NULL END --<Notas1, varchar(60),>
                ,CASE WHEN '$coment2'!='' THEN '$coment2' ELSE NULL END --<Notas2, varchar(60),>
                ,CASE WHEN '$coment3'!='' THEN '$coment3' ELSE NULL END --<Notas3, varchar(60),>
                ,CASE WHEN '$coment4'!='' THEN '$coment4' ELSE NULL END --<Notas4, varchar(60),>
                ,CASE WHEN '$coment5'!='' THEN '$coment5' ELSE NULL END --<Notas5, varchar(60),>
                ,NULL --<Notas6, varchar(60),>
                ,NULL --<Notas7, varchar(60),>
                ,NULL --<Notas8, varchar(60),>
                ,'APP' --<Notas9, varchar(60),>
                ,NULL --<Notas10, varchar(60),>
                ,0 --<TipoTraE, smallint,>
                ,NULL --<NumeroNCF, varchar(20),>
                ,0 --<TGravable0, decimal(28,3),>
                ,NULL --<NIT, varchar(15),>
                --,NULL --<AutSRIReten, varchar(10),>
                --,NULL --<FecCadReten, varchar(10),>
                --,NULL --<EstablReten, varchar(10),>
                --,NULL --<PtoEmiReten, varchar(10),>
                ,NULL --<FechaSRI, varchar(10),>
                --,0 --<EstadoFE, int,>
                --,NULL --<CodTran, varchar(10),>
                -- ,1 --<FromTran, int,>
                --  ,0 --<TipoDev, smallint,>
                --  ,NULL --<CodTarj, varchar(10),>
                -- ,0 --<NroTurno, int,>
                -- ,0 --<CancelTips, decimal(28,3),>
                --  ,0 --<NroUnicoL, int,>
                --  ,NULL --<CodAlte, varchar(10),>
                --  ,0 --<Parcial, smallint,>
                --  ,NULL --<NumeroU, varchar(20),>
                -- ,0 --<ImpuestoD, decimal(28,3),>)
                FROM 
                (SELECT sum(CASE WHEN Esexento = 0 then (Precio*Cantidad) else 0 end) Tgravable , sum(CASE WHEN Esexento = 0 then MtoTax else 0 end) MtoTax,  sum(CASE WHEN Esexento = 1 then (Precio*Cantidad) else 0 end) TExento, sum(Cantidad*Costo) CostoSer, 
                    NumeroD from saitemfac where NumeroD = '$correl_nuevo' AND TipoFac = '$tipofac_facturar' AND CodSucu = '$codsucu'
                    group by NumeroD, TipoFac) as Factura, SACLIE 
                where NumeroD = '$correl_nuevo' AND CodClie = '$codclie' ")."\n";
$queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";

        # ELIMINAR ITEM DE DOCUMENTO SI ESTA CARGADO
if ( in_array($tipofac_facturar, array('E')) && ($tipofac_c != "") ) {
    $queryTRAN .= ("SELECT @CantRegistNroOrg = COUNT(*) FROM SAITEMFAC WHERE NumeroD='$numerod_c' AND TipoFac='$tipofac_c' AND CodSucu='$codsucu' ");
    $queryTRAN .= "IF @CantRegistNroOrg = 0
    BEGIN"."\n";
    $queryTRAN .= ("DELETE FROM SAFACT   WHERE NumeroD='$numerod_c' AND TipoFac='$tipofac_c' AND CodSucu='$codsucu' ")."\n";
    $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
    $queryTRAN .= ("DELETE FROM SATAXITF WHERE NumeroD='$numerod_c' AND TipoFac='$tipofac_c' AND CodSucu='$codsucu' ")."\n";
    $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
    $queryTRAN .= ("DELETE FROM SATAXVTA WHERE NumeroD='$numerod_c' AND TipoFac='$tipofac_c' AND CodSucu='$codsucu' ")."\n";
    $queryTRAN .= "SET @NUMERRORS=@NUMERRORS+@@ERROR; IF (@NUMERRORS<>0) GOTO EvaluarError" ."\n\n";
    $queryTRAN .= "END"."\n"; 
}
}

$queryTRAN .= "EvaluarError:
IF @NUMERRORS>0
BEGIN
SET @MENSAJE = ERROR_MESSAGE()
SET @STATUS = 2;
ROLLBACK TRANSACTION VENTA;
END
ELSE
BEGIN
SET @STATUS = 1;
COMMIT TRANSACTION VENTA;
END
END TRY  
BEGIN CATCH  
SET @MENSAJE = ERROR_MESSAGE()
SET @STATUS = 2;
ROLLBACK TRANSACTION VENTA;
END CATCH;  

SELECT @STATUS response, @MENSAJE message";
$Transaction = mssql_query($queryTRAN);
$mensaje_err = mssql_result($Transaction,0,"message");
$procesar = (mssql_result($Transaction,0,"response") == 1);

} catch (Exception $e) {
    $queryTRAN .= "EvaluarError:
    ROLLBACK TRANSACTION VENTA;
    SET @STATUS = 2;
    
    END TRY  
    BEGIN CATCH  
    SET @MENSAJE = ERROR_MESSAGE()
    SET @STATUS = 2;
    ROLLBACK TRANSACTION VENTA;
    END CATCH;  

    SELECT @STATUS response, @MENSAJE message";
    $mensaje_err = "PHP ERROR <br>".$e->getMessage()." [linea ".$e->getLine()."] ".$e->getFile();
    $procesar = false;
}