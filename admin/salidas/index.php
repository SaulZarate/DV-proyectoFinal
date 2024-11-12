<?
require_once __DIR__ . "/../../config/init.php";

$section = "salidas";
$title = "Salidas | " . APP_NAME;


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
                        <table class="table" id="tableExcursiones">
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
                                        <a href="./detalle?id=<?=$salida->idPaquete?>&fecha=<?=$salida->fecha?>"><i class="bi bi-eye"></i></a>
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
