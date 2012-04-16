$(document).ready(function(){
    $.getScript("comun.js");
    $("#formulario").validate({
        errorClass:"help-inline",
        validClass:"help-inline",
        highlight:function(element){
            $(element).parents(".control-group:eq(0)").removeClass("success").addClass("error");
        },
        unhighlight:function(element){
            $(element).parents(".control-group:eq(0)").removeClass("error").addClass("success");
        }
    });
    
    $("input[type='submit']").click(function() {
        if ($(this).parents("form").eq(0).valid()) {
            return confirm("¿Confirma que desea realizar esta acción?");
        } else {
            return false;
        }
    });
});