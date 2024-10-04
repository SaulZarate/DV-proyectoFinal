<?
require_once __DIR__ . "/../../config/init.php";

$section = "dashboard";
$title = "Dashboard | " . APP_NAME;
ob_start();
?>

<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row">

                <!-- Sales Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

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
                            <h5 class="card-title">Ventas <span>| Hoy</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>$256.200</h6>
                                    <span class="text-success small pt-1 fw-bold">10</span> <span class="text-muted small pt-2 ps-1">Ventas</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->

                <!-- Revenue Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">

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
                            <h5 class="card-title">Consultas <span>| Este mes</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <!-- <i class="bi bi-currency-dollar"></i> -->
                                    <i class="bi bi-chat-left-dots"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>3.264</h6>
                                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->
                                    <span class="text-muted small pt-2 ps-1">Nuevas</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Revenue Card -->

                <!-- Customers Card -->
                <div class="col-xxl-4 col-xl-12">

                    <div class="card info-card customers-card">

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
                            <h5 class="card-title">Clientes <span>| Semana</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <!-- <i class="bi bi-people"></i> -->
                                    <i class="bi bi-person-add"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>1244</h6>
                                    <span class="text-muted small pt-2 ps-1">Nuevos</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- End Customers Card -->


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
                    <div class="card top-selling overflow-auto">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-funnel-fill"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Indique el rango</h6>
                                </li>

                                <div class="dropdown-item">
                                    <div class="form-floating">
                                        <input class="form-control form-control-sm" id="desde" placeholder="Your Name" type="date">
                                        <label for="desde">Desde</label>
                                    </div>


                                    <div class="form-floating my-1">
                                        <input class="form-control form-control-sm" id="hasta" placeholder="Your Name" type="date">
                                        <label for="hasta">Hasta</label>
                                    </div>

                                    <div class="d-grid gap-1">
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                                    </div>
                                </div>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Top vendedores <span>| Mes</span></h5>

                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>E-mail</th>
                                        <th>Ventas</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td scope="row">Pepito</td>
                                        <td>pepito@gmail.com</td>
                                        <td>14</td>
                                        <td>$526.500</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Pepito2</td>
                                        <td>pepito2@gmail.com</td>
                                        <td>12</td>
                                        <td>$506.000</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Pepito3</td>
                                        <td>pepit3@gmail.com</td>
                                        <td>5</td>
                                        <td>$350.000</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Pepito4</td>
                                        <td>pepit4@gmail.com</td>
                                        <td>5</td>
                                        <td>$320.000</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Pepito5</td>
                                        <td>pepit5@gmail.com</td>
                                        <td>1</td>
                                        <td>$80.000</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div><!-- End Top vendedores -->

            </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

            <!-- Origen de ventas -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-funnel-fill"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Indique el rango</h6>
                        </li>

                        <div class="dropdown-item">
                            <div class="form-floating">
                                <input class="form-control form-control-sm" id="desde" placeholder="Your Name" type="date">
                                <label for="desde">Desde</label>
                            </div>


                            <div class="form-floating my-1">
                                <input class="form-control form-control-sm" id="hasta" placeholder="Your Name" type="date">
                                <label for="hasta">Hasta</label>
                            </div>

                            <div class="d-grid gap-1">
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                            </div>
                        </div>
                    </ul>
                </div>

                <div class="card-body pb-0">
                    <h5 class="card-title">Origen de ventas <br><span>Hoy</span></h5>

                    <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            echarts.init(document.querySelector("#trafficChart")).setOption({
                                tooltip: {
                                    trigger: 'item'
                                },
                                legend: {
                                    top: '5%',
                                    left: 'center'
                                },
                                series: [{
                                    name: '',
                                    type: 'pie',
                                    radius: ['40%', '70%'],
                                    avoidLabelOverlap: false,
                                    label: {
                                        show: false,
                                        position: 'center'
                                    },
                                    emphasis: {
                                        label: {
                                            show: true,
                                            fontSize: '18',
                                            fontWeight: 'bold'
                                        }
                                    },
                                    labelLine: {
                                        show: false
                                    },
                                    data: [{
                                            value: 48,
                                            name: 'CRM'
                                        },
                                        {
                                            value: 15,
                                            name: 'Punto de venta'
                                        },
                                        {
                                            value: 30,
                                            name: 'API'
                                        }
                                    ]
                                }]
                            });
                        });
                    </script>

                </div>
            </div><!-- End Origen de ventas -->

            <!-- Actividad reciente -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-funnel-fill"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filtrar por fecha</h6>
                        </li>

                        <div class="dropdown-item">
                            <input type="date" class="form-control form-control-sm">
                        </div>
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Últimas 10 actividades<br><span>Hoy</span></h5>

                    <div class="activity">

                        <div class="activity-item d-flex">
                            <div class="activite-label">32 min</div>
                            <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                            <div class="activity-content">
                                Venta del paquete <b>paquete001</b> hecha por <b>Rick Sanchez</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">56 min</div>
                            <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                            <div class="activity-content">
                                Consulta <a href="#" target="_blank">#125</a> abierta por el usuario <b>Rick Sanchez</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">2 hrs</div>
                            <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                            <div class="activity-content">
                                Modificación de los datos de la consulta <a href="#" target="_blank">#151</a> por <b>Morty Sanchez</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">1 day</div>
                            <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                            <div class="activity-content">
                                El usuario <b>Saúl Zárate</b> creó al usuario <b>Morty</b> con el rol de administrador
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">2 days</div>
                            <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                            <div class="activity-content">
                                El usuario <b>Saúl Zárate</b> creó al usuario <b>Rick Sanchez</b> con el rol de administrador
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">4 weeks</div>
                            <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                            <div class="activity-content">
                                Consulta <a href="#" target="_blank">#52</a> abierta por el usuario <b>Saúl Zárate</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">4 weeks</div>
                            <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                            <div class="activity-content">
                                Consulta <a href="#" target="_blank">#31</a> abierta por el usuario <b>Saúl Zárate</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">5 weeks</div>
                            <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                            <div class="activity-content">
                                Consulta <a href="#" target="_blank">#16</a> abierta por el usuario <b>Saúl Zárate</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">5 weeks</div>
                            <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                            <div class="activity-content">
                                Consulta <a href="#" target="_blank">#15</a> abierta por el usuario <b>Saúl Zárate</b>
                            </div>
                        </div>

                        <div class="activity-item d-flex">
                            <div class="activite-label">6 weeks</div>
                            <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                            <div class="activity-content">
                                Consulta <a href="#" target="_blank">#5</a> abierta por el usuario <b>Saúl Zárate</b>
                            </div>
                        </div>

                    </div>

                </div>
            </div><!-- End Actividad reciente -->

        </div><!-- End Right side columns -->

    </div>
</section>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
