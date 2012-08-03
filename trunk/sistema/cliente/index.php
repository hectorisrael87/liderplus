<?php

include '../../includes/constants.php';
$cliente = new cliente();
$usuario = new usuario();
$funcionalidad = new funcionalidad;
$usuario->confirmar_miembro();
$menu = $funcionalidad->funcionalidad_grupo($_SESSION['usuario']['grupo_id']);
// <editor-fold defaultstate="collapsed" desc="query listado">
$query_paginado = "select cliente.*, 
            tipo_documento.nombre tipo_documento
            from cliente 
            inner join tipo_documento on cliente.tipo_documento_id = tipo_documento.id ";
// </editor-fold>

$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";
$session = $_SESSION;
switch ($accion) {
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar">
        $data = $_POST;
        unset($data['crear'], $data['modificar'], $data['editar']);
        if ($_POST['id'] == '') {
            unset($data['id']);
            $exito = $cliente->insertar($data);
            if ($exito['suceed']) {
                $exito['mensaje'] = "cliente creado con exito";
            }
        } else {
            $exito = $cliente->actualizar($data['id'], $data);
            if ($exito['suceed']) {
                $exito['mensaje'] = "cliente modificado con exito";
            }
        }
        echo $twig->render('sistema/cliente/formulario.html.twig', array(
            "menu" => $menu['data'],
            "session" => $session,
            "resultado" => $exito,
            "accion" => "guardar"));
        break;
    // </editor-fold>
    case "crear":
        $tipo_documento = new tipodocumento();
        $tipos_documento = $tipo_documento->listar();
        $status_cliente = new statuscliente();
        $lista_status_cliente = $status_cliente->listar();
        // <editor-fold defaultstate="collapsed" desc="crear">
        echo $twig->render('sistema/cliente/formulario.html.twig', array(
            "menu" => $menu['data'],
            "session" => $session,
            "accion" => "crear",
            "tipoDocumentos" => $tipos_documento['data'],
            "statusCliente" => $lista_status_cliente['data'],
            "modoLectura" => false
        ));
        break;
    // </editor-fold>
    case "consultar":
    case "ver":
        $tipo_documento = new tipodocumento();
        $tipos_documento = $tipo_documento->listar();
        $status_cliente = new statuscliente();
        $lista_status_cliente = $status_cliente->listar();
        // <editor-fold defaultstate="collapsed" desc="ver">
        $dato = $cliente->ver($_GET['id']);
        echo $twig->render('sistema/cliente/formulario.html.twig', array(
            "menu" => $menu['data'],
            "session" => $session,
            "cliente" => $dato['data'][0],
            "tipoDocumentos" => $tipos_documento['data'],
            "statusCliente" => $lista_status_cliente['data'],
            "modoLectura" => true,
            "accion" => $accion));
        break;
    // </editor-fold>
    case "editar":
    case "modificar":
        // <editor-fold defaultstate="collapsed" desc="modificar">
        $tipo_documento = new tipodocumento();
        $tipos_documento = $tipo_documento->listar();
        $status_cliente = new statuscliente();
        $lista_status_cliente = $status_cliente->listar();
        $dato = $cliente->ver($_GET['id']);
        echo $twig->render('sistema/cliente/formulario.html.twig', array(
            "menu" => $menu['data'],
            "session" => $session,
            "tipoDocumentos"=>$tipos_documento['data'],
            "statusCliente" => $lista_status_cliente['data'],
            "cliente" => $dato['data'][0],
            "modoLectura" => false,
            "accion" => $accion));
        break;
    // </editor-fold>
    case "borrar":
        // <editor-fold defaultstate="collapsed" desc="borrar">
        $exito = $cliente->borrar($_GET['id']);
        if ($exito['suceed']) {
            $tipoMensaje = "success";
            $mensaje = "cliente borrado con exito";
        } else {
            $tipoMensaje = "alert";
            $mensaje = "No se pudo borrar el registro, por favor intente de nuevo o comuniquese con el administrador del sistema";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar($query_paginado);
        $registros = $paginacion->registros;
        echo $twig->render('sistema/cliente/paginacion.html.twig', array(
            "menu" => $menu['data'],
            "session" => $session,
            "registros" => $registros,
            "accion" => "Listar",
            "tipoMensaje" => $tipoMensaje,
            "mensaje" => $mensaje));
        break;
    // </editor-fold>
    case "listar":
    default:
        // <editor-fold defaultstate="collapsed" desc="listado">
        $paginacion = new paginacion();
        $paginacion->paginar($query_paginado);
        echo $twig->render('sistema/cliente/paginacion.html.twig', array(
            "menu" => $menu['data'],
            "session" => $session,
            "accion" => "Listar",
            "registros" => $paginacion->registros,
            "paginacion" => $paginacion->mostrar_paginado_lista()));
        break;
    //</editor-fold>
}
?>