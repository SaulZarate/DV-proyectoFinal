<?
require_once __DIR__ . "/../config/init.php";

$title = "Calendario";

$mesesSalidas = array();
$paquetesByFechaSalida = array();

$queryPaquetes = "SELECT 
        p.*, 
        prov.nombre as provincia, 
        pfs.id as idFechaSalida, 
        pfs.fecha as fechaSalida
    FROM 
        paquetes p, provincias prov, paquetes_fechas_salida pfs
    WHERE 
        p.idProvincia = prov.idProvincia AND 
        p.idPaquete = pfs.idPaquete AND 
        p.eliminado = 0 AND 
        p.estado = 'A' AND 
        DATE(p.fechaInicioPublicacion) <= DATE(NOW()) AND 
        DATE(NOW()) <= DATE(p.fechaFinPublicacion) AND 
        DATE(pfs.fecha) >= DATE(NOW())
    ORDER BY 
        pfs.fecha ASC 
";

foreach (DB::getAll($queryPaquetes) as $paquete) {
    $fechaFormateada = date("Ym", strtotime($paquete->fechaSalida));

    $mesesSalidas[] = $fechaFormateada;
    $paquetesByFechaSalida[$fechaFormateada][] = $paquete;
}

$mesesSalidas = array_unique($mesesSalidas);
sort($mesesSalidas);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <? require_once PATH_SERVER . "public/head.php"; ?>
</head>

<body class="<?= $title ?>">

    <? require_once PATH_SERVER . "public/nav.php"; ?>

    <main class="container mb-5 mt-3">
        <h1 class="fw-bold fs-2 text-primary mb-3">Calendario Anual</h1>

        <section class="sectionCalendario">
            <? foreach ($mesesSalidas as $mesSalida):
                $anio = substr($mesSalida, 0, 4);
                $mes = substr($mesSalida, 4, 2);
            ?>
                <div class="bg-secondary py-1 rounded mb-1">
                    <p class="fs-5 text-white ps-2"><i class="fa fa-calendar-alt me-1"></i><?= ucfirst(Util::numberMonthToMes($mes)) ?> <?=$anio?></p>
                </div>

                <div class="table-responsive">
                    <table class="table border align-middle">
                        <thead></thead>
                        <tbody>
                            <? foreach ($paquetesByFechaSalida[$mesSalida] as $paquete): 
                                $cuposDisponibles = Paquete::getCuposDisponibles($paquete->idPaquete, $paquete->fechaSalida);
                            ?>
                                <tr>
                                    <td class="calendar__contentImage">
                                        <img src="<?= DOMAIN_NAME ?><?= $paquete->imagen ?>" alt="<?= $paquete->titulo ?>">
                                    </td>
                                    <td>
                                        <div class="calendar__contentText d-flex">
                                            <p class="badge bg-secondary d-flex align-items-center me-1"><?= date("d/m", strtotime($paquete->fechaSalida)) ?></p>
                                            <p class="badge bg-3 d-flex align-items-center me-1"><i class="fa fa-users me-1"></i><?=$cuposDisponibles?> lugar<?=$cuposDisponibles > 1 ? "es" : ""?></p>
                                            <p class="text-6"><?= ucfirst($paquete->titulo) ?> | <?= ucfirst($paquete->provincia) ?>, <?= $paquete->destino ?></p>
                                        </div>
                                    </td>
                                    <td class="calendar__contentButton">
                                        <div class="d-grid">
                                            <a href="<?= DOMAIN_NAME ?>detalle?id=<?= rtrim(base64_encode($paquete->idPaquete), "=") ?>&idFecha=<?=rtrim(base64_encode($paquete->idFechaSalida), "=")?>&fecha=<?= $paquete->fechaSalida ?>" class="btn btn-sm btn-primary bg-primary border-0"><i class="fa fa-plus me-1"></i>Info</a>
                                        </div>
                                    </td>
                                </tr>
                            <? endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <? endforeach; ?>
        </section>

    </main>

    <? require_once PATH_SERVER . "public/footer.php"; ?>
    <? require_once PATH_SERVER . "public/script.php"; ?>
</body>

</html>