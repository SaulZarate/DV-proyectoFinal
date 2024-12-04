<? 
require_once __DIR__ . "/config/init.php"; 

$title = "Inicio";

$lastPaquetes = DB::getAll(
  "SELECT 
    p.*, prov.nombre as provincia FROM paquetes p, provincias prov 
  WHERE 
    p.idProvincia = prov.idProvincia AND p.eliminado = 0 AND p.estado = 'A' AND DATE(p.fechaInicioPublicacion) <= DATE(NOW()) AND DATE(NOW()) <= DATE(p.fechaFinPublicacion) 
  ORDER BY 
    p.idPaquete DESC 
  LIMIT 4");

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <? require_once PATH_SERVER . "public/head.php"; ?>
</head>

<body>

  <? require_once PATH_SERVER . "public/header.inicio.php"; ?>

  <main class="mb-5 mt-4 mt-md-5 container">
    <h3 class="text-center fw-bold fs-1 text-primary mb-3">Nuevas salidas</h3>

    <section class="sectionExcursiones row">
      <? foreach ($lastPaquetes as $paquete): 
        ?>
        <div class="col-md-6 col-lg-3 mb-3">
          <a href="<?=DOMAIN_NAME?>detalle?id=<?=base64_encode($paquete->idPaquete)?>">
            <div class="contentImage">
              <img src="<?=DOMAIN_NAME?><?=$paquete->imagen?>" alt="Imagen principal de <?=$paquete->titulo?>" class="rounded">
            </div>

            <div class="contentHeader text-primary fw-bold my-2">
              <p class="fs-5"><?=ucfirst($paquete->titulo)?></p>
              <p class=""><?=ucfirst($paquete->subtitulo)?></p>
            </div>
            
            <div class="contentBody text-6">
              <p><i class="fa fa-map-marker-alt me-1"></i><?=ucfirst($paquete->provincia) . ", " . ucfirst($paquete->destino)?></p>
              <p><i class="fa fa-utensils me-1"></i><?=ucfirst($paquete->pension)?></p>
              <p><i class="fa fa-calendar-day me-1"></i><?=COUNT(Paquete::getAllFechasSalida($paquete->idPaquete))?> salidas programadas</p>
              <? if ($paquete->traslado === 1): ?>
                <p><i class="fa fa-bus me-1"></i><?=$paquete->traslado?>Incluye traslado</p>
              <? endif; ?>
            </div>

            <div class="contentFooter">
              <p class="text-3 fs-1">$<?=Util::numberToPrice($paquete->precio, true)?><span class="fs-6"> x persona</span></p>
            </div>
          </a>
        </div>
      <? endforeach; ?>
    </section>
  </main>

  <? require_once PATH_SERVER . "public/footer.php"; ?>
  <? require_once PATH_SERVER . "public/script.php"; ?>
  
</body>

</html>