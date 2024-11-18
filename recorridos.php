<?
require_once __DIR__ . "/config/init.php";

if (!isset($_GET["r"]) || !$_GET["r"]) HTTPController::get401(false);
$idRecorrido = base64_decode($_GET["r"]);

$recorrido = Recorrido::getByIdAllInfo($idRecorrido);


$title = ucfirst($recorrido->paquete->titulo) . " " . date("d/m/Y", strtotime($recorrido->fecha));
?>
<!DOCTYPE html>
<html lang="es">
<? require_once PATH_SERVER . "/helpers/sections/head.php" ?>

<body>
    <style>
        .contentDataPaquete {
            margin: 0;
        }

        @media screen and (min-width: 768px) {
            /* .contentDataPaquete {
                position: fixed;
                z-index: 99;
                top: 30vh;
                left: 0;
                width: 100%;
                height: auto;
            } */
            .contentDataPaquete {
                margin-top: -200px;
            }
        }
    </style>

    <main class="">
        <section class="sectionPortada shadow" style="height: 50vh;">
            <div class="d-none d-md-block h-100">
                <img src="<?= DOMAIN_NAME ?><?= $recorrido->paquete->banner ?>" alt="Banner de la excursión" height="100%" width="100%">
            </div>
            <div class="d-md-none h-100">
                <img src="<?= DOMAIN_NAME ?><?= $recorrido->paquete->imagen ?>" alt="Imagen principal de la excursión" height="100%" width="100%">
            </div>
        </section>
        <section class="contentDataPaquete row">
            <div class="card m-0 col-md-6 col-md-4 mx-auto">
                <div class="card-body py-3 px-4">

                    <div class="headerDetalle">
                        <h1 class="fs-1 m-0"><?= ucfirst($recorrido->paquete->titulo) ?></h1>
                        <h2 class="fs-2 m-0 text-secondary"><?= ucfirst($recorrido->paquete->subtitulo) ?></h2>
                        <hr class="m-0 p-0">
                    </div>

                    <div class="bodyDetalle mt-3">
                        <p class="fs-5 m-0"><i class="me-1 bi bi-globe-americas"></i><?= ucfirst($recorrido->paquete->provincia) ?>, <?= $recorrido->paquete->destino ?></p>
                        <p class="fs-5 m-0"><i class="me-1 <?= $menu->clientes->icon ?>"></i><?= $recorrido->pasajeros ?> <?= $recorrido->pasajeros == 1 ? "pasajero" : "pasajeros" ?></p>
                        <? if ($recorrido->totalAlojamientoConsulta > 0): ?>
                            <p class="fs-5 m-0"><i class="me-1 <?= $menu->alojamientos->icon ?>"></i><?= $recorrido->totalAlojamientoConsulta ?> <?= $recorrido->totalAlojamientoConsulta == 1 ? "parada" : "paradas" ?></p>
                        <? endif; ?>
                        <p class="fs-5 m-0">
                            <? if ($noches = $recorrido->paquete->noches == 0): ?>
                                <i class="me-1 bi bi-sun"></i>1 día
                            <? else: ?>
                                <i class="me-1 bi bi-moon-fill"></i><?= $noches == 1 ? "1 noche" : $noches . " noches" ?>
                            <? endif; ?>
                        </p>
                        <p class="fs-5 m-0"><i class="me-2 fa fa-utensils"></i><?= $recorrido->paquete->pension ?></p>
                    </div>

                    <div class="footerDetalle mt-4">
                        <p class="m-0 fs-5 text-dark">Fecha de salida</p>
                        <p class="m-0 display-5"><?= date("d/m/Y", strtotime($recorrido->fecha)) ?> <span class="fs-3"><?= date("H:i", strtotime($recorrido->paquete->horaSalida)) ?>hs</span></p>
                    </div>

                    <div class="buttonDetalle mt-2">
                        <button type="button" class="btn btn-primary btn-sm"><i class="<?= $menu->usuarios->icon ?> me-1"></i>Info del guía</button>
                        <button type="button" class="btn btn-success btn-sm"><i class="<?= $menu->consultas->icon ?> me-1"></i>Mensajes</button>
                    </div>
                </div>
            </div>

            <div class="col-12"></div>

            <div class="card dashboard m-0 col-md-6 col-md-4 mx-auto my-3">
                <div class="card-body py-3">
                    <h3 class="mb-3"><i class="me-1 <?=$menu->recorridos->icon?>"></i>Tramos del vehículo</h3>

                    <div class="activity">

                        <div class="activity-item d-flex">
                            <div class="activite-label">32 min</div>
                            <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                            <div class="activity-content">
                                Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">56 min</div>
                            <i class="bi bi-circle-fill activity-badge text-danger align-self-start"></i>
                            <div class="activity-content">
                                Voluptatem blanditiis blanditiis eveniet
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">2 hrs</div>
                            <i class="bi bi-circle-fill activity-badge text-primary align-self-start"></i>
                            <div class="activity-content">
                                Voluptates corrupti molestias voluptatem
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">1 day</div>
                            <i class="bi bi-circle-fill activity-badge text-info align-self-start"></i>
                            <div class="activity-content">
                                Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">2 days</div>
                            <i class="bi bi-circle-fill activity-badge text-warning align-self-start"></i>
                            <div class="activity-content">
                                Est sit eum reiciendis exercitationem
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">4 weeks</div>
                            <i class="bi bi-circle-fill activity-badge text-muted align-self-start"></i>
                            <div class="activity-content">
                                Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>

    <script>
        document.addEventListener("DOMContentLoaded", e => {

        })
    </script>

</body>

</html>