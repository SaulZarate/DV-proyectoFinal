<?
require_once __DIR__ . "/../../config/init.php";

$section = "Excursiones";

$titlePage = isset($_GET["id"]) ? "Editar excursión" : "Agregar excursión";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

$excursion = isset($_GET["id"]) ? Paquete::getById($_GET["id"]) : null;
$fechasDeSalidas = array();
$galeryExcursion = array();

if ($excursion) {
    $fechasDeSalidas = Paquete::getAllFechasSalida($_GET['id']);
    $galeryExcursion = Paquete::getAllGalery($_GET['id']);
}

$title = "Excursiones | " . APP_NAME;
ob_start();

?>

<section class="section profile">
    <div class="row">

        <!-- Imagen principal & Banner -->
        <? if ($excursion): ?>
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body p-2" style="height: 300px; overflow: hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="h5 text-center mb-2">Portada</h3>
                            <a href="javascript:;" onclick="openModalUpdateImage('image')">
                                <i class="bi bi-pencil" data-bs-toggle="tooltip" title="Cambiar imagen principal"></i>
                            </a>
                        </div>
                        <div class="contentImage w-100" style="height: 250px; overflow: hidden;">
                            <img src="<?= DOMAIN_NAME . $excursion->imagen ?>" alt="Imagen principal" class="rounded-1 img-100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-lg-10">
                <div class="card">
                    <div class="card-body p-2" style="height: 300px; overflow: hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="h5 text-center mb-2">Banner</h3>
                            <a href="javascript:;" onclick="openModalUpdateImage('banner')"><i class="bi bi-pencil" data-bs-toggle="tooltip" title="Cambiar banner"></i></a>
                        </div>
                        <div class="contentImage w-100" style="height: 250px; overflow: hidden;">
                            <img src="<?= DOMAIN_NAME . $excursion->banner ?>" alt="Banner" class="rounded-1 img-100">
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>


        <div class="col-12">
            <div class="card">
                <div class="card-body <?= $excursion ? "pt-2" : "" ?>">

                    <? if (!$excursion): ?>
                        <h5 class="card-title m-0"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>
                    <? endif; ?>

                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link <?= isset($_GET["show"]) ? "" : "active" ?>" data-bs-toggle="tab" data-bs-target="#excursion-edit"><i class="bi bi-bus-front me-1"></i>Datos generales</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link <?= isset($_GET["show"]) && $_GET["show"] == "descripcion" ? "active" : "" ?>" data-bs-toggle="tab" data-bs-target="#descripcion-edit"><i class="bi bi-body-text me-1"></i>Descripción</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link <?= isset($_GET["show"]) && $_GET["show"] == "itinerario" ? "active" : "" ?>" data-bs-toggle="tab" data-bs-target="#itinerario-edit"><i class="bi bi-list-ol me-1"></i>Itinerario</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link <?= isset($_GET["show"]) && $_GET["show"] == "equipo" ? "active" : "" ?>" data-bs-toggle="tab" data-bs-target="#equipo-edit"><i class="bi bi-duffle me-1"></i>Equipo</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link <?= isset($_GET["show"]) && $_GET["show"] == "galery" ? "active" : "" ?>" data-bs-toggle="tab" data-bs-target="#galery-edit"><i class="bi bi-folder-fill me-1"></i>Galería</button>
                        </li>
                    </ul>

                    <!-- Content tabs -->
                    <div class="tab-content pt-3">

                        <!-- ------------------ -->
                        <!--                    -->
                        <!--        EDITAR      -->
                        <!--                    -->
                        <!-- ------------------ -->
                        <div class="tab-pane fade <?= isset($_GET["show"]) ? "" : "show active" ?>" id="excursion-edit">
                            <form method="post" class="row g-3" enctype="multipart/form-data" id="formExcursion">

                                <!--#region Datos generales -->
                                <div class="col-12">
                                    <h5 class="card-title p-0 m-0 fs-5"><i class="bi bi-card-text me-1"></i>Datos generales</h5>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" oninput="FormController.validateForm(this, 4)" value="<?= $excursion->titulo ?? "" ?>">
                                        <label for="titulo">Título</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subtitulo" name="subtitulo" placeholder="Subtítulo" oninput="FormController.validateForm(this, 4)" value="<?= $excursion->subtitulo ?? "" ?>">
                                        <label for="subtitulo">Subtítulo</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="estado" name="estado" aria-label="Estado">
                                            <option value="A" <?= $excursion && $excursion->estado == "A" ? "selected" : "" ?>>Activo</option>
                                            <option value="I" <?= $excursion && $excursion->estado == "I" ? "selected" : "" ?>>Inactivo</option>
                                        </select>
                                        <label for="estado">Estado</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="precio" name="precio" placeholder="Precio" oninput="FormController.validateForm(this)" value="<?= $excursion->precio ?? "" ?>">
                                        <label for="precio">Precio</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="capacidad" name="capacidad" placeholder="Cupos" oninput="FormController.validateForm(this)" value="<?= $excursion->capacidad ?? "" ?>">
                                        <label for="capacidad">Cupos</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="idProvincia" name="idProvincia" aria-label="Provincia" oninput="FormController.validateForm(this)">
                                            <option value="">-|-</option>
                                            <? foreach (DB::select("provincias") as $provincia): ?>
                                                <option value="<?= $provincia->idProvincia ?>" <?= $excursion && $excursion->idProvincia == $provincia->idProvincia ? "selected" : "" ?>><?= ucfirst($provincia->nombre) ?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idProvincia">Provincia</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="destino" name="destino" placeholder="Destino" oninput="FormController.validateForm(this, 4)" value="<?= $excursion->destino ?? "" ?>">
                                        <label for="destino">Destino</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="pension" name="pension" placeholder="Pensión" oninput="FormController.validateForm(this, 4)" value="<?= $excursion->pension ?? "" ?>">
                                        <label for="pension">Pensión</label>
                                    </div>
                                </div>

                                <!-- Check traslado -->
                                <div class="col-12 mt-2 mt-md-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="traslado" name="traslado" <?= $excursion && $excursion->traslado ? "checked" : "" ?>>
                                        <label class="form-check-label" for="traslado">
                                            ¿Incluye translado al hospedaje?
                                        </label>
                                    </div>
                                </div>
                                <!--#endregion -->


                                <!-- Imagenes -->
                                <? if (!$excursion): ?>
                                    <hr>
                                    <div class="col-12 mb-2 mt-0">
                                        <h5 class="card-title p-0 m-0 mb-2"><i class="bi bi-card-image me-1"></i>Imagenes</h5>
                                    </div>
                                    <div class="col-md-6 m-0">
                                        <label for="image">Imagen principal</label>
                                        <input class="form-control" type="file" id="image" name="image">
                                        <small class="text-secondary">Extensiones válidas: <?= implode(", ", FileController::validExtensionsImage) ?></small>
                                    </div>
                                    <div class="col-md-6 m-0">
                                        <label for="banner">Banner</label>
                                        <input class="form-control" type="file" id="banner" name="banner">
                                        <small class="text-secondary">Extensiones válidas: <?= implode(", ", FileController::validExtensionsImage) ?></small>
                                    </div>
                                    <hr>
                                <? endif; ?>


                                <!--#region Configuración general & Fechas de salidas -->
                                <div class="col-md-7 col-lg-8">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <h5 class="card-title p-0 m-0 fs-5"><i class="bi bi-gear me-1"></i>Configuración general</h5>
                                            <p class="m-0 mb-1 text-secondary">Realice todas las configuraciones básicas que desee</p>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="date" class="form-control" id="fechaInicioPublicacion" name="fechaInicioPublicacion" oninput="FormController.validateForm(this)" value="<?= $excursion->fechaInicioPublicacion ?? "" ?>">
                                                <label for="fechaInicioPublicacion">Inicio de la publicación</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2 mt-md-0">
                                            <div class="form-floating">
                                                <input type="date" class="form-control" id="fechaFinPublicacion" name="fechaFinPublicacion" oninput="FormController.validateForm(this)" value="<?= $excursion->fechaFinPublicacion ?? "" ?>">
                                                <label for="fechaFinPublicacion">Fin de la publicación</label>
                                            </div>
                                        </div>

                                        <div class="col-12 my-1"></div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="time" class="form-control" id="horaSalida" name="horaSalida" onkeyup="FormController.validateForm(this)" value="<?= $excursion && $excursion->horaSalida ? date("H:i", strtotime($excursion->horaSalida)) : "" ?>">
                                                <label for="horaSalida">Horario de salida</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2 mt-md-0">
                                            <div class="form-floating">
                                                <input type="time" class="form-control" id="horaLlegada" name="horaLlegada" onkeyup="FormController.validateForm(this)" value="<?= $excursion && $excursion->horaLlegada ? date("H:i", strtotime($excursion->horaLlegada)) : "" ?>">
                                                <label for="horaLlegada">Horario de llegada</label>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-floating">
                                                <input type="tel" class="form-control" id="noches" name="noches" placeholder="Cantidad de noches" oninput="FormController.validateForm(this)" value="<?= $excursion->noches ?? "" ?>">
                                                <label for="noches">Cantidad de noches</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 col-lg-4">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <h5 class="card-title p-0 m-0 fs-5"><i class="bi bi-calendar me-1"></i>Fechas de salidas</h5>
                                            <p class="m-0 mb-1 text-secondary"><?= $excursion ? "Seleccione las fechas que desea agregar" : "Seleccione las fechas de salida" ?></p>
                                        </div>

                                        <div class="col-12">
                                            <input type="hidden" name="fechasSalida" id="fechasSalida" class="form-control form-control-sm" placeholder="Seleccione todas las fechas de desee agregar">

                                            <? if ($fechasDeSalidas): ?>
                                                <div class="contentFechasSalidas border rounded p-2 d-flex align-items-center flex-wrap mt-2">
                                                    <? foreach ($fechasDeSalidas as $fechaDeSalida): ?>
                                                        <p class="badge bg-success mb-1 me-1" id="contentFechaSalida-<?= $fechaDeSalida->id ?>">
                                                            <?= date("d/m/Y", strtotime($fechaDeSalida->fecha)) ?>
                                                            <button type="button" class="border-0 bg-transparent text-white" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar fecha" onclick="handlerDeleteFecha(<?= $_GET['id'] ?>, '<?= $fechaDeSalida->fecha ?>', <?= $fechaDeSalida->id ?>)"><i class="fa fa-times-circle ms-1"></i></button>
                                                        </p>
                                                    <? endforeach; ?>
                                                </div>
                                            <? endif; ?>

                                        </div>
                                    </div>
                                </div>
                                <!--#endregion -->


                                <input type="hidden" name="idPaquete" value="<?= $_GET['id'] ?? '' ?>">
                                <input type="hidden" name="pk" value="idPaquete">
                                <input type="hidden" name="table" value="paquetes">
                                <input type="hidden" name="action" value="paquete_save">

                                <div class="">
                                    <button type="button" class="btn btn-primary" onclick="handlerSubmitExcursion(this)"><i class="fa fa-save me-1"></i><?= $excursion ? "Guardar" : "Agregar" ?></button>
                                    <a href="<?= DOMAIN_ADMIN ?>excursiones" class="btn btn-secondary"><i class="fa fa-times-circle me-1"></i>Cancelar</a>
                                </div>
                            </form>
                        </div>


                        <!-- ------------------- -->
                        <!--                     -->
                        <!--         Galery      -->
                        <!--                     -->
                        <!-- ------------------- -->
                        <div class="tab-pane fade <?= isset($_GET["show"]) && $_GET["show"] == "galery" ? "show active" : "" ?>" id="galery-edit">

                            <? if ($excursion): ?>

                                <? if (!$galeryExcursion): ?>
                                    <h5 class="text-center bg-light border bordered py-3"><i class="bi bi-images me-1"></i>Galería vacía</h5>
                                <? endif; ?>

                                <form action="" method="post" enctype="multipart/form-data" id="formGalery" class="border p-3 rounded">
                                    <input type="hidden" name="action" value="paquete_uploadsGalery">
                                    <input type="hidden" name="idPaquete" value="<?= $_GET["id"] ?>">

                                    <!-- Content previewer -->
                                    <div class="custom-file-container" data-upload-id="custom-file-container"></div>


                                    <small class="text-secondary">
                                        Máxima capacidad de subida: <span id="galey_limiteSubida"><?= number_format(Util::convertBytes(FileController::getMaxSizeUploadServer(), "KB"), 0, ",", ".") ?> KB</span>
                                    </small>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-sm" type="button" onclick="handlerSubmitGalery()" id="formGalery_btnSubmit"><i class="bi bi-cloud-arrow-up me-1"></i>Subir archivos seleccionados</button>
                                    </div>
                                </form>


                                <? if ($galeryExcursion): ?>
                                    <div class="table-responsive mt-4">
                                        <table class="table table-sm table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="5" class="bg-light">
                                                        <h5 class="card-title py-2 m-0 fs-5 text-center"><i class="bi bi-folder2-open me-1"></i>Archivos de la galería</h5>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" style="width: 10%;">Orden</th>
                                                    <th class="text-center"><i class="bi bi-display me-1"></i>Archivo</th>
                                                    <th class="text-center" style="width: 10%;"><i class="bi bi-file-earmark me-1"></i>Tipo</th>
                                                    <th class="text-center" style="width: 20%;"><i class="bi bi-calendar-date me-1"></i>Fecha de subida</th>
                                                    <th style="width: 10%;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="contentGaleryItems">
                                                <? foreach ($galeryExcursion as $galeryItem):
                                                    $isImage = in_array(strtolower(explode(".", $galeryItem->path)[1]), ["jpg", "jpeg", "png", "gif"]);
                                                ?>
                                                    <tr data-id="<?= $galeryItem->id ?>" id="galeryItem-<?= $galeryItem->id ?>">
                                                        <td class="text-center align-middle">
                                                            <!-- <i class="fa fa-sort me-1"></i> -->
                                                            <i class="fa fa-sort iconSortable cursor-pointer"></i>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="<?= DOMAIN_NAME ?><?= $galeryItem->path ?>" target="_blank">
                                                                <? if ($isImage): ?>
                                                                    <img src="<?= DOMAIN_NAME ?><?= $galeryItem->path ?>" alt="<?= $galeryItem->path ?>" width="80" height="80">
                                                                <? else: ?>
                                                                    <video src="<?= DOMAIN_NAME ?><?= $galeryItem->path ?>" width="80" height="80"></video>
                                                                <? endif; ?>
                                                            </a>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge <?= $isImage ? "bg-success" : "bg-primary" ?>">
                                                                <? if ($isImage): ?>
                                                                    <i class="bi bi-image me-1"></i>Imagen
                                                                <? else: ?>
                                                                    <i class="bi bi-camera-reels me-1"></i>Vídeo
                                                                <? endif; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge bg-info text-dark"><?= date("d/m/Y", strtotime($galeryItem->created_at)) ?></span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="<?= DOMAIN_NAME ?><?= $galeryItem->path ?>" target="_blank" data-bs-toggle="tooltip" title="Ver"><i class="bi bi-eye"></i></a>
                                                            <button type="button" class="text-danger bg-transparent border-0" onclick="handlerDeletePaquete(<?= $galeryItem->id ?>)"><i class="bi bi-trash" data-bs-toggle="tooltip" title="Eliminar"></i></button>
                                                        </td>
                                                    </tr>
                                                <? endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <? endif; ?>

                            <? else: ?>
                                <div class="bg-light px-3 py-5 text-center">
                                    <h3 class="mb-0 h5">Sin excursión</h3>
                                    <p class="mb-0 text-secondary">Debe crear un excursión para acceder a esta sección</p>
                                </div>
                            <? endif; ?>


                        </div>


                        <!-- ------------------------ -->
                        <!--                          -->
                        <!--         Descripción      -->
                        <!--                          -->
                        <!-- ------------------------ -->
                        <div class="tab-pane fade <?= isset($_GET["show"]) && $_GET["show"] == "descripcion" ? "show active" : "" ?>" id="descripcion-edit">

                            <? if ($excursion): ?>
                                <div id="textareaEditorDescripcion" style="min-height: 100px;"><?=html_entity_decode($excursion->descripcion)?></div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="handlerBtnSaveTextEditor(this, 'descripcion')"><i class="fa fa-save me-1"></i>Guardar</button>
                            <? else: ?>
                                <div class="bg-light px-3 py-5 text-center">
                                    <h3 class="mb-0 h5">Sin excursión</h3>
                                    <p class="mb-0 text-secondary">Debe crear un excursión para acceder a esta sección</p>
                                </div>
                            <? endif; ?>
                        </div>


                        <!-- ------------------------ -->
                        <!--                          -->
                        <!--         Itinerario       -->
                        <!--                          -->
                        <!-- ------------------------ -->
                        <div class="tab-pane fade <?= isset($_GET["show"]) && $_GET["show"] == "itinerario" ? "show active" : "" ?>" id="itinerario-edit">

                            <? if ($excursion): ?>
                                <div id="textareaEditorItinerario" style="min-height: 100px;"><?=html_entity_decode($excursion->itinerario)?></div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="handlerBtnSaveTextEditor(this, 'itinerario')"><i class="fa fa-save me-1"></i>Guardar</button>
                            <? else: ?>
                                <div class="bg-light px-3 py-5 text-center">
                                    <h3 class="mb-0 h5">Sin excursión</h3>
                                    <p class="mb-0 text-secondary">Debe crear un excursión para acceder a esta sección</p>
                                </div>
                            <? endif; ?>
                        </div>


                        <!-- ------------------------ -->
                        <!--                          -->
                        <!--            Equipo        -->
                        <!--                          -->
                        <!-- ------------------------ -->
                        <div class="tab-pane fade <?= isset($_GET["show"]) && $_GET["show"] == "equipo" ? "show active" : "" ?>" id="equipo-edit">

                            <? if ($excursion): ?>
                                <div id="textareaEditorEquipo" style="min-height: 100px;"><?=html_entity_decode($excursion->equipo)?></div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="handlerBtnSaveTextEditor(this, 'equipo')"><i class="fa fa-save me-1"></i>Guardar</button>
                            <? else: ?>
                                <div class="bg-light px-3 py-5 text-center">
                                    <h3 class="mb-0 h5">Sin excursión</h3>
                                    <p class="mb-0 text-secondary">Debe crear un excursión para acceder a esta sección</p>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODAL | Cambio de portada & banner -->
<div class="modal fade" id="modalUploadImage" tabindex="-1" style="display: none;" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-image me-1"></i><span id="modalUploadImage_title">Título</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="modalUploadImage_form">

                    <input type="file" name="image" class="form-control" id="modalUploadImage_inputImage">
                    <small class="text-secondary">Extensiones válidas: <?= implode(", ", FileController::validExtensionsImage) ?></small>

                    <input type="hidden" name="table" value="paquetes">
                    <input type="hidden" name="pk" value="idPaquete">
                    <input type="hidden" name="destino" id="modalUploadImage_inputName">
                    <input type="hidden" name="action" value="paquete_uploadImagenBanner">
                    <input type="hidden" name="idPaquete" value="<?= $_GET["id"] ?? "" ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times-circle me-1"></i>Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="handlerSubmitModalUploadImage(this)"><i class="fa fa-save me-1"></i>Subir</button>
            </div>
        </div>
    </div>
</div>



<script>
    const maxSizeFilesUplaod = <?= FileController::getMaxSizeUploadServer() ?>;
    let textArea = null
    let modalUploadImage = null
    let fechasSalida = null
    let lastOrderGalery = [] // Para saber cual fue el orden anterior por si rechaza la actualización

    // Editores de texto
    let textareaEditorDescripcion = null
    let textareaEditorItinerario = null
    let textareaEditorEquipo = null

    document.addEventListener("DOMContentLoaded", e => {
        HTMLController.trigger("#titulo,#subtitulo,#precio,#capacidad,#idProvincia,#destino,#pension,#fechaInicioPublicacion,#fechaFinPublicacion,#noches", "input")
        HTMLController.trigger("#horaSalida,#horaLlegada", "keyup")

        // Modal | Creo el objeto
        modalUploadImage = new bootstrap.Modal(document.getElementById('modalUploadImage'), {
            keyboard: false
        })

        // Inicializo el datepicker
        fechasSalida = new Datepicker('#fechasSalida', {
            multiple: true,
            inline: true,
            min: "<?= date("Y-m-d") ?>"
        });


        // Inicializo los editores de texto
        textareaEditorDescripcion = new TextareaEditor("#textareaEditorDescripcion")
        textareaEditorItinerario = new TextareaEditor("#textareaEditorItinerario")
        textareaEditorEquipo = new TextareaEditor("#textareaEditorEquipo")

        textareaEditorDescripcion.initFull({video: "none"})
        textareaEditorItinerario.initFull({video: "none"})
        textareaEditorEquipo.initFull({video: "none"})


        // Inicializo el drag and drop sortable si hay archivos
        if (!!document.getElementById("contentGaleryItems")) handlerGalerySortable()
    })

    /* ----------------------------------- */
    /*          MODAL UPLOAD IMAGE         */
    /* ----------------------------------- */
    function openModalUpdateImage(type) {
        let title = "";
        let nameInput = ""

        if (type == "image") {
            title = "Cambiar portada"
            nameInput = "imagen"
        }

        if (type == "banner") {
            title = "Cambiar banner"
            nameInput = "banner"
        }

        document.getElementById("modalUploadImage_inputName").value = nameInput
        document.querySelector("#modalUploadImage #modalUploadImage_title").textContent = title

        // Abro el modal
        modalUploadImage.show()
    }

    function handlerSubmitModalUploadImage(element) {
        const form = document.getElementById("modalUploadImage_form")

        // Deshabilito el botón
        element.disabled = true

        // Valido que haya una imagen
        if (document.getElementById("modalUploadImage_inputImage").value == "") {
            Swal.fire("Sin imagen!", "Seleccione una imagen y vuelva a intentarlo.", "warning")
            element.disabled = false
            return
        }

        // Pido confirmación
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
                element.disabled = false
                return
            }

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: new FormData(form),
                })
                .then(res => res.json())
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status
                    } = response
                    Swal.fire(title, message, type).then(res => {
                        if (status === "OK") location.reload()
                    })

                    element.disabled = false
                })
        });
        // modalUploadImage.hide()
    }


    /* ------------------------------- */
    /*          SECTION EDITAR         */
    /* ------------------------------- */
    function handlerSubmitExcursion(elementBtn) {
        const form = document.getElementById("formExcursion")
        elementBtn.disabled = true

        // Valido el formulario
        if (document.querySelectorAll("input.is-invalid,select.is-invalid").length > 0) {
            Swal.fire("Campos invalidos!", "Revise todos los campos marcados en rojo para continuar", "warning")
            elementBtn.disabled = false
            return
        }

        // Pido confirmación
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
                elementBtn.disabled = false
                return
            }

            
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: new FormData(form),
                })
                .then(res => res.json())
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status,
                        idPaquete
                    } = response

                    Swal.fire(title, message, type).then(res => {
                        if (status === "OK") {
                            location.href = "<?= DOMAIN_ADMIN ?>excursiones/editar?id=" + idPaquete
                        }
                    })

                    elementBtn.disabled = false
                })
        });
    }


    /**
     * Elimina fechas de salida
     * Válida que haya por lo menos una
     * Deshabilita todos los botones mientras se realiza el proceso
     */
    function handlerDeleteFecha(idPaquete, fechas, idFecha) {

        // Valido que no sea la última fecha seleccionada
        if (document.querySelectorAll(".contentFechasSalidas > p").length == 1) {
            Swal.fire("No puede eliminar la fecha!", "Tiene que haber por lo menos una fecha de salida en cada excursión", "warning")
            return
        }

        // Deshabilito todos los botones habilitados
        let buttonsVisible = HTMLController.selectElementVisible("button")
        for (const btnVisible of buttonsVisible) btnVisible.disabled = true

        // Pido confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Si eliminas esta fecha afectaras a todos las tareas relacionadas con la misma.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) {
                for (const btnVisible of buttonsVisible) btnVisible.disabled = false
                return
            }

            let formData = new FormData()
            formData.append("action", "paquete_deleteFechaSalida")
            formData.append("idPaquete", idPaquete)
            formData.append("fecha", fechas)

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: formData,
                })
                .then(res => res.json())
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status
                    } = response

                    Swal.fire(title, message, type).then(res => {
                        // Si salio todo bien elimino el elemento
                        if (status === "OK") document.getElementById(`contentFechaSalida-${idFecha}`).remove()
                    })

                    for (const btnVisible of buttonsVisible) btnVisible.disabled = false
                })
        });
    }


    /* -------------------------------------------- */
    /*                                              */
    /*               EDITORES DE TEXTO              */
    /*      Descripcion | Itinerario | Equipo       */
    /*                                              */
    /* -------------------------------------------- */
    function handlerBtnSaveTextEditor(elBtn, typeEditor){

        // Pido confirmación
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
                btnSubmit.reset()
                return
            }

            let btnSubmit = new FormButtonSubmitController(elBtn, false)
            btnSubmit.init()

            let editor = null
            if(typeEditor == "descripcion") editor = textareaEditorDescripcion
            if(typeEditor == "itinerario") editor = textareaEditorItinerario
            if(typeEditor == "equipo") editor = textareaEditorEquipo

            let formData = new FormData()
            formData.append("action", "paquete_updateText")
            formData.append("idPaquete", <?=$_GET["id"] ?? 0?>)
            formData.append("tipo", typeEditor)
            formData.append("texto", editor.getHTML())

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: formData,
                })
                .then(res => res.json())
                .then(response => {
                    btnSubmit.reset()
                    
                    const {title, message, type, status} = response
                    Swal.fire(title, message, type)
                })
        });
    }


    /* ----------------------------- */
    /*              GALERY           */
    /* ----------------------------- */
    function handlerSubmitGalery() {

        const btnSubmit = new FormButtonSubmitController("#formGalery_btnSubmit")

        // Deshabilito el botón
        btnSubmit.init(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Subiendo...`)

        // Valido que haya una imagen
        if (!window.fileUploadWithPreview.hasFiles()) {
            Swal.fire("Sin imagenes seleccionadas!", "", "warning")
            btnSubmit.reset() // Habilito el botón submit
            return
        }

        // Cargo el formData
        let formData = new FormData(document.getElementById("formGalery"))

        // Agrego los archivos
        let files = window.fileUploadWithPreview.getFiles()
        let sizeFiles = 0

        for (let i = 0; i < files.length; i++) {
            formData.append("files" + i, files[i])
            sizeFiles += files[i].size
        }

        if (sizeFiles > maxSizeFilesUplaod) {
            const limite = document.querySelector("#galey_limiteSubida").textContent
            Swal.fire(
                "Superó el límite de subida!",
                `El tamaño de todos los archivos seleccionados superan el máximo por ${Util.numberToPrice(Util.convertBytes(sizeFiles - maxSizeFilesUplaod), true)} KB`,
                "warning")
            btnSubmit.reset() // Habilito el botón submit
            return
        }

        // Pido confirmación
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
                btnSubmit.reset() // Habilito el botón submit
                return
            }

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: formData,
                })
                .then(res => {
                    btnSubmit.reset() // Habilito el botón submit
                    return res.json()
                })
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status
                    } = response
                    Swal.fire(title, message, type).then(res => {
                        if (status === "OK") {
                            const urlParams = new URLSearchParams(window.location.search);
                            urlParams.set('show', 'galery');
                            window.location.search = urlParams;
                        }
                    })

                })
        });
        // modalUploadImage.hide()
    }

    function handlerDeletePaquete(idFile) {

        // Deshabilito todos los botones habilitados
        let buttonsVisible = HTMLController.selectElementVisible("button")
        for (const btnVisible of buttonsVisible) btnVisible.disabled = true

        // Pido confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Si eliminas este archivo no podras recuperarlo.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) {
                for (const btnVisible of buttonsVisible) btnVisible.disabled = false
                return
            }

            let formData = new FormData()
            formData.append("action", "paquete_deleteArchivoGalery")
            formData.append("idFile", idFile)

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: formData,
                })
                .then(res => res.json())
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status
                    } = response

                    Swal.fire(title, message, type).then(res => {
                        // Si salio todo bien elimino el elemento
                        if (status === "OK") document.getElementById(`galeryItem-${idFile}`).remove()
                    })

                    for (const btnVisible of buttonsVisible) btnVisible.disabled = false
                })
        });
    }

    function handlerGalerySortable() {
        // Seteo el orden por defecto
        let orderDefault = []
        for (const divGaleryItem of document.querySelectorAll("#contentGaleryItems > tr")) orderDefault.push(divGaleryItem.dataset.id)
        lastOrderGalery = orderDefault

        new Sortable(document.getElementById('contentGaleryItems'), {
            handle: '.iconSortable',
            animation: 150,
            /* onUpdate: function(evt) {
                console.log("Cambio el orden de la lista")
            }, */
            store: {
                set: function(sortable) {
                    // Nuevo orden 
                    const order = sortable.toArray();

                    // No cambió el orden. No hago nada
                    if (order.join() === lastOrderGalery.join()) return

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
                        formData.append("action", "paquete_setOrderGalery")
                        formData.append("orderGalery", order)

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
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
