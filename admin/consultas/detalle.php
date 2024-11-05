<?
require_once __DIR__ . "/../../config/init.php";

$section = "consultas";

$titlePage = "Consulta #". str_pad($_GET['id'], 5, "0", STR_PAD_LEFT);
$consulta = Consulta::getById($_GET["id"]);
$cliente = Cliente::getById($consulta->idCliente);
$paquete = Paquete::getById($consulta->idPaquete);
$fechasSalida = Paquete::getAllFechasSalida($consulta->idPaquete);
$datosDeContactoAdicional = Consulta::getAllDatosDeContactoAdicional($consulta->idConsulta);

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section row">

    <div class="col-md-4 col-lg-3">
        <div class="card">
            <div class="card-body">
                

                <form action="" id="formDetalleConsulta">

                    <!-- ACCORDION | cliente, excursión, pax -->
                    <div class="accordion accordion-flush" id="accordionConsulta">

                        <!-- CONSULTA -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingConsulta">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalseConsulta" aria-expanded="false" aria-controls="flush-coppalseConsulta">
                                    <h5 class="card-title"><?= $titlePage ?></h5>
                                </button>
                            </h2>
                            <div id="flush-coppalseConsulta" class="accordion-collapse collapse" aria-labelledby="flush-headingConsulta" data-bs-parent="#accordionConsulta">
                                <div class="accordion-body">
                                    <div class="form-floating mb-2">
                                        <input type="tel" class="form-control" name="asunto" placeholder="Asunto" oninput="FormController.validateForm(this, 3)" value="<?= $consulta->asunto ?>">
                                        <label>Asunto</label>
                                    </div>

                                    <div class="form-floating mb-2">
                                        <select class="form-select" id="idUsuario" name="idUsuario" oninput="FormController.validateForm(this)">
                                            <? foreach (Usuario::getAll(["where" => "estado = 'A'", "order" => "nombre"]) as $usuario): ?>
                                                <option value="<?= $usuario->idUsuario ?>" <?= $consulta->idUsuario == $usuario->idUsuario ? "selected" : "" ?>><?= ucfirst($usuario->nombre) ?> <?=ucfirst($usuario->apellido)?> | <?=$usuario->tipo == 0 ? "ADMIN" : "VENDEDOR"?></option>
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

                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="traslado" name="traslado" oninput="FormController.validateForm(this)">
                                            <option value="0" <?= $consulta->traslado == "0" ? "selected" : "" ?>>No</option>
                                            <option value="1" <?= $consulta->traslado == "1" ? "selected" : "" ?>>Si</option>
                                        </select>
                                        <label for="traslado">Traslado</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="idAlojamiento" name="idAlojamiento">
                                            <option value=""></option>
                                            <? foreach (Alojamiento::getAll(["order" => "nombre"]) as $alojamiento): ?>
                                                <option value="<?= $alojamiento->idAlojamiento ?>" <?= $consulta->idAlojamiento == $alojamiento->idAlojamiento ? "selected" : "" ?>><?= ucfirst($alojamiento->nombre) ?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idAlojamiento">Alojamiento</label>
                                    </div>
                                </div>
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
                                    <p class="m-0"><b>Noches:</b> <?=$paquete->noches == 0 ? "Excursión de día completo" : $paquete->noches?></p>
                                    <p class="m-0"><b>Horario de salida:</b> <?=date("H:i", strtotime($paquete->horaSalida))?>hs</p>
                                    <p class="m-0"><b>Horario de llegada:</b> <?=date("H:i", strtotime($paquete->horaLlegada))?>hs</p>

                                    <div class="form-floating mt-2">
                                        <select name="idFechaSalida" id="idFechaSalida" class="form-select form-select-sm">
                                            <? foreach ($fechasSalida as $fecha): ?>
                                                <option value="<?=$fecha->id?>" <?=$fecha->id == $consulta->idPaqueteFechaSalida ? "selected" : ""?>><?=date("d/m/Y", strtotime($fecha->fecha))?></option>
                                            <? endforeach; ?>
                                        </select>
                                        <label for="idFechaSalida">Fecha de salida</label>
                                    </div>

                                    <div class="contentPrecio text-center mt-3">
                                        <p class="m-0 display-6">$<?=Util::numberToPrice($paquete->precio)?></p>
                                        <p class="m-0">Precio x persona</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PAX -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingPax">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalsePax" aria-expanded="false" aria-controls="flush-coppalsePax">
                                    <h5 class="card-title p-0"><i class="me-1 bi bi-person-add"></i>Pax</h5>
                                </button>
                            </h2>
                            <div id="flush-coppalsePax" class="accordion-collapse collapse" aria-labelledby="flush-headingPax" data-bs-parent="#accordionConsulta">
                                <div class="accordion-body">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="contentButton">
                        <button class="btn btn-primary" type="button"><i class="fa fa-save me-1"></i>Guardar</button>
                        <button class="btn btn-secondary" type="button" onclick="HTTP.backURL()"><i class="fa fa-times-circle me-1"></i>Salir</button>
                    </div>
                    
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="idConsulta" value="<?=$_GET['id']?>">
                    <input type="hidden" name="pk" value="idConsulta">
                    <input type="hidden" name="table" value="consultas">

                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 col-lg-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="<?= $menu->consultas->icon ?> me-1"></i>Mensajes</h5>
            </div>
        </div>
    </div>
    
</section>


<script>

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

    function handlerSaveForm(elBtn) {

        // Valido el formulario
        if(document.querySelectorAll("#formMessage .is-invalid").length > 0){
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
                    body: new FormData(document.getElementById("formMessage"))
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type, data}) => {
                btnSubmit.reset()

                Swal.fire(title, message, type).then(res => {
                    if(status == "OK") addMessage(data)
                })
            })
        })
    }

    function addMessage(data){
        console.log(data)
    }

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
