<?
require_once __DIR__ . "/../../config/init.php";

$section = "consultas";

$titlePage = "Consulta #". str_pad($_GET['id'], 5, "0", STR_PAD_LEFT);
$consulta = Consulta::getAllInfo($_GET["id"]);
$asignado = $consulta->asignado;
$cliente = $consulta->cliente;
$paquete = $consulta->paquete;
$fechasSalida = $consulta->paquete->fechasSalida;
$datosDeContactoAdicional = $consulta->contactosAdicionales;
$pasajeros = $consulta->pasajeros;

$iconsUserMessage = array(
    "C" => $menu->clientes->icon,
    "U" => $menu->usuarios->icon,
    "S" => "fa fa-server",
);

$isConsultaAbierta = $consulta->estado == "A";

$title = $titlePage . " | " . APP_NAME;
ob_start();
?>

<section class="section row">

    <!-- -------------------- -->
    <!--        DETALLE       -->
    <!-- -------------------- -->
    <div class="col-md-4 col-lg-3">
        <div class="card">
            <div class="card-body">

                <!-- ACCORDION | cliente, excursión, pax -->
                <div class="accordion accordion-flush" id="accordionConsulta">

                    <!-- CONSULTA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingConsulta">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalseConsulta" aria-expanded="false" aria-controls="flush-coppalseConsulta">
                                <h5 class="card-title"><?= $titlePage ?></h5>
                            </button>
                        </h2>
                        <div id="flush-coppalseConsulta" class="accordion-collapse collapse show" aria-labelledby="flush-headingConsulta" data-bs-parent="#accordionConsulta">
                            <? if ($isConsultaAbierta): ?>
                                <form class="accordion-body" id="formConsulta">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control" name="asunto" placeholder="Asunto" oninput="FormController.validateForm(this, 3)" value="<?= $consulta->asunto ?>">
                                        <label>Asunto</label>
                                    </div>
    
                                    <div class="form-floating mb-2">
                                        <select class="form-select" id="idUsuario" name="idUsuario" oninput="FormController.validateForm(this)">
                                            <? foreach (Usuario::getAll(["where" => "estado = 'A'", "order" => "nombre"]) as $usuario): ?>
                                                <option 
                                                    value="<?= $usuario->idUsuario ?>" 
                                                    <?= $consulta->idUsuario == $usuario->idUsuario ? "selected" : "" ?> 
                                                    data-nombre="<?= ucfirst($usuario->nombre) ?> <?=ucfirst($usuario->apellido)?>" 
                                                >
                                                    <?= ucfirst($usuario->nombre) ?> <?=ucfirst($usuario->apellido)?> | <?=$usuario->tipo == 0 ? "ADMIN" : "VENDEDOR"?>
                                                </option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idUsuario">Asignado</label>
                                    </div>
    
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="idOrigen" name="idOrigen" oninput="FormController.validateForm(this)">
                                            <? foreach (Origen::getAll(["where" => "estado = 'A'", "order" => "nombre"]) as $origen): ?>
                                                <option value="<?= $origen->idOrigen ?>" <?= $consulta->idOrigen == $origen->idOrigen ? "selected" : "" ?>><?= ucfirst($origen->nombre) ?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idOrigen">Origen</label>
                                    </div>
    
                                    <? if ($paquete->traslado == 1): ?>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="traslado" name="traslado" oninput="FormController.validateForm(this)">
                                            <option value="0" <?= $consulta->traslado == "0" ? "selected" : "" ?>>No</option>
                                            <option value="1" <?= $consulta->traslado == "1" ? "selected" : "" ?>>Si</option>
                                        </select>
                                        <label for="traslado">Traslado</label>
                                    </div>
                                    <? else: ?>
                                        <!-- El paquete no permite traslados -->
                                        <input type="hidden" name="traslado" value="0">
                                    <? endif; ?>
    
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="idAlojamiento" name="idAlojamiento">
                                            <option value="">-- Sin alojamiento --</option>
                                            <? foreach (Alojamiento::getAll(["order" => "nombre"]) as $alojamiento): ?>
                                                <option value="<?= $alojamiento->idAlojamiento ?>" <?= $consulta->idAlojamiento == $alojamiento->idAlojamiento ? "selected" : "" ?>><?= ucfirst($alojamiento->nombre) ?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idAlojamiento">Alojamiento</label>
                                    </div>
                                    
                                    <input type="hidden" name="table" value="consultas">
                                    <input type="hidden" name="pk" value="idConsulta">
                                    <input type="hidden" name="idConsulta" value="<?=$_GET['id']?>">
                                    <input type="hidden" name="action" value="save">
    
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-success btn-sm" onclick="handlerUpdateConsulta(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                                    </div>
                                </form>
                            <? else: ?>
                                <div class="accordion-body">
                                    <p class=""><b>Asunto</b> <br><?=$consulta->asunto?></p>
                                    <p class=""><b>Asignado</b> <br><?=ucfirst($asignado->nombre)?> <?=ucfirst($asignado->apellido)?></p>
                                    <p class=""><b>Origen</b> <br><?=$consulta->origen->nombre?></p>
                                    <p class=""><b>Traslado</b> <br><?=$consulta->traslado ? "Si" : "No"?></p>
                                    <p class=""><b>Alojamiento</b> <br><?=isset($consulta->alojamiento->nombre) ? $consulta->alojamiento->nombre : "-"?></p>

                                    <? if ($consulta->estado == "V"): ?>
                                        <hr>
                                        <div class="text-center">
                                            <p class="display-5 fs-2 m-0">$<?=Util::numberToPrice($consulta->total)?></p>
                                            <p>Precio total</p>
                                        </div>
                                    <? endif; ?>

                                </div>
                            <? endif; ?>
                        </div>
                    </div>

                    <!-- CLIENTE -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingContacto">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalseContacto" aria-expanded="false" aria-controls="flush-coppalseContacto">
                                <h5 class="card-title p-0"><i class="me-1 <?=$menu->clientes->icon?>"></i>Cliente</h5>
                            </button>
                        </h2>
                        <div id="flush-coppalseContacto" class="accordion-collapse collapse" aria-labelledby="flush-headingContacto" data-bs-parent="#accordionConsulta">
                            <div class="accordion-body">
                                <p class="m-0"><b>Nombre:</b> <?=ucfirst($cliente->nombre)?> <?=ucfirst($cliente->apellido)?></p>
                                <p class="m-0"><b>DNI:</b> <?=$cliente->dni?></p>
                                <p class="m-0"><b>Nacionalidad:</b> <?=$cliente->nacionalidad?></p>
                                <p class="m-0"><b>Sexo:</b> <?=$cliente->sexo == "M" ? "Masculino" : "Femenino"?></p>
                                <p class="m-0"><b>Fecha de nacimiento:</b> <?=date("d/m/Y", strtotime($cliente->fechaDeNacimiento))?> (<?=Util::dateToAge($cliente->fechaDeNacimiento)?> años)</p>
                                <p class="m-0"><b>E-mail:</b> <?=$cliente->email?></p>
                                <p class=""><b>Teléfono:</b> +<?=$cliente->codPais?> <?=$cliente->codArea?> <?=$cliente->telefono?></p>
                                
                                <h5>Datos de contacto adicional</h5>
                            
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <tbody id="tableDatosAdicionales__tbody">
                                            <? foreach ($datosDeContactoAdicional as $contacto): ?>
                                                <tr>
                                                    <td style="width: 10%" class="text-center">
                                                        <a 
                                                            href="javascript:;" 
                                                            class="text-danger" 
                                                            onclick="handlerDeleteDatoDeContacto(<?=$contacto->idContactoAdicional?>, this)"
                                                            data-bs-target="tooltip"
                                                            title="Eliminar"
                                                        >
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                    <td><?=$contacto->descripcion?></td>
                                                    <td><?=$contacto->contacto?></td>
                                                </tr>
                                            <? endforeach; ?>

                                            <!-- New row -->
                                            <tr id="tableDatosAdicionales__rowNewContacto">
                                                <td style="width: 10%" class="text-center align-middle">
                                                    <a 
                                                        href="javascript:;" 
                                                        class="text-success" 
                                                        onclick="handlerBtnNewContactoAdicional()"
                                                        data-bs-target="tooltip"
                                                        title="Agregar contacto"
                                                    >
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <input type="text" id="inputNewDatoContacto_origen" class="form-control form-control-sm is-invalid inputNewDatoContacto" placeholder="Origen" oninput="FormController.validateForm(this, 3)">
                                                </td>
                                                <td>
                                                    <input type="text" id="inputNewDatoContacto_contacto" class="form-control form-control-sm is-invalid inputNewDatoContacto" placeholder="Contacto" oninput="FormController.validateForm(this, 3)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PAX -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingPax">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalsePax" aria-expanded="false" aria-controls="flush-coppalsePax">
                                <h5 class="card-title p-0"><i class="me-1 bi bi-person-add"></i>Pasajeros <?=!$isConsultaAbierta ? "(".count($pasajeros).")" : ""?></h5>
                            </button>
                        </h2>
                        <div id="flush-coppalsePax" class="accordion-collapse collapse" aria-labelledby="flush-headingPax" data-bs-parent="#accordionConsulta">
                            <div class="accordion-body">
                                
                                <ul class="list-group">
                                    <? foreach ($pasajeros as $pasajero): ?>
                                        <li class="list-group-item">
                                            <div class=" d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold"><?=ucfirst($pasajero->nombre)?> <?=ucfirst($pasajero->apellido)?></div>
                                                    <span class="text-secondary"><?=$pasajero->sexo == "M" ? "Masculino" : "Femenino"?></span>
                                                    <? if ($pasajero->observaciones): ?>
                                                        <p class="m-0 p-0 mt-1">
                                                            <?=$pasajero->observaciones?>
                                                        </p>
                                                    <? endif; ?>
                                                </div>
                                                <span class="badge bg-primary rounded-pill"><?=Util::dateToAge($pasajero->fechaDeNacimiento)?> años</span>
                                            </div>

                                            <? if ($isConsultaAbierta): ?>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-danger btn-sm mt-1" onclick="handlerDeletePasajero(<?=$pasajero->idPasajero?>, this)"><i class="fa fa-trash me-1"></i>Eliminar</button>
                                                </div>
                                            <? endif; ?>
                                        </li>
                                    <? endforeach; ?>

                                    <? if ($isConsultaAbierta): ?>
                                        <li class="list-group-item" id="accordionPax_liNewPax">
                                            <form method="POST" id="formNewPasajero" class="row">
                                                <div class="col-12 my-1">
                                                    <h5 class="m-0 p-0"><i class="fa fa-plus me-1"></i>Agregar pasajero</h5>
                                                </div>
                                                <div class="col-md-12 mb-1">
                                                    <label for="pax_nombre">Nombre</label>
                                                    <input type="text" id="pax_nombre" name="nombre" class="form-control form-control-sm is-invalid" oninput="FormController.validateForm(this, 3)" placeholder="...">
                                                </div>
                                                <div class="col-md-12 mb-1">
                                                    <label for="pax_apellido">Apellido</label>
                                                    <input type="text" id="pax_apellido" name="apellido" class="form-control form-control-sm is-invalid" oninput="FormController.validateForm(this, 3)" placeholder="...">
                                                </div>

                                                <div class="col-12 mb-1">
                                                    <label for="pax_fechaDeNacimiento">Fecha de nacimiento</label>
                                                    <input type="date" id="pax_fechaDeNacimiento" name="fechaDeNacimiento" class="form-control form-control-sm is-invalid" oninput="FormController.validateForm(this)">
                                                </div>

                                                <div class="col-12 mb-2">
                                                    <label for="pax_observaciones">Observaciones</label>
                                                    <textarea id="pax_observaciones" name="observaciones" class="form-control"></textarea>
                                                </div>

                                                <input type="hidden" name="idConsulta" value="<?=$_GET["id"]?>">
                                                <input type="hidden" name="table" value="consulta_pasajeros">
                                                <input type="hidden" name="action" value="save">

                                                <div class="col-12 d-grid">
                                                    <button type="button" class="btn btn-success btn-sm" onclick="handlerBtnNewPax()"><i class="fa fa-plus me-1"></i>Agregar</button>
                                                </div>
                                            </form>
                                        </li>
                                    <? endif; ?>
                                </ul>
                                
                                
                            </div>
                        </div>
                    </div>

                    <!-- EXCURSIÓN -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingPaquete">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsePaquete" aria-expanded="false" aria-controls="flush-collapsePaquete">
                                <h5 class="card-title p-0"><i class="me-1 <?=$menu->excursiones->icon?>"></i>Excursión</h5>
                            </button>
                        </h2>
                        <div id="flush-collapsePaquete" class="accordion-collapse collapse" aria-labelledby="headingPaquete" data-bs-parent="#accordionConsulta">
                            <div class="accordion-body">
                                <p class="m-0"><b>Nombre:</b> <?=$paquete->titulo?></p>
                                <p class="m-0"><b>Destino:</b> <?=$paquete->provincia?>, <?=$paquete->destino?></p>
                                <p class="m-0"><b>Pensión:</b> <?=$paquete->pension?></p>
                                <p class="m-0"><b>Permite traslados:</b> <?=$paquete->traslado == 1 ? "Si" : "No"?></p>
                                <p class="m-0"><b>Noches:</b> <?=$paquete->noches == 0 ? "Excursión de día completo" : $paquete->noches?></p>
                                <p class="m-0"><b>Horario de salida:</b> <?=date("H:i", strtotime($paquete->horaSalida))?>hs</p>
                                <p class="m-0"><b>Horario de llegada:</b> <?=date("H:i", strtotime($paquete->horaLlegada))?>hs</p>

                                <div class="form-floating mt-2">
                                    <select name="idFechaSalida" id="idFechaSalida" class="form-select form-select-sm" onchange="handlerChangeFecha(this)" <?=$isConsultaAbierta ? "" : "disabled"?>>
                                        <? foreach ($fechasSalida as $fecha): ?>
                                            <option value="<?=$fecha->id?>" <?=$fecha->id == $consulta->idPaqueteFechaSalida ? "selected" : ""?>><?=date("d/m/Y", strtotime($fecha->fecha))?></option>
                                        <? endforeach; ?>
                                    </select>
                                    <label for="idFechaSalida">Fecha de salida</label>
                                </div>

                                <? if ($consulta->estado != "V"): ?>
                                    <div class="contentPrecio text-center mt-3">
                                        <p class="m-0 display-6">$<?=Util::numberToPrice($paquete->precio)?></p>
                                        <p class="m-0">Precio x persona</p>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid">
                    <? if ($isConsultaAbierta): ?>  
                        <div class="btn-group mb-1">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="dropdownMenuEstado">
                                Cambiar estado
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuEstado">
                                <li><a class="dropdown-item" href="javascript:;" onclick="handlerChangeEstado('V')"><i class="fas fa-cash-register me-1"></i>Finalizar venta</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="handlerChangeEstado()"><i class="fas fa-window-close me-1"></i>Cerrar consulta</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="handlerDeleteConsulta()"><i class="fas fa-trash me-1"></i>Eliminar consulta</a></li>
                            </ul>
                        </div>
                    <? endif; ?>
                     
                    <button class="btn btn-secondary" type="button" onclick="HTTP.backURL()"><i class="fa fa-arrow-left me-1"></i>Volver</button>
                </div>
                    
            </div>
        </div>
    </div>
    
    <!-- ------------------- -->
    <!--        CHAT         -->
    <!-- ------------------- -->
    <div class="col-md-8 col-lg-9">
        <div class="card">
            
            <div class="card-header text-black">
                <h5 class="card-title p-0"><i class="fa fa-plus me-1"></i>Nuevo mensaje</h5>

                <div id="messageContent" class="mb-2"></div>

                <div class="d-flex">
                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="handlerSubmitMessage(this, 0)"><i class="fa fa-paper-plane me-1"></i>Enviar al cliente</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="handlerSubmitMessage(this, 1)"><i class="fa fa-save me-1"></i>Guardar nota interna</button>
                </div>
            </div>

            <div class="card-footer">
                <h5 class="card-title m-0 p-0 text-center"><i class="<?= $menu->consultas->icon ?> me-1"></i>Historial de mensajes</h5>
            </div>

            <div class="card-body bg-light pt-2" style="max-height: 600px; overflow-y: auto;">
                <? foreach (Paquete::getAllMessage($_GET["id"]) as $mensaje): ?>
                    <? if ($mensaje->tipo != "S"): ?>
                        <div class="card" <?=$mensaje->tipo == "C" ? "data-bs-target='tooltip' title='Mensaje del cliente'" : ""?>>
                            <div class="card-body px-0 py-3 <?=$mensaje->isNotaInterna == 1 ? "bg-warning rounded" : ""?>">
                                <div class="d-flex">
                                    <div class="contentIcon d-flex justify-content-center align-items-center border-end">
                                        <i class="<?=$iconsUserMessage[$mensaje->tipo]?> px-3 fs-4"></i>
                                    </div>
                                    <div class="contentDataMessage px-3 w-100">
                                        <h5 class="card-title m-0 p-0"><?=$mensaje->nombreUsuarioMensaje?><?=$mensaje->tipo == "U" ? " ({$mensaje->nombreTipoUsuarioMensaje})" : ""?></h5>
                                        <? if ($mensaje->isNotaInterna): ?>
                                            <p class="text-secondary border-bottom m-0">[Nota interna]</p>
                                            <hr class="mt-0">
                                        <? endif; ?>
                                        <div class="contentMessage mt-1"><?=html_entity_decode($mensaje->mensaje)?></div>
                                        <small class="text-secondary"><i class="fa fa-calendar me-1"></i><?=date("d/m/Y H:i")?>hs</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? else: /* MENSAJE DEL SISTEMA */
                        $iconMessage = $iconsUserMessage[$mensaje->tipo];
                        $colorMessage = "text-black-50";
                        
                        if($mensaje->typeMessageSistem == "I"){
                            $iconMessage = "fa fa-info-circle";
                            $colorMessage = "border-info text-black-50 bg-info";
                        }
                        if($mensaje->typeMessageSistem == "W"){
                            $iconMessage = "fa fa-exclamation-triangle";
                            $colorMessage = "text-black-50 bg-warning";
                        }
                        if($mensaje->typeMessageSistem == "D"){
                            $iconMessage = "fa fa-exclamation";
                            $colorMessage = "text-white bg-danger";
                        }
                        ?>
                        <div class="rounded px-2 py-1 mb-3 text-center border <?=$colorMessage?>">
                            <div class="d-flex justify-content-between fs-6">
                                <p class="m-0 p-0"><i class="<?=$iconMessage?> me-1"></i>Mensaje del sistema</p>
                                <p class="m-0 p-0 fw-light fst-italic"><i class="fa fa-calendar me-1"></i><?=date("d/m/Y H:i")?>hs</p>
                            </div>
                            <div class="m-0 p-0"><?=$mensaje->mensaje?></div>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>
            </div>

            
        </div>
    </div>
    
</section>


<script>

    let messageContent = null

    document.addEventListener("DOMContentLoaded", e => {
        messageContent = new TextareaEditor("#messageContent")
        messageContent.initBasicText()
    })

    /* --------------------------------------------- */
    /*                                               */
    /*          Datos de contacto adicional          */
    /*                                               */
    /* --------------------------------------------- */
    function handlerDeleteDatoDeContacto(id, elem){
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

            const formData = new FormData()
            formData.append("action", "hardDelete")
            formData.append("table", "consulta_contacto_adicional")
            formData.append("pk", "idContactoAdicional")
            formData.append("idContactoAdicional", id)

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: formData
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {

                if (status == "OK") { // Elimino la fila
                    HTMLController.deleteParentElement(elem, "tr")
                }else{
                    Swal.fire(title, message, type)
                }
            })
        });
    }
    function handlerBtnNewContactoAdicional() {
        if(document.querySelectorAll(".inputNewDatoContacto.is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Revise los campos marcados en rojo", "warning")
            return
        }
        
        const inputDescripcion = document.getElementById("inputNewDatoContacto_origen")
        const inputContacto = document.getElementById("inputNewDatoContacto_contacto")

        const formData = new FormData()
        formData.append("idConsulta", <?=$_GET["id"]?>)
        formData.append("descripcion", inputDescripcion.value)
        formData.append("contacto", inputContacto.value)
        formData.append("table", "consulta_contacto_adicional")
        formData.append("action", "save")

        fetch(
            "<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: formData
            }
        )
        .then(res => res.json())
        .then(({pk}) => {

            const data = {
                id: pk,
                descripcion: inputDescripcion.value, 
                contacto: inputContacto.value
            }
            
            inputDescripcion.value = ""
            inputContacto.value = ""
            inputDescripcion.classList.add("is-invalid")
            inputContacto.classList.add("is-invalid")

            const content = document.getElementById("tableDatosAdicionales__rowNewContacto")
            content.insertAdjacentHTML("beforebegin", consulta_htmlNewContactoAdicional(data))
        })

        
    }
    function consulta_htmlNewContactoAdicional(data) {
        return `
            <tr>
                <td style="width: 10%" class="text-center">
                    <a 
                        href="javascript:;" 
                        class="text-danger" 
                        onclick="handlerDeleteDatoDeContacto(${data.id}, this)"
                        data-bs-target="tooltip"
                        title="Eliminar"
                    >
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
                <td>${data.descripcion}</td>
                <td>${data.contacto}</td>
            </tr>
        `
    }
    

    /* --------------------------------- */
    /*                                   */
    /*              Pasajeros            */
    /*                                   */
    /* --------------------------------- */
    function handlerDeletePasajero(id, elem){
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

            const formData = new FormData()
            formData.append("action", "hardDelete")
            formData.append("table", "consulta_pasajeros")
            formData.append("pk", "idPasajero")
            formData.append("idPasajero", id)

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", {
                    method: "POST",
                    body: formData
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {

                if (status == "OK") { // Elimino la fila
                    HTMLController.deleteParentElement(elem, "li")
                }else{
                    Swal.fire(title, message, type)
                }
            })
        });
    }
    function handlerBtnNewPax() {

        if(document.querySelectorAll("#formNewPasajero .is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Revise los campos marcados en rojo", "warning")
            return
        }

        const inputNombre = document.getElementById("pax_nombre")
        const inputApellido = document.getElementById("pax_apellido")
        const inputFechaDeNacimiento = document.getElementById("pax_fechaDeNacimiento")
        const inputObservaciones = document.getElementById("pax_observaciones")

        fetch(
            "<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: new FormData(document.getElementById("formNewPasajero"))
            }
        )
        .then(res => res.json())
        .then(({pk}) => {

            const data = {
                idPasajero: pk,
                nombre: Util.ucfirst(inputNombre.value), 
                apellido: Util.ucfirst(inputApellido.value), 
                edad: Util.dateToAge(inputFechaDeNacimiento.value), 
                observaciones: Util.dateToAge(inputObservaciones.value), 
            }
            
            inputNombre.value = ""
            inputApellido.value = ""
            inputFechaDeNacimiento.value = ""
            inputObservaciones.value = ""
            inputNombre.classList.add("is-invalid")
            inputApellido.classList.add("is-invalid")
            inputFechaDeNacimiento.classList.add("is-invalid")

            const content = document.querySelector("#accordionPax_liNewPax")
            content.insertAdjacentHTML("beforebegin", consulta_htmlNewPax(data))
        })

        

    }
    function consulta_htmlNewPax(data) {
        let observaciones = ""
        if(data.observaciones) observaciones = `<p class="m-0 p-0 mt-1">${data.observaciones}</p>`

        return `
            <li class="list-group-item">
                <div class=" d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">${data.nombre} ${data.apellido}</div>
                        <span class="text-secondary">${data.sexo == "M" ? "Masculino" : "Femenino"}</span>
                        ${data.observaciones}
                    </div>
                    <span class="badge bg-primary rounded-pill">${data.edad} años</span>
                </div>
                <div class="d-grid">
                    <button type="button" class="btn btn-danger btn-sm mt-1" onclick="handlerDeletePasajero(${data.idPasajero}, this)"><i class="fa fa-trash me-1"></i>Eliminar</button>
                </div>
            </li>
        `
    }


    /* ------------------------- */
    /*      CAMBIO DE FECHA      */
    /* ------------------------- */
    function handlerChangeFecha(elem){
        
        const formData = new FormData()
        formData.append("idConsulta", <?=$_GET["id"]?>)
        formData.append("pk", "idConsulta")
        formData.append("idPaqueteFechaSalida", elem.value)
        formData.append("table", "consultas")
        formData.append("action", "save")
        formData.append("response_title", "Fecha Modificada!")

        fetch(
            "<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: formData
            }
        )
        .then(res => res.json())
        .then(({status, title}) => {
            Swal.fire(title, "", "success")
        })
    }

    /* ----------------------------------- */
    /*      GUARDAR DATOS DE CONSULTA      */
    /* ----------------------------------- */
    function handlerUpdateConsulta(elBtn) {

        // Valido el formulario
        if(document.querySelectorAll("#formConsulta .is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Modifique los campos marcados en rojo para continuar", "warning")
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
            if(!result.isConfirmed) return
            
            let btnSubmit = new FormButtonSubmitController(elBtn, false)
            btnSubmit.init()

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: new FormData(document.getElementById("formConsulta"))
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type, data}) => {
                btnSubmit.reset()

                Swal.fire(title, message, type)
            })
        })
    }

    /* ---------------------------- */
    /*          Nuevo mensaje       */
    /* ---------------------------- */
    function handlerSubmitMessage(elBtn, isNotaInterna){
        const message = messageContent.getHTML()

        // Valido el formulario
        if(message.length == 0){
            Swal.fire("Mensaje vacío!", "", "warning")
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
            if(!result.isConfirmed) return
            
            let btnSubmit = new FormButtonSubmitController(elBtn, false)
            btnSubmit.init()

            const formData = new FormData()
            formData.append("action", "save")
            formData.append("pk", "idMensaje")
            formData.append("table", "consulta_mensajes")
            formData.append("idConsulta", <?=$_GET["id"]?>)
            formData.append("idUsuarioMensaje", <?=$_SESSION["user"]["idUsuario"]?>)
            formData.append("mensaje", message)
            formData.append("tipo", "U")
            formData.append("isNotaInterna", isNotaInterna)

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: formData
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type, data}) => {
                btnSubmit.reset()

                Swal.fire(title, message, type).then(res => {
                    if(status == "OK") location.reload()
                })
            })
        })
    }

    /* --------------------------------- */
    /*          ESTADO CONSULTA          */
    /* --------------------------------- */
    function handlerChangeEstado(newState = 'C'){

        let titleAlert = newState === "C" ? "¿Seguro que desea cerrar la consulta?" : ""
        let messageAlert = ""
        let iconAlert = newState === "C" ? "question" : ""
        let totalConsulta = 0

        // Venta
        if(newState === "V"){
            const [diaSalida, mesSalida, anioSalida] = document.querySelector(`#idFechaSalida option[value='${document.getElementById("idFechaSalida").value}']`).textContent.split("/")
            const totalPasajeros = document.querySelectorAll("#flush-coppalsePax .list-group-item button.btn-danger").length

            if(totalPasajeros == 0){
                Swal.fire("Sin pasajeros!", "Debe agregar como mínimo un pasajero a la consulta.", "warning")
                return
            }

            totalConsulta = <?=$paquete->precio?> * totalPasajeros
            let textoTraslado = `<p class="m-0 p-0">Sin traslado</p>`

            if(document.getElementById("traslado").value === "1"){
                const idAlojamiento = document.getElementById("idAlojamiento").value

                if(idAlojamiento === ""){
                    Swal.fire("Sin alojamiento!", "Si indica que tiene traslado debe indicar el alojamiento de forma obligatoria", "warning")
                    return  
                }

                textoTraslado = `<p class="m-0 p-0">Con traslado desde el alojamiento ${document.querySelector(`#idAlojamiento option[value='${idAlojamiento}']`).textContent}</p>`
            }

            // Armo el mensaje con el detalle de la consulta
            messageAlert = `
                <div>
                    <h3><i class="fas fa-cash-register me-1"></i>Detalle de la venta</h3>
                    <hr>

                    <h4><i class="me-1 <?=$menu->excursiones->icon?>"></i><?=$paquete->titulo?></h4>
                    <h4 class="mb-3"><i class="me-1 <?=$menu->clientes->icon?>"></i><?=ucfirst($cliente->nombre)?> <?=ucfirst($cliente->apellido)?></h4>

                    ${textoTraslado}
                    <p class="m-0 p-0">Pasajeros: ${totalPasajeros}</p>
                    <p class="m-0 p-0">Fecha de salida: ${diaSalida}/${mesSalida}/${anioSalida}</p>
                    <p class="m-0 p-0">Hora de salida: <?=date("H:i", strtotime($paquete->horaSalida))?>hs</p>

                    <h1 class="display-5 mt-3">$${Util.numberToPrice(totalConsulta, true)}<small class="text-secondary fs-3">/total</small></h1>

                    <hr>
                    <h4>¿Seguro que desea realizar la venta?</h4>
                </div>
            `
        }

        Swal.fire({
            title: titleAlert,
            html: messageAlert,
            icon: iconAlert,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then(result => {

            // Rechazo la eliminación
            if(!result.isConfirmed) return
            
            const formData = new FormData()
            formData.append("action", "save")
            formData.append("estado", newState)
            formData.append("total", totalConsulta)
            formData.append("table", "consultas")
            formData.append("pk", "idConsulta")
            formData.append("idConsulta", <?=$_GET["id"]?>)

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: formData
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                Swal.fire(title, message, type).then(e => {
                    if(status === "OK"){

                        // Si se cerró la venta agrego el mensaje
                        const nombreAsignado = `<?=ucfirst($_SESSION['user']['nombre'])?> <?=ucfirst($_SESSION['user']['apellido'])?> (${(<?=$_SESSION["user"]["tipo"]?> == 0 ? "admin" : "Vendedor")})`
                        const formDataMessage = new FormData()
                        formDataMessage.append("action", "save")
                        formDataMessage.append("table", "consulta_mensajes")
                        formDataMessage.append("idUsuarioMensaje", 0)
                        formDataMessage.append("tipo", "S")
                        formDataMessage.append("pk", "idMensaje")
                        formDataMessage.append("idConsulta", <?=$_GET["id"]?>)

                        if(newState === "V"){
                            formDataMessage.append("mensaje", `
                                <p class="m-0">Venta realizada por: ${nombreAsignado}</p>
                                <h4 class="display-5 fs-3 m-0">$${Util.numberToPrice(totalConsulta, true)}<small class="text-secondary fs-5">/total</small></h4>
                            `)
                        }else{
                            formDataMessage.append("mensaje", `<p class="m-0">Consulta cerrada por el usuario: ${nombreAsignado}</p>`)
                        }

                        fetch("<?= DOMAIN_ADMIN ?>process.php", {method: "POST", body: formDataMessage}).then(res => res.json()).then(response => {
                            if(newState == "V"){
                                location.reload()
                            }else{
                                HTTP.redirect('<?=DOMAIN_ADMIN?>consultas/')
                            }

                        })
                    }
                })
            })
        })
    }
    function handlerDeleteConsulta(){
        Swal.fire({
            title: "¿Estás seguro que desea eliminar la consulta?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then(result => {

            // Rechazo la eliminación
            if(!result.isConfirmed) return
            
            const formData = new FormData()
            formData.append("action", "delete")
            formData.append("table", "consultas")
            formData.append("pk", "idConsulta")
            formData.append("idConsulta", <?=$_GET["id"]?>)

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: formData
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                Swal.fire(title, message, type).then(e => {
                    if(status === "OK"){
                        HTTP.redirect('<?=DOMAIN_ADMIN?>consultas/')
                    }
                })
            })
        })
    }

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
