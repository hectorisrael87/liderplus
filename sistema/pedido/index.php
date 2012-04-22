<?php

include '../../includes/constants.php';
$producto = new pedido();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

switch ($accion) {
    case "consultar":
    case "ver":
        $pedido = new pedido();
        $dato = $pedido->ver($_GET['id']);
        $productos = $pedido->ver_productos_pedido($_GET['id']);
        echo $twig->render('sistema/pedido/formulario.html.twig', array(
            "pedido" => $dato['data'][0],
            "productos" => $productos['data'],
            'accion' => 'ver',
            "modoLectura" => true));
        break;
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
    case "listar":
    default :
        $pag = new paginacion();
        // <editor-fold defaultstate="collapsed" desc="query">
        $pag->paginar("select pedido.id, pedido.numero, pedido.fecha,  
        CONCAT(cliente.nombres,' ',cliente.apellidos) cliente, 
        estatus_pedido.descripcion estatus_pedido,
         count(pedido_detalle.id) productos
        from pedido 
        inner join pedido_detalle on pedido.id = pedido_detalle.pedido_id
        inner join cliente on cliente_id = cliente.id 
        inner join estatus_pedido on pedido.estatus_pedido_id = estatus_pedido.id");
// </editor-fold>
        echo $twig->render('sistema/pedido/paginacion.html.twig', array(
            "registros" => $pag->registros,
            "accion" => "listar"));
        break;
}
?>
