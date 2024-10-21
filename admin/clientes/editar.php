<?
require_once __DIR__ . "/../../config/init.php";

$section = "clientes";

$titlePage = isset($_GET["id"]) ? "Editar cliente" : "Crear cliente";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

$client = isset($_GET["id"]) ? Cliente::getById($_GET["id"]) : null;

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>

            <!-- Multi Columns Form -->
            <form id="form" class="row g-3">

                <div class="col-md-5">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" oninput="FormController.validateForm(this, 3)" value="<?=$client ? $client->nombre : ""?>">
                </div>
                <div class="col-md-5">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" name="apellido" id="apellido" oninput="FormController.validateForm(this, 3)" value="<?=$client ? $client->apellido : ""?>">
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" oninput="FormController.validateForm(this)">
                        <option value="A" <?=$client && $client->estado == "A" ? "selected" : "" ?>>Activo</option>
                        <option value="I" <?=$client && $client->estado == "I" ? "selected" : "" ?>>Inactivo</option>
                    </select>
                </div>


                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" oninput="FormController.validateForm(this)" value="<?=$client ? $client->email : ""?>" autocomplete="new-email">
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña<i class="fa fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="La contraseña debe tener como mínimo 8 digitos, 1 , 1 minúscula y 1 caracter especial"></i></label>
                    <input type="password" class="form-control" name="password" id="password" oninput="validatePassword(this)" value="" autocomplete="new-password">
                    <? if ($client): ?>
                        <small class="text-secondary">Si completa este campo cambiara la contraseña</small>
                    <? endif; ?>
                </div>

                <div class="col-12">
                    <label for="inputEmail5" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <input type="tel" class="form-control" name="codPais" id="codPais" oninput="FormController.validateForm(this)" style="width:20%" value="<?=$client ? $client->codPais : ""?>">
                        <input type="tel" class="form-control" name="codArea" id="codArea" oninput="FormController.validateForm(this)" style="width:20%" value="<?=$client ? $client->codArea : ""?>">
                        <input type="tel" class="form-control" name="telefono" id="telefono" oninput="FormController.validateForm(this)" style="width:60%" value="<?=$client ? $client->telefono : ""?>">
                    </div>
                    <small class="text-secondary">Cod país + Cod área + Número de teléfono</small>
                </div>

                

                <div class="col-md-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="dni" class="form-control" name="dni" id="dni" oninput="FormController.validateForm(this, 8)" value="<?=$client ? $client->dni : ""?>">
                </div>
                <div class="col-md-3">
                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                    <select name="nacionalidad" id="nacionalidad" class="form-select" oninput="FormController.validateForm(this)">
                        <option value="">-- Seleccione un país --</option>
                        <? foreach (DB::getAll("SELECT * FROM paises ORDER BY nombre") as $pais): ?>
                            <option value="<?=$pais->id?>" <?=$client && $client->nacionalidad == $pais->id ? "selected" : "" ?>><?=ucfirst($pais->nombre)?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sexo" class="form-label">Sexo</label>
                    <select name="sexo" id="sexo" class="form-select" oninput="FormController.validateForm(this)">
                        <option value="">-- Seleccione una opción --</option>
                        <option value="M" <?=$client && $client->sexo == "M" ? "selected" : "" ?>>Masculino</option>
                        <option value="F" <?=$client && $client->sexo == "F" ? "selected" : "" ?>>Femenino</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fechaDeNacimiento" class="form-label">Fecha de nacimiento</label>
                    <input type="date" class="form-control" id="fechaDeNacimiento" name="fechaDeNacimiento" oninput="FormController.validateForm(this)" value="<?=$client && $client->fechaDeNacimiento ? $client->fechaDeNacimiento : ""?>">
                </div>


                <input type="hidden" name="idCliente" value="<?= $_GET["id"] ?? '' ?>">
                <input type="hidden" name="pk" value="idCliente">
                <input type="hidden" name="table" value="clientes">
                <input type="hidden" name="action" value="cliente_save">

                <div class="mt-4">
                    <button type="button" class="btn btn-primary" onclick="handlerSaveForm(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                    <a class="btn btn-secondary" href="<?=DOMAIN_ADMIN?>clientes"><i class="fa fa-times-circle me-1"></i>Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</section>


<script>
    const form = document.getElementById("form")
    const isNewClient = <?=isset($_GET["id"]) ? "false" : "true"?>

    document.addEventListener("DOMContentLoaded", e => {
        HTMLController.trigger("#nombre,#apellido,#password,#email,#codPais,#codArea,#telefono,#dni,#nacionalidad,#sexo,#fechaDeNacimiento", "input")
    })

    function validatePassword(element){
        element.classList.remove("is-invalid")

        if(isNewClient || (!isNewClient && element.value.length > 0)){
            if(isPasswordValid(element.value)) element.classList.remove("is-invalid")
            else element.classList.add("is-invalid")
        }
    }

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
                    if(status == "OK" && isNewClient) HTTP.redirect("<?=DOMAIN_ADMIN?>clientes/")
                    if(status == "OK" && !isNewClient) location.reload()
                })
            })
        })

    }

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
