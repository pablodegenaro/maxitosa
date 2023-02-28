<div class="content-header">
  <div class="container">
    <h1>Menu Estadisticas</h1>
  </div>
</div>
<div class="content">
  <div class="container">
    <div class="accordion" id="accordionExample">

      <div class="card">
        <div class="card-header" id="headingone">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseone" aria-expanded="false" aria-controls="collapseone">
              Activaciones
            </button>
          </h5>
        </div>
        <div id="collapseone" class="collapse" aria-labelledby="headingone" data-parent="#accordionExample">
          <div class="card-body">
            <!-- <p><a href="principal.php?page=estadisticas_cnestle&mod=1">Ver Clientes con Codificaci&oacute;n Nestle</a></p> -->
            <p><a href="principal.php?page=ultima_activacion_clientes&mod=1">Ultima Activacion de Clientes</a></p>
            <p><a href="principal.php?page=clientes_no_activados_xfecha&mod=1">Clientes No Activados por Rango de Fecha</a></p>
            <!-- <p><a href="principal.php?page=estadisticas_sinfactura&mod=1">Clientes Sin Realizar Transacciones por Fecha </a></p> 
            <p><a href="principal.php?page=estadisticas_clientes&mod=1">Clientes Nuevos por Semana</a></p>
            <p><a href="principal.php?page=estadisticas_bloqueados&mod=1">Clientes Bloqueados</a></p>-->
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingTwo">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Administracion
            </button>
          </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=libro_compras&mod=1">Libros de Compras</a></p>
            <p><a href="principal.php?page=libro_ventas&mod=1">Libros de Ventas</a></p>
            <p><a href="principal.php?page=cambia_factor&mod=1">Factor Cambiario</a></p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingThree">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Almacen
            </button>
          </h5>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=indicadores_despacho&mod=1">Indicadores de Gesti&oacute;n de Despachos</a></p>
            <p><a href="principal.php?page=estadisticas_fact_sin_des&mod=1">Facturas sin Despachar</a></p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingFour">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              Compras
            </button>
          </h5>
        </div>
        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=sellin&mod=1">Sell In Compras</a></p>
            <p><a href="principal.php?page=reporte_compras&mod=1">Reporte de compras</a></p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingFive">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
              Cuentas por Cobrar
            </button>
          </h5>
        </div>
        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=resumen_cobranza&mod=1">Resumen de Cobranzas</a></p>            
            <p><a href="principal.php?page=cobranza_edv_cierre_caja&mod=1">Cierre de Caja</a></p>

          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingSix">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
              Geolocalizacion
            </button>
          </h5>
        </div>
        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=geolocalizacion&mod=1">Geolocalizaci&oacute;n de Clientes</a></p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingSeven">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
              Inventario
            </button>
          </h5>
        </div>
        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=costos_inv&mod=1">Costos e Inventario</a></p>
            <p><a href="principal.php?page=inventario&mod=1">Inventario Global</a></p>
          <!--   <p><a href="principal.php?page=estadisticas_presu&mod=1">Sku Pendientes por Facturar</a></p> -->
            <!-- <p><a href="principal.php?page=indicador_sku&mod=1">Indicador de dias de Inventario por SKU</a></p> -->
            <!-- <p><a href="principal.php?page=estadisticas_inv_paq&mod=1">Inventario en Paquetes</a></p> -->
            <p><a href="principal.php?page=disponible_almacen&mod=1">Disponible en Almacen</a></p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingEight">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
              Proveedores
            </button>
          </h5>
        </div>
        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=analisis_vencimiento_proveedores&mod=1">Analisis de Vencimiento</a></p>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingNine">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
              Ventas
            </button>
          </h5>
        </div>
        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordionExample">
          <div class="card-body">
            <p><a href="principal.php?page=maestro_clientes&mod=1">Maestro de Clientes por Vendedor</a></p>
            <!-- <p><a href="principal.php?page=motivonoventa&mod=1">Motivo de no Venta</a></p> -->
            <p><a href="principal.php?page=estadisticas&mod=1">Efectividad EDV (Efectividad de Ventas x Vendedor)</a></p>
            <!-- <p><a href="principal.php?page=estadisticas_r1&mod=1">Ventas en Kg de EDV (x Categoria)</a></p> -->
         <!--    <p><a href="principal.php?page=estadisticas_ventas_kg_5&mod=1">Ventas x Kg x Categoria de Principales Clientes x Ruta NESTLE</a></p> -->
            <p><a href="kpi.php">KPI (Key Performance Indicator)</a></p>    
            <p><a href="tabladinamica_facturas.php">Tabla Dinamica Facturas</a></p>
            <!-- <p><a href="tabladinamica_nota.php">Tabla Dinamica Notas de Entrega</a></p> -->
            <!-- <p><a href="principal.php?page=estadisticas_devol&mod=1">Devoluciones</a></p>
            <p><a href="principal.php?page=devoluciones_sm&mod=1">Devoluciones sin Motivo</a></p> -->
            <p><a href="principal.php?page=estadisticas_prod&mod=1">Ventas por Instancias de Productos </a></p>
            <!-- <p><a href="principal.php?page=comisiones&mod=1">Resumen de Comisiones de Ventas y Cobros </a></p> -->
            <p><a href="principal.php?page=relacion_pedidos&mod=1">Relacion de Pedidos sin Facturar </a></p>
          </div>
        </div>
      </div>
      <br>
    </div>
  </div>
</div>