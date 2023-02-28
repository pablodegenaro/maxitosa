<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-6">
                    <h2 class="ml-3">Generador de CSV Fuerza Movil</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="card card-saint"  id="tabla">
                        <div class="card-header">
                            <h3 class="card-title">Generar CSV Fuerza Movil</h3>
                        </div>
                        <div class="card-body" style="width:auto;">
                            <div class="form-check-inline">
                                <form action="txt_generar_fuerzamovil_ver.php" method="POST" target="_blank">
                                    <div class="form-check form-check-inline">
                                        <select class="form-control custom-select" name="fmovil" id="fmovil" style="width: 100%;" required>
                                            <option value="">¿Que desea Exportar?</option>
                                            <option value="1">Empresa</option>
                                            <option value="2">Sucursales<s/option>
                                                <option value="3">Clientes</option>
                                                <option value="4">Clientes por Empresas</option>
                                                <option value="5">Clientes por Vendedor</option>
                                                <option value="6">Tipo de Lista de Precios</option> 
                                                <option value="7">Lista de Precios Clientes</option>
                                                <option value="8">Articulos</option>
                                                <option value="9">Articulos por Empresa</option>
                                                <option value="10">Unidad</option>
                                                <option value="11">Sector</option>
                                                <option value="12">Plan Visita</option>
                                                <option value="13">Factor / Tasa</option>
                                                <option value="14">Limite de Credito</option>
                                                <option value="15">Monedas</option>
                                                <option value="16">Tipos de Documentos CXC</option>
                                                <option value="17">Cantidad de Empaques por Caja y Unidad de Venta</option>
                                                <option value="18">Unidad de Venta</option>
                                                <option value="19">Precios Articulos</option>
                                                <option value="20">Facturas Pendientes CXC</option>
                                                <option value="21">Tipos de Pago</option>
                                                <option value="22">Tipo Pago Cliente</option>
                                                <option value="23">Bancos</option>
                                            </select>
                                        </div> <br><br>
                                        <button type="submit" class="btn btn-primary btn-sm">Generar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php require_once("footer.php");?>
    <script type="text/javascript" src="txt_generar.js"></script>
