<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";
$isNew = !isset($_GET["id"]);
$isOrigenExcursiones = false;


$subSection = $isNew ? "Nuevo" : "";
$dataPaquete = null;
$fechaSalida = "";

if($isNew && isset($_GET["idPaquete"]) && isset($_GET["fecha"])){
    $dataPaquete = Paquete::getById($_GET["idPaquete"]);
    $fechaSalida = $_GET["fecha"];
    $isOrigenExcursiones = true;
    $subSection = "excursiones";
}



$title = ($isNew ? "Crear" : "Editar") . " recorrido | " . APP_NAME;

$recorrido = $isNew ? null : Recorrido::getByIdAllInfo($_GET["id"]);

ob_start();
?>


<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="mb-3 row">
                <div class="col">
                    <h5 class="card-title pb-0"><i class="<?=$menu->{$section}->icon?> me-1"></i>Crear recorrido</h5>
                    <p class="text-secondary pb-0 mb-2">Complete el siguiente formulario para <?=$isNew ? "crear" : "modificar" ?> el recorrido</p>
                </div>
            </div>

            <form action="" class="row" id="formRecorrido">

                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idUsuario" id="idUsuario" class="form-select is-invalid" oninput="FormController.validateForm(this)">
                            <option value="">-- Seleccione un guía --</option>
                            <? foreach (Usuario::getAll(["where" => "estado = 'A' AND tipo = 2", "order" => "nombre ASC"]) as $usuario): ?>
                                <option value="<?= $usuario->idUsuario ?>" <?=$recorrido && $recorrido->idUsuario == $usuario->idUsuario ? "selected" : "" ?>>
                                    <?= ucfirst($usuario->nombre) . " " . ucfirst($usuario->apellido) ?>
                                </option>
                            <? endforeach; ?>
                        </select>
                        <label for="idUsuario" class="form-label">Guía</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating">
                        <? if ($isNew): ?>
                            <select name="idPaquete" id="idPaquete" class="form-select <?=$isOrigenExcursiones ? '' : 'is-invalid'?>" oninput="FormController.validateForm(this)" onchange="handlerChangePaquete(this)">
                                <? if ($isOrigenExcursiones): ?>
                                    <option value="<?=$dataPaquete->idPaquete?>"><?=ucfirst($dataPaquete->titulo)?></option>
                                <? else: ?>
                                    <option value="">-- Seleccione una excursión --</option>
                                    <? foreach (Paquete::getAll(["where" => "estado = 'A'", "order" => "nombre ASC"]) as $paquete): 
                                        // Si no tiene fechas de salida lo ignoro
                                        if(!Paquete::getAllFechasSalida($paquete->idPaquete, date("Y-m-d"))) continue;
                                        ?>
                                        <option value="<?= $paquete->idPaquete ?>" data-traslado="<?=$paquete->traslado?>"><?= ucfirst($paquete->titulo) ?></option>
                                    <? endforeach; ?>
                                <? endif; ?>
                            </select>
                        <? else: ?>
                            <select id="idPaquete" class="form-select text-secondary" disabled>
                                <option value="<?=$recorrido->idPaquete?>"><?=ucfirst($recorrido->paquete->titulo)?></option>
                            </select>
                        <? endif; ?>
                        <label for="idPaquete" class="form-label">Excursión</label>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-floating">
                        <? if ($isNew): ?>
                            <select name="fecha" id="fecha" class="form-select <?=$isOrigenExcursiones ? '' : 'is-invalid'?>"" oninput="FormController.validateForm(this)">
                                <? if ($isOrigenExcursiones): ?>
                                    <option value="<?=$fechaSalida?>"><?=date("d/m/Y", strtotime($fechaSalida))?></option>
                                <? else: ?>
                                    <option value="">-- Seleccione una excursión --</option>
                                <? endif; ?>
                            </select>
                        <? else: ?>
                            <select id="fecha" class="form-select text-secondary" disabled>
                                <option value="<?=$recorrido->fecha?>"><?=date("d/m/Y", strtotime($recorrido->fecha))?></option>
                            </select>
                        <? endif; ?>
                        <label for="fecha" class="form-label">Fecha de salida</label>
                    </div>
                </div>

                <input type="hidden" name="action" value="recorrido_save">
                <input type="hidden" name="table" value="recorridos">
                <input type="hidden" name="pk" value="idRecorrido">
                <input type="hidden" name="idRecorrido" value="<?=$_GET["id"] ?? ""?>">

                <? if ($isNew): ?>
                    <input type="hidden" name="created_by_idUsuario" value="<?=$_SESSION["user"]["idUsuario"]?>">
                <? endif; ?>

                <div class="col-12 mt-2">
                    <button type="button" class="btn btn-primary" onclick="handlerSubmit(this)"><i class="fa fa-plus me-1"></i>Crear</button>
                    <a href="javascript:;" class="btn btn-secondary" onclick="HTTP.backURL()"><i class="fa fa-times-circle me-1"></i>Salir</a>
                </div>
            </form>        
        </div>
    </div>
</section>

<script>

    document.addEventListener("DOMContentLoaded", e => {
        
    })

    /* Busco las fechas disponibles del paquete */
    function handlerChangePaquete(element) {
        fetch('<?= DOMAIN_ADMIN ?>process?action=paquete_getAllFechaDisponibles&id=' + element.value)
        .then(res => res.json())
        .then(result => {
            
            let htmlOptions = result.fechas.lenght === 0 ? `<option value="">Sin fechas disponibles</option>` : `<option value="">-- Seleccione una fecha --</option>`

            for (const {id,fecha,cupos,disponibles,hasRecorrido} of result.fechas) {

                const [anio, mes, dia] = fecha.split("-")
                const disabled = disponibles === 0 || hasRecorrido === 1 ? "disabled" : ""
                const textCupos = hasRecorrido === 1 ? "Tiene salida creada" : "disponible"

                htmlOptions += `<option value="${fecha}" ${disabled}>${dia}/${mes}/${anio} | ${textCupos}</option>`
            }

            document.getElementById("fecha").innerHTML = htmlOptions

            HTMLController.trigger("#fecha", "input")
        })
    }

    function handlerSubmit(elem){
        if(document.querySelectorAll(".is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Complete todos los campos para continuar", "warning")
            return
        }

        const idGuia = document.getElementById("idUsuario").value
        const fecha = document.getElementById("fecha").value

        Swal.fire({
            title: "¿Quieres crear el recorrido?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return
            
            const btnSunbmit = new FormButtonSubmitController(elem, false)
            btnSunbmit.init()

            fetch(`<?=DOMAIN_ADMIN?>process.php?action=recorrido_isGuiaDisponible&idUsuario=${idGuia}&fecha=${fecha}&idRecorrido=<?=$_GET["id"] ?? ""?>`).then(res => res.json()).then(response => {
                // Creo el recorrido
                if(response.status == "OK"){
                    saveRecorrido(btnSunbmit)
                }else{ // Pregunto si quiere crearlo igual
                    Swal.fire({
                        title: "El guía tiene otro recorrido asignado, ¿desea asignarle este recorrido?",
                        text: "",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Si",
                        cancelButtonText: "No"
                    }).then(result => {
                        if (!result.isConfirmed) {
                            btnSunbmit.reset()
                            return
                        }

                        saveRecorrido(btnSunbmit)
                    })
                }
            })
        });
    }

    function saveRecorrido(btnSunbmit){
        fetch(
            "<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: new FormData(document.getElementById("formRecorrido"))
            }
        )
        .then(res => res.json())
        .then(({status,title,message,type}) => {
            btnSunbmit.reset()

            Swal.fire(title, message, type).then(res => {
                if (status == "OK") HTTP.redirect("<?= DOMAIN_ADMIN ?>recorridos/")
            })
        })
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
