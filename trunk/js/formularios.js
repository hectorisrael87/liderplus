$(document).ready(function(){
    $(".row-fluid form").each(function(){
        if(typeof console.log=="function"){
            console.log("Activando validación Formulario "+$(this).attr("name"));
        }
        $(this).validate({
            showErrors: function(){
                $(".alert").html("No se puede procesar este formulario porque tiene "
                    + this.numberOfInvalids() 
                    + " errores.");
                this.defaultShowErrors();
            }
        });
    });
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-]+$/i.test(value);
    }, "Este campo debe tener solo letras numeros y guiones.");
    $.validator.methods.range = function (value, element, param) {
        var globalizedValue = value.replace(",", ".");
        return this.optional(element) || (globalizedValue >= param[0] && globalizedValue <= param[1]);
    }
 
    $.validator.methods.number = function (value, element) {
        return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
    }
    
    $.validator.setDefaults({
        errorClass: "help-inline",
        validClass: "help-inline",
        highlight: function(element){
            $(element).parents(".control-group:eq(0)").removeClass("success").addClass("error");
        },
        unhighlight: function(element){
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