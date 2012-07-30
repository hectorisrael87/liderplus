<?php



// <editor-fold defaultstate="collapsed" desc="init">

include '../../includes/constants.php';

$pedido = new pedido();

$cliente = new cliente();

$usuario = new usuario();

$producto = new producto();

$usuario->confirmar_miembro();

$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="query">

$queryPedidos = "select pedido.id, pedido.numero, pedido.fecha,  

        CONCAT(cliente.nombres,' ', cliente.apellidos) cliente, 

        estatus_pedido.descripcion estatus_pedido,

        COUNT(pedido_detalle.id) productos,

        (select fase.nombre 

            from fase 

            inner join pedido_fase on pedido_fase.fase_id = fase.id

            where pedido_fase.pedido_id = pedido.id  order by pedido_fase.id desc limit 1) fase

        from pedido 

        inner join pedido_detalle on pedido.id = pedido_detalle.pedido_id

        inner join cliente on pedido.cliente_id = cliente.id 

        inner join estatus_pedido on pedido.estatus_pedido_id = estatus_pedido.id

        group by pedido.id";

// </editor-fold>



switch ($accion) {

    case "guardar":

        // <editor-fold defaultstate="collapsed" desc="guardar">

        $data = $_POST;

        unset($data['crear'], $data['modificar'], $data['editar'], $data['valor']);

        if (isset($_POST['crear']) && $_POST['crear'] == "Procesar Pedido") {

            $exito['suceed'] = $pedido->procesar($data);

        } elseif ($_POST['crear'] && $_POST['crear'] == "Modificar Pedido") {

            $data = array("cliente_id" => $_POST['cliente_id'], "numero" => $_POST['numero']);

            $exito = $pedido->actualizar($_POST['id'], $data);

        } else {

            unset($data['id']);

            $exito = $pedido->insertar($data);

        }

        $pag = new paginacion();

        $pag->paginar($queryPedidos);

        if ($exito['suceed']) {

            $exito['mensaje'] = "pedido procesado con exito";

        } else {

            $exito['mensaje'] = "Ha ocurrido un error al procesar el pedido";

        }

        echo $twig->render('sistema/pedido/paginacion.html.twig', array(

            "registros" => $pag->registros,

            "session" => $_SESSION,

            "resultado" => $exito,

            "mensaje" => $exito['mensaje'],

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

            "session" => $_SESSION,

            "pedido" => $dato['data'][0],

            "productos" => $pedidos['data'],

            'accion' => 'ver',

            "modoLectura" => true));

        break;

    // </editor-fold>

    case "editar":

    case "modificar":

    case "procesar":

        // <editor-fold defaultstate="collapsed" desc="modificar">

        $clientes = $cliente->listar();

        $dato = $pedido->ver($_GET['id']);

        $productos = $producto->listar();

        $productosPedido = $pedido->ver_productos_pedido($_GET['id']);

        echo $twig->render('sistema/pedido/registrar.html.twig', array(

            "agregarProducto" => false,

            "session" => $_SESSION,

            "clientes" => $clientes,

            "pedido" => $dato['data'][0],

            "productos" => $productos,

            "productosPedido" => $productosPedido['data'],

            "status_pedido_detalles" => $pedido->listar_status_pedido_detalle(),

            'accion' => $accion,

            "modoLectura" => false

        ));

        break; // </editor-fold>

    case "crear":

    case "registrar":

        // <editor-fold defaultstate="collapsed" desc="crear">

        $productos = $producto->listar();

        $clientes = $cliente->listar();

        $variables = array(

            "agregarProducto" => true,

            "session" => $_SESSION,

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

            "session" => $_SESSION,

            "accion" => "listar"));

        break;

    // </editor-fold>

}

?>

