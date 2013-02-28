var $popPrincipal;
var $indice = 0;

$(function(){    
    validarSesion();
    var segundos  = 30;
    setInterval(validarSesion, segundos * 1000);
//    $("body").bind("contextmenu", function(e) {
//        e.preventDefault();
//    });
    //mantener la sesión abierta
    KeepAlive();
    setInterval(KeepAlive, '600000');
});

function validarSesion(){
    $.ajax({
        type: "get",
        url: "/usuarios.php?a=validarLoginUnicoAjax" ,
        dataType: "text",
        success: function(data) {
            var str = data.toString();
            if(str.indexOf("valid session") != -1){  
                //Es una sesión válida
            }else{
                //Ya no es una sesión válida. Redireccionando..
                redirect("/?e=1&msg=sesionNoValida");
            }
        }
    });
}

Popcorn( function() {
    $popPrincipal = Popcorn('#mediaPopcorn');
    $popPrincipal.controls(true);
    $popPrincipal.volume(0.5);
    $popPrincipal.autoplay(true);
    cargarElementosGuardados();
});

function pauseVideo(){
    $popPrincipal.pause();
}

function getUnidadPx(unidad){
    if(unidad.indexOf("auto") != -1){
        return unidad;
    }else{
        return unidad + "%";
        
    }
}

function agregarTextoDiv(texto, inicio, fin, color, top, left, width, height){
    var textoDiv = '<div id="drag_'+$indice+'" class="ui-corner-all textoAgregado stack draggable" style="overflow:auto;background-color: '+color+'; position: fixed; top: '+getUnidadPx(top)+'; left: '+getUnidadPx(left)+'; width: '+getUnidadPx(width)+'; height: '+getUnidadPx(height)+';">' +
    '<div id="content_'+$indice+'" style="width: 100%;height: 100%;overflow-y: auto;overflow-wrap: break-word;">'+
    '<div>' +
    texto +
    '</div>' +
    '</div>' +
    '</div>';
 
    $popPrincipal.footnote({
        start: inicio,
        end: fin,
        text: textoDiv,
        target: "footnotediv"
    });
    $("#drag_"+$indice).draggable({
        handle: "#content_"+$indice,
        containment: "#editorContainment",
        stack: ".stack",
        start: function() {
            // if we're scrolling, don't start and cancel drag
            if ($(this).data("scrolled")) {
                $(this).data("scrolled", false).trigger("mouseup");
                return false;
            }
        }
    }).find("*").andSelf().scroll(function() {               
        // bind to the scroll event on current elements, and all children.
        //  we have to bind to all of them, because scroll doesn't propagate.
        
        //set a scrolled data variable for any parents that are draggable, so they don't drag on scroll.
        $(this).parents(".ui-draggable").data("scrolled", true);
        
    });
    $indice++;
}

function agregarImagenDiv(urlImagen, inicio, fin, color, top, left, width, height){
    var textoDiv = '<div id="drag_'+$indice+'"  class="ui-corner-all imagenAgregada stack draggable" style="background-color: '+color+'; position: fixed; top: '+getUnidadPx(top)+'; left: '+getUnidadPx(left)+'; width: '+getUnidadPx(width)+'; height: '+getUnidadPx(height)+';">' +
    '<div>'+
    '<img src="'+urlImagen+'" style="width:98%; height: 98%;position: absolute;top:1%;left:1%;"/>'+
    '</div>' +
    '</div>';
 
    $popPrincipal.footnote({
        start: inicio,
        end: fin,
        text: textoDiv,
        target: "footnotediv"
    });
    $("#drag_"+$indice).draggable({
        containment: "#editorContainment",
        stack: ".stack"
    });
    $indice++;
}

function agregarLinkDiv(texto, url, inicio, fin, color, top, left, width, height){
    var textoDiv = '<div id="drag_'+$indice+'"  class="ui-corner-all linkAgregado stack draggable" style="background-color: '+color+'; position: fixed; top: '+getUnidadPx(top)+'; left: '+getUnidadPx(left)+'; width: '+getUnidadPx(width)+'; height: '+getUnidadPx(height)+';">' +
    '<a href="'+url+'" target="_blank" onclick="pauseVideo()" class="textoLink">'+
    '<div>' +
    decode_utf8(texto) +
    '</div>' +
    '</a>'+
    '</div>';
    
    $popPrincipal.footnote({
        start: inicio,
        end: fin,
        text: textoDiv,
        target: "footnotediv"
    });
    $("#drag_"+$indice).draggable({
        containment: "#editorContainment",
        stack: ".stack"
    });
    $indice++;
}

var $idVideo = 0;
function agregarVideoDiv(urlVideo, inicio, fin, color, top, left, width, height){
    var indiceVideo = $idVideo;
    $idVideo++;
    var textoDiv = '<div id="videoContainer_'+indiceVideo+'" class="ui-corner-all videoAgregado draggable" style="background-color: '+color+'; position: fixed; top: '+getUnidadPx(top)+'; left: '+getUnidadPx(left)+'; width: '+getUnidadPx(width)+'; height: '+getUnidadPx(height)+';">' +
    '<p class="ui-widget-header dragHandle">Arr&aacute;strame de aqu&iacute;<br></p>'+
    '<div id="video_'+indiceVideo+'" class="videoPopcorn" style="width:98%; height: 98%;position: absolute;top:1%;left:1%;">'+
    '</div>' +
    '</div>';
 
    $popPrincipal.footnote({
        start: inicio,
        end: fin,
        text: textoDiv,
        target: "footnotediv"
    });
    var auxVarVideo = Popcorn.smart('#video_'+indiceVideo, urlVideo);
    auxVarVideo.autoplay(false);
    auxVarVideo.pause();
    auxVarVideo.on("playing", function() {    	
        pauseVideo();
    });
    $("#videoContainer_"+indiceVideo).draggable({
        handle: "p",
        containment: "#editorContainment",
        stack: ".stack"
    });
}
