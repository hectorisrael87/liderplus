<?php

/**
 * Description of producto
 *
 * @author Anyul Rivas
 */
class producto extends db implements crud {

    public function actualizar($id, $data) {
        return $this->update("producto", $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete("producto", array("id" => $id));
    }

    public function insertar($data) {
        return $this->insert("producto", $data);
    }

    public function listar() {
        return $this->select("*", "producto");
    }

    public function ver($id) {
        return $this->dame_query("select * from producto where id=$id");
    }
}
?>
