<?
require_once __DIR__ . "/../../config/init.php";

$section = "salidas";
$title = "Salidas | " . APP_NAME;

/* TODO: Aplicar filtros */

$salidas = DB::getAll(
    "SELECT 
        p.*, 
        ps.fecha, 
        prov.nombre as provincia 
    FROM 
        paquetes p,
        paquetes_fechas_salida ps, 
        provincias prov
    WHERE 
        p.idProvincia = prov.idProvincia AND 
        p.idPaquete = ps.idPaquete AND 
        p.estado = 'A' AND 
        p.eliminado = 0 
    ORDER BY 
        ps.fecha
");

ob_start();
?>


<section class="section">
    <div class="card">
        <form class="card-body" method="GET">
            <h5 class="card-title"><i class="fa fa-filter me-1"></i>Filtros</h5>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="form-floating">
                        <select name="idPaquete" id="idPaquete" class="form-select">
                            <option value="">Todas</option>
                            <? foreach (Paquete::getAll(["order" => "p.titulo ASC"]) as $paquete): ?>
                                <option value="<?=$paquete->idPaquete?>"><?=$paquete->titulo?></option>
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
                                <option value="<?=$provincia->idProvincia?>"><?=ucfirst($provincia->nombre)?></option>
                            <? endforeach; ?>
                        </select>
                        <label for="idProvincia" class="mb-1">Provincia</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="date" name="fecha" id="fecha" class="form-control">
                        <label for="fechas" class="mb-1">Fecha de salida</label>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit"><i class="fa fa-save me-1"></i>Filtrar</button>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <h5 class="card-title pb-0"><i class="<?=$menu->{$section}->icon?> me-1"></i><?=ucfirst($section)?></h5>
                <p class="text-secondary pb-0 mb-2">Utiliza la siguiente vista para crear, modificar o eliminar <?=$section?> del sistema.</p>
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
                            <th><i class="bi bi-people me-1"></i>Cupos</th>
                            <th style="width: 20%;"><i class='bi bi-bus-front me-1'></i>Fecha de salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($salidas as $salida): 
                            $totalVentas = 0;
                            foreach (DB::getAll("SELECT c.idConsulta FROM consultas c, paquetes_fechas_salida ps WHERE c.idPaquete = ps.idPaquete AND c.idPaquete = {$salida->idPaquete} AND ps.fecha = '{$salida->fecha}' AND c.estado = 'V' AND c.eliminado = 0") as $venta) {
                                $totalVentas += COUNT(DB::getAll("SELECT * FROM consulta_pasajeros WHERE idConsulta = {$venta->idConsulta}"));
                            }
                        ?>
                        <tr id="excursion-<?=$salida->idPaquete?>">
                            <td>
                                <a href="./detalle?id=<?=$salida->idPaquete?>&fecha=<?=$salida->fecha?>" data-bs-target="tooltip" title="Ver información"><i class="fas fa-map-marked-alt"></i></a>
                            </td>
                            <td>
                                <p class="m-0"><?=ucfirst($salida->titulo)?></p>
                                <p class="m-0 text-secondary"><?=ucfirst($salida->subtitulo)?></p>
                            </td>
                            <td>
                                <?=$salida->provincia?>, <?=$salida->destino?>
                            </td>
                            <td>
                                <?=Paquete::getCuposVendidos($salida->idPaquete, $salida->fecha)?>/<?=$salida->capacidad?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center flex-wrap">
                                    <p class="badge bg-success mb-1 me-1"><?= date("d/m/Y H:i", strtotime($salida->fecha . " " . $salida->horaSalida)) ?>hs</p>
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
