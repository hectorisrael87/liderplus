<?php 

include '../../includes/constants.php';
$usuario = new usuario();
$producto = new producto();
$usuario->confirmar_miembro();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

switch ($accion) {
    case 'listarPedidosPorFacturarPorCliente':
        $pedidos = new pedido();
        $query = "select * from pedido where cliente_id=".$_GET['id']. 
            " and estatus_pedido_id=3";
        $result = $pedidos->dame_query($query);
        echo json_encode($result);
        break;
    
    case 'listarDetalleFactura':
        $pedidos = new pedido();
        $result = $pedidos->ver_productos_pedido_por_facturar($_GET['id']);
        echo json_encode($result);
        break;
    
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar">
        $facturas = new factura();
        $data = $_POST;
        unset($data['crear'], $data['modificar'], $data['editar'], $data['valor']);
        $exito = $facturas->insertar($data);
        if ($exito['suceed']) {
            $exito['mensaje'] = "factura procesado con exito";
        }
        echo $twig->render('sistema/factura/paginacion.html.twig', array(
            "resultado" => $exito,
            "accion" => "guardar"));
        break; 
        // </editor-fold>
    case "consultar":
    case "ver":
        // <editor-fold defaultstate="collapsed" desc="ver">
//        $pedido = new pedido();
//        $dato = $pedido->ver($_GET['id']);
//        $pedidos = $pedido->ver_pedidos_pedido($_GET['id']);
//        echo $twig->render('sistema/pedido/formulario.html.twig', array(
//            "pedido" => $dato['data'][0],
//            "pedidos" => $pedidos['data'],
//            'accion' => 'ver',
//            "modoLectura" => true));
        break;
    // </editor-fold>
    case "crear":
    case "registrar":
        // <editor-fold defaultstate="collapsed" desc="crear">
        $cliente = new cliente();
        $productos = $producto->listar();
        $clientes = $cliente->listarConPedidoProcesado();
        $variables = array(
            "accion" => "Registrar",
            "productos" => $productos,
            "clientes" => $clientes);
        if (isset($_SESSION['usuario'])) {
            array_push($variables, array("usuario" => $_SESSION['usuario']));
        }
        echo $twig->render('sistema/factura/registrar.html.twig', $variables);
        break;
    // </editor-fold>
    case "listar":
    default :
        // <editor-fold defaultstate="collapsed" desc="listar">
        $pag = new paginacion();
        // <editor-fold defaultstate="collapsed" desc="query">
        $pag->paginar("select f.id, f.numero, f.fecha, f.monto_total, CONCAT(c.nombres,' ',c.apellidos) cliente, ef.descripcion
            from factura f
            join pedido p on p.id = f.pedido_id 
            join cliente c on c.id = p.cliente_id
            join estatus_factura ef on ef.id = f.estatus_factura_id");
// </editor-fold>
        echo $twig->render('sistema/factura/paginacion.html.twig', array(
            "registros" => $pag->registros,
            "accion" => "listar"));
        break;
    // </editor-fold>
}
?>
