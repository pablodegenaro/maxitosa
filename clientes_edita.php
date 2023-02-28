<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
$aux = $_GET['cli'];

$descrip = "";
$codclie = "";
$clasificacion = "";
$ruta_alternativa = "";
$ruta_alternativa_2 = "";
$dia_visita = "";
$latitud = "";
$longitud = "";
$ruc = "";
$segmentacion = "";
$convenio = "";
if ($aux != ""){
    $query = mssql_query("SELECT Descrip, Activo, cli99.* from SACLIE clie inner join SACLIE_99 cli99 on clie.CodClie = cli99.CodClie where clie.CodClie = '$aux'");
    $descrip = mssql_result($query,0,"descrip");
    $codclie = mssql_result($query,0,"codclie");
    $clasificacion = mssql_result($query,0,"clasificacion");
    $ruta_alternativa = mssql_result($query,0,"ruta_alternativa");
    $ruta_alternativa_2 = mssql_result($query,0,"ruta_alternativa_2");
    $frecuencia_visita = mssql_result($query,0,"frecuencia_visita");
    $limite_credito = mssql_result($query,0,"lcredito");
    $limite_credito = (!empty($limite_credito)) ? $limite_credito : 0;
    $dia_visita = mssql_result($query,0,"dia_visita");
    $latitud = mssql_result($query,0,"latitud");
    $longitud = mssql_result($query,0,"longitud");
    $ruc = mssql_result($query,0,"ruc");
    $portafolio = mssql_result($query,0,"portafolio");
    $licencia_licor = mssql_result($query,0,"licencia_licor");
    $canal = mssql_result($query,0,"canal");
    $pdv_ocasion = mssql_result($query,0,"pdv_ocasion");
    $formato_cliente = mssql_result($query,0,"formato_cliente");
    $formato_cliente_2 = mssql_result($query,0,"formato_cliente_2");
    $alcance = mssql_result($query,0,"alcance");
    $nivel_ejecucion = mssql_result($query,0,"nivel_ejecucion");
    $tipo = mssql_result($query,0,"tipo");
    $segmentacion = mssql_result($query,0,"segmentacion");
    $convenio = mssql_result($query,0,"convenio");
}?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Editar Cliente</li>
                    </ol>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-saint">
                    <script type="text/javascript">
                        function guarda(){
                                /*document.forms["registro_cliente"].submit();*/
                        }
                        function regresa(){
                            window.location.href = "principal1.php?page=clientes&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Editar Cliente: <?php echo utf8_encode($descrip) ; ?></h3>
                    </div>
                    <form class="form-horizontal" action="principal1.php?page=clientes_edita_procesa&mod=1&id=<?php echo $aux; ?>" method="post" id="registro_cliente" name="registro_cliente">
                        <div class="card-body">
                            <!-- Date -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="codclie" class="col-sm-2 col-form-label">Codigo Cliente</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="<?php echo $codclie; ?>" id="codclie" name="codclie" placeholder="Codigo Cliente" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="descrip" class="col-sm-2 col-form-label">Descripcion</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="<?php echo utf8_encode($descrip); ?>" id="descrip" name="descrip" placeholder="Descripcion" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="clasificacion" class="col-sm-2 col-form-label">Clasificacion</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="<?php echo $clasificacion; ?>" id="clasificacion" name="clasificacion" placeholder="Clasificacion">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="limitecredito" class="col-sm-2 col-form-label">Limite de Credito</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" value="<?php echo $limite_credito; ?>" id="limitecredito" name="limitecredito" placeholder="Limite de Credito">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ruta_alternativa" class="col-sm-2 col-form-label">Ruta Alternativa</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="ruta_alternativa" id="ruta_alternativa" style="width: 100%;" >
                                            <option value="">Seleccione un Vendedor</option>
                                            <option value="-">Todos los Vendedores</option>
                                            <?php 
                                            $vendedores= mssql_query("SELECT * from savend where activo = '1'");
                                            if (mssql_num_rows($vendedores) != 0){                                                     
                                                for($i=0;$i<mssql_num_rows($vendedores);$i++){
                                                    ?>                         
                                                    <option value="<?php echo mssql_result($vendedores,$i,"codvend"); ?>" <?php if (mssql_result($vendedores,$i,"codvend")==$ruta_alternativa) {echo 'selected'; }?> >
                                                        <?php echo mssql_result($vendedores,$i,"codvend"); ?>: <?php echo substr(mssql_result($vendedores,$i,"descrip"), 0, 35); ?>
                                                    </option>
                                                    <?php 
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ruta_alternativa_2" class="col-sm-2 col-form-label">Ruta Alternativa 2</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="ruta_alternativa_2" id="ruta_alternativa_2" style="width: 100%;" >
                                            <option value="">Seleccione un Vendedor</option>
                                            <option value="-">Todos los Vendedores</option>
                                            <?php 
                                            $vendedores= mssql_query("SELECT * from savend where activo = '1'");
                                            if (mssql_num_rows($vendedores) != 0){                                                     
                                                for($i=0;$i<mssql_num_rows($vendedores);$i++){
                                                    ?>                         
                                                    <option value="<?php echo mssql_result($vendedores,$i,"codvend"); ?>" <?php if (mssql_result($vendedores,$i,"codvend")==$ruta_alternativa_2) {echo 'selected'; }?> >
                                                        <?php echo mssql_result($vendedores,$i,"codvend"); ?>: <?php echo substr(mssql_result($vendedores,$i,"descrip"), 0, 35); ?>
                                                    </option>
                                                    <?php 
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="frecuencia_visita" class="col-sm-2 col-form-label">Frecuencia Visita</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="frecuencia_visita" id="frecuencia_visita" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="Semanal"   <?php if ($frecuencia_visita=='Semanal') echo 'selected'; ?>>SEMANAL</option>
                                            <option value="Quincenal" <?php if ($frecuencia_visita=='Quincenal') echo 'selected'; ?>>QUINCENAL</option>
                                            <option value="Mensual"   <?php if ($frecuencia_visita=='Mensual') echo 'selected'; ?>>MENSUAL</option>
                                            <option value="NA"   <?php if ($frecuencia_visita=='NA') echo 'selected'; ?>>No Aplica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="dia_visita" class="col-sm-2 col-form-label">Dia Visita</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="dia_visita" id="dia_visita" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="Lunes"     <?php if ($dia_visita=="Lunes") echo 'selected'; ?>>LUNES</option>
                                            <option value="Martes"    <?php if ($dia_visita=="Martes") echo 'selected'; ?>>MARTES</option>
                                            <option value="Miercoles" <?php if ($dia_visita=="Miercoles") echo 'selected'; ?>>MIERCOLES</option>
                                            <option value="Jueves"    <?php if ($dia_visita=="Jueves") echo 'selected'; ?>>JUEVES</option>
                                            <option value="Viernes"   <?php if ($dia_visita=="Viernes") echo 'selected'; ?>>VIERNES</option>
                                            <option value="Sabado"    <?php if ($dia_visita=="Sabado") echo 'selected'; ?>>SABADO</option>
                                            <option value="NA"    <?php if ($dia_visita=="NA") echo 'selected'; ?>>No Aplica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="latitud" class="col-sm-2 col-form-label">Latitud</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="latitud" value="<?php echo $latitud; ?>" name="latitud" placeholder="Latitud" onkeypress="return isNumberKey(this, event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="longitud" class="col-sm-2 col-form-label">Longitud</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="longitud" value="<?php echo $longitud; ?>" name="longitud" placeholder="Longitud" onkeypress="return isNumberKey(this, event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="ruc" class="col-sm-2 col-form-label">Ruc</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="ruc" value="<?php echo $ruc; ?>" name="ruc" placeholder="RUC">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="portafolio" class="col-sm-2 col-form-label">Portafolio</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="portafolio" id="portafolio" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="LICORES" <?php if ($portafolio=="LICORES") echo 'selected'; ?>>LICORES</option>
                                            <option value="MISCELANEOS/BNA" <?php if ($portafolio=="MISCELANEOS/BNA") echo 'selected'; ?>>MISCELANEOS/BNA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="licencia_licor" class="col-sm-2 col-form-label">Licencia Licor</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="licencia_licor" value="<?php echo $licencia_licor; ?>" name="licencia_licor" placeholder="Licencia Licor">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="canal" class="col-sm-2 col-form-label">Canal</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="canal" id="canal" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="ON TRADE"  <?php if ($canal=="ON TRADE") echo 'selected'; ?>>ON TRADE</option>
                                            <option value="OFF TRADE" <?php if ($canal=="OFF TRADE") echo 'selected'; ?>>OFF TRADE</option>
                                            <option value="OTROS"     <?php if ($canal=="OTROS") echo 'selected'; ?>>OTROS</option>
                                            <option value="OUT TRADE" <?php if ($canal=="OUT TRADE") echo 'selected'; ?>>OUT TRADE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="formato_cliente" class="col-sm-2 col-form-label">Formato Cliente</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="formato_cliente" id="formato_cliente" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="RESTAURANTE"     <?php if ($formato_cliente=="RESTAURANTE") echo 'selected'; ?>>RESTAURANTE</option>
                                            <option value="TIENDA DEPORTIVA" <?php if ($formato_cliente=="TIENDA DEPORTIVA") echo 'selected'; ?>>TIENDA DEPORTIVA</option>
                                            <option value="FARMACIA"        <?php if ($formato_cliente=="FARMACIA") echo 'selected'; ?>>FARMACIA</option>
                                            <option value="REST MODERNO"    <?php if ($formato_cliente=="REST MODERNO") echo 'selected'; ?>>REST MODERNO</option>
                                            <option value="BAR"             <?php if ($formato_cliente=="BAR") echo 'selected'; ?>>BAR</option>
                                            <option value="CAFE"            <?php if ($formato_cliente=="CAFE") echo 'selected'; ?>>CAFE</option>
                                            <option value="SUPERMERCADO"    <?php if ($formato_cliente=="SUPERMERCADO") echo 'selected'; ?>>SUPERMERCADO</option>
                                            <option value="RESTAURANTE MODERNO" <?php if ($formato_cliente=="RESTAURANTE MODERNO") echo 'selected'; ?>>RESTAURANTE MODERNO</option>
                                            <option value="HOTEL"           <?php if ($formato_cliente=="HOTEL") echo 'selected'; ?>>HOTEL</option>
                                            <option value="DISCOTECA"       <?php if ($formato_cliente=="DISCOTECA") echo 'selected'; ?>>DISCOTECA</option>
                                            <option value="E-COMMERCE"      <?php if ($formato_cliente=="E-COMMERCE") echo 'selected'; ?>>E-COMMERCE</option>
                                            <option value="MAYORISTA"       <?php if ($formato_cliente=="MAYORISTA") echo 'selected'; ?>>MAYORISTA</option>
                                            <option value="LICORERIA"       <?php if ($formato_cliente=="LICORERIA") echo 'selected'; ?>>LICORERIA</option>
                                            <option value="CONVENIENCIA"    <?php if ($formato_cliente=="CONVENIENCIA") echo 'selected'; ?>>CONVENIENCIA</option>
                                            <option value="RESTAURANTE CLASICO" <?php if ($formato_cliente=="RESTAURANTE CLASICO") echo 'selected'; ?>>RESTAURANTE CLASICO</option>
                                            <option value="AUTO REPUESTOS" <?php if ($formato_cliente=="AUTO REPUESTOS") echo 'selected'; ?>>AUTO REPUESTOS</option>
                                            <option value="CAFE" <?php if ($formato_cliente=="CAFE") echo 'selected'; ?>>CAFE</option>
                                            <option value="NA" <?php if ($formato_cliente=="NA") echo 'selected'; ?>>No Aplica</option>
                                            <option value="EMPLEADO" <?php if ($formato_cliente=="EMPLEADO") echo 'selected'; ?>>EMPLEADO</option>
                                            <option value="OFICINA" <?php if ($formato_cliente=="OFICINA") echo 'selected'; ?>>OFICINA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pdv_ocasion" class="col-sm-2 col-form-label">Pdv Ocacion</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="pdv_ocasion" id="pdv_ocasion" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="HIPERMERCADO"    <?php if ($pdv_ocasion=="HIPERMERCADO") echo 'selected'; ?>>HIPERMERCADO</option>
                                            <option value="ABASTO"          <?php if ($pdv_ocasion=="ABASTO") echo 'selected'; ?>>ABASTO</option>
                                            <option value="TIENDA DE CONVENIENCIA" <?php if ($pdv_ocasion=="TIENDA DE CONVENIENCIA") echo 'selected'; ?>>TIENDA DE CONVENIENCIA</option>
                                            <option value="TIENDA DEPORTIVA" <?php if ($pdv_ocasion=="TIENDA DEPORTIVA") echo 'selected'; ?>>TIENDA DEPORTIVA</option>
                                            <option value="QUIERO LUCIRME"  <?php if ($pdv_ocasion=="QUIERO LUCIRME") echo 'selected'; ?>>QUIERO LUCIRME</option>
                                            <option value="FARMACIA"        <?php if ($pdv_ocasion=="FARMACIA") echo 'selected'; ?>>FARMACIA</option>
                                            <option value="MAYORISTA PDV"   <?php if ($pdv_ocasion=="MAYORISTA PDV") echo 'selected'; ?>>MAYORISTA PDV</option>
                                            <option value="SUPERMERCADO"    <?php if ($pdv_ocasion=="SUPERMERCADO") echo 'selected'; ?>>SUPERMERCADO</option>
                                            <option value="MINIMERCADO"     <?php if ($pdv_ocasion=="MINIMERCADO") echo 'selected'; ?>>MINIMERCADO</option>
                                            <option value="TIENDA CONVENIENCIA" <?php if ($pdv_ocasion=="TIENDA CONVENIENCIA") echo 'selected'; ?>>TIENDA CONVENIENCIA</option>
                                            <option value="PDV DIGITAL"     <?php if ($pdv_ocasion=="PDV DIGITAL") echo 'selected'; ?>>PDV DIGITAL</option>
                                            <option value="VAMOS DE RUMBA"  <?php if ($pdv_ocasion=="VAMOS DE RUMBA") echo 'selected'; ?>>VAMOS DE RUMBA</option>
                                            <option value="BODEGON"         <?php if ($pdv_ocasion=="BODEGON") echo 'selected'; ?>>BODEGON</option>
                                            <option value="HOTEL"           <?php if ($pdv_ocasion=="HOTEL") echo 'selected'; ?>>HOTEL</option>
                                            <option value="MARKETPLACE"     <?php if ($pdv_ocasion=="MARKETPLACE") echo 'selected'; ?>>MARKETPLACE</option>
                                            <option value="COMPARTIR CON FAMILIA"   <?php if ($pdv_ocasion=="COMPARTIR CON FAMILIA") echo 'selected'; ?>>COMPARTIR CON FAMILIA</option>
                                            <option value="TIENDA AL MAYOR" <?php if ($pdv_ocasion=="TIENDA AL MAYOR") echo 'selected'; ?>>TIENDA AL MAYOR</option>
                                            <option value="COMPARTIR CON PANAS" <?php if ($pdv_ocasion=="COMPARTIR CON PANAS") echo 'selected'; ?>>COMPARTIR CON PANAS</option>
                                            <option value="AUTOSERVICIO"    <?php if ($pdv_ocasion=="AUTOSERVICIO") echo 'selected'; ?>>AUTOSERVICIO</option>
                                            <option value="SERVICIO ASISTIDO" <?php if ($pdv_ocasion=="SERVICIO ASISTIDO") echo 'selected'; ?>>SERVICIO ASISTIDO</option>
                                            <option value="MAYORISTA FDV"   <?php if ($pdv_ocasion=="MAYORISTA FDV") echo 'selected'; ?>>MAYORISTA FDV</option>
                                            <option value="NA" <?php if ($pdv_ocasion=="NA") echo 'selected'; ?>>No Aplica</option>
                                            <option value="TIENDA AUTOMOTRIZ" <?php if ($pdv_ocasion=="TIENDA AUTOMOTRIZ") echo 'selected'; ?>>TIENDA AUTOMOTRIZ</option>
                                            <option value="EMPLEADO" <?php if ($pdv_ocasion=="EMPLEADO") echo 'selected'; ?>>EMPLEADO</option>
                                            <option value="OFICINA" <?php if ($pdv_ocasion=="OFICINA") echo 'selected'; ?>>OFICINA</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="formato_cliente_2" class="col-sm-2 col-form-label">Formato Cliente 2</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="formato_cliente_2" id="formato_cliente_2" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="CADENA"          <?php if ($formato_cliente_2=="CADENA") echo 'selected'; ?>>CADENA</option>
                                            <option value="INDEPENDIENTE"   <?php if ($formato_cliente_2=="INDEPENDIENTE") echo 'selected'; ?>>INDEPENDIENTE</option>
                                            <option value="COMPARTIR EN FAMILIA"              <?php if ($formato_cliente_2=="COMPARTIR EN FAMILIA") echo 'selected'; ?>>COMPARTIR EN FAMILIA</option>
                                            <option value="COMPARTIR CON PANAS"              <?php if ($formato_cliente_2=="COMPARTIR CON PANAS") echo 'selected'; ?>>COMPARTIR CON PANAS</option>
                                            <option value="QUIERO LUCIRME"              <?php if ($formato_cliente_2=="QUIERO LUCIRME") echo 'selected'; ?>>QUIERO LUCIRME</option>
                                            <option value="VAMOS DE RUMBA"              <?php if ($formato_cliente_2=="VAMOS DE RUMBA") echo 'selected'; ?>>VAMOS DE RUMBA</option>
                                            <option value="CAFE"              <?php if ($formato_cliente_2=="CAFE") echo 'selected'; ?>>CAFE</option>
                                            <option value="NA"              <?php if ($formato_cliente_2=="NA") echo 'selected'; ?>>No Aplica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="alcance" class="col-sm-2 col-form-label">Alcance</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="alcance" id="alcance" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="NACIONAL" <?php if ($alcance=="NACIONAL") echo 'selected'; ?>>NACIONAL</option>
                                            <option value="LOCAL"    <?php if ($alcance=="LOCAL") echo 'selected'; ?>>LOCAL</option>
                                            <option value="REGIONAL" <?php if ($alcance=="REGIONAL") echo 'selected'; ?>>REGIONAL</option>
                                            <option value="NA"       <?php if ($alcance=="NA") echo 'selected'; ?>>No Aplica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nivel_ejecucion" class="col-sm-2 col-form-label">Nivel Ejecucion</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="nivel_ejecucion" id="nivel_ejecucion" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="SILVER"   <?php if ($nivel_ejecucion=="SILVER") echo 'selected'; ?>>SILVER</option>
                                            <option value="BRONZE"   <?php if ($nivel_ejecucion=="BRONZE") echo 'selected'; ?>>BRONZE</option>
                                            <option value="GOLD"     <?php if ($nivel_ejecucion=="GOLD") echo 'selected'; ?>>GOLD</option>
                                            <option value="PLATINUM" <?php if ($nivel_ejecucion=="PLATINUM") echo 'selected'; ?>>PLATINUM</option>
                                            <option value="NA"       <?php if ($nivel_ejecucion=="NA") echo 'selected'; ?>>No Aplica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="tipo" id="tipo" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                            <option value="Specialist"  <?php if ($tipo=="Specialist") echo 'selected'; ?>>Specialist</option>
                                            <option value="Analitico"   <?php if ($tipo=="Analitico") echo 'selected'; ?>>Analitico</option>
                                            <option value="Comer y Beber" <?php if ($tipo=="Comer y Beber") echo 'selected'; ?>>Comer y Beber</option>
                                            <option value="Direct to End User" <?php if ($tipo=="Direct to End User") echo 'selected'; ?>>Direct to End User</option>
                                            <option value="Otros"       <?php if ($tipo=="Otros") echo 'selected'; ?>>Otros</option>
                                            <option value="Oficina"     <?php if ($tipo=="Oficina") echo 'selected'; ?>>Oficina</option>
                                            <option value="Rumba"       <?php if ($tipo=="Rumba") echo 'selected'; ?>>Rumba</option>
                                            <option value="Directo"     <?php if ($tipo=="Directo") echo 'selected'; ?>>Directo</option>
                                            <option value="Conceptual"  <?php if ($tipo=="Conceptual") echo 'selected'; ?>>Conceptual</option>
                                            <option value="Convenience" <?php if ($tipo=="Convenience") echo 'selected'; ?>>Convenience</option>
                                            <option value="Compartir con Panas" <?php if ($tipo=="Compartir con Panas") echo 'selected'; ?>>Compartir con Panas</option>
                                            <option value="Social"      <?php if ($tipo=="Social") echo 'selected'; ?>>Social</option>
                                            <option value="Grocery"     <?php if ($tipo=="Grocery") echo 'selected'; ?>>Grocery</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="segmentacion" class="col-sm-2 col-form-label">Segmentacion</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="segmentacion" value="<?php echo $segmentacion; ?>" name="segmentacion" placeholder="Segmentacion Diagio"  onkeypress="return isNumberKey(this, event)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="convenio" class="col-sm-2 col-form-label">Convenio</label>
                                    <div class="col-sm-10">
                                        <select class="form-control custom-select" name="convenio" id="convenio" style="width: 100%;">
                                            <option name="" value="">--SELECCIONE UN CONVENIO--</option>
                                            <option value="0"   <?php if ($convenio=="0") echo 'selected'; ?>>SIN CONVENIO</option>
                                            <option value="1"   <?php if ($convenio=="1") echo 'selected'; ?>>DIAGEO</option>
                                            <option value="2"   <?php if ($convenio=="2") echo 'selected'; ?>>EURO</option>
                                            <option value="3"   <?php if ($convenio=="3") echo 'selected'; ?>>CALL CENTER</option>
                                            <option value="4"   <?php if ($convenio=="4") echo 'selected'; ?>>EMPLEADOS</option>
                                            <option value="5"   <?php if ($convenio=="5") echo 'selected'; ?>>MAYORISTA</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                            <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint float-right">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script type="text/javascript">
    function isNumberKey(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
            //Check if the text already contains the . character
            if (txt.value.indexOf('.') === -1) {
                return true;
            } else {
                return false;
            }
        } else {
            if (charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
        }
        return true;
    }
</script>