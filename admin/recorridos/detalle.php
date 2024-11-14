<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";

$title = "Detalle del recorrido | " . APP_NAME;

$recorrido = Recorrido::getByIdAllInfo($_GET["id"]);
$recorrido->tramos = Recorrido::getAllTramos($_GET["id"]);
$recorrido->consultas = Recorrido::getConsultasByRecorrido($_GET["id"]);

/* $totalPasajeros = Paquete::getCuposVendidos($recorrido->idPaquete, $recorrido->fecha); */

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

            <!-- <button class="btn btn-primary btn-sm" type="button" onclick="printDetalle()"><i class="fa fa-print me-1"></i>Imprimir</button> -->
            <button class="btn btn-primary btn-sm" type="button" onclick="handlerRefreshRecorrido()"><i class="fa fa-sync-alt me-1"></i>Actualizar recorrido</button>
            <button class="btn btn-success btn-sm" type="button"><i class="<?= $menu->clientes->icon ?> me-1"></i>Vista del cliente</button>
        </div>
    </div>

    <div class="row" id="contentDetalleRecorrido">
        <!-- Detalle Paquete -->
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="d-block d-md-flex">
                        <div class="contentImagePrincipal text-center">
                            <img src="<?=DOMAIN_NAME?><?=$recorrido->paquete->imagen?>" alt="<?=$recorrido->paquete->titulo?>" width="auto" height="350">
                        </div>
                        <div class="py-3 px-4 w-100 d-flex flex-column justify-content-between" style="min-height: 350px;">
                            <div class="headerDetalle">
                                <h5 class="fs-4 m-0"><?=ucfirst($recorrido->paquete->titulo)?></h5>
                                <p class="fs-5 m-0 text-secondary"><?=ucfirst($recorrido->paquete->subtitulo)?></p>
                                <hr class="m-0 p-0">
                            </div>
                            <div class="bodyDetalle flex-fill mt-2">
                                <? if (Auth::isAdmin()): ?>
                                    <p class="fs-6 mb-1 badge bg-success"><i class="fa fa-cash-register me-1"></i>Ventas por $<?=Util::numberToPrice($recorrido->total, true)?></p>
                                <? endif; ?>

                                <p class="m-0"><i class="me-1 bi bi-globe-americas"></i><?=ucfirst($recorrido->paquete->provincia)?>, <?=$recorrido->paquete->destino?></p>
                                <p class="m-0"><i class="me-1 <?=$menu->clientes->icon?>"></i><?=$recorrido->pasajeros?> <?=$recorrido->pasajeros == 1 ? "pasajero" : "pasajeros"?></p>
                                <p class="m-0">
                                    <? if ($noches = $recorrido->paquete->noches == 0): ?>
                                        <i class="me-1 bi bi-sun"></i>1 día
                                    <? else: ?>
                                        <i class="me-1 bi bi-moon-fill"></i><?=$noches == 1 ? "1 noche" : $noches." noches"?>
                                    <? endif; ?>
                                </p>
                                <p class="m-0"><i class="me-2 fa fa-utensils"></i><?=$recorrido->paquete->pension?></p>
                            </div>
                            <div class="footerDetalle">
                                <p class="m-0 fs-5 text-dark">Fecha de salida</p>
                                <p class="m-0 display-5"><?=date("d/m/Y", strtotime($recorrido->fecha))?> <span class="fs-3"><?=date("H:i", strtotime($recorrido->paquete->horaSalida))?>hs</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pagebreak"></div>

        <!-- Recorrido -->
        <div class="col-12">
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
