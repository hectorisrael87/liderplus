<?php

include_once 'includes/constants.php';
$db = new db();
$funcionalidad = new funcionalidad;
$menu = array();
session_start();
if (isset($_SESSION['usuario'])) {
    $menu = $funcionalidad->funcionalidad_grupo($_SESSION['usuario']['grupo_id']);
}

echo $twig->render('index.html.twig', array(
    "menu" => $menu['data'],
    'session' => $_SESSION));
?>