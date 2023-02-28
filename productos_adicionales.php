<?php
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <!--  <h2 id="title_permisos">Ultima Activacion Clientes1</h2> -->
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                            <li class="breadcrumb-item active">Datos Adicionales de Productos</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-saint">
                    <script type="text/javascript">
                        function guarda(){
                                /* document.forms["registro_usuarios"].submit()aa;*/
                        }
                        function regresa(){
                            window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                        }
                    </script>
                    <div class="card-header">
                        <h3 class="card-title">Datos Adicionales de Productos</h3>&nbsp;&nbsp;&nbsp;
                    </div>
                    <form name="formulario" method="post" action="productos_adicionales_procesa.php">
                        <div class="card-body">
                            <?php
                            $productos = mssql_query("SELECT prod.CodProd, Descrip, Marca, capacidad_botella, proveedor, casa_representacion, clasificacion_categoria, sub_clasificacion_categoria, grado_alcoholico
                               from saprod prod  left join SAPROD_99 prod99 on prod.CodProd = prod99.CodProd");

                               ?>
                               <!--  <table id="example2" class="table table-bordered table-hover"> -->
                                 <table id="example5" class="table table-sm table-bordered table-striped table-responsive p-0">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Marca</th>
                                            <th>Contenido Neto</th>
                                            <th>Proveedor</th>
                                            <th>Casa representacion</th>
                                            <th>Clasificacion</th>
                                            <th>Sub Clasificacion</th>
                                            <th>Grado Alcolico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($productos); $i++) {
                                            $proveedor = trim(mssql_result($productos,$i,"proveedor"));
                                            $casa_representacion = trim(mssql_result($productos,$i,"casa_representacion"));
                                            $clasificacion_categoria = trim(mssql_result($productos,$i,"clasificacion_categoria"));
                                            $sub_clasificacion_categoria = trim(mssql_result($productos,$i,"sub_clasificacion_categoria"));
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo mssql_result($productos, $i, "CodProd"); ?>
                                                    <input type="hidden" name="cod[]"
                                                    value="<?php echo trim(mssql_result($productos, $i, 'CodProd')); ?>">
                                                </td>
                                                <td class="text-left"><?php echo utf8_encode(mssql_result($productos, $i, "Descrip")); ?></td>
                                                <td class="text-center"><?php echo utf8_encode(mssql_result($productos, $i, "Marca")); ?></td>
                                                <td class="text-center">
                                                    <input type="text" name="capacidad_botella[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                    value="<?= mssql_result($productos, $i, "capacidad_botella"); ?>">
                                                    <td class="text-center">
                                                        <select class="form-control custom-select" name="proveedor[]" id="proveedor[]" style="width: 100%;">
                                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="ALIMENTOS BUONO"   <?php if ($proveedor=='ALIMENTOS BUONO') echo 'selected'; ?>>ALIMENTOS BUONO</option>
                                                            <option value="CARBON TOTAL EXPRESS" <?php if ($proveedor=='CARBON TOTAL EXPRESS') echo 'selected'; ?>>CARBON TOTAL EXPRESS</option>
                                                            <option value="CHOCOBRU" <?php if ($proveedor=='CHOCOBRU') echo 'selected'; ?>>CHOCOBRU</option>
                                                            <option value="COMERCIALIZADORA SIKER" <?php if ($proveedor=='COMERCIALIZADORA SIKER') echo 'selected'; ?>>COMERCIALIZADORA SIKER</option>
                                                            <option value="DIAGEO" <?php if ($proveedor=='DIAGEO') echo 'selected'; ?>>DIAGEO</option>
                                                            <option value="DIMASSI" <?php if ($proveedor=='DIMASSI') echo 'selected'; ?>>DIMASSI</option>
                                                            <option value="EL CAIMAN" <?php if ($proveedor=='EL CAIMAN') echo 'selected'; ?>>EL CAIMAN</option>
                                                            <option value="ETCA" <?php if ($proveedor=='ETCA') echo 'selected'; ?>>ETCA</option>
                                                            <option value="EUROLICORES" <?php if ($proveedor=='EUROLICORES') echo 'selected'; ?>>EUROLICORES</option>
                                                            <option value="GRUPO LATVIK" <?php if ($proveedor=='GRUPO LATVIK') echo 'selected'; ?>>GRUPO LATVIK</option>
                                                            <option value="ID" <?php if ($proveedor=='ID') echo 'selected'; ?>>ID</option>
                                                            <option value="METROPOLITAN" <?php if ($proveedor=='METROPOLITAN') echo 'selected'; ?>>METROPOLITAN</option>
                                                            <option value="MULTIMPORT 826" <?php if ($proveedor=='MULTIMPORT 826') echo 'selected'; ?>>MULTIMPORT 826</option>
                                                            <option value="PARUPA" <?php if ($proveedor=='PARUPA') echo 'selected'; ?>>PARUPA</option>
                                                            <option value="POLYCARGO" <?php if ($proveedor=='POLYCARGO') echo 'selected'; ?>>POLYCARGO</option>
                                                            <option value="PRODALIC" <?php if ($proveedor=='PRODALIC') echo 'selected'; ?>>PRODALIC</option>
                                                            <option value="STELLE & FORTUNE" <?php if ($proveedor=='STELLE & FORTUNE') echo 'selected'; ?>>STELLE & FORTUNE</option>
                                                            <option value="VELAS 3N, C.A." <?php if ($proveedor=='VELAS 3N, C.A.') echo 'selected'; ?>>VELAS 3N, C.A.</option>
                                                            <option value="PARAWA" <?php if ($proveedor=='PARAWA') echo 'selected'; ?>>PARAWA</option>
                                                            <option value="CAMPOS DE SANAA" <?php if ($proveedor=='CAMPOS DE SANAA') echo 'selected'; ?>>CAMPOS DE SANAA</option>
                                                            <option value="UNITED CHEMICAL PACKAGING" <?php if ($proveedor=='UNITED CHEMICAL PACKAGING') echo 'selected'; ?>>UNITED CHEMICAL PACKAGING</option>
                                                            <option value="THEODORA 1111" <?php if ($proveedor=='THEODORA 1111') echo 'selected'; ?>>THEODORA 1111</option>
                                                            <option value="INVERSIONES CDE" <?php if ($proveedor=='INVERSIONES CDE') echo 'selected'; ?>>INVERSIONES CDE</option>
                                                            <option value="Furia Energy Drink" <?php if ($proveedor=='Furia Energy Drink') echo 'selected'; ?>>Furia Energy Drink</option>
                                                            <option value="Inversiones Easy Market" <?php if ($proveedor=='Inversiones Easy Market') echo 'selected'; ?>>Inversiones Easy Market</option>
                                                            <option value="Maxi Empaque 18" <?php if ($proveedor=='Maxi Empaque 18') echo 'selected'; ?>>Maxi Empaque 18</option>
                                                            <option value="Mystic Brands" <?php if ($proveedor=='Mystic Brands') echo 'selected'; ?>>Mystic Brands</option>
                                                            <option value="Disven Express" <?php if ($proveedor=='Disven Express') echo 'selected'; ?>>Disven Express</option>
                                                            <option value="SHALOM, CA" <?php if ($proveedor=='SHALOM, CA') echo 'selected'; ?>>SHALOM, CA</option>
                                                            <option value="DISTRIBUIDORA AREL C.D., C.A" <?php if ($proveedor=='DISTRIBUIDORA AREL C.D., C.A') echo 'selected'; ?>>DISTRIBUIDORA AREL C.D., C.A</option>
                                                            <option value="MO CUICHLE C.A" <?php if ($proveedor=='MO CUICHLE C.A') echo 'selected'; ?>>MO CUICHLE C.A</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <select class="form-control custom-select" name="casa_representacion[]" id="casa_representacion[]" style="width: 100%;">
                                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="ALIMENTOS BUONO"   <?php if ($casa_representacion=='ALIMENTOS BUONO') echo 'selected'; ?>>ALIMENTOS BUONO</option>
                                                            <option value="Anheuser-Busch" <?php if ($casa_representacion=='Anheuser-Busch') echo 'selected'; ?>>Anheuser-Busch</option>
                                                            <option value="BETTY CROCKER" <?php if ($casa_representacion=='BETTY CROCKER') echo 'selected'; ?>>BETTY CROCKER</option>
                                                            <option value="BIGELOW" <?php if ($casa_representacion=='BIGELOW') echo 'selected'; ?>>BIGELOW</option>
                                                            <option value="CACHAMAY" <?php if ($casa_representacion=='CACHAMAY') echo 'selected'; ?>>CACHAMAY</option>
                                                            <option value="CAMARITA" <?php if ($casa_representacion=='CAMARITA') echo 'selected'; ?>>CAMARITA</option>
                                                            <option value="CANA LISA" <?php if ($casa_representacion=='CANA LISA') echo 'selected'; ?>>CANA LISA</option>
                                                            <option value="CARBON TOTAL EXPRESS" <?php if ($casa_representacion=='CARBON TOTAL EXPRESS') echo 'selected'; ?>>CARBON TOTAL EXPRESS</option>
                                                            <option value="CHIMO EL TIGRITO" <?php if ($casa_representacion=='CHIMO EL TIGRITO') echo 'selected'; ?>>CHIMO EL TIGRITO</option>
                                                            <option value="COSTA VENEZUELA" <?php if ($casa_representacion=='COSTA VENEZUELA') echo 'selected'; ?>>COSTA VENEZUELA</option>
                                                            <option value="DIAGEO" <?php if ($casa_representacion=='DIAGEO') echo 'selected'; ?>>DIAGEO</option>
                                                            <option value="DIMASSI" <?php if ($casa_representacion=='DIMASSI') echo 'selected'; ?>>DIMASSI</option>
                                                            <option value="DIMASSI-OTROS" <?php if ($casa_representacion=='DIMASSI-OTROS') echo 'selected'; ?>>DIMASSI-OTROS</option>
                                                            <option value="DOM PERIGNON" <?php if ($casa_representacion=='DOM PERIGNON') echo 'selected'; ?>>DOM PERIGNON</option>
                                                            <option value="DON GIOVA" <?php if ($casa_representacion=='DON GIOVA') echo 'selected'; ?>>DON GIOVA</option>
                                                            <option value="EL VALLE" <?php if ($casa_representacion=='EL VALLE') echo 'selected'; ?>>EL VALLE</option>
                                                            <option value="ETCA" <?php if ($casa_representacion=='ETCA') echo 'selected'; ?>>ETCA</option>
                                                            <option value="EUREKA" <?php if ($casa_representacion=='EUREKA') echo 'selected'; ?>>EUREKA</option>
                                                            <option value="GRUPO MODELO" <?php if ($casa_representacion=='GRUPO MODELO') echo 'selected'; ?>>GRUPO MODELO</option>
                                                            <option value="JOHNNIE WALKER" <?php if ($casa_representacion=='JOHNNIE WALKER') echo 'selected'; ?>>JOHNNIE WALKER</option>
                                                            <option value="KARAT" <?php if ($casa_representacion=='KARAT') echo 'selected'; ?>>KARAT</option>
                                                            <option value="KIMBERLY-CLARK" <?php if ($casa_representacion=='KIMBERLY-CLARK') echo 'selected'; ?>>KIMBERLY-CLARK</option>
                                                            <option value="LA UVITA" <?php if ($casa_representacion=='LA UVITA') echo 'selected'; ?>>LA UVITA</option>
                                                            <option value="LIKI LIKI" <?php if ($casa_representacion=='LIKI LIKI') echo 'selected'; ?>>LIKI LIKI</option>
                                                            <option value="MALAGA" <?php if ($casa_representacion=='MALAGA') echo 'selected'; ?>>MALAGA</option>
                                                            <option value="MICO" <?php if ($casa_representacion=='MICO') echo 'selected'; ?>>MICO</option>
                                                            <option value="MOET & CHANDON" <?php if ($casa_representacion=='MOET & CHANDON') echo 'selected'; ?>>MOET & CHANDON</option>
                                                            <option value="NATAL" <?php if ($casa_representacion=='NATAL') echo 'selected'; ?>>NATAL</option>
                                                            <option value="OKYALO" <?php if ($casa_representacion=='OKYALO') echo 'selected'; ?>>OKYALO</option>
                                                            <option value="ONEHOPE" <?php if ($casa_representacion=='ONEHOPE') echo 'selected'; ?>>ONEHOPE</option>
                                                            <option value="POST" <?php if ($casa_representacion=='POST') echo 'selected'; ?>>POST</option>
                                                            <option value="RED BULL" <?php if ($casa_representacion=='RED BULL') echo 'selected'; ?>>RED BULL</option>
                                                            <option value="REEN" <?php if ($casa_representacion=='REEN') echo 'selected'; ?>>REEN</option>
                                                            <option value="ROBERT MONDAVI" <?php if ($casa_representacion=='ROBERT MONDAVI') echo 'selected'; ?>>ROBERT MONDAVI</option>
                                                            <option value="RONKYOLO" <?php if ($casa_representacion=='RONKYOLO') echo 'selected'; ?>>RONKYOLO</option>
                                                            <option value="SEVI" <?php if ($casa_representacion=='SEVI') echo 'selected'; ?>>SEVI</option>
                                                            <option value="SLIM FAST" <?php if ($casa_representacion=='SLIM FAST') echo 'selected'; ?>>SLIM FAST</option>
                                                            <option value="STELLE & FORTUNE" <?php if ($casa_representacion=='STELLE & FORTUNE') echo 'selected'; ?>>STELLE & FORTUNE</option>
                                                            <option value="SUMMERS EVE" <?php if ($casa_representacion=='SUMMERS EVE') echo 'selected'; ?>>SUMMERS EVE</option>
                                                            <option value="SWEET BABY RAYS" <?php if ($casa_representacion=='SWEET BABY RAYS') echo 'selected'; ?>>SWEET BABY RAYS</option>
                                                            <option value="UNILEVER ANDINA" <?php if ($casa_representacion=='UNILEVER ANDINA') echo 'selected'; ?>>UNILEVER ANDINA</option>
                                                            <option value="UNILEVER INTERNATIONAL" <?php if ($casa_representacion=='UNILEVER INTERNATIONAL') echo 'selected'; ?>>UNILEVER INTERNATIONAL</option>
                                                            <option value="VELAS 3N" <?php if ($casa_representacion=='VELAS 3N') echo 'selected'; ?>>VELAS 3N</option>
                                                            <option value="VEUVE CLICQUOT" <?php if ($casa_representacion=='VEUVE CLICQUOT') echo 'selected'; ?>>VEUVE CLICQUOT</option>
                                                            <option value="WOODBRIDGE" <?php if ($casa_representacion=='WOODBRIDGE') echo 'selected'; ?>>WOODBRIDGE</option>
                                                            <option value="BENIZAR" <?php if ($casa_representacion=='BENIZAR') echo 'selected'; ?>>BENIZAR</option>
                                                            <option value="CASTILLO DE BENIZAR" <?php if ($casa_representacion=='CASTILLO DE BENIZAR') echo 'selected'; ?>>CASTILLO DE BENIZAR</option>
                                                            <option value="ESTOLA" <?php if ($casa_representacion=='ESTOLA') echo 'selected'; ?>>ESTOLA</option>
                                                            <option value="PARAWA" <?php if ($casa_representacion=='PARAWA') echo 'selected'; ?>>PARAWA</option>
                                                            <option value="LA TITNA" <?php if ($casa_representacion=='LA TITNA') echo 'selected'; ?>>LA TITNA</option>
                                                            <option value="CAMPOS DE SANAA" <?php if ($casa_representacion=='CAMPOS DE SANAA') echo 'selected'; ?>>CAMPOS DE SANAA</option>
                                                            <option value="HELLMANN S" <?php if ($casa_representacion=='HELLMANN S') echo 'selected'; ?>>HELLMANN S</option>
                                                            <option value="UNITED CHEMICAL PACKAGING" <?php if ($casa_representacion=='UNITED CHEMICAL PACKAGING') echo 'selected'; ?>>UNITED CHEMICAL PACKAGING</option>
                                                            <option value="WEIMAN" <?php if ($casa_representacion=='WEIMAN') echo 'selected'; ?>>WEIMAN</option>
                                                            <option value="Furia Energy" <?php if ($casa_representacion=='Furia Energy') echo 'selected'; ?>>Furia Energy</option>
                                                            <option value="Fiore Frind" <?php if ($casa_representacion=='Fiore Frind') echo 'selected'; ?>>Fiore Frind</option>
                                                            <option value="Quinta do Gradil" <?php if ($casa_representacion=='Quinta do Gradil') echo 'selected'; ?>>Quinta do Gradil</option>
                                                            <option value="Parras Wines" <?php if ($casa_representacion=='Parras Wines') echo 'selected'; ?>>Parras Wines</option>
                                                            <option value="WIKI WIKI" <?php if ($casa_representacion=='WIKI WIKI') echo 'selected'; ?>>WIKI WIKI</option>
                                                            <option value="Mystic Brands" <?php if ($casa_representacion=='Mystic Brands') echo 'selected'; ?>>Mystic Brands</option>
                                                            <option value="Eliodoro Gonzalez" <?php if ($casa_representacion=='Eliodoro Gonzalez') echo 'selected'; ?>>Eliodoro Gonzalez</option>
                                                            <option value="AREL FOODS" <?php if ($casa_representacion=='AREL FOODS') echo 'selected'; ?>>AREL FOODS</option>
                                                        </select>
                                                        
                                                    </td>
                                                    <td class="text-center">
                                                        <select class="form-control custom-select" name="clasificacion_categoria[]" id="clasificacion_categoria[]" style="width: 100%;">
                                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="BEBIDAS ESPIRITUOSAS" <?php if ($clasificacion_categoria=='BEBIDAS ESPIRITUOSAS') echo 'selected'; ?>>BEBIDAS ESPIRITUOSAS</option>
                                                            <option value="BEBIDAS NO-ALCOHOLICAS" <?php if ($clasificacion_categoria=='BEBIDAS NO-ALCOHOLICAS') echo 'selected'; ?>>BEBIDAS NO-ALCOHOLICAS</option>
                                                            <option value="CERVEZAS" <?php if ($clasificacion_categoria=='CERVEZAS') echo 'selected'; ?>>CERVEZAS</option>
                                                            <option value="CREMAS" <?php if ($clasificacion_categoria=='CREMAS') echo 'selected'; ?>>CREMAS</option>
                                                            <option value="GINEBRAS" <?php if ($clasificacion_categoria=='GINEBRAS') echo 'selected'; ?>>GINEBRAS</option>
                                                            <option value="LINEA CUIDADO DEL HOGAR" <?php if ($clasificacion_categoria=='LINEA CUIDADO DEL HOGAR') echo 'selected'; ?>>LINEA CUIDADO DEL HOGAR</option>
                                                            <option value="LINEA CUIDADO PERSONAL" <?php if ($clasificacion_categoria=='LINEA CUIDADO PERSONAL') echo 'selected'; ?>>LINEA CUIDADO PERSONAL</option>
                                                            <option value="LINEA DE ALIMENTOS" <?php if ($clasificacion_categoria=='LINEA DE ALIMENTOS') echo 'selected'; ?>>LINEA DE ALIMENTOS</option>
                                                            <option value="LINEA DE BELLEZA" <?php if ($clasificacion_categoria=='LINEA DE BELLEZA') echo 'selected'; ?>>LINEA DE BELLEZA</option>
                                                            <option value="LINEA DE CONSUMIBLES" <?php if ($clasificacion_categoria=='LINEA DE CONSUMIBLES') echo 'selected'; ?>>LINEA DE CONSUMIBLES</option>
                                                            <option value="LINEA PROTECCION BEBE" <?php if ($clasificacion_categoria=='LINEA PROTECCION BEBE') echo 'selected'; ?>>LINEA PROTECCION BEBE</option>
                                                            <option value="LINEA PROTECCION FEMENINA" <?php if ($clasificacion_categoria=='LINEA PROTECCION FEMENINA') echo 'selected'; ?>>LINEA PROTECCION FEMENINA</option>
                                                            <option value="LSR" <?php if ($clasificacion_categoria=='LSR') echo 'selected'; ?>>LSR</option>
                                                            <option value="RONES" <?php if ($clasificacion_categoria=='RONES') echo 'selected'; ?>>RONES</option>
                                                            <option value="SCOTCH" <?php if ($clasificacion_categoria=='SCOTCH') echo 'selected'; ?>>SCOTCH</option>
                                                            <option value="VINOS" <?php if ($clasificacion_categoria=='VINOS') echo 'selected'; ?>>VINOS</option>
                                                            <option value="VODKA" <?php if ($clasificacion_categoria=='VODKA') echo 'selected'; ?>>VODKA</option>
                                                            <option value="VODKAS" <?php if ($clasificacion_categoria=='VODKAS') echo 'selected'; ?>>VODKAS</option>
                                                            <option value="LINEA DE CUIDADO AUTOMOTRIZ" <?php if ($clasificacion_categoria=='LINEA DE CUIDADO AUTOMOTRIZ') echo 'selected'; ?>>LINEA DE CUIDADO AUTOMOTRIZ</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <select class="form-control custom-select" name="sub_clasificacion_categoria[]" id="sub_clasificacion_categoria[]" style="width: 100%;">
                                                            <option name="" value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="ACEITUNAS" <?php if ($sub_clasificacion_categoria=='ACEITUNAS') echo 'selected'; ?>>ACEITUNAS</option>
                                                            <option value="ACONDICIONADOR" <?php if ($sub_clasificacion_categoria=='ACONDICIONADOR') echo 'selected'; ?>>ACONDICIONADOR</option>
                                                            <option value="ADOBO" <?php if ($sub_clasificacion_categoria=='ADOBO') echo 'selected'; ?>>ADOBO</option>
                                                            <option value="AGUARDIENTE" <?php if ($sub_clasificacion_categoria=='AGUARDIENTE') echo 'selected'; ?>>AGUARDIENTE</option>
                                                            <option value="ALCPARRAS" <?php if ($sub_clasificacion_categoria=='ALCPARRAS') echo 'selected'; ?>>ALCPARRAS</option>
                                                            <option value="ANIS" <?php if ($sub_clasificacion_categoria=='ANIS') echo 'selected'; ?>>ANIS</option>
                                                            <option value="ANTITRANSPIRANTE" <?php if ($sub_clasificacion_categoria=='ANTITRANSPIRANTE') echo 'selected'; ?>>ANTITRANSPIRANTE</option>
                                                            <option value="ANTITRANSPIRANTE PARA PIES" <?php if ($sub_clasificacion_categoria=='ANTITRANSPIRANTE PARA PIES') echo 'selected'; ?>>ANTITRANSPIRANTE PARA PIES</option>
                                                            <option value="ATUN" <?php if ($sub_clasificacion_categoria=='ATUN') echo 'selected'; ?>>ATUN</option>
                                                            <option value="BATERIAS" <?php if ($sub_clasificacion_categoria=='BATERIAS') echo 'selected'; ?>>BATERIAS</option>
                                                            <option value="BEBIDA ALOE VERA" <?php if ($sub_clasificacion_categoria=='BEBIDA ALOE VERA') echo 'selected'; ?>>BEBIDA ALOE VERA</option>
                                                            <option value="BEBIDA ESPIRITUOSA SECA" <?php if ($sub_clasificacion_categoria=='BEBIDA ESPIRITUOSA SECA') echo 'selected'; ?>>BEBIDA ESPIRITUOSA SECA</option>
                                                            <option value="BEBIDAS ENERGETICAS" <?php if ($sub_clasificacion_categoria=='BEBIDAS ENERGETICAS') echo 'selected'; ?>>BEBIDAS ENERGETICAS</option>
                                                            <option value="BODYSPRAY" <?php if ($sub_clasificacion_categoria=='BODYSPRAY') echo 'selected'; ?>>BODYSPRAY</option>
                                                            <option value="CARBON" <?php if ($sub_clasificacion_categoria=='CARBON') echo 'selected'; ?>>CARBON</option>
                                                            <option value="CEREAL" <?php if ($sub_clasificacion_categoria=='CEREAL') echo 'selected'; ?>>CEREAL</option>
                                                            <option value="CERVEZAS IMPORTADAS" <?php if ($sub_clasificacion_categoria=='CERVEZAS IMPORTADAS') echo 'selected'; ?>>CERVEZAS IMPORTADAS</option>
                                                            <option value="CLORO" <?php if ($sub_clasificacion_categoria=='CLORO') echo 'selected'; ?>>CLORO</option>
                                                            <option value="CREMA DE PEINAR" <?php if ($sub_clasificacion_categoria=='CREMA DE PEINAR') echo 'selected'; ?>>CREMA DE PEINAR</option>
                                                            <option value="CREMA FACIAL" <?php if ($sub_clasificacion_categoria=='CREMA FACIAL') echo 'selected'; ?>>CREMA FACIAL</option>
                                                            <option value="CUBITOS" <?php if ($sub_clasificacion_categoria=='CUBITOS') echo 'selected'; ?>>CUBITOS</option>
                                                            <option value="DESINFECTANTE" <?php if ($sub_clasificacion_categoria=='DESINFECTANTE') echo 'selected'; ?>>DESINFECTANTE</option>
                                                            <option value="DESODORANTE INTIMO" <?php if ($sub_clasificacion_categoria=='DESODORANTE INTIMO') echo 'selected'; ?>>DESODORANTE INTIMO</option>
                                                            <option value="DETERGENTE EN POLVO" <?php if ($sub_clasificacion_categoria=='DETERGENTE EN POLVO') echo 'selected'; ?>>DETERGENTE EN POLVO</option>
                                                            <option value="DETERGENTE LIQUIDO" <?php if ($sub_clasificacion_categoria=='DETERGENTE LIQUIDO') echo 'selected'; ?>>DETERGENTE LIQUIDO</option>
                                                            <option value="DLX" <?php if ($sub_clasificacion_categoria=='DLX') echo 'selected'; ?>>DLX</option>
                                                            <option value="ENCURTIDOS" <?php if ($sub_clasificacion_categoria=='ENCURTIDOS') echo 'selected'; ?>>ENCURTIDOS</option>
                                                            <option value="ESMALTE DE UNAS" <?php if ($sub_clasificacion_categoria=='ESMALTE DE UNAS') echo 'selected'; ?>>ESMALTE DE UNAS</option>
                                                            <option value="ESPUMANTES" <?php if ($sub_clasificacion_categoria=='ESPUMANTES') echo 'selected'; ?>>ESPUMANTES</option>
                                                            <option value="JABON EN BARRA" <?php if ($sub_clasificacion_categoria=='JABON EN BARRA') echo 'selected'; ?>>JABON EN BARRA</option>
                                                            <option value="JABON INTIMO" <?php if ($sub_clasificacion_categoria=='JABON INTIMO') echo 'selected'; ?>>JABON INTIMO</option>
                                                            <option value="KETCHUP" <?php if ($sub_clasificacion_categoria=='KETCHUP') echo 'selected'; ?>>KETCHUP</option>
                                                            <option value="LAVAPLATOS" <?php if ($sub_clasificacion_categoria=='LAVAPLATOS') echo 'selected'; ?>>LAVAPLATOS</option>
                                                            <option value="MEZCLA POSTRES" <?php if ($sub_clasificacion_categoria=='MEZCLA POSTRES') echo 'selected'; ?>>MEZCLA POSTRES</option>
                                                            <option value="MOSTAZA" <?php if ($sub_clasificacion_categoria=='MOSTAZA') echo 'selected'; ?>>MOSTAZA</option>
                                                            <option value="PANALES DESECHABLES" <?php if ($sub_clasificacion_categoria=='PANALES DESECHABLES') echo 'selected'; ?>>PANALES DESECHABLES</option>
                                                            <option value="PAPEL HIGIENICO" <?php if ($sub_clasificacion_categoria=='PAPEL HIGIENICO') echo 'selected'; ?>>PAPEL HIGIENICO</option>
                                                            <option value="PASTA DE TOMATE" <?php if ($sub_clasificacion_categoria=='PASTA DE TOMATE') echo 'selected'; ?>>PASTA DE TOMATE</option>
                                                            <option value="PASTA DENTAL" <?php if ($sub_clasificacion_categoria=='PASTA DENTAL') echo 'selected'; ?>>PASTA DENTAL</option>
                                                            <option value="PONCHE CREMA" <?php if ($sub_clasificacion_categoria=='PONCHE CREMA') echo 'selected'; ?>>PONCHE CREMA</option>
                                                            <option value="PRIMARY" <?php if ($sub_clasificacion_categoria=='PRIMARY') echo 'selected'; ?>>PRIMARY</option>
                                                            <option value="QUITAMANCHAS" <?php if ($sub_clasificacion_categoria=='QUITAMANCHAS') echo 'selected'; ?>>QUITAMANCHAS</option>
                                                            <option value="RTD" <?php if ($sub_clasificacion_categoria=='RTD') echo 'selected'; ?>>RTD</option>
                                                            <option value="SALSA BBQ" <?php if ($sub_clasificacion_categoria=='SALSA BBQ') echo 'selected'; ?>>SALSA BBQ</option>
                                                            <option value="SALSAS" <?php if ($sub_clasificacion_categoria=='SALSAS') echo 'selected'; ?>>SALSAS</option>
                                                            <option value="SANGRIA" <?php if ($sub_clasificacion_categoria=='SANGRIA') echo 'selected'; ?>>SANGRIA</option>
                                                            <option value="SDLX" <?php if ($sub_clasificacion_categoria=='SDLX') echo 'selected'; ?>>SDLX</option>
                                                            <option value="SERVILLETAS" <?php if ($sub_clasificacion_categoria=='SERVILLETAS') echo 'selected'; ?>>SERVILLETAS</option>
                                                            <option value="SHAMPOO" <?php if ($sub_clasificacion_categoria=='SHAMPOO') echo 'selected'; ?>>SHAMPOO</option>
                                                            <option value="SNACKS" <?php if ($sub_clasificacion_categoria=='SNACKS') echo 'selected'; ?>>SNACKS</option>
                                                            <option value="STANDARD" <?php if ($sub_clasificacion_categoria=='STANDARD') echo 'selected'; ?>>STANDARD</option>
                                                            <option value="SUPLEMENTO" <?php if ($sub_clasificacion_categoria=='SUPLEMENTO') echo 'selected'; ?>>SUPLEMENTO</option>
                                                            <option value="TALCO" <?php if ($sub_clasificacion_categoria=='TALCO') echo 'selected'; ?>>TALCO</option>
                                                            <option value="TALCO PARA PIES" <?php if ($sub_clasificacion_categoria=='TALCO PARA PIES') echo 'selected'; ?>>TALCO PARA PIES</option>
                                                            <option value="TAMPONES" <?php if ($sub_clasificacion_categoria=='TAMPONES') echo 'selected'; ?>>TAMPONES</option>
                                                            <option value="TE" <?php if ($sub_clasificacion_categoria=='TE') echo 'selected'; ?>>TE</option>
                                                            <option value="TOALLAS HUMEDAS" <?php if ($sub_clasificacion_categoria=='TOALLAS HUMEDAS') echo 'selected'; ?>>TOALLAS HUMEDAS</option>
                                                            <option value="TOALLAS SANITARIAS" <?php if ($sub_clasificacion_categoria=='TOALLAS SANITARIAS') echo 'selected'; ?>>TOALLAS SANITARIAS</option>
                                                            <option value="UDLX" <?php if ($sub_clasificacion_categoria=='UDLX') echo 'selected'; ?>>UDLX</option>
                                                            <option value="VELAS" <?php if ($sub_clasificacion_categoria=='VELAS') echo 'selected'; ?>>VELAS</option>
                                                            <option value="VINAGRE" <?php if ($sub_clasificacion_categoria=='VINAGRE') echo 'selected'; ?>>VINAGRE</option>
                                                            <option value="VINO" <?php if ($sub_clasificacion_categoria=='VINO') echo 'selected'; ?>>VINO</option>
                                                            <option value="VINO ESPUMANTE" <?php if ($sub_clasificacion_categoria=='VINO ESPUMANTE') echo 'selected'; ?>>VINO ESPUMANTE</option>
                                                            <option value="OTROS" <?php if ($sub_clasificacion_categoria=='OTROS') echo 'selected'; ?>>OTROS</option>
                                                            <option value="ACEITE BEBE" <?php if ($sub_clasificacion_categoria=='ACEITE BEBE') echo 'selected'; ?>>ACEITE BEBE</option>
                                                            <option value="ACEITE LUBRICANTE" <?php if ($sub_clasificacion_categoria=='ACEITE LUBRICANTE') echo 'selected'; ?>>ACEITE LUBRICANTE</option>
                                                            <option value="ANTIBACTERIAL" <?php if ($sub_clasificacion_categoria=='ANTIBACTERIAL') echo 'selected'; ?>>ANTIBACTERIAL</option>
                                                            <option value="ANTIBACTERIAL JABONOSO" <?php if ($sub_clasificacion_categoria=='ANTIBACTERIAL JABONOSO') echo 'selected'; ?>>ANTIBACTERIAL JABONOSO</option>
                                                            <option value="AOVE" <?php if ($sub_clasificacion_categoria=='AOVE') echo 'selected'; ?>>AOVE</option>
                                                            <option value="APRESTO PLANCHADO" <?php if ($sub_clasificacion_categoria=='APRESTO PLANCHADO') echo 'selected'; ?>>APRESTO PLANCHADO</option>
                                                            <option value="ARCOS DENTALES" <?php if ($sub_clasificacion_categoria=='ARCOS DENTALES') echo 'selected'; ?>>ARCOS DENTALES</option>
                                                            <option value="BANO DE CREMA" <?php if ($sub_clasificacion_categoria=='BANO DE CREMA') echo 'selected'; ?>>BANO DE CREMA</option>
                                                            <option value="BATERIAS" <?php if ($sub_clasificacion_categoria=='BATERIAS') echo 'selected'; ?>>BATERIAS</option>
                                                            <option value="BOLSAS PRESERVADORAS COMIDA" <?php if ($sub_clasificacion_categoria=='BOLSAS PRESERVADORAS COMIDA') echo 'selected'; ?>>BOLSAS PRESERVADORAS COMIDA</option>
                                                            <option value="CEPILLO DENTAL" <?php if ($sub_clasificacion_categoria=='CEPILLO DENTAL') echo 'selected'; ?>>CEPILLO DENTAL</option>
                                                            <option value="CEPILLO INTERDENTAL" <?php if ($sub_clasificacion_categoria=='CEPILLO INTERDENTAL') echo 'selected'; ?>>CEPILLO INTERDENTAL</option>
                                                            <option value="CEPILLO LIMPIEZA" <?php if ($sub_clasificacion_categoria=='CEPILLO LIMPIEZA') echo 'selected'; ?>>CEPILLO LIMPIEZA</option>
                                                            <option value="CERA" <?php if ($sub_clasificacion_categoria=='CERA') echo 'selected'; ?>>CERA</option>
                                                            <option value="DESENGRASANTE" <?php if ($sub_clasificacion_categoria=='DESENGRASANTE') echo 'selected'; ?>>DESENGRASANTE</option>
                                                            <option value="DESINFECTANTE GRANITO" <?php if ($sub_clasificacion_categoria=='DESINFECTANTE GRANITO') echo 'selected'; ?>>DESINFECTANTE GRANITO</option>
                                                            <option value="DESMAQUILLANTE" <?php if ($sub_clasificacion_categoria=='DESMAQUILLANTE') echo 'selected'; ?>>DESMAQUILLANTE</option>
                                                            <option value="ENJUAGUE BUCAL" <?php if ($sub_clasificacion_categoria=='ENJUAGUE BUCAL') echo 'selected'; ?>>ENJUAGUE BUCAL</option>
                                                            <option value="ESPONJA LIMPIEZA" <?php if ($sub_clasificacion_categoria=='ESPONJA LIMPIEZA') echo 'selected'; ?>>ESPONJA LIMPIEZA</option>
                                                            <option value="ESPUMA PARA AFEITAR" <?php if ($sub_clasificacion_categoria=='ESPUMA PARA AFEITAR') echo 'selected'; ?>>ESPUMA PARA AFEITAR</option>
                                                            <option value="HILO DENTAL" <?php if ($sub_clasificacion_categoria=='HILO DENTAL') echo 'selected'; ?>>HILO DENTAL</option>
                                                            <option value="INSECTICIDA" <?php if ($sub_clasificacion_categoria=='INSECTICIDA') echo 'selected'; ?>>INSECTICIDA</option>
                                                            <option value="KEROSENE" <?php if ($sub_clasificacion_categoria=='KEROSENE') echo 'selected'; ?>>KEROSENE</option>
                                                            <option value="LIMPIA CARBURADOR" <?php if ($sub_clasificacion_categoria=='LIMPIA CARBURADOR') echo 'selected'; ?>>LIMPIA CARBURADOR</option>
                                                            <option value="LIMPIA PARABRISAS" <?php if ($sub_clasificacion_categoria=='LIMPIA PARABRISAS') echo 'selected'; ?>>LIMPIA PARABRISAS</option>
                                                            <option value="LIMPIA VIDRIOS" <?php if ($sub_clasificacion_categoria=='LIMPIA VIDRIOS') echo 'selected'; ?>>LIMPIA VIDRIOS</option>
                                                            <option value="LIMPIADOR DE ACERO INOXIDABLE" <?php if ($sub_clasificacion_categoria=='LIMPIADOR DE ACERO INOXIDABLE') echo 'selected'; ?>>LIMPIADOR DE ACERO INOXIDABLE</option>
                                                            <option value="LIMPIADOR DE COCINA" <?php if ($sub_clasificacion_categoria=='LIMPIADOR DE COCINA') echo 'selected'; ?>>LIMPIADOR DE COCINA</option>
                                                            <option value="LIMPIADOR DE HORNO" <?php if ($sub_clasificacion_categoria=='LIMPIADOR DE HORNO') echo 'selected'; ?>>LIMPIADOR DE HORNO</option>
                                                            <option value="LIMPIADOR MULTIUSOS" <?php if ($sub_clasificacion_categoria=='LIMPIADOR MULTIUSOS') echo 'selected'; ?>>LIMPIADOR MULTIUSOS</option>
                                                            <option value="LOCION CORPORAL" <?php if ($sub_clasificacion_categoria=='LOCION CORPORAL') echo 'selected'; ?>>LOCION CORPORAL</option>
                                                            <option value="MAYONESA" <?php if ($sub_clasificacion_categoria=='MAYONESA') echo 'selected'; ?>>MAYONESA</option>
                                                            <option value="NUTRIENTE PARA CUEROS" <?php if ($sub_clasificacion_categoria=='NUTRIENTE PARA CUEROS') echo 'selected'; ?>>NUTRIENTE PARA CUEROS</option>
                                                            <option value="PANO LIMPIEZA" <?php if ($sub_clasificacion_categoria=='PANO LIMPIEZA') echo 'selected'; ?>>PANO LIMPIEZA</option>
                                                            <option value="SILICONE PROTECTOR" <?php if ($sub_clasificacion_categoria=='SILICONE PROTECTOR') echo 'selected'; ?>>SILICONE PROTECTOR</option>
                                                            <option value="TOALLIN ABSORBENTE" <?php if ($sub_clasificacion_categoria=='TOALLIN ABSORBENTE') echo 'selected'; ?>>TOALLIN ABSORBENTE</option>
                                                            <option value="TOALLAS HUMEDAS" <?php if ($sub_clasificacion_categoria=='TOALLAS HUMEDAS') echo 'selected'; ?>>TOALLAS HUMEDAS</option>
                                                            <option value="VASELINA BEBE" <?php if ($sub_clasificacion_categoria=='VASELINA BEBE') echo 'selected'; ?>>VASELINA BEBE</option>
                                                            <option value="AFEITADORAS" <?php if ($sub_clasificacion_categoria=='AFEITADORAS') echo 'selected'; ?>>AFEITADORAS</option>
                                                            <option value="AGUA PARA BATERIAS" <?php if ($sub_clasificacion_categoria=='AGUA PARA BATERIAS') echo 'selected'; ?>>AGUA PARA BATERIAS</option>
                                                            <option value="ANTIDESLIZANTE DE CORREAS" <?php if ($sub_clasificacion_categoria=='ANTIDESLIZANTE DE CORREAS') echo 'selected'; ?>>ANTIDESLIZANTE DE CORREAS</option>
                                                            <option value="WEIMAN" <?php if ($sub_clasificacion_categoria=='WEIMAN') echo 'selected'; ?>>WEIMAN</option>
                                                            <option value="Enlatados" <?php if ($sub_clasificacion_categoria=='Enlatados') echo 'selected'; ?>>Enlatados</option>
                                                            <option value="Mermeladas" <?php if ($sub_clasificacion_categoria=='Mermeladas') echo 'selected'; ?>>Mermeladas</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="grado_alcoholico[]" style="text-align: right; width: 90%;" onkeypress="return isNumberKey(this, event)"
                                                        value="<?= mssql_result($productos, $i, "grado_alcoholico"); ?>">
                                                    </td>
                                                </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                                    <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint float-right">Procesar</button>
                                </div>
                            </form>
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
            <?php
        } else {
            header('Location: index.php');
        }
    ?>