<?

session_start();
require_once __DIR__ . "/constants.php";

require_once PATH_SERVER . "config/db.php"; // include class Database
require_once PATH_SERVER . "helpers/Util.php";

require_once PATH_SERVER . "controllers/HTTPController.php";
require_once PATH_SERVER . "controllers/FileController.php";
require_once PATH_SERVER . "controllers/Auth.php";
require_once PATH_SERVER . "controllers/Usuario.php";
require_once PATH_SERVER . "controllers/Paquete.php";
require_once PATH_SERVER . "controllers/Cliente.php";




/* -------------------------- */
/*          CONFIG PAGE       */
/* -------------------------- */
$title      = "Panel administrativo";
$section    = "";
$subSection = "";

/* ----------------------------- */
/*          BARRA LATERAL        */
/* ----------------------------- */

// Admin
$menu = array(
    "dashboard" => array(
        "name"          => "dashboard",
        "icon"          => "bi bi-grid",
        "path"          => DOMAIN_ADMIN . "dashboard/"
    ),
    "usuarios" => array(
        "name"          => "usuarios",
        "icon"          => "bi bi-person-workspace",
        "path"          => DOMAIN_ADMIN . "usuarios/"
    ),
    "excursiones" => array(
        "name"          => "excursiones",
        "icon"          => "bi bi-backpack2",
        "path"          => DOMAIN_ADMIN . "excursiones/"
    ),
    "clientes" => array(
        "name"          => "clientes",
        "icon"          => "bi bi-people",
        "path"          => DOMAIN_ADMIN . "clientes/"
    ),
    
    
    
    /* 
    Example navitems
    array(
        "name"          => "Paquetes (a futuro)",
        "icon"          => "bi bi-box-seam",
        "path"          => DOMAIN_ADMIN . "paquetes/"
    ),

    array(
        "name"          => "components",
        "icon"          => "bi bi-menu-button-wide",
        "path"          => "",
        "subSection"    => array(
            array(
                "name"  => "alerts",
                "path"  => DOMAIN_ADMIN . "components/alerts",
            )
        )
    ),
    array(
        "type"          => "separate",
        "name"          => "separador",
    ) */
);

// Convierto el menu en un array de objetos
$menu = json_decode(json_encode($menu, JSON_UNESCAPED_UNICODE));
