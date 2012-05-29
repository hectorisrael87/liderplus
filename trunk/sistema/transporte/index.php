<?php
include '../../includes/constants.php';

$usuario = new usuario();
$usuario->confirmar_miembro();
$session = $_SESSION;
$accion = isset($_GET['accion']) ? $_GET['accion'] : "listar";

switch ($accion) {
    case "crear":
        echo $twig->render('sistema/transporte/formulario.html.twig', array(
            "session" => $session,
            "accion" => "crear",
            "modoLectura" => false
        ));
        break;
    case "listar":
    default:
        $query_paginado = "select * from empresa_transporte";
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
