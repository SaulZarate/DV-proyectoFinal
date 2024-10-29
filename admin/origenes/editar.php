<?
require_once __DIR__ . "/../../config/init.php";

$section = "origenes";

$titlePage = isset($_GET["id"]) ? "Editar origen" : "Crear origen";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

$origen = isset($_GET["id"]) ? Origen::getById($_GET["id"]) : null;

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>

            <!-- Multi Columns Form -->
            <form id="form" class="row g-3">

                <div class="col-md-10">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" oninput="FormController.validateForm(this, 3)" value="<?=$origen ? $origen->nombre : ""?>">
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" oninput="FormController.validateForm(this)">
                        <option value="A" <?=$origen && $origen->estado == "A" ? "selected" : "" ?>>Activo</option>
                        <option value="I" <?=$origen && $origen->estado == "I" ? "selected" : "" ?>>Inactivo</option>
                    </select>
                </div>

                <input type="hidden" name="idOrigen" value="<?= $_GET["id"] ?? '' ?>">
                <input type="hidden" name="pk" value="idOrigen">
                <input type="hidden" name="table" value="origenes">
                <input type="hidden" name="action" value="save">

                <div class="mt-4">
                    <button type="button" class="btn btn-primary" onclick="handlerSaveForm(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                    <a class="btn btn-secondary" href="<?=DOMAIN_ADMIN?>origenes"><i class="fa fa-times-circle me-1"></i>Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</section>


<script>
    const form = document.getElementById("form")
    const isNewOgiren = <?=isset($_GET["id"]) ? "false" : "true"?>

    document.addEventListener("DOMContentLoaded", e => {
        HTMLController.trigger("#nombre", "input")
    })

    function handlerSaveForm(elBtn) {

        // Valido el formulario
        if(document.querySelectorAll("input.is-invalid,select.is-invalid").length > 0){
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
            if(!result.isConfirmed) {
                return
            }
            
            let btnSubmit = new FormButtonSubmitController(elBtn, false)
            btnSubmit.init()

            fetch(
                "<?= DOMAIN_ADMIN ?>process.php", 
                {
                    method: "POST", 
                    body: new FormData(form)
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                btnSubmit.reset()

                Swal.fire(title, message, type).then(res => {
                    if(status == "OK" && isNewOgiren) HTTP.redirect("<?=DOMAIN_ADMIN?>origenes")
                    if(status == "OK" && !isNewOgiren) location.reload()
                })
            })
        })

    }

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
