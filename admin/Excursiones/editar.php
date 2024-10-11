<?
require_once __DIR__ . "/../../config/init.php";

$section = "Excursiones";

$titlePage = isset($_GET["id"]) ? "Editar excursión" : "Agregar excursión";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

$excursion = isset($_GET["id"]);
/* $excursion = isset($_GET["id"]) ? Usuario::getById($_GET["id"]) : null; */

$title = "Excursiones | " . APP_NAME;
ob_start();
?>

<section class="section profile">
    <div class="row">

        <!-- Imagen principal & Banner -->
        <? if ($excursion): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-body p-2" style="height: 300px; overflow: hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="h5 text-center mb-2">Portada</h3>
                            <a href="#"><i class="bi bi-pencil" data-bs-toggle="tooltip" data-bs-original-title="Cambiar imagen"></i></a>
                        </div>
                        <div class="contentImage w-100" style="height: 250px;">
                            <img src="<?= DOMAIN_NAME ?>assets/img/profile-img.jpg" alt="Imagen principal" class="rounded-1 img-100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="card">
                    <div class="card-body p-2" style="height: 300px; overflow: hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="h5 text-center mb-2">Banner</h3>
                            <a href="#"><i class="bi bi-pencil" data-bs-toggle="tooltip" data-bs-original-title="Cambiar imagen"></i></a>
                        </div>
                        <div class="contentImage w-100" style="height: 250px;">
                            <img src="<?= DOMAIN_NAME ?>assets/img/slides-1.jpg" alt="Imagen principal" class="rounded-1 img-100">
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>


        <div class="col-12">
            <div class="card">
                <div class="card-body <?=$excursion ? "pt-2" : ""?>">

                    <? if (!$excursion): ?>            
                        <h5 class="card-title m-0"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>
                    <? endif; ?>

                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#excursion-edit"><i class="bi bi-bus-front me-1"></i>Excursión</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#galery-edit"><i class="bi bi-images me-1"></i>Galería</button>
                        </li>
                    </ul>

                    <!-- Content tabs -->
                    <div class="tab-content pt-3">

                        <!-- Editar -->
                        <div class="tab-pane fade show active" id="excursion-edit">
                            <form class="row g-3">

                                <!-- Datos generales -->
                                <div class="col-12">
                                    <h5 class="card-title p-0 m-0 fs-5"><i class="bi bi-card-text me-1"></i>Datos generales</h5>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="titulo" name="titulo">
                                        <label for="titulo">Título</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subtitulo" name="subtitulo">
                                        <label for="subtitulo">Subtítulo</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="estado" name="estado" aria-label="Estado">
                                            <option value="A">Activo</option>
                                            <option value="I">Inactivo</option>
                                        </select>
                                        <label for="estado">Estado</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="precio" name="precio">
                                        <label for="precio">Precio</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="capacidad" name="capacidad">
                                        <label for="capacidad">Cupos</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="idProvincia" name="idProvincia" aria-label="Provincia">
                                            <option value="">-|-</option>
                                            <? foreach (DB::select("provincias") as $provincia): ?>
                                                <option value="<?= $provincia->idProvincia ?>"><?= ucfirst($provincia->nombre) ?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idProvincia">Provincia</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="destino" name="destino">
                                        <label for="destino">Destino</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="pension" name="pension">
                                        <label for="pension">Pensión</label>
                                    </div>
                                </div>



                                <!-- Fechas -->
                                <div class="col-12 mt-4">
                                    <h5 class="card-title p-0 m-0 fs-5"><i class="bi bi-calendar me-1"></i>Configuración de fechas</h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="time" class="form-control" id="horarioSalida" name="horarioSalida">
                                        <label for="horarioSalida">Horario de salida</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="fechaInicioPublicación" name="fechaInicioPublicación">
                                        <label for="fechaInicioPublicación">Inicio de la publicación</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="fechaFinPublicación" name="fechaFinPublicación">
                                        <label for="fechaFinPublicación">Fin de la publicación</label>
                                    </div>
                                </div>



                                <!-- Imagenes -->
                                <? if (!$excursion): ?>
                                    <div class="col-12 mt-4">
                                        <h5 class="card-title p-0 m-0 fs-5"><i class="bi bi-card-image me-1"></i>Imagenes</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="image">Imagen principal</label>
                                        <input class="form-control" type="file" id="image" name="image">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="banner">Banner</label>
                                        <input class="form-control" type="file" id="banner" name="banner">
                                    </div>
                                <? endif; ?>


                                <div class="col-12 mt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck">
                                        <label class="form-check-label" for="gridCheck">
                                            Incluye translado
                                        </label>
                                    </div>
                                </div>

                                <input type="hidden" name="idPaquete" value="<?= $_GET['id'] ?? '' ?>">
                                <input type="hidden" name="pk" value="idPaquete">
                                <input type="hidden" name="table" value="paquetes">

                                <input type="hidden" name="noches" value="0">

                                <div class="">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i><?= $excursion ? "Guardar" : "Agregar" ?></button>
                                    <a href="<?= DOMAIN_ADMIN ?>excursiones" class="btn btn-secondary"><i class="fa fa-times-circle me-1"></i>Cancelar</a>
                                </div>
                            </form>
                        </div>

                        <!-- Gallery -->
                        <div class="tab-pane fade" id="galery-edit">

                            <? if ($excursion): ?>
                                <form class="mb-3" action="POST" enctype="multipart/form-data">
                                    <button class="btn btn-primary btn-sm" type="button"><i class="bi bi-upload me-1"></i>Subir archivo</button>
                                </form>

                                <div class="table-responsive ">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;"></th>
                                                <th>Imagen</th>
                                                <th>Fecha de subida</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="text-center"><i class="bi bi-arrows-move"></i></th>
                                                <td>Brandon Jacob</td>
                                                <td>2016-05-25</td>
                                            </tr>
                                            <tr>
                                                <th class="text-center"><i class="bi bi-arrows-move"></i></th>
                                                <td>Bridie Kessler</td>
                                                <td>2014-12-05</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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


<script>
    let textArea = null

    document.addEventListener("DOMContentLoaded", e => {
        HTMLController.trigger("#nombre,#apellido,#email,#codPais,#codArea,#telefono,#dni,#fechaNacimiento,#nacionalidad,#sexo", "input")

        <? if ($_SESSION["user"]["descripcion"]): ?>
            TextareaEditor.initOnlyShow("#contentAbout")
        <? endif; ?>

        // Editar perfil | Inicio el textarea
        textArea = new TextareaEditor("#about")
        textArea.initBasic()
    })


    /* ------------------------------- */
    /*          SECTION EDITAR         */
    /* ------------------------------- */
    function handlerSaveProfile() {
        const form = document.getElementById("formProfile")

        // Valido el formulario
        if (document.querySelectorAll("input.is-invalid,select.is-invalid").length > 0) {
            Swal.fire("Campos invalidos!", "Revise todos los campos marcados en rojo para continuar", "warning")
            return
        }

        let formData = new FormData(form)
        formData.append("descripcion", textArea.getHTML())

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
            if (!result.isConfirmed) return

            // Cambio la contraseña
            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: formData,
                    }
                )
                .then(res => res.json())
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status
                    } = response

                    Swal.fire(title, message, type).then(res => {
                        if (status === "OK") {
                            location.href = '<?= DOMAIN_ADMIN ?>perfil/?view=edit'
                        }
                    })
                })
        });
    }


    /* ----------------------------------------- */
    /*          SECTION NUEVA CONTRASEÑA         */
    /* ----------------------------------------- */
    function changePassword() {
        const formPassword = document.getElementById("formPassword")
        const inputPassword = document.getElementById("newPassword")
        const inputRepetPassword = document.getElementById("renewPassword")

        if (inputPassword.value !== inputRepetPassword.value) {
            inputRepetPassword.classList.add("is-invalid")
            Swal.fire("Campos invalidos!", "Las contraseñas no coinciden, por favor revise los campos y vuelva a intentarlo", "warning")
            return
        }

        if (!isPasswordValid(inputPassword.value)) {
            inputPassword.classList.add("is-invalid")
            inputRepetPassword.classList.add("is-invalid")
            Swal.fire("Contraseña invalida!", "La contraseña no cumple con los caracteres pedidos.", "warning")
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
            if (!result.isConfirmed) return

            // Cambio la contraseña
            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(formPassword),
                    }
                )
                .then(res => res.json())
                .then(response => {
                    const {
                        title,
                        message,
                        type,
                        status
                    } = response

                    Swal.fire(title, message, type).then(res => {
                        if (status == "OK") {
                            formPassword.reset()
                        }
                    })

                })
        });



    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
