<? 
    require_once __DIR__ . "/config/init.php";
?>

<!DOCTYPE html>
<html lang="es">
    <? require_once PATH_SERVER . "/helpers/sections/head.php" ?>
<body>
    <main>
        <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>401</h1>
            <h2 class="text-center">No tienes permisos para acceder a este recurso</h2>
            <img src="<?=DOMAIN_NAME?>assets/img/access_denied.svg" class="img-fluid py-5" alt="Page unauthorized page" style="height: 500px;">
            <? if (isset($_GET["btn"]) && $_GET["btn"] == 1): ?>
                <a class="btn" href="javascript:;" onclick="history.back()">Back to home</a>
            <? endif; ?>
            
            <div class="credits mt-4">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </section>

        </div>
    </main>

    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>
</body>
</html>