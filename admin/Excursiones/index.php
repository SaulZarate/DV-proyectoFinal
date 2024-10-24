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
                            <h5 class="card-title pb-0"><i class="<?=$menu->{$section}->icon?> me-1"></i><?=ucfirst($section)?></h5>
                            <p class="text-secondary pb-0 mb-2">Utiliza la siguiente vista para crear, modificar o eliminar <?=$section?> del sistema.</p>
                        </div>

                        <div class="col-md-3 d-flex align-items-center justify-content-start justify-content-md-end">
                            <button class="btn btn-primary btn-sm" type="button" onclick="HTTP.redirect('<?=HTTPController::getCurrenURL()?>editar')"><i class="fa fa-plus me-1"></i>Agregar</button>
                        </div>
    
                    </div>

                    <!-- ------------------- -->
                    <!--        TABLE        -->
                    <!-- ------------------- -->
                    <table class="table" id="tableExcursiones">
                        <thead>
                            <tr>
                                <th></th>
                                <th><i class="<?=$menu->{$section}->icon?> me-1"></i>Excursión</th>
                                <th><i class="bi bi-globe-americas me-1"></i>Destino</th>
                                <th><i class="bi bi-info-square me-1"></i>Información</th>
                                <th style="width: 20%;"><i class='bi bi-bus-front me-1'></i>Fechas de salidas</th>
                                <th style="width: 20%;"><i class="bi bi-calendar-range me-1"></i>Fechas de vigencia</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                           <? foreach ($excursiones as $excursion): ?>
                            <tr id="excursion-<?=$excursion->idPaquete?>">
                                <td>
                                    <a href="./editar?id=<?=$excursion->idPaquete?>"><i class="bi bi-pencil" data-bs-toggle="tooltip" title="Editar"></i></a>
                                    <button type="button" class="text-danger bg-transparent border-0" onclick="handlerDeletePaquete(<?=$excursion->idPaquete?>)"><i class="bi bi-trash" data-bs-toggle="tooltip" title="Eliminar"></i></button>
                                </td>
                                <td>
                                    <p class="m-0"><?=ucfirst($excursion->titulo)?></p>
                                    <p class="m-0 text-secondary"><?=ucfirst($excursion->subtitulo)?></p>
                                </td>
                                <td>
                                    <?=$excursion->provincia?>, <?=$excursion->destino?>
                                </td>
                                <td>
                                    <p class='badge bg-info text-dark m-0 me-1'>
                                        <i class="bi bi-people me-1"></i><?=$excursion->capacidad > 1 ? $excursion->capacidad . " personas" : $excursion->capacidad . " persona"?>
                                    </p>
                                    <p class='badge <?=$excursion->traslado ? "bg-primary" : "bg-danger" ?>  m-0'>
                                        <?=$excursion->traslado ? "<i class='bi bi-bus-front me-1'></i>Con traslado" : "<i class='bi bi-bus-front me-1'></i>Sin traslado"?>
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <? foreach (DB::getAll("SELECT * FROM paquetes_fechas_salida WHERE idPaquete = {$excursion->idPaquete} ORDER BY fecha") as $fechaSalida): ?>
                                            <p class="badge bg-success mb-1 me-1"><?= date("d/m/Y", strtotime($fechaSalida->fecha)) ?></p>
                                        <? endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?=date("d/m/Y", strtotime($excursion->fechaInicioPublicacion))?> - <?=date("d/m/Y", strtotime($excursion->fechaFinPublicacion))?></span>
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

    function handlerDeletePaquete(idPaquete){

        // Deshabilito todos los botones habilitados
        let buttonsVisible = HTMLController.selectElementVisible("button")
        for (const btnVisible of buttonsVisible) btnVisible.disabled = true

        // Pido confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Si eliminas esta excursión se perderan todas las referencias al mismo.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, estoy seguro",
            cancelButtonText: "No"
        }).then((result) => {
            if (!result.isConfirmed) {
                for (const btnVisible of buttonsVisible) btnVisible.disabled = false
                return
            }

            let formData = new FormData()
            formData.append("action", "paquete_delete")
            formData.append("idPaquete", idPaquete)

            // Cambio la contraseña
            fetch("<?= DOMAIN_ADMIN ?>process.php", {
                method: "POST",
                body: formData,
            })
            .then(res => res.json())
            .then(response => {
                const {title,message,type,status} = response

                Swal.fire(title, message, type).then(res => {
                    // Si salio todo bien elimino la fila
                    if (status === "OK") document.getElementById(`excursion-${idPaquete}`).remove()
                })
                
                for (const btnVisible of buttonsVisible) btnVisible.disabled = false
            })
        });
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
