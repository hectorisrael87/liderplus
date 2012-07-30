<?php

/**
 * Description of cliente
 *
 * @author Anyul Rivas
 */
class cliente extends db implements crud {

    const tabla = "cliente";

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
            where id in (select cliente_id from pedido where estatus_pedido_id=" . STATUS_PEDIDO_PROCESADO . ")
            order by c.nombres";
        return $this->dame_query($query);
    }

    public function verificar_cliente($cliente) {
        $cliente = $this->dame_query("select cliente.id, 
            status_cliente.nombre status_cliente,
            count(estatus_pedido_id) pedidos, estatus_pedido.descripcion status,
            (select fase.nombre 
                from fase 
                    inner join pedido_fase on pedido_fase.fase_id = fase.id
                    where pedido_fase.pedido_id = pedido.id  order by pedido_fase.id desc limit 1) fase
            from pedido
            inner join ciente on pedido.cliente_id = cliente.id
            inner join estatus_pedido on estatus_pedido_id = estatus_pedido.id
            inner join status_cliente on status_cliente.id = cliente.status_cliente_id
            group by cliente.id, status, fase, status_cliente
            where cliente.id = $cliente");
    }
}

?>
