<?php

include_once 'includes/constants.php';
$usuario = new usuario();
if (isset($_POST['submit'])) {
    $usuario->login($_POST['login'], $_POST['password'], 0);
} 
echo $twig->render('login.html.twig');
?>
