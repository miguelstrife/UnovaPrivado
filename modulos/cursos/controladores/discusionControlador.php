<?php

function agregarDiscusion() {
    $res = false;
    $usuario = getUsuarioActual();
    if (isset($_POST['titulo']) && isset($_POST['texto']) && isset($_POST['curso'])) {
        $titulo = removeBadHtmlTags(trim($_POST['titulo']));
        $texto = removeBadHtmlTags(trim($_POST['texto']));
        $idCurso = removeBadHtmlTags($_POST['curso']);
        if (strlen($titulo) > 0 && strlen($titulo) <= 140 && strlen($texto) > 0) {
            require_once 'modulos/cursos/modelos/DiscusionModelo.php';
            $discusion = new Discusion();
            $discusion->titulo = $titulo;
            $discusion->texto = $texto;
            $discusion->idCurso = $idCurso;
            $discusion->idUsuario = $usuario->idUsuario;
            $discusion->idDiscusion = altaDiscusion($discusion);
            if ($discusion->idDiscusion >= 0) {
                //Se agrego correctamente
                $res = true;
                $msg = "se agrego discusion: " . $discusion->idDiscusion;
            } else {
                //Ocurrió un error al agregar
                $msg = "Ocurrió un error al agregar a la base de datos";
            }
        } else {
            $msg = "Los datos introducidos no son válidos";
        }
    } else {
        $msg = "No hay datos";
    }
    $resultado = array(
        "res" => $res,
        "msg" => $msg
    );
    $resultado = json_encode($resultado);
    echo $resultado;
}

function obtenerDiscusiones() {
    if (isset($_POST['curso']) && isset($_POST['pagina']) && isset($_POST['rows'])) {
        $idCurso = $_POST['curso'];
        $pagina = $_POST['pagina'];
        $numRows = $_POST['rows'];
        $offset = $numRows * ($pagina - 1);
        require_once 'modulos/cursos/modelos/DiscusionModelo.php';
        $array = getDiscusiones($idCurso, $offset, $numRows);
        $discusiones = $array['discusiones'];
        foreach ($discusiones as $discusion) {
            printDiscusion($discusion);
        }
    } else {
        echo 'error -- datos no recibidos';
    }
}

function obtenerNumeroDiscusiones() {
    if (isset($_POST['curso'])) {
        $idCurso = $_POST['curso'];
        require_once 'modulos/cursos/modelos/DiscusionModelo.php';
        $array = getDiscusiones($idCurso, 0, 1);
        $res = array(
            "n" => $array['n']
        );
        $res = json_encode($res);
        echo $res;
    }
}

function votarDiscusion() {
    $res = false;
    $puedeVotar = true;
    if (isset($_POST['discusion']) && isset($_POST['delta'])) {
        $discusion = $_POST['discusion'];
        $delta = $_POST['delta'];
        $delta = intval($delta);
        if ($delta > 1)
            $delta = 1;
        if ($delta < -1)
            $delta = -1;
        require_once 'modulos/cursos/modelos/DiscusionModelo.php';
        $compensacion = $delta * usuarioPuedeVotar($discusion, $delta);
        if ($puedeVotar) {            
            if (actualizarVotacionDeDiscusion($discusion, $compensacion)) {
                $nuevaPuntuacion = getPuntuacionDiscusion($discusion);
                if (isset($nuevaPuntuacion)) {
                    guardarVotacionDiscusionSesion($discusion, $delta);
                    $res = true;
                    $msg = $nuevaPuntuacion;
                } else {
                    $msg = "error al obtener la nueva puntuación";
                }
            } else {
                $msg = "error al actualizar la base de datos";
            }
        } else {
            $res = true;
            $nuevaPuntuacion = getPuntuacionDiscusion($discusion);
            $msg = $nuevaPuntuacion;
        }
    } else {
        $msg = "datos no válidios";
    }
    $resultado = array(
        "res" => $res,
        "msg" => $msg
    );
    $resultado = json_encode($resultado);
    echo $resultado;
}

?>