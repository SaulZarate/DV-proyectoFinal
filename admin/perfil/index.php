<?
require_once __DIR__ . "/../../config/init.php";

$section = "blank";
$title = "Page template | " . APP_NAME;
ob_start();
?>

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">

            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="<?= DOMAIN_NAME ?>assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                    <h2><?= ucfirst($_SESSION["user"]["nombre"]) . " " . ucfirst($_SESSION["user"]["apellido"]) ?></h2>
                    <h3><?= ucfirst(Auth::getRoleName()) ?></h3>
                </div>
            </div>

        </div>

        <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">

                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview"><i class="fa fa-address-card me-1"></i>Mi perfil</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit"><i class="fa fa-pencil me-1"></i>Editar perfil</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"><i class="fa fa-unlock-alt me-1"></i>Cambiar contraseña</button>
                        </li>
                    </ul>

                    <!-- Content tabs -->
                    <div class="tab-content pt-2">

                        <!-- Detalle perfil -->
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">

                            <? if ($_SESSION["user"] && $_SESSION["user"]["descripcion"]): ?>
                                <h5 class="card-title mb-0">Sobre mí</h5>
                                <div class="bg-light" id="contentAbout"><?= html_entity_decode($_SESSION["user"]["descripcion"]) ?></div>
                            <? endif; ?>

                            <h5 class="card-title">Información del perfil</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Nombre</div>
                                <div class="col-lg-9 col-md-8"><?= ucfirst($_SESSION["user"]["nombre"]) . " " . ucfirst($_SESSION["user"]["apellido"]) ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Email</div>
                                <div class="col-lg-9 col-md-8"><?= $_SESSION["user"]["email"] ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Teléfono</div>
                                <div class="col-lg-9 col-md-8">+<?= $_SESSION["user"]["codPais"] . " " . $_SESSION["user"]["codArea"] . " " . $_SESSION["user"]["telefono"] ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">DNI</div>
                                <div class="col-lg-9 col-md-8"><?= $_SESSION["user"]["dni"] ?? "-" ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Fecha de nacimiento</div>
                                <div class="col-lg-9 col-md-8"><?= $_SESSION["user"]["fechaNacimiento"] ? date("d/m/Y", strtotime($_SESSION["user"]["fechaNacimiento"])) : "-" ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Nacionalidad</div>
                                <div class="col-lg-9 col-md-8"><?= $_SESSION["user"]["nacionalidad"] ?? "-" ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Sexo</div>
                                <div class="col-lg-9 col-md-8"><?= $_SESSION["user"]["sexo"] ?? "-" ?></div>
                            </div>

                        </div>

                        <!-- Editar -->
                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                            <!-- Profile Edit Form -->
                            <form id="formProfile">

                                <div class="row mb-3">
                                    <label for="nombre" class="col-md-4 col-lg-3 col-form-label">Nombre</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nombre" type="text" class="form-control" id="nombre" value="<?= $_SESSION["user"]["nombre"] ?>" oninput="FormController.validateForm(this, 3)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="apellido" class="col-md-4 col-lg-3 col-form-label">Apellido</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="apellido" type="text" class="form-control" id="apellido" value="<?= $_SESSION["user"]["apellido"] ?>" oninput="FormController.validateForm(this, 3)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="Email" value="<?= $_SESSION["user"]["email"] ?>" oninput="FormController.validateForm(this)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Country" class="col-md-4 col-lg-3 col-form-label">Teléfono</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div class="input-group">
                                            <input type="tel" name="codPais" class="form-control" value="<?= $_SESSION["user"]["codPais"] ? $_SESSION["user"]["codPais"] : "" ?>"  oninput="FormController.validateForm(this)" style="width:20%">
                                            <input type="tel" name="codArea" class="form-control" value="<?= $_SESSION["user"]["codArea"] ? $_SESSION["user"]["codArea"] : "" ?>"  oninput="FormController.validateForm(this)" style="width:20%">
                                            <input type="tel" name="telefono" class="form-control" value="<?= $_SESSION["user"]["telefono"] ? $_SESSION["user"]["telefono"] : "" ?>"  oninput="FormController.validateForm(this)" style="width:60%">
                                        </div>
                                        <small class="text-secondary">Cod país + Cod área + Número</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="dni" class="col-md-4 col-lg-3 col-form-label">DNI</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="dni" type="number" class="form-control" id="dni" value="<?= $_SESSION["user"]["dni"] ?>" oninput="FormController.validateForm(this, 8)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fechaNacimiento" class="col-md-4 col-lg-3 col-form-label">Fecha de nacimiento</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="fechaNacimiento" type="date" class="form-control" id="fechaNacimiento" value="<?= $_SESSION["user"]["fechaNacimiento"] ?>" oninput="FormController.validateForm(this)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nacionalidad" class="col-md-4 col-lg-3 col-form-label">Nacionalidad</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="nacionalidad" type="text" class="form-control" id="nacionalidad" value="<?= $_SESSION["user"]["nacionalidad"] ?>" oninput="FormController.validateForm(this, 3)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="sexo" class="col-md-4 col-lg-3 col-form-label">Sexo</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="sexo" type="text" class="form-control" id="sexo" value="<?= $_SESSION["user"]["sexo"] ?>" oninput="FormController.validateForm(this, 3)">
                                    </div>
                                </div>

                                <hr class="mb-2">
                                <div class="contentAbout mb-3">
                                    <p class="text-center mb-2 mt-0"><label for="">Sobre mí</label></p>
                                    <div id="about"><?=$_SESSION["user"]["descripcion"] ? html_entity_decode($_SESSION["user"]["descripcion"]) : ""?></div>
                                </div>
                                

                                <input type="hidden" name="table" value="usuarios">
                                <input type="hidden" name="pk" value="idUsuario">
                                <input type="hidden" name="action" value="usuario_update">

                                <div class="d-grid gap-1">
                                    <button type="button" class="btn btn-primary" onclick="handlerSaveProfile()"><i class="fa fa-save me-1"></i>Guardar cambios</button>
                                </div>
                            </form><!-- End Profile Edit Form -->

                        </div>

                        <!-- Cambiar contraseña -->
                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <form id="formPassword">

                                <div class="row mb-3">
                                    <label for="newPassword" class="col-md-5 col-lg-4 col-form-label">Nueva contraseña<i class="fa fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-original-title="La contraseña debe tener como mínimo 8 digitos, 1 , 1 minúscula y 1 caracter especial"></i></label>
                                    <div class="col">
                                        <input name="newPassword" type="password" class="form-control" id="newPassword" oninput="FormController.validateForm(this)">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="renewPassword" class="col-md-5 col-lg-4 col-form-label">Repetir contraseña</label>
                                    <div class="col">
                                        <input name="reNewPassword" type="password" class="form-control" id="renewPassword" oninput="FormController.validateForm(this)">
                                    </div>
                                </div>

                                <input type="hidden" name="action" value="usuario_changePassword">

                                <div class="d-grid gap-1">
                                    <button type="button" class="btn btn-primary" onclick="changePassword()"><i class="fa fa-save me-1"></i>Cambiar contraseña</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<script>
    let textArea = null

    document.addEventListener("DOMContentLoaded", e => {
        HTMLController.trigger("#nombre,#apellido,#email,#codPais,#codArea,#telefono,#dni,#fechaNacimiento,#nacionalidad,#sexo", "input")

        <? if ($_SESSION["user"]["descripcion"]): ?>
            TextareaEditor.initOnlyShow("#contentAbout")
        <? endif; ?>

        // Editar perfil | Inicio el textarea
        textArea = new TextareaEditor("#about")
        textArea.initBasic()
    })


    /* ------------------------------- */
    /*          SECTION EDITAR         */
    /* ------------------------------- */
    function handlerSaveProfile(){
        const form = document.getElementById("formProfile")
        
        // Valido el formulario
        if(document.querySelectorAll("input.is-invalid,select.is-invalid").length > 0){
            Swal.fire("Campos invalidos!", "Revise todos los campos marcados en rojo para continuar", "warning")
            return
        }

        let formData = new FormData(form)
        formData.append("descripcion", textArea.getHTML())

        // Pido confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            // Cambio la contraseña
            fetch(
                "<?= DOMAIN_NAME ?>admin/process.php", {
                    method: "POST",
                    body: formData,
                }
            )
            .then(res => res.json())
            .then(response => {
                const {title, message, type, status} = response

                Swal.fire(title, message, type).then(res => {
                    /* if(status === "OK"){
                        location.reload()
                    } */
                })
            })
        });
    }


    /* ----------------------------------------- */
    /*          SECTION NUEVA CONTRASEÑA         */
    /* ----------------------------------------- */
    function changePassword() {
        const formPassword = document.getElementById("formPassword")
        const inputPassword = document.getElementById("newPassword")
        const inputRepetPassword = document.getElementById("renewPassword")

        if (inputPassword.value !== inputRepetPassword.value) {
            inputRepetPassword.classList.add("is-invalid")
            Swal.fire("Campos invalidos!", "Las contraseñas no coinciden, por favor revise los campos y vuelva a intentarlo", "warning")
            return
        }

        if(!isPasswordValid(inputPassword.value)){
            inputPassword.classList.add("is-invalid")
            inputRepetPassword.classList.add("is-invalid")
            Swal.fire("Contraseña invalida!", "La contraseña no cumple con los caracteres pedidos.", "warning")
            return
        }

        // Pido confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) return

            // Cambio la contraseña
            fetch(
                "<?= DOMAIN_NAME ?>admin/process.php", {
                    method: "POST",
                    body: new FormData(formPassword),
                }
            )
            .then(res => res.json())
            .then(response => {
                const {
                    title,
                    message,
                    type,
                    status
                } = response

                Swal.fire(title, message, type).then(res => {
                    if (status == "OK") {
                        formPassword.reset()
                    }
                })

            })
        });



    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
