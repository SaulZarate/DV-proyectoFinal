<!DOCTYPE html>
<html lang="es">

<body>
    <? require_once PATH_SERVER . "/helpers/sections/head.php" ?>
    <? require_once PATH_SERVER . "/helpers/sections/header.php" ?>
    <? require_once PATH_SERVER . "/helpers/sections/aside.php" ?>
    
    <main id="main" class="main">
        <?= $content ?>
    </main>

    <? require_once PATH_SERVER . "/helpers/sections/footer.php" ?>
    <? require_once PATH_SERVER . "/helpers/sections/script.php" ?>
</body>
</html>