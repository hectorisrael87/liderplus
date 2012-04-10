<?php

include '../../includes/constants.php';
$producto = new producto();
$cliente = new cliente();
$productos = $producto->listar();
$clientes = $cliente->listar();
$variables = array("productos" => $productos, "clientes" => $clientes);
if (isset($_SESSION['usuario'])) {
    array_push($variables, array("usuario" => $_SESSION['usuario']));
}
echo $twig->render('sistema/pedido/registrar.html.twig', $variables);
?>
