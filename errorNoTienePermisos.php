<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Error</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning">Error! </h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Usted no tiene permisos.</h3>

                <p>
                    No posee permisos para acceder a este módulo. !
                    Intente <a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">volver al Inicio</a> ó seleccione otra opción del menú.
                </p>

            </div>
        </div>
    </section>
</div>
<?php include "footer.php"; ?>

