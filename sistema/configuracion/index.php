<?php

// <editor-fold defaultstate="collapsed" desc="init">
include '../../includes/constants.php';
$usuario = new usuario();
$usuario->confirmar_miembro();
$funcionalidad = new funcionalidad;
$menu = $funcionalidad->funcionalidad_grupo($_SESSION['usuario']['grupo_id']);
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

// </editor-fold>
function mostrar_fase($twig, $menu, $accion, $resultado = NULL) {
    $fase = new fase();
    $fases = $fase->listar();
    echo $twig->render("sistema/configuracion/fase.html.twig", array(
        "menu" => $menu['data'],
        "session" => $_SESSION,
        "modoLectura" => false,
        "accion" => $accion,
        "resultado" => $resultado,
        "fases" => $fases['data']
    ));
}

function mostrar_funcionalidad($twig, $menu, $accion, $resultado = NULL) {
    $id = $_SESSION['usuario']['grupo_id'];
    if (isset($_GET['id'])) {
        echo "<h1>hay un id</h1>";
        $id = $_GET['id'];
    }
    $funcionalidad = new funcionalidad();
    $funcionalidades = $funcionalidad->listar();
    $funcionalidad_grupo = $funcionalidad->funcionalidad_grupo($id);
    echo $twig->render("sistema/configuracion/funcionalidad.html.twig", array(
        "menu" => $menu['data'],
        "session" => $_SESSION,
        "grupo_id" => $id,
        "funcionalidades" => $funcionalidades['data'],
        "mi_funcionalidad" => $funcionalidad_grupo['data'],
        "modoLectura" => false,
        "rol" => $_SESSION['usuario']['grupo'],
        "resultado" => $resultado,
        "accion" => $accion,
    ));
}

switch ($accion) {
    case "guardar":
        switch ($_POST['flag']) {
            // <editor-fold defaultstate="collapsed" desc="guardar">
            case "fase":
                $fase = new fase();
                $resultado = $fase->configurar_fases($_POST);
                if ($resultado['suceed']) {
                    $resultado['mensaje'] = 'Operacion realizada exitosamente.';
                }
                mostrar_fase($twig, $menu, $accion, $resultado);
                break;
            case "funcionalidad":
                $funcionalidad = new funcionalidad();
                $resultado = $funcionalidad->guardar_funcionalidad($_POST);
                if ($resultado['suceed']) {
                    $resultado['mensaje'] = 'Operacion realizada exitosamente.';
                }
                mostrar_funcionalidad($twig, $menu, $accion, $resultado);
                break;
            default:
                break;
        }
        // </editor-fold>
        break;
    case "funcionalidad":
        // <editor-fold defaultstate="collapsed" desc="funcionalidad">
        mostrar_funcionalidad($twig, $menu, $accion);
        break;
    // </editor-fold>
    case 'fase':
        // <editor-fold defaultstate="collapsed" desc="fases">
        mostrar_fase($twig, $menu, $accion);
        break;
    // </editor-fold>
    case "listar":
    default :
        // <editor-fold defaultstate="collapsed" desc="listar">
        echo $twig->render('sistema/configuracion/index.html.twig', array(
            "menu" => $menu['data'],
            "session" => $_SESSION
        ));
        // </editor-fold>
        break;
}
?>
