<?php

include_once 'includes/constants.php';
$db = new db();
session_start();

echo $twig->render('index.html.twig', array(
    'session' => $_SESSION));
?>