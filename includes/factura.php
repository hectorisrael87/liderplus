<?php

/**
 * Description of factura
 *
 * @author emessia
 */
class factura extends db implements crud {
    
    const status_almacen = 2;
    const status_emitida = 1;
    
    public function actualizar($id, $data) {
        return $this->update("factura", $data, array("id" => $id));
    }
    public function borrar($id) {
        return $this->delete("factura",array("id"=>$id));
    }
    public function insertar($data) {
        try {
            $factura_detalle = array();

            $factura_detalle['producto_id'] = $data['producto_id'];
            $factura_detalle['cantidad_pedido'] = $data['cantidad_pedido'];
            $factura_detalle['precio'] = $data['precio'];
            unset($data['producto_id'], $data['cantidad_pedido'], $data['precio']);
            $data['estatus_factura_id'] = self::status_almacen;
            
            $resultado = $this->insert("factura", $data);

            if ($resultado['suceed']) {
                for ($i = 0; $i < sizeof($factura_detalle['producto_id']); $i++) {
                    $subtotal = $factura_detalle['precio'][$i]/(1 + $data['porcentaje_iva']);
                    $result_detalle = $this->insert("factura_detalle", array(
                        "factura_id"   => $resultado['insert_id'],
                        "producto_id" => $factura_detalle['producto_id'][$i],
                        "cantidad"    => $factura_detalle['cantidad_pedido'][$i],
                        "precio"      => $factura_detalle['precio'][$i],
                        "monto_iva"   => $subtotal * $data['porcentaje_iva'],
                        "subtotal"    => $subtotal
                            ));
                    if (!$result_detalle['suceed']) {
                        $resultado['suceed'] = $result_detalle['suceed'];
                        $resultado['mensaje'] = "Ha ocurrido un error al procesar el pedido";
                    }
                }
            }
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return $resultado;
        }
    
    public function listar() {
        return $this->dame_query("select f.id, f.numero, f.fecha, f.monto_total, CONCAT(c.nombres,' ',c.apellidos) cliente, ef.descripcion
            from factura f
            join pedido p on p.id = f.pedido_id 
            join cliente c on c.id = p.cliente_id
            join estatus_factura ef on ef.id = f.estatus_factura_id");
    }
    
    public function ver($id) {
        
        $query  = "select f.*, concat(c.nombres, ' ', c.apellidos) as cliente, ef.descripcion as estatus
            from factura f join pedido p on f.pedido_id = p.id
            join cliente c on p.cliente_id = c.id
            join estatus_factura ef on f.estatus_factura_id = ef.id
            where f.id = ".$id;
        
        return $this->dame_query($query);
    }
    
    public function ver_detalle_factura($id) {
        $query = "select d.*, p.codigo, p.descripcion, d.cantidad * d.precio as total   
            from factura_detalle d 
            join producto p on d.producto_id = p.id 
            where d.factura_id=".$id;
                
        return $this->dame_query($query);
    }
}

?>
