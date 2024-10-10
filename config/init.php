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
        "path"          => DOMAIN_NAME . "admin/dashboard/"
    ),
    array(
        "name"          => "usuarios",
        "icon"          => "bi bi-people",
        "path"          => DOMAIN_NAME . "admin/usuarios/"
    ),
    array(
        "name"          => "paquetes",
        "icon"          => "bi bi-box-seam",
        "path"          => DOMAIN_NAME . "admin/paquetes/"
    ),



    /* 
    Example navitems

    array(
        "name"          => "components",
        "icon"          => "bi bi-menu-button-wide",
        "path"          => "",
        "subSection"    => array(
            array(
                "name"  => "alerts",
                "path"  => DOMAIN_NAME . "admin/components/alerts",
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
