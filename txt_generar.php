<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-6">
                    <h2 class="ml-3">Generar txt</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="card card-primary"  id="tabla">
                        <div class="card-header">
                            <h3 class="card-title">Generar TXT RET de Compras</h3><!-- overfalow:scroll; -->
                        </div>
                        <div class="card-body" style="width:auto;">
                            <div class="form-check-inline">
                                <form action="txt_generar_ret.php" method="POST" target="_blank">
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4">Fecha Inicial</label>
                                        <input type="date" class="form-control col-sm-10"  id="fechai" name="fechai" required>
                                    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-check form-check-inline">
                                        <label for="vutil" class="col-form-label col-sm-4">Fecha Final</label>
                                        <input type="date" class="form-control col-sm-10"  id="fechaf" name="fechaf" required>
                                    </div> <br><br>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Generar TXT
                                    </button>
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
