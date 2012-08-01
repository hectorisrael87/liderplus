<?php

include '../../includes/constants.php';
$usuario = new usuario();
$usuario->confirmar_miembro();

$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";
switch ($accion) {
    case "guardar":
        switch ($_GET['flag']) {
            case "fase":
                //TODO guardar fases
                break;
            case "funcionalidad":
                //TODO guardar funcionalidad 
                break;
            default:
                break;
        }
        break;
    case "funcionalidad":
        $funcionalidad = new funcionalidad();
        $funcionalidades = $funcionalidad->listar();
        $funcionalidad_grupo = $funcionalidad->funcionalidad_grupo($_SESSION['usuario']['grupo_id']);
        echo $twig->render("sistema/configuracion/funcionalidad.html.twig", array(
            "session" => $_SESSION,
            "funcionalidades" => $funcionalidades['data'],
            "mi_funcionalidad" => $funcionalidad_grupo['data'],
            "modoLectura" => true,
            "rol" => $_SESSION['usuario']['grupo'],
            "accion" => $accion,
        ));
        break;
    case 'fases':
    default :
        $fase = new fase();
        $fases = $fase->listar();
        echo $twig->render("sistema/configuracion/fase.html.twig", array(
            "session" => $_SESSION,
            "modoLectura" => true,
            "accion" => $accion,
            "fases" => $fases['data']
        ));
        break;
}
?>
