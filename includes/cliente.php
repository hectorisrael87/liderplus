<?php

/**
 * Description of cliente
 *
 * @author Anyul Rivas
 */
class cliente extends db implements crud{
    //put your code here
    public function actualizar($id, $data) {
        
    }
    public function borrar($id) {
        
    }
    public function insertar($data) {
        
    }
    public function listar() {
        return $this->select("*", "cliente");
    }
    public function ver($id) {
        
    }
}

?>
