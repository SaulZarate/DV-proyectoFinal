<?
require_once __DIR__ . "/config/init.php";

$_GET["c"] = $_GET["c"] ?? "";
if (!isset($_GET["r"]) || !$_GET["r"]) HTTPController::get401(false);
$idRecorrido = base64_decode($_GET["r"]);
$idClienteMessage = base64_decode($_GET["c"]);

$dataCliente = $idClienteMessage ? Cliente::getById($idClienteMessage) : null;
$recorrido = Recorrido::getByIdAllInfo($idRecorrido);

$title = ucfirst($recorrido->paquete->titulo) . " " . date("d/m/Y", strtotime($recorrido->fecha));

?>
<!DOCTYPE html>
<html lang="es">
<? require_once PATH_SERVER . "/helpers/sections/head.php" ?>

<body>

    <main class="mainRecorridoPublic">
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
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasGuía" aria-controls="offcanvasGuía">
                            <i class="<?= $menu->usuarios->icon ?> me-1"></i>Guía
                        </button>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMensajes" aria-controls="offcanvasMensajes">
                            <i class="<?= $menu->consultas->icon ?> me-1"></i>Chat general
                        </button>

                        <!-- Información de guía -->
                        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasGuía" aria-labelledby="offcanvasGuíaLabel">
                            <div class="offcanvas-header border-bottom">
                                <h5 class="offcanvas-title" id="offcanvasGuíaLabel">Información del guía</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <h5 class="card-title m-0 pt-0 fs-4"><i class="me-1 <?=$menu->usuarios->icon?>"></i><?=ucfirst($recorrido->usuario->nombre)?> <?=ucfirst($recorrido->usuario->apellido)?></h5>
                                <p class="m-0"><span class="fw-bold">Edad:</span> <?=Util::dateToAge($recorrido->usuario->fechaNacimiento)?> años</p>
                                <p class="m-0"><span class="fw-bold">Sexo:</span> <?=$recorrido->usuario->sexo?></p>
                                <p class="m-0"><span class="fw-bold">Nacionalidad:</span> <?=$recorrido->usuario->nacionalidad?></p>

                                <hr class="">
                                <div><?=html_entity_decode($recorrido->usuario->descripcion)?></div>
                            </div>
                        </div>

                        <!-- Chat general -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMensajes" aria-labelledby="offcanvasMensajesLabel">
                            <div class="offcanvas-header border-bottom">
                                <h5 class="offcanvas-title" id="offcanvasMensajesLabel"><i class="me-1 <?=$menu->consultas->icon?>"></i>Chat general</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body d-flex flex-column">

                                <!-- Mensajes del chat -->
                                <div class="bg-light flex-fill border border-bottom-0 overflow-auto p-2" id="contentMessageChat"></div>
                                
                                <!-- Si no existe un cliente no permito mandar mensajes. Solo lectura -->
                                <? if ($idClienteMessage): ?>
                                    <div id="messageContent" style="height: 80px;"></div>

                                    <div class="flex mt-1">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="handlerSubmitMessage(this)"><i class="fa fa-paper-plane me-1"></i>Enviar</button>
                                        <button type="button" class="btn btn-success btn-sm" onclick="refreshChat('button')"><i class="fa fa-sync-alt me-1"></i>Actualizar</button>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <? if ($recorrido->totalAlojamientoConsulta > 0): ?>
                <div class="col-12"></div>

                <div class="card dashboard m-0 col-md-6 col-md-4 mx-auto my-3">
                    <div class="card-body py-3">
                        <h3 class="mb-3">Tramos del vehículo</h3>

                        <div class="activity">
                            <? foreach ($recorrido->tramos as $tramo):
                                $textoLeftTramo = "Pendiente";
                                $colorPointTramo = "secondary";
                                $colorTextRight = "";
                                $titleTramo = "";
                                $textPaxTramo = "";

                                if ($tramo->estado == "M") {
                                    $textoLeftTramo = "<i class='fa fa-check text-success'></i>";
                                    $colorPointTramo = "success";
                                    $colorTextRight = "text-success";
                                }

                                if ($tramo->tipo == "O") {
                                    $titleTramo = "<i class='me-1 {$menu->recorridos->icon}'></i>Punto de partida";
                                    $textPaxTramo = "<i class='me-1 {$menu->clientes->icon}'></i>" . $tramo->pax . " " . ($tramo->pax == 1 ? "pasajero" : "pasajeros");
                                }

                                if ($tramo->tipo == "P") {
                                    $titleTramo = "<i class='me-1 {$menu->alojamientos->icon}'></i>" . $tramo->alojamiento->nombre;
                                    $textPaxTramo = "<i class='me-1 {$menu->clientes->icon}'></i>" . $tramo->pax . " " . ($tramo->pax == 1 ? "pasajero" : "pasajeros");
                                }

                                if ($tramo->tipo == "D") {
                                    $titleTramo = "<i class='me-1 {$menu->recorridos->icon}'></i>Inicio de la excursión";
                                }
                            ?>
                                <div class="activity-item d-flex">
                                    <div class="activite-label d-flex-fullCenter"><?= $textoLeftTramo ?></div>
                                    <i class="bi bi-circle-fill activity-badge text-<?= $colorPointTramo ?> align-self-center"></i>
                                    <div class="activity-content align-self-center <?= $colorTextRight ?>">
                                        <p class="m-0 fs-5"><?= $titleTramo ?></p>

                                        <? if ($textPaxTramo): ?>
                                            <p class="m-0 text-secondary"><?= $textPaxTramo ?></p>
                                        <? endif; ?>
                                    </div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
            <? endif; ?>
        </section>
    </main>

    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>

    <script>
        const idRecorrido = <?=$idRecorrido?>;
        const idCliente = <?=!!$idClienteMessage ? $idClienteMessage : 0?>;
        let messageContent = null

        document.addEventListener("DOMContentLoaded", e => {
            refreshChat()
            
            <? if ($idClienteMessage): ?>
                messageContent = new TextareaEditor("#messageContent")
                messageContent.initBasicText()
            <? endif; ?>
        })
        
        function handlerSubmitMessage(elBtn){
            const message = messageContent.getHTML()

            // Valido el formulario
            if(message.length == 0 || message == "<p></p>"){
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
                formData.append("table", "recorrido_mensajes")
                formData.append("idRecorrido", <?=$idRecorrido?>)
                formData.append("idUsuario", <?=$idClienteMessage?>)
                formData.append("mensaje", message)
                formData.append("tipo", "C")
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

                    refreshChat()

                    if(status ===" OK") messageContent.setContent("")

                    Swal.fire(title, message, type)
                })
            })
        }

        function refreshChat(origin = "newMessage"){
            const formData = new FormData()
            formData.append("action", "recorrido_getChatGeneral")
            formData.append("idRecorrido", <?=$idRecorrido?>)

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: formData
                }
            )
            .then(res => res.json())
            .then(response => {
                /* console.log(response) */

                let htmlMessages = ""

                for (const message of response) {
                    htmlMessages += htmlItemMessageChat(message)
                }

                document.getElementById("contentMessageChat").innerHTML = htmlMessages

                // Hago foco en el último mensaje
                if(document.querySelector('#contentMessageChat > div.itemMessageChatGeneral:last-child')) document.querySelector('#contentMessageChat > div.itemMessageChatGeneral:last-child').scrollIntoView()

                /* Mensaje de chat actualizado */
                if(origin == "button"){
                    Swal.fire({
                        title: "Chat actualizado!", 
                        html: "",
                        icon: "success",
                        showConfirmButton: false, 
                        timer: 2000
                    })
                }
            })
        }

        function htmlItemMessageChat(data){
            const isMyMessage = data.tipo == "C" && data.idUsuario == idCliente
            const htmlImage = data.tipo == "U" ? `<img src="<?=DOMAIN_NAME?>assets/img/logo.png" alt="Logo de <?=APP_NAME?>" width="30" height="30" class="me-2">` : ""
            const titleMessage = isMyMessage ? "" : `<h5 class="card-title m-0 p-0">${Util.ucfirst(data.tipo == "C" ? data.cliente : data.usuario)}</h5>`

            const [dataFechaMensaje, dataHoraMensaje] = data.created_at.split(" ")
            const [anio, mes, dia] = dataFechaMensaje.split("-")
            const [hora, minutos, segundo] = dataHoraMensaje.split(":")
            return `
                <div class="card w-75 mb-3 itemMessageChatGeneral ${isMyMessage ? "ms-auto" : ""}">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center mb-1">
                            ${htmlImage}
                            ${titleMessage}
                        </div>

                        <div class="contentMessage m-0 p-0 mb-1">${data.mensaje}</div>

                        <p class="m-0 p-0 fs-6 text-secondary fst-italic ${isMyMessage ? "text-end" : ""}">
                            <small><i class="fa fa-clock me-1"></i>${dia}/${mes}/${anio} ${hora}:${minutos}hs</small>
                        </p>
                    </div>
                </div>
            `
        }
    </script>
</body>

</html>