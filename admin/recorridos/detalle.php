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
                                        <? if ($recorrido->totalAlojamientoConsulta > 1): ?>
                                            <th style="width: 5%;"></th>
                                        <? endif; ?>
                                        <th style="width: 12%;">Marcar parada</th>
                                        <th <?=$recorrido->totalAlojamientoConsulta == 1 ? "colspan='2'" : ""?>>Parada</th>
                                        <th>Pasajeros</th>
                                    </tr>
                                </thead>
                                <tbody id="contentTramoItems">
                                    <? foreach ($recorrido->tramos as $tramo): ?>
                                        <tr data-id="<?=$tramo->idRecorridoTramo?>" class="<?=in_array($tramo->tipo, ["O", "D"]) ? "trSortable" : ""?>">
                                            <? if ($recorrido->totalAlojamientoConsulta > 1): ?>
                                                <td class="text-center align-middle <?=$tramo->tipo != "P" ? 'bg-light' : ''?>">
                                                    <? if ($tramo->tipo == "P"): ?>
                                                        <i class="fa fa-sort iconSortable cursor-move" data-bs-target="tooltip" title="Mover"></i>
                                                    <? endif; ?>
                                                </td>
                                            <? endif; ?>
                                            <td class="text-center align-middle <?=$tramo->tipo == "D" ? 'bg-light' : ''?>" id="checkParada-<?=$tramo->idRecorridoTramo?>">

                                                <? if ($tramo->tipo !== "D" && $tramo->estado == 'P' && $recorrido->fecha != date("Y-m-d")): ?>
                                                    <span class="badge bg-danger"><i class="fa fa-times-circle me-1"></i>Se habilitará en la fecha de salida</span>
                                                <? endif; ?>

                                                <? if ($tramo->tipo !== "D" && $tramo->estado == 'P' && $recorrido->fecha == date("Y-m-d")): ?>
                                                    <input type="checkbox" onclick="handlerMarcarParada(this, <?=$tramo->idRecorridoTramo?>)">
                                                <? endif; ?>

                                                <? if ($tramo->estado === "M"): ?>
                                                    <span class="badge bg-success">Marcado</span>
                                                <? endif; ?>
                                            </td>
                                            <td class="align-middle" <?=$recorrido->totalAlojamientoConsulta == 1 ? "colspan='2'" : ""?>>
                                                <?
                                                $textParada = "";
                                                if($tramo->tipo === "O") $textParada = "<i class='fa fa-bus me-1'></i>Punto de Partida";
                                                if($tramo->tipo === "D") $textParada = "<i class='fa fa-bus me-1'></i>Inicio de la excursión";
                                                if($tramo->tipo === "P") $textParada = "<i class='{$menu->alojamientos->icon} me-1'></i>".ucfirst($tramo->alojamiento->nombre);
                                                ?>
                                                <p class="m-0 fs-5"><?=$textParada?></p>

                                                <? if ($tramo->tipo === "P"): ?>
                                                    <a class="text-success" href="https://www.google.com/maps/place/<?=$tramo->alojamiento->latitud?>,<?=$tramo->alojamiento->longitud?>/@<?=$tramo->alojamiento->latitud?>,<?=$tramo->alojamiento->longitud?>,14z" target="_blank">
                                                        <i class="bi bi-geo-alt me-1"></i><?=$tramo->alojamiento->direccion?>
                                                    </a>
                                                <? endif; ?>
                                                <? if ($tramo->tipo === "O"): ?>
                                                    <p class="m-0 text-secondary"><i class="fa fa-clock me-1"></i>Salida a las <?=date("H:i", strtotime($recorrido->paquete->horaSalida))?>hs</p>
                                                <? endif; ?>
                                            </td>
                                            <td class="align-middle <?=$tramo->tipo === "D" ? "bg-light" : ""?>">
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
    let lastOrderGalery = []

    document.addEventListener("DOMContentLoaded", e => {
        if(document.getElementById("contentTramoItems")) handlerTramosSortable()
    })

    function printDetalle(){
        document.getElementById("contentDetalleRecorrido").classList.add("fullScreen")
        window.print()
        document.getElementById("contentDetalleRecorrido").classList.remove("fullScreen")
    }

    function handlerRefreshRecorrido(){
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Si realiza esta acción perdera todos los cambios realizados a las paradas.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            fetch("<?=DOMAIN_ADMIN?>process.php?action=recorrido_update&idRecorrido=<?=$_GET["id"]?>")
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                Swal.fire(title, message, type).then(result => {
                    if(status === "OK") location.reload()
                })
            })
        });
    }

    function handlerTramosSortable() {
        // Seteo el orden por defecto
        let orderDefault = []
        for (const divGaleryItem of document.querySelectorAll("#contentTramoItems > tr")) orderDefault.push(divGaleryItem.dataset.id)
        lastOrderGalery = orderDefault

        new Sortable(document.getElementById('contentTramoItems'), {
            handle: '.iconSortable',
            animation: 150,
            store: {
                set: function(sortable) {
                    // Nuevo orden 
                    const order = sortable.toArray();

                    // No cambió el orden. No hago nada
                    if (order.join() === lastOrderGalery.join()) return

                    // Valido que no lo haya movido al principio ni al final
                    if(order[0] !== lastOrderGalery[0] || order[lastOrderGalery.length-1] !== lastOrderGalery[lastOrderGalery.length-1]){
                        Swal.fire("No permitido!", "No puede cambiar el inicio ni el final del recorrido.", "warning")
                        sortable.sort(lastOrderGalery, true)
                        return
                    }

                    // Desabilito el sortable
                    sortable.option("disabled", true)

                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si, estoy seguro",
                        cancelButtonText: "No"
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            sortable.option("disabled", false) // Habilito el sortable
                            sortable.sort(lastOrderGalery, true)
                            return
                        }

                        let formData = new FormData()
                        formData.append("action", "recorrido_setOrderTramos")
                        formData.append("idRecorrido", <?=$_GET["id"]?>)
                        formData.append("orderTramos", order)

                        // Cambio la contraseña
                        fetch(
                            "<?= DOMAIN_ADMIN ?>process.php", {
                            method: "POST",
                            body: formData,
                        })
                        .then(res => res.json())
                        .then(response => {
                            sortable.option("disabled", false) // Habilito el sortable
                            const {title, message, type, status} = response

                            Swal.fire(title, message, type).then(res => {
                                if (status === "OK"){
                                    lastOrderGalery = order // Seteo el nuevo orden
                                }else{
                                    sortable.sort(lastOrderGalery, true) // Vuelvo a como estaba antes
                                }
                            })
                        })
                    });
                    
                }
            }
        });
    }

    function handlerMarcarParada(elem, idTramo){
        
        Swal.fire({
            title: "¿Estás seguro que desea marcar la parada?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) {
                elem.checked = false
                return
            }

            let formData = new FormData()
            formData.append("action", "save")
            formData.append("table", "recorrido_tramos")
            formData.append("pk", "idRecorridoTramo")
            formData.append("idRecorridoTramo", idTramo)
            formData.append("estado", "M")

            // Cambio la contraseña
            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: formData,
            })
            .then(res => res.json())
            .then(response => {
                const {title, message, type, status} = response
                Swal.fire(title, message, type).then(res => {
                    if (status === "OK") location.reload()
                })
            })
        });
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
