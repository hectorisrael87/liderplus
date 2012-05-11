<?php

/**
 * Description of cliente
 *
 * @author Anyul Rivas
 */
class cliente extends db implements crud {

    const tabla = "cliente";
    const pedido_procesado = 3;
    
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
        return $this->dame_query("
            select " . self::tabla . ".*,
            status_cliente.nombre status,
            tipo_documento.nombre tipo_documento
                from cliente
                    inner join status_cliente on cliente.status_cliente_id = status_cliente.id
                    inner join tipo_documento on cliente.tipo_documento_id = tipo_documento.id 
                where cliente.id = $id");
    }

    public function listarConPedidoProcesado() {
        $query = "select c.*
            from cliente c
            where id in (select cliente_id from pedido where estatus_pedido_id=".self::pedido_procesado.")
            order by c.nombres";
        
        return $this->dame_query($query);
    }
}

?>
