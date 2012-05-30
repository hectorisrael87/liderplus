<?php

include '../../includes/constants.php';
$pedido = new pedido();
$cliente = new cliente();
$usuario = new usuario();
$producto = new producto();
$usuario->confirmar_miembro();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";
// <editor-fold defaultstate="collapsed" desc="query">
$queryPedidos = "select pedido.id, pedido.numero, pedido.fecha,  
        CONCAT(cliente.nombres,' ',cliente.apellidos) cliente, 
        estatus_pedido.descripcion estatus_pedido,
        COUNT(pedido_detalle.id) productos
        from pedido 
        inner join pedido_detalle on pedido.id = pedido_detalle.pedido_id
        inner join cliente on cliente_id = cliente.id 
        inner join estatus_pedido on pedido.estatus_pedido_id = estatus_pedido.id
        group by pedido.id";
// </editor-fold>

switch ($accion) {
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar">
        $data = $_POST;
        unset($data['crear'], $data['modificar'], $data['editar'], $data['valor']);
        $exito = $pedido->insertar($data);
        $pag = new paginacion();
        $pag->paginar($queryPedidos);
        if ($exito['suceed']) {
            $exito['mensaje'] = "pedido procesado con exito";
        }
        echo $twig->render('sistema/pedido/paginacion.html.twig', array(
            "registros" => $pag->registros,
            "resultado" => $exito,
            "accion" => "guardar"));
        break;
    // </editor-fold>
    case "consultar":
    case "ver":
        // <editor-fold defaultstate="collapsed" desc="ver">
        $clientes = $cliente->listar();
        $dato = $pedido->ver($_GET['id']);
        $pedidos = $pedido->ver_productos_pedido($_GET['id']);
        echo $twig->render('sistema/pedido/formulario.html.twig', array(
            "pedido" => $dato['data'][0],
            "productos" => $pedidos['data'],
            'accion' => 'ver',
            "modoLectura" => true));
        break;
    // </editor-fold>
    case "editar":
    case "modificar":
// <editor-fold defaultstate="collapsed" desc="modificar">
        $clientes = $cliente->listar();
        $dato = $pedido->ver($_GET['id']);
        $productos = $producto->listar();
        $productosPedido = $pedido->ver_productos_pedido($_GET['id']);
        echo $twig->render('sistema/pedido/registrar.html.twig', array(
            "clientes" => $clientes,
            "pedido" => $dato['data'][0],
            "productos" => $productos,
            "productosPedido" => $productosPedido['data'],
            'accion' => 'modificar',
            "modoLectura" => false
        ));
        break; // </editor-fold>

    case "crear":
    case "registrar":
        // <editor-fold defaultstate="collapsed" desc="crear">
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
    // </editor-fold>
    case "listar":
    default :
        // <editor-fold defaultstate="collapsed" desc="listar">
        $pag = new paginacion();
        $pag->paginar($queryPedidos);
        echo $twig->render('sistema/pedido/paginacion.html.twig', array(
            "registros" => $pag->registros,
            "accion" => "listar"));
        break;
    // </editor-fold>
}
?>
