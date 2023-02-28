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
                    <li class="breadcrumb-item active">Ultima Activacion</li>
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
                if (document.getElementById("fecha").value != "" ){
                           /* document.forms["registro_usuarios"].submit();*/
                }else{
                  alert("Debe Rellenar Todos Los Campos");
              }
          }
          function regresa(){
            window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
        }
    </script>
    <div class="card-header">
        <h3 class="card-title">Ultima Activacion de Clientes</h3>
    </div>
    <form class="form-horizontal" action="principal1.php?page=ultima_activacion_clientes_ver&mod=1" method="post" id="" name="">
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label>Hasta la Fecha</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" name="fecha" id="fecha" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" name="Submit"   onclick="guarda()"  class="btn btn-saint">Procesar</button>
            <button type="button" onclick="regresa()" class="btn btn-outline-saint float-right">Regresar</button>
        </div>
    </form>
</div>
</div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
