<?
require_once __DIR__ . "/../../config/init.php";

$section = "alojamientos";
$title = "Alojamientos | " . APP_NAME;

$alojamientos = Alojamiento::getAll();

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

                        <div class="col-md-3 d-flex align-items-center justify-content-start justify-content-md-end">
                            <button class="btn btn-primary btn-sm" type="button" onclick="HTTP.redirect('<?=HTTPController::getCurrentURL()?>editar')"><i class="fa fa-plus me-1"></i>Agregar</button>
                        </div>
    
                    </div>
                    

                    

                    <!-- ------------------- -->
                    <!--        TABLE        -->
                    <!-- ------------------- -->
                    <div class="table-responsive">
                        <table class="table" id="tableAlojamientos">
                            <thead>
                                <tr>
                                    <th style="width: 10%;"></th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Descripción</th>
                                    <th data-type="date" data-format="DD/MM/YYYY/">Fecha de creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? foreach ($alojamientos as $alojamiento): ?>
                                    <tr id="alojamiento-<?=$alojamiento->idAlojamiento?>">
                                        <td>
                                            <a class="text-success" href="https://www.google.com/maps/place/<?=$alojamiento->latitud?>,<?=$alojamiento->longitud?>/@<?=$alojamiento->latitud?>,<?=$alojamiento->longitud?>,14z" target="_blank">
                                                <i class="bi bi-globe-americas me-1" data-bs-toggle="tooltip" data-bs-original-title="Ver"></i>
                                            </a>
                                            <a href="./editar?id=<?=$alojamiento->idAlojamiento?>">
                                                <i class="bi bi-pencil" data-bs-toggle="tooltip" data-bs-original-title="Editar"></i>
                                            </a>
                                            <button type="button" class="text-danger bg-transparent border-0 btnDelete" onclick="handlerDeleteAlojamiento(<?=$alojamiento->idAlojamiento?>)">
                                                <i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-original-title="Eliminar"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <?=ucfirst($alojamiento->nombre)?>
                                        </td>
                                        <td>
                                            <?=$alojamiento->direccion?>
                                        </td>
                                        <td>
                                            <?=$alojamiento->descripcion?>
                                        </td>
                                        <td><?=date("d/m/Y H:i\h\s", strtotime($alojamiento->created_at))?></td>
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
        initDataTable()
    })

    function initDataTable(){
        dataTableUsers = new simpleDatatables.DataTable("#tableAlojamientos", {
            labels: {
                placeholder: "Buscador...",
                searchTitle: "Search within table",
                pageTitle: "Page {page}",
                perPage: "resultados por página",
                noRows: "Sin alojamientos encontrados",
                info: "<?=ucfirst($section)?>: {rows}",
                noResults: "No hay resultados",
            },
            perPageSelect: [5, 10, 25, 50, 100, ["Todos", -1]],
            fixedHeight: true
        })
    }

    async function handlerDeleteAlojamiento(id){

        const result = await Swal.fire({
            title: "¿Estás seguro?",
            text: "Recuerda que si eliminas el cliente no podrás recuperarlo",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        })

        // Rechazo la eliminación
        if(!result.isConfirmed) return


        // Deshabilito todos los botones
        HTMLController.setProp(".btnDelete", {disabled: true})

        // Armo el form data
        let formData = new FormData()
        formData.append("idAlojamiento", id)
        formData.append("table", "alojamientos")
        formData.append("pk", "idAlojamiento")
        formData.append("action", "delete")

        
        const resultFetch = await fetch("<?= DOMAIN_ADMIN ?>process.php", {method: "POST", body: formData})
        const response = await resultFetch.json()

        const {title, message, type, status} = response
        const resultAlert = await Swal.fire(title, message, type)
        if (status == "OK"){
            dataTableUsers.destroy()
            document.getElementById(`alojamiento-${id}`).remove()
            initDataTable()
        }
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
