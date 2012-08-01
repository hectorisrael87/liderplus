function agregarProducto(producto) {
    producto.precio = parseFloat(producto.precio.replace(".","").replace(",","."));
    producto.cantidad = parseInt(producto.cantidad);
    filaPedido = $("<tr/>");
    inputProducto = $("<input/>",{
        type : "hidden", 
        name : "producto_id[]", 
        val : producto.id
    });
    celdaProducto = $("<td/>",{
        html : "<span><i class='icon-remove'></i> " + producto.nombre + "</span>"
    });
    celdaProducto.append(inputProducto);
        
    inputCantidad = $("<input/>",{
        type: "hidden", 
        name: "cantidad_pedido[]", 
        val: producto.cantidad
    });
    celdaCantidad = $("<td/>", {
        html: "<span class='cantidad_producto pull-right'>" + producto.cantidad + "</span>"
    });
    celdaCantidad.append(inputCantidad);
    
    inputPrecio =$("<input/>",{
        type: "hidden",
        name: "precio[]",
        val: producto.precio
    });    
    celdaPrecio = $("<td/>", {
        html: "<span class='precio_producto pull-right'>" + producto.precio.formatCurrency() + " Bsf. </span>"
    });
    celdaPrecio.append(inputPrecio);
    tempTotal = producto.precio * producto.cantidad
    celdaTotal = $("<td/>", {
        html: "<span class='pull-right'>" + tempTotal.formatCurrency() + " Bsf.</span>"
    });
    filaPedido.append(celdaProducto).append(celdaCantidad).append(celdaPrecio).append(celdaTotal);
    $("#productos tbody").append(filaPedido);
    actualizarTabla();
}
function eliminarProducto(producto) {
    if(confirm("Confirma que desea eliminar este producto?")){
        $(producto).closest("tr").remove();
        actualizarTabla();
    }
}
function actualizarTabla(){
    cantidad = 0;
    subtotal = 0;
    iva = 0;
    total = 0;
    $("#productos tbody tr").each(function(){
        tempCantidad = parseInt($(this).find(".cantidad_producto").text());
        tempPrecio = parseFloat($(this).find("input[name='precio[]']").val());
        cantidad += tempCantidad;
        subtotal += tempPrecio * tempCantidad;
    });
    iva = subtotal * 0.10;
    total = subtotal + iva;
    $("#total_productos").text(cantidad);
    $("#subtototal").text(subtotal.formatCurrency() + " Bsf.");
    $("#iva").text(iva.formatCurrency() + " Bsf. ");
    $("#total").text(total.formatCurrency() + " Bsf.");
    $("#productos_form").valid();
}
function cuadromodal(){
    $("#agregarProductoModal").hide();
    $("#agregarProductoModal .close, #agregarProductoModal .btn-danger").click(function(){
        $("#agregarProductoModal").modal('hide');
    });
    $("#agregarProductoBtn").click(function(){
        $("#agregarProductoModal").modal('show');
    });
    
    $("#agregar").click(function(){
        if($("#buscarProducto").valid()){
            producto = new Object;
            producto.id = $("#producto_id").val();
            dataProducto = $("#producto_id option:selected").text().split("-");
            producto.nombre = dataProducto[0];
            producto.precio = dataProducto[1];
            producto.cantidad = $("#cantidad").val();
            agregarProducto(producto);
            $("#cantidad").val("");
        }
    });
}
function validacionFormulario(){
    if(accion=="procesar"){
        $("#cliente_id").attr("disabled", true);
    }
    else
    {
        $(document).on("click", ".icon-remove", function(){
            eliminarProducto($(this));
        });
    }
    $("#cantidad").rules("add",{
        required: true,
        min: 1,
        messages:{
            required: "Debe agregar al menos un producto",
            min: "Debe agregar al menos uno"
        }
    });
    $("#valor").rules("add",{
        required:function(){
            return ($("#productos").find("input[name='producto_id[]']").length==0);
        },
        messages:{
            required:"Debe agregar al menos un producto para realizar una orden de compra."
        }
    });
    if($("[id*='status_pedido_detalle']").length>0){
        $("[id*='status_pedido_detalle']").rules("add",{
            required:true,
            messages:{
                required:"Seleccione un status para este producto"
            }
        });
    }
    if($("[id*='cantidad_despacho']").length>0){
        $("[id*='cantidad_despacho']").rules("add",{
            required:true,
            min:0,
            max: function(element) {
                return $(element).parents("tr").eq(0).find("input[name='cantidad_pedido[]']").val();   
            },
            messages:{
                required: "Introduzca la cantidad disponible en despacho",
                max: "Introduzca un valor no mayor a la cantidad solicitada ({0})"
            }
        });
    }
    
    $("#buscarProducto").validate({
        groups: {
            agregarProducto: "producto_id cantidad"
        },
        errorPlacement: function(error, element){
            if (element.attr("name") == "producto_id" || element.attr("name") == "cantidad" ){
                error.insertAfter("#agregar");
            } else{
                error.insertAfter(element);
            }
        }
    });
    
}

function verificar_cliente(cliente) {
    $("#cliente_id").change(function(){
        if($(this).val()!=""){
            data = {
                cliente_id:cliente
            };
            $.getJSON("../cliente/verificarCliente", data, function(){
                
            });
        }
    });
}
$(document).ready(function(){
    cuadromodal();
    validacionFormulario();
});