<?
require_once __DIR__ . "/../../config/init.php";

$section = "usuarios";
$title = "Usuarios | " . APP_NAME;
ob_start();
?>


<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title pb-0"><i class="bi bi-people me-1"></i>Usuarios</h5>
                    <p class="text-secondary pb-0 mb-2">Utiliza ña siguiente vista para crear, modificar o eliminar usuarios del sistema.</p>

                    <button class="btn btn-primary btn-sm mb-3" type="button" onclick="HTTP.redirect('<?=HTTPController::getCurrenURL()?>editar')"><i class="fa fa-plus me-1"></i>Nuevo usuario</button>

                    <!-- ------------------- -->
                    <!--        TABLE        -->
                    <!-- ------------------- -->
                    <table class="table" id="tableUsers">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th data-type="date" data-format="DD/MM/YYYY/">Fecha de creación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach (Usuario::getAll() as $user): ?>
                                <tr id="user-<?=$user->idUsuario?>">
                                    <td>
                                        <? if ($user->idUsuario != $_SESSION["user"]["idUsuario"]): ?>
                                            <a href="./editar?id=<?=$user->idUsuario?>" ><i class="bi bi-pencil"></i></a>
                                            <button type="button" class="text-danger bg-transparent border-0 btnDeleteUser" onclick="handlerDeleteUser(<?=$user->idUsuario?>)"><i class="bi bi-trash"></i></button>
                                        <? else: ?>
                                            <!-- <span class="text-success"><i class="bi bi-tag-fill"></i></span> -->
                                        <? endif; ?>
                                    </td>
                                    <td><?=ucfirst($user->nombre) . " " . ucfirst($user->apellido)?></td>
                                    <td><?=ucfirst(Auth::getRoleName())?></td>
                                    <td><?=$user->email?></td>
                                    <td>+<?=$user->codPais . " " . $user->codArea . " " . $user->telefono?></td>
                                    <td>
                                        <? if ($user->estado == "A"): ?>
                                            <span class="text-success"><i class="bi bi-check-circle me-1"></i>activo</span>
                                        <? else: ?>
                                            <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                        <? endif; ?>
                                    </td>
                                    <td><?=date("d/m/Y H:i\h\s", strtotime($user->created_at))?></td>
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
        dataTableUsers = new simpleDatatables.DataTable("#tableUsers", {
            labels: {
                placeholder: "Buscador...",
                searchTitle: "Search within table",
                pageTitle: "Page {page}",
                perPage: "resultados por página",
                noRows: "Sin filas encontradas",
                info: "Resultados: {rows}",
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

        
        const resultFetch = await fetch("<?= DOMAIN_NAME ?>admin/process.php", {method: "POST", body: formData})
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
