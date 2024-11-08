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

        <!-- Left side columns -->
        <div class="col-lg-8">
            <div class="row">

                <!-- Sales Card -->
                <div class="col-lg-5 col-md-6">
                    <div class="card info-card sales-card">

                        <!-- <div class="filter">
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
                        </div> -->

                        <div class="card-body">
                            <h5 class="card-title">Ventas <span>| Esta semana</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>$<?=Util::numberToPrice($dataVentas->total, true)?></h6>
                                    <span class="text-success small pt-1 fw-bold"><?=$dataVentas->ventas?></span> <span class="text-muted small pt-2 ps-1"><?=$dataVentas->ventas == 1 ? "Venta" : "Ventas"?></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->

                <!-- Consulta Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="card info-card revenue-card">

                        <!-- <div class="filter">
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
                        </div> -->

                        <div class="card-body">
                            <h5 class="card-title">Consultas <span>| Esta semana</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <!-- <i class="bi bi-currency-dollar"></i> -->
                                    <i class="bi bi-chat-left-dots"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?=$totalNewConsultas?></h6>
                                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->
                                    <span class="text-muted small pt-2 ps-1">Nuevas</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Consultas Card -->

                <!-- Customers Card -->
                <div class="col-lg-3">

                    <div class="card info-card customers-card">

                       <!--  <div class="filter">
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
                        </div> -->

                        <div class="card-body">
                            <h5 class="card-title">Clientes <span>| Esta semana</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <!-- <i class="bi bi-people"></i> -->
                                    <i class="bi bi-person-add"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?=$totalNewClient?></h6>
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
                    <div class="card top-selling">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-funnel-fill"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Indique el rango</h6>
                                </li>

                                <form class="dropdown-item" id="formFiltroTopVendedores">
                                    <div class="form-floating">
                                        <input class="form-control form-control-sm" id="formFiltroTopVendedores_min" name="min" type="date" value="<?=$fechaInicioSemana?>" oninput="FormController.validate(this)">
                                        <label for="formFiltroTopVendedores_min">Desde</label>
                                    </div>


                                    <div class="form-floating my-1">
                                        <input class="form-control form-control-sm" id="formFiltroTopVendedores_max" name="max" type="date" value="<?=$fechaFinSemana?>" oninput="FormController.validate(this)">
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
                                            
                                        <!-- <tr>
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
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        <? endif; ?>

                    </div>
                </div><!-- End Top vendedores -->

            </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

            <!-- Medios de pago -->
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
                    <h5 class="card-title">Medios de pago<br><span>Hoy</span></h5>

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
                                            name: 'Mercado pago'
                                        },
                                        {
                                            value: 15,
                                            name: 'Transferencias'
                                        },
                                        {
                                            value: 30,
                                            name: 'Efectivo'
                                        }
                                    ]
                                }]
                            });
                        });
                    </script>

                </div>
            </div><!-- End Medios de pago -->

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

<script>

    document.addEventListener("DOMContentLoaded", e => {

        <? if (Auth::isAdmin()): ?>
            handlerSubmitFiltroVendedores()
        <? endif; ?>
    })

    function handlerSubmitFiltroVendedores(){

        if(document.querySelectorAll("#formFiltroTopVendedores .is-invalid").length > 0){
            Swal.fire("Revise los campos!", "", "warning")
            return
        }

        const min = parseInt(document.getElementById("formFiltroTopVendedores_min").value.replaceAll("-", ""))
        const max = parseInt(document.getElementById("formFiltroTopVendedores_max").value.replaceAll("-", ""))

        if(max < min){
            Swal.fire("Revise el orden de las fechas!", "", "warning")
            return
        }

        fetch(
            `<?= DOMAIN_ADMIN ?>process.php`,
            {
                method: "POST", 
                body: new FormData(document.getElementById("formFiltroTopVendedores"))
            }
        )
        .then(res => res.json())
        .then(vendedores => {

            console.log(vendedores)

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
</script>

<?
$content = ob_get_clean();
require_once PATH_SERVER . "/admin/wraper.php";
