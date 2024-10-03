<?
require_once __DIR__ . "/../../config/init.php";

$section = "usuarios";

$titlePage = isset($_GET["id"]) ? "Editar usuario" : "Crear usuario";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

$user = isset($_GET["id"]) ? Usuario::getById($_GET["id"]) : null;

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>

            <!-- Multi Columns Form -->
            <form id="form" class="row g-3">

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" oninput="FormController.validateForm(this, 3)" value="<?=$user ? $user->nombre : ""?>">
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" name="apellido" id="apellido" oninput="FormController.validateForm(this, 3)" value="<?=$user ? $user->apellido : ""?>">
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" oninput="FormController.validateForm(this)" value="<?=$user ? $user->email : ""?>">
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña<i class="fa fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="La contraseña debe tener como mínimo 8 digitos, 1 , 1 minúscula y 1 caracter especial"></i></label>
                    <input type="password" class="form-control" name="password" id="password" oninput="validatePassword(this)" value="">
                    <? if ($user): ?>
                        <small class="text-secondary">Si completa este campo cambiara la contraseña</small>
                    <? endif; ?>
                </div>

                <div class="col-12">
                    <label for="inputEmail5" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <input type="tel" class="form-control" name="codPais" id="codPais" oninput="FormController.validateForm(this)" style="width:20%" value="<?=$user ? $user->codPais : ""?>">
                        <input type="tel" class="form-control" name="codArea" id="codArea" oninput="FormController.validateForm(this)" style="width:20%" value="<?=$user ? $user->codArea : ""?>">
                        <input type="tel" class="form-control" name="telefono" id="telefono" oninput="FormController.validateForm(this)" style="width:60%" value="<?=$user ? $user->telefono : ""?>">
                    </div>
                    <small class="text-secondary">Cod país + Cod área + Número de teléfono</small>
                </div>

                <div class="col-md-6">
                    <label for="tipo" class="form-label">Rol del usuario</label>
                    <select name="tipo" id="tipo" class="form-select" oninput="FormController.validateForm(this)">
                        <option value="">-- Seleccione un rol --</option>
                        <option value="0" <?=$user && $user->tipo == "0" ? "selected" : "" ?>>Administrador</option>
                        <option value="1" <?=$user && $user->tipo == "1" ? "selected" : "" ?>>Vendedor</option>
                        <option value="2" <?=$user && $user->tipo == "2" ? "selected" : "" ?>>Guía</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" oninput="FormController.validateForm(this)">
                        <option value="I" <?=$user && $user->estado == "I" ? "selected" : "" ?>>Inactivo</option>
                        <option value="A" <?=$user && $user->estado == "A" ? "selected" : "" ?>>Activo</option>
                    </select>
                </div>


                <input type="hidden" name="idUsuario" value="<?= $_GET["id"] ?? '' ?>">
                <input type="hidden" name="pk" value="idUsuario">
                <input type="hidden" name="table" value="usuarios">
                <input type="hidden" name="action" value="usuario_save">

                <div class="mt-4">
                    <button type="button" class="btn btn-primary" onclick="handlerSaveForm()"><i class="fa fa-save me-1"></i>Guardar</button>
                    <button type="button" class="btn btn-secondary" onclick="HTTP.backURL()"><i class="fa fa-times-circle me-1"></i>Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</section>


<script>
    const form = document.getElementById("form")
    const isNewUser = <?=isset($_GET["id"]) ? "false" : "true"?>

    document.addEventListener("DOMContentLoaded", e => {
        FormController.trigger("#nombre,#apellido,#password,#email,#codPais,#codArea,#telefono,#tipo", "input")
    })

    function validatePassword(element){
        element.classList.remove("is-invalid")

        if(isNewUser || (!isNewUser && element.value.length > 0)){
            if(isPasswordValid(element.value)) element.classList.remove("is-invalid")
            else element.classList.add("is-invalid")
        }
    }

    function handlerSaveForm() {

        // Valido el formulario
        if(document.querySelectorAll("input.is-invalid,select.is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Modifique los campos marcados en rojo para continuar", "warning")
            return
        }
        
        HTMLController.setProp("button", {disabled: true})

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
                HTMLController.setProp("button", {disabled: false})
                return
            }

            fetch(
                "<?= DOMAIN_NAME ?>admin/process.php", 
                {
                    method: "POST", 
                    body: new FormData(form)
                }
            )
            .then(res => res.json())
            .then(({status, title, message, type}) => {
                HTMLController.setProp("button", {disabled: false})

                Swal.fire(title, message, type).then(res => {
                    if(status == "OK" && isNewUser){
                        HTTP.redirect("<?=DOMAIN_NAME?>admin/usuarios/")
                    }
                })
            })
        })

    }

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
