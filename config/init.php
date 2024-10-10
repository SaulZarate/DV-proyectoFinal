<?

session_start();
require_once __DIR__ . "/constants.php";

require_once PATH_SERVER . "config/db.php"; // include class Database
require_once PATH_SERVER . "helpers/Util.php";

require_once PATH_SERVER . "controllers/HTTPController.php";
require_once PATH_SERVER . "controllers/Auth.php";
require_once PATH_SERVER . "controllers/Usuario.php";




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
    array(
        "name"          => "dashboard",
        "icon"          => "bi bi-grid",
        "path"          => DOMAIN_ADMIN . "dashboard/"
    ),
    array(
        "name"          => "usuarios",
        "icon"          => "bi bi-people",
        "path"          => DOMAIN_ADMIN . "usuarios/"
    ),
    array(
        "name"          => "Excursiones",
        "icon"          => "bi bi-bus-front",
        "path"          => DOMAIN_ADMIN . "excursiones/"
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
