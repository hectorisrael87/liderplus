<?php

include_once 'includes/constants.php';
$db = new db();


echo $twig->render('index.html.twig', array(
    'session' => array(
        "usuario" => array(
            "Nombre" => "Anyul",
            "Apellido" => "Rivas"))));
?>