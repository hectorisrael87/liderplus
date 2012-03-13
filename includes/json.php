<?php

require_once 'constants.php';
$db = new db();
$result = array();

switch ($_GET['accion']) {
    case 'query':
        $result = $db->dame_query("select * from bitacora");
        break;
    default:
        $result = array("suceed" => false, "error" => "No ha seleccionado ninguna acción");
        break;
}
echo json_encode($result);
?>
