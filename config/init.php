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
require_once PATH_SERVER . "controllers/Calendario.php";
require_once PATH_SERVER . "controllers/Alojamiento.php";
require_once PATH_SERVER . "controllers/Origen.php";
require_once PATH_SERVER . "controllers/Consulta.php";




/* -------------------------- */
/*          CONFIG PAGE       */
/* -------------------------- */
$title      = "Panel administrativo";
$section    = "";
$subSection = "";

/* ----------------------------- */
/*          BARRA LATERAL        */
/* ----------------------------- */

/* Menu general */
$menu["dashboard"] = array(
    "name"          => "dashboard",
    "icon"          => "bi bi-grid",
    "path"          => DOMAIN_ADMIN . "dashboard/"
);
$menu["calendario"] = array(
    "name"          => "calendario",
    "icon"          => "bi bi-calendar",
    "path"          => DOMAIN_ADMIN . "calendario/"
);
$menu["clientes"] = array(
    "name"          => "clientes",
    "icon"          => "bi bi-people",
    "path"          => DOMAIN_ADMIN . "clientes/"
);
$menu["consultas"] = array(
    "name"          => "consultas",
    "icon"          => "bi bi-chat-right-text",
    "path"          => DOMAIN_ADMIN . "consultas/",
    "subSection"    => array(
        array(
            "name"  => "Nueva consulta",
            "path"  => DOMAIN_ADMIN . "consultas/create",
        ),
        array(
            "name"  => "Abiertas",
            "path"  => DOMAIN_ADMIN . "consultas/?s=A",
        ),
        array(
            "name"  => "Vendidas",
            "path"  => DOMAIN_ADMIN . "consultas/?s=V",
        ),
        array(
            "name"  => "Cerradas",
            "path"  => DOMAIN_ADMIN . "consultas/?s=C",
        ),
    )
);
$menu["alojamientos"] = array(
    "name"          => "alojamientos",
    "icon"          => "bi bi-building",
    "path"          => DOMAIN_ADMIN . "alojamientos/"
);
$menu["origenes"] = array(
    "name"          => "origenes",
    "icon"          => "bi bi-inbox-fill",
    "path"          => DOMAIN_ADMIN . "origenes/"
);

/* Menu admin */
if(Auth::isAdmin()){
    $menu = array_merge(
        $menu, 
        array(
            array(
                "type"          => "separate",
                "name"          => "Administrador",
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
            )
        )
    );
}

// Convierto el menu en un array de objetos
$menu = json_decode(json_encode($menu, JSON_UNESCAPED_UNICODE));
