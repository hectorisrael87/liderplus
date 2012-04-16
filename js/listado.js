$(document).ready(function(){
    $.getScript("comun.js");
    $(".btn-danger").click(function(){
        return confirm("Realmente desea borrar este registro?");
    })
});