<?php
// <editor-fold defaultstate="collapsed" desc="configuracion regional">
date_default_timezone_set("America/Caracas");
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Acceso a la BD">
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "root");
define("DB", "liderplus");
// </editor-fold>
//<editor-fold defaultstate="collapsed" desc="configuracion de ficheros del sistema">
define("SISTEMA", "/liderplus/");
define("EMAIL_ERROR",false);
define("EMAIL_CONTACTO","anyulled@gmail.com");
define("EMAIL_TITULO","error");
define("MOSTRAR_ERROR",true);
define("DEBUG",true);

define("TITULO", "LiderPlus - Sistema para el control de pedidos");
/**
 * para las urls
 */
define("ROOT", 'http://' . $_SERVER['SERVER_NAME'] . SISTEMA);
/**
 * para los includes
 */
define("SERVER_ROOT", $_SERVER['DOCUMENT_ROOT'] . SISTEMA);
set_include_path(SERVER_ROOT . "/liderplus/");
define("TEMPLATE", SERVER_ROOT . "/template/");

//</editor-fold>
////<editor-fold defaultstate="collapsed" desc="Twig">
include_once SERVER_ROOT. 'includes/twig/lib/Twig/Autoloader.php';
include_once SERVER_ROOT. 'includes/extensiones.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(SERVER_ROOT . 'template');
$twig = new Twig_Environment($loader, array(
            'debug' => true,
            'cache' => SERVER_ROOT. 'cache',
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
spl_autoload_register("__autoload",false);
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="cerrar sesión">
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $user_logout = new usuario();
    $user_logout->logout();
}
//</editor-fold>