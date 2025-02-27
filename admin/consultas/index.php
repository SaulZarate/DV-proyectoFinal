<?
require_once __DIR__ . "/../../config/init.php";

$section = "consultas";
$title = "Consultas | " . APP_NAME;

$filtroUsuario = Auth::isAdmin() ? "" : " AND c.idUsuario = {$_SESSION['user']['idUsuario']}";
$subSection = "Abiertas";
$filtroEstado = " AND c.estado = 'A'";

if(isset($_GET["s"]) && $_GET["s"]){
    if($_GET["s"] == "V") { 
        $subSection = "Vendidas"; 
        $filtroEstado = " AND c.estado = 'V'"; 
    }
    if($_GET["s"] == "C") { 
        $subSection = "Cerradas"; 
        $filtroEstado = " AND c.estado = 'C'"; 
    }
}

$consultas = DB::getAll(
    "SELECT 
        c.*, 
        cl.nombre, 
        cl.apellido, 
        CONCAT(u.nombre, ' ', u.apellido) as usuario, 
        p.titulo as paquete, 
        p.destino, 
        COUNT(pax.idPasajero) as pasajeros
    FROM
        clientes cl,
        paquetes p, 
        consultas c
    LEFT JOIN
        consulta_pasajeros pax
    ON 
        pax.idConsulta = c.idConsulta
    LEFT JOIN 
        usuarios u 
    ON 
        u.idUsuario = c.idUsuario
    WHERE 
        c.idCliente = cl.idCliente AND 
        c.idPaquete = p.idPaquete AND 
        c.eliminado = 0
        {$filtroEstado}
        {$filtroUsuario}
    GROUP BY 
        c.idConsulta
    ORDER BY 
        c.updated_at DESC
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
    
                    </div>

                    <!-- ------------------- -->
                    <!--        TABLE        -->
                    <!-- ------------------- -->
                     <div class="table-responsive">
                        <table class="table table-hover" id="tableConsultas">
                            <thead class="">
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Asunto</th>
                                    <th>Cliente</th>
                                    <th>Asignado</th>
                                    <th>Pax</th>
                                    <th>Paquete</th>
                                    <? if ($subSection == "Abiertas"): ?>
                                        <th>Estado</th>
                                    <? endif; ?>
                                    <th>Última modificación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? if (!$consultas): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Sin <?=$section?></td>
                                    </tr>
                                <? endif; ?>

                                <? foreach ($consultas as $consulta): 
                                    $dataUltimoMensaje = db::getOne("SELECT * FROM consulta_mensajes WHERE idConsulta = {$consulta->idConsulta} ORDER BY created_at DESC");
                                ?>
                                <tr id="consulta-<?=$consulta->idConsulta?>" onclick="HTTP.redirect('<?=DOMAIN_ADMIN?>consultas/detalle?id=<?=$consulta->idConsulta?>')" class="cursor-pointer">
                                    <td>
                                        <a href="javascript:;"><i class="bi bi-eye" data-bs-toggle="tooltip" title="Ver"></i></a>
                                        <button type="button" class="text-danger bg-transparent border-0 btnDelete" onclick="handlerDelete(<?=$consulta->idConsulta?>, event)"><i class="bi bi-trash" data-bs-toggle="tooltip" title="Eliminar"></i></button>
                                    </td>
                                    <td>
                                        #<?=str_pad($consulta->idConsulta, 4, "0", STR_PAD_LEFT)?>
                                    </td>
                                    <td>
                                        <?=ucfirst($consulta->asunto)?>
                                    </td>
                                    <td>
                                        <p class="m-0"><?=ucfirst($consulta->nombre) . " " . ucfirst($consulta->apellido)?></p>
                                    </td>
                                    <td>
                                        <p class="m-0"><?=ucfirst($consulta->usuario)?></p>
                                    </td>
                                    <td>
                                        <p class='badge bg-success m-0'><?=$consulta->pasajeros?> <?=$consulta->pasajeros == 1 ? "Pasajero" : "Pasajeros"?></p>
                                    </td>
                                    <td>
                                        <p class="m-0"><?=ucfirst($consulta->paquete)?></p>
                                        <span class="text-secondary"><?=ucfirst($consulta->destino)?></span>
                                    </td>

                                    <? if ($subSection == "Abiertas"): ?>
                                        <td>
                                            <? if ($dataUltimoMensaje): ?>
                                                <? if ($dataUltimoMensaje->tipo == "C"): ?>
                                                    <p class='badge bg-warning'>Respuesta de cliente</p>
                                                <? elseif ($dataUltimoMensaje->tipo == "U"): ?>
                                                    <p class='badge bg-primary'>Esperando respuesta</p>
                                                <? else: ?>
                                                    <p class='badge bg-secondary'>Sin mensajes</p>
                                                <? endif; ?>
                                            <? else: ?>
                                                <p class='badge bg-secondary'>Sin mensajes</p>
                                            <? endif; ?>
                                        </td>
                                    <? endif; ?>
                                    
                                    <td>
                                        <p class='badge bg-primary m-0'>
                                            <? if ($dataUltimoMensaje): ?>
                                                <?=date("d/m/Y H:i\h\s", strtotime($dataUltimoMensaje->created_at))?>
                                            <? else: ?>
                                                <?=date("d/m/Y H:i\h\s", strtotime($consulta->updated_at))?>
                                            <? endif; ?>
                                        </p>
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

    function handlerDelete(id, event){

        // Evita la redirección al detalle
        event.stopPropagation();

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
            formData.append("table", "consultas")
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
