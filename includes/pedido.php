<?php

/**
 * Description of pedido
 *
 * @author Anyul Rivas
 */
class pedido extends db implements crud {

    const status_pendiente = 1;

    public function actualizar($id, $data) {
        return $this->update("pedido", $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete("pedido", array("id" => $id));
    }

    public function insertar($data) {
        //$this->exec_query("start transaction");

        try {
            $pedido_detalle = array();

            $pedido_detalle['producto_id'] = $data['producto_id'];
            $pedido_detalle['cantidad_pedido'] = $data['cantidad_pedido'];
            $pedido_detalle['precio'] = $data['precio'];
            unset($data['producto_id'], $data['cantidad_pedido'], $data['precio']);
            $data['estatus_pedido_id'] = self::status_pendiente;
            $resultado = $this->insert("pedido", $data);

            if ($resultado['suceed']) {
                for ($i = 0; $i < sizeof($pedido_detalle['producto_id']); $i++) {
                    $result_detalle = $this->insert("pedido_detalle", array(
                        "pedido_id" => $resultado['insert_id'],
                        "producto_id" => $pedido_detalle['producto_id'][$i],
                        "cantidad_pedido" => $pedido_detalle['cantidad_pedido'][$i],
                        "precio" => $pedido_detalle['precio'][$i],
                        "estatus_pedido_id" => self::status_pendiente,
                        "almacen_id" => 1
                            ));
                    if (!$result_detalle['suceed']) {
                        $resultado['suceed'] = $result_detalle['suceed'];
                        $resultado['mensaje'] = "Ha ocurrido un error al procesar el pedido";
                    }
                }
            }
            // $this->exec_query("commit");
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            //$this->exec_query("rollback");
        }
        return $resultado;
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
        pedido.cliente_id,
        CONCAT(cliente.nombres,' ',cliente.apellidos) cliente, 
        estatus_pedido.descripcion estatus_pedido,
        count(pedido_detalle.id) productos
        from pedido 
        inner join pedido_detalle on pedido.id = pedido_detalle.pedido_id
        inner join cliente on cliente_id = cliente.id 
        inner join estatus_pedido on pedido.estatus_pedido_id = estatus_pedido.id where pedido.id = $id group by pedido.id");
    }

    public function ver_productos_pedido($id) {
        return $this->dame_query("select 
                producto.id, producto.codigo, producto.descripcion, 
                pedido_detalle.cantidad_pedido, pedido_detalle.cantidad_despacho, pedido_detalle.precio,
                estatus_pedido.descripcion estatus_pedido
                from pedido_detalle
                inner join producto on pedido_detalle.producto_id = producto.id
                inner join estatus_pedido on pedido_detalle.estatus_pedido_id = estatus_pedido.id
                where pedido_id = $id ");
    }

}

?>
