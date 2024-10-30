<?
require_once __DIR__ . "/../../config/init.php";

$section = "consultas";
$title = "Consultas | " . APP_NAME;

$subSection = "Abiertos";
if($_GET["s"] == "C") $subSection = "Cerradas";

$consultas = [];
ob_start();
?>


<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    
                    <div class="row mb-3">
                        
                        <div class="col">
                            <h5 class="card-title pb-0"><i class="<?=$menu->{$section}->icon?> me-1"></i><?=ucfirst($section)?></h5>
                            <p class="text-secondary pb-0 mb-2">Utiliza la siguiente vista para crear, modificar o eliminar <?=$section?> del sistema.</p>
                        </div>
    
                    </div>

                    <!-- ------------------- -->
                    <!--        TABLE        -->
                    <!-- ------------------- -->
                     <div class="table-responsive">
                        <table class="table table-hover" id="tableConsultas">
                            <thead class="">
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Asunto</th>
                                    <th>Pax</th>
                                    <th>Paquete</th>
                                    <th>Estado</th>
                                    <th>Última modificación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? if (!$consultas): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Sin <?=$section?></td>
                                    </tr>
                                <? endif; ?>

                                <? foreach ($consultas as $consulta): ?>
                                <tr id="consulta-<?=$consulta->idOrigen?>">
                                    <td>
                                        <a href="./editar?id=<?=$consulta->idOrigen?>"><i class="bi bi-pencil" data-bs-toggle="tooltip" title="Editar"></i></a>
                                        <button type="button" class="text-danger bg-transparent border-0 btnDelete" onclick="handlerDelete(<?=$consulta->idOrigen?>)"><i class="bi bi-trash" data-bs-toggle="tooltip" title="Eliminar"></i></button>
                                    </td>
                                    <td>
                                        <p class="m-0"><?=ucfirst($consulta->nombre)?></p>
                                    </td>
                                    <td>
                                        <p class='badge bg-primary m-0'><?=$consulta->estado == "A" ? "Activo" : "Inactivo"?></p>
                                    </td>
                                </tr>
                            <? endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                
                </div>
            </div>
        
        </div>
    </div>
</section>

<script>
    var dataTableUsers = null

    document.addEventListener("DOMContentLoaded", e => {
        //initDataTable()
    })

    function initDataTable(){
        dataTableUsers = new simpleDatatables.DataTable("#tableConsultas", {
            labels: {
                placeholder: "Buscador...",
                searchTitle: "Search within table",
                pageTitle: "Page {page}",
                perPage: "resultados por página",
                noRows: "Sin filas encontradas",
                info: "<?=ucfirst($section)?>: {rows}",
                noResults: "No hay resultados",
            },
            perPageSelect: [5, 10, 25, 50, 100, ["Todos", -1]],
            fixedHeight: true
        })
    }

    function handlerDelete(id){

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

            // Deshabilito todos los botones
            HTMLController.setProp(".btnDelete", {disabled: true})

            let formData = new FormData()
            formData.append("action", "delete")
            formData.append("pk", "idConsulta")
            formData.append("table", "origenes")
            formData.append("idConsulta", id)

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: formData,
            })
            .then(res => res.json())
            .then(response => {
                
                HTMLController.setProp(".btnDelete", {disabled: false})

                const {title,message,type,status} = response

                Swal.fire(title, message, type).then(res => {
                    if (status === "OK") document.getElementById(`consulta-${id}`).remove()
                })
                
            })
        });
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
