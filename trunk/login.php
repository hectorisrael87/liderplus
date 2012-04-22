<?php

include_once 'includes/constants.php';
$usuario = new usuario();
$result = array();
if (isset($_POST['submit'])) {
    $result = $usuario->login($_POST['login'], $_POST['password'], 0);
}
echo $twig->render('login.html.twig', array("mensaje" => $result));
?>
