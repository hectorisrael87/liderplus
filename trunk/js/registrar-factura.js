function listarPedidosPorFacturarPorCliente() {
    $("#productos tbody tr").remove();
    $("#pedido_id option").remove();
    $("<option value=\"\">Seleccione</option>").appendTo("#pedido_id");
    if (!$("#cliente_id").val()=='') {
        $.getJSON('listarPedidosPorFacturarPorCliente/'+$("#cliente_id").val(), 
            function(data){
                for(var elemento in data.data){
                    if( typeof data.data[elemento] == "object"){
                        $("<option value='"+ data.data[elemento].id + "'>"+ data.data[elemento].numero  +"</option>").appendTo("#pedido_id");
                    }
                }
            });
    } else {
        actualizarTabla();
    }
}

function verDetallePedidoPorFacturar() {
    $("#productos tbody tr").remove();
    if (!$("#pedido_id").val()=='') {
        $.getJSON('listarDetalleFactura/'+$("#pedido_id").val(), 
            function(data){
                for(var elemento in data.data){
                    if( typeof data.data[elemento] == "object"){
                        agregarProducto(data.data[elemento]);
                    }
                }
            });
    } else {
        actualizarTabla();
    }
    
}

function agregarProducto(producto) {
    producto.precio = parseFloat(producto.precio);
    producto.cantidad = parseInt(producto.cantidad_despacho);
    filaPedido = $("<tr/>");
    inputProducto = $("<input/>",{
        type : "hidden", 
        name : "producto_id[]", 
        val : producto.id
    });
    
    celdaCodigo = $("<td/>", {
        html : "<span>" + producto.codigo + "</span>"
    });
    celdaProducto = $("<td/>",{
        html : "<span> " + producto.descripcion + "</span>"
    });
    celdaProducto.append(inputProducto);
        
    inputCantidad = $("<input/>",{
        type: "hidden", 
        name: "cantidad_pedido[]", 
        val: producto.cantidad
    });
    celdaCantidad = $("<td/>", {
        html: "<span class='cantidad_producto pull-right'>" + producto.cantidad_despacho + "</span>"
    });
    celdaCantidad.append(inputCantidad);
    
    inputPrecio =$("<input/>",{
        type: "hidden",
        name: "precio[]",
        val: producto.precio
    });    
    celdaPrecio = $("<td/>", {
        html: "<span class='precio_producto pull-right'>" + producto.precio.formatCurrency() + "</span>"
    });
    celdaPrecio.append(inputPrecio);
    var totalItem = parseFloat(producto.precio * producto.cantidad_despacho);
    celdaTotal = $("<td/>", {
        html: "<span class='pull-right'>" + totalItem.formatCurrency() + "</span>"
    });
    filaPedido.append(celdaCodigo).append(celdaProducto).append(celdaCantidad).append(celdaPrecio).append(celdaTotal);
    $("#productos tbody").append(filaPedido);
    actualizarTabla();
}

function actualizarTabla(){
    cantidad = 0;
    subtotal = 0;
    iva = 0;
    total = 0;
    $("#productos tbody tr").each(function(){
        tempCantidad = parseInt($(this).find(".cantidad_producto").text());
        tempPrecio = parseFloat($(this).find(".precio_producto").text().replace(".","").replace(",","."));
        cantidad += tempCantidad;
        subtotal += tempPrecio * tempCantidad;
    });
    subtotal = parseInt(subtotal * 100)/100;
    iva = subtotal * iva_rata;
    total = subtotal + iva;
    $("#total_productos").text(cantidad);
    $("#subtototal").text(subtotal.formatCurrency());
    $("#iva").text(iva.formatCurrency());
    $("#porcentaje_iva").val(iva_rata * 100);
    $("#label_iva").text("IVA("+$("#porcentaje_iva").text()+"%)");
    $("#total").text(total.formatCurrency());
    $("#monto_total").val(total);
    $("#subtotal").val(subtotal);
 
}

$(document).ready(function() {
    $("#agregarProductoModal").hide(); 
    $("#cliente_id").change(function(){
        listarPedidosPorFacturarPorCliente(); 
    });
    $("#pedido_id").change(function() {
        verDetallePedidoPorFacturar(); 
    });
});

