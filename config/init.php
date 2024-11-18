<?

session_start();
require_once __DIR__ . "/includes.php";



/* -------------------------- */
/*          CONFIG PAGE       */
/* -------------------------- */
$title      = "Panel administrativo";
$section    = "";
$subSection = "";

/* ----------------------------- */
/*          BARRA LATERAL        */
/* ----------------------------- */


$menu["dashboard"] = array(
    "name"          => "dashboard",
    "icon"          => "bi bi-grid",
    "path"          => DOMAIN_ADMIN . "dashboard/", 
    "rol"           => "vendedor"
);
$menu["calendario"] = array(
    "name"          => "calendario",
    "icon"          => "bi bi-calendar",
    "path"          => DOMAIN_ADMIN . "calendario/", 
    "rol"           => "vendedor"
);
$menu["clientes"] = array(
    "name"          => "clientes",
    "icon"          => "bi bi-people",
    "path"          => DOMAIN_ADMIN . "clientes/", 
    "rol"           => "vendedor"
);
$menu["consultas"] = array(
    "name"          => "consultas",
    "icon"          => "bi bi-chat-right-text",
    "path"          => DOMAIN_ADMIN . "consultas/",
    "rol"           => "vendedor",
    "subSection"    => array(
        "nueva" => array(
            "name"  => "Nueva consulta",
            "path"  => DOMAIN_ADMIN . "consultas/create",
            "rol"   => "vendedor",
        ),
        "Abiertas" => array(
            "name"  => "Abiertas",
            "path"  => DOMAIN_ADMIN . "consultas/?s=A",
            "rol"   => "vendedor",
        ),
        "Vendidas" => array(
            "name"  => "Vendidas",
            "path"  => DOMAIN_ADMIN . "consultas/?s=V",
            "rol"   => "vendedor",
        ),
        "Cerradas" => array(
            "name"  => "Cerradas",
            "path"  => DOMAIN_ADMIN . "consultas/?s=C",
            "rol"   => "vendedor",
        ),
    )
);
$menu["alojamientos"] = array(
    "name"          => "alojamientos",
    "icon"          => "bi bi-building",
    "path"          => DOMAIN_ADMIN . "alojamientos/", 
    "rol"           => "vendedor"
);
$menu["origenes"] = array(
    "name"          => "origenes",
    "icon"          => "bi bi-inbox-fill",
    "path"          => DOMAIN_ADMIN . "origenes/", 
    "rol"           => "vendedor"
);
$menu["recorridos"] =  array(
    "name"          => "recorridos",
    "icon"          => "bi bi-bus-front",
    "path"          => DOMAIN_ADMIN . "recorridos/", 
    "rol"           => "vendedor", 
    "subSection"    => array(
        "nuevo" => array(
            "name"  => "Nuevo",
            "path"  => DOMAIN_ADMIN . "recorridos/editar",
            "rol"   => "vendedor", 
        ),
        "listado" => array(
            "name"  => "Listado",
            "path"  => DOMAIN_ADMIN . "recorridos/",
            "rol"   => "vendedor", 
        ),
        "excursiones" => array(
            "name"  => "Excursiones sin recorrido",
            "path"  => DOMAIN_ADMIN . "recorridos/excursiones",
            "rol"   => "vendedor", 
        )
    )
);

/* Menu admin */
$menu[] = array(
    "type"          => "separate",
    "name"          => "Administrador",
    "rol"           => "admin"
);
$menu["usuarios"] = array(
    "name"          => "usuarios",
    "icon"          => "bi bi-person-workspace",
    "path"          => DOMAIN_ADMIN . "usuarios/",
    "rol"           => "admin"
);
$menu["excursiones"] = array(
    "name"          => "excursiones",
    "icon"          => "bi bi-backpack2",
    "path"          => DOMAIN_ADMIN . "excursiones/",
    "rol"           => "admin"
);



// Convierto el menu en un array de objetos
$menu = json_decode(json_encode($menu, JSON_UNESCAPED_UNICODE));
