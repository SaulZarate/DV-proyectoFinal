<?
require_once __DIR__ . "/../../config/init.php";

$section = "alojamientos";

$titlePage = isset($_GET["id"]) ? "Editar alojamiento" : "Crear alojamiento";
$iconPage = isset($_GET["id"]) ? "fa fa-pencil" : "fa fa-plus";

/* $alojamiento = isset($_GET["id"]) ? Cliente::getById($_GET["id"]) : null; */
$alojamiento = null;

$title = $titlePage . " | " . APP_NAME;

ob_start();
?>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="<?= $iconPage ?> me-1"></i><?= $titlePage ?></h5>

            <div class="row">
                <div class="col-md-6">
                    <iframe 
                        class="w-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4020.332706029793!2d-58.37525582183659!3d-34.592985907793654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccab51a122e41%3A0x2872fc9ac9a5fe37!2sSheraton%20Buenos%20Aires%20Hotel%20%26%20Convention%20Center!5e0!3m2!1ses!2sar!4v1729807787241!5m2!1ses!2sar" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="col-md-6">

                </div>
            </div>

        </div>
    </div>
</section>


<script>

    document.addEventListener("DOMContentLoaded", e => {
        //HTMLController.trigger("#nombre,#apellido,#password,#email,#codPais,#codArea,#telefono,#dni,#nacionalidad,#sexo,#fechaDeNacimiento", "input")
    })

    /* function handlerSaveForm(elBtn) {

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

    } */

</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
