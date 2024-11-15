<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";

$title = "Detalle del recorrido | " . APP_NAME;

$recorrido = Recorrido::getByIdAllInfo($_GET["id"]);
$recorrido->tramos = Recorrido::getAllTramos($_GET["id"]);


ob_start();
?>
<section class="section">
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
                            <img src="<?=DOMAIN_NAME?><?=$recorrido->paquete->imagen?>" alt="<?=$recorrido->paquete->titulo?>" width="auto" height="380">
                        </div>
                        <div class="py-3 px-4 w-100 d-flex flex-column justify-content-between" style="min-height: 350px;">
                            <div class="headerDetalle">
                                <h5 class="fs-3 m-0"><?=ucfirst($recorrido->paquete->titulo)?></h5>
                                <p class="fs-5 m-0 text-secondary"><?=ucfirst($recorrido->paquete->subtitulo)?></p>
                                <hr class="m-0 p-0">
                            </div>
                            <div class="bodyDetalle flex-fill mt-2">
                                <p class="m-0 fs-6 badge bg-primary"><i class="bi bi-person-workspace me-1"></i>Guía: <?=ucfirst($recorrido->usuario->nombre)?> <?=ucfirst($recorrido->usuario->apellido)?></p>
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
        <!-- Solo mostrar si hay alojamientos de clientes para pasar a buscar -->
        <!-- TODO: 3° Utilizar la tabla recorrido_tramo_pasajeros ($recorrido->tramo->pasajeros) -->

        <? if ($recorrido->totalAlojamientoConsulta > 0): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="table-responsive">
                            <table class="table table-bordered my-2">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-center py-2 h4 bg-light">Paradas del recorrido</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th style="width: 12%;">Marcar parada</th>
                                        <th>Parada</th>
                                        <th>Pasajeros</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($recorrido->tramos as $tramo): ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <i class="fa fa-sort iconSortable cursor-move" data-bs-target="tooltip" title="Mover"></i>
                                            </td>
                                            <td class="text-center align-middle" id="checkParada-<?=$tramo->idRecorridoTramo?>">
                                                <? if ($tramo->estado == 'P'): ?>
                                                    <input type="checkbox" onclick="handlerMarcarParada(this, $tramo->idRecorridoTramo)">
                                                <? else: ?>
                                                    <span class="badge bg-success">Marcado</span>
                                                <? endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?
                                                $textParada = "";
                                                if($tramo->tipo === "O") $textParada = "Punto de Partida";
                                                if($tramo->tipo === "D") $textParada = "<i class='fa fa-bus me-1'></i>Inicio de la excursión";
                                                if($tramo->tipo === "P") $textParada = ucfirst($tramo->alojamiento->nombre);
                                                ?>
                                                <p class="m-0"><?=$textParada?></p>
                                                <? if ($tramo->tipo === "P"): ?>
                                                    <p class="m-0 text-secondary"><i class="bi bi-geo-alt me-1"></i><?=$tramo->alojamiento->direccion?></p>
                                                <? endif; ?>
                                                <? if ($tramo->tipo === "O"): ?>
                                                    <p class="m-0 text-secondary"><i class="fa fa-clock me-1"></i>Salida a las <?=date("H:i", strtotime($recorrido->paquete->horaSalida))?>hs</p>
                                                <? endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?=$tramo->tipo === "D" ? "" : $tramo->pax . ($tramo->pax == 1 ? " pasajero" : " pasajeros")?>
                                            </td>
                                        </tr>
                                    <? endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="activity d-none">
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
        <? endif; ?>
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

    function handlerRefreshRecorrido(){
        fetch("<?=DOMAIN_ADMIN?>process.php?action=recorrido_update&idRecorrido=<?=$_GET["id"]?>")
        .then(res => res.json())
        .then(({status, title, message, type}) => {
            Swal.fire(title, message, type).then(result => {
                if(status === "OK") Location.reload()
            })
        })
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
