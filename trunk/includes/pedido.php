<?php

/**
 * Description of pedido
 *
 * @author Anyul Rivas
 */
class pedido extends db implements crud {

    public function actualizar($id, $data) {
        return $this->update("pedido", $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete("pedido", array("id" => $id));
    }

    public function insertar($data) {
        return $this->insert("pedido", $data);
    }

    public function listar() {
        return $this->dame_query("select * from pedido");
    }

    public function ver($id) {
        return $this->dame_query("select * from pedido where id = $id");
    }

}

?>
