<?
require_once __DIR__ . "/../../config/init.php";

$section = "dashboard";
$title = "Dashboard | " . APP_NAME;
ob_start();

$fechaInicioSemana = date("Y-m-d", strtotime("last Monday"));
$fechaFinSemana = date("Y-m-d", strtotime("next Sunday"));


// Clientes nuevos 
$totalNewClient = count(Cliente::getAll(["where" =>  "created_at BETWEEN '{$fechaInicioSemana}' AND '{$fechaFinSemana}'"]));
$totalNewConsultas = count(Consulta::getAll(["where" =>  "created_at BETWEEN '{$fechaInicioSemana}' AND '{$fechaFinSemana}'"]));
$dataVentas = Consulta::getDataVentas((Auth::isAdmin() ? "" : $_SESSION["user"]["idUsuario"]), $fechaInicioSemana, $fechaFinSemana);

?>
<section class="section dashboard">
    <div class="row">

        <!-- Ventas Card -->
        <div class="col-lg-5 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Ventas <span>| Esta semana</span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="ps-3">
                            <h6>$<?= Util::numberToPrice($dataVentas->total, true) ?></h6>
                            <span class="text-success small pt-1 fw-bold"><?= $dataVentas->ventas ?></span> <span class="text-muted small pt-2 ps-1"><?= $dataVentas->ventas == 1 ? "Venta" : "Ventas" ?></span>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- End Ventas Card -->

        <!-- Consulta Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card info-card revenue-card">

                <div class="card-body">
                    <h5 class="card-title">Consultas <span>| Esta semana</span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <!-- <i class="bi bi-currency-dollar"></i> -->
                            <i class="bi bi-chat-left-dots"></i>
                        </div>
                        <div class="ps-3">
                            <h6><?= $totalNewConsultas ?></h6>
                            <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->
                            <span class="text-muted small pt-2 ps-1">Nuevas</span>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- End Consultas Card -->

        <!-- Clientes Card -->
        <div class="col-lg-3">

            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Clientes <span>| Esta semana</span></h5>

                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <!-- <i class="bi bi-people"></i> -->
                            <i class="bi bi-person-add"></i>
                        </div>
                        <div class="ps-3">
                            <h6><?= $totalNewClient ?></h6>
                            <span class="text-muted small pt-2 ps-1">Nuevos</span>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- End Clientes Card -->

        <!-- Reporte de ventas -->
        <div class="col-12">
            <div class="card">

                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filtros</h6>
                        </li>

                        <li><a class="dropdown-item" href="#">Hoy</a></li>
                        <li><a class="dropdown-item" href="#">Esta semana</a></li>
                        <li><a class="dropdown-item" href="#">Este mes</a></li>
                        <li><a class="dropdown-item" href="#">Este año</a></li>
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Reporte de ventas <span>| Hoy</span></h5>

                    <!-- Line Chart -->
                    <div id="reportsChart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            new ApexCharts(document.querySelector("#reportsChart"), {
                                series: [{
                                    name: 'Sales',
                                    data: [31, 40, 28, 51, 42, 82, 56],
                                }, {
                                    name: 'Revenue',
                                    data: [11, 32, 45, 32, 34, 52, 41]
                                }, {
                                    name: 'Customers',
                                    data: [15, 11, 32, 18, 9, 24, 11]
                                }],
                                chart: {
                                    height: 350,
                                    type: 'area',
                                    toolbar: {
                                        show: false
                                    },
                                },
                                markers: {
                                    size: 4
                                },
                                colors: ['#4154f1', '#2eca6a', '#ff771d'],
                                fill: {
                                    type: "gradient",
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: 0.3,
                                        opacityTo: 0.4,
                                        stops: [0, 90, 100]
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    curve: 'smooth',
                                    width: 2
                                },
                                xaxis: {
                                    type: 'datetime',
                                    categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
                                },
                                tooltip: {
                                    x: {
                                        format: 'dd/MM/yy HH:mm'
                                    },
                                }
                            }).render();
                        });
                    </script>
                    <!-- End Line Chart -->

                </div>

            </div>
        </div><!-- End reporte de ventas -->

        <!-- Top vendedores -->
        <div class="col-12">
            <div class="card top-selling">

                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-funnel-fill"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Indique el rango</h6>
                        </li>

                        <form class="dropdown-item" id="formFiltroTopVendedores">
                            <div class="form-floating">
                                <input class="form-control form-control-sm" id="formFiltroTopVendedores_min" name="min" type="date" value="<?= $fechaInicioSemana ?>" oninput="FormController.validate(this)">
                                <label for="formFiltroTopVendedores_min">Desde</label>
                            </div>


                            <div class="form-floating my-1">
                                <input class="form-control form-control-sm" id="formFiltroTopVendedores_max" name="max" type="date" value="<?= $fechaFinSemana ?>" oninput="FormController.validate(this)">
                                <label for="formFiltroTopVendedores_max">Hasta</label>
                            </div>

                            <input type="hidden" name="action" value="usuario_topVendedores">

                            <div class="d-grid gap-1">
                                <button class="btn btn-sm btn-outline-primary" onclick="handlerSubmitFiltroVendedores()" type="button"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                            </div>
                        </form>
                    </ul>
                </div>

                <? if (Auth::isAdmin()): ?>
                    <div class="card-body pb-0">
                        <h5 class="card-title">Top vendedores</h5>

                        <table class="table table-borderless" id="tableTopVendedores">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>E-mail</th>
                                    <th>Ventas</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <? endif; ?>

            </div>
        </div><!-- End Top vendedores -->

    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", e => {

        <? if (Auth::isAdmin()): ?>
            handlerSubmitFiltroVendedores()
        <? endif; ?>
    })

    function handlerSubmitFiltroVendedores() {

        if (document.querySelectorAll("#formFiltroTopVendedores .is-invalid").length > 0) {
            Swal.fire("Revise los campos!", "", "warning")
            return
        }

        const min = parseInt(document.getElementById("formFiltroTopVendedores_min").value.replaceAll("-", ""))
        const max = parseInt(document.getElementById("formFiltroTopVendedores_max").value.replaceAll("-", ""))

        if (max < min) {
            Swal.fire("Revise el orden de las fechas!", "", "warning")
            return
        }

        fetch(
                `<?= DOMAIN_ADMIN ?>process.php`, {
                    method: "POST",
                    body: new FormData(document.getElementById("formFiltroTopVendedores"))
                }
            )
            .then(res => res.json())
            .then(vendedores => {

                let htmlVendedores = ''
                for (const vendedor of vendedores) {
                    htmlVendedores += `
                    <tr>
                        <td scope="row">${vendedor.nombre} ${vendedor.apellido}</td>
                        <td>${vendedor.email}</td>
                        <td>${vendedor.ventas}</td>
                        <td>${Util.numberToPrice(vendedor.total)}</td>
                    </tr>
                `
                }

                document.querySelector("#tableTopVendedores tbody").innerHTML = htmlVendedores
            })


    }

    function handlerSubmitFiltroActividades(elem) {
        console.log("Fecha: ", elem.value)
    }
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
