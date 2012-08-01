<?php

/**
 * Description of producto
 *
 * @author Anyul Rivas
 */
class producto extends db implements crud {

    public function actualizar($id, $data) {
        $data['precio'] = Misc::format_mysql_number($data['precio']);
        return $this->update("producto", $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete("producto", array("id" => $id));
    }

    public function insertar($data) {
        $data['precio'] = Misc::format_mysql_number($data['precio']);
        return $this->insert("producto", $data);
    }

    public function listar() {
        return $this->select("*", "producto");
    }

    public function ver($id) {
        $producto = $this->dame_query("select * from producto where id=$id");
        if (sizeof($producto['data'] > 0)) {
            $producto['data'][0]['precio'] = Misc::number_format($producto['data'][0]['precio']);
        }
        return $producto;
    }

}

?>
