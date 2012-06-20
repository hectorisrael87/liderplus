<?php

include '../../includes/constants.php';
$usuario = new usuario();
$usuario->confirmar_miembro();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "index";
$session = $_SESSION;
$id = 0;
if (isset($_GET['id'])) {
    $id = $session['usuario']['id'];
} else {
    $id = $session['usuario']['id'];
}

switch ($accion) {
    case "index":
        // <editor-fold defaultstate="collapsed" desc="index">
        echo $twig->render('index.html.twig', array(
            "session" => $session));
        break; 
    // </editor-fold>
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar">
        $data = $_POST;
        unset($data['crear'], $data['modificar'], $data['editar']);
        if ($_POST['id'] == '') {
            unset($data['id']);
            $exito = $usuario->insertar($data);
            if ($exito['suceed']) {
                $exito['mensaje'] = "usuario creado con exito";
            }
        } else {
            $exito = $usuario->actualizar($data['id'], $data);
            if ($exito['suceed']) {
                $exito['mensaje'] = "usuario modificado con exito";
            }
        }
        echo $twig->render('sistema/usuario/formulario.html.twig', array(
            "session" => $session,
            "resultado" => $exito,
            "accion" => "guardar"));
        break;
    // </editor-fold>
    case "crear":
        // <editor-fold defaultstate="collapsed" desc="crear">
        echo $twig->render('sistema/usuario/formulario.html.twig', array(
            "session" => $session,
            "accion" => "crear",
            "modoLectura" => false
        ));
        break;
    // </editor-fold>
    case "consultar":
    case "ver":
        // <editor-fold defaultstate="collapsed" desc="ver">
        $dato = $usuario->ver($id);
        echo $twig->render('sistema/usuario/formulario.html.twig', array(
            "session" => $session,
            "usuario" => $dato['data'][0],
            "modoLectura" => true,
            "accion" => $accion));
        break;
    // </editor-fold>
    case "editar":
    case "modificar":
        // <editor-fold defaultstate="collapsed" desc="modificar">
        $dato = $usuario->ver($id);
        echo $twig->render('sistema/usuario/formulario.html.twig', array(
            "session" => $session,
            "usuario" => $dato['data'][0],
            "modoLectura" => false,
            "accion" => $accion));
        break;
    // </editor-fold>
    case "borrar":
        // <editor-fold defaultstate="collapsed" desc="borrar">
        $exito = $usuario->borrar($id);
        if ($exito['suceed']) {
            $tipoMensaje = "success";
            $mensaje = "usuario borrado con exito";
        } else {
            $tipoMensaje = "alert";
            $mensaje = "No se pudo borrar el registro, por favor intente de nuevo o comuniquese con el administrador del sistema";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar("select * from usuario");
        $registros = $paginacion->registros;
        echo $twig->render('sistema/usuario/paginacion.html.twig', array(
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
        $paginacion->paginar("select * from usuarios");
        echo $twig->render('sistema/usuario/paginacion.html.twig', array(
            "session" => $session,
            "accion" => "Listar",
            "registros" => $paginacion->registros,
            "paginacion" => $paginacion->mostrar_paginado_lista()));
        break;
    //</editor-fold>
}
?>