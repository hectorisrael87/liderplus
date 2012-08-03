<?php

/**
 * Description of fase
 *
 * @author Anyul Rivas
 */
class fase extends db implements crud {

    const tabla = "fase";

    public function actualizar($id, $data) {
        return $this->update(self::tabla, $data, array("id" => $id));
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
        return $this->select("*", self::tabla, array("id" => $id));
    }

    public function configurar_fases($data) {
        for ($i = 0; $i < sizeof($data['id']); $i++) {
            $exito = $this->update(self::tabla, array(
                "duracion" => $data['duracion'][$i]), array(
                "id" => $data['id'][$i]));
            if (!$exito['suceed'])
                break;
        }
        return $exito;
    }

}

?>
