$(document).ready(function(){
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

    $(".row-fluid form").each(function(){
        //console.log("Formulario "+$(this).attr("name"));
        $(this).validate({
            showErrors: function(errorMap, errorList){
                $(".alert").html("No se puede procesar este formulario porque tiene "
                    + this.numberOfInvalids() 
                    + " errores.");
                this.defaultShowErrors();
            }
        });
    });
    
    $("input[type='submit']").click(function() {
        if ($(this).parents("form").eq(0).valid()) {
            return confirm("¿Confirma que desea realizar esta acción?");
        } else {
            return false;
        }
    });
});