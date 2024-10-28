<?
require_once __DIR__ . "/../../config/init.php";

$section = "alojamientos";

$titlePage = isset($_GET["id"]) ? "Editar alojamiento" : "Crear alojamiento";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

$alojamiento = isset($_GET["id"]) ? Alojamiento::getById($_GET["id"]) : null;

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>
            
            <div class="row">
                <div class="col-md-8">
                    <? if ($alojamiento): ?>
                        <iframe 
                            id="mapIframe" 
                            src="<?=DOMAIN_ADMIN?>alojamientos/map.readonly.iframe.php?v=<?=date("Ymd_His", filemtime(PATH_SERVER.'admin/alojamientos/map.readonly.iframe.php'))?>?>&latitud=<?=$alojamiento->latitud?>&longitud=<?=$alojamiento->longitud?>" 
                            frameborder="0" 
                            class="w-100" 
                            style="height: 500px;"
                        >
                        </iframe>
                    <? else: ?>
                        <iframe 
                            id="mapIframe" 
                            src="<?=DOMAIN_ADMIN?>alojamientos/map.iframe.php?handlerNewMarker=mapbox__handlerNewMarker&handlerDeleteMarker=mapbox__handlerDeleteMarker&v=<?=date("Ymd_His", filemtime(PATH_SERVER.'admin/alojamientos/map.iframe.php'))?>?>" 
                            frameborder="0" 
                            class="w-100" 
                            style="height: 500px;"
                        >
                        </iframe>
                    <? endif; ?>
                </div>

                <div class="col-md-4">
                    <form action="" class="row" id="formMap">

                        <div class="col-12 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" oninput="FormController.validateForm(this, 3)" value="<?=$alojamiento ? $alojamiento->nombre : ""?>">
                                <label for="nombre"><i class="<?=$menu->{$section}->icon?> me-1"></i>Nombre</label>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control <?=$alojamiento ? "text-secondary" : ""?>" id="direccion" name="direccion" placeholder="Dirección" oninput="FormController.validateForm(this, 3)" value="<?=$alojamiento ? $alojamiento->direccion : ""?>" <?=$alojamiento ? "readonly" : ""?>>
                                <label for="direccion"><i class="bi bi-signpost me-1"></i>Dirección</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control text-secondary" id="longitud" name="longitud" placeholder="Longitud" readonly oninput="FormController.validateForm(this)" value="<?=$alojamiento ? $alojamiento->longitud : ""?>">
                                <label for="longitud"><i class="bi bi-globe-americas me-1"></i>Longitud</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control text-secondary" id="latitud" name="latitud" placeholder="Latitud" readonly oninput="FormController.validateForm(this)" value="<?=$alojamiento ? $alojamiento->latitud : ""?>">
                                <label for="latitud"><i class="bi bi-globe-americas me-1"></i>Latitud</label>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Descripción (opcional)" name="descripcion" id="descripcion" style="height: 150px"><?=$alojamiento ? $alojamiento->descripcion : ""?></textarea>
                                <label for="descripcion"><i class="bi bi-text-left me-1"></i>Descripción (opcional)</label>
                            </div>
                        </div>

                        <input type="hidden" name="table" value="alojamientos">
                        <input type="hidden" name="pk" value="idAlojamiento">
                        <input type="hidden" name="idAlojamiento" value="<?=$_GET["id"] ?? ""?>">
                        <input type="hidden" name="action" value="save">

                        <input type="hidden" name="response_title" value="<?=$alojamiento ? "Alojamiento modificado!" : "Alojamiento agregado!"?>">

                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="handlerSaveForm(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="HTTP.redirect('<?=DOMAIN_ADMIN?>alojamientos')"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>


<script>

    document.addEventListener("DOMContentLoaded", e => {
        dispatchTriggerInputs()
    })

    function dispatchTriggerInputs(){
        HTMLController.trigger("#nombre,#direccion,#longitud,#latitud", "input")
    }

    // Chequea que haya un marker en el mapa
    function mapbox__hasMarker(){
        return !!document.getElementById("mapIframe").contentWindow.marker
    }
    // Se llama a esta funcion en el iframe del mapa
    function mapbox__handlerNewMarker(data){
        document.getElementById("nombre").value = data.name
        document.getElementById("direccion").value = data.direction
        document.getElementById("latitud").value = data.coordinates.lat
        document.getElementById("longitud").value = data.coordinates.lng
        dispatchTriggerInputs()
    }
    function mapbox__handlerDeleteMarker(){
        document.getElementById("nombre").value = ""
        document.getElementById("direccion").value = ""
        document.getElementById("latitud").value = ""
        document.getElementById("longitud").value = ""
        dispatchTriggerInputs()
    }

    function handlerSaveForm(elBtn) {

        // Valido el formulario
        if(document.querySelectorAll("input.is-invalid,select.is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Revise los campos marcados en rojo para continuar", "warning")
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
            if(!result.isConfirmed) return
            
            let btnSubmit = new FormButtonSubmitController(elBtn, false)
            btnSubmit.init()

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: new FormData(document.getElementById("formMap"))
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                btnSubmit.reset()

                Swal.fire(title, message, type).then(res => {
                    if(status == "OK") HTTP.redirect("<?=DOMAIN_ADMIN?>alojamientos/")
                })
            })
        })

    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
