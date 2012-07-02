<?php

// <editor-fold defaultstate="collapsed" desc="configuracion regional">
date_default_timezone_set("America/Caracas");
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="init">
$debug = true;
$sistema = "/liderplus/";
$email_error = false;
$mostrar_error = true;
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Cheqeuo servidor">
if ($_SERVER['SERVER_NAME'] == "www.liderplus.co.ve" | $_SERVER['SERVER_NAME'] == "liderplus.co.ve") {
    $user = "asgmu_user";
    $password = "32ltiplex32";
    $db = "asgmu_liderplus";
    $email_error = true;
    $mostrar_error = false;
    $debug = false;
    $sistema = "/";
} else {
    $user = "root";
    $password = "root";
    $db = "liderplus";
}
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Acceso a la BD">
define("HOST", "localhost");
define("USER", $user);
define("PASSWORD", $password);
define("DB", $db);
// </editor-fold>
//<editor-fold defaultstate="collapsed" desc="configuracion de ficheros del sistema">
define("SISTEMA", $sistema);
define("EMAIL_ERROR", $email_error);
define("EMAIL_CONTACTO", "anyulled@gmail.com");
define("EMAIL_TITULO", "error");
define("MOSTRAR_ERROR", $mostrar_error);
define("DEBUG", $debug);

define("TITULO", "LiderPlus - Sistema para el control de pedidos");
/**
 * para las urls
 */
define("ROOT", 'http://' . $_SERVER['SERVER_NAME'] . SISTEMA);
define("URL_SISTEMA", ROOT . "sistema");
/**
 * para los includes
 */
define("SERVER_ROOT", $_SERVER['DOCUMENT_ROOT'] . SISTEMA);
set_include_path(SERVER_ROOT . "/liderplus/");
define("TEMPLATE", SERVER_ROOT . "/template/");

//</editor-fold>
////<editor-fold defaultstate="collapsed" desc="Twig">
include_once SERVER_ROOT . 'includes/twig/lib/Twig/Autoloader.php';
include_once SERVER_ROOT . 'includes/extensiones.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(SERVER_ROOT . 'template');
$twig = new Twig_Environment($loader, array(
            'debug' => true,
            'cache' => SERVER_ROOT . 'cache',
            "auto_reload" => true)
);
if (isset($_SESSION))
    $twig->addGlobal("session", $_SESSION);

$twig->addExtension(new extensiones());
$twig->addExtension(new Twig_Extension_Debug());

//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="autoload">
function __autoload($clase) {
    include_once SERVER_ROOT . "/includes/" . $clase . ".php";
}

spl_autoload_register("__autoload", false);
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="cerrar sesión">
if (isset($_GET['logout']) && $_GET['logout'] == true) {
    $user_logout = new usuario();
    $user_logout->logout();
}
//</editor-fold>

// <editor-fold defaultstate="collapsed" desc="STATUS DEL SISTEMA">
define("STATUS_PEDIDO_PENDIENTE", 1);
define("STATUS_PEDIDO_PROCESADO", 2);
define("STATUS_PEDIDO_ALMACEN", 3);
define("STATUS_PEDIDO_CANCELADO", 4);

define("STATUS_PEDIDO_DETALLE_PENDIENTE", 1);
define("STATUS_PEDIDO_DETALLE_PROCESADO", 2);
define("STATUS_PEDIDO_DETALLE_RECHAZADO", 3);

define("STATUS_FACTURA_PENDIENTE", 1);
define("STATUS_FACTURA_PAGADA", 2);

define("FASE_PEDIDO", 1);
define("FASE_ALMACEN", 2);
define("FASE_FACTURACION", 3);
define("FASE_CHEQUEO", 4);
define("FASE_TRANSPORTE", 5);
define("FASE_CONFIRMACION", 6); 
// </editor-fold>
