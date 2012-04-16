function eventosGlobales(){
    /* Configuración Global para peticiones ajax y envío de formularios*/
    $("body").ajaxStart(function(){
        setTimeout(function(){
            $.fancybox('<h3>Cargando...</h3>',{
                modal:true,
                centerOnScroll:true,
                transitionIn:'none'
            })
        }, 500);
    });
    $("body").ajaxSuccess(function(){
        setTimeout(function(){
            $.fancybox.close();
        }, 500);
    });
    $("body").ajaxError(function(){
        setTimeout(function(){
            $.fancybox.close();
        }, 250);
    });
    $("form").bind("submit",function(){
        $.fancybox('<h4>Procesando, por favor espere...</h4>',{
            modal:true,
            centerOnScroll:true,
            transitionIn:'none'
        });
        setTimeout(function(){
            $.fancybox.close();
        }, 250);
    });
}
$(document).ready(function(){
    $(".alert").alert();
    eventosGlobales();
});
