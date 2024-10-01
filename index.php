<?
require_once __DIR__ . "/config/init.php";

// Reemplazar por el controller

// Si no hay una sessión activa lo mando a loguearse
if (!isset($_SESSION["user"])) {
  header("Location: ".DOMAIN_NAME."login.php");
  die;
}

// Lo redirecciono al panel

