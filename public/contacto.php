<? 
require_once __DIR__ . "/../config/init.php"; 
$title = "Contacto";
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <? require_once PATH_SERVER . "public/head.php"; ?>
</head>

<body class="<?=$title?>">

  <? require_once PATH_SERVER . "public/nav.php"; ?>

  <main class="container mb-5 mt-3">
    <h1 class="fw-bold fs-2 text-primary mb-3">Contactanos</h1>

    <section class="row">
        <div class="col-md-6 mb-3">
            <article class="mb-3">
                <h5 class="text-secondary fw-bold h4"><i class="fa fa-store me-1"></i>Agencia</h5>
                <p class="text-6">Agencia TurApp 2024 &reg; - Ascenso32 EVT Leg:50196 - Disp:1232/20 </p>
                <p class="text-6"><i class="fa fa-map-marker-alt me-1"></i>Argentina, C.A.B.A. - Av. Corrientes 2037</p>
            </article>

            <article>
                <h5 class="text-secondary fw-bold h4"><i class="far fa-clock me-1"></i>Horario de atención</h5>
                <p class="text-6">Lunes a Viernes de 10hs a 19hs.</p>
                <p class="text-6">Sábados de 10hs a 13hs.</p>
            </article>
        </div>
        <div class="col-md-6 mb-3">
            <h5 class="text-secondary fw-bold h4"><i class="far fa-comment-dots me-1"></i>Contacto</h5>
            <p class="text-6"><i class="fa fa-phone me-1"></i>+54 11 6242 6321</p>
            <p class="text-6"><i class="fa fa-whatsapp me-1"></i>+54 9 11 2045 9685</p>
            <p class="text-6"><i class="fa fa-envelope me-1"></i>contacto@<?=strtolower(APP_NAME)?>.com</p>
        </div>

    </section>

    <section class="border rounded">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13135.97065806217!2d-58.396!3d-34.604347!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bccaea670d4e67%3A0x2198c954311ad6d9!2sDa%20Vinci%20%7C%20Primera%20Escuela%20de%20Arte%20Multimedial!5e0!3m2!1ses!2sar!4v1733329215474!5m2!1ses!2sar" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

  </main>

  <? require_once PATH_SERVER . "public/footer.php"; ?>
  <? require_once PATH_SERVER . "public/script.php"; ?>
</body>
</html>