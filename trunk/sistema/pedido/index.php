<?php

include '../../includes/constants.php';
$pedido = new pedido();
$usuario = new usuario();
$producto = new producto();
$usuario->confirmar_miembro();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

switch ($accion) {
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar">
        $data = $_POST;
        unset($data['crear'], $data['modificar'], $data['editar'], $data['valor']);
        $exito = $pedido->insertar($data);
        if ($exito['suceed']) {
            $exito['mensaje'] = "pedido procesado con exito";
        }
        echo $twig->render('sistema/pedido/paginacion.html.twig', array(
            "resultado" => $exito,
            "accion" => "guardar"));
        break; 
        // </editor-fold>
    case "consultar":
    case "ver":
        // <editor-fold defaultstate="collapsed" desc="ver">
        $pedido = new pedido();
        $dato = $pedido->ver($_GET['id']);
        $pedidos = $pedido->ver_pedidos_pedido($_GET['id']);
        echo $twig->render('sistema/pedido/formulario.html.twig', array(
            "pedido" => $dato['data'][0],
            "pedidos" => $pedidos['data'],
            'accion' => 'ver',
            "modoLectura" => true));
        break;
    // </editor-fold>
    case "crear":
    case "registrar":
        // <editor-fold defaultstate="collapsed" desc="crear">
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
    // </editor-fold>
    case "listar":
    default :
        // <editor-fold defaultstate="collapsed" desc="listar">
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
    // </editor-fold>
}
?>
