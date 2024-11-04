<?
require_once __DIR__ . "/../../config/init.php";

$section = "consultas";
$subSection = "Nueva consulta";

$titlePage = "Nueva consulta";
$iconPage = "fa fa-plus";

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">

            <form id="form" class="g-3">

                <h5 class="card-title m-0"><i class="<?= $menu->{$section}->icon ?> me-1"></i>Datos de la consulta</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" name="asunto" id="asunto" oninput="FormController.validateForm(this, 3)">
                    </div>

                    <div class="col-md-3">
                        <label for="idCliente" class="form-label">Cliente</label>
                        <div class="input-group">
                            <select name="idCliente" id="idCliente" class="form-select" oninput="FormController.validateForm(this)">
                                <option value=""></option>

                            </select>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="tooltip" title="Agregar" onclick="openModalNewClient()"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="idUsuario" class="form-label">Asignado</label>
                        <div class="input-group">
                            <select name="idUsuario" id="idUsuario" class="form-select" oninput="FormController.validateForm(this)">
                                <option value=""></option>
                                <? foreach (Usuario::getAll(["where" => "estado = 'A'", "order" => "nombre ASC"]) as $usuario): ?>
                                    <option value="<?= $usuario->idUsuario ?>"><?= ucfirst($usuario->nombre) . " " . ucfirst($usuario->apellido) ?></option>
                                <? endforeach; ?>
                            </select>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="tooltip" title="Agregar" onclick="openModalNewUsuario()"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="idOrigen" class="form-label">Origen</label>
                        <div class="input-group">
                            <select name="idOrigen" id="idOrigen" class="form-select" oninput="FormController.validateForm(this)">
                                <option value=""></option>
                                <? foreach (Origen::getAll(["where" => "estado = 'A'", "order" => "nombre ASC"]) as $origen): ?>
                                    <option value="<?= $origen->idOrigen ?>"><?= ucfirst($origen->nombre) ?></option>
                                <? endforeach; ?>
                            </select>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="tooltip" title="Agregar" onclick="openModalNewOrigen()"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="idPaquete" class="form-label">Excursión</label>
                        <select name="idPaquete" id="idPaquete" class="form-select" oninput="FormController.validateForm(this)" onchange="handlerChangePaquete(this)">
                            <option value=""></option>
                            <? foreach (Paquete::getAll(["where" => "estado = 'A'", "order" => "nombre ASC"]) as $paquete): ?>
                                <option value="<?= $paquete->idPaquete ?>"><?= ucfirst($paquete->titulo) ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="idPaqueteFechaSalida" class="form-label">Fecha de salida</label>
                        <select name="idPaqueteFechaSalida" id="idPaqueteFechaSalida" class="form-select" oninput="FormController.validateForm(this)">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="traslado" class="form-label">Con traslado desde el alojamiento</label>
                        <select name="traslado" id="traslado" class="form-select" oninput="FormController.validateForm(this)">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="idAlojamiento" class="form-label">Alojamiento</label>
                        <div class="input-group">
                            <select name="idAlojamiento" id="idAlojamiento" class="form-select">
                                <option value=""></option>
                            </select>
                            <button class="btn btn-success btn-sm" type="button" data-bs-toggle="tooltip" title="Agregar" onclick="openModalNewAlojamiento()"><i class="fa fa-plus"></i></button>
                        </div>
                        <small class="text-secondary">Obligatorio si elige traslado</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <h5 class="card-title m-0"><i class="<?= $menu->clientes->icon ?> me-1"></i>Pasajeros</h5>

                        <div class="contentPax"></div>

                        <button class="btn btn-success btn-sm" type="button" onclick="handlerBtnNewPax('.contentPax')"><i class="fa fa-plus me-1"></i>Nuevo pasajero</button>
                    </div>

                    <div class="col-md-4">
                        <h5 class="card-title m-0"><i class="bi bi-chat-dots me-1"></i>Datos de contacto adicionales <i class="bi bi-info-circle-fill text-dark" data-bs-toggle="tooltip" title="Puede utilizar esta sección para agregar, por ejemplo, redes sociales. Ejemplo: Instagram - @juanPerez11"></i></h5>

                        <div class="contentContactosAdicionales"></div>

                        <button class="btn btn-success btn-sm" type="button" onclick="handlerBtnNewContactoAdicional('.contentContactosAdicionales')"><i class="fa fa-plus me-1"></i>Nuevo contacto</button>
                    </div>
                </div>


                <input type="hidden" name="estado" value="A">

                <input type="hidden" name="idConsulta" value="">
                <input type="hidden" name="pk" value="idConsulta">
                <input type="hidden" name="table" value="consultas">
                <input type="hidden" name="action" value="consulta_create">

                <div class="mt-4">
                    <button type="button" class="btn btn-primary" onclick="handlerSaveForm(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                    <a class="btn btn-secondary" href="<?= DOMAIN_ADMIN ?>consultas"><i class="fa fa-times-circle me-1"></i>Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</section>

<!-- MODAL | New cliente -->
<div class="modal fade" id="modalNewClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="<?= $menu->clientes->icon ?> me-1"></i>Nuevo cliente</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formNewClient" class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" oninput="FormController.validateForm(this, 3)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" name="apellido" oninput="FormController.validateForm(this, 3)">
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" oninput="FormController.validateForm(this)" autocomplete="new-email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña<i class="fa fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="La contraseña debe tener como mínimo 8 digitos, 1 mayúscula, 1 minúscula y 1 caracter especial"></i></label>
                        <input type="password" class="form-control" name="password" oninput="validatePassword(this)" autocomplete="new-password">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Teléfono</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" name="codPais" oninput="FormController.validateForm(this)" style="width:20%">
                            <input type="tel" class="form-control" name="codArea" oninput="FormController.validateForm(this)" style="width:20%">
                            <input type="tel" class="form-control" name="telefono" oninput="FormController.validateForm(this)" style="width:60%">
                        </div>
                        <small class="text-secondary">Cod país + Cod área + Número de teléfono</small>
                    </div>



                    <div class="col-md-6">
                        <labelclass="form-label">DNI</label>
                        <input type="dni" class="form-control" name="dni" oninput="FormController.validateForm(this, 8)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" name="fechaDeNacimiento" oninput="FormController.validateForm(this)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nacionalidad</label>
                        <select name="nacionalidad" class="form-select" oninput="FormController.validateForm(this)">
                            <option value="">-- Seleccione un país --</option>
                            <? foreach (DB::getAll("SELECT * FROM paises ORDER BY nombre") as $pais): ?>
                                <option value="<?= $pais->id ?>"><?= ucfirst($pais->nombre) ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sexo</label>
                        <select name="sexo" class="form-select" oninput="FormController.validateForm(this)">
                            <option value="">-- Seleccione una opción --</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>



                    <input type="hidden" name="idCliente" value="">
                    <input type="hidden" name="pk" value="idCliente">
                    <input type="hidden" name="table" value="clientes">
                    <input type="hidden" name="action" value="cliente_save">

                    <hr>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary" onclick="handlerSubmitModalNewClient(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                        <button type="button" class="btn btn-secondary" onclick="modalNewClient.hide()"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- MODAL | New Usuario -->
<div class="modal fade" id="modalNewUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="<?= $menu->usuarios->icon ?> me-1"></i>Nuevo Usuario</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formNewUsuario" class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" oninput="FormController.validateForm(this, 3)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" name="apellido" oninput="FormController.validateForm(this, 3)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" oninput="FormController.validateForm(this)" autocomplete="new-email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña<i class="fa fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="La contraseña debe tener como mínimo 8 digitos, 1 mayúscula, 1 minúscula y 1 caracter especial"></i></label>
                        <input type="password" class="form-control" name="password" oninput="validatePassword(this)" autocomplete="new-password">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Teléfono</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" name="codPais" oninput="FormController.validateForm(this)" style="width:20%">
                            <input type="tel" class="form-control" name="codArea" oninput="FormController.validateForm(this)" style="width:20%">
                            <input type="tel" class="form-control" name="telefono" oninput="FormController.validateForm(this)" style="width:60%">
                        </div>
                        <small class="text-secondary">Cod país + Cod área + Número de teléfono</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Rol del usuario</label>
                        <select name="tipo" class="form-select" oninput="FormController.validateForm(this)">
                            <? if (Auth::isAdmin()): ?>
                                <option value="">-- Seleccione un rol --</option>
                                <option value="0">Administrador</option>
                            <? endif; ?>
                            <option value="1">Vendedor</option>
                        </select>
                    </div>


                    <input type="hidden" name="idUsuario" value="">
                    <input type="hidden" name="pk" value="idUsuario">
                    <input type="hidden" name="table" value="usuarios">
                    <input type="hidden" name="action" value="usuario_save">

                    <hr>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary" onclick="handlerSubmitModalNewUsuario(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                        <button type="button" class="btn btn-secondary" onclick="openModalNewUsuario.hide()"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- MODAL | New Origen -->
<div class="modal fade" id="modalNewOrigen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="<?= $menu->origenes->icon ?> me-1"></i>Nuevo Origen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formNewOrigen" class="row g-3">

                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" oninput="FormController.validateForm(this, 3)">
                    </div>

                    <input type="hidden" name="idOrigen" value="">
                    <input type="hidden" name="pk" value="idOrigen">
                    <input type="hidden" name="table" value="origenes">
                    <input type="hidden" name="action" value="save">

                    <hr>

                    <div class="col-12">
                        <button type="button" class="btn btn-primary" onclick="handlerSubmitModalNewOrigen(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                        <button type="button" class="btn btn-secondary" onclick="modalNewOrigen.hide()"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- MODAL | New Alojamiento -->
<div class="modal fade" id="modalNewAlojamiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="<?= $menu->alojamientos->icon ?> me-1"></i>Nuevo Alojamiento</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <iframe
                            id="mapIframe"
                            src="<?= DOMAIN_ADMIN ?>alojamientos/map.iframe.php?handlerNewMarker=mapbox__handlerNewMarker&handlerDeleteMarker=mapbox__handlerDeleteMarker&v=<?= date("Ymd_His", filemtime(PATH_SERVER . 'admin/alojamientos/map.iframe.php')) ?>?>"
                            frameborder="0"
                            class="w-100"
                            style="height: 500px;">
                        </iframe>
                    </div>
                    <div class="col-md-4">
                        <form action="" class="row" id="formNewAlojamiento">

                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" oninput="FormController.validateForm(this, 3)">
                                    <label for="nombre"><i class="<?= $menu->{$section}->icon ?> me-1"></i>Nombre</label>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control text-secondary" name="direccion" placeholder="Dirección" oninput="FormController.validateForm(this, 3)" readonly>
                                    <label for="direccion"><i class="bi bi-signpost me-1"></i>Dirección</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control text-secondary" name="longitud" placeholder="Longitud" readonly oninput="FormController.validateForm(this)">
                                    <label for="longitud"><i class="bi bi-globe-americas me-1"></i>Longitud</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control text-secondary" name="latitud" placeholder="Latitud" readonly oninput="FormController.validateForm(this)">
                                    <label for="latitud"><i class="bi bi-globe-americas me-1"></i>Latitud</label>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Descripción (opcional)" name="descripcion" style="height: 150px"></textarea>
                                    <label for="descripcion"><i class="bi bi-text-left me-1"></i>Descripción (opcional)</label>
                                </div>
                            </div>

                            <input type="hidden" name="idAlojamiento" value="">
                            <input type="hidden" name="table" value="alojamientos">
                            <input type="hidden" name="pk" value="idAlojamiento">
                            <input type="hidden" name="action" value="save">

                            <div class="col-12">
                                <button type="button" class="btn btn-primary" onclick="handlerSubmitModalNewAlojamiento(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                                <button type="button" class="btn btn-secondary" onclick="modalNewAlojamiento.hide()"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let modalNewClient = null
    let modalNewUsuario = null
    let modalNewOrigen = null
    let modalNewAlojamiento = null

    const form = document.getElementById("form")

    document.addEventListener("DOMContentLoaded", e => {
        triggerInputs()

        // Complete selects
        fetchClientes()
        fetchUsuarios()
        fetchOrigenes()
        fetchAlojamientos()

        modalNewClient = new bootstrap.Modal(document.getElementById('modalNewClient'))
        modalNewUsuario = new bootstrap.Modal(document.getElementById('modalNewUsuario'))
        modalNewOrigen = new bootstrap.Modal(document.getElementById('modalNewOrigen'))
        modalNewAlojamiento = new bootstrap.Modal(document.getElementById('modalNewAlojamiento'))
    })

    function triggerInputs() {
        // General
        HTMLController.trigger("#asunto,#idCliente,#idUsuario,#idOrigen,#idPaquete,#idPaqueteFechaSalida,#traslado", "input")

        // Pasajeros
        HTMLController.trigger(".itemPaxNombre,.itemPaxApellido,.itemPaxSexo,.itemPaxFechaDeNacimiento", "input")

        // Contactos
        HTMLController.trigger(".itemContactoAdicionalOrigen, .itemContactoAdicionalContacto", "input")
    }

    function handlerSaveForm(elBtn) {

        triggerInputs()

        // Valido el formulario
        if (document.querySelectorAll(".is-invalid").length > 0) {
            Swal.fire("Campos invalidos!", "Revise los campos marcados en rojo para continuar", "warning")
            return
        }

        if (document.getElementById("traslado").value === "1" && document.getElementById("idAlojamiento").value === "") {
            Swal.fire("Sin alojamiento!", "Recuerde que si elige traslado debe indicar el alojamiento del cliente de forma obligatoria.", "warning")
            return
        }

        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then(result => {

            // Rechazo la eliminación
            if (!result.isConfirmed) return

            let btnSubmit = new FormButtonSubmitController(elBtn, false)
            btnSubmit.init()

            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(form)
                    }
                )
                .then(res => res.json())
                .then(({
                    status,
                    title,
                    message,
                    type
                }) => {
                    btnSubmit.reset()

                    Swal.fire(title, message, type).then(res => {
                        if (status == "OK") HTTP.redirect("<?= DOMAIN_ADMIN ?>consultas/")
                    })
                })
        })

    }

    /* ----------------------------- */
    /*                               */
    /*      FETCH OPTIONS SELECTS    */
    /*                               */
    /* ----------------------------- */
    function fetchClientes(idCliente = null) {
        fetch('<?= DOMAIN_ADMIN ?>process?action=cliente_consulta_getAll')
            .then(res => res.json())
            .then(result => {
                const elemSelect = document.getElementById("idCliente")
                let htmlOptions = `<option value=""></option>`

                for (const cliente of result.results) {
                    const selected = cliente.idCliente == idCliente ? "selected" : ""
                    htmlOptions += `<option value="${cliente.idCliente}" ${selected}>
                        ${cliente.nombre} ${cliente.apellido} | DNI: ${cliente.dni}
                    </option>`
                }

                elemSelect.innerHTML = htmlOptions
                HTMLController.trigger("#idCliente", "input")
            })
    }

    function fetchUsuarios(idUsuario = null) {
        fetch('<?= DOMAIN_ADMIN ?>process?action=usuario_consulta_getAll')
            .then(res => res.json())
            .then(result => {
                const elemSelect = document.getElementById("idUsuario")
                let htmlOptions = `<option value=""></option>`

                for (const usuario of result.results) {
                    const selected = usuario.idUsuario == idUsuario ? "selected" : ""
                    htmlOptions += `<option value="${usuario.idUsuario}" ${selected}>${usuario.nombre} ${usuario.apellido} | ${(usuario.tipo == 0 ? 'ADMIN' : 'VENDEDOR')}</option>`
                }

                elemSelect.innerHTML = htmlOptions
                HTMLController.trigger("#idUsuario", "input")
            })
    }

    function fetchOrigenes(idOrigen = null) {
        fetch('<?= DOMAIN_ADMIN ?>process?action=origen_consulta_getAll')
            .then(res => res.json())
            .then(result => {
                const elemSelect = document.getElementById("idOrigen")
                let htmlOptions = `<option value=""></option>`

                for (const origen of result.results) {
                    const selected = idOrigen == origen.idOrigen ? "selected" : ""
                    htmlOptions += `<option value="${origen.idOrigen}" ${selected}>${origen.nombre}</option>`
                }

                elemSelect.innerHTML = htmlOptions
                HTMLController.trigger("#idOrigen", "input")
            })
    }

    function fetchAlojamientos(idAlojamiento = null) {
        fetch('<?= DOMAIN_ADMIN ?>process?action=alojamiento_consulta_getAll')
            .then(res => res.json())
            .then(result => {
                const elemSelect = document.getElementById("idAlojamiento")
                let htmlOptions = `<option value=""></option>`

                for (const alojamiento of result.results) {
                    const selected = idAlojamiento == alojamiento.idAlojamiento ? "selected" : ""
                    htmlOptions += `<option value="${alojamiento.idAlojamiento}" ${selected}>${alojamiento.nombre}</option>`
                }

                elemSelect.innerHTML = htmlOptions
                HTMLController.trigger("#idAlojamiento", "input")
            })
    }

    function handlerChangePaquete(element) {
        fetch('<?= DOMAIN_ADMIN ?>process?action=paquete_getFechasByIdPaquete&id=' + element.value)
            .then(res => res.json())
            .then(result => {
                const selectFechaSalida = document.getElementById("idPaqueteFechaSalida")
                let htmlOptions = `<option value=""></option>`

                for (const {
                        id,
                        fecha
                    }
                    of result.fechas) {
                    const [anio, mes, dia] = fecha.split("-")
                    htmlOptions += `<option value="${id}">${dia}/${mes}/${anio}</option>`
                }

                selectFechaSalida.innerHTML = htmlOptions

                HTMLController.trigger("#idPaqueteFechaSalida", "input")
            })
    }

    /* ---------------------- */
    /*                        */
    /*          MODALS        */
    /*                        */
    /* ---------------------- */
    function validatePassword(element) {
        element.classList.remove("is-invalid")

        if (isPasswordValid(element.value)) element.classList.remove("is-invalid")
        else element.classList.add("is-invalid")
    }

    // Cliente
    function openModalNewClient() {
        // Reset inputs
        document.getElementById("formNewClient").reset()

        // Valido los inputs
        HTMLController.trigger("#formNewClient input, #formNewClient select", "input")

        // Muestro el modal
        modalNewClient.show()
    }
    function handlerSubmitModalNewClient(elBtnSubmit) {
        if(document.querySelectorAll("#formNewClient .is-invalid").length > 0){
            Swal.fire("Campos incorrectos!", "Revise todos los campos marcados en rojo.", "warning")
            return
        }

        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            const btnSubmit = new FormButtonSubmitController(elBtnSubmit, false)
            btnSubmit.init()

            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(document.getElementById("formNewClient"))
                    }
                )
                .then(res => res.json())
                .then(({
                    status,
                    title,
                    message,
                    type,
                    idCliente
                }) => {
                    btnSubmit.reset()

                    Swal.fire(title, message, type).then(res => {
                        if (status == "OK") {
                            fetchClientes(idCliente) // Actualizo el selector
                            modalNewClient.hide()
                        }
                    })
                })
        });
    }

    // Usuario
    function openModalNewUsuario() {
        document.getElementById("formNewUsuario").reset()

        // Valido los inputs
        HTMLController.trigger("#formNewUsuario input, #formNewUsuario select", "input")

        // Reset inputs
        modalNewUsuario.show()
    }
    function handlerSubmitModalNewUsuario(elBtnSubmit) {
        if(document.querySelectorAll("#formNewUsuario .is-invalid").length > 0){
            Swal.fire("Campos incorrectos!", "Revise todos los campos marcados en rojo.", "warning")
            return
        }
        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            const btnSubmit = new FormButtonSubmitController(elBtnSubmit, false)
            btnSubmit.init()

            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(document.getElementById("formNewUsuario"))
                    }
                )
                .then(res => res.json())
                .then(({
                    status,
                    title,
                    message,
                    type,
                    idUsuario
                }) => {
                    btnSubmit.reset()

                    Swal.fire(title, message, type).then(res => {
                        if (status == "OK") {
                            fetchUsuarios(idUsuario) // Actualizo el selector
                            modalNewUsuario.hide()
                        }
                    })
                })
        });
    }

    // Origen
    function openModalNewOrigen() {
        document.getElementById("formNewOrigen").reset()

        // Valido los inputs
        HTMLController.trigger("#formNewOrigen input", "input")

        // Reset inputs
        modalNewOrigen.show()
    }
    function handlerSubmitModalNewOrigen(elBtnSubmit) {
        if(document.querySelectorAll("#formNewOrigen .is-invalid").length > 0){
            Swal.fire("Campos incorrectos!", "Revise todos los campos marcados en rojo.", "warning")
            return
        }
        
        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            const btnSubmit = new FormButtonSubmitController(elBtnSubmit, false)
            btnSubmit.init()

            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(document.getElementById("formNewOrigen"))
                    }
                )
                .then(res => res.json())
                .then(({
                    status,
                    title,
                    message,
                    type,
                    pk
                }) => {
                    btnSubmit.reset()

                    Swal.fire(title, message, type).then(res => {
                        if (status == "OK") {
                            fetchOrigenes(pk) // Actualizo el selector
                            modalNewOrigen.hide()
                        }
                    })
                })
        });
    }

    // Alojamiento
    function openModalNewAlojamiento() {
        // Borro el marker en caso de que tenga
        document.getElementById("mapIframe").contentWindow.deleteMarker()

        document.getElementById("formNewAlojamiento").reset()

        // Valido los inputs
        HTMLController.trigger("#formNewAlojamiento input", "input")

        // Reset inputs
        modalNewAlojamiento.show()
    }
    function handlerSubmitModalNewAlojamiento(elBtnSubmit) {

        if(document.querySelectorAll("#formNewAlojamiento .is-invalid").length > 0){
            Swal.fire("Campos incorrectos!", "Revise todos los campos marcados en rojo.", "warning")
            return
        }

        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            const btnSubmit = new FormButtonSubmitController(elBtnSubmit, false)
            btnSubmit.init()

            fetch(
                    "<?= DOMAIN_ADMIN ?>process.php", {
                        method: "POST",
                        body: new FormData(document.getElementById("formNewAlojamiento"))
                    }
                )
                .then(res => res.json())
                .then(({
                    status,
                    title,
                    message,
                    type,
                    pk
                }) => {
                    btnSubmit.reset()

                    Swal.fire(title, message, type).then(res => {
                        if (status == "OK") {
                            fetchAlojamientos(pk) // Actualizo el selector
                            modalNewAlojamiento.hide()
                        }
                    })
                })
        });
    }
    // Se llama a esta funcion en el iframe del mapa
    function mapbox__handlerNewMarker(data){
        console.log(data)
        document.querySelector("#formNewAlojamiento input[name='nombre']").value = data.name
        document.querySelector("#formNewAlojamiento input[name='direccion']").value = data.direction
        document.querySelector("#formNewAlojamiento input[name='latitud']").value = data.coordinates.lat
        document.querySelector("#formNewAlojamiento input[name='longitud']").value = data.coordinates.lng
        HTMLController.trigger("#formNewAlojamiento input", "input")
    }
    function mapbox__handlerDeleteMarker(){
        document.querySelector("#formNewAlojamiento input[name='nombre']").value = ""
        document.querySelector("#formNewAlojamiento input[name='direccion']").value = ""
        document.querySelector("#formNewAlojamiento input[name='latitud']").value = ""
        document.querySelector("#formNewAlojamiento input[name='longitud']").value = ""
        HTMLController.trigger("#formNewAlojamiento input", "input")
    }



    /* --------------------------------------------- */
    /*                                               */
    /*          Datos de contacto adicional          */
    /*                                               */
    /* --------------------------------------------- */
    function handlerBtnNewContactoAdicional(selectElContent) {
        const content = document.querySelector(selectElContent)
        content.insertAdjacentHTML("beforeend", consulta_htmlNewContactoAdicional())
    }

    function consulta_htmlNewContactoAdicional(id = "") {
        const idContact = id ? id : (new Date).getTime()

        const idContent = `datoDeContactoAdicional__item-${idContact}`

        return `
            <div class="input-group mb-2" id="${idContent}">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar" onclick="HTMLController.deleteParentElement(this, '#${idContent}')"><i class="fa fa-trash"></i></button>

                <input type="text" name="contactoAdicional[descripcion][]" class="form-control form-control-sm itemContactoAdicionalOrigen" oninput="FormController.validateForm(this)" placeholder="Origen del contacto">

                <input type="text" name="contactoAdicional[contacto][]" class="form-control form-control-sm itemContactoAdicionalContacto" oninput="FormController.validateForm(this)" placeholder="Contacto">
            </div>
        `
    }
    /* --------------------------------- */
    /*                                   */
    /*              Pasajeros            */
    /*                                   */
    /* --------------------------------- */
    function handlerBtnNewPax(selectElContent) {
        const content = document.querySelector(selectElContent)
        content.insertAdjacentHTML("beforeend", consulta_htmlNewPax())
    }

    function consulta_htmlNewPax(id = "") {
        const idPax = id ? id : (new Date).getTime()

        const idContent = `pax__item-${idPax}`

        return `
            <div class="row mb-2" id="${idContent}">
                <div class="col-md-3 mb-2">
                    <label for="">Nombre</label>
                    <input type="text" name="pax[nombre][]" class="form-control form-control-sm itemPaxNombre" oninput="FormController.validateForm(this)">
                </div>
                <div class="col-md-3 mb-2">
                    <label for="">Apellido</label>
                    <input type="text" name="pax[apellido][]" class="form-control form-control-sm itemPaxApellido" oninput="FormController.validateForm(this)">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="">Sexo</label>
                    <select name="pax[sexo][]" class="form-select form-select-sm itemPaxSexo" oninput="FormController.validateForm(this)">
                        <option value=""></option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label for="">Fecha de nacimiento</label>
                    <input type="date" name="pax[fechaDeNacimiento][]" class="form-control form-control-sm itemPaxFechaDeNacimiento" oninput="FormController.validateForm(this)">
                </div>
                <div class="col-12 mb-2">
                    <label for="">Observaciones</label>
                    <textarea name="pax[observacion][]" class="form-control form-control-sm"></textarea>
                </div>
                <div class="col-12 d-grid gap-1">
                    <button class="btn btn-danger btn-sm" type="button" onclick="HTMLController.deleteParentElement(this, '#${idContent}')"><i class="fa fa-trash me-1"></i>Eliminar</button>
                </div>
            </div>
        `
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
