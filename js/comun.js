function eventosGlobales(){
    /* Configuración Global para peticiones ajax y envío de formularios*/
    $("body").ajaxStart(function(){
        setTimeout(function(){
            $('#myModal').modal('show');
        }, 500);
    });
    $("body").ajaxSuccess(function(){
        setTimeout(function(){
            $('#myModal').modal('hide');
        }, 500);
    });
    $("body").ajaxError(function(){
        setTimeout(function(){
            $('#myModal').modal('hide');
        }, 250);
    });
    $("form").bind("submit",function(){
        $('#myModal').modal('show');
    });
}
Number.prototype.format = function(){
var number = new String(this);
var result = '';
while( number.length > 3 )
{
 result = '.' + number.substr(number.length - 3) + result;
 number = number.substring(0, number.length - 3);
}
result = number + result;
return result;
};
$(document).ready(function(){
    $(".alert").alert();
    eventosGlobales();
});

var iva_rata = 0.12;