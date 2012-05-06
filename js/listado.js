$(document).ready(function(){
    $(".btn-danger").click(function(){
        return confirm("Realmente desea borrar este registro?");
    })
});