<?
require_once __DIR__ . "/config/init.php";

if(!isset($_GET["c"]) || !$_GET["c"]) HTTPController::get401(false);

$idConsulta = base64_decode($_GET["c"]);

$titlePage = "Consulta #". str_pad($idConsulta, 5, "0", STR_PAD_LEFT);
$consulta = Consulta::getAllInfo($idConsulta);
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

$title = $titlePage;
?>

<!DOCTYPE html>
<html lang="es">
<? require_once PATH_SERVER . "/helpers/sections/head.php" ?>
<body>
    <style>
        .contentMessage p,
        .contentMessage br{
            margin: 0;
            padding: 0;
        }
    </style>

    <main class="p-2 p-md-5">
        <section class="section row">

            <!-- -------------------- -->
            <!--        DETALLE       -->
            <!-- -------------------- -->
            <div class="col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $titlePage ?></h5>

                        <!-- ACCORDION | cliente, excursión, pax -->
                        <div class="accordion accordion-flush" id="accordionConsulta">

                            <!-- EXCURSIÓN -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingPaquete">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapsePaquete" aria-expanded="active" aria-controls="flush-collapsePaquete">
                                        <h5 class="card-title p-0"><i class="me-1 <?=$menu->excursiones->icon?>"></i>Excursión</h5>
                                    </button>
                                </h2>
                                <div id="flush-collapsePaquete" class="accordion-collapse collapse show" aria-labelledby="headingPaquete" data-bs-parent="#accordionConsulta">
                                    <div class="accordion-body">
                                        <p class="m-0"><b>Nombre:</b> <?=$paquete->titulo?></p>
                                        <p class="m-0"><b>Destino:</b> <?=$paquete->provincia?>, <?=$paquete->destino?></p>
                                        <p class="m-0"><b>Pensión:</b> <?=$paquete->pension?></p>
                                        <p class="m-0"><b>Permite traslados:</b> <?=$paquete->traslado == 1 ? "Si" : "No"?></p>
                                        <p class="m-0"><b>Noches:</b> <?=$paquete->noches == 0 ? "Excursión de día completo" : $paquete->noches?></p>
                                        <p class="m-0"><b>Fecha de salida:</b> <?=date("d/m/Y", strtotime($consulta->fechaSalida))?></p>
                                        <p class="m-0"><b>Horario de salida:</b> <?=date("H:i", strtotime($paquete->horaSalida))?>hs</p>
                                        <p class="m-0"><b>Horario de llegada:</b> <?=date("H:i", strtotime($paquete->horaLlegada))?>hs</p>
                                    </div>
                                </div>
                            </div>

                            <!-- CLIENTE -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingContacto">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalseContacto" aria-expanded="false" aria-controls="flush-coppalseContacto">
                                        <h5 class="card-title p-0"><i class="me-1 <?=$menu->clientes->icon?>"></i>Mis datos</h5>
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
                                        
                                        <? if ($datosDeContactoAdicional): ?>
                                            <h5>Datos de contacto adicional</h5>
                                            <? foreach ($datosDeContactoAdicional as $contacto): ?>
                                                <p class="m-0"><b><?=$contacto->descripcion?>:</b> <?=$contacto->contacto?></p>
                                            <? endforeach; ?>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- PAX -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingPax">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-coppalsePax" aria-expanded="false" aria-controls="flush-coppalsePax">
                                        <h5 class="card-title p-0"><i class="me-1 bi bi-person-add"></i>Pasajeros <?=$pasajeros ? "(".count($pasajeros).")" : ""?></h5>
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
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
                </div>
            </div>

            <!-- ------------------- -->
            <!--        CHAT         -->
            <!-- ------------------- -->
            <div class="col-md-8 col-lg-9">
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title m-0 p-0 text-center"><i class="<?= $menu->consultas->icon ?> me-1"></i>Chat</h5>
                    </div>

                    <div class="card-body bg-light pt-2 px-2" style="max-height: 600px; overflow-y: auto;" id="contentMessageChat">
                        <? foreach (Paquete::getAllMessagePublic($idConsulta) as $mensaje): ?>
                            <div class="card w-75 <?=$mensaje->tipo == 'C' ? "ms-auto" : ""?>">
                                <div class="card-body py-2 px-3">
                                    <? if ($mensaje->tipo == "U"): ?>
                                        <div class="d-flex align-items-center mb-1">
                                            <img src="<?=DOMAIN_NAME?>assets/img/logo.png" alt="Logo de <?=APP_NAME?>" width="30" height="30">
                                            <h5 class="card-title m-0 p-0 ms-2"><?=ucfirst($mensaje->nombreUsuarioMensaje)?></h5>
                                        </div>
                                    <? endif; ?>

                                    <div class="contentMessage m-0 p-0 mb-1"><?=html_entity_decode($mensaje->mensaje)?></div>
                                    <p class="m-0 p-0 fs-6 text-secondary fst-italic <?=$mensaje->tipo == 'C' ? "text-end" : ""?>"><small><i class="fa fa-clock me-1"></i><?=date("d/m/Y H:i", strtotime($mensaje->created_at))?>hs</small></p>
                                </div>
                            </div>
                        <? endforeach; ?>
                    </div>
                    
                    <? if ($consulta->estado == "A"): ?>
                        <div class="card-footer text-black">
                            <h5 class="card-title p-0"><i class="fa fa-plus me-1"></i>Nuevo mensaje</h5>

                            <div id="messageContent" class="mb-2"></div>

                            <button type="button" class="btn btn-primary btn-sm me-2" onclick="handlerSubmitMessage(this)"><i class="fa fa-paper-plane me-1"></i>Enviar mensaje</button>
                        </div>
                    <? else: ?>
                        <div class="card-footer text-black">
                            <? if($consulta->estado == "V"): ?>
                                <h5 class="card-title p-0 m-0 text-success text-center"><i class="fa fa-check me-1"></i>Consulta vendida</h5>
                            <? else: ?>
                                <h5 class="card-title p-0 m-0 text-danger text-center"><i class="fa fa-exclamation-triangle me-1"></i>Consulta cerrada</h5>
                            <? endif; ?>
                        </div>
                    <? endif; ?>

                    
                </div>
            </div>

        </section>
    </main>
            
    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>

    <script>
        let messageContent = null

        document.addEventListener("DOMContentLoaded", e => {
            if(document.querySelector("#messageContent")){
                messageContent = new TextareaEditor("#messageContent")
                messageContent.initBasicText()
            }

            // Hago foco en el último mensaje
            if(document.querySelector('#contentMessageChat > div.card:last-child')){
                document.querySelector('#contentMessageChat > div.card:last-child').scrollIntoView()
            }
        })

        /* ---------------------------- */
        /*          Nuevo mensaje       */
        /* ---------------------------- */
        function handlerSubmitMessage(elBtn){
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
                formData.append("idConsulta", <?=$idConsulta?>)
                formData.append("idUsuarioMensaje", <?=$consulta->idCliente?>)
                formData.append("mensaje", message)
                formData.append("tipo", "C")
                formData.append("isNotaInterna", 0)
                formData.append("response_title", "Enviado!")

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


    </script>
    
</body>
</html>



