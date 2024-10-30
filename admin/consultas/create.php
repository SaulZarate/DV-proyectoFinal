<?
require_once __DIR__ . "/../../config/init.php";

$section = "consultas";
$subSection = "Nueva consulta";

$titlePage = "Nueva consulta";
$iconPage = "fa fa-plus";

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">
            
            <form id="form" class="g-3">

                <h5 class="card-title m-0"><i class="<?= $menu->{$section}->icon ?> me-1"></i>Datos de la consulta</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" name="asunto" id="asunto" oninput="FormController.validateForm(this, 3)">
                    </div>
                    <div class="col-md-3">
                        <label for="idCliente" class="form-label">Cliente</label>
                        <select name="idCliente" id="idCliente" class="form-select" oninput="FormController.validateForm(this)">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="idUsuario" class="form-label">Asignado</label>
                        <select name="idUsuario" id="idUsuario" class="form-select">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="origen" class="form-label">Origen</label>
                        <select name="origen" id="origen" class="form-select" oninput="FormController.validateForm(this)">
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="idPaquete" class="form-label">Excursión</label>
                        <select name="idPaquete" id="idPaquete" class="form-select" oninput="FormController.validateForm(this)">
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="idPaqueteFechaSalida" class="form-label">Fecha de salida</label>
                        <select name="idPaqueteFechaSalida" id="idPaqueteFechaSalida" class="form-select" oninput="FormController.validateForm(this)">
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="hasTraslado" class="form-label">Con traslado desde el alojamiento</label>
                        <select name="hasTraslado" id="hasTraslado" class="form-select" oninput="FormController.validateForm(this)">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="idAlojamiento" class="form-label">Alojamiento</label>
                        <select name="idAlojamiento" id="idAlojamiento" class="form-select" oninput="FormController.validateForm(this)">
                        </select>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="card-title m-0"><i class="bi bi-chat-dots me-1"></i>Datos de contacto adicionales</h5>

                        <p>Descripción | Medio</p>
                    </div>

                    <div class="col-md-12">
                        <h5 class="card-title m-0"><i class="<?= $menu->clientes->icon ?> me-1"></i>Pasajeros</h5>

                        <p>Nombre | Apellido | sexo | fecha de nacimiento | observaciones</p>
                    </div>
                </div>


                <input type="hidden" name="estado" value="A">

                <input type="hidden" name="idConsulta" value="">
                <input type="hidden" name="pk" value="idConsulta">
                <input type="hidden" name="table" value="consultas">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="response_title" value="Consulta creada!">

                <div class="mt-4">
                    <button type="button" class="btn btn-primary" onclick="handlerSaveForm(this)"><i class="fa fa-save me-1"></i>Guardar</button>
                    <a class="btn btn-secondary" href="<?=DOMAIN_ADMIN?>consultas"><i class="fa fa-times-circle me-1"></i>Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</section>


<script>
    const form = document.getElementById("form")

    document.addEventListener("DOMContentLoaded", e => {
        HTMLController.trigger("#nombre,#apellido,#password,#email,#codPais,#codArea,#telefono,#dni,#nacionalidad,#sexo,#fechaDeNacimiento", "input")
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
            if(!result.isConfirmed) return
            
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
                    if(status == "OK") HTTP.redirect("<?=DOMAIN_ADMIN?>consultas/")
                })
            })
        })

    }

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
