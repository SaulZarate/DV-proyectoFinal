<?
require_once __DIR__ . "/../../config/init.php";

$section = "clientes";
$title = "Clientes | " . APP_NAME;

$clients = Cliente::getAll();

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
                    <table class="table" id="tableClients">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Consultas</th>
                                <th>Estado</th>
                                <th data-type="date" data-format="DD/MM/YYYY/">Fecha de creación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach ($clients as $client): ?>
                                <tr id="client-<?=$client->idCliente?>">
                                    <td>
                                        <a href="./editar?id=<?=$client->idCliente?>" ><i class="bi bi-pencil" data-bs-toggle="tooltip" data-bs-original-title="Editar"></i></a>
                                        <button type="button" class="text-danger bg-transparent border-0 btnDelete" onclick="handlerDeleteClient(<?=$client->idCliente?>)"><i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-original-title="Eliminar"></i></button>
                                    </td>
                                    <td>
                                        <?=ucfirst($client->nombre) . " " . ucfirst($client->apellido)?>
                                    </td>
                                    <td>
                                        <a 
                                            onClick="javascript:window.open('mailto:<?=$client->email?>?subject=Mensaje desde <?=APP_NAME?>&body=Hola <?=ucfirst($client->nombre)?>, ¿cómo estás?', 'mail');event.preventDefault()"
                                            href="#" 
                                        ><i class="bi bi-envelope-at"></i>
                                        </a>
                                        <?=$client->email?>
                                    </td>
                                    <td>
                                        <?
                                        $numberWhatsapp = $client->codPais . ($client->codPais === "54" ? "9" : "") . $client->codArea . $client->telefono;
                                        ?>
                                        <a href="https://wa.me/<?=$numberWhatsapp?>?text=Hola <?=ucfirst($client->nombre)?>, ¿cómo estás?" target="_blank"><i class="bi bi-whatsapp"></i></a>    
                                        +<?=$client->codPais . " " . $client->codArea . " " . $client->telefono?>
                                    </td>
                                    <td>
                                        <span class="bagde">0 consultas</span>
                                    </td>
                                    <td>
                                        <? if ($client->estado == "A"): ?>
                                            <span class="text-success"><i class="bi bi-check-circle me-1"></i>activo</span>
                                        <? else: ?>
                                            <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                        <? endif; ?>
                                    </td>
                                    <td><?=date("d/m/Y H:i\h\s", strtotime($client->created_at))?></td>
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
        dataTableUsers = new simpleDatatables.DataTable("#tableClients", {
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

    async function handlerDeleteClient(id){

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
        formData.append("idCliente", id)
        formData.append("table", "clientes")
        formData.append("pk", "idCliente")
        formData.append("action", "delete")

        
        const resultFetch = await fetch("<?= DOMAIN_ADMIN ?>process.php", {method: "POST", body: formData})
        const response = await resultFetch.json()

        const {title, message, type, status} = response
        const resultAlert = await Swal.fire(title, message, type)
        if (status == "OK"){
            dataTableUsers.destroy()
            document.getElementById(`client-${id}`).remove()
            initDataTable()
        }
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
