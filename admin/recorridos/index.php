<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";
$title = "Recorridos | " . APP_NAME;

$idUsuario = $_GET["idUsuario"] ?? "";
$idPaquete = $_GET["idPaquete"] ?? "";
$idProvincia = $_GET["idProvincia"] ?? "";
$fechaSalida = $_GET["fechaSalida"] ?? "";


$filtroUsuario = $idUsuario ? " AND r.idUsuario = {$idUsuario}" : "";
$filtroPaquete = $idPaquete ? " AND p.idPaquete = {$idPaquete}" : "";
$filtroProvincia = $idProvincia ? " AND p.idProvincia = {$idProvincia}" : "";
$filtroFecha = $fechaSalida ? " AND r.fecha = '{$fechaSalida}'" : "";


$recorridos = DB::getAll(
    "SELECT
        r.*, 
        u.nombre, 
        u.apellido, 
        p.titulo,
        p.subtitulo,
        p.destino,
        p.horaSalida,
        p.traslado, 
        prov.nombre as provincia
    FROM 
        recorridos r,
        usuarios u,
        paquetes p,
        provincias prov
    where 
        r.idUsuario = u.idUsuario AND 
        r.idPaquete = p.idPaquete AND 
        p.idProvincia = prov.idProvincia 
        {$filtroUsuario}
        {$filtroPaquete}
        {$filtroProvincia}
        {$filtroFecha}
    ORDER BY 
        r.fecha ASC
");

ob_start();
?>


<section class="section">
    <!-- <div class="card">
        <form class="card-body" method="GET">
            <h5 class="card-title"><i class="fa fa-filter me-1"></i>Filtros</h5>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idPaquete" id="idPaquete" class="form-select">
                            <option value="">Todas</option>
                            <? foreach (Paquete::getAll(["order" => "p.titulo ASC"]) as $paquete): ?>
                                <option value="<?=$paquete->idPaquete?>" <?=$idPaquete == $paquete->idPaquete ? "selected" : ""?>><?=$paquete->titulo?></option>
                            <? endforeach; ?>
                        </select>
                        <label for="idPaquete" class="mb-1">Excursión</label>
                    </div>                    
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idProvincia" id="idProvincia" class="form-select">
                            <option value="">Todas</option>
                            <? foreach (DB::getAll("SELECT * FROM provincias ORDER BY nombre") as $provincia): ?>
                                <option value="<?=$provincia->idProvincia?>" <?=$idProvincia == $provincia->idProvincia ? "selected" : ""?>><?=ucfirst($provincia->nombre)?></option>
                            <? endforeach; ?>
                        </select>
                        <label for="idProvincia" class="mb-1">Provincia de destino</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="date" name="fechaSalida" id="fechaSalida" class="form-control" value="<?=$fechaSalida?>">
                        <label for="fechaSalida" class="mb-1">Fecha de salida</label>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit"><i class="fa fa-save me-1"></i>Filtrar</button>
            <a class="btn btn-primary" href="<?=DOMAIN_ADMIN?>salidas/"><i class="fa fa-eraser me-1"></i>Limpiar</a>
        </form>
    </div> -->

    <div class="card">
        <div class="card-body">
            <div class="mb-3 row">
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
                <table class="table" id="tableSalidas">
                    <thead>
                        <tr>
                            <th></th>
                            <th><i class="<?=$menu->{$section}->icon?> me-1"></i>Excursión</th>
                            <th><i class="bi bi-globe-americas me-1"></i>Destino</th>
                            <th><i class="me-1 <?=$menu->usuarios->icon?>"></i>Guía</th>
                            <th><i class="bi bi-people me-1"></i>Pax</th>
                            <th style="width: 20%;"><i class='bi bi-bus-front me-1'></i>Fecha de salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? if (!$recorridos): ?>
                            <tr>
                                <td colspan="6" class="text-center text-secondary">Sin salidas creadas</td>
                            </tr>
                        <? endif; ?>
                        <? foreach ($recorridos as $recorrido): ?>
                            <tr id="recorrido-<?=$recorrido->idRecorrido?>">
                                <td>
                                    <a href="./editar?id=<?=$recorrido->idRecorrido?>" data-bs-target="tooltip" title="Editar"><i class="fa fa-pencil"></i></a>
                                    <a href="./detalle?id=<?=$recorrido->idRecorrido?>" class="text-success" data-bs-target="tooltip" title="Ver información del recorrido"><i class="fa fa-map-marked-alt"></i></a>
                                    <a href="javascript:;" class="text-warning" data-bs-target="tooltip" title="Volver a armar el recorrido"><i class="fa fa-sync-alt"></i></a>
                                    <a href="javascript:;" class="text-danger" data-bs-target="tooltip" title="Eliminar"><i class="fa fa-trash"></i></a>
                                </td>
                                <td>
                                    <p class="m-0"><?=ucfirst($recorrido->titulo)?></p>
                                    <p class="m-0 text-secondary"><?=ucfirst($recorrido->subtitulo)?></p>

                                    <? if ($recorrido->traslado): ?>
                                        <p class='badge bg-primary m-0'><i class='bi bi-bus-front me-1'></i>Incluye traslado desde alojamiento</p>
                                    <? endif; ?>
                                </td>
                                <td>
                                    <?=$recorrido->provincia?>, <?=$recorrido->destino?>
                                </td>
                                <td>
                                    <?=ucfirst($recorrido->nombre)?> <?=ucfirst($recorrido->apellido)?>
                                </td>
                                <td>
                                    <?=Paquete::getCuposVendidos($recorrido->idPaquete, $recorrido->fecha)?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <p class="badge bg-success mb-1 me-1"><?= date("d/m/Y H:i", strtotime($recorrido->fecha . " " . $recorrido->horaSalida)) ?>hs</p>
                                    </div>
                                </td>
                            </tr>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        
        </div>
    </div>
</section>

<script>
    var dataTableUsers = null

    document.addEventListener("DOMContentLoaded", e => {
        /* initDataTable() */
    })

    function initDataTable(){
        dataTableUsers = new simpleDatatables.DataTable("#tableSalidas", {
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
