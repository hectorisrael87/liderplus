<?php

/**
 * Description of factura
 *
 * @author emessia
 */
class transporte extends db implements crud {
    const tabla = 'empresa_transporte';
public function actualizar($id, $data) {
        return $this->update(self::tabla, $data,array("id" => $id));
    }
public function borrar($id) {
        return $this->delete(self::tabla, array("id" => $id));
    }
public function insertar($data) {
        return $this->insert(self::tabla, $data);
    }
public function listar() {
        return $this->select("*", self::tabla);
    }
public function ver($id) {
        return $this->select("*", self::tabla, array("id"=>$id));
    }
}
?>
