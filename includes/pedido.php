<?php

/**
 * Description of pedido
 *
 * @author Anyul Rivas
 */
class pedido extends db implements crud {

    const status_pendiente = 1;
    const status_procesado = 2;
    const status_almacen = 3;
    const status_cancelado = 3;
    const fase_pedido = 1;
    const fase_almacen = 2;
    const fase_facturacion = 3;
    const fase_chequeo = 4;
    const fase_transporte = 5;
    const fase_confirmacion = 6;

    public function actualizar($id, $data) {
        return $this->update("pedido", $data, array("id" => $id));
    }

    public function borrar($id) {
        return $this->delete("pedido", array("id" => $id));
    }

    public function insertar($data) {
        //$this->exec_query("start transaction");
        $usuario = $_SESSION['usuario']['id'];
        try {
            $pedido_detalle = array();

            $pedido_detalle['producto_id'] = $data['producto_id'];
            $pedido_detalle['cantidad_pedido'] = $data['cantidad_pedido'];
            $pedido_detalle['precio'] = $data['precio'];
            unset($data['producto_id'], $data['cantidad_pedido'], $data['precio']);
            $data['estatus_pedido_id'] = self::status_pendiente;
            $resultado = $this->insert("pedido", $data);

            if ($resultado['suceed']) {
                //           <editor-fold defaultstate="collapsed" desc="detalles del pedido">
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
                // </editor-fold>
                $resultado_fase = $this->insert("pedido_fase", array(
                    "pedido_id" => $resultado['insert_id'],
                    "fase_id" => self::fase_pedido,
                    "usuario_id" => $usuario));
                $resultado['fase'] = $resultado_fase;
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

        return $this->dame_query("select pedido_detalle.id pedido_detalle_id,  producto.id, 
                producto.id, producto.codigo, producto.descripcion, 
                pedido_detalle.cantidad_pedido, pedido_detalle.cantidad_despacho, pedido_detalle.precio,
                estatus_pedido_detalle.descripcion estatus_pedido
                from pedido_detalle
                inner join producto on pedido_detalle.producto_id = producto.id
                inner join estatus_pedido_detalle on pedido_detalle.estatus_pedido_id = estatus_pedido_detalle.id
                where pedido_id = $id ");
    }

    public function ver_productos_pedido_por_facturar($id) {
        return $this->dame_query("select producto.id, 
                producto.codigo, producto.descripcion, 
                pedido_detalle.cantidad_pedido, pedido_detalle.cantidad_despacho, pedido_detalle.precio,
                estatus_pedido_detalle.descripcion estatus_pedido
                from pedido_detalle
                inner join producto on pedido_detalle.producto_id = producto.id
                inner join estatus_pedido_detalle on pedido_detalle.estatus_pedido_id = estatus_pedido_detalle.id
                where pedido_id = $id and estatus_pedido_id = 2");
    }

    public function procesar($data) {
        $uno = $this->update("pedido", array(
            "estatus_pedido_id" => self::status_procesado
                ), array(
            "id" => $data['id']));
        $dos = $this->insert("pedido_fase", array(
            "pedido_id" => $data['id'],
            "fase_id" => self::fase_almacen,
            "usuario_id" => $_SESSION['usuario']['id']));
        if ($uno['suceed'] && $dos['suceed']) {
            for ($i = 0; $i <= sizeof($data); $i++) {
                $tres = $this->update("pedido_detalle", array(
                    "estatus_pedido_id" => $data['status_pedido_detalle'][$i],
                    "cantidad_despacho" => $data['cantidad_despacho'][$i]
                        ), array(
                    "id" => $data['pedido_detalle_id'][$i]
                        ));
                if (!$tres['suceed']) {
                    break;
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function listar_status_pedido_detalle() {
        return $this->select("*", "estatus_pedido_detalle");
    }

    public function listar_pedido_chequeado() {
        $query = "select p.id as pedido_id, f.id as factura_id, f.numero  
            from pedido p join factura f on p.id = f.pedido_id 
            where p.estatus_pedido_id = 4";
        return $this->dame_query($query);
    }
    
    public function listar_pedido_despacho() {
        $query = "select p.id as pedido_id, f.id as factura_id, f.numero  
            from pedido p join factura f on p.id = f.pedido_id 
            where p.estatus_pedido_id = 5";
        return $this->dame_query($query);
    }

}

?>
