<?php

require_once 'constants.php';

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
        //$this->exec_query("start transaction");
        $usuario = $_SESSION['usuario']['id'];
        $resultado = array();
        try {
            $pedido_detalle = array();
            $pedido_detalle['producto_id'] = $data['producto_id'];
            $pedido_detalle['cantidad_pedido'] = $data['cantidad_pedido'];
            $pedido_detalle['precio'] = $data['precio'];
            unset($data['producto_id'], $data['cantidad_pedido'], $data['precio']);
            $data['estatus_pedido_id'] = STATUS_PEDIDO_PENDIENTE;
            $resultado['pedido'] = $this->insert("pedido", $data);
            if ($resultado['pedido']['suceed']) {
                
                $resultado['suceed'] = true;
                //<editor-fold defaultstate="collapsed" desc="detalles del pedido">
                $resultado['pedido_detalle'] = array();
                for ($i = 0; $i < sizeof($pedido_detalle['producto_id']); $i++) {
                    $result_detalle = $this->insert("pedido_detalle", array(
                        "pedido_id" => $resultado['pedido']['insert_id'],
                        "producto_id" => $pedido_detalle['producto_id'][$i],
                        "cantidad_pedido" => $pedido_detalle['cantidad_pedido'][$i],
                        "precio" => $pedido_detalle['precio'][$i],
                        "estatus_pedido_detalle_id" => STATUS_PEDIDO_PENDIENTE
                            ));
                    array_push($resultado['pedido_detalle'], $result_detalle);
                    if (!$result_detalle['suceed']) {
                        $resultado['suceed'] = $result_detalle['suceed'];
                        $resultado['mensaje'] = "Ha ocurrido un error al procesar el pedido";
                    }
                }
                // </editor-fold>
                $resultado_fase = $this->insert("pedido_fase", array(
                    "pedido_id" => $resultado['pedido']['insert_id'],
                    "fase_id" => FASE_PEDIDO,
                    "usuario_id" => $usuario));
                $resultado['fase'] = $resultado_fase;
            }
            // $this->exec_query("commit");
        } catch (Exception $exc) {
            $resultado['suceed'] = false;
            $resultado['mensaje'] = "Error inesperado, contacte con el administrador del sistema";
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

        return $this->dame_query("SELECT 
                pedido_detalle.id pedido_detalle_id, 
                producto.id, 
                producto.codigo, 
                producto.descripcion, 
                pedido_detalle.cantidad_pedido, 
                pedido_detalle.cantidad_despacho, 
                pedido_detalle.precio, 
                estatus_pedido_detalle.descripcion estatus_pedido
                FROM pedido_detalle
                    INNER JOIN producto ON pedido_detalle.producto_id = producto.id
                    INNER JOIN estatus_pedido_detalle ON pedido_detalle.estatus_pedido_detalle_id = estatus_pedido_detalle.id
                WHERE pedido_id = $id ");
    }

    public function ver_productos_pedido_por_facturar($id) {
        return $this->dame_query("select producto.id, 
                producto.codigo, producto.descripcion, 
                pedido_detalle.cantidad_pedido, pedido_detalle.cantidad_despacho, pedido_detalle.precio,
                estatus_pedido_detalle.descripcion estatus_pedido
                from pedido_detalle
                inner join producto on pedido_detalle.producto_id = producto.id
                inner join estatus_pedido_detalle on pedido_detalle.estatus_pedido_detalle_id = estatus_pedido_detalle.id
                where pedido_id = $id and estatus_pedido_detalle.id = " . STATUS_PEDIDO_DETALLE_PROCESADO);
    }

    public function procesar($data) {
        $result = array();
        $result['suceed'] = false;
        
        // <editor-fold defaultstate="collapsed" desc="actualizar pedido">
        $resultado_pedido = $this->update("pedido", array(
            "estatus_pedido_id" => STATUS_PEDIDO_PROCESADO
                ), array(
            "id" => $data['id'])); 
        $result['pedido'] = $resultado_pedido;
        // </editor-fold>

        // <editor-fold defaultstate="collapsed" desc="actualizar fase">
        $resultado_pedido_fase = $this->insert("pedido_fase", array(
            "pedido_id" => $data['id'],
            "fase_id" => FASE_ALMACEN,
            "usuario_id" => $_SESSION['usuario']['id'])); 
        $result['fase'] = $resultado_pedido_fase;
        // </editor-fold>

        if ($resultado_pedido['suceed'] && $resultado_pedido_fase['suceed']) {
            $result['pedido_detalle'] = array();
            for ($i = 0; $i < sizeof($data['producto_id']); $i++) {
                // <editor-fold defaultstate="collapsed" desc="actualizar pedido detalle">
                $resultado_pedido_detalle = $this->update("pedido_detalle", array(
                    "estatus_pedido_detalle_id" => $data['status_pedido_detalle'][$i],
                    "cantidad_despacho" => $data['cantidad_despacho'][$i]
                        ), array(
                    "id" => $data['pedido_detalle_id'][$i]
                        )); 
                array_push($result['pedido_detalle'], $resultado_pedido_detalle);
                // </editor-fold>

                if (!$resultado_pedido_detalle['suceed']) {
                    break;
                    return $result;
                }
            }
            $result['suceed'] = true;
            return $result;
        } else {
            return $result;
        }
    }

    public function listar_status_pedido_detalle() {
        return $this->select("*", "estatus_pedido_detalle");
    }

    public function listar_pedido_chequeado() {
        $query = "select p.id as pedido_id, f.id as factura_id, f.numero  
            from pedido p join factura f on p.id = f.pedido_id 
            where p.estatus_pedido_id =" . STATUS_PEDIDO_PROCESADO;
        return $this->dame_query($query);
    }

    public function listar_pedido_despacho() {
        $query = "select p.id as pedido_id, f.id as factura_id, f.numero  
            from pedido p join factura f on p.id = f.pedido_id 
            where p.estatus_pedido_id =" . STATUS_PEDIDO_TRANSPORTE;
        return $this->dame_query($query);
    }

    public function json_pedidos_por_facturar_cliente($cliente) {
        return $this->dame_query("select * from pedido 
                where cliente_id= $cliente 
                and estatus_pedido_id=" . STATUS_PEDIDO_PROCESADO);
    }

    public function listar_seguimiento_pedido($id) {
        $query = "select d.*, e.descripcion 
            from `despacho` d join `estatus_seguimiento_despacho` e
            on d.estatus_seguimiento_despacho_id = e.id
            where factura_empresa_transporte_id=" . $id;
        return $this->dame_query($query);
    }

}

?>
