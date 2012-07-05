<?php

/**
 * Despacho
 *
 * @author emessia
 */
class despacho extends db implements crud {
    const tabla = 'factura_empresa_transporte';
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
public function actualizar_seguimiento($data) {
        return $this->insert("despacho",$data);
}
public function obtener_proximo_estatus_despacho($id) {
        $query = "select *
            from estatus_seguimiento_despacho
            where id not in (select estatus_seguimiento_despacho_id 
            from despacho where factura_empresa_transporte_id = ".$id.")
            order by id
            limit 0,1";
        return $this->dame_query($query);
}
}
?>
