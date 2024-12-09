<?
require_once __DIR__ . "/config/init.php";

$idPaquete = base64_decode($_GET["id"] ?? "");
$fecha = isset($_GET["fecha"]) && $_GET["fecha"] ? $_GET["fecha"] : "";
$idFecha = isset($_GET["idFecha"]) && $_GET["idFecha"] ? base64_decode($_GET["idFecha"]) : "";

$paquete = Paquete::getAllInfo($idPaquete);
if (!$paquete) HTTPController::get404(false);

$fechasDisponibles = Paquete::getAllFechasDisponibles($idPaquete);

$title = ucfirst($paquete->titulo);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <? require_once PATH_SERVER . "/public/head.php" ?>
</head>

<body class="bodyDetallePaquete">
    <? require_once PATH_SERVER . "/public/nav.php" ?>

    <main class="mainDetallePaquete">
        <section class="sectionSlide">
            <div class="h-100 w-100">
                <img src="<?= DOMAIN_NAME ?><?= $paquete->banner ?>" alt="Banner de la excursión" height="100%" width="100%" style="object-fit: cover; object-position: top;">
            </div>
        </section>

        <section class="sectionDetallePaquete">

            <!-- Navbar -->
            <div class="sectionDetallePaquete__contenteHeader">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detalle-tab" data-bs-toggle="tab" data-bs-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="true">Detalle</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="itinerario-tab" data-bs-toggle="tab" data-bs-target="#itinerario" type="button" role="tab" aria-controls="itinerario" aria-selected="false">Itinerario</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="equipo-tab" data-bs-toggle="tab" data-bs-target="#equipo" type="button" role="tab" aria-controls="equipo" aria-selected="false">Equipo</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="galeria-tab" data-bs-toggle="tab" data-bs-target="#galeria" type="button" role="tab" aria-controls="galeria" aria-selected="false">Galería</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="consulta-tab" data-bs-toggle="tab" data-bs-target="#consulta" type="button" role="tab" aria-controls="consulta" aria-selected="false">Consultar</button>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <div class="sectionDetallePaquete__contenteBody py-3 px-4 mb-5">

                <div class="tab-content" id="myTabContent">

                    <!-- DETALLE -->
                    <div class="tab-pane fade show active" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                        <div class="contentHeader text-primary fw-bold mt-2">
                            <h1 class="fw-bold h1"><?= ucfirst($paquete->titulo) ?></h1>
                            <h2 class="fw-bold h3"><?= ucfirst($paquete->subtitulo) ?></h2>

                            <? if ($paquete->traslado === 1): ?>
                                <p class="badge bg-3"><i class="fa fa-bus me-1"></i>Con traslado desde tu alojamiento</p>
                            <? endif; ?>
                        </div>

                        <div class="contentBody text-6">

                            <div class="contentDetallePaquete border p-2 mt-2 row mx-0">
                                <div class="col-6 col-lg-3 text-center">
                                    <p class="fs-3"><i class="fa fa-map-marker-alt me-1"></i><span class="fs-4">Destino</span></p>
                                    <p><?= ucfirst($paquete->provincia) . ", " . ucfirst($paquete->destino) ?></p>
                                </div>

                                <div class="col-6 col-lg-3 text-center">
                                    <p class="fs-3"><i class="fa fa-utensils me-1"></i><span class="fs-4">Pensión</span></p>
                                    <p><?= ucfirst($paquete->pension) ?></p>
                                </div>

                                <div class="col-6 col-lg-3 text-center">
                                    <p class="fs-3"><i class="fa fa-calendar-day me-1"></i><span class="fs-4">Salidas</span></p>
                                    <p><?= COUNT(Paquete::getAllFechasSalida($paquete->idPaquete, $fecha)) ?> programadas</p>
                                </div>

                                <div class="col-6 col-lg-3 text-center">
                                    <? if ($paquete->noches == 0): ?>
                                        <p class="fs-3"><i class="far fa-sun me-1"></i><span class="fs-4">Tiempo</span></p>
                                        <p>1 día</p>
                                    <? else: ?>
                                        <p class="fs-3"><i class="fa moon me-1"></i><span class="fs-4">Tiempo</span></p>
                                        <p><?= $paquete->noches ?> noche<?= $paquete->noches > 1 ? "s" : "" ?></p>
                                    <? endif; ?>
                                </div>
                            </div>

                            <div class="border border-top-0 p-2 text-center">
                                <p class="text-3 fs-1">$<?= Util::numberToPrice($paquete->precio, true) ?><span class="fs-6"> x persona</span></p>
                                <small>Disponible hasta el <?= date("d/m/Y", strtotime($paquete->fechaFinPublicacion)) ?></small>
                            </div>

                            <? if ($paquete->descripcion): ?>
                                <div class="pt-4">
                                    <?= str_replace(["<p></p>", "ql-size-huge", "ql-size-large"], ["<br>", "d-block h1 mt-2", "d-block h3 mt-2"], html_entity_decode($paquete->descripcion)) ?>
                                </div>
                            <? endif; ?>
                        </div>

                    </div>

                    <!-- ITINERARIO -->
                    <? if ($paquete->itinerario): ?>
                        <div class="tab-pane fade" id="itinerario" role="tabpanel" aria-labelledby="itinerario-tab">
                            <div><?= str_replace(["<p></p>", "ql-size-huge", "ql-size-large"], ["<br>", "d-block h1 mt-2", "d-block h3 mt-2"], html_entity_decode($paquete->itinerario)) ?></div>
                        </div>
                    <? endif; ?>

                    <!-- EQUIPO -->
                    <? if ($paquete->equipo): ?>
                        <div class="tab-pane fade" id="equipo" role="tabpanel" aria-labelledby="equipo-tab">
                            <div><?= str_replace(["<p></p>", "ql-size-huge", "ql-size-large"], ["<br>", "d-block h1 mt-2", "d-block h3 mt-2"], html_entity_decode($paquete->equipo)) ?></div>
                        </div>
                    <? endif; ?>

                    <!-- GALERIA -->
                    <? if ($paquete->galeria): ?>
                        <div class="tab-pane fade" id="galeria" role="tabpanel" aria-labelledby="galeria-tab">
                            <div class="row text-center text-lg-start">
                                <? foreach ($paquete->galeria as $index => $item):
                                    $isImage = in_array(strtolower(explode(".", $item->path)[1]), ["jpg", "jpeg", "png", "gif"]);
                                ?>
                                    <div class="col-12 col-md-6 mb-3" style="min-height: 250px;">
                                        <? if ($isImage): ?>
                                            <img class="img-fluid" src="<?= DOMAIN_NAME ?><?= $item->path ?>" alt="imagen <?= $index + 1 ?> de la galería" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                        <? else: ?>
                                            <div class="contentVideo bg-black h-100 w-100 d-flex justify-content-center align-items-center">
                                                <video src="<?= DOMAIN_NAME ?><?= $item->path ?>" controls width="100%" height="auto"></video>
                                            </div>
                                        <? endif; ?>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    <? endif; ?>

                    <!-- CONSULTAS -->
                    <div class="tab-pane fade" id="consulta" role="tabpanel" aria-labelledby="consulta-tab">
                        <form action="" class="row" id="formConsulta">
                            <div class="col-12">
                                <h3 class="h2 text-primary fw-bold">Envíanos tu consulta</h3>
                            </div>

                            <div class="col mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" oninput="validateForm(this)">
                            </div>

                            <? if ($fecha && $idFecha): ?>
                                <input type="hidden" name="fecha" value="<?=$idFecha?>">
                            <? else: ?>
                                <div class="col col-md-4 mb-2">
                                    <label for="fecha" class="form-label">Fecha de salida</label>
                                    <select name="fecha" id="fecha" class="form-control">
                                        <? if ($fechasDisponibles): ?>
                                            <option value="sin fecha seleccionada">-- Seleccione una fecha --</option>
                                        <? else: ?>
                                            <option value="sin disponibilidad">-- Sin disponibilidad --</option>
                                        <? endif; ?>
                                        
                                        <? foreach ($fechasDisponibles as $fecha): ?>
                                            <option value="<?= $fecha->id ?>"><?= date("d/m/Y", strtotime($fecha->fecha)) ?> (<?= $fecha->cupos === 1 ? "1 cupo disponible" : $fecha->cupos . " cupos disponibles" ?>)</option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            <? endif; ?>

                            <div class="col-12"></div>

                            <div class="col-12 mb-2">
                                <label for="asunto" class="form-label">Asunto</label>
                                <input type="text" class="form-control" id="asunto" name="asunto" oninput="validateForm(this, 3)">
                            </div>

                            <div class="col-12 mb-3">
                                <label for="consulta" class="form-label">Consulta</label>
                                <textarea class="form-control" name="consulta" id="consulta" oninput="validateForm(this, 3)"></textarea>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary bg-primary border-0" type="button" onclick="handlerSubmitContacto(this)"><i class="fa fa-paper-plane me-1"></i>Enviar</button>
                            </div>

                            <input type="hidden" name="idPaquete" value="<?=$idPaquete?>">
                            <input type="hidden" name="action" value="consulta_detallePublico_create">
                        </form>
                    </div>

                </div>

                <? require_once PATH_SERVER . "/public/footer.php" ?>
        </section>

    </main>


    <? require_once PATH_SERVER . "/public/script.php" ?>

    <script>
        function handlerSubmitContacto(element) {
            element.disabled = true
            
            if(document.querySelectorAll("#formConsulta .is-invalid").length > 0){
                Swal.fire("Campos inválidos!", "Revise el/los campo/s marcados en rojo y vuelva a enviar la consulta.", "warning")
                element.disabled = false
                return
            }

            // Envio el formulario
            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(document.getElementById("formConsulta"))
                    }
                )
                .then(res => res.json())
                .then(({
                    status,
                    title,
                    message,
                    type
                }) => {
                    element.disabled = false
                    Swal.fire(title, message, type).then(res => {
                        if(status === "OK") document.getElementById("formConsulta").reset()
                    })
                })
        }
    </script>
</body>

</html>