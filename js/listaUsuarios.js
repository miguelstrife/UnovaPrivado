$(function(){
    $(".cuadro").hover(
        function () {
            $(this).children(".cuadroFooter").addClass("bottomFooterHover");
        }, 
        function () {
            $(this).children(".cuadroFooter").removeClass("bottomFooterHover");
        });
    $("a.borrarUsuario").click(function(e){
        e.preventDefault();
        var id = $(this).attr("id");
        bootbox.dialog("Se eliminará permanentemente el usuario<br>¿Estás seguro?", 
            [{
                "label" : "Eliminar",
                "class" : "btn-danger",
                "icon"  : "icon-warning-sign icon-white",
                "callback": function() {
                    $url = "/usuarios.php?c=usuario&a=eliminar&iu="+id+"&pagina="+pagina;
                    redirect($url);
                }
            }, {
                "label" : "Cancelar",
                "class" : "btn-primary"
            }]);
    });
});