<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {

  $fechai = $_POST['fechai'];
  $fechaf = $_POST['fechaf'];
  $sucursal = $_POST['sucursal'];
  $convend = $_POST['edv'];

  switch (true) {
    # =================================================
    # === UN TIPO DE SUCURSAL,  VENDEDOR ==== 
    # =================================================
    case ($sucursal!="-" && $convend!="-"):
    $noactivos = mssql_query("SELECT cli.codclie as codclie, cli.descrip as descrip, cli.id3 as id3, CLI.activo as activo, *
      from SACLIE as CLI inner join saclie_99 as CLI01 ON CLI.codclie = CLI01.codclie where CLI.codclie not in
      (SELECT distinct(SAFACT.CodClie) AS CODCLIE FROM SAFACT WHERE SAFACT.CodVend = '$convend' AND TipoFac in ('A') AND SAFACT.CodSucu = '$sucursal' 
        AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_99 ON SACLIE.CodClie = SACLIE_99.CodClie
          WHERE (ACTIVO = 1 or activo = 5 ) AND (SACLIE.CodVend = '$convend' or Ruta_Alternativa = '$convend' OR Ruta_Alternativa_2 = '$convend'))
        AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai' and '$fechaf'
        AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT)))
      and (ACTIVO = 1 or activo = 5 ) and (CLI.CodVend = '$convend' or CLI01.Ruta_Alternativa = '$convend' OR CLI01.Ruta_Alternativa_2 = '$convend') order by cli.Descrip");
    break;

    # =============================================================
    # === UN TIPO DE SUCURSAL , TODOS LOS VENDEDORES ==== 
    # =============================================================
    case ($sucursal!="-" && $convend=="-"):
    $noactivos = mssql_query("SELECT cli.codclie as codclie, cli.descrip as descrip, cli.id3 as id3, CLI.activo as activo, *
      from SACLIE as CLI inner join saclie_99 as CLI01 ON CLI.codclie = CLI01.codclie where CLI.codclie not in
      (SELECT distinct(SAFACT.CodClie) AS CODCLIE FROM SAFACT WHERE  TipoFac in ('A') AND SAFACT.CodSucu = '$sucursal' 
        AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_99 ON SACLIE.CodClie = SACLIE_99.CodClie
          WHERE (ACTIVO = 1 or activo = 5 ) )
        AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai' and '$fechaf'
        AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT)))
      and (ACTIVO = 1 or activo = 5 )  order by cli.Descrip");
    break;
    
    # =============================================================
    # ===  VENDEDOR, TODAS LAS SUCURSALES ==== 
    # =============================================================
    case ($sucursal=="-" && $convend!="-"):
    $noactivos = mssql_query("SELECT cli.codclie as codclie, cli.descrip as descrip, cli.id3 as id3, CLI.activo as activo, *
      from SACLIE as CLI inner join saclie_99 as CLI01 ON CLI.codclie = CLI01.codclie where CLI.codclie not in
      (SELECT distinct(SAFACT.CodClie) AS CODCLIE FROM SAFACT WHERE SAFACT.CodVend = '$convend' AND TipoFac in ('A') AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_99 ON SACLIE.CodClie = SACLIE_99.CodClie
        WHERE (ACTIVO = 1 or activo = 5 ) AND (SACLIE.CodVend = '$convend' or Ruta_Alternativa = '$convend' OR Ruta_Alternativa_2 = '$convend'))
      AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai' and '$fechaf'
      AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT)))
      and (ACTIVO = 1 or activo = 5 ) and (CLI.CodVend = '$convend' or CLI01.Ruta_Alternativa = '$convend' OR CLI01.Ruta_Alternativa_2 = '$convend') order by cli.Descrip");
    break;

    # ===============================================================
    # === UN TIPO DE VENDEDOR, TODAS LAS SUCURSALES ==== 
    # ===============================================================
    case ($sucursal=="-"  && $convend!="-"):
    $noactivos = mssql_query("SELECT cli.codclie as codclie, cli.descrip as descrip, cli.id3 as id3, CLI.activo as activo, *
      from SACLIE as CLI inner join saclie_99 as CLI01 ON CLI.codclie = CLI01.codclie where CLI.codclie not in
      (SELECT distinct(SAFACT.CodClie) AS CODCLIE FROM SAFACT WHERE SAFACT.CodVend = '$convend' AND TipoFac in ('A') 
        AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_99 ON SACLIE.CodClie = SACLIE_99.CodClie
          WHERE (ACTIVO = 1 or activo = 5 ) AND (SACLIE.CodVend = '$convend' or Ruta_Alternativa = '$convend' OR Ruta_Alternativa_2 = '$convend'))
        AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai' and '$fechaf'
        AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT)))
      and (ACTIVO = 1 or activo = 5 ) and (CLI.CodVend = '$convend' or CLI01.Ruta_Alternativa = '$convend' OR CLI01.Ruta_Alternativa_2 = '$convend') order by cli.Descrip");
    break;

    # =====================================================
    # === TODAS LAS SUCURSALES,  VENDEDORES ==== 
    # =====================================================
    default:
    $noactivos = mssql_query("SELECT cli.codclie as codclie, cli.descrip as descrip, cli.id3 as id3, CLI.activo as activo, *
      from SACLIE as CLI inner join saclie_99 as CLI01 ON CLI.codclie = CLI01.codclie where CLI.codclie not in
      (SELECT distinct(SAFACT.CodClie) AS CODCLIE FROM SAFACT WHERE  TipoFac in ('A') AND SAFACT.CodClie IN (SELECT SACLIE.CodClie FROM SACLIE INNER JOIN SACLIE_99 ON SACLIE.CodClie = SACLIE_99.CodClie
        WHERE (ACTIVO = 1 or activo = 5 ) )
      AND DATEADD(dd, 0, DATEDIFF(dd, 0, SAFACT.FechaE)) between '$fechai' and '$fechaf'
      AND NumeroD NOT IN (SELECT X.NumeroD FROM SAFACT AS X WHERE X.TipoFac in ('A') AND x.NumeroR is not NULL AND cast(X.Monto as BIGINT) = cast((select Z.Monto from SAFACT AS Z where Z.NumeroD = x.NumeroR and Z.TipoFac in ('B'))as BIGINT)))
      and (ACTIVO = 1 or activo = 5 )  order by cli.Descrip");
  }
  ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
<!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion Clientes</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion Clientes</li>
</ol>
</div>
</div> -->
</div>
</section>
<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card card-saint">
        <div class="card-header">
          <script type="text/javascript">
            function regresa(){
              window.location.href = "principal1.php?page=clientes_no_activados_xfecha&mod=1";
            }
          </script>
          <h3 class="card-title">Clientes no Activados por Fecha</h3>&nbsp;&nbsp;&nbsp;
          <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
        </div>
        <div class="card-body">
          <?php 
          $maestro = mssql_query("SELECT codclie from saclie where codvend = '$convend' and (ACTIVO = 1 or activo = 5 )"); 
          $num = mssql_num_rows($noactivos); 
          ?>
          <table id="example1" class="table table-sm table-bordered table-striped">
            <thead style="background-color: #00137f;color: white;">
              <tr>
                <th>Codigo Cliente</th>
                <th>Razon Social</th>
                <th>Rif</th>
                <th>Direccion</th>
                <th>Estatus</th>
                <th>Dia de Visita</th>
              </tr>
            </thead>
            <tbody>
              <?php for ($i = 0; $i < mssql_num_rows($noactivos); $i++) {
                ?>
                <tr>
                  <td><?php echo mssql_result($noactivos, $i, "CodClie"); ?></td>
                  <td><?php echo utf8_encode(mssql_result($noactivos, $i, "Descrip")); ?></td>
                  <td><?php echo mssql_result($noactivos, $i, "id3"); ?></td>
                  <td><?php echo utf8_encode(mssql_result($noactivos, $i, "Direc1")); ?></td>
                  <td><?php if (mssql_result($noactivos,$i,"activo") == 1){ echo "ACTIVO"; }else{ echo "INACTIVO: ".utf8_encode(mssql_result($noactivos,$i,"observa")); } ?></td>
                  <td><?php echo mssql_result($noactivos, $i, "dia_visita"); ?>
                </td>
              </tr>
            <?php }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</section>

</div>
<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<?php
} else {
  header('Location: index.php');
}
?>