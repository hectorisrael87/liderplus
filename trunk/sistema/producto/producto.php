<?php

include '../../includes/constants.php';
$producto = new producto();
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

switch ($accion) {
    case "guardar":
        // <editor-fold defaultstate="collapsed" desc="guardar">
        $data = $_POST;
        unset($data['crear'], $data['modificar'], $data['editar']);
        if ($_POST['id'] == '') {
            unset($data['id']);
            $exito = $producto->insertar($data);
            if ($exito['suceed']) {
                $exito['mensaje'] = "Producto creado con exito";
            }
        } else {
            $exito = $producto->actualizar($data['id'], $data);
            if ($exito['suceed']) {
                $exito['mensaje'] = "Producto modificado con exito";
            }
        }
        echo $twig->render('sistema/producto/formulario.html.twig', array(
            "resultado" => $exito,
            "accion" => "guardar"));
        break;
    // </editor-fold>
    case "crear":
        // <editor-fold defaultstate="collapsed" desc="crear">
        echo $twig->render('sistema/producto/formulario.html.twig', array(
            "accion" => "crear",
            "modoLectura" => false
        ));
        break;
    // </editor-fold>
    case "consultar":
    case "ver":
        // <editor-fold defaultstate="collapsed" desc="ver">
        $dato = $producto->ver($_GET['id']);
        echo $twig->render('sistema/producto/formulario.html.twig', array(
            "producto" => $dato['data'][0],
            "modoLectura" => true,
            "accion" => $accion));
        break;
    // </editor-fold>
    case "editar":
    case "modificar":
        // <editor-fold defaultstate="collapsed" desc="modificar">
        $dato = $producto->ver($_GET['id']);
        echo $twig->render('sistema/producto/formulario.html.twig', array(
            "producto" => $dato['data'][0],
            "modoLectura" => false,
            "accion" => $accion));
        break;
    // </editor-fold>
    case "borrar":
        // <editor-fold defaultstate="collapsed" desc="borrar">
        $exito = $producto->borrar($_GET['id']);
        if ($exito['suceed']) {
            $tipoMensaje = "success";
            $mensaje = "Producto borrado con exito";
        } else {
            $tipoMensaje = "alert";
            $mensaje = "No se pudo borrar el registro, por favor intente de nuevo o comuniquese con el administrador del sistema";
        }
        $paginacion = new paginacion(true);
        $paginacion->paginar("select * from producto");
        $registros = $paginacion->registros;
        echo $twig->render('sistema/producto/paginacion.html.twig', array(
            "registros" => $registros,
            "accion" => "Listar",
            "tipoMensaje" => $tipoMensaje,
            "mensaje" => $mensaje));
        break;
    // </editor-fold>
    case "listar":
        // <editor-fold defaultstate="collapsed" desc="lisstado">
        $paginacion = new paginacion();
        $paginacion->paginar("select * from producto");
        echo $twig->render('sistema/producto/paginacion.html.twig', array(
            "accion" => "Listar",
            "registros" => $paginacion->registros,
            "paginacion" => $paginacion->mostrar_paginado_lista()));
    default:
        break;
    //</editor-fold>
}
?>