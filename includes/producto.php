<?php

/**
 * Description of producto
 *
 * @author Anyul Rivas
 */
class producto extends db implements crud {

    public function actualizar($id, $data) {
        
    }

    public function borrar($id) {
        
    }

    public function insertar($data) {
        
    }

    public function listar() {
        return $this->select("*", "producto");
    }

    public function ver($id) {
        
    }

}

?>
