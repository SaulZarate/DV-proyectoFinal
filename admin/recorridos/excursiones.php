<?
require_once __DIR__ . "/../../config/init.php";

$section = "recorridos";
$subSection = "Excursiones";
$title = "Recorridos | " . APP_NAME;

$fechaActual = date("Y-m-d");
$fechaManana = date("Y-m-d", strtotime("+ 1 day"));

$idPaquete = $_GET["idPaquete"] ?? "";
$idProvincia = $_GET["idProvincia"] ?? "";
$fechaSalida = $_GET["fechaSalida"] ?? "";

$filtroPaquete = $idPaquete ? " AND p.idPaquete = {$idPaquete}" : "";
$filtroProvincia = $idProvincia ? " AND p.idProvincia = {$idProvincia}" : "";
$filtroFecha = $fechaSalida ? " AND ps.fecha = '{$fechaSalida}'" : " AND ps.fecha IN ('{$fechaActual}', '{$fechaManana}')";


$excursiones = DB::getAll(
    "SELECT
        p.*, 
        ps.fecha, 
        prov.nombre as provincia
    FROM 
        paquetes p,
        paquetes_fechas_salida ps,
        provincias prov
    where 
        p.idPaquete = ps.idPaquete AND 
        p.idProvincia = prov.idProvincia AND 
        p.estado = 'A' AND 
        p.eliminado = 0 AND
        ps.hasRecorrido = 0 
        {$filtroPaquete}
        {$filtroProvincia}
        {$filtroFecha}
    ORDER BY 
        ps.fecha ASC
");

ob_start();
?>


<section class="section">

    <!-- FILTROS -->
    <div class="card">
        <form class="card-body" method="GET">
            <h5 class="card-title"><i class="fa fa-filter me-1"></i>Filtros</h5>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idPaquete" id="idPaquete" class="form-select">
                            <option value="">Todas</option>
                            <? foreach (Paquete::getAll(["order" => "p.titulo ASC"]) as $paquete): ?>
                                <option value="<?=$paquete->idPaquete?>" <?=$idPaquete == $paquete->idPaquete ? "selected" : ""?>><?=ucfirst($paquete->titulo)?></option>
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

            <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-save me-1"></i>Filtrar</button>
            <a class="btn btn-secondary btn-sm" href="<?=DOMAIN_ADMIN?>recorridos/excursiones"><i class="fa fa-eraser me-1"></i>Limpiar</a>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3 row">
                <div class="col">
                    <h5 class="card-title pb-0"><i class="<?=$menu->excursiones->icon?> me-1"></i>Excursiones sin recorridos</h5>
                    <p class="text-secondary pb-0 mb-2">Utiliza la siguiente vista para ver las excursiones sin recorridos creados.</p>
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
                            <th><i class="<?=$menu->excursiones->icon?> me-1"></i>Excursión</th>
                            <th><i class="bi bi-globe-americas me-1"></i>Destino</th>
                            <th><i class="bi bi-people me-1"></i>Pax</th>
                            <th style="width: 20%;"><i class='bi bi-calendar-event me-1'></i>Fecha de salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? if (!$excursiones): ?>
                            <tr>
                                <td colspan="5" class="text-center text-secondary">Sin excursiones</td>
                            </tr>
                        <? endif; ?>
                        <? 
                        $lastDateExcursion = "";
                        foreach ($excursiones as $excursion): ?>
                            <? if ($lastDateExcursion != $excursion->fecha): 
                                $lastDateExcursion = $excursion->fecha;

                                $textoFecha = date("d/m/Y", strtotime($excursion->fecha));
                                if($excursion->fecha == $fechaActual) $textoFecha = "HOY";
                                if($excursion->fecha == $fechaManana) $textoFecha = "MAÑANA";
                            ?>
                                <tr>
                                    <td colspan="5" class="bg-light text-center"><?=$textoFecha?></td>
                                </tr>
                            <? endif; ?>

                            <tr>
                                <td class="text-center align-middle">
                                    <a href="./editar?idPaquete=<?=$excursion->idPaquete?>&fecha=<?=$excursion->fecha?>" data-bs-target="tooltip" title="Crear recorrido"><i class="fa fa-plus-circle me-1"></i></a>
                                </td>
                                <td>
                                    <p class="m-0"><?=ucfirst($excursion->titulo)?></p>
                                    <p class="m-0 text-secondary"><?=ucfirst($excursion->subtitulo)?></p>
                                </td>
                                <td>
                                    <p class="m-0"><?=$excursion->provincia?>, <?=$excursion->destino?></p>
                                    <? if ($excursion->traslado): ?>
                                        <span class='badge bg-primary m-0'><i class='bi bi-bus-front me-1'></i>Incluye traslado desde alojamiento</span>
                                    <? endif; ?>
                                </td>
                                <td>
                                    <?=Paquete::getCuposVendidos($excursion->idPaquete, $excursion->fecha)?>/<?=$excursion->capacidad?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <p class="badge bg-success mb-1 me-1"><?= date("d/m/Y H:i", strtotime($excursion->fecha . " " . $excursion->horaSalida)) ?>hs</p>
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
    document.addEventListener("DOMContentLoaded", e => {
        
    })
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
