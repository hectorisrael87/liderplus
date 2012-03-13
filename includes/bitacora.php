<?php

/**
 * Description of bitacora
 *
 * @author Anyul Rivas
 */
class bitacora extends db implements crud {

    const tabla = 'bitacora';

    public function actualizar($id, $data) {
        return $this->update(self::tabla, $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete(self::tabla, array("id" => $id));
    }

    public function insertar($data) {
        return $this->insert(self::tabla, $data);
    }

    public function log($id, $modulo) {
        $data = array("usuario_id" => $id, "modulo" => $modulo);
        return $this->insertar($data);
    }

    public function ver($id) {
        return $this->select("*", self::tabla, array('id' => $id));
    }

    public function listar() {
        return $this->dame_query("select id, fecha from " . self::tabla);
    }

}

?>
