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
            <h1>500</h1>
            <h2 class="text-center">Error en el servidor, vuelva en unos minutos</h2>
            <img src="<?=DOMAIN_NAME?>assets/img/server-down.svg" class="img-fluid py-5" alt="server error">
            
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