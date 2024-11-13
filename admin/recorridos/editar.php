<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";
$isNew = !isset($_GET["id"]);

$title = ($isNew ? "Crear" : "Editar") . " recorrido | " . APP_NAME;

$recorrido = $isNew ? null : Recorrido::getByIdAllInfo($_GET["id"]);

ob_start();
?>


<section class="section">
    <!-- <div class="card">
        <form class="card-body" method="GET">
            <h5 class="card-title"><i class="fa fa-filter me-1"></i>Filtros</h5>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idPaquete" id="idPaquete" class="form-select">
                            <option value="">Todas</option>
                            <? foreach (Paquete::getAll(["order" => "p.titulo ASC"]) as $paquete): ?>
                                <option value="<?=$paquete->idPaquete?>" <?=$idPaquete == $paquete->idPaquete ? "selected" : ""?>><?=$paquete->titulo?></option>
                            <? endforeach; ?>
                        </select>
                        <label for="idPaquete" class="mb-1">Excursión</label>
                    </div>                    
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idProvincia" id="idProvincia" class="form-select">
                            <option value="">Todas</option>
                            <? foreach (DB::getAll("SELECT * FROM provincias ORDER BY nombre") as $provincia): ?>
                                <option value="<?=$provincia->idProvincia?>" <?=$idProvincia == $provincia->idProvincia ? "selected" : ""?>><?=ucfirst($provincia->nombre)?></option>
                            <? endforeach; ?>
                        </select>
                        <label for="idProvincia" class="mb-1">Provincia de destino</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="date" name="fechaSalida" id="fechaSalida" class="form-control" value="<?=$fechaSalida?>">
                        <label for="fechaSalida" class="mb-1">Fecha de salida</label>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit"><i class="fa fa-save me-1"></i>Filtrar</button>
            <a class="btn btn-primary" href="<?=DOMAIN_ADMIN?>salidas/"><i class="fa fa-eraser me-1"></i>Limpiar</a>
        </form>
    </div> -->

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
                            <select name="idPaquete" id="idPaquete" class="form-select is-invalid" oninput="FormController.validateForm(this)" onchange="handlerChangePaquete(this)">
                                <option value="">-- Seleccione una excursión --</option>
                                <? foreach (Paquete::getAll(["where" => "estado = 'A'", "order" => "nombre ASC"]) as $paquete): 
                                    // Si no tiene fechas de salida lo ignoro
                                    if(!Paquete::getAllFechasSalida($paquete->idPaquete, date("Y-m-d"))) continue;
                                    ?>
                                    <option value="<?= $paquete->idPaquete ?>" data-traslado="<?=$paquete->traslado?>"><?= ucfirst($paquete->titulo) ?></option>
                                <? endforeach; ?>
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
                            <select name="fecha" id="fecha" class="form-select is-invalid" oninput="FormController.validateForm(this)">
                                <option value="">-- Seleccione una excursión --</option>
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

                <? if ($isNew): ?><input type="hidden" name="created_by_idUsuario" value="<?=$_SESSION["user"]["idUsuario"]?>"><? endif; ?>

                <div class="col-12 mt-2">
                    <button type="button" class="btn btn-primary" onclick="handlerSubmit(this)"><i class="fa fa-plus me-1"></i>Crear</button>
                    <a href="<?=DOMAIN_ADMIN?>recorridos" class="btn btn-secondary"><i class="fa fa-times-circle me-1"></i>Salir</a>
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

            for (const {id,fecha,cupos,disponibles,hasSalida} of result.fechas) {

                const [anio, mes, dia] = fecha.split("-")
                const disabled = disponibles === 0 || hasSalida === 1 ? "disabled" : ""
                const textCupos = hasSalida === 1 ? "Tiene salida creada" : "disponible"

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
