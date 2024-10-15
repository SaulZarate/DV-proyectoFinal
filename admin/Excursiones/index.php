<?
require_once __DIR__ . "/../../config/init.php";

$section = "excursiones";
$title = "Excursiones | " . APP_NAME;


$excursiones = DB::getAll(
    "SELECT 
        p.*, 
        prov.nombre as provincia 
    FROM 
        paquetes p,
        provincias prov
    WHERE 
        p.idProvincia = prov.idProvincia AND 
        p.eliminado = 0 
    ORDER BY p.created_at DESC
");

ob_start();
?>


<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    
                    <div class="row mb-3">
                        
                        <div class="col">
                            <h5 class="card-title pb-0"><i class="bi bi-bus-front me-1"></i><?=ucfirst($section)?></h5>
                            <p class="text-secondary pb-0 mb-2">Utiliza la siguiente vista para crear, modificar o eliminar <?=$section?> del sistema.</p>
                        </div>

                        <div class="col-md-3 d-flex align-items-center justify-content-start justify-content-md-end">
                            <button class="btn btn-primary" type="button" onclick="HTTP.redirect('<?=HTTPController::getCurrenURL()?>editar')"><i class="fa fa-plus me-1"></i>Agregar excursión</button>
                        </div>
    
                    </div>

                    <!-- ------------------- -->
                    <!--        TABLE        -->
                    <!-- ------------------- -->
                    <table class="table" id="tableExcursiones">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Excursión</th>
                                <th>Destino</th>
                                <th><i class="bi bi-people me-1"></i>Cupos</th>
                                <th><i class="bi bi-bus-front me-1"></i>Traslado</th>
                                <th>Fechas de publicación</th>
                                <th style="width: 20%;">Fechas de salidas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                           <? foreach ($excursiones as $excursion): ?>
                            <tr id="excursion-<?=$excursion->idPaquete?>">
                                <td>
                                    <a href="./editar?id=<?=$excursion->idPaquete?>"><i class="bi bi-pencil" data-bs-toggle="tooltip" title="Editar"></i></a>
                                    <!-- TODO: Agregar funcionalidad al eliminado de paquetes -->
                                    <button type="button" class="text-danger bg-transparent border-0 btnDeleteUser" onclick="handlerDeletePaquete(<?=$excursion->idPaquete?>)"><i class="bi bi-trash" data-bs-toggle="tooltip" title="Eliminar"></i></button>
                                </td>
                                <td>
                                    <p class="m-0"><?=ucfirst($excursion->titulo)?></p>
                                    <p class="m-0 text-secondary"><?=ucfirst($excursion->subtitulo)?></p>
                                </td>
                                <td>
                                    <?=$excursion->provincia?>, <?=$excursion->destino?>
                                </td>
                                <td>
                                    <p class='badge bg-info text-dark m-0'><?=$excursion->capacidad > 1 ? $excursion->capacidad . " personas" : $excursion->capacidad . " persona"?></p>
                                </td>
                                <td>
                                    <?=$excursion->traslado ? "<i class='bi bi-check-circle text-primary'></i>" : "<i class='bi bi-x-circle text-danger'></i>"?>
                                </td>
                                <td>
                                    <?=date("d/m/Y", strtotime($excursion->fechaInicioPublicacion))?> - <?=date("d/m/Y", strtotime($excursion->fechaFinPublicacion))?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <? foreach (DB::getAll("SELECT * FROM paquetes_fechas_salida WHERE idPaquete = {$excursion->idPaquete} ORDER BY fecha") as $fechaSalida): ?>
                                            <p class="badge bg-success mb-1 me-1"><?= date("d/m/Y", strtotime($fechaSalida->fecha)) ?></p>
                                        <? endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <?=$excursion->estado == "A" ? "<p class='badge bg-primary m-0'>Activo</p>" : "<p class='badge bg-primary m-0'>Inactivo</p>"?>
                                </td>
                            </tr>
                           <? endforeach; ?>
                        </tbody>
                    </table>
                
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
        dataTableUsers = new simpleDatatables.DataTable("#tableExcursiones", {
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

    async function handlerDeleteUser(id){

        const result = await Swal.fire({
            title: "¿Estás seguro?",
            text: "Recuerda que si eliminas el usuario no podrás recuperarlo",
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
        HTMLController.setProp(".btnDeleteUser", {disabled: true})

        // Armo el form data
        let formData = new FormData()
        formData.append("idUsuario", id)
        formData.append("action", "usuario_delete")

        
        const resultFetch = await fetch("<?= DOMAIN_ADMIN ?>process.php", {method: "POST", body: formData})
        const response = await resultFetch.json()

        const {title, message, type, status} = response
        const resultAlert = await Swal.fire(title, message, type)
        if (status == "OK"){
            dataTableUsers.destroy()
            document.getElementById(`user-${id}`).remove()
            initDataTable()
        }
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
