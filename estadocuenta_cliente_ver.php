<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $cliente = $_POST['cliente'];
  $fechai = $_POST['fechai'].' 00:00:00';
  $fechaf = $_POST['fechaf'].' 23:59:59';
  $fechaiii = $_POST['fechai'];
  $fechafff = $_POST['fechaf'];
  $fechaii = normalize_date($_POST['fechai']);
  $fechaff = normalize_date($_POST['fechaf']);

  $estadocuenta = mssql_query("
    SELECT 
    cxc.CodClie,
    a.Descrip as rsocial,
    cxc.codvend as vendedor,
    a.clase as clase,
    a.Telef as telefono,
    cxc.FechaE as Emision,
    cxc.FechaV as Vencimiento,
    case 
    when TipoCxc = '10' then 'FAC'
    when TipoCxc = '50' then 'ADE'
    when TipoCxc = '41' then 'PAG'
    when TipoCxc = '31' then 'RET'
    when TipoCxc LIKE '8%' then 'RET'
    ELSE '' END Operacion, 
    NumeroD Numero,
    cxc.Document Descripcion,
    isnull(bnc.Descripcion,0) Banco, 
    isnull(trn.Documento,0) Nro_Documento,
    (CASE WHEN substring(cxc.TipoCxc,1,1) In ('1','2','6','7') Then cxc.Monto Else CONVERT(decimal(28,3),0) END) as Debitos,
    (CASE WHEN substring(cxc.TipoCxc,1,1) In ('3','4','5','8') Then cxc.Monto Else CONVERT(decimal(28,3),0) END) as Creditos,
    isnull(sum((CASE WHEN substring(cxc.TipoCxc,1,1) In ('1','2','6','7') Then cxc.Monto Else CONVERT(decimal(28,3),0) END) - (CASE WHEN substring(cxc.TipoCxc,1,1) In ('3','4','5','8') Then cxc.Monto Else CONVERT(decimal(28,3),0) END)) over (Order by NroUnico asc), 0) Saldo 
    from saacxc cxc 
    left join SBTRAN trn on cxc.NroUnico = trn.NroPpal and cxc.TipoCxc != 10
    left join SBBANC bnc on trn.CodBanc = bnc.CodBanc
    left join SACLIE a on cxc.CodClie=a.CodClie
    left join (select min(FechaE) Fecha, CodClie from SAACXC group by CodClie) Fe on cxc.CodClie = Fe.CodClie
    where cxc.CodClie='$cliente' and cxc.fechae BETWEEN FE.Fecha and '$fechaf'
    order by  NroUnico,CodClie asc");
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
<!--      <div class="row mb-2">
<div class="col-sm-6">
<h2 id="title_permisos">Ultima Activacion $estadocuenta</h2>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
<li class="breadcrumb-item active">Ultima Activacion $estadocuenta</li>
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
              window.location.href = "principal1.php?page=estadocuenta_cliente&mod=1";
            }
          </script>
          <h3 class="card-title">Estado de Cuenta del Cliente " <?php
          $rsocial= mssql_query("SELECT descrip from saclie where codclie ='$cliente'");
          for($i=0;$i<mssql_num_rows($rsocial);$i++){                                                    
           echo utf8_encode(mssql_result($rsocial,$i,"descrip"));
         } ?> " <br> Hasta el <?php echo $fechaff; ?></h3>&nbsp;&nbsp;&nbsp;
         <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
       </div>
       <div class="card-body">
        <?php
        $num = mssql_num_rows($estadocuenta); 
        ?>
        <table id="example7" class="table table-sm table-bordered table-striped">
          <thead style="background-color: #00137f;color: white;">
            <tr>
              <th>Emision</th>
              <th>Vencimiento</th>
              <th>Operacion</th>                    
              <th>Numero</th>
              <th>Descripcion</th>
              <th>Banco</th>
              <th>N Deposito</th>
              <th>Debitos</th>
              <th>Creditos</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($i = 0; $i < mssql_num_rows($estadocuenta); $i++) {
              $cliente = mssql_result($estadocuenta, $i, "codclie");
              $fecha=  mssql_query("SELECT top 1 min(fechae) as fecha from SAACXC where CodClie = '$cliente'");
              $fechae = date('Y-m-d', strtotime(mssql_result($fecha,$j, "fecha")));
              ?>
              <tr>
                <td ><?php echo date('d/m/Y', strtotime(mssql_result($estadocuenta, $i, 'emision'))); ?></td>
                <td ><?php echo date('d/m/Y', strtotime(mssql_result($estadocuenta, $i, 'vencimiento'))); ?></td>
                <td><?php echo mssql_result($estadocuenta, $i, "operacion"); ?></td>
                <td><?php echo mssql_result($estadocuenta, $i, "numero"); ?></td>
                <td><?php echo mssql_result($estadocuenta, $i, "descripcion"); ?></td>
                <td><?php echo mssql_result($estadocuenta, $i, "banco"); ?></td>
                <td><?php echo mssql_result($estadocuenta, $i, "nro_documento"); ?></td>
                <td><?php echo rdecimal2(mssql_result($estadocuenta, $i, "debitos")); ?></td>
                <td><?php echo rdecimal2(mssql_result($estadocuenta, $i, "creditos")); ?></td>
                <td><?php echo rdecimal2(mssql_result($estadocuenta, $i, "saldo")); ?></td>
              </tr>
            <?php }?>
          </tbody>
        </table>
        <div align="center"><a href="estadocuenta_cliente_excel.php?&fechai=<?php echo $fechae; ?>&fechaf=<?php echo $fechafff; ?>&cliente=<?php echo $cliente; ?>" ><img src="images/excel.jpeg" width="19" height="18" border="0" /> Exportar a Excel</a>&nbsp;&nbsp;</div>
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