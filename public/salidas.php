<?
require_once __DIR__ . "/../config/init.php";

$title = "Salidas";

$paquetes = DB::getAll("SELECT 
        p.*, 
        prov.nombre as provincia, 
        COUNT(pfs.fecha) as fechasSalida
    FROM 
        paquetes p, 
        provincias prov, 
        paquetes_fechas_salida pfs
    WHERE 
        p.idProvincia = prov.idProvincia AND 
        p.idPaquete = pfs.idPaquete AND 
        p.eliminado = 0 AND 
        p.estado = 'A' AND 
        DATE(p.fechaInicioPublicacion) <= DATE(NOW()) AND 
        DATE(NOW()) <= DATE(p.fechaFinPublicacion) AND 
        DATE(pfs.fecha) >= DATE(NOW())
    GROUP BY 
        p.idPaquete
    ORDER BY 
        p.idPaquete DESC 
");


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
            <div class="col-md-3 mb-3">
                <form action="<?=DOMAIN_NAME?>public/salidas" method="get">
                    <section class="sectionFiltros mb-3">
                        <h5 class="fw-bold fs-4 text-primary mb-3 border-bottom"><i class="fa fa-filter me-1"></i>Filtros</h5>
                        <p>Buscador</p>
                        <p>Provincia</p>
                        <p>Provincia</p>
                        <p>Cupos mínimo</p>
                        <p>Cupos máximo</p>
                        <p>Precio mínimo</p>
                        <p>Precio máximo</p>
                        <p>Cantidad noches</p>
                    </section>

                    <section class="sectionOrden mb-3">
                        <h5 class="fw-bold fs-4 text-primary mb-3 border-bottom"><i class="fas fa-sort-amount-up-alt me-1"></i></i>Orden</h5>
                        <p>Precio (mayor y menor)</p>
                        <p>Mas nuevo</p>
                        <p>Mas ventas</p>
                    </section>

                    <button class="btn btn-primary bg-primary border-0"><i class="fa fa-search me-1"></i>Filtrar</button>
                </form>
            </div>

            <div class="col-md-9">
                <h5 class="fw-bold fs-3 text-primary mb-3 border-bottom"><i class="me-1 fa fa-hiking"></i>Salidas</h5>

                <section class="sectionSalidas row">
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
                                        <p><i class="fa fa-calendar-day me-1"></i><?= COUNT(Paquete::getAllFechasSalida($paquete->idPaquete)) ?> salidas programadas</p>

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
</body>

</html>