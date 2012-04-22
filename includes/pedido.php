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
        return $this->dame_query("select pedido.id, pedido.numero, pedido.fecha,  
CONCAT(cliente.nombres,' ',cliente.apellidos) cliente, 
estatus_pedido.descripcion estatus_pedido,
 count(pedido_detalle.id) productos
from pedido 
inner join pedido_detalle on pedido.id = pedido_detalle.pedido_id
inner join cliente on cliente_id = cliente.id 
inner join estatus_pedido on pedido.estatus_pedido_id = estatus_pedido.id");
    }

    public function ver($id) {
        return $this->dame_query("select pedido.id, pedido.numero, pedido.fecha,  
CONCAT(cliente.nombres,' ',cliente.apellidos) cliente, 
estatus_pedido.descripcion estatus_pedido,
 count(pedido_detalle.id) productos
from pedido 
inner join pedido_detalle on pedido.id = pedido_detalle.pedido_id
inner join cliente on cliente_id = cliente.id 
inner join estatus_pedido on pedido.estatus_pedido_id = estatus_pedido.id where pedido.id = $id");
    }
    public function ver_productos_pedido($id){
        return $this->dame_query("select 
                producto.codigo, producto.descripcion, 
                pedido_detalle.cantidad_pedido, pedido_detalle.cantidad_despacho, pedido_detalle.precio,
                estatus_pedido.descripcion estatus_pedido
                from pedido_detalle
                inner join producto on pedido_detalle.producto_id = producto.id
                inner join estatus_pedido on pedido_detalle.estatus_pedido_id = estatus_pedido.id
                where pedido_id = $id ");
    }
}

?>
