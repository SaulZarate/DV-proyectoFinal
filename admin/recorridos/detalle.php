<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";

$title = "Detalle del recorrido | " . APP_NAME;

$recorrido = Recorrido::getByIdAllInfo($_GET["id"]);
$recorrido->consultas = Recorrido::getConsultasByRecorrido($_GET["id"]);
ob_start();
?>


<section class="section">
    <div class="card">
        <div class="card-body overflow-auto" style="height: 400px;"><?= Util::printVar($recorrido); ?></div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title pb-0 m-0"><i class="<?= $menu->{$section}->icon ?> me-1"></i>Detalle del recorrido</h5>
            <p class="text-secondary pb-0 mb-2">Esta vista contiene información detallada sobre el recorrido</p>

            <button class="btn btn-primary btn-sm" type="button" onclick="printDetalle()"><i class="fa fa-print me-1"></i>Imprimir</button>
            <button class="btn btn-success btn-sm" type="button"><i class="<?= $menu->clientes->icon ?> me-1"></i>Vista del cliente</button>
        </div>
    </div>

    <div class="row" id="contentDetalleRecorrido">
        <!-- Detalle Paquete -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Excursión</h5>
                </div>
            </div>
        </div>

        <!-- Detalle guía -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="me-1 <?= $menu->usuarios->icon ?>"></i>Guía <?= ucfirst($recorrido->usuario->nombre) ?> <?= ucfirst($recorrido->usuario->apellido) ?></h5>
                </div>
            </div>
        </div>

        <!-- Recorrido -->
        <div class="col-md-6">
            <div class="card dashboard">
                <div class="card-body">
                    <h5 class="card-title">Recorrido</h5>

                    <div class="activity">
                        <div class="activity-item d-flex">
                            <div class="activite-label d-flex align-items-center ">Inicio</div>
                            <i class="bi bi-circle-fill activity-badge text-success align-self-center"></i>
                            <div class="activity-content py-1 d-flex justify-content-center flex-column">
                                <p class="m-0 p-0 fs-4">Punto de salida</p>
                                <span class="text-secondary"><i class="me-1 <?= $menu->clientes->icon ?>"></i>5 pasajeros</span>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label d-flex align-items-center ">1° Parada</div>
                            <i class="bi bi-circle-fill activity-badge text-success align-self-center"></i>
                            <div class="activity-content py-1 d-flex justify-content-center flex-column">
                                <p class="m-0 p-0 fs-4">Alojamiento....</p>
                                <span class="text-secondary"><i class="me-1 <?= $menu->clientes->icon ?>"></i>2 pasajeros</span>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label d-flex align-items-center ">2° Parada</div>
                            <i class="bi bi-circle-fill activity-badge text-warning align-self-center"></i>
                            <div class="activity-content py-1 d-flex justify-content-center flex-column">
                                <p class="m-0 p-0 fs-4">Alojamiento....</p>
                                <span class="text-secondary"><i class="me-1 <?= $menu->clientes->icon ?>"></i>1 pasajeros</span>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label d-flex align-items-center ">3° Parada</div>
                            <i class="bi bi-circle-fill activity-badge text-warning align-self-center"></i>
                            <div class="activity-content py-1 d-flex justify-content-center flex-column">
                                <p class="m-0 p-0 fs-4">Alojamiento....</p>
                                <span class="text-secondary"><i class="me-1 <?= $menu->clientes->icon ?>"></i>2 pasajeros</span>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label d-flex align-items-center ">Fin</div>
                            <i class="bi bi-circle-fill activity-badge text-warning align-self-center"></i>
                            <div class="activity-content py-1 d-flex justify-content-center flex-column">
                                <p class="m-0 p-0 fs-4">Punto de llegada</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



</section>

<script>
    document.addEventListener("DOMContentLoaded", e => {

    })

    function printDetalle(){
        document.getElementById("contentDetalleRecorrido").classList.add("fullScreen")
        window.print()
        document.getElementById("contentDetalleRecorrido").classList.remove("fullScreen")
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
