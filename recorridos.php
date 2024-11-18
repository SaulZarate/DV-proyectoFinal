<?
require_once __DIR__ . "/config/init.php";

if(!isset($_GET["r"]) || !$_GET["r"]) HTTPController::get401(false);

$idRecorrido = base64_decode($_GET["r"]);

$title = "Recorrido ". $recorrido->paquete->titulo;
?>

<!DOCTYPE html>
<html lang="es">
<? require_once PATH_SERVER . "/helpers/sections/head.php" ?>
<body>
    <style>
        
    </style>

    <main class="p-2 p-md-5">
        <section class="section row">

            <!-- TODO: Vista del recorrido -->

        </section>
    </main>
    
    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>

    <script>

        document.addEventListener("DOMContentLoaded", e => {
            
        })

    </script>
    
</body>
</html>



