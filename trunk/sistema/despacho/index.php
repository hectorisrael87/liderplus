<?php
include '../../includes/constants.php';

$usuario = new usuario();
$usuario->confirmar_miembro();
$despacho = new despacho();
$session = $_SESSION;
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";
$query_paginado = "select ft.id,ft.fecha, f.numero,t.nombre from factura_empresa_transporte ft 
    join factura f on ft.factura_id = f.id
    join empresa_transporte t on t.id = ft.empresa_transporte_id";

switch ($accion) {
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar despacho">
        $data = $_POST;
        unset($data['crear']);
        
        if ($data['id']!='') {
            $exito = $despacho->actualizar($data['id'], $data);
        } else {
            unset($data['id']);
            $exito = $despacho->insertar($data);
            if ($exito['suceed']) {
                
                $pedido = new pedido();
                $factura = new factura();
                $result = $factura->ver($_POST['factura_id']);
                $id = $result['data'][0]['pedido_id'];
                $result = $pedido->actualizar($id, Array("estatus_pedido_id"=>5));
            }
        }
        if ($exito['suceed']) {
            $exito['mensaje'] = "La información fue guardada con éxito";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar($query_paginado);
        $registros = $paginacion->registros;
        echo $twig->render('sistema/despacho/paginacion.html.twig', array(
            "esSeguimiento"=>false,
            "session" => $session,
            "accion" => "Guardar",
            "registros" => $registros,
            "resultado" => $exito));
        break; // </editor-fold>

    case "editar":
    case "modificar":
    case "consultar":
    case "ver":
        $dato = $despacho->ver($_GET['id']);
        $empresa = new transporte();
        $empresas = $empresa->listar();
        $pedido = new pedido();
        $facturas = $pedido->listar_pedido_despacho();
        echo $twig->render('sistema/despacho/formulario.html.twig', array(
            "session"  => $session,
            "despacho" => $dato['data'][0],
            "empresas" => $empresas['data'],
            "facturas" => $facturas['data'],
            "modoLectura" => ($accion=='consultar' || $accion=='ver'),
            "accion" => $accion,
            "esSeguimiento"=>false));
        break;
     case "borrar":
        // <editor-fold defaultstate="collapsed" desc="borrar">
        $exito = $despacho->borrar($_GET['id']);
        if ($exito['suceed']) {
            $tipoMensaje = "success";
            $mensaje = "Registro borrado con éxito";
        } else {
            $tipoMensaje = "alert";
            $mensaje = "No se pudo borrar el registro, por favor intente de nuevo o comuniquese con el administrador del sistema";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar($query_paginado);
        $registros = $paginacion->registros;
        echo $twig->render('sistema/despacho/paginacion.html.twig', array(
            "session" => $session,
            "registros" => $registros,
            "accion" => "Listar",
            "tipoMensaje" => $tipoMensaje,
            "mensaje" => $mensaje));
        break;
    // </editor-fold>
    case "crear":
        $empresa = new transporte();
        $empresas = $empresa->listar();
        $pedido = new pedido();
        $facturas = $pedido->listar_pedido_chequeado();
        
        echo $twig->render('sistema/despacho/formulario.html.twig', array(
            "session" => $session,
            "accion" => "crear",
            "empresas" => $empresas['data'],
            "facturas" => $facturas['data'],
            "modoLectura" => false
        ));
        break;
    case "seguimientodetalle":
    case "actualizarseguimiento":
        if ($accion == "actualizarseguimiento") {
            $data = $_POST;
            unset($data['actualizarseguimiento']);
            unset($data['id']);
            $res = $despacho->actualizar_seguimiento($data);
        }
        $dato = $despacho->ver($_GET['id']);
        $empresa = new transporte();
        $empresas = $empresa->listar();
        $pedido = new pedido();
        $facturas = $pedido->listar_pedido_despacho();
        $seguimiento = $pedido->listar_seguimiento_pedido($_GET['id']);
        $prox_estatus = $despacho->obtener_proximo_estatus_despacho($_GET['id']);
        echo $twig->render('sistema/despacho/formulario.html.twig', array(
            "esSeguimiento" => true,
            "session" => $session,
            "despacho" => $dato['data'][0],
            "empresas" => $empresas['data'],
            "facturas" => $facturas['data'],
            "modoLectura" => true,
            "accion" => "Seguimiento",
            "seguimiento" => $seguimiento['data'],
            "proximo_estatus" => $prox_estatus['data']));
        break;
    
    case "listar":
    case "seguimiento":
    default:
        $titulo = ($accion=='listar') ? "Listar": "Seguimiento";
        $paginacion = new paginacion();
        $paginacion->paginar($query_paginado);
        echo $twig->render('sistema/despacho/paginacion.html.twig', array(
            "esSeguimiento" => ($accion == 'seguimiento'),
            "session" => $session,
            "accion" => $titulo,
            "registros" => $paginacion->registros,
            "paginacion" => $paginacion->mostrar_paginado_lista()));
        break;
    
}
?>
