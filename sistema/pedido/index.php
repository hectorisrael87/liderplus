<?php

include '../../includes/constants.php';
$producto = new pedido();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

switch ($accion) {
    case "crear":
    case "registrar":
        $producto = new producto();
        $cliente = new cliente();
        $productos = $producto->listar();
        $clientes = $cliente->listar();
        $variables = array(
            "accion" => "Registrar",
            "productos" => $productos,
            "clientes" => $clientes);
        if (isset($_SESSION['usuario'])) {
            array_push($variables, array("usuario" => $_SESSION['usuario']));
        }
        echo $twig->render('sistema/pedido/registrar.html.twig', $variables);
        break;
    default :
        break;
}
?>
