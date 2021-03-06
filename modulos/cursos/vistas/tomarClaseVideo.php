<?php
require_once('layout/headers/headInicio.php');
require_once('layout/headers/headTomarClase.php');
require_once('layout/headers/headTomarClasePopcorn.php');

$json = $clase->codigo;
$var = json_decode($json, true);

if (isset($var['videoData'])) {
    $videoData = $var['videoData'];
} else {
    $videoData = array();
    $videoData['top'] = 0;
    $videoData['left'] = 0;
    $videoData['width'] = 100;
    $videoData['height'] = 100;
}
?>
<link rel="stylesheet" href="/layout/css/tomarClaseVideo.css" />

<script languague="javascript">
    function cargarElementosGuardados(){
<?php
if (isset($var['textos'])) {
    $textos = $var['textos'];
    foreach ($textos as $texto) {
        ?>
                        agregarTextoDiv( '<?php echo $texto['texto']; ?>','<?php echo $texto['inicio']; ?>','<?php echo $texto['fin']; ?>','<?php echo $texto['color']; ?>','<?php echo $texto['top']; ?>','<?php echo $texto['left']; ?>','<?php echo $texto['width']; ?>','<?php echo $texto['height']; ?>');
                                                                        
        <?php
    }
}
if (isset($var['imagenes'])) {
    $imagenes = $var['imagenes'];
    foreach ($imagenes as $imagen) {
        ?>
                        agregarImagenDiv('<?php echo $imagen['urlImagen']; ?>','<?php echo $imagen['inicio']; ?>','<?php echo $imagen['fin']; ?>','<?php echo $imagen['color']; ?>','<?php echo $imagen['top']; ?>','<?php echo $imagen['left']; ?>','<?php echo $imagen['width']; ?>','<?php echo $imagen['height']; ?>');
        <?php
    }
}
if (isset($var['videos'])) {
    $videos = $var['videos'];
    foreach ($videos as $video) {
        ?>
                        agregarVideoDiv('<?php echo $video['urlVideo']; ?>','<?php echo $video['inicio']; ?>','<?php echo $video['fin']; ?>','<?php echo $video['color']; ?>','<?php echo $video['top']; ?>','<?php echo $video['left']; ?>','<?php echo $video['width']; ?>','<?php echo $video['height']; ?>');
        <?php
    }
}
if (isset($var['links'])) {
    $links = $var['links'];
    foreach ($links as $link) {
        ?>
                        agregarLinkDiv('<?php echo $link['texto']; ?>','<?php echo $link['url']; ?>','<?php echo $link['inicio']; ?>','<?php echo $link['fin']; ?>','<?php echo $link['color']; ?>','<?php echo $link['top']; ?>','<?php echo $link['left']; ?>','<?php echo $link['width']; ?>','<?php echo $link['height']; ?>');
        <?php
    }
}
if (isset($var['preguntas'])) {
    $preguntas = $var['preguntas'];
    foreach($preguntas as $pregunta) {
        ?> 
                agregarPreguntaDiv(<?php echo $pregunta['idPregunta']; ?>,'<?php echo $pregunta['inicio']; ?>');
        <?php
    }
}
?>
    }
</script>
<?php
require_once('layout/headers/headCierreTomarClase.php');
?>
</div>
<div id="editorContainment" style="z-index:-50;">
    <video id="mediaPopcorn" class="videoClass" style="z-index:-10; position: absolute; top: <?php echo $videoData['top'] . '%'; ?>; left: <?php echo $videoData['left'] . '%'; ?>; width: <?php echo $videoData['width'] . '%'; ?>; height: <?php echo $videoData['height'] . '%'; ?>;">
        <source src="<?php echo $clase->archivo; ?>" type="video/mp4"></source>      
        <source src="<?php echo $clase->archivo2; ?>" type="video/webm"></source>      
        Tu navegador no es compatible con las características de este sitio. Te recomendamos descargar google chrome
        <a href="http://www.google.com/intl/es/chrome/browser/"> desde aquí</a>
    </video>  
    <div id="footnotediv">
    </div>
</div>

<div id="dialog-form-responderPregunta" title="Pregunta" style="display:none;">
    <div id="preguntaContainer">

    </div>
</div>