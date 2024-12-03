<? 
require_once __DIR__ . "/config/init.php"; 

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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicons -->
  <link href="<?= DOMAIN_NAME ?>assets/img/favicon.png" rel="icon">
  <link href="<?= DOMAIN_NAME ?>assets/img/favicon.png" rel="apple-touch-icon">

  <title>Inicio | <?= APP_NAME ?></title>

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">


  <link href="<?= DOMAIN_NAME ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?= DOMAIN_NAME ?>assets/css/app.public.css">

</head>

<body>

  <header class="container-fluid shadow">

    <div class="header__contentText text-white">
      <h1>Bienvenido a TurApp!</h1>
      <p>Contamos con salidas para todo tipo de gustos</p>
    </div>

    <div class="contentNav container pt-3 sticky-top">
      <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">
            <img src="<?= DOMAIN_NAME ?>assets/img/logo.png" alt="logo de tur app" height="70" width="70">
          </a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" href="/">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Calendario</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Salidas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Preguntas frecuentes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contacto</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>



  <main class="my-5 container">
    <h3 class="text-center fw-bold fs-1 text-primary mb-3">Nuevas salidas</h3>

    <section class="excursiones row">
      <? foreach ($lastPaquetes as $paquete): 
        ?>
        <div class="col-md-6 col-lg-3 mb-2">
          <a href="<?=DOMAIN_NAME?>detalle?id=<?=base64_encode($paquete->idPaquete)?>">
            <div class="contentImage">
              <img src="<?=DOMAIN_NAME?><?=$paquete->imagen?>" alt="Imagen principal de <?=$paquete->titulo?>" class="rounded" style="height: 400px; width: 100%; object-fit: cover; object-position: center;">
            </div>

            <div class="contentHeader text-primary fw-bold my-2">
              <p class="fs-5"><?=ucfirst($paquete->titulo)?></p>
              <p class=""><?=ucfirst($paquete->subtitulo)?></p>
            </div>
            
            <div class="contentBody text-6" style="min-height: 100px;">
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



  <footer class="bg-3">
      <section class="text-center text-light pt-5 pb-3">
          <h5 class="fw-bold fs-1 mb-2">Contactanos por nuestras redes</h5>

          <div class="d-flex justify-content-center container fs-1 contentRedesSociales">
            <a href="//instagram.com/turApp" class="text-light me-3" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="//tiktok.com/turApp" class="text-light me-3" target="_blank"><i class="fab fa-tiktok"></i></a>
            <a href="//facebook.com/turApp" class="text-light me-3" target="_blank"><i class="fa fa-facebook"></i></a>
            <a href="//youtube.com/turApp" class="text-light me-3" target="_blank"><i class="fab fa-youtube"></i></a>
            <a href="//linkedin.com/turApp" class="text-light me-3" target="_blank"><i class="fa fa-linkedin"></i></a>
            <a href="mailto:turApp@gmail.com" class="text-light me-3" target="_blank"><i class="fa fa-envelope"></i></a>
          </div>
      </section>

      <p class="py-2 text-white-50 text-center">Desarrollado & diseñado por <b>TurApp</b></p>
  </footer>
  
  <!-- Bootstrap 5 -->
  <script src="<?= DOMAIN_NAME ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/deecb3ce02.js" crossorigin="anonymous"></script>
</body>

</html>