<?
date_default_timezone_set("America/Argentina/Buenos_Aires");

/* ----------------------------- */
/*              PROYECT          */
/* ----------------------------- */
define("URI_BASE", "/proyectoFinal/");
define("PATH_SERVER", $_SERVER["DOCUMENT_ROOT"] . "/proyectoFinal/");
define("DOMAIN_NAME", "http://localhost/proyectoFinal/");
define("DOMAIN_ADMIN", "http://localhost/proyectoFinal/admin/");
define("APP_NAME", "TurApp");


/* --------------------- */
/*          SMTP         */
/* --------------------- */
define("SMTP_SERVER", "smtp.gmail.com");
define("SMTP_SECURITY", "SSL");
define("SMTP_PORT", 465);
define("SMTP_USERNAME", "");
define("SMTP_PASSWORD", "");
define("SMTP_FROM_EMAIL", "");