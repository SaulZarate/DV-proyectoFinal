<?
require_once __DIR__ . "/../config/init.php";

$title = "Salidas";

$fechaDesde     = isset($_GET["desde"]) && $_GET["desde"] ? $_GET["desde"] : date("Y-m-d");
$fechaHasta     = isset($_GET["hasta"]) && $_GET["hasta"] ? $_GET["hasta"] : "";
$precioMinimo   = isset($_GET["minimo"]) && $_GET["minimo"] ? $_GET["minimo"] : 0;
$precioMaximo   = isset($_GET["maximo"]) && $_GET["maximo"] ? $_GET["maximo"] : "";
$noches         = isset($_GET["noches"]) && $_GET["noches"] ? $_GET["noches"] : 0;
$provincias     = isset($_GET["provincia"]) && $_GET["provincia"] ? $_GET["provincia"] : [];
$orden          = isset($_GET["orden"]) && $_GET["orden"] ? $_GET["orden"] : "masNuevo";
$search         = isset($_GET["search"]) && $_GET["search"] ? $_GET["search"] : "";


$filtroSearch = $search ? "AND (p.titulo LIKE '%{$search}%' OR p.subtitulo LIKE '%{$search}%')" : "";
$filtroFechaMinima = " AND DATE(pfs.fecha) >= '{$fechaDesde}'";
$filtroFechaMaxima = $fechaHasta ? " AND DATE(pfs.fecha) <= '{$fechaHasta}'" : "";
$filtroPrecioMinimo = " AND p.precio >= {$precioMinimo}";
$filtroPrecioMaximo = $precioMaximo ? " AND p.precio <= {$precioMaximo}" : "";
$filtroNoches = " AND p.noches >= {$noches}";
$filtroProvincias = $provincias ? " AND p.idProvincia IN (".implode(",", $provincias).")" : "";

$ordenPaquetes = "p.idPaquete DESC"; // Nuevos
if($orden === "menorPrecio") $ordenPaquetes = "p.precio ASC";
if($orden === "mayorPrecio") $ordenPaquetes = "p.precio DESC";

$sqlPaquetes = "SELECT 
        p.*, 
        prov.nombre as provincia
    FROM 
        paquetes p, 
        provincias prov, 
        paquetes_fechas_salida pfs
    WHERE 
        p.idProvincia = prov.idProvincia AND 
        p.idPaquete = pfs.idPaquete AND 
        p.eliminado = 0 AND 
        p.estado = 'A' AND 
        DATE(NOW()) BETWEEN DATE(p.fechaInicioPublicacion) AND DATE(p.fechaFinPublicacion)
        {$filtroSearch}
        
        {$filtroFechaMinima}
        {$filtroFechaMaxima}
        {$filtroPrecioMinimo}
        {$filtroPrecioMaximo}
        {$filtroNoches}
        {$filtroProvincias}
    GROUP BY 
        p.idPaquete
    ORDER BY 
        {$ordenPaquetes}
";


$paquetes = DB::getAll($sqlPaquetes);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <? require_once PATH_SERVER . "public/head.php"; ?>
</head>

<body class="<?= $title ?>">

    <? require_once PATH_SERVER . "public/nav.php"; ?>

    <main class="container mb-5 mt-2 mt-md-4">
        <div class="row">

            <!-- ------------------- -->
            <!--        Filtros      -->
            <!-- ------------------- -->
            <div class="col-md-4 col-lg-3 mb-3">
                <form action="<?= DOMAIN_NAME ?>public/salidas" method="get">
                    <section class="sectionOrden mb-3">
                        <h5 class="fw-bold fs-5 text-primary mb-3 border-bottom"><i class="fas fa-sort-amount-up-alt me-1"></i></i>Orden</h5>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orden" id="masNuevo" value="masNuevo" <?=$orden === "masNuevo" ? "checked" : ""?>>
                            <label class="form-check-label" for="masNuevo">Mas nuevo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orden" id="menorPrecio" value="menorPrecio" <?=$orden === "menorPrecio" ? "checked" : ""?>>
                            <label class="form-check-label" for="menorPrecio">Menor precio</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="orden" id="mayorPrecio" value="mayorPrecio" <?=$orden === "mayorPrecio" ? "checked" : ""?>>
                            <label class="form-check-label" for="mayorPrecio">Mayor precio</label>
                        </div>
                    </section>

                    <section class="sectionFiltros mb-3">
                        <h5 class="fw-bold fs-5 text-primary mb-3 border-bottom"><i class="fa fa-filter me-1"></i>Filtros</h5>
                        
                        <div class="salidas__filtro-contentFechas mb-2">
                            <label class="mb-1" for="">Rango de fechas</label>
                            <div class="input-group">
                                <input type="date" class="form-control form-control-sm" placeholder="Desde" name="desde" oninput="validateForm(this)" value="<?=$fechaDesde?>">
                                <input type="date" class="form-control form-control-sm" placeholder="Hasta" name="hasta" oninput="validateForm(this)" value="<?=$fechaHasta?>">
                            </div>
                        </div>

                        <div class="salidas__filtro-contentPrecio mb-2">
                            <label class="mb-1" for="">Precio</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="tel" class="form-control form-control-sm" placeholder="Mínimo" name="minimo" oninput="validateForm(this)" value="<?=$precioMinimo > 0 ? $precioMinimo : ""?>">
                                <span class="input-group-text">$</span>
                                <input type="tel" class="form-control form-control-sm" placeholder="Máximo" name="maximo" oninput="validateForm(this)" value="<?=$precioMaximo?>">
                            </div>
                        </div>

                        <div class="salidas__filtro-contentNoches mb-2">
                            <label class="mb-1" for="noches">Cantidad de noches</label>
                            <input type="number" class="form-control form-control-sm" id="noches" name="noches" oninput="validateForm(this)" value="<?=$noches?>">
                        </div>

                        <div class="salidas__filtro-contentNoches mb-2">
                            <label class="mb-1">Provincia</label>
                            <? foreach (DB::getAll("SELECT * FROM provincias ORDER BY nombre") as $provincia): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck<?=$provincia->idProvincia?>" name="provincia[]" value="<?=$provincia->idProvincia?>" <?=in_array($provincia->idProvincia, $provincias) ? "checked" : ""?>>
                                    <label class="form-check-label" for="gridCheck<?=$provincia->idProvincia?>"><?=ucfirst($provincia->nombre)?></label>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </section>



                    <button class="btn btn-sm btn-primary bg-primary border-0"><i class="fa fa-filter me-1"></i>Filtrar</button>
                    <a href="<?= DOMAIN_NAME ?>public/salidas" class="btn btn-sm btn-primary bg-secondary border-0"><i class="fa fa-eraser me-1"></i>Limpiar</a>
                </form>
            </div>


            <!-- ------------------- -->
            <!--        Salidas      -->
            <!-- ------------------- -->
            <div class="col-md-8 col-lg-9">
                <div class="d-flex justify-content-between align-items-center pb-2">
                    <h5 class="fw-bold fs-5 text-primary"><i class="me-1 fa fa-hiking"></i>Salidas (<?=count($paquetes)?>)</h5>

                    <div>
                        <form class="input-group" action="<?= DOMAIN_NAME ?>public/salidas" method="get">
                            <input type="text" class="form-control form-control-sm" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="btnSearch" name="search" required>
                            <button class="btn btn-sm bg-3 text-white" type="submit" id="btnSearch"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </div>

                <section class="sectionSalidas row">
                    <? if (!$paquetes): ?>
                        <div class="col-12 text-center align-middle border-top mt-1 pt-2">
                            <h2 class="h3">Sin salidas disponibles</h2>
                        </div>
                    <? endif; ?>

                    <? foreach ($paquetes as $paquete): ?>
                        <div class="col-md-6 col-lg-4 mb-3 cardPaquete ">
                            <a class="d-block rounded" href="<?= DOMAIN_NAME ?>detalle?id=<?= base64_encode($paquete->idPaquete) ?>">
                                <div class="contentImage">
                                    <? if ($paquete->traslado === 1): ?>
                                        <p class="badge bg-3 tagTraslado shadow"><i class="fa fa-bus me-1"></i>Con Traslado</p>
                                    <? endif; ?>
                                    <img src="<?= DOMAIN_NAME ?><?= $paquete->imagen ?>" alt="Imagen principal de <?= $paquete->titulo ?>">
                                </div>

                                <div class="contentText px-3">
                                    <div class="contentHeader text-primary mt-2">
                                        <p class="fw-bold fs-6"><?= ucfirst($paquete->titulo) ?></p>
                                        <p class=""><?= ucfirst($paquete->subtitulo) ?></p>
                                    </div>

                                    <div class="contentBody text-6">
                                        <p><i class="fa fa-map-marker-alt me-1"></i><?= ucfirst($paquete->provincia) . ", " . ucfirst($paquete->destino) ?></p>
                                        <p><i class="fa fa-utensils me-1"></i><?= ucfirst($paquete->pension) ?></p>
                                        <p><i class="fa fa-calendar-day me-1"></i><?= COUNT(Paquete::getAllFechasSalida($paquete->idPaquete, $fechaDesde)) ?> salidas programadas</p>

                                        <? if ($paquete->noches == 0): ?>
                                            <p><i class="far fa-sun me-1"></i>1 día</p>
                                        <? else: ?>
                                            <p><i class="fa moon me-1"></i><?= $paquete->noches ?> noche<?= $paquete->noches > 1 ? "s" : "" ?></p>
                                        <? endif; ?>
                                    </div>

                                    <div class="contentFooter pb-1">
                                        <p class="text-3 fs-1">$<?= Util::numberToPrice($paquete->precio, true) ?><span class="fs-6">/persona</span></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <? endforeach; ?>
                </section>
            </div>
        </div>
    </main>

    <? require_once PATH_SERVER . "public/footer.php"; ?>
    <? require_once PATH_SERVER . "public/script.php"; ?>

    <script>
        
    </script>
</body>

</html>