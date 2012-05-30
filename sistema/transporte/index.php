<?php
include '../../includes/constants.php';

$usuario = new usuario();
$usuario->confirmar_miembro();
$transporte = new transporte();
$session = $_SESSION;
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";
$query_paginado = "select * from empresa_transporte";
switch ($accion) {
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar empresa">
        $data = $_POST;
        unset($data['crear'], $data['atras'],$data['editar'],$data['consultar']);
        
        if ($data['id']!='') {
            $exito = $transporte->actualizar($data['id'], $data);
        } else {
            unset($data['id']);
            $exito = $transporte->insertar($data);
        }
        if ($exito['suceed']) {
            $exito['mensaje'] = "La información fue guardada con éxito";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar($query_paginado);
        $registros = $paginacion->registros;
        echo $twig->render('sistema/transporte/paginacion.html.twig', array(
            "session" => $session,
            "accion" => "Guardar",
            "registros" => $registros,
            "resultado" => $exito));
        break; // </editor-fold>

    case "editar":
    case "modificar":
    case "consultar":
    case "ver":
        $dato = $transporte->ver($_GET['id']);
        echo $twig->render('sistema/transporte/formulario.html.twig', array(
            "session" => $session,
            "transporte" => $dato['data'][0],
            "modoLectura" => ($accion=='consultar' || $accion=='ver'),
            "accion" => $accion));
        break;
     case "borrar":
        // <editor-fold defaultstate="collapsed" desc="borrar">
        $exito = $transporte->borrar($_GET['id']);
        if ($exito['suceed']) {
            $tipoMensaje = "success";
            $mensaje = "Empresa borrada con éxito";
        } else {
            $tipoMensaje = "alert";
            $mensaje = "No se pudo borrar el registro, por favor intente de nuevo o comuniquese con el administrador del sistema";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar($query_paginado);
        $registros = $paginacion->registros;
        echo $twig->render('sistema/transporte/paginacion.html.twig', array(
            "session" => $session,
            "registros" => $registros,
            "accion" => "Listar",
            "tipoMensaje" => $tipoMensaje,
            "mensaje" => $mensaje));
        break;
    // </editor-fold>
    case "crear":
        echo $twig->render('sistema/transporte/formulario.html.twig', array(
            "session" => $session,
            "accion" => "crear",
            "modoLectura" => false
        ));
        break;
    case "listar":
    default:
        $paginacion = new paginacion();
        $paginacion->paginar($query_paginado);
        echo $twig->render('sistema/transporte/paginacion.html.twig', array(
            "session" => $session,
            "accion" => "Listar",
            "registros" => $paginacion->registros,
            "paginacion" => $paginacion->mostrar_paginado_lista()));
        break;
}
?>
